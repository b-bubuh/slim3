<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 03/11/2016
 * Time: 10:50
 */

namespace App\Controllers;

use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class PageController extends BaseController
{
    /**
     * The homepage
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function home(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->render($response, 'pages/home.twig', [
            'posts' => Post::getPosts(),
            'page' => ['title' => 'Home', 'name' => 'home']
        ]);
    }

    /**
     * Render view for /contact
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function getContact(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->render($response, 'pages/contact.twig', [
            'posts' => Post::getPosts(),
            'page' => ['title' => 'Formulaire de contact', 'name' => 'contact']
        ]);
    }

    /**
     * Handle the POST request to the /contact url
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return static
     */
    public function postContact(RequestInterface $request, ResponseInterface $response)
    {
        $errors = [];

        Validator::email()->validate($request->getParam('email')) || $errors['email'] = "Votre email n'est pas valide";
        Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = "Votre nom est vide";
        Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = "Votre message est vide";
        Validator::date()->validate($request->getParam('date')) || $errors['date'] = "La date est invalide";

        if (empty($errors)) {
            try {
                $message = \Swift_Message::newInstance('Message de contact')
                    ->setFrom([$request->getParam('email') => $request->getParam('name')])
                    ->setTo('contact@grafikart.fr')
                    ->setBody('Un email vous a été envoyé: ' . $request->getParam('content'));
                $this->mailer()->send($message);
                $this->flash('success', "Mail envoyé");
            } catch (\Exception $e) {
                $this->flash('error', "Impossible d'envoyer le mail");
                return $this->redirect($response, 'contact', 400);
            }
            return $this->redirect($response, 'contact', 302);
        } else {
            //$this->flash("Certains champs n'on pas été validés", 'error');
            $this->flash('errors', $errors);
            return $this->redirect($response, 'contact', 400);
        }
    }
}