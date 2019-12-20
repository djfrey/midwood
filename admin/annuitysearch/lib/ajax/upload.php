<?php 
//error_reporting(E_ALL);
//ini_set("display_errors", 1); 

require_once("../../config.php");
$GLOBALS['root_lib'] = "/var/www/vhosts/midwoodfinancial.com/lib/";
require_once($GLOBALS['root_lib']."gpg.inc.php");

set_include_path(get_include_path() . PATH_SEPARATOR . '/root/.gnupg');
set_time_limit(300);

session_start();
if ($_SESSION['annuity_auth'] !== true) {
	die("Access denied");
}

if ($_POST && $_FILES) { //File has been submitted for upload
	$err = false;
	if (!$err) {
		$upl = file_get_contents($_FILES['uploadedfile']['tmp_name']);

		if (!$handle = fopen($GLOBALS['root_lib'].'ua/crypt.csv', 'w')) {
			 $err = "Cannot open file for writing";
		}
		
		if (fwrite($handle, $upl) === FALSE) {
			$err = "Cannot write to file";
		}
		
		fclose($handle);

		if(!$err) {
			$gpg = '/usr/bin/gpg';
			$passphrase = $gpgpass;
			$encrypted_file = $GLOBALS['root_lib'].'ua/crypt.csv';
			$unencrypted_file = $GLOBALS['root_lib'].'ua/db.csv';
			
			exec("echo $passphrase | $gpg --homedir /var/www/.gnupg --no-mdc-warning --no-tty --batch --passphrase-fd 0 -o $unencrypted_file -d $encrypted_file 2>&1 1> /dev/null");
			
			$handle = fopen($unencrypted_file, 'r');
			
			if(!$handle) {
				$err = "Error opening uploaded file";
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
			}
			unlink($encrypted_file);
			unlink($unencrypted_file);
		}

		if ($err) {
			$out = sprintf('{success: false, error: "%s"}', $err);
			die($out);
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
		$out = sprintf('{success: true, error: "", out: "%s"}', count($arr_sql).' records were added to the database.');
		print $out;
	}
}	

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