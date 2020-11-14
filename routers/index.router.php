<?php

// GET index route
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app->get('/', function (Request $request, Response $response, $args) use ($app) {


    $response = $this->view->render($response, "index.html");
    return $response;
});
