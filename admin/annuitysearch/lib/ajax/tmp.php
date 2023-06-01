<?php
require_once("/var/www/vhosts/midwood.com/admin/annuitysearch/mysql.conn.php");

$sql = 'select distinct ga_num FROM DATA order by ga_num';


$db = db_conn('@annuity');
$rs = $db->Execute($sql);
$db->disconnect();

$rs_arr = $rs->GetArray();
foreach ($rs_arr as $a) {
	echo $a['ga_num'].chr(10);
}
die();