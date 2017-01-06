<?php

session_start();

/**
 * Date: 03/11/2016
 * Time: 09:36
 */

// ---------------------------------------------------------------------------- //
// ----- Namespaces  ---------------------------------------------------------- //
// ---------------------------------------------------------------------------- //
use App\Controllers\ApiController;
use App\Controllers\PageController;
use App\Controllers\PostController;
use App\Helpers\TwigBaseExtension;
use App\Middlewares\FlashMiddleware;
use App\Middlewares\OldValueMiddleware;
use App\Middlewares\TwigCsrfMiddleware;
use Slim\Http\Request;
use Slim\Http\Response;

// ---------------------------------------------------------------------------- //
// ----- Initialisation  ------------------------------------------------------ //
// ---------------------------------------------------------------------------- //
require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new Slim\App($config);

require '../app/container.php';
require '../app/eloquent.php';

// ---------------------------------------------------------------------------- //
// ----- Middlewares  --------------------------------------------------------- //
// ---------------------------------------------------------------------------- //
$app->add(new FlashMiddleware($container->view->getEnvironment()));
$app->add(new OldValueMiddleware($container->view->getEnvironment()));
$app->add(new TwigCsrfMiddleware($container->view->getEnvironment(), $container->csrf));
$app->add($container->csrf);

// ---------------------------------------------------------------------------- //
// ----- Routes  -------------------------------------------------------------- //
// ---------------------------------------------------------------------------- //
$app->get('/', PageController::class . ':home')->setName('home');
$app->get('/contact', PageController::class . ':getContact')->setName('contact');
$app->get('/post/{id}', PostController::class . ':show')->setName('show-post');
$app->post('/update-post/{id}', PostController::class . ':updatePost');
$app->get('/new-post', PostController::class . ':newPost')->setName('new-post');
$app->post('/create-post', PostController::class . ':postNewPost');
$app->post('/contact', PageController::class . ':postContact');

$app->group('/api', function () {
    $this->get('/users/{id:[0-9]+}', ApiController::class . ':getUsers');
    $this->get('/posts', ApiController::class . ':getPosts');
    $this->get('/post/{id:[0-9]+}', ApiController::class . ':getPost');
    $this->post('/post', ApiController::class . ':postPost');
});

$app->run();

