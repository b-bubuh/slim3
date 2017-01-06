<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 08/11/2016
 * Time: 12:02
 */

namespace App\Middlewares;


use Slim\Http\Request;
use Slim\Http\Response;

class FlashMiddleware
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function __construct(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $this->environment->addGlobal('flash', isset($_SESSION['flash']) ? $_SESSION['flash'] : []);
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
        return $next($request, $response);
    }
}