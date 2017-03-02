<?php
date_default_timezone_set("America/Detroit");
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/vendor/autoload.php';
$loader->add('App', __DIR__);

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$mysql_host = 'mysql-quod';
if ( $_SERVER['HTTP_HOST'] == 'beta1.quod.lib.umich.edu' ) {
  $mysql_host = 'mysql-quod-dev';
  $_SERVER['DLXSDATAROOT'] = '/l1/dev/beta1';
}
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'dlxs',
        'host' => $mysql_host,
        'user' => 'dlxs',
        'password' => 'getyer0wn'
    )
));

return $app;
