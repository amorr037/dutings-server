<?php
/**
 * Created by PhpStorm.
 * User: ayme
 * Date: 2/16/16
 * Time: 9:11 PM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
});