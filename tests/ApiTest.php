<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 11:34 PM
 */
class ApiTest extends PHPUnit_Framework_TestCase
{

    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'localhost:8000'
        ]);
    }
    public function testAlwaysPass()
    {
        $response = $this->client->get('/hello/javier', []);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
