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
     * @return DataStore The DataStore instance
     */
    public static function getInstance()
    {
        $settings = require(__DIR__."/settings.php");
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
        return $this->database->get("USER", [
                "id",
                "email",
                "password",
                "name",
                "joined",
                "owner"
            ], [
                "email" => $email
            ]
        );
    }

    public function createUser($email, $password, $name, $owner){
        return $this->database->insert("USER",[
                "email" => $email,
                "password" => $password,
                "name" => $name,
                "owner" => $owner
            ]
        );
    }

    public function removeUser($email){
        return $this->database->delete("USER",[
            "email" => $email
        ]);
    }

    public function createAuthToken($userId, $authToken, $expireSeconds){
        return $this->database->insert("AUTH_TOKEN",[
                "user_id" => $userId,
                "auth_token" => $authToken,
                "expires_seconds" => $expireSeconds
            ]
        );
    }

    public function getAuthToken($authToken){
        return $this->database->get("AUTH_TOKEN",[
                "auth_token",
                "user_id",
                "created",
                "expires_seconds"
            ],[
                "auth_token" => $authToken,
            ]
        );
    }

    public function removeAuthToken($authToken){
        return $this->database->delete("AUTH_TOKEN",[
            "auth_token" => $authToken
        ]);
    }
}