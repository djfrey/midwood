<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

require '../../vendor/autoload.php';

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];


use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('/var/google/midwood-89488.json')->withDatabaseUri('https://midwood-89488.firebaseio.com');

$auth = $factory->createAuth();
$customToken = $auth->createCustomToken('djfrey');

print_r($auth);
echo chr(10);
print_r($customToken);
die();