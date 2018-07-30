<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require_once __DIR__."/../vendor/autoload.php";
require_once './src/Tests/TestCase.php';

// App
require_once './src/App/Actions/Logs.php';
require_once './src/App/Actions/Clients.php';
require_once './src/App/Actions/Clients/Activation.php';
require_once './src/App/Actions/Comments.php';
require_once './src/App/Actions/CreateClient.php';

// // @codeCoverageIgnoreEnd
