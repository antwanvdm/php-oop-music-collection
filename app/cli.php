<?php
//Require needed files
require_once 'vendor/autoload.php';

//Initialize bootstrap & render the application
echo new \MusicCollection\Bootstrap\CLIBootstrap()->render();
