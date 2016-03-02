<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/23/16
 * Time: 9:34 PM
 */

require_once 'vendor/autoload.php';

class DataStore
{
    private static $instance;
    private $database;
    public $cache;

    /**
     * @param array $settings The settings from which to grab database credentials.
     * @return DataStore The DataStore instance
     */
    public static function getInstance($settings)
    {
        if (null === static::$instance) {
            static::$instance = new DataStore($settings);
        }

        return static::$instance;
    }

    private function __construct($settings)
    {
        $this->database = new medoo([
            'database_type' => 'mysql',
            'database_name' => $settings['DUTINGS_DB_NAME'],
            'server' => $settings['DUTINGS_DB_HOSTNAME'],
            'username' => $settings['DUTINGS_DB_USERNAME'],
            'password' => $settings['DUTINGS_DB_PASSWORD'],
            'charset' => 'utf8'
        ]);
        $this->cache = [];
    }

    public function getUser($email){
        return $this->database->get(
            "USER", [
                "ID",
                "EMAIL",
                "PASSWORD",
                "NAME",
                "JOINED",
                "OWNER"
            ], [
                "EMAIL" => $email
            ]
        );
    }

    public function createUser($email, $password, $name, $owner){
        return $this->database->insert(
            "USER",[
                "EMAIL" => $email,
                "PASSWORD" => $password,
                "NAME" => $name,
                "OWNER" => $owner
            ]
        );
    }

    public function createAuthToken($userId, $authToken, $expireSeconds){
        return $this->database->insert(
            "AUTH_TOKEN",[
                "USER_ID" => $userId,
                "AUTH_TOKEN" => $authToken,
                "EXPIRES_SECONDS" => $expireSeconds
            ]
        );
    }

    public function getAuthToken($authToken){
        return $this->database->get(
            "AUTH_TOKEN",[
                "AUTH_TOKEN",
                "USER_ID",
                "CREATED",
                "EXPIRES_SECONDS"
            ],[
                "AUTH_TOKEN" => $authToken,
            ]
        );
    }

    public function createEvent($userId, $name){
        return $this->database->insert(
            "EVENT",[
                "USER_ID" => $userId,
                "NAME" => $name
            ]
        );
    }

    public function getEvent($eventId){
        return $this->database->get(
            "EVENT",[
                "ID",
                "USER_ID",
                "CREATED",
                "NAME"
            ],[
                "ID" => $eventId
            ]
        );
    }
}