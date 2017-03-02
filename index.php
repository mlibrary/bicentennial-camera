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

    $sql = "SELECT title, image_href, record_href, ( SELECT COUNT(*) FROM bc_story_item b WHERE b.historical_item_id = a.id AND b.image_filename IS NULL) AS number_of_stories, ( SELECT COUNT(*) FROM bc_story_item c WHERE c.historical_item_id = a.id AND c.image_filename IS NOT NULL) AS number_of_pictures FROM bc_historical_item a";
    $data = $app['db']->fetchAssoc($sql);

    /*
      Include array of historical images:

      - title
      - image_href
      - record_href
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

$app['debug'] = true;

$app->run();
