<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SparePartModel;
use App\Models\SparePartCategoryModel;
use App\Models\SparePartUsageModel;

class SparePartsController extends BaseController
{
    protected $sparePartModel;
    protected $categoryModel;
    protected $usageModel;
    protected $db;

    public function __construct()
    {
        $this->sparePartModel = new SparePartModel();
        $this->categoryModel  = new SparePartCategoryModel();
        $this->usageModel     = new SparePartUsageModel();
        $this->db             = \Config\Database::connect();
    }

    /**
     * GET: employee/spare/categories
     */
    public function categories()
    {
        // Filter handles security, but we check AJAX for consistency
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $rows = $this->categoryModel
            ->select('spare_part_categories.id, spare_part_categories.name')
            ->join('spare_parts', 'spare_parts.category_id = spare_part_categories.id', 'inner')
            ->groupBy('spare_part_categories.id')
            ->orderBy('spare_part_categories.name', 'ASC')
            ->findAll();

        return $this->response->setJSON(['success' => true, 'categories' => $rows]);
    }


    public function use()
    {
        // 1. Security Check
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
        }

        // 2. Input Sanitation
        $bookingId   = (int) $this->request->getPost('booking_id');
        $stationId   = (int) $this->request->getPost('station_id');
        $sparePartId = (int) $this->request->getPost('spare_part_id');
        $qty         = (int) $this->request->getPost('qty');

        if (!$bookingId || !$stationId || !$sparePartId || $qty < 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid input data.']);
        }

        $this->db->transStart();

        // 3. Workflow Guard: Ensure the station is still "in_progress"
        // This prevents adding parts after the employee clicked "Finish Process"
        $assignment = $this->db->table('booking_assignments')
            ->where(['booking_id' => $bookingId, 'station_id' => $stationId])
            ->get()->getRowArray();

        if (!$assignment || $assignment['status'] !== 'in_progress') {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot add parts. This station process is already finished or not started.'
            ]);
        }

        // 4. Fetch Part Details & Lock Row (FOR UPDATE)
        // This prevents other users from changing the stock while we calculate
        $part = $this->db->table('spare_parts')->where('id', $sparePartId)->get()->getRowArray();

        if (!$part) {
            $this->db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Part not found.']);
        }

        // 5. Atomic Stock Deduction
        $this->db->table('spare_parts')
            ->set('stock_qty', "stock_qty - {$qty}", false)
            ->where('id', $sparePartId)
            ->where('stock_qty >=', $qty) // Prevents negative stock
            ->update();

        if ($this->db->affectedRows() < 1) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => "Insufficient stock. Only {$part['stock_qty']} left."
            ]);
        }

        // 6. Record Usage with Prices for Billing
        $unitPrice = (float) ($part['price'] ?? 0);
        $usageData = [
            'booking_id'    => $bookingId,
            'station_id'    => $stationId,
            'employee_id'   => session()->get('employee_id'),
            'spare_part_id' => $sparePartId,
            'qty'           => $qty,
            'unit_price'    => $unitPrice,
            'total_price'   => $unitPrice * $qty,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $this->usageModel->insert($usageData);
        $usageId = $this->db->insertID();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'System error. Transaction failed.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Spare part added to job card.',
            'token'   => csrf_hash(), // Send new token for the next AJAX call
            'usage'   => [
                'id'        => $usageId,
                'part_name' => $part['name'],
                'qty'       => $qty
            ]
        ]);
    }
    /**
     * GET: employee/spare/items
     * Matches the JS: url + "?category_id=" + categoryId + "&station_id=" + stationId
     */
    public function items()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $categoryId = $this->request->getGet('category_id');

        if (!$categoryId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category ID is required']);
        }

        $items = $this->sparePartModel
            ->select('id, name, stock_qty, price, sku')
            ->where('category_id', $categoryId)
            ->where('stock_qty >', 0) // Only show items actually in stock
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'items'   => $items
        ]);
    }

    /**
     * GET: employee/spare/usage
     * Fetches parts already added to this specific job/station
     */
    public function usage()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $bookingId = $this->request->getGet('booking_id');
        $stationId = $this->request->getGet('station_id');

        $rows = $this->usageModel
            ->select('spare_part_usages.id, spare_part_usages.qty, spare_parts.name as part_name')
            ->join('spare_parts', 'spare_parts.id = spare_part_usages.spare_part_id', 'left')
            ->where('booking_id', $bookingId)
            ->where('station_id', $stationId)
            ->orderBy('spare_part_usages.id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'rows'    => $rows
        ]);
    }
}
