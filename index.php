<?php

use Symfony\Component\HttpFoundation\Request;

$app = require __DIR__ . '/bootstrap.php';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// $app['upload.controller'] = $app->share(function() use ($app) {
//     return new DLXS\Controller\UploadController();
// });

$app->get('/photo', function(Silex\Application $app)  {
    //$data = $app['db']->fetchAssoc("SELECT * FROM bc_test");

    $data = 'Hey backend folks, data here';

    /*
      Include:
    */

    return $app['twig']->render('layouts/photo.twig', array(
      '' => $data,
    ));
});

$app->get('/', function(Silex\Application $app)  {
    //$data = $app['db']->fetchAssoc("SELECT * FROM bc_test");

    $data = 'Hey backend folks, data here';

    /*
      Include:

      - title
      - image_url
      - detail_view_url
      - number_of_stories
      - number_of_pictures
    */

    return $app['twig']->render('layouts/browse.twig', array(
      'photos' => $data,
    ));
});


// totally hacking
$app->get('/camera', function() use ($app) {
    return $app['twig']->render('camera/form.twig');
});

$app->post('/upload', 'App\Controller\UploadController::saveAction');

// $app->post('/upload', function(Request $request) use ($app) {
//     $data_js = $request->get('imgBase64');
//     $data_to_php = explode(',', $data_js);
//     $data = base64_decode($data_to_php[1]);
//     file_put_contents(__DIR__ . '/files/test.png', $data);
//     return "OK";
// });

$app['debug'] = true;

$app->run();
