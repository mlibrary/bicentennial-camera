<?php

$app = require __DIR__ . '/bootstrap.php';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->get('/info', function(Silex\Application $app)  {
    $output = "Hello, world";
    return $output;
});

$app['debug'] = true;

$app->run();