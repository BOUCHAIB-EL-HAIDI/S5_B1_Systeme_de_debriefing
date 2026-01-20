<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$router = new Router();

// Auth Routes
$router->get('/login', "AuthController@showLogin");
$router->post('/login', "AuthController@login");
$router->get('/logout', "AuthController@logout");

// Admin Routes
$router->get('/admin/dashboard', "AdminController@dashboard");
$router->get('/admin/users', "AdminController@users");
$router->get('/admin/classes', "AdminController@classes");
$router->get('/admin/competences', "AdminController@competences");

// Teacher Routes
$router->get('/teacher/dashboard', "TeacherController@dashboard");
$router->get('/teacher/debriefing', "TeacherController@debriefing");
$router->get('/teacher/briefs', "TeacherController@briefs");
$router->get('/teacher/progression', "TeacherController@progression");

// Student Routes
$router->get('/student/dashboard', "StudentController@dashboard");
$router->get('/student/briefs', "StudentController@briefs");
$router->get('/student/progression', "StudentController@progression");

// Home fallback
$router->get('/', "AuthController@showLogin");

$router->dispatch();
