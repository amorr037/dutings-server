<?php
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 3/1/16
 * Time: 11:34 AM
 */

require_once "RestfulTest.php";

class EventRestfulTest extends RestfulTest{
    private $authToken;
    public function setUp()
    {
        $data = array('email' => 'test1@mailinator.com', "password" => "test");
        $resData = $this->post("/auth/login/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);
        $res = json_decode($resData, true);
        $this->authToken = $res["auth_token"];
    }

    public function testListEvents(){
        $headers = ["AUTH_TOKEN" => $this->authToken];
        $eventData = $this->post("/api/events/list/", [], $headers);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);
        $events = json_decode($eventData, true);
        $this->assertTrue(is_array($events['events']));
    }

    public function testCreateAndDeleteEvent(){
        $headers = ["AUTH_TOKEN" => $this->authToken];
        $name = 'testevent';
        $data = array('name' => $name);
        $eventData = $this->post("/api/events/create/", $data, $headers);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);
        $event = json_decode($eventData, true);
        $this->assertEquals($event['name'], $name);

        $eventId = $event['id'];
        $data = array("event_id" => $eventId);
        $this->post("/api/events/delete/", $data, $headers);
        $this->assertEquals($this->getResponse()->getStatusCode(), 200);
    }

    public function testCreateEventWithoutAuthentication(){
        $data = array('name' => 'testevent');
        $this->post("/api/events/create/", $data);
        $this->assertEquals($this->getResponse()->getStatusCode(), 401);
    }
}