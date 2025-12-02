<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----------------------------
// Public Routes
// ----------------------------
$routes->get('/', 'EventController::index');

// Event registration
$routes->get('events/register/(:num)', 'EventController::register/$1');
$routes->post('events/store', 'EventController::store');

// Login & Logout
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::loginProcess');
$routes->get('/logout', 'AuthController::logout');

// ----------------------------
// Admin Routes (Protected by Filter)
// ----------------------------
$routes->group('admin', ['filter' => 'auth'], function($routes) {

    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');

    // Registrations
    $routes->get('registrations', 'AdminController::registrations');
    $routes->get('approve/(:num)', 'AdminController::approve/$1');

    // Events CRUD
    $routes->get('events', 'AdminController::events');
    $routes->get('events/create', 'AdminController::createEvent');
    $routes->post('events/store', 'AdminController::storeEvent');

    $routes->get('events/edit/(:num)', 'AdminController::editEvent/$1');
    $routes->post('events/update/(:num)', 'AdminController::updateEvent/$1');
    $routes->get('events/delete/(:num)', 'AdminController::deleteEvent/$1');
});
