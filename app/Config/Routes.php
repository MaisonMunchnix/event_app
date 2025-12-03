<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----------------------------
// Public Routes
// ----------------------------
$routes->get('/', 'EventController::index');

// Feedback
$routes->get('feedback/create/(:num)', 'FeedbackController::create/$1');
$routes->post('feedback/find', 'FeedbackController::findByEmail');
$routes->post('feedback/store', 'FeedbackController::store');

// Event registration
$routes->get('events/register/(:num)', 'EventController::register/$1');
$routes->post('events/store', 'EventController::store');
$routes->get('user/registrations', 'EventController::myRegistrations');

// Login & Logout
$routes->get('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');
$routes->post('/login/process', 'AuthController::loginProcess');
$routes->post('/register/account', 'AuthController::createAccount');
$routes->get('/logout', 'AuthController::logout');

// ----------------------------
// Admin Routes (Protected by Filter)
// ----------------------------
$routes->group('admin', ['filter' => 'auth'], function ($routes) {


    // Dashboard
    $routes->get('feedback', 'AdminController::feedback');
    $routes->get('dashboard', 'AdminController::dashboard');

    // Registrations
    $routes->get('registrations', 'AdminController::registrations');
    $routes->get('approve/(:num)', 'AdminController::approve/$1');

    // Events CRUD
    $routes->get('events', 'AdminController::events');
    $routes->get('events/create', 'AdminController::createEvent');
    $routes->post('events/store', 'AdminController::storeEvent');

    $routes->get('events/edit/(:num)', 'AdminController::editEvent/$1');
    $routes->get('events/view/(:num)', 'AdminController::viewEvent/$1');
    $routes->post('events/update/(:num)', 'AdminController::updateEvent/$1');
    $routes->get('events/delete/(:num)', 'AdminController::deleteEvent/$1');
});
