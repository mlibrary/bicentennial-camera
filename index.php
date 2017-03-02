<?php

$app = require __DIR__ . '/bootstrap.php';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->get('/info', function(Silex\Application $app)  {
    $output = "<p>Hello, world</p>";
    $data = $app['db']->fetchAssoc("SELECT * FROM bc_test");
    $output .= "<pre>" . print_r($data, true) . "</pre>";
    return $output;
});

$app['debug'] = true;

$app->run();
