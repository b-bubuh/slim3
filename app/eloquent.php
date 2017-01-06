<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 22/11/2016
 * Time: 16:25
 */


// Database information

$settings = array(
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'slim3',
    'username' => 'root',
    'password' => '',
    'collation' => 'utf8_general_ci',
    'prefix' => ''
);

// Bootstrap Eloquent ORM
$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory(new \Illuminate\Container\Container());
$resolver = new \Illuminate\Database\ConnectionResolver();
$connection = $connFactory->make($settings);
$resolver->addConnection('default', $connection);
$resolver->setDefaultConnection('default');
\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
