<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UploadController {

    public function saveAction (Request $request, Application $app, $id) {
        $data_js = $request->get('imgBase64');
        $data_to_php = explode(',', $data_js);
        $data = base64_decode($data_to_php[1]);

        $loc_long = $request->get('loc_long');
        $loc_lat = $request->get('loc_lat');
        $description = $request->get('description');

        $remote_user = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : 'anonymous';

        $image_filename = sprintf("%s", uniqid()) . ".jpg";

        file_put_contents($app['__ROOT__'] . '/files/' . $image_filename, $data);

        $app['db']->executeQuery("INSERT INTO bc_story_item ( image_filename, description, loc_lat, loc_long, historical_item_id, user, date_added ) VALUES ( ?, ?, ?, ?, ?, ?, NOW() )", 
                array($image_filename, $description, $loc_lat, $loc_long, $id, $remote_user));

        $story_id = $app['db']->lastInsertId();

        return "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/bicentennial-camera/index.php/photo/{$id}";
    }

}