<?php
//Require needed files
require_once __DIR__ . '/../vendor/autoload.php';

//Initialize bootstrap & render the application
echo (new \MusicCollection\Bootstrap\WebBootstrap())->render();
