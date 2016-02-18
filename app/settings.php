<?php
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/17/16
 * Time: 9:12 PM
 */

require_once "exceptions/SettingsException.php";

function joinPaths() {
    $args = func_get_args();
    $paths = array();
    foreach ($args as $arg) {
        $paths = array_merge($paths, (array)$arg);
    }

    $paths = array_map(create_function('$p', 'return trim($p, "/");'), $paths);
    $paths = array_filter($paths);
    return join('/', $paths);
}

$DUTINGS_DB_PORT = getenv("DUTINGS_DB_PORT");
$DUTINGS_DB_USERNAME = getenv("DUTINGS_DB_USERNAME");
$DUTINGS_DB_HOSTNAME = getenv("DUTINGS_DB_HOSTNAME");
$DUTINGS_DB_PASSWORD = getenv("DUTINGS_DB_PASSWORD");

$homeDirectory = getenv('HOME');
$settingsFilePath = "/".joinPaths($homeDirectory, "settings/dutings.php");
if(file_exists($settingsFilePath)){
    require_once $settingsFilePath;
}

if(!$DUTINGS_DB_PORT){
    throw new SettingsException("Missing database port.");
}
if(!$DUTINGS_DB_HOSTNAME){
    throw new SettingsException("Missing database hostname.");
}
if(!$DUTINGS_DB_USERNAME){
    throw new SettingsException("Missing database username.");
}
if(!$DUTINGS_DB_PASSWORD){
    throw new SettingsException("Missing database password.");
}

return [
    "DUTINGS_DB_HOSTNAME" => $DUTINGS_DB_HOSTNAME,
    "DUTINGS_DB_PORT" => $DUTINGS_DB_PORT,
    "DUTINGS_DB_USERNAME" => $DUTINGS_DB_USERNAME,
    "DUTINGS_DB_PASSWORD" => $DUTINGS_DB_PASSWORD,
];