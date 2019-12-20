<?php
error_reporting(E_ALL);
define('DATABASE_HOST','localhost');
define('DATABASE_USER','as_admin');
define('DATABASE_PASS','as_N054@2');
define('DATABASE_NAME','annuitysearch');

define('SCRIPT_USER', 'annuity_mysql');
define('SCRIPT_PASS', 'password123');

define('VERSION', '2015-12-17');

/**
 * this script performs read-only MySQL database access for an Explore Analytics data source
 * 
 * copyright (c) 2011 Gadi Yedwab. All Rights Reserved.
 */

// remove unnecessary blackslashes in $_REQUEST
nukeMagicQuotes();

// global variables
$db_host = DATABASE_HOST;
$db_user = DATABASE_USER;
$db_pass = DATABASE_PASS;
$db_name = getDBName();
$app_user = getAppUser();
$app_pass = getAppPass();

// authenticate
// replace this with your own authentication code or
// at least change the app user and password with your own
if ($app_user != SCRIPT_USER || $app_pass != SCRIPT_PASS) {
	header("HTTP/1.0 403 Forbidden");
	exit;
}

// query request parameters (global variables)
$request_table = $_REQUEST['table'];
$request_select_list_table_alias = $_REQUEST['select_list_table_alias'];
$request_select_list_column_name = $_REQUEST['select_list_column_name'];
$request_select_list_alias = $_REQUEST['select_list_alias'];
$request_from_list_table_name = $_REQUEST['from_list_table_name'];
$request_from_list_alias = $_REQUEST['from_list_alias'];
$request_from_list_primary_key = $_REQUEST['from_list_primary_key'];
$request_from_list_left_foreign_key = $_REQUEST['from_list_left_foreign_key'];
$request_from_list_left_table_alias = $_REQUEST['from_list_left_table_alias'];
$request_condition = $_REQUEST['condition'];
$request_condition_variables = $_REQUEST['condition_variables'];
$request_order_by = $_REQUEST['order_by'];
$request_order_by_table_alias = $_REQUEST['order_by_table_alias'];
$request_order_by_direction = $_REQUEST['order_by_direction'];
$request_order_by_function = $_REQUEST['order_by_function'];
$request_group_by = $_REQUEST['group_by'];
$request_group_by_function = $_REQUEST['group_by_function'];
$request_group_by_expressions = $_REQUEST['group_by_expressions'];
$request_group_by_expression_aggregates = $_REQUEST['group_by_expression_aggregates'];
$request_all_data_points = $_REQUEST['all_data_points'];
$request_get_tables = $_REQUEST['get_tables'];
$request_get_foreign_keys = $_REQUEST['get_foreign_keys'];
$request_no_row_data = $_REQUEST['no_row_data'];
$request_get_version = $_REQUEST['get_version'];
$request_limit = $_REQUEST['limit'];

// perform the query and send back the data
query();

/**
 * perform the query and send back the data
 */
function query() {
	global $request_no_row_data;

	if (! dbConnect()) {
		header("HTTP/1.0 400 Bad Request");
		exit;
	}

	$sql = sql();
	echo "DEBUG: SQL: " . $sql . "\n";
	echo "VERSION: " . VERSION . "\n";
	$result = mysql_query($sql);
	if (! $result) {
		echo "ERROR: " . mysql_error() . "\n";
		exit;
	}

	sendHeadings($result);
	if (!empty($request_no_row_data) && $request_no_row_data == "yes") {
		// data not needed
		return;
	}
	while ($row = mysql_fetch_row($result)) {
		sendData($row);
	}
}

/**
 * send three lines of column names, types, and sizes
 */
function sendHeadings($result) {
	global $request_get_tables, $request_get_foreign_keys, $request_get_version;

	// special case of getting a list of tables
	if (!empty($request_get_tables) && $request_get_tables == "yes") {
		sendGetTablesHeading();
		return;
	}

	// special case of getting a version information only
	if (!empty($request_get_version) && $request_get_version == "yes") {
		sendGetVersionHeading();
		return;
	}

	// special case of getting a list of foreign keys
	if (!empty($request_get_foreign_keys) && $request_get_foreign_keys == "yes") {
		sendGetForeignKeyHeading();
		return;
	}
	
	// send field names
	$i = 0;
	while ($i < mysql_num_fields($result)) {
		if ($i > 0) {
			echo ',';
		}
		$meta = mysql_fetch_field($result, $i);
		echo '"' . escapeNewline(escapeDoubleQuotes($meta->name)) . '"';
		$i = $i + 1;
	}
	echo "\n";
	// send data types
	$i = 0;
	while ($i < mysql_num_fields($result)) {
		if ($i > 0) {
			echo ',';
		}
		$meta = mysql_fetch_field($result, $i);
		echo '"' . escapeNewline(escapeDoubleQuotes($meta->type)) . '"';
		$i = $i + 1;
	}
	echo "\n";
	// send display size
	$i = 0;
	while ($i < mysql_num_fields($result)) {
		if ($i > 0) {
			echo ',';
		}
		$meta = mysql_fetch_field($result, $i);
		echo '"' . escapeNewline(escapeDoubleQuotes($meta->max_length)) . '"';
		$i = $i + 1;
	}
	echo "\n";
}

/**
 * send headings for a list of tables/view names
 */
function sendGetTablesHeading() {
	// send field names
	echo '"name","type"' . "\n";
	// send data types
	echo '"string","string"' . "\n";
	// send display size
	echo '"30","30"' . "\n";
}

/**
 * send headings for a get_version request
 */
function sendGetVersionHeading() {
	// send field names
	echo '"1"' . "\n";
	// send data types
	echo '"string"' . "\n";
	// send display size
	echo '"30"' . "\n";
}

/**
 * send headings for a list of tables/view names
 */
function sendGetForeignKeyHeading() {
	// send field names
	echo '"fk_table","fk_column","ref_table","ref_column","name","position"' . "\n";
	// send data types
	echo '"string","string","string","string","string","int"' . "\n";
	// send display size
	echo '"200","200","200","200","200","40"' . "\n";
}

/**
 * put together the SQL statement
 */
function sql() {
	global $request_table, $request_group_by, $request_group_by_expressions,
	$request_get_tables, $request_get_foreign_keys, $request_no_row_data,
	$request_get_version;

	// special request, to list all tables
	if (!empty($request_get_tables) && $request_get_tables == "yes") {
		return "SHOW FULL TABLES";
	}

	// special request, to get just the version
	if (!empty($request_get_version) && $request_get_version == "yes") {
		return "SELECT 1";
	}

	// special request, to list all foreign key constraints
	if (!empty($request_get_foreign_keys) && $request_get_foreign_keys == "yes") {
		return "SELECT table_name, column_name, referenced_table_name, referenced_column_name, "
				. "constraint_name, position_in_unique_constraint "
				. "FROM   information_schema.key_column_usage "
				. "WHERE  table_schema = '"
				. getDBName()
				. "' and referenced_table_name is not null "
				. "ORDER BY constraint_name, position_in_unique_constraint DESC";
	}

	// a query with GROUP BY and/or with SUM, COUNT, etc.
	if (!empty($request_group_by) || !empty($request_group_by_expressions)) {
		// this is an aggregate query
		return aggregateSql();
	}

	// special request to return only the column headings. Data not needed
	// we request 100 rows to get a sample of column sizes
	if (!empty($request_no_row_data) && $request_no_row_data == "yes") {
		return "SELECT * FROM " . addIdentifierQuotes($request_table) . " LIMIT 100";
	}
	$s = createSelect();
	$s .= createFrom();
	$s .= createWhere();
	$s .= createOrderBy();
	$s .= createLimit();
	return $s;
}

/**
 * create the SELECT and FROM clauses of the SQL
 */
function createSelect() {
	global $request_select_list_table_alias, $request_select_list_column_name, 
	$request_select_list_alias;

	$tableAlias = splitCSV($request_select_list_table_alias);
	$columnName = splitCSV($request_select_list_column_name);
	$alias = splitCSV($request_select_list_alias);
	
	$s = "SELECT ";
	for ($i = 0; $i < count($tableAlias); $i++) {
		if ($i > 0) {
			$s .= ", ";
		}
		$s .= addIdentifierQuotes($tableAlias[$i]) . "."
			. addIdentifierQuotes($columnName[$i]) . " "
			. addIdentifierQuotes($alias[$i]);
	}
	return $s;
}


/**
 * generate the FROM list based on the information in the FromList
 */
function createFrom() {
	global $request_from_list_table_name, $request_from_list_alias,
	$request_from_list_primary_key, $request_from_list_left_foreign_key,
	$request_from_list_left_table_alias;

	$tableName = splitCSV($request_from_list_table_name);
	$alias = splitCSV($request_from_list_alias);
	$primaryKey = splitCSV($request_from_list_primary_key);
	$leftForeignKey = splitCSV($request_from_list_left_foreign_key);
	$leftTableAlias = splitCSV($request_from_list_left_table_alias);
		
	$s = " FROM ";
	
	// the number of joins is the number of tables minus 1
	for ($i = 0; $i < count($tableName) - 1; $i++) {
		$s .= "(";
	}

	// write all the tables and joins
	for ($i = 0; $i < count($tableName); $i++) {
		if ($i > 0) {
			$s .= ") LEFT JOIN ";
		}
		$s .= addIdentifierQuotes($tableName[$i]) . " "
			. addIdentifierQuotes($alias[$i]);
		if ($i > 0) {
			$s .= " ON " . addIdentifierQuotes($leftTableAlias[$i]) . "."
						. addIdentifierQuotes($leftForeignKey[$i]) . " = "
						. addIdentifierQuotes($alias[$i]) . "."
						. addIdentifierQuotes($primaryKey[$i]);
		}
	}
	return $s;
}

/**
 * write the SQL ORDER BY clause
 */
function createOrderBy() {
	global $request_order_by, $request_order_by_table_alias, $request_order_by_direction, 
	$request_order_by_function, $request_group_by, $request_group_by_expressions, $request_all_data_points;

	if (empty($request_order_by)) {
		return "";
	}

	$orderBy = splitCSV($request_order_by);
	$orderByTableAlias = splitCSV($request_order_by_table_alias);
	$orderByDirection = splitCSV($request_order_by_direction);
	$orderByFunction = splitCSV($request_order_by_function);
	
	$s = " ORDER BY ";
	for ($i = 0; $i < count($orderBy); $i++) {
		if ($i > 0) {
			$s .= ", ";
		}

		if (isDateIntervalFunction($orderByFunction[$i])) {
			$s .= createDateFunction($orderByTableAlias[$i], $orderBy[$i], $orderByFunction[$i]);
		} else if (isAggregateExpression($orderByFunction[$i]) && !($request_all_data_points == "yes")) {
			$s .= createAggregateExpression($orderByTableAlias[$i], $orderBy[$i], $orderByFunction[$i]);
		} else {
			$s .= addIdentifierQuotes($orderByTableAlias[$i]) . "." . addIdentifierQuotes($orderBy[$i]);
		}
		if ($orderByDirection[$i] == "descending") {
			$s .= " DESC";
		}
	}
	return $s;
}

/**
 * write the SQL WHERE clause
 */
function createWhere() {
	global $request_condition;

	if (empty($request_condition)) {
		return "";
	}

	$where = conditionToSQL();
	$where = addLiteralValues($where);
	return " WHERE " . $where;
}

/**
 * write the condition by parsing the condition (XML) into an array in prefix
 * notation (AND/OR, condition, condition) recursively. Then convert the array
 * into a SQL string
 */
function conditionToSQL() {
	conditionToArray();
	$a = conditionArrayToSQL(0);
	return $a["sql"];
}

/**
 * parse the condition XML into an array. (setting case folding to 0 means that we don't
 * convert tag names and attribute names to uppercase. we get them as they are (lowercase))
 */
function conditionToArray() {
	global $request_condition, $condition_array, $condition_array_index;

	$condition_array = array();
	$condition_array_index = 0;

	$xml_parser = xml_parser_create("UTF-8");
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
	xml_set_element_handler($xml_parser, "startConditionTag", "endConditionTag");
	if (! xml_parse($xml_parser, $request_condition, true)) {
		echo "ERROR: XML Parse error: " . xml_error_string(xml_get_error_code($xml_parser)) . " at byte index " . xml_get_current_byte_index($xml_parser) . "\n";
	}
	xml_parser_free($xml_parser);
}

/**
 * callback to handle the <condition> tag
 *
 * we count on the fact that $attribs is passed by value, so we can simply put
 * it into our array
 */
function startConditionTag($parser, $name, $attribs) {
	global $condition_array_index, $condition_array;

	// skip <condition_value> tags, only do <condition> tags
	if ($name == "condition") {
		$condition_array[$condition_array_index] = $attribs;
		$condition_array_index++;
	}
}

/**
 * callback to handle closing tag. Nothing for it really to do
 */
function endConditionTag($parser, $name) {
}

/**
 * create SQL string representing this condition.
 *
 * this function is recursive.
 *
 * this function returns two values, therefore it returns an array with these two values.
 * the first returned value is position of the next condition after this one and
 * the second returned value is the SQL for the current condition (the condition that
 * starts at $start_index)
 */
function conditionArrayToSQL($start_index) {
	global $condition_array;

	switch ($condition_array[$start_index]['type']) {
		case "logical":
			// operator should be AND or OR
			$operator = mysql_real_escape_string($condition_array[$start_index]['operator']);
			$operand_count = $condition_array[$start_index]['operand_count'];
			$operand_count = intval($operand_count);
			// recursive calls to get the each operand
			$sql = "";
			$next = $start_index + 1;
			for ($i = 0; $i < $operand_count; $i++) {
				$operand = conditionArrayToSQL($next);
				if ($i > 0)
					$sql .= " " . $operator . " ";
				if ($operand["type"] == "logical") {
					$sql .= "(" . $operand["sql"] . ")";
				} else {
					$sql .= $operand["sql"];
				}
				$next = $operand["next"];
			}
			return array("next" => $next, "sql" => $sql, "type" => "logical");

		case "predicate":
			$sql = predicateToSQL($condition_array[$start_index]);
			return array("next" => $start_index + 1, "sql" => $sql, "type" => "predicate");

		default:
			break;
	}
}

/**
 * process a predicate operator (this one is not recursive).
 * a placeholder "?" is placed for each variable
 */
function predicateToSQL($predicate) {
	$operator = mysql_real_escape_string($predicate['operator']);
	$tableAlias = $predicate['table_alias'];
	$duration = $predicate['duration'];
	$function = $predicate['interval_function'];

	if (isCompareToAnotherField($operator))
		return createFieldComparison($predicate);

	if (isDateIntervalFunction($function)) {
		$field = createDateFunction($tableAlias, $predicate['field'], $function);
	} else {
		$field = addIdentifierQuotes($tableAlias) . "." . addIdentifierQuotes($predicate['field']);
	}

	switch ($operator) {
		case "ON":
		case "BETWEEN":
			return  $field . " BETWEEN ? AND ?";

		case "NOT ON":
		case "NOT BETWEEN":
			return  $field . " NOT BETWEEN ? AND ?";

		case "IN":
		case "NOT IN":
			$sql = $field . " " . $operator . " (";
			// a given number of variables
			for ($i = 0; $i < $predicate['variables']; $i++) {
				if ($i > 0) {
					$sql .= ",";
				}
				$sql .= "?";
			}
			$sql .= ")";
			return $sql;

		case "IS NULL":
			return $field . " IS NULL";

		case "IS NOT NULL":
			return $field . " IS NOT NULL";

		case "IS EMPTY":
			return "(" . $field . " = '' OR " .$field . " IS NULL)";

		case "IS NOT EMPTY":
			return $field . " != ''";

		case "STARTS WITH":
			return $field . " LIKE CONCAT(?,'%')";

		case "ENDS WITH":
			return $field . " LIKE CONCAT('%',?)";
			
		case "CONTAINS":
			return $field . " LIKE CONCAT(CONCAT('%',?),'%')";
			
		case "NOT CONTAINS":
			return $field . " NOT LIKE CONCAT(CONCAT('%',?),'%')";
			
		case "DURING":
			return "(" . $field . " >= ? AND " . $field . " < ?)";
		
		case "NOT DURING":
			return "(" . $field . " < ? OR " . $field . " >= ?)";
			
		case "BEFORE":
			return $field . " < ?";
						
		case "AFTER":
			return $field . " > ?";
			
		case "DURING LAST":
			return $field . createDuringLast($duration);

		case "EARLIER THAN":
		case "LATER THAN":
			return $field . createEarlierThan($predicate);
			
		default:
			// typically a comparison operator
			return $field . " " . $operator . " ?";

	}
}

/**
 * create a condition testing for the column to be within the last xx units of duration (e.g.,
 * last 3 days)
 * 
 * @param $duration
 */
function createDuringLast($duration) {
		$unit = "DAY";
		if ($duration == "hours")
			$unit = "HOUR";
		if ($duration == "days")
			$unit = "DAY";
		if ($duration == "weeks")
			$unit = "WEEK";
		if ($duration == "months")
			$unit = "MONTH";
		if ($duration == "years")
			$unit = "YEAR";
		return " BETWEEN DATE_SUB(NOW(), INTERVAL ? " . $unit . ") AND NOW()";
}

/**
 * create a condition testing for the column to be before/after xx units of duration ago/from
 * now (e.g., before 7 days ago)
 */
function createEarlierThan($predicate) {
	$operator = mysql_real_escape_string($predicate['operator']);
	$earlierThan = ($operator == "EARLIER THAN");
	$operator = $earlierThan ? " < " : " > ";
	$duration = $predicate['duration'];
	$timeRelative = $predicate['time_relative'];
	$ago = ($timeRelative == "ago");

	return $operator . ($ago ? " DATE_SUB" : " DATE_ADD") . "(NOW(), INTERVAL ? "
				. translateDurationToSQLInterval($duration) . ")";
}

/**
 * translate the duration constant (e.g."years") to a MySQL/SQL Server INTERVAL keyword (e.g.
 * "YEAR")
 */
function translateDurationToSQLInterval($duration) {
	if ($duration == "minutes")
		return "MINUTE";
	if ($duration == "hours")
		return "HOUR";
	if ($duration == "days")
		return "DAY";
	if ($duration == "weeks")
		return "WEEK";
	if ($duration == "months")
		return "MONTH";
	if ($duration == "quarters")
		return "QUARTER";
	if ($duration == "years")
		return "YEAR";
	return "DAY";
}

/**
 * replace the "?" placeholders in the condition with the actual literl values. The literals
 * are placed in single quotes and escaped to prevent SQL injection (see quotedLiteral()).
 *
 * Note, using str_replace would not be reliable because if the literals have "?", they might
 * get subtituted instead othe the placeholders. Instead, we use explode to break the string
 * on all the "?" and then put the string back together
 */
function addLiteralValues($where) {
	global $request_condition_variables;

	$variables = splitCSV($request_condition_variables);

	$pieces = explode("?", $where);

	$new_where = "";
	for ($i = 0; $i < count($variables); $i++) {
		$s = quotedLiteral($variables[$i]);
		$new_where = $new_where . $pieces[$i] . $s;
	}
	// add the piece after the last '?'
	$new_where = $new_where . $pieces[count($variables)];
	return $new_where;
}

/**
 * write an aggregate SQL (with SUM, COUNT and/or GROUP BY)
 */
function aggregateSql() {
	global $request_group_by, $request_group_by_function, $request_group_by_expressions,
	$request_group_by_expression_aggregates, $request_select_list_alias,
	$request_select_list_table_alias, $request_all_data_points;

	$groupBy = splitCSV($request_group_by);
	$groupByFunction = splitCSV($request_group_by_function);
	$groupByExpressions = splitCSV($request_group_by_expressions);
	$groupByExpressionAggregates = splitCSV($request_group_by_expression_aggregates);
	$alias = splitCSV($request_select_list_alias);
	$tableAlias = splitCSV($request_select_list_table_alias);

	$s = "SELECT ";
	$count = 0;
	for($i = 0; $i < count($groupBy); $i++) {
		$fieldName = $groupBy[$i];
		$function = $groupByFunction[$i];
		if ($count > 0) {
			$s .= ", ";
		}

		if (isDateIntervalFunction($function)) {
			$s .= createDateFunction($tableAlias[$count], $fieldName, $function);
		} else {
			$s .= addIdentifierQuotes($tableAlias[$count]) . "." . addIdentifierQuotes($fieldName);
		}

		$s .= " " . addIdentifierQuotes($alias[$count]);
		$count++;
	}
	for($i = 0; $i < count($groupByExpressions); $i++) {
		$fieldName = $groupByExpressions[$i];
		$aggregate = $groupByExpressionAggregates[$i];
		if ($count > 0) {
			$s .= ", ";
		}
		$aggregate = ($request_all_data_points == "yes") ? "" : $aggregate;  
		$s .= createAggregateExpression($tableAlias[$count], $fieldName, $aggregate);
		$s .= " " . addIdentifierQuotes($alias[$count]);
		$count++;
	}
	$s .= createFrom();
	$s .= createWhere();
	$s .= createGroupBy();
	$s .= createOrderBy();
	$s .= createLimit();
	return $s;
}

/**
 * write the GROUP BY clause. This is the same as the group by fields in the SELECT clause, 
 * only without the column aliases
 */
function createGroupBy() {
	global $request_group_by, $request_group_by_function, $request_select_list_table_alias, $request_all_data_points;
	
	if ($request_all_data_points == "yes") {
		return "";
	}
	
	$groupBy = splitCSV($request_group_by);
	$groupByFunction = splitCSV($request_group_by_function);
	$tableAlias = splitCSV($request_select_list_table_alias);
	
	$len = count($groupBy);
	if ($len == 0) {
		return "";
	}
	$s = " GROUP BY ";

	for ($i = 0; $i < $len; $i++) {
		$fieldName = $groupBy[$i];
		$function = $groupByFunction[$i];
		if ($i > 0) {
			$s .= ", ";
		}

		if (isDateIntervalFunction($function)) {
			$s .= createDateFunction($tableAlias[$i], $fieldName, $function);
		} else {
			$s .= addIdentifierQuotes($tableAlias[$i]) . "." . addIdentifierQuotes($fieldName);
		}
	}
	return $s;
}

/**
 * send each data row in CSV format. special care is given to carriage return and
 * linefeed in the data. We remove carriage returns (because they'd cause an issue with
 * readLine on the java side) and escape newlines.
 *
 * double quotes are also escaped
 */
function sendData($row) {
	foreach ($row as $i => $value) {
		if ($i > 0) {
			echo ',';
		}
		echo '"' . escapeNewline(escapeDoubleQuotes(removeCarriageReturns($value))) . '"';
	}
	echo "\n";
}

/**
 * escape double quotes by changing each " to two double quotes '""
 */
function escapeDoubleQuotes($field) {
	return str_replace('"', '""', $field);
}

/**
 * strip out the carriage returns
 */
function removeCarriageReturns($field) {
	return str_replace("\r", "", $field);
}

/**
 * escape newlines with backslash
 */
function escapeNewline($field) {
	return str_replace("\n", "\\n", $field);
}

/**
 * get the database name from the request or from the define statement above.
 * The database name is set in the data source
 */
function getDBName() {
	if (isset($_REQUEST['db_name']) && $_REQUEST['db_name'] != '') {
		return $_REQUEST['db_name'];
	}
	return DATABASE_NAME;
}

/**
 * get the user for authentication. This user is set in the data source
 */
function getAppUser() {
	if (isset($_REQUEST['user'])) {
		return $_REQUEST['user'];
	}
	return "";
}

/**
 * get the pass for authentication. This password is set in the data source
 */
function getAppPass() {
	if (isset($_REQUEST['pass'])) {
		return $_REQUEST['pass'];
	}
	return "";
}

/*
 * initiates a connection to the MySQL database.
 * returns the connection for success, FALSE for failure.
 */
function dbConnect() {
	global $db_host, $db_user, $db_pass, $db_name;

	$conn = @mysql_connect($db_host, $db_user, $db_pass);
	if (! $conn) {
		return FALSE;
	}
	mysql_set_charset("UTF8", $conn);
	if (! mysql_select_db($db_name)) {
		return FALSE;
	}
	return $conn;
}

/*
 * nukeMagicQuotes() - removes slashes added by "magic quotes" to $_REQUEST.
 *                     recurses through nested arrays.
 */
function nukeMagicQuotes() {
	if (get_magic_quotes_gpc()) {
		function stripslashes_deep($value) {
			$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
			return $value;
		}
		$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
	}
}

/**
 * turn a table name or field name into a quoted name that's ready to put into the SQL statement
 */
function addIdentifierQuotes($id) {
	return "`" . mysql_real_escape_string($id) . "`";
}

/**
 * turn a string into a quoted SQL literal that's ready to go into the SQL statement
 */
function quotedLiteral($value) {
	return "'" . mysql_real_escape_string($value) . "'";
}

/**
 * fields are assumed to be comma separated and enclosed in double quotes. Inside the field,
 * double quotes are escaped with double quotes.
 */
function splitCSV($line) {
	$fields = array();
	$fields_index = 0;
	$quotes = false;
	$len = strlen($line);
	for ($i = 0, $j = 0; $i < $len; $i++) {
		if (substr($line, $i, 1) == '"') {
			$quotes = !$quotes;
		}
		if (!$quotes && substr($line, $i, 1) == ',') {
			$field = substr($line, $j, $i - $j);
			$fields[$fields_index++] = stripQuotes($field);
			$j = $i + 1;
		}
		if ($i == $len - 1) {
			// last field
			$field = substr($line, $j, $i - $j + 1);
			$fields[$fields_index++] = stripQuotes($field);
		}
	}
	return $fields;
}

/**
 * remove escaping of double quote and remove the enclosing double quotes.
 */
function stripQuotes($field) {
	$field = str_replace('""', '"', $field);
	if (strlen($field) > 2) {
		$field = substr($field, 1, strlen($field) - 2);
	} else {
		$field = "";
	}
	return $field;
}

/**
 * add a LIMIT clause
 */
function createLimit() {
	global $request_limit;

	if (empty($request_limit)) {
		return "";
	} else {
		return " LIMIT " . mysql_real_escape_string($request_limit);
	}
}

/**
 * create the aggregate expression (e.g., sum(table.field))
 */
function createAggregateExpression($tableAlias, $fieldName, $aggregate) {
	$column = addIdentifierQuotes($tableAlias) . "." . addIdentifierQuotes($fieldName);
	$s = "";
	if (empty($aggregate)) {
		$s .= $column;
	} else if ($aggregate == "count distinct values") {
		$s .= "COUNT(DISTINCT " . $column . ")";
	} else if ($aggregate == "count rows") {
		$s .= "COUNT(*)";
	} else if ($aggregate == "count non-empty values") {
		$s .= "COUNT(" . $column . ")";
	} else if ($aggregate == "average") {
		$s .= "AVG(" . $column . ")";
	} else if ($aggregate == "max" || $aggregate == "min") {
		$s .= $aggregate . "(0 + " . $column . ")";
	} else if ($aggregate == "max datetime") {
		$s .= "MAX(" . $column . ")";
	} else if ($aggregate == "min datetime") {
		$s .= "MIN(" . $column . ")";
	} else if ($aggregate == "average age") {
		$s .= "AVG(TIMESTAMPDIFF(SECOND, " . $column . ", NOW()))";
	} else if ($aggregate == "max age") {
		$s .= "MAX(TIMESTAMPDIFF(SECOND, " . $column . ", NOW()))";
		} else if ($aggregate == "min age") {
		$s .= "MIN(TIMESTAMPDIFF(SECOND, " . $column . ", NOW()))";
	} else {
		$s .= $aggregate . "(" . $column . ")";
	}
	return $s;
}

/**
 * handle grouping dates by Year, Quarter, Month, etc.
 */
function createDateFunction($tableAlias, $fieldName, $function) {
	$column = addIdentifierQuotes($tableAlias) . "." . addIdentifierQuotes($fieldName);
	$s = "";
	if (empty($function)) {
		$s .= $column;
	} else {
		if ($function == "year") {
			$s .= "DATE_FORMAT(" . $column . ", '%Y')";
		} else if ($function == "quarter") {
			$s .= "QUARTER(" . $column . ")";
		} else if ($function == "month") {
			$s .= "DATE_FORMAT(" . $column . ", '%m')";
		} else if ($function == "day_of_week") {
			$s .= "DAYOFWEEK(" . $column . ")";
		} else if ($function == "day_of_month") {
			$s .= "DAYOFMONTH(" . $column . ")";
		} else if ($function == "top_of_year") {
			$s .= "CAST(DATE_FORMAT(" . $column . ", '%Y-01-01') AS DATETIME)";
		} else if ($function == "top_of_quarter") {
			$s .= "DATE_ADD(DATE_FORMAT(" . $column . ", '%Y-01-01'), INTERVAL (QUARTER(" 
				. $column . ") - 1) QUARTER)";
		} else if ($function == "top_of_month") {
			$s .= "CAST(DATE_FORMAT(" . $column . ", '%Y-%m-01') AS DATETIME)";
		} else if ($function == "top_of_week_sunday") {
			$s .= "DATE_SUB(DATE(" . $column . "), INTERVAL (DAYOFWEEK(" . $column .  ") - 1) DAY)";
		} else if ($function == "top_of_week_monday") {
			$s .= "DATE_SUB(DATE(" . $column . "), INTERVAL WEEKDAY(" . $column . ") DAY)";
		} else if ($function == "top_of_day") {
			$s .= "CAST(DATE_FORMAT(" . $column . ", '%Y-%m-%d') AS DATETIME)";
		} else if ($function == "top_of_hour") {
			$s .= "CAST(DATE_FORMAT(" . $column . ", '%Y-%m-%d %H:00:00') AS DATETIME)";
		} else if ($function == "top_of_quarter_hour") {
			$s .= "DATE_ADD(DATE_FORMAT(" . $column . ", '%Y-%m-%d %H:00:00'), INTERVAL (FLOOR(MINUTE("
			 	. $column . ")/15)*15) MINUTE)";
		} else if ($function == "week_of_year_sunday") {
			$s .= "WEEK(" . $column . ", 0)";
		} else if ($function == "week_of_year_monday") {
			$s .= "WEEK(" . $column . ", 5)";
		} else if ($function == "hour_of_day") {
			$s .= "HOUR(" . $column . ")";
		}
	}
	return $s;
}

/**
 * write a predicate condition that compares two fields 
 */
function createFieldComparison($predicate) {
	$operator = mysql_real_escape_string($predicate['operator']);

	if ($operator == "IS SAME" || $operator == "IS DIFFERENT")
		return createIsSameFieldComparison($predicate);

	if ($operator == "IS MORE THAN" || $operator == "IS LESS THAN")
		return createIsMoreThanComarison($predicate);

	$column = addIdentifierQuotes($predicate['table_alias']) . "." . addIdentifierQuotes($predicate['field']);
	$otherColumn = addIdentifierQuotes($predicate['other_field_table_alias']) . "." . addIdentifierQuotes($predicate['other_field']);

	if ($operator == "IS DIFFERENT FROM")
		return $column . " != " . $otherColumn;
	if ($operator == "IS > FIELD")
		return $column . " > " . $otherColumn;
	if ($operator == "IS >= FIELD")
		return $column . " >= " . $otherColumn;
	if ($operator == "IS < FIELD")
		return $column . " < " . $otherColumn;
	if ($operator == "IS <= FIELD")
		return $column . " <= " . $otherColumn;

	// IS SAME AS
	return $column . " = " . $otherColumn;
}

/**
 * write a predicate condition that compares a date/time part of two date fields
 * 
 * implements the predicate condition for operator IS_SAME and IS_DIFFERENT
 */
function createIsSameFieldComparison($predicate) {
	$operator = mysql_real_escape_string($predicate['operator']);
	$arg1 = createDateFunction($predicate['table_alias'], $predicate['field'], $predicate['interval_function']);
	$arg2 = createDateFunction($predicate['other_field_table_alias'], $predicate['other_field'], $predicate['interval_function']);

	if ($operator == "IS SAME")
		return $arg1 . " = " . $arg2;
	else
		return $arg1 . " != " . $arg2;
}

/**
 * write a predicate condition that compares a datetime field against a time delta around
 * another datetime field
 */
function createIsMoreThanComarison($predicate) {
	$operator = mysql_real_escape_string($predicate['operator']);
	$column = addIdentifierQuotes($predicate['table_alias']) . "." . addIdentifierQuotes($predicate['field']);
	$otherColumn = addIdentifierQuotes($predicate['other_field_table_alias']) . "." . addIdentifierQuotes($predicate['other_field']);
	$duration = $predicate['duration'];
	$timeRelative = $predicate['time_relative'];

	if ($operator == "IS MORE THAN") {
		if ($timeRelative == "before") {
			return $column . " < " . createDateSub($duration, $otherColumn);
		} else if ($timeRelative == "after") {
			return $column . " > " . createDateAdd($duration, $otherColumn);
		} else {
			// before or after
			return "(" . $column . " < " . createDateSub($duration, $otherColumn) . " OR " . $column . " > "
					. createDateAdd($duration, $otherColumn) . ")";
		}
	} else {
		// IS_LESS_THAN
		if ($timeRelative == "before") {
			return "(" . $column . " >= " . createDateSub($duration, $otherColumn) . " AND " . $column
					. " < " . $otherColumn . ")";
		} else if ($timeRelative == "after") {
			return "(" . $column . " <= " . createDateAdd($duration, $otherColumn) . " AND " . $column
					. " > " . $otherColumn . ")";
		} else {
			// before or after
			return $column . " BETWEEN " . createDateSub($duration, $otherColumn) . " AND "
					. createDateAdd($duration, $otherColumn);
		}
	}
}

/**
 * do the SQL date calculation to add the number of intervals from the given database field
 * expression
 */
function createDateSub($duration, $otherColumn) {
	return "DATE_SUB(" . $otherColumn . ", INTERVAL ? " . translateInterval($duration) . ")";
}

/**
 * do the SQL date calculation to add the number of intervals from the given database field
 * expression
 */
function createDateAdd($duration, $otherColumn) {
	return "DATE_ADD(" . $otherColumn . ", INTERVAL ? " . translateInterval($duration) . ")";
}

/**
 * translate the interval specifier to its SQL counterpart
 */
function translateInterval($duration) {
	if ($duration == "hours")
		return "HOUR";
	if ($duration == "days")
		return "DAY";
	if ($duration == "weeks")
		return "WEEK";
	if ($duration == "months")
		return "MONTH";
	if ($duration == "quarters")
		return "QUARTER";
	else
		return "YEAR";
}

/**
 * checks that the given function is a valid date interval function
 */
function isDateIntervalFunction($function) {
	return (isDateIntervalStringFunction($function) || isDateIntervalDateFunction($function));
}

/**
 * check that the given operator compare two fields
 */
function isCompareToAnotherField($operator) {
	return ($operator == "IS SAME" || $operator == "IS DIFFERENT" || $operator == "IS MORE THAN"
		|| $operator == "IS LESS THAN" || $operator == "IS SAME AS" || $operator == "IS DIFFERENT FROM"
		|| $operator == "IS > FIELD" || $operator == "IS < FIELD" || $operator == "IS >= FIELD" || $operator == "IS <= FIELD");
}

/**
 * check if the given string is an aggregate function
 */
function isAggregateExpression($function) {
	if (empty($function)) {
		return FALSE;
	}
	return ($function == "average" || $function == "count distinct values"
		|| $function == "count non-empty values" || $function == "count rows"
		|| $function == "sum" || $function == "max" || $function == "min");
}

/**
 * checks that the given function is a valid date interval function
 * 
 * @param $function
 */
function isDateIntervalDateFunction($function) {
	return ($function == "top_of_year" || $function == "top_of_quarter" || $function == "top_of_month"
		|| $function == "top_of_week_sunday" || $function == "top_of_week_monday" || $function == "top_of_day"
		|| $function == "top_of_hour" || $function == "top_of_quarter_hour");
}

/**
 * checks that the given function is a valid date interval function
 * 
 * @param $function
 */
function isDateIntervalStringFunction($function) {
	return ($function == "year" || $function == "quarter" || $function == "month" 
		|| $function == "day_of_week" || $function == "day_of_month" || $function == "hour_of_day");
}
?>