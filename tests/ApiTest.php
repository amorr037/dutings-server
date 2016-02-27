<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 11:34 PM
 */

define('PROJECT_ROOT', realpath(__DIR__ . "/.."));
require 'vendor/rmccue/requests/library/Requests.php';
class ApiTest extends PHPUnit_Framework_TestCase
{
    public function testSettingsFetched()
    {
        $settings = require_once PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

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
