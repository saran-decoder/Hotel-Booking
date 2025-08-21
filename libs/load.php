<?php

require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    require_once "includes/" . $class . ".class.php";
});

$wapi = new WebAPI();
$wapi->initiateSession();

function get_config($key, $default=null)
{
    global $__site_config;
    $array = json_decode($__site_config, true);
    if (isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
    }
}