<?php
require_once('/var/www/vhosts/midwoodfinancial.com/app/lib/adodb5/adodb.inc.php');

function db_conn($schema = NULL) {
	switch ($schema) {
	case '@annuity':
		$dbase = 'annuitysearch';
		$user = 'as_admin';
		$pass = 'as_N054@2';
		$host = 'localhost';
	break;
	case '@docmgmt':
		$dbase = 'doc_mgmt';
		$user = 'dm_admin';
		$pass = 'dm_N054@2';
		$host = 'localhost';
	break;
	case '@clientlist':
		$dbase = 'clientlist';
		$user = 'cl_admin';
		$pass = 'cln054@2';
		$host = 'localhost';	
	break;
	default:
		$db = '';
		$user = '';
		$pass = '';
		$host = '';
	}
	$db = &ADONewConnection('mysqli');
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$db->NConnect($host, $user, $pass, $dbase);
	if (false == $db) { 
		die("Connection error"); 
	}
	return $db;
}
?>