<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProgrammerZamanNow\Belajar\PHP\MVC\App\Router;
use ProgrammerZamanNow\Belajar\PHP\MVC\config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\HomeController;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\UserController;
use ProgrammerZamanNow\Belajar\PHP\MVC\Middleware\MustLoginMiddleware;
use ProgrammerZamanNow\Belajar\PHP\MVC\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');

// Home Controller
Router::add('GET', '/', HomeController::class, 'index',[]);

//User Controller
Router::add('GET', '/users/register', UserController::class, 'register',[MustLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister',[MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login',[MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin',[MustLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout',[MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile',[MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postupdateProfile',[MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword',[MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postupdateProfile',[MustLoginMiddleware::class]);
Router::run();