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
$customToken = $auth->createCustomToken($username);
$firestore = $factory->createFirestore();
$database = $firestore->database();

$usersCollection = $database->collection('users');
$queryUser = $usersCollection->where('userName', '==', $username);

$staffCollection = $database->collection('staff');
$queryStaff = $staffCollection->where('userName', '==', $username);

$snapshotUser = $queryUser->documents();
$snapshotStaff = $queryStaff->documents();

$user = array();
$out = array();
$out['error'] = '';
$out['user'] = '';


foreach ($snapshotUser as $userData) {    
    $user = $userData->data();    
    $user['role'] = 'user';
}


foreach ($snapshotStaff as $userData) {    
    $user = $userData->data();    
    $user['role'] = 'staff';
}


if (!$user['userName']) {
    $out['error'] = "You've entered an invalid username";
} else {
    if ($user['password'] != $password) {
        $out['error'] = "You've entered an invalid password";
    } else {
        $out['user'] = $user;
    }
}

echo(json_encode($out));