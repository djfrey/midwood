<?php
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);

require_once('/var/www/vhosts/midwood.com/app/advisorlink-legacy/config.php');

require_once('/var/www/vhosts/midwood.com/app/advisorlink-legacy/class/dataset.class.php');
require_once('/var/www/vhosts/midwood.com/app/advisorlink-legacy/class/element.class.php');
require_once('/var/www/vhosts/midwood.com/app/advisorlink-legacy/lib/functions.php');

$db = db_conn(db_connection);
//Create dropdown of editable top-level elements
$top_element_id = $db->Execute(element_detail_dataset('folder', 'parent', NULL, '0'));
$arr_top_element_id = $top_element_id->GetArray();

foreach ($arr_top_element_id as $e) {
	$arr_element_id[] = $e['element_id'];
}

$arr_element_id = array();

//$arr_element_id[] = '9791'; //Bluerock
//$arr_element_id[] = '4925'; //Delaware
//$arr_element_id[] = '9723'; //GILICO
////$arr_element_id[] = '135'; //GA
//$arr_element_id[] = '7799'; //GA -2
//$arr_element_id[] = '146'; //>Lib Ntnl
//$arr_element_id[] = '123'; //Midland
//$arr_element_id[] = '4984'; //Midwood wholesaler maps
//$arr_element_id[] = '130'; //The standard
//$arr_element_id[] = '128'; //UA
//$arr_element_id[] = '8618';




$top_element_list = $db->Execute(element_detail_dataset('folder', 'title'));
$arr_top_element_list = $top_element_list->GetArray();
if (is_array($arr_top_element_list)) {
	foreach ($arr_top_element_list as $k => $e) {
		if (in_array($e['element_id'], $arr_element_id)) {
			$x = $db->Execute(element_rpt_dataset($e['element_id']));
			$array = $x->GetArray();		
			
			$tree = array($selected_top_element => array('children' => array()));
			foreach($array as $item){
				if(isset($tree[$item['element_id']])){
				$tree[$item['element_id']] = array_merge($tree[$item['element_id']],$item);
				} else {
				$tree[$item['element_id']] = $item;
				}
				
				$parentid = is_null($item['parent_id']) ? $selected_top_element : $item['parent_id'];
				if(!isset($tree[$parentid])) $tree[$parentid] = array('children' => array());
				//this & is where the magic happens: any alteration to $tree[$item['id']
				//  will reflect in the item $tree[$parentid]['children'] as they are the same
				//  variable. For instance, adding a child to $tree[$item['id']]['children]
				//  will be seen in 
				//  $tree[$parentid]['children'][<whatever index $item['id'] has>]['children]
				$tree[$parentid]['children'][] = &$tree[$item['element_id']];
			}

			$result = $tree[$e['element_id']]['children'];
			$arr_doctree[$k] = array(
				'id' => 0,
				'element_id' => $e['element_id'],
				'parent_id' => 0,				
				'element_type' => 'folder',
				'sort_order' => '00010',
				'title' => $e['element_datavalue']					
			);					
			$arr_doctree[$k]['children'] = $result;
			//always unset references
			unset($tree);
		}
	}
}
$db->disconnect();

echo(json_encode(array_values($arr_doctree)));

//$e = new al_doc($arr_doctree);
