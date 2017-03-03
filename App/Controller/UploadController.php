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

        $image_filename = sprintf("%s", uniqid()) . ".jpg";

        file_put_contents($app['__ROOT__'] . '/files/' . $image_filename, $data);

        $app['db']->executeQuery("INSERT INTO bc_story_item ( image_filename, description, loc_lat, loc_long, historical_item_id ) VALUES ( ?, ?, ?, ?, ? )", 
                array($image_filename, $description, $loc_lat, $loc_long, $id));

        $story_id = $app['db']->getId();

        return "https://{$_SERVER['HTTP_HOST']}/bicentennial_camera/photo/{$id}";
    }

}