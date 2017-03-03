<?php

use Symfony\Component\HttpFoundation\Request;

$app = require __DIR__ . '/bootstrap.php';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// $app['upload.controller'] = $app->share(function() use ($app) {
//     return new DLXS\Controller\UploadController();
// });

$app->get('/photo/{id}', function(Silex\Application $app, $id)  {

    $photo = $app['db']->fetchAssoc("SELECT id, title, image_href, date, record_href, loc_long, loc_lat FROM bc_historical_item WHERE id = ?", array($id));
    $stories = $app['db']->fetchAll("SELECT id, image_filename, description, date_added FROM bc_story_item WHERE historical_item_id = ?", array($photo['id']));

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

    $sql = "SELECT id, title, image_href, record_href, ( SELECT COUNT(*) FROM bc_story_item b WHERE b.historical_item_id = a.id AND b.image_filename IS NULL) AS number_of_stories, ( SELECT COUNT(*) FROM bc_story_item c WHERE c.historical_item_id = a.id AND c.image_filename IS NOT NULL) AS number_of_pictures FROM bc_historical_item a";
    $photos = $app['db']->fetchAll($sql);

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
$app->get('/camera/{id}', function($id) use ($app) {
    $photo = $app['db']->fetchAssoc("SELECT id, title, image_href, record_href, loc_long, loc_lat FROM bc_historical_item");

    return $app['twig']->render('layouts/camera.twig', array(
        'photo' => $photo
    ));
    /*
      Creates a story with image and text associated with a historical image

      Save:
        - image (optional)
        - quote_text (optional)

      Note: requires at least one of the two^
    */
});

$app->post('/upload/{id}', 'App\Controller\UploadController::saveAction');

$app['debug'] = true;

$app->run();
