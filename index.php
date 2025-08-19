<?php

// Redirect only if not already inside /public
if (strpos($_SERVER['REQUEST_URI'], '/public/') === false) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host   = $_SERVER['HTTP_HOST'];
    $base   = rtrim(dirname($_SERVER['SCRIPT_NAME']), "/\\");
    $url    = $scheme . "://" . $host . $base . "/public/";

    header("Location: " . $url, true, 302);
    exit;
}