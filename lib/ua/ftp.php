<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1); 

require_once("/var/www/vhosts/midwoodfinancial.com/app/lib/mysql.conn.php");
require_once("/var/www/vhosts/midwoodfinancial.com/app/lib/gpg.inc.php");

$path = "/var/www/vhosts/midwoodfinancial.com/app/lib/ua/openssl/";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

die('ok');
