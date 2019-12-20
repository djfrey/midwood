<?php
header('Content-Type: text/xml');
$secret = 'midwoodN054@123';
$ptid = $_REQUEST['ptid'];
$key = $_REQUEST['key'];
$profile = $_REQUEST['profile'];

$hash = strtoupper(hash('md5', $ptid.$secret));

if ($key != $hash) {
    $out = '<agent ptid="'.$ptid.'" authorized="False" profile="no" error="Invalid ID, Agent was not found"/>';
    die($out);
}

if ($profile == 'yes') {
    $out = '<agent ptid="'.$ptid.'" authorized="True" profile="yes">';
    $out.= '<firstname></firstname>';
    $out.= '<lastname></lastname>';
    $out.= '<username></username>';
    $out.= '<company></company>';
    $out.= '<email></email>';
    $out.= '<address></address>';
    $out.= '<address2></address2>';
    $out.= '<city></city>';
    $out.= '<state></state>';
    $out.= '<zip></zip>';
    $out.= '<phone></phone>';
    $out.= '<cell/>';
    $out.= '<fax></fax>';
    $out.= '</agent>';
}

if ($profile == 'no') { 
    $out = '<agent ptid="'.$ptid.'" authorized="True" profile="no" />';
}

echo $out;