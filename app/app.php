<?php
/**
 * Created by PhpStorm.
 * User: ayme
 * Date: 2/16/16
 * Time: 9:11 PM
 */

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

require_once "DataStore.php";
$settings = require "settings.php";

if(!function_exists("get")) {
    function get(&$var, $default=null) {
        return isset($var) ? $var : $default;
    }
}

$app->post('/git/pull', function (Request $request, Response $response) use($settings){
    $inputJSON = urldecode($_POST['payload']);
    $myfile = fopen("post.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $inputJSON);
    fclose($myfile);
    $commit = json_decode($inputJSON, true);
    if(isset($commit['branch']) && $commit['branch']==="master"){
        exec("/usr/bin/git pull && /opt/php55/bin/php composer.phar --no-dev install");
    }
});

$app->post("/auth/login/", function(Request $request, Response $response) use($app, $settings){
    $data = $request->getParsedBody();
    $email = get($data['email'], null);
    if(!$email){
        $response->getBody()->write(json_encode("Missing email"));
        return $response->withStatus(400);
    }
    $email = strtolower($email);
    $password = $data['password'];
    if(!$password){
        $response->getBody()->write(json_encode("Missing password"));
        return $response->withStatus(400);
    }
    $dataStore = DataStore::getInstance($settings);
    $user = $dataStore->getUser($email);
    if(!$user || !password_verify($password, $user['password'])){
        $response->getBody()->write(json_encode("Email and password combination not found"));
        return $response->withStatus(400);
    }
    unset($user['password']);
    $authToken = uniqid("auth_", true);
    $dataStore->createAuthToken($user['id'], $authToken, 0);
    $response->getBody()->write(json_encode(["user"=>$user, "auth_token"=>$authToken]));
});

$app->post("/auth/google/", function(Request $request, Response $response) use($app, $settings){
    $data = $request->getParsedBody();
    $idToken = get($data['id_token'], null);
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
    $email = strtolower($email);
    $dataStore = DataStore::getInstance($settings);
    $user = $dataStore->getUser($email);
    if($user === null){
        $dataStore->createUser($email, null, $name, "google");
    }
    $user = $dataStore->getUser($email);
    unset($user['password']);
    $authToken = uniqid("auth_", true);
    $dataStore->createAuthToken($user['id'], $authToken, 0);
    $response->getBody()->write(json_encode(["user"=>$user, "auth_token"=>$authToken]));
});

$app->post("/auth/register/", function(Request $request, Response $response) use($app, $settings){
    $data = $request->getParsedBody();
    $email = get($data['email'], null);
    if(!$email){
        $response->getBody()->write(json_encode("Missing email"));
        return $response->withStatus(400);
    }
    $email = strtolower($email);
    $name = get($data['name'], null);
    if(!$name){
        $response->getBody()->write(json_encode("Missing name"));
        return $response->withStatus(400);
    }
    $password = get($data['password'], null);
    if(!$password){
        $response->getBody()->write(json_encode("Missing password"));
        return $response->withStatus(400);
    }
    $dataStore = DataStore::getInstance($settings);
    $user = $dataStore->getUser($email);
    if($user){
        $response->getBody()->write(json_encode("User with the same email already exists"));
        return $response->withStatus(400);
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $dataStore->createUser($email, $passwordHash, $name, "dutings");
    $user = $dataStore->getUser($email);
    unset($user['password']);
    $response->getBody()->write(json_encode($user));
});

$hasAuthToken = function(Request $request, Response $response, $next) use($app, $settings){
    $authTokens = $request->getHeader('AUTH_TOKEN');
    if(count($authTokens) === 0){
        return $response->withStatus(401);
    }
    $authToken = $authTokens[0];
    $dataStore = DataStore::getInstance($settings);
    $token = $dataStore->getAuthToken($authToken);
    if(!$token){
        return $response->withStatus(401);
    }
    $dataStore->cache["user_id"] = $token['user_id'];
    return $next($request, $response);
};

$app->group("/api", function() use($app, $settings){
    $app->post("/events/create/", function(Request $request, Response $response) use($app, $settings){
        $data = $request->getParsedBody();
        $name = get($data['name'], null);
        if(!$name || strlen($name) === 0){
            $response->getBody()->write(json_encode("Missing name"));
            return $response->withStatus(400);
        }

        $dataStore = DataStore::getInstance($settings);
        $userId = $dataStore->cache['user_id'];
        $eventId = $dataStore->createEvent($userId, $name);
        $event = $dataStore->getEvent($eventId);
        $response->getBody()->write(json_encode($event));
    });
    $app->post("/events/list/", function(Request $request, Response $response) use($app, $settings){
        $dataStore = DataStore::getInstance($settings);
        $userId = $dataStore->cache['user_id'];
        $events = $dataStore->listEvents($userId);
        $response->getBody()->write(json_encode(["events"=>$events, "invited"=>[]]));
    });
})->add($hasAuthToken);
