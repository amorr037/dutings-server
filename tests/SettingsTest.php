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
    private $hasSettingsFile;
    public function setUp()
    {
        $this->DUTINGS_DB_NAME = getenv("DUTINGS_DB_NAME");
        $this->DUTINGS_DB_USERNAME = getenv("DUTINGS_DB_USERNAME");
        $this->DUTINGS_DB_HOSTNAME = getenv("DUTINGS_DB_HOSTNAME");
        $this->DUTINGS_DB_PASSWORD = getenv("DUTINGS_DB_PASSWORD");
        $this->GOOGLE_CLIENT_ID = getenv("GOOGLE_CLIENT_ID");

        $homeDirectory = getenv('HOME');
        $settingsFilePath = "/" . $this->joinPaths($homeDirectory, "settings/dutings.php");
        $this->hasSettingsFile = file_exists($settingsFilePath);
        if($this->hasSettingsFile){
            copy($settingsFilePath, "$settingsFilePath.backup");
            unlink($settingsFilePath);
        }
    }

    public function tearDown()
    {
        putenv("DUTINGS_DB_NAME=$this->DUTINGS_DB_NAME");
        putenv("DUTINGS_DB_USERNAME=$this->DUTINGS_DB_USERNAME");
        putenv("DUTINGS_DB_HOSTNAME=$this->DUTINGS_DB_HOSTNAME");
        putenv("DUTINGS_DB_PASSWORD=$this->DUTINGS_DB_PASSWORD");
        putenv("GOOGLE_CLIENT_ID=$this->GOOGLE_CLIENT_ID");

        $homeDirectory = getenv('HOME');
        $settingsFilePath = "/" . $this->joinPaths($homeDirectory, "settings/dutings.php");
        if(file_exists("$settingsFilePath.backup")){
            copy("$settingsFilePath.backup", $settingsFilePath);
            unlink("$settingsFilePath.backup");
        }
        if(!$this->hasSettingsFile && file_exists($settingsFilePath)){
            unlink($settingsFilePath);
        }
    }

    private function joinPaths() {
        $args = func_get_args();
        $paths = array();
        foreach ($args as $arg) {
            $paths = array_merge($paths, (array)$arg);
        }

        $paths = array_map(create_function('$p', 'return trim($p, "/");'), $paths);
        $paths = array_filter($paths);
        return join('/', $paths);
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

    public function testSettingsWithSettingsFile()
    {
        $homeDirectory = getenv('HOME');
        $settingsFolderPath = "/" . joinPaths($homeDirectory, "settings");
        if (!file_exists($settingsFolderPath)) {
            mkdir($settingsFolderPath, 0755, true);
        }
        $settingsFilePath = "/" . joinPaths($homeDirectory, "settings/dutings.php");
        $settingsFile = fopen($settingsFilePath, "w");
        $txt = '<?php
$DUTINGS_DB_NAME = "'.$this->DUTINGS_DB_NAME.'";
$DUTINGS_DB_USERNAME = "'.$this->DUTINGS_DB_USERNAME.'";
$DUTINGS_DB_HOSTNAME = "'.$this->DUTINGS_DB_HOSTNAME.'";
$DUTINGS_DB_PASSWORD = "'.$this->DUTINGS_DB_PASSWORD.'";
$GOOGLE_CLIENT_ID="'.$this->GOOGLE_CLIENT_ID.'";';
        fwrite($settingsFile, $txt);
        fclose($settingsFile);

        putenv('DUTINGS_DB_NAME');
        putenv('DUTINGS_DB_USERNAME');
        putenv('DUTINGS_DB_HOSTNAME');
        putenv('DUTINGS_DB_PASSWORD');
        putenv('GOOGLE_CLIENT_ID');
        $settings = require PROJECT_ROOT."/app/settings.php";
        $this->assertNotEquals($settings, null);
    }
}