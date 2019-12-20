<?php
require_once("/var/www/vhosts/midwood.com/admin/annuitysearch/mysql.conn.php");

set_time_limit(300);

session_start();
if ($_SESSION['annuity_auth'] !== true) {
	die("Access denied");
}
function add_space(&$item, &$key) {
	if ($item == '') {
		$item = '&nbsp;';
	}
}

function xlsBOF() {
	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
	return;
}

function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
	return;
}

function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
	return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
} 


if ($_GET['format'] == 'xls') {
	$sql = $_SESSION['sql'];
	$db = db_conn('@annuity');
	$rs = $db->Execute($sql);
	$db->disconnect();
	
	$filename = "annuity_list.xls";
	
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment;filename=annuity_list.xls ");
	header("Content-Transfer-Encoding: binary");
	xlsBOF();
	xlsWriteLabel(0,0,"Policy Number");
	xlsWriteLabel(0,1,"Policy Holder");
	xlsWriteLabel(0,2,"Care of 1");
	xlsWriteLabel(0,3,"Care of 2");
	xlsWriteLabel(0,4,"Address");
	xlsWriteLabel(0,5,"City");
	xlsWriteLabel(0,6,"State");
	xlsWriteLabel(0,7,"Zip");
	xlsWriteLabel(0,8,"Phone Number");
	xlsWriteLabel(0,9,"Date of Birth");
	xlsWriteLabel(0,10,"GA Number");
	xlsWriteLabel(0,11,"Bank Name");	
	xlsWriteLabel(0,12,"Agent ID");
	xlsWriteLabel(0,13,"Agent Name");
	xlsWriteLabel(0,14,"Account Value");
	xlsWriteLabel(0,15,"Value As Of Date");
	xlsWriteLabel(0,16,"Qualified/Non-Qualified");
	xlsWriteLabel(0,17,"RMD Status");	
	xlsWriteLabel(0,18,"Initial Deposit");
	xlsWriteLabel(0,19,"Account Status Code");
	xlsWriteLabel(0,20,"Account Status Description");
	xlsWriteLabel(0,21,"Issue Date");
	if($rs) {
		$rs_arr = $rs->GetArray();
		$i = 1;
		foreach ($rs_arr as $r) {
			xlsWriteLabel($i,0,$r['policy']);
			xlsWriteLabel($i,1,$r['name']); 
			xlsWriteLabel($i,2,$r['co1']); 
			xlsWriteLabel($i,3,$r['co2']); 
			xlsWriteLabel($i,4,$r['street1']); 
			xlsWriteLabel($i,5,$r['city']); 
			xlsWriteLabel($i,6,$r['state']); 
			xlsWriteLabel($i,7,$r['zip']); 
			xlsWriteLabel($i,8,$r['phone']); 
			xlsWriteLabel($i,9,$r['dob']); 
			xlsWriteLabel($i,10,$r['ga_num']); 
			xlsWriteLabel($i,11,$r['ga_name']); 
			xlsWriteLabel($i,12,$r['agent_id']); 
			xlsWriteLabel($i,13,$r['agent_name']); 
			xlsWriteNumber($i,14,$r['acct_value']); 
			xlsWriteLabel($i,15,$r['acct_as_of']); 
			xlsWriteLabel($i,16,$r['field_nq']);
			xlsWriteLabel($i,17,$r['field_r']);
			xlsWriteNumber($i,18,$r['init_deposit']); 
			xlsWriteLabel($i,19,$r['acct_status_code']); 
			xlsWriteLabel($i,20,$r['acct_status_desc']); 
			xlsWriteLabel($i,21,$r['iss_date']); 
			$i++;
		}
	}
	xlsEOF();
    exit();
}

//Base query
$count_sql = 'SELECT COUNT(*) as record_count FROM DATA WHERE ';

$sql = 'SELECT policy, name, co1, co2, street1, city, state, zip, phone, dob, ga_num, ga_name, agent_id, agent_name, acct_value, acct_as_of, field_nq, init_deposit, acct_status_code, acct_status_desc, iss_date, field_r FROM DATA WHERE ';

foreach ($_POST as $clause) {
	$value = '';
	$fmt = '';
	switch ($clause['field']) {
		case 'acct_value':
		case 'init_deposit':
			$add_quotes = false;
		break;
		default:
			$add_quotes = true;
		break;		
	}
	foreach ($clause['value'] as $v) {
		if ($v == '') {
			continue(2);
		}
	}
	switch ($clause['operator']) {
		case "BETWEEN":
			if ($add_quotes) {
				$fmt = "'%s' AND ";
			} else {
				$fmt = "%s AND ";
			}
			foreach ($clause['value'] as $v) {
				if (trim($v) > '') {
					$value .= sprintf($fmt, strtoupper($v));
				}
			}
			$value = rtrim($value, " AND ");			
		break;
		case "LIKE":
			if ($add_quotes) {
				$fmt = "'%%%s%%'";
			} else {
				$fmt = "%%%s%%";
			}
			foreach ($clause['value'] as $v) {
				$value .= sprintf($fmt, strtoupper($v));
			}
		break;
		default:
			if ($add_quotes) {
				$fmt = "'%s', ";
			} else {
				$fmt = "%s, ";
			}
			foreach ($clause['value'] as $v) {
				if (trim($v) > '') {
					$value .= sprintf($fmt, strtoupper($v));
				}
			}
			if ($value > '') {
				$value = sprintf("(%s) ", rtrim($value, ", "));
			}
		break;
	}
	if ($value > '') {
		$where .= sprintf("UPPER(%s) %s %s AND ", $clause['field'], $clause['operator'], $value);
	}
}

//Append where clause
$sql .= rtrim($where, " AND ");
$count_sql .= rtrim($where, " AND ");

//Save full query for Excel export
$_SESSION['sql'] = $sql;

$start_row = 0;
$to_show = 20;

if ($_GET['r'] > '') { $start_row = $_GET['r']; }
if ($_GET['s']) { $to_show = $_GET['s']; }

$sql = sprintf("%s LIMIT %s, %s", $sql, $start_row, $to_show);

//Debug
//echo sprintf("<div class='debug'><div class='title'>Debugging information</div><hr/>%s</div>", $count_sql);
//echo sprintf("<div class='debug'><div class='title'>Debugging information</div><hr/>%s</div>", $sql);
//End debug

$db = db_conn('@annuity');
$rs_count = $db->Execute($count_sql);
$rs = $db->Execute($sql);
$db->disconnect();

if (!$rs) {
	$out = "<h3 class='result'>No records match your criteria</h3>";
} else {
	$count_arr = $rs_count->GetArray();
	foreach ($count_arr as $c) {
		$record_count = $c['record_count'];
	}	
	$rs_arr = $rs->GetArray();
	if ($record_count == 0) {
		$out = "<h3 class='result'>No records match your criteria</h3>";
	} else {
		$next = $start_row + $to_show;
		$back = $start_row - $to_show;
		$of = $next;
		if ($next > $record_count)  { $of = $record_count; }
		$out = "<div id='export_csv'><i class='fas fa-file-excel'></i> <a href='https://midwood.com/admin/annuitysearch/lib/ajax/query.php?format=xls'>Export all results to Excel</a></div>";
		$out .= sprintf("<h3 class='result'>Displaying records %s - %s of %s results</h3>", $start_row + 1, $of, $record_count);
		if ($back >= 0) {
			$back_nav .= sprintf("<div id='result_back' onclick='account_search(%s, %s);'><i class='fas fa-arrow-alt-left'></i> Back</div>", $start_row - $to_show, $to_show);
		}
		if ($next <= $record_count) {
			$next_nav .= sprintf("<div id='result_next' onclick='account_search(%s, %s);'><i class='fas fa-arrow-alt-right'></i> Next</div>", $start_row + $to_show, $to_show);
		}
		$page_nav = sprintf("<table><tr><td>%s</td><td style='text-align: right'>%s</td></tr></table>", $back_nav, $next_nav);
		$out .= $page_nav;
	}
	foreach ($rs_arr as $result) {
		$r = 'No';
		$nq = 'Nonqualified';
		if ($result['field_r'] == 'Y') { $r = 'Yes'; }
		if ($result['field_nq'] == 'Q') { $nq = 'Qualified'; }		
		array_walk($result, 'add_space');
		$out .= sprintf('<div id="%s" class="annuity account">', $result['policy']);
		//Begin heading
		$out .= sprintf('<h3>%s: %s</h3>', $result['policy'], $result['name']);
		//End heading
		//Policy value
		$out .= '<table class="table">';
		$out .= sprintf('<tr><td >Policy Value</td><td class="data">$%s as of %s</td></tr>', number_format($result['acct_value'], '2', '.', ','), date('m/d/Y', strtotime($result['acct_as_of'])));	
		//End policy value
		//Begin Initial Deposit
		$out .= sprintf('<tr><td >Initial Deposit</td><td class="data">$%s</td></tr>', number_format($result['init_deposit'], '2', '.', ','));	
		//End Initial Deposit
		//Begin Date of Issue
		$out .= sprintf('<tr><td >Date of Issue</td><td class="data">%s</td></tr>', date('m/d/Y', strtotime($result['iss_date'])));	
		//End Date of Issue	
		//Begin Account status
		$out .= sprintf('<tr><td >Account Status</td><td class="data">%s (%s)</td></tr>', $result['acct_status_desc'], $result['acct_status_code']);	
		//End Account status
		//Begin Bank name
		$out .= sprintf('<tr><td >Bank Information</td><td class="data">%s (%s)</td></tr>', $result['ga_name'], $result['ga_num']);	
		//End Bank name			
		//Begin address
		$out .= '<tr><td >Address</td><td class="data">';
		$out .= sprintf('<div class="address">%s</div>', $result['street1']);
		$out .= sprintf('<div class="address">%s, %s %s</div>', $result['city'], $result['state'], $result['zip']);
		$out .= '</td></tr>';
		//End address
		//Begin phone
		$out .= sprintf('<tr><td >Phone Number</td><td class="data">%s</td></tr>', $result['phone']);	
		//End phone
		//Begin DOB
		$out .= sprintf('<tr><td >Date of birth</td><td class="data">%s</td></tr>', date('m/d/Y', strtotime($result['dob'])));	
		//End DOB
		//Begin Agent
		$out .= sprintf('<tr><td >Agent</td><td class="data">%s (%s)</td></tr>', $result['agent_name'], $result['agent_id']);	
		//End agent
		//Begin qual/non-qual, RMD
		$out .= sprintf('<tr><td >Qualified status</td><td class="data">%s</td></tr>', $nq);	
		//End qual/non-qual
		//Begin RMD
		$out .= sprintf('<tr><td >RMD status</td><td class="data">%s</td></tr>', $r);	
		//End RMD
		$out .= '</table>';
		$out .= '</div>';
	}
	$out .= $page_nav;
}
print $out;
?>