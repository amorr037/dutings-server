<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 11:34 PM
 */

define('PROJECT_ROOT', realpath(__DIR__ . "/.."));

class ApiTest extends PHPUnit_Framework_TestCase
{

    protected function request($method, $path, $options = array())
    {
        ob_start();
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $path;
        $_SERVER['SERVER_NAME'] = 'localhost';
        require_once __DIR__ . '/../vendor/autoload.php';
        $app = new \Slim\App;
        require PROJECT_ROOT . '/app/app.php';
        $response = $app->run();
        ob_get_clean();
        return $response;
    }

    protected function get($path, $options = array())
    {
        return $this->request('GET', $path, $options);
    }

    public function testAlwaysPass()
    {
        $this->assertEquals(200, 200);
    }

    public function testSettingsFetched()
    {
        $settings = require_once PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

    public function testHelloWorldEndpoint(){
        $response = $this->get('/hello/javier');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLoginBadCredentials(){
        $url = "http://localhost:8000/auth/login/";
        $data = array('email' => 'bademail', 'password' => 'badpassword');

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        try{
            file_get_contents($url, false, $context);
            $this->fail("Logged in successfully with bad credentials");
        }catch(Exception $e){
            $this->assertEquals($http_response_header[0], "HTTP/1.1 400 Bad Request");
        }
    }
}
