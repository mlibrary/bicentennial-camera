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
    $photo = '';
    $stories = '';

    /*
      Include:

      This is a dynamic route to a details of a historical photos

      pass in:
      - title
      - image_url
      - coordinates (location)
      - stories: [] (array of stories include: image_url and text)
    */

    return $app['twig']->render('layouts/photo.twig', array(
      'photo' => $photo,
      'stories' => $stories
    ));
});

$app->get('/', function(Silex\Application $app)  {
    $photos = '';

    /*
      Include array of historical images:

      - title
      - image_url
      - detail_view_url
      - coordinates
      - number_of_stories
      - number_of_pictures
    */

    return $app['twig']->render('layouts/browse.twig', array(
      'photos' => $photos,
    ));
});


// totally hacking
$app->get('/camera', function() use ($app) {
    return $app['twig']->render('camera/form.twig');
    /*
      Creates a story with image and text associated with a historical image

      Save:
        - image (optional)
        - quote_text (optional)

      Note: requires at least one of the two^
    */
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
