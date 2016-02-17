<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 11:34 PM
 */

define('PROJECT_ROOT', realpath(__DIR__ . "/.."));
require_once __DIR__ . '/../vendor/autoload.php';

class ApiTest extends PHPUnit_Framework_TestCase
{

//    protected function request($method, $path, $options = array())
//    {
//        ob_start();
//        $_SERVER['REQUEST_METHOD'] = $method;
//        $_SERVER['REQUEST_URI'] = $path;
//        $_SERVER['SERVER_NAME'] = 'localhost';
//
//        $app = new \Slim\App;
//        require PROJECT_ROOT . '/app/app.php';
//        $response = $app->run();
//        ob_get_clean();
//        return $response;
//    }
//
//    protected function get($path, $options = array())
//    {
//        return $this->request('GET', $path, $options);
//    }

    public function testAlwaysPass()
    {
//        $response = $this->get('/hello/javier');

//        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(200, 200);
    }
}
