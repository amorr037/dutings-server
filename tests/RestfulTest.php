<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 11:34 PM
 */

require (__DIR__.'/../vendor/autoload.php');
require_once (__DIR__."/../app/DataStore.php");

use \Slim\Http\Cookies;
use \Slim\Http\Environment;
use \Slim\Http\Headers;
use \Slim\Http\RequestBody;
use \Slim\Http\UploadedFile;
use \Slim\Http\Uri;
use \Slim\Http\Request;

class TestRequest extends Request{
    public function withParsedJsonBody($data)
    {
        if (!is_null($data) && !is_object($data) && !is_array($data)) {
            throw new InvalidArgumentException('Parsed body value must be an array, an object, or null');
        }

        $clone = clone $this;
        $clone->bodyParsed = $data;

        return $clone;
    }

    public static function createFromEnvironment(Environment $environment, $jsonBody=null)
    {
        $method = $environment['REQUEST_METHOD'];
        $uri = Uri::createFromEnvironment($environment);
        $headers = Headers::createFromEnvironment($environment);
        $cookies = Cookies::parseHeader($headers->get('Cookie', []));
        $serverParams = $environment->all();
        $body = new RequestBody();
        $uploadedFiles = UploadedFile::createFromEnvironment($environment);

        $request = new static($method, $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);

        if ($method === 'POST' &&
            in_array($request->getMediaType(), ['application/x-www-form-urlencoded', 'multipart/form-data'])
        ) {
            // parsed body must be $_POST
            $request = $request->withParsedBody($_POST);
        }
        if($jsonBody !== null){
            $request = $request->withParsedBody($jsonBody);
        }
        return $request;
    }
}

abstract class RestfulTest extends PHPUnit_Framework_TestCase
{
    private $app;
    private $request;
    private $response;
    public function request($method, $path, $data, $headers=[])
    {
        // Capture STDOUT
        ob_start();

        // Prepare a mock environment

        $options = [
            'REQUEST_METHOD' => $method,
            'SERVER_PORT' => '8000',
            'REQUEST_URI' => $path,
            'SERVER_NAME' => 'localhost'
        ];

        foreach($headers as $key => $value){
            $options['HTTP_'.$key] = $value;
        }

        $env = Environment::mock($options);

        $request = TestRequest::createFromEnvironment($env, $data);
        $container = new \Slim\Container(["environment" => $env, "request" => $request]);

        // Run the application
        // this creates an Slim $app
        $app = new \Slim\App($container);
        require __DIR__ . '/../app/app.php';
        $this->app = $app;
        $this->request = $app->getContainer()->get("request");

        // We fire the routes
        $this->response = $this->app->run();

        // Return STDOUT
        return ob_get_clean();
    }

    public function post($path, $data=array(), $headers=array())
    {
        return $this->request('POST', $path, $data, $headers);
    }

    public function getRequest(){
        return $this->request;
    }

    /**
     * @return \Slim\Http\Response The last request's response.
     */
    public function getResponse(){
        return $this->response;
    }

}
