<?php

// Load Download

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../public/DownloadController.php';

$app->get('/download/{id}', function ($request, $response, $args) use ($app) {


    $name = $args['id'];

    DownloadController::prepareDownload($name, $this, $response);

});


$app->post('/download/{id}', function ($request, $response, $args) use ($app) {

        if (isset($_POST['password'])) {
            $password = $_POST['password'];
            $name = $args['id'];

            DownloadController::checkCredentialsForDownload($password, $name, $response, $this);

        } else {
            echo "error";
        }

});