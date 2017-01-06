<?php

/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 22/11/2016
 * Time: 15:11
 */

namespace App\Controllers;

use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ApiController extends BaseController
{
    public function getUsers(RequestInterface $request, ResponseInterface $response, $args)
    {
        if (isset($args['id'])) {
            $data = $this->model->getRecords((int)$request->getAttribute('id'));
        } else {
            $data = $this->model->getRecords(21);
        }
        return $response->withStatus(200)->withJSON($data);
    }

    public function getPosts(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $response->withStatus(200)->withJSON(Post::all());
    }

    public function getPost(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $response->withStatus(200)->withJSON(Post::find($args['id']));
    }

    public function postPost(RequestInterface $request, ResponseInterface $response)
    {
        return $response->withStatus(200)->withJSON($request->getParams());
    }
}