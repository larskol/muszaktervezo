<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Errors::notFound');
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->get('/logout', 'Login::logout');
$routes->post('/auth', 'Login::auth');
$routes->get('/not-found', 'Errors::notFound');
$routes->get('/forbidden', 'Errors::forbidden');
$routes->get('/profile', 'Profile::index');
$routes->post('/profile/save', 'Profile::save');

/**
 * Admin
 */
$routes->get('/departments', 'Departments::index', ['filter' => 'auth:admin']);
$routes->get('/departments/create', 'Departments::create', ['filter' => 'auth:admin']);
$routes->get('/departments/edit/(:num)', 'Departments::edit/$1', ['filter' => 'auth:admin']);
$routes->post('/departments/save', 'Departments::save', ['filter' => 'auth:admin']);
$routes->post('/departments/delete', 'Departments::delete', ['filter' => 'auth:admin']);

$routes->get('/knowledge', 'KnowledgeController::index', ['filter' => 'auth:admin']);
$routes->get('/knowledge/create', 'KnowledgeController::create', ['filter' => 'auth:admin']);
$routes->get('/knowledge/edit/(:num)', 'KnowledgeController::edit/$1', ['filter' => 'auth:admin']);
$routes->post('/knowledge/save', 'KnowledgeController::save', ['filter' => 'auth:admin']);
$routes->post('/knowledge/delete', 'KnowledgeController::delete', ['filter' => 'auth:admin']);

$routes->get('/knowledge-levels', 'KnowledgeLevels::index', ['filter' => 'auth:admin']);
$routes->get('/knowledge-levels/create', 'KnowledgeLevels::create', ['filter' => 'auth:admin']);
$routes->get('/knowledge-levels/edit/(:num)', 'KnowledgeLevels::edit/$1', ['filter' => 'auth:admin']);
$routes->post('/knowledge-levels/save', 'KnowledgeLevels::save', ['filter' => 'auth:admin']);
$routes->post('/knowledge-levels/delete', 'KnowledgeLevels::delete', ['filter' => 'auth:admin']);

$routes->get('/shifts', 'Shifts::index', ['filter' => 'auth:admin']);
$routes->get('/shifts/create', 'Shifts::create', ['filter' => 'auth:admin']);
$routes->get('/shifts/edit/(:num)', 'Shifts::edit/$1', ['filter' => 'auth:admin']);
$routes->post('/shifts/save', 'Shifts::save', ['filter' => 'auth:admin']);
$routes->post('/shifts/delete', 'Shifts::delete', ['filter' => 'auth:admin']);

$routes->get('/restrictions', 'Restrictions::index', ['filter' => 'auth:admin']);
$routes->get('/restrictions/edit/(:num)', 'Restrictions::edit/$1', ['filter' => 'auth:admin']);
$routes->post('/restrictions/save', 'Restrictions::save', ['filter' => 'auth:admin']);

$routes->get('/users', 'Users::index', ['filter' => 'auth:admin']);
$routes->get('/users/create', 'Users::create', ['filter' => 'auth:admin']);
$routes->get('/users/edit/(:num)', 'Users::edit/$1', ['filter' => 'auth:admin']);
$routes->get('/users/upload', 'Users::upload', ['filter' => 'auth:admin']);
$routes->get('/users/update-points', 'Users::updateBonusPoints', ['filter' => 'auth:admin']);
$routes->post('/users/save', 'Users::save', ['filter' => 'auth:admin']);
$routes->post('/users/delete', 'Users::delete', ['filter' => 'auth:admin']);
$routes->post('/users/upload-users', 'Users::addUsersCSV', ['filter' => 'auth:admin']);
$routes->post('/users/upload-points', 'Users::addBonusPointsCSV', ['filter' => 'auth:admin']);

$routes->get('/download/csv/user-sample', 'Downloader::admin/user-sample', ['filter' => 'auth:admin']);
$routes->get('/download/csv/user-example', 'Downloader::admin/user-example', ['filter' => 'auth:admin']);
$routes->get('/download/csv/point-sample', 'Downloader::admin/point-sample', ['filter' => 'auth:admin']);
$routes->get('/download/csv/point-example', 'Downloader::admin/point-example', ['filter' => 'auth:admin']);

$routes->get('/schedule/admin', 'Schedule::admin', ['filter' => 'auth:admin']);

/**
 * Részleg admin
 */
$routes->get('/department-users', 'DepartmentUsers::index', ['filter' => 'auth:department_admin']);
$routes->get('/department-users/create', 'DepartmentUsers::create', ['filter' => 'auth:department_admin']);
$routes->get('/department-users/edit/(:num)', 'DepartmentUsers::edit/$1', ['filter' => 'auth:department_admin']);
$routes->get('/department-users/upload', 'DepartmentUsers::upload', ['filter' => 'auth:department_admin']);
$routes->get('/department-users/update-points', 'DepartmentUsers::updateBonusPoints', ['filter' => 'auth:department_admin']);
$routes->post('/department-users/save', 'DepartmentUsers::save', ['filter' => 'auth:department_admin']);
$routes->post('/department-users/delete', 'DepartmentUsers::delete', ['filter' => 'auth:department_admin']);
$routes->post('/department-users/upload-users', 'DepartmentUsers::addUsersCSV', ['filter' => 'auth:department_admin']);
$routes->post('/department-users/upload-points', 'DepartmentUsers::addBonusPointsCSV', ['filter' => 'auth:department_admin']);
$routes->post('/department-switch', 'DepartmentUsers::switchDepartment', ['filter' => 'auth:department_admin']);

$routes->get('/download/csv-dep/user-sample', 'Downloader::departmentAdmin/user-sample', ['filter' => 'auth:department_admin']);
$routes->get('/download/csv-dep/user-example', 'Downloader::departmentAdmin/user-example', ['filter' => 'auth:department_admin']);
$routes->get('/download/csv-dep/point-sample', 'Downloader::departmentAdmin/point-sample', ['filter' => 'auth:department_admin']);
$routes->get('/download/csv-dep/point-example', 'Downloader::departmentAdmin/point-example', ['filter' => 'auth:department_admin']);
$routes->get('/download/csv-dep/capacity-demand-sample', 'Downloader::departmentAdmin/capacity-demand-sample', ['filter' => 'auth:department_admin']);
$routes->get('/download/csv-dep/capacity-demand-example', 'Downloader::departmentAdmin/capacity-demand-example', ['filter' => 'auth:department_admin']);

$routes->get('/capacity-demands', 'CapacityDemands::index', ['filter' => 'auth:department_admin']);
$routes->get('/capacity-demands/create', 'CapacityDemands::create', ['filter' => 'auth:department_admin']);
$routes->get('/capacity-demands/edit/(:num)', 'CapacityDemands::edit/$1', ['filter' => 'auth:department_admin']);
$routes->get('/capacity-demands/upload', 'CapacityDemands::upload', ['filter' => 'auth:department_admin']);
$routes->get('/capacity-demands/details', 'CapacityDemands::details', ['filter' => 'auth:department_admin']);
$routes->post('/capacity-demands/save', 'CapacityDemands::save', ['filter' => 'auth:department_admin']);
$routes->post('/capacity-demands/delete', 'CapacityDemands::delete', ['filter' => 'auth:department_admin']);
$routes->post('/capacity-demands/upload-file', 'CapacityDemands::uploadFile', ['filter' => 'auth:department_admin']);

$routes->get('/schedule', 'Schedule::index', ['filter' => 'auth:department_admin']);
$routes->post('/schedule/assign', 'Schedule::assign', ['filter' => 'auth:department_admin']);

/**
 * Felhasználó
 */
$routes->get('/skills', 'Skills::index', ['filter' => 'auth:user']);
$routes->post('/skills/save', 'Skills::save', ['filter' => 'auth:user']);
$routes->post('/skills/delete', 'Skills::delete', ['filter' => 'auth:user']);

$routes->get('/my-restrictions', 'UserRestrictions::index', ['filter' => 'auth:user']);
$routes->get('/my-restrictions/history', 'UserRestrictions::previousRestrictions', ['filter' => 'auth:user']);
$routes->post('/my-restrictions/save', 'UserRestrictions::save', ['filter' => 'auth:user']);
$routes->post('/my-restrictions/delete', 'UserRestrictions::delete', ['filter' => 'auth:user']);
$routes->post('/my-restrictions/get-input', 'UserRestrictions::getInput', ['filter' => 'auth:user']);

$routes->get('/schedule/user', 'Schedule::user', ['filter' => 'auth:user']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
