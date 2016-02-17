<?php
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/15/16
 * Time: 10:57 PM
 */

define('PROJECT_ROOT', realpath(__DIR__));

require 'vendor/autoload.php';

$app = new \Slim\App;
require PROJECT_ROOT . '/app/app.php';
$app->run();