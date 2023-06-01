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

$docCollection = $database->collection('advisorlink');

$queryDocs = $docCollection->orderBy('sort', 'asc');

$snapshotDocs = $queryDocs->documents();

$docs = [];

foreach ($snapshotDocs as $docData) {    
    $tmp = $docData->data();    
    $tmp['id'] = $docData->id();      
    if (gettype($docData['parent']) == 'object') {        
        $tmp['pid'] = $docData['parent']->path();
    } else {
        $tmp['pid'] = '';
    }
    $tmp['parent'] = array();
    $docs[$docData->id()] = $tmp;
}

foreach ($docs as $id => &$doc) {
    $p = substr($doc['pid'], strpos($doc['pid'], '/') + 1, strlen($doc['pid']));
    $doc['parent']['id'] = $p;
    $doc['parent']['parent'] = $docs[$p]['pid'];
    $doc['parent']['name'] = $docs[$p]['name'];
    $doc['parent']['sort'] = $docs[$p]['sort'];
    $doc['parent']['type'] = $docs[$p]['type'];
}

$docs = array_values($docs);

echo(json_encode($docs));