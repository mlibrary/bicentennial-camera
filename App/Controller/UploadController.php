<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UploadController {

    public function saveAction (Request $request, Application $app) {
        $data_js = $request->get('imgBase64');
        $data_to_php = explode(',', $data_js);
        $data = base64_decode($data_to_php[1]);

        
        
        file_put_contents($app['__ROOT__'] . '/files/test.png', $data);
        return "OK";
    }

}