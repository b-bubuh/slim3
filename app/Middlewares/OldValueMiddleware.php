<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 08/11/2016
 * Time: 14:09
 */

namespace App\Middlewares;


use Slim\Http\Request;
use Slim\Http\Response;

class OldValueMiddleware
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
        $this->environment->addGlobal('old', isset($_SESSION['old']) ? $_SESSION['old'] : []);
        if (isset($_SESSION['old'])) {
            unset($_SESSION['old']);
        }
        $response = $next($request, $response);
        if ($response->getStatusCode() === 400) {
            $_SESSION['old'] = $request->getParams();
        }
        return $response;
    }
}