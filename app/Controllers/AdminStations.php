<?php

namespace App\Controllers;

use App\Models\StationModel;
use App\Models\StationTypeModel;

class AdminStations extends BaseController
{
    public function index()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $stationModel = new StationModel();
        $typeModel    = new StationTypeModel();

        $stations = $stationModel
            ->select('stations.*, station_types.name as type_name')
            ->join('station_types', 'station_types.id = stations.station_type_id')
            ->orderBy('stations.id', 'DESC')
            ->findAll();

        return view('admin/stations/index', [
            'stations' => $stations,
            'types'    => $typeModel->findAll()
        ]);
    }

    public function store()
    {
        $stationModel = new StationModel();

        $stationModel->insert([
            'service_center_id' => 1, // later make dynamic
            'station_type_id'   => $this->request->getPost('station_type_id'),
            'name'              => $this->request->getPost('name'),
            'bay_no'            => $this->request->getPost('bay_no'),
            'capacity'          => $this->request->getPost('capacity'),
            'status'            => 'active'
        ]);

        return redirect()->back()->with('success', 'Station added successfully');
    }

    public function changeStatus($id)
    {
        $model = new StationModel();
        $station = $model->find($id);

        if (!$station) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Station not found'
                ], 404);
        }

        $nextStatus = match ($station['status']) {
            'active' => 'maintenance',
            'maintenance' => 'inactive',
            default => 'active'
        };

        $updated = $model->update($id, ['status' => $nextStatus]);

        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Station updated successfully!',
                'station_id' => $id,
                'new_status' => $nextStatus
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update station status'
        ], 500);
    }
}
