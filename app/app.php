<?php
/**
 * Created by PhpStorm.
 * User: ayme
 * Date: 2/16/16
 * Time: 9:11 PM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once "DataStore.php";
$settings = require_once "settings.php";

$app->get('/hello/{name}', function (Request $request, Response $response) use($settings){
    $name = $request->getAttribute('name');
    $response->getBody()->write(json_encode("Hello, $name"));
});

$app->post('/git/pull', function (Request $request, Response $response) use($settings){
    $inputJSON = urldecode($_POST['payload']);
    $myfile = fopen("post.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $inputJSON);
    fclose($myfile);
    $commit = json_decode($inputJSON, true);
    if(isset($commit['branch']) && $commit['branch']==="master"){
        exec("/usr/bin/git pull && /opt/php55/bin/php composer.phar install");
    }
});

$app->post("/auth/login", function(Request $request, Response $response) use($app, $settings){
    $json = $request->getBody();
    $data = json_decode($json, true);
    $email = $data['email'];
    if(!$email){
        $response->getBody()->write(json_encode("Missing email"));
        return $response->withStatus(400);
    }
    $password = $data['password'];
    if(!$password){
        $response->getBody()->write(json_encode("Missing password"));
        return $response->withStatus(400);
    }
    $dataStore = new DataStore($settings);
    $user = $dataStore->getUser($email);
    if(!$user || !password_verify($password, $user['PASSWORD'])){
        $response->getBody()->write(json_encode("Email and password combination not found"));
        return $response->withStatus(400);
    }
    unset($user['PASSWORD']);
    $authToken = uniqid("auth_", true);
    $dataStore->createAuthToken($user['ID'], $authToken, 0);
    $response->getBody()->write(json_encode(["user"=>$user, "auth_token"=>$authToken]));
});

$app->post("/auth/google/", function(Request $request, Response $response) use($app, $settings){
    $json = $request->getBody();
    $data = json_decode($json, true);
    $idToken = $data['id_token'];
    $client = new Google_Client();
    $client->setClientId($settings['GOOGLE_CLIENT_ID']);
    try{
        $loginTicket = $client->verifyIdToken($idToken);
    }catch(Exception $e){
        $response->getBody()->write(json_encode("Invalid google id token"));
        return $response->withStatus(401);
    }

    $attributes = $loginTicket->getAttributes()['payload'];
    $name = $attributes['name'];
    $email = $attributes['email'];
    $dataStore = new DataStore($settings);
    $user = $dataStore->getUser($email);
    if($user === null){
        $dataStore->createUser($email, null, $name, "google");
    }
    $user = $dataStore->getUser($email);
    unset($user['PASSWORD']);
    $authToken = uniqid("auth_", true);
    $dataStore->createAuthToken($user['ID'], $authToken, 0);
    $response->getBody()->write(json_encode(["user"=>$user, "auth_token"=>$authToken]));
});

$app->post("/auth/register", function(Request $request, Response $response) use($app, $settings){
    $json = $request->getBody();
    $data = json_decode($json, true);
    $email = $data['email'];
    if(!$email){
        $response->getBody()->write(json_encode("Missing email"));
        return $response->withStatus(400);
    }
    $name = $data['name'];
    if(!$name){
        $response->getBody()->write(json_encode("Missing name"));
        return $response->withStatus(400);
    }
    $password = $data['password'];
    if(!$password){
        $response->getBody()->write(json_encode("Missing password"));
        return $response->withStatus(400);
    }
    $dataStore = new DataStore($settings);
    $user = $dataStore->getUser($email);
    if($user){
        $response->getBody()->write(json_encode("User with the same email already exists"));
        return $response->withStatus(400);
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $dataStore->createUser($email, $passwordHash, $name, "dutings");
    $user = $dataStore->getUser($email);
    unset($user['PASSWORD']);
    $response->getBody()->write(json_encode($user));
});
