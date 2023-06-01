<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

require '../../vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('/var/google/midwood-89488.json')->withDatabaseUri('https://midwood-89488.firebaseio.com');

$auth = $factory->createAuth();
$firestore = $factory->createFirestore();
$database = $firestore->database();

$staffCollection = $database->collection('staff');

$snapshotStaff = $staffCollection->documents();

$staff = [];

foreach ($snapshotStaff as $staffData) {    
    $tmp = $staffData->data();
    $tmp['id'] = $staffData->id();
    $staff[] = $tmp;    
}

echo(json_encode($staff));