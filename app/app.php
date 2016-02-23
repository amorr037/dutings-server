<?php
/**
 * Created by PhpStorm.
 * User: ayme
 * Date: 2/16/16
 * Time: 9:11 PM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$settings = require_once "settings.php";
$app->get('/hello/{name}', function (Request $request, Response $response) use($settings){

    $response->getBody()->write(json_encode($settings));
});

$app->post("/auth/google/", function(Request $request, Response $response) use($settings){
    $json = $request->getBody();
    $data = json_decode($json, true);
    $idToken = $data['id_token'];
    $client = new Google_Client();
    $client->setClientId($settings['GOOGLE_CLIENT_ID']);
    $loginTicket = $client->verifyIdToken($idToken);
    $attributes = $loginTicket->getAttributes()['payload'];
    $name = $attributes['name'];
    $email = $attributes['email'];
    $response->getBody()->write(json_encode(["name"=>$name, "email"=>$email]));
});