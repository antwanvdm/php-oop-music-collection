<?php
//Require needed files
require_once "config/settings.php";
require_once "vendor/autoload.php";

//Initialize bootstrap & render the application
$bootstrap = new \System\Bootstrap\CLIBootstrap();
echo $bootstrap->render();
