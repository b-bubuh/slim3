<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 03/11/2016
 * Time: 16:43
 */

namespace App\Controllers;

use App\Models\BaseModel;
use Psr\Http\Message\ResponseInterface;

class BaseController
{
    private $container;
    protected $model;

    public function __construct($container)
    {
        $this->container = $container;
        $this->model = new BaseModel();
    }

    public function render(ResponseInterface $response, $file, $params = [])
    {
        $this->container->view->render($response, $file, $params);
    }

    public function mailer(): \Swift_Mailer
    {
        return $this->container->mailer;
    }

    public function flash($type, $message)
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        return ($_SESSION['flash'][$type] = $message);
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    public function redirect(ResponseInterface $response, $name, $status = 302)
    {
        return $response->withStatus($status)->withHeader('Location', $this->router->pathFor($name));
    }
}