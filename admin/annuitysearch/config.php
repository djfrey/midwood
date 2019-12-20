<?php
ini_set('display_errors',0);
error_reporting(E_ALL ^ E_NOTICE);

date_default_timezone_set('America/New_York');

$GLOBALS['root_path'] = '/var/www/vhosts/midwood.com/';
$GLOBALS['root_url'] = 'https://midwood.com/';

$GLOBALS['app_path'] = $GLOBALS['root_path']."admin/annuitysearch/";
$GLOBALS['app_url'] = $GLOBALS['root_url']."admin/annuitysearch/";
$GLOBALS['ajax_url'] = $GLOBALS['app_url']."lib/ajax/";
?>