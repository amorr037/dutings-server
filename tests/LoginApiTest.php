<?php

require_once "RestfulTest.php";

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 3/1/16
 * Time: 10:08 AM
 */
class LoginRestfulTest extends RestfulTest{
    public function testLoginFailsBadCredentials(){
        $data = array('email' => 'bademail', 'password' => 'badpassword');
        $this->post("/auth/login/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }
}