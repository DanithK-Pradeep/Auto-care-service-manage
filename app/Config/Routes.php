<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- Public Routes ---
$routes->get('/', 'Home::index');
$routes->get('services/(:segment)', 'Services::detail/$1');
$routes->get('book-service', 'Booking::index');
$routes->post('book-service', 'Booking::store');

// --- Admin Authentication ---
$routes->get('/admin/login', 'AdminAuth::showLogin');
$routes->post('/admin/login-process', 'AdminAuth::loginProcess');
$routes->get('/admin/dashboard', 'AdminAuth::dashboard');
$routes->get('/admin/logout', 'AdminAuth::logout');

// --- Admin Booking Management ---
$routes->get('/admin/bookings', 'AdminBookings::index');
$routes->post('/admin/bookings/reject', 'AdminBookings::rejectBooking');
$routes->get('/admin/bookings/view/(:num)', 'AdminBookings::view/$1');
$routes->post('/admin/bookings/approve', 'AdminBookings::approve');
$routes->get('/admin/bookings/approve/(:num)', 'AdminBookings::approve/$1');

// --- Admin Employee Management ---
$routes->get('/admin/employees', 'AdminEmployees::dashboard');
$routes->get('/admin/employees/create', 'AdminEmployees::create');
$routes->post('/admin/employees/store', 'AdminEmployees::store');
$routes->get('/admin/employees/list', 'AdminEmployees::index');
$routes->get('/admin/employees/view/(:num)', 'AdminEmployees::view/$1');
$routes->post('/admin/employees/change-status/(:num)', 'AdminEmployees::changeStatus/$1');
$routes->get('/admin/employees/edit/(:num)', 'AdminEmployees::edit/$1');
$routes->post('/admin/employees/update/(:num)', 'AdminEmployees::update/$1');

// --- Admin Assignment & Stations ---
$routes->get('/admin/employees/assign', 'AdminEmployees::assign');
$routes->post('/admin/employees/assign', 'AdminAssign::store');
$routes->get('/admin/employees/getEmployeeDetails/(:num)', 'AdminAssign::getEmployeeDetails/$1');
$routes->get('/admin/stations', 'AdminStations::index');
$routes->post('/admin/stations/store', 'AdminStations::store');
$routes->post('/admin/stations/status/(:num)', 'AdminStations::changeStatus/$1');
$routes->get('/admin/stations/(:num)/employees', 'AdminStations::employees/$1');

// --- Employee Authentication ---
$routes->get('employee/login', 'EmployeeAuth::login');
$routes->post('employee/login', 'EmployeeAuth::attemptLogin');
$routes->get('employee/logout', 'EmployeeAuth::logout');

// --- Employee Authenticated Group ---
$routes->group('employee', ['filter' => 'employeeAuth'], function ($routes) {
    // Dashboard & Details
    $routes->get('dashboard', 'EmployeeDashboard::index');
    $routes->get('empdetail', 'EmployeeDashboard::details');
    
    // Booking Operations
    $routes->get('bookings', 'EmployeeDashboard::bookings');
    $routes->post('approve', 'EmployeeDashboard::approve');
    $routes->get('getBookingDetails/(:num)', 'EmployeeDashboard::getBookingDetails/$1');
    $routes->get('bookings/view-data/(:num)', 'EmployeeDashboard::viewBookingData/$1');
    
    // Workflow / Services
    $routes->get('services', 'EmployeeDashboard::services');
    $routes->post('process/start', 'EmployeeDashboard::startProcess');
    $routes->post('process/finish', 'EmployeeDashboard::finishProcess');
    $routes->post('jobstep/done', 'EmployeeDashboard::doneJobStep');
    $routes->post('jobstep/skip', 'EmployeeDashboard::skipJobStep');
    $routes->post('services/stations', 'EmployeeDashboard::loadstations');
    $routes->get('services/employees', 'EmployeeDashboard::loademployees');
    $routes->post('assign_next', 'EmployeeDashboard::assignNext');
    
    // Supervisor Role Specific
    $routes->get('supervisor', 'SupervisorController::dashboard');
    $routes->post('supervisor/release', 'SupervisorController::releaseVehicle');
    $routes->get('supervisor/history', 'SupervisorController::history');
    
    // Attendance Management (Moved inside group for security)
    $routes->get('attendance', 'EmployeeAttendance::index');
    $routes->post('attendance/checkIn', 'EmployeeAttendance::checkIn');
    $routes->get('attendance/checkIn', 'EmployeeAttendance::checkIn'); 
    $routes->post('attendance/checkOut', 'EmployeeAttendance::checkOut');
    $routes->get('attendance/checkOut', 'EmployeeAttendance::checkOut'); 
    $routes->post('attendance/applyLeave', 'EmployeeAttendance::applyLeave');
    $routes->get('attendance/getFullHistory', 'EmployeeAttendance::fetchFullHistory');

    // Spare Parts Management
    $routes->group('spare', function ($routes) {
        $routes->get('categories', 'SparePartsController::categories');
        $routes->get('items', 'SparePartsController::items');
        $routes->get('usage', 'SparePartsController::usage');
        $routes->post('use', 'SparePartsController::use');
        $routes->post('use/remove', 'SparePartsController::removeUsage');
    });
});