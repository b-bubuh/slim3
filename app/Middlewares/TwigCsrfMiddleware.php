<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 08/11/2016
 * Time: 14:50
 */

namespace App\Middlewares;

use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;

class TwigCsrfMiddleware
{
    /**
     * @var Guard
     */
    private $guard;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig, Guard $guard)
    {
        $this->guard = $guard;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $csrf = $this->guard;
        $this->twig->addFunction(new \Twig_SimpleFunction('csrf', function () use ($request, $csrf) {
            $nameKey = $csrf->getTokenNameKey();
            $valueKey = $csrf->getTokenValueKey();
            $name = $request->getAttribute($nameKey);
            $value = $request->getAttribute($valueKey);
            return "<input type=\"hidden\" name=\"$nameKey\" value=\"$name\"><input type=\"hidden\" name=\"$valueKey\" value=\"$value\">";
        }, ['is_safe' => ['html']]));
        return $next($request, $response);
    }

}