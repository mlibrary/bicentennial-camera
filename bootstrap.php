<?php
date_default_timezone_set("America/Detroit");
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/vendor/autoload.php';
$loader->add('App', __DIR__);

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$config = parse_ini_file(__DIR__ .'/vendor/database.ini');

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => $config['dbname'],
        'host' => $config['host'],
        'user' => $config['user'],
        'password' => array_key_exists('password', $config) ? $config['password'] : null
    )
));

return $app;
