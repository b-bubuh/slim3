<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 03/11/2016
 * Time: 09:36
 */

use Slim\Http\Request;
use Slim\Http\Response;

require '../vendor/autoload.php';

class DemoMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $response->write('<h1>Bienvenue</h1>');
        $middle = $next($request, $response);
        $response->write('<h1>Au revoir</h1>');
        return $middle;
    }
}

class Database
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query($sql)
    {
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
}

class PageController
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function salut(Request $request, Response $response, $args) {
        var_dump($this->container->db->query('SELECT * FROM posts'));
        return $response->write('Salut ' . $args['nom']);
    }
}

$app = new Slim\App();
$app->add(new DemoMiddleware());

$container = $app->getContainer();

$container['pdo'] = function () {
    $pdo = new PDO('mysql:dbname=slim3;host:localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
};

$container['db'] = function ($container) {
    return new Database($container->get('pdo'));
};

$app->get('/', function (Request $request, Response $response) {
    return $response->getBody()->write('Salut les gens');
});

$app->get('/salut/{nom}', 'PageController:salut');

$app->run();