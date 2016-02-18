<?php

/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/17/16
 * Time: 9:40 PM
 */
class SettingsException extends Exception{
    function __construct($message){
        parent::__construct($message);
    }
}