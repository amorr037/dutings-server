<?php
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 2/17/16
 * Time: 9:12 PM
 */

require_once "exceptions/SettingsException.php";

if(!function_exists("joinPaths")){
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
}

if(!function_exists("settingsInit")) {
    function settingsInit()
    {
        $DUTINGS_DB_NAME = getenv("DUTINGS_DB_NAME");
        $DUTINGS_DB_USERNAME = getenv("DUTINGS_DB_USERNAME");
        $DUTINGS_DB_HOSTNAME = getenv("DUTINGS_DB_HOSTNAME");
        $DUTINGS_DB_PASSWORD = getenv("DUTINGS_DB_PASSWORD");
        $GOOGLE_CLIENT_ID = getenv("GOOGLE_CLIENT_ID");

        $homeDirectory = getenv('HOME');
        $settingsFilePath = "/" . joinPaths($homeDirectory, "settings/dutings.php");
        if (file_exists($settingsFilePath)) {
            require_once $settingsFilePath;
        }

        if (!$DUTINGS_DB_NAME) {
            throw new SettingsException("Missing database name.");
        }
        if (!$DUTINGS_DB_HOSTNAME) {
            throw new SettingsException("Missing database hostname.");
        }
        if (!$DUTINGS_DB_USERNAME) {
            throw new SettingsException("Missing database username.");
        }
        if (!$DUTINGS_DB_PASSWORD) {
            throw new SettingsException("Missing database password.");
        }
        if (!$GOOGLE_CLIENT_ID) {
            throw new SettingsException("Missing google client id.");
        }

        return [
            "DUTINGS_DB_HOSTNAME" => $DUTINGS_DB_HOSTNAME,
            "DUTINGS_DB_NAME" => $DUTINGS_DB_NAME,
            "DUTINGS_DB_USERNAME" => $DUTINGS_DB_USERNAME,
            "DUTINGS_DB_PASSWORD" => $DUTINGS_DB_PASSWORD,
            "GOOGLE_CLIENT_ID" => $GOOGLE_CLIENT_ID
        ];
    }
}
return settingsInit();