<?php

/**
 * @see COPY/PASTE from Laravel to easily run this server without the need of apache
 * @link https://www.php.net/manual/en/features.commandline.webserver.php
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

//Set GET to work as .htaccess would handle it in an apache based application
$_GET['_url'] = $uri;

require_once __DIR__ . '/index.php';
