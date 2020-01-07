<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1); 
date_default_timezone_set('America/New_York');

require_once("/var/www/vhosts/midwood.com/lib/mysql.conn.php");
require_once("/var/www/vhosts/midwood.com/lib/gpg.inc.php");

/*ob_start();
include_once('/var/www/vhosts/midwoodfinancial.com/lib/ua/fname.txt');
$fname = ob_get_contents();
ob_end_clean();
*/

$ftp_server = 'sftp.torchmarkcorp.com';
$ftp_user_name = 'midwood';
//$ftp_user_pass = 'MW$TMKDATA';
$ftp_user_pass = '2/hqYprb';

$gpg = '/usr/bin/gpg';
$passphrase = 'N054a123';
$encrypted_file = '/var/www/vhosts/midwood.com/lib/ua/crypt.csv';
$unencrypted_file = '/var/www/vhosts/midwood.com/lib/ua/db.csv';

$connection = ssh2_connect($ftp_server, 22);
ssh2_auth_password($connection, $ftp_user_name, $ftp_user_pass);

$sftp = ssh2_sftp($connection);

$log = NULL;

if (!$sftp) { 
	$log .=  sprintf("Error establishing FTP connection%c", 10);
} else {
	$handle = opendir("ssh2.sftp://".intval($sftp)."/midwood/Outbound");
	while (false != ($file = readdir($handle))){
	    if ($file != '.' && $file != '..' && $file != 'archive') {
			$files[$file] = $file;
		}
	}
	
	krsort($files);
	

	$fn = array_shift($files);
		
	if (!$remoteStream = @fopen("ssh2.sftp://".intval($sftp)."/midwood/Outbound/$fn", 'r')) {
		throw new Exception("Unable to open remote file: $fn");
    } 
	
	// Local stream
	if (!$localStream = @fopen($encrypted_file, 'w')) {
		throw new Exception("Unable to open local file for writing");
	}

	// Write from our remote stream to our local stream
	$read = 0;
	$fileSize = filesize("ssh2.sftp://".intval($sftp)."/midwood/Outbound/$fn");
	while ($read < $fileSize && ($buffer = fread($remoteStream, $fileSize - $read))) {
		// Increase our bytes read
		$read += strlen($buffer);

		// Write to our local file
		if (fwrite($localStream, $buffer) === FALSE) {
			throw new Exception("Unable to write to local file");
		}
	}

	// Close our streams
	fclose($localStream);
	fclose($remoteStream);	

	$h = fopen('/var/www/vhosts/midwood.com/lib/ua/fname.txt', 'w');
	fwrite($h, $fn);
	fclose($h);
	
	while (false != ($file = readdir($handle))){
	    if ($file != '.' && $file != '..' && $file != 'archive' && $file != $fn) {
			unlink($file);
		}
	}
	$file = true;
}
			
if ($file === true) {
	//exec("echo $passphrase | $gpg --homedir /var/www/.gnupg --no-mdc-warning --no-tty --batch --passphrase-fd 0 -o $unencrypted_file -d $encrypted_file 2>&1 1> /dev/null");
				
	$handle = fopen($encrypted_file, 'r');
				
	if(!$handle) {
		$log .= sprintf("Error decrypting file%c", 10);
	} else {
		$row = 0;
		$arr_sql = array();
		while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
			if ($row >= 2) {
				$num = count($data);
				for ($c=0; $c < $num; $c++) {
					add_sql_data($arr_sql[$row], $c, trim($data[$c]));  
				}
			}
			$row++;
		}
		fclose($handle);
		unlink($encrypted_file);
//		unlink($unencrypted_file);
	}
	
	$sql_begin = "INSERT INTO DATA (policy, name, fname, mname, suffix, lname, co1, co2, street1, city, state, zip, phone, dob, ga_name, ga_num, agent_id, agent_name, acct_value, acct_as_of, field_nq, init_deposit, acct_status_code, acct_status_desc, iss_date, field_r) VALUES ";	
	
	foreach ($arr_sql as $row => $stmt) {
		$tmp_sql .= sprintf("(%s), ", implode(", ", $stmt));
		if ($row % 1000 == 0 || $row >= count($arr_sql)) { //Every 1000 records or remaining
			$tmp_sql = rtrim($tmp_sql, ", ");
			$arr_stmt[] = sprintf("%s %s;", $sql_begin, $tmp_sql);
			$tmp_sql = '';
		}
	}
	
			
	$db = db_conn('@annuity');
	$db->Execute("TRUNCATE TABLE DATA");
	foreach ($arr_stmt as $stmt) {
		$db->Execute($stmt);
	}
	$db->disconnect();
	
	$log .= sprintf("Added %s records to database%c", count($arr_sql), 10);
}

$log = date("m.d.y").chr(10).$log.'--end'.chr(10);
echo $log;

$log_handle = fopen('/var/www/vhosts/midwood.com/lib/ua/log.txt', 'a');
fwrite($log_handle, $log);
fclose($log_handle);

//End

function add_space(&$item, &$key) {
	if ($item == '') {
		$item = '&nbsp;';
	}
}

function add_quotes(&$data) {
	$data = '"'.$data.'"';
	return $data;
}

function add_sql_data(&$arr, $seq, $data) {
	$arr_ga = array(
		'00305' => 'Key Investment Services LLC', 
		'01464' => 'FirstTrust Financial Resources',
		'14584' => 'Infinex Investments, Inc.', 
		'15433' => 'Infinex Insurance Agency',
		'23444' => 'IRNB Investment Services',
		'26385' => 'Colonial Investment Services, Inc.',
		'65000' => 'Parkvale Savings Bank', 
		'80784' => 'First Citizens Investor Services, Inc.', 
		'82198' => 'BancMutual Financial & Insurance Services', 
		'95613' => 'First Merit Insurance Agency',
		'95934' => 'BB&T Investments',
		'A01701' => 'First Citizens Securities', 
		'A06151' => 'First Citizens Securities Corp',
		'A06154' => 'BancMutual Financial',
		'A06967' => 'New Alliance Investments',
		'A09592' => 'Mo-Kan Insurance Services, Inc.', 
		'A10396' => 'RBC Capital Markets',
		'A12175' => 'BB&T Investment Services, Inc.',
		'A17275' => 'Rick Lebel',
		'A22207' => 'New Alliance Bank', 
		'A34987' => 'Mo-Kan Insurance Services, Inc'
	);
	switch($seq) {
		case '0': //id
			$arr['POLICY'] = add_quotes($data);
		case '1': //name
			if ($data != "Not On File") {
				$arr['NAME'] = add_quotes($data);
				$tmp = split(' ', $data);
			/*	$arr['FNAME'] = add_quotes(array_shift($tmp));
				if (strlen($tmp[0]) == 1 && strlen($tmp[1]) == 1) { //Two middle initials
					$arr['MNAME'] = sprintf('"%s %s"', array_shift($tmp), array_shift($tmp));
				} else {
					$arr['MNAME'] = add_quotes(array_shift($tmp));
				}
				if (count($tmp) > 1) {
					$last = array_pop($tmp);
					if (in_array($last, array("JR", "SR", "II", "III", "IV", "V", "VI", "ESQ"))) {
						$arr['SUFFIX'] = add_quotes($last);
					} else {
						$arr['SUFFIX'] = '""';
					}
					$arr['LNAME'] = add_quotes(implode($tmp, ' '));
				} else {
					$arr['SUFFIX'] = '""';
					$arr['LNAME'] = add_quotes(array_shift($tmp));
				}			
			*/					
			} else {
				$arr['NAME'] = '""';				
			}
			$arr['FNAME'] = '""';
			$arr['MNAME'] = '""';
			$arr['SUFFIX'] = '""';				
			$arr['LNAME'] = '""';
		break;
		case '2': //c/o 1
			$arr['CO1'] = add_quotes($data);
		break;
		case '3': //c/o 2
			$arr['CO2'] = add_quotes($data);
		break;
		case '4': //Street1
			$arr['STREET1'] = add_quotes($data);
		break;
		case '5': //City,state zip
			$tmp = split(',', $data);
			$arr['CITY'] = add_quotes(array_shift($tmp));
			$tmp1 = split(' ', array_shift($tmp));
			$arr['STATE'] = add_quotes(array_shift($tmp1));
			$arr['ZIP'] = add_quotes(array_shift($tmp1));
		break;
		case '6': //Phone
			$arr['PHONE'] = add_quotes($data);
		break;
		case '7': //date of birth
			$arr['DOB'] = add_quotes(date('Y-m-d', strtotime($data)));
		break;
		case '8': //GA Num
			$arr['GA_NAME'] = add_quotes($arr_ga[$data]);			
			$arr['GA_NUM'] = add_quotes($data);
		break;
		case '9': //Agent ID
			$arr['AGENT_ID'] = add_quotes($data);
		break;
		case '10': //Account manager?			
			$arr['AGENT_NAME'] = add_quotes($data);
		break;
		case '11': //Amount 1
			$arr['ACCT_VALUE'] = strval(intval($data)/100);
		break;
		case '12': //Date ran
			$arr['ACCT_AS_OF'] = add_quotes(date('Y-m-d', strtotime($data)));
		break;
		case '13': //N or Q
			$arr['FIELD_NQ'] = add_quotes($data);
		break;
		case '14': //Amount 2
			$arr['INIT_DEPOSIT'] = strval(intval($data)/100);
		break;		
		case '15': //Status code
			$arr['ACCT_STATUS_CODE'] = add_quotes($data);
		break;		
		case '16': //Status desc
			$arr['ACCT_STATUS_DESC'] = add_quotes($data);
		break;		
		case '17': //Policy date
			$arr['ISS_DATE'] = add_quotes(date('Y-m-d', strtotime($data)));
		break;		
		case '18': //yes no field
			$arr['FIELD_R'] = add_quotes(rtrim($data, ','));
		break;
	}	
}
?>