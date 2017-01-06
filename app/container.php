<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 03/11/2016
 * Time: 10:58
 */


// Get container
use App\Helpers\TwigBaseExtension;
$container = $app->getContainer();

$container['debug'] = function () {
    return true;
};

$container['csrf'] = function () {
    $guard = new Slim\Csrf\Guard();
    $guard->setFailureCallable(function (\Slim\Http\Request $request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};


// Register component on container
$container['view'] = function ($container) {

    $dir = dirname(__DIR__);

    $view = new \Slim\Views\Twig($dir . '/app/Views',
        [
            'cache' => $container->debug ? false : $dir . '/tmp/cache',
            'debug' => $container->debug
        ]
    );

    $view->addExtension(new TwigBaseExtension());
    $view->addExtension(new Twig_Extensions_Extension_Text());
    if ($container->debug) {
        $view->addExtension(new Twig_Extension_Debug());
    }
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));


    return $view;
};

$container['mailer'] = function () {
    $transport = Swift_SmtpTransport::newInstance('localhost', 1025);
    return Swift_Mailer::newInstance($transport);
};
