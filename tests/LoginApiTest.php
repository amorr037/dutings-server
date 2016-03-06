<?php

require_once "RestfulTest.php";

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 3/1/16
 * Time: 10:08 AM
 */
class LoginRestfulTest extends RestfulTest{
    public function testCreateAccount(){
        $emailPrefix = uniqid();
        $email = "$emailPrefix@mailinator.com";
        $data = array('email' => $email, 'password' => 'password', "name" => "Automatic Test Account");
        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);

        $dataStore = DataStore::getInstance();
        $removed = $dataStore->removeUser($email);
        $this->assertTrue($removed > 0);
    }
    public function testCreateAccountFailsMissingEmail(){
        $data = array('password' => 'password', "name" => "Automatic Test Account");
        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }
    public function testCreateAccountFailsMissingPassword(){
        $emailPrefix = uniqid();
        $email = "$emailPrefix@mailinator.com";
        $data = array('email' => $email, "name" => "Automatic Test Account");
        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }
    public function testCreateAccountFailsMissingName(){
        $emailPrefix = uniqid();
        $email = "$emailPrefix@mailinator.com";
        $data = array('email' => $email, 'password' => 'password');
        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }

    public function testCreateAccountFailsDuplicateEmail(){
        $emailPrefix = uniqid();
        $email = "$emailPrefix@mailinator.com";
        $data = array('email' => $email, 'password' => 'password', "name" => "Automatic Test Account");
        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);

        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);

        $dataStore = DataStore::getInstance();
        $removed = $dataStore->removeUser($email);
        $this->assertTrue($removed > 0);
    }

    public function testLogin(){
        //When a user registers
        $emailPrefix = uniqid();
        $email = "$emailPrefix@mailinator.com";
        $data = array('email' => $email, 'password' => 'password', "name" => "Automatic Test Account");
        $this->post("/auth/register/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);
        //And logs in with the same email and password
        $data = array('email' => $email, 'password' => 'password');
        $this->post("/auth/login/", $data);
        //A 200 response is received
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);

        $dataStore = DataStore::getInstance();
        $removed = $dataStore->removeUser($email);
        $this->assertTrue($removed > 0);
    }
    public function testLoginFailsMissingPassword(){
        $data = array('email' => 'bademail@mailinator.com');
        $this->post("/auth/login/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }
    public function testLoginFailsMissingEmail(){
        $data = array('password' => uniqid());
        $this->post("/auth/login/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }
    public function testLoginFailsBadCredentials(){
        $data = array('email' => 'bademail@mailinator.com', 'password' => uniqid());
        $this->post("/auth/login/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 400);
    }

//    public function testLoginGoogleFailsWithNoIdToken(){
//        $data = array();
//        $this->post("/auth/google/", $data);
//        $this->assertEquals($this->getResponse()->getStatusCode(), 401);
//    }
}