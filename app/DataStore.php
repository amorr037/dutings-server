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
    private $database;
    public function __construct($settings)
    {
        $this->database = new medoo([
            'database_type' => 'mysql',
            'database_name' => $settings['DUTINGS_DB_NAME'],
            'server' => $settings['DUTINGS_DB_HOSTNAME'],
            'username' => $settings['DUTINGS_DB_USERNAME'],
            'password' => $settings['DUTINGS_DB_PASSWORD'],
            'charset' => 'utf8'
        ]);
    }

    public function getUser($email){
        $users = $this->database->select(
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
        return sizeof($users) === 0 ? null : $users[0];
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
}