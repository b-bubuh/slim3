<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 23/11/2016
 * Time: 16:14
 */

namespace App\Controllers;

use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class PostController extends BaseController
{
    public function show(RequestInterface $request, ResponseInterface $response, $args)
    {
        $data = [];
        $post = Post::first($args['id']);
        if (isset($post)) {
            $data = [
                'post' => $post,
                'posts' => Post::getPosts(),
                'page' => ['title' => $post->name]
            ];
        }
        $this->render($response, 'pages/post-show.twig', $data);
    }

    public function newPost(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->render($response, 'pages/post-create.twig', ['posts' => Post::getPosts()]);
    }

    public function postNewPost(RequestInterface $request, ResponseInterface $response, $args)
    {
        $errors = [];

        Validator::notEmpty()->validate($request->getParam('title')) || $errors['title'] = "Le titre ne peut pas être vide";
        Validator::notEmpty()->validate($request->getParam('description')) || $errors['description'] = "Le description ne peut pas être vide";

        if (empty($errors)) {
            try {
                $post = new Post();
                $post->name = $request->getParam('title');
                $post->description = $request->getParam('description');
                $post->save();
                $this->flash('success', "L'article a été bien créé");
            } catch (\Exception $e) {
                $this->flash('error', "Impossible de créer l'article");
                return $this->redirect($response, 'new-post', 400);
            }
            return $this->redirect($response, 'home', 302);
        } else {
            $this->flash('error', "Certains champs n'on pas été validés");
            $this->flash('errors', $errors);
            return $this->redirect($response, 'new-post', 400);
        }
    }

    public function updatePost(RequestInterface $request, ResponseInterface $response, $args)
    {
        $errors = [];

        Validator::notEmpty()->validate($request->getParam('title')) || $errors['title'] = "Le titre ne peut pas être vide";
        Validator::notEmpty()->validate($request->getParam('description')) || $errors['description'] = "Le description ne peut pas être vide";

        if (empty($errors)) {
            try {
                $post = Post::first($args['id']);
                $post->name = $request->getParam('title');
                $post->description = $request->getParam('description');
                $post->save();
                $this->flash('success', "L'article a été bien modifié");
            } catch (\Exception $e) {
                $this->flash('error', "Impossible de créer l'article");
                return $this->redirect($response, 'show-post', 400);
            }
            return $this->redirect($response, 'home', 302);
        } else {
            $this->flash('error', "Certains champs n'on pas été validés");
            $this->flash('errors', $errors);
            return $this->redirect($response, 'new-post', 400);
        }
    }

}