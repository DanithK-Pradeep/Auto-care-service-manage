<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('services/(:segment)', 'Services::detail/$1');
$routes->get('book-service', 'Booking::index');
$routes->post('book-service', 'Booking::store');
$routes->get('/admin/login', 'AdminAuth::showLogin');
$routes->post('/admin/login-process', 'AdminAuth::loginProcess');

$routes->get('/admin/dashboard', 'AdminAuth::dashboard');
$routes->get('/admin/logout', 'AdminAuth::logout');


$routes->get('/admin/bookings', 'AdminBookings::index');
$routes->post('/admin/bookings/reject','AdminBookings::rejectBooking');
$routes->get('/admin/bookings/view/(:num)','AdminBookings::view/$1');

$routes->get('/admin/employees', 'AdminEmployees::dashboard');
$routes->get('/admin/employees/create', 'AdminEmployees::create');
$routes->post('/admin/employees/store', 'AdminEmployees::store');
$routes->get('/admin/employees/list', 'AdminEmployees::index');
$routes->get('/admin/employees/view/(:num)', 'AdminEmployees::view/$1');
$routes->post('/admin/employees/change-status/(:num)', 'AdminEmployees::changeStatus/$1');
$routes->get('/admin/employees/edit/(:num)', 'AdminEmployees::edit/$1');
$routes->post('/admin/employees/update/(:num)', 'AdminEmployees::update/$1');
$routes->get('/admin/employees/assign', 'AdminEmployees::assign');
$routes->post('/admin/employees/assign', 'AdminAssign::store');
$routes->get('/admin/employees/getEmployeeDetails/(:num)', 'AdminAssign::getEmployeeDetails/$1');








$routes->get('/admin/stations', 'AdminStations::index');
$routes->post('/admin/stations/store', 'AdminStations::store');
$routes->post('/admin/stations/status/(:num)', 'AdminStations::changeStatus/$1');



