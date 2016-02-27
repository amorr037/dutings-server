<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 11:34 PM
 */

require __DIR__ . '/../vendor/rmccue/requests/library/Requests.php';
Requests::register_autoloader();

class ApiTest extends PHPUnit_Framework_TestCase
{
    public function testLoginFailsBadCredentials(){
        $headers = array('Accept' => 'application/json');
        $url = "http://localhost:8000/auth/login/";
        $data = array('email' => 'bademail', 'password' => 'badpassword');

        $request = Requests::post(
            $url,
            $headers,
            json_encode($data)
        );
        $this->assertEquals($request->status_code, 400);
    }
}
