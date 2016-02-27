<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/27/16
 * Time: 5:49 PM
 */

define('PROJECT_ROOT', realpath(__DIR__ . "/.."));

class SettingsTest extends PHPUnit_Framework_TestCase
{
    private $DUTINGS_DB_NAME;
    private $DUTINGS_DB_USERNAME;
    private $DUTINGS_DB_HOSTNAME;
    private $DUTINGS_DB_PASSWORD;
    private $GOOGLE_CLIENT_ID;
    public function setUp()
    {
        $this->DUTINGS_DB_NAME = getenv("DUTINGS_DB_NAME");
        $this->DUTINGS_DB_USERNAME = getenv("DUTINGS_DB_USERNAME");
        $this->DUTINGS_DB_HOSTNAME = getenv("DUTINGS_DB_HOSTNAME");
        $this->DUTINGS_DB_PASSWORD = getenv("DUTINGS_DB_PASSWORD");
        $this->GOOGLE_CLIENT_ID = getenv("GOOGLE_CLIENT_ID");
    }

    public function tearDown()
    {
        putenv("DUTINGS_DB_NAME=$this->DUTINGS_DB_NAME");
        putenv("DUTINGS_DB_USERNAME=$this->DUTINGS_DB_USERNAME");
        putenv("DUTINGS_DB_HOSTNAME=$this->DUTINGS_DB_HOSTNAME");
        putenv("DUTINGS_DB_PASSWORD=$this->DUTINGS_DB_PASSWORD");
        putenv("GOOGLE_CLIENT_ID=$this->GOOGLE_CLIENT_ID");
    }

    public function testSettingsFetched()
    {
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

    /**
     * @expectedException SettingsException
     */
    public function testMissingDUTINGS_DB_NAME()
    {
        putenv('DUTINGS_DB_NAME');
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

    /**
     * @expectedException SettingsException
     */
    public function testMissingDUTINGS_DB_USERNAME()
    {
        putenv('DUTINGS_DB_USERNAME');
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

    /**
     * @expectedException SettingsException
     */
    public function testMissingDUTINGS_DB_HOSTNAME()
    {
        putenv('DUTINGS_DB_HOSTNAME');
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

    /**
     * @expectedException SettingsException
     */
    public function testMissingDUTINGS_DB_PASSWORD()
    {
        putenv('DUTINGS_DB_PASSWORD');
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }

    /**
     * @expectedException SettingsException
     */
    public function testMissingGOOGLE_CLIENT_ID()
    {
        putenv('GOOGLE_CLIENT_ID');
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }
}