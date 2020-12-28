<?php
//Require needed files
require_once "../app/config/settings.php";
require_once "../app/vendor/autoload.php";

//Initialize bootstrap & render the application
echo (new \System\Bootstrap\WebBootstrap())->render();
