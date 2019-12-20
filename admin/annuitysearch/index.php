<?php 
require_once("config.php");
session_start();
if ($_SESSION['annuity_auth'] !== true) {
	header("Location: ".$GLOBALS['app_url']."auth.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Midwood Financial - Annuity Search</title>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['app_url'];?>style/application.css" media="screen">
<link rel="stylesheet" href="<?php echo $GLOBALS['app_url'].'lib/js/css/jquery-ui.css';?>" type="text/css" media="screen" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-NJXGk7R+8gWGBdutmr+/d6XDokLwQhF1U3VA7FhvBDlOq7cNdI69z7NQdnXxcF7k" crossorigin="anonymous">
<script type="text/javascript" src="<?php echo $GLOBALS['app_url'];?>lib/js/jquery-core.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['app_url'];?>lib/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['app_url'];?>lib/js/default.js"></script>
<link rel="stylesheet" type="text/css" href="https://midwood.com/app/node_modules/bootstrap/dist/css/bootstrap.min.css" media="screen">
<script type="text/javascript">
var clause_count = 0;
var current_clause;
$.ajaxSetup({
	timeout: 60000
});

$(document).ready (function () {
	add_hover_class(".panel li", "active");
	
	$("#menu_tools li").click(function () {
		if (!$(this).hasClass("disabled")) {
			add_click_class($(this), "current");
			item_type = $(this).attr("rel");
			load_page(item_type);		
		}
	});

	$(".remove").live("click", function() {
		$(this).parent().remove();
	});
	
	$("#add_criteria").live("click", function() {
		add_clause();
	});
	
	$("#search_accounts").live("click", function() {
		account_search(0, 20);
	});
	
	$("#upload_submit").live("click", function() {
		update_db();
	});
	
});

function load_page(item_type) {
	var page_url = "<?php print $GLOBALS['app_url'];?>"+item_type+".php";
	$("#content").html("").addClass("wait");
	$.ajax({
		type: "POST",
		url: page_url,
		success: function(d){
			$("#content").removeClass("wait").html(d);
			switch (item_type) {
				case "search":
					$(document).keypress(function(e) {
						if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
							account_search(0, 20);
						}
					}); 
				break;
				case "logout":
					window.location = "<?php print $GLOBALS['app_url'];?>auth.php";
				break;					
			}
		}
	});
}

function add_hover_class(element, cl) {
	$(element).hover(function () {
		$(this).addClass(cl);
	}, function() {
		$(this).removeClass(cl);
	});
}

function add_click_class(element, cl) {
	$(element).siblings().removeClass(cl);
	$(element).addClass(cl);
}

function add_clause() {
	var field, operator, value, remove;
	var search_form = $("#account_search");
	var field_name = "c_"+clause_count;
	current_clause = $("<div class='clause' id='"+field_name+"'></div>");
	field = $("#account_field").clone();
	field.attr("name", field_name+"[field]");
	field.attr("id", "field_"+clause_count);	
	operator = $("#account_operator").clone();
	operator.attr("name", field_name+"[operator]");	
	operator.attr("id", "operator_"+clause_count);	
	
	operator.bind("change", function(e) {
		add_picker(e);
	});
	
	field.bind("change", function(e) {
		add_picker(e);
	});
	
	//
	remove = $("<i class='fas fa-fw fa-lg fa-ban protected remove'></i>");	
	current_clause.append(remove);
	current_clause.append(field);
	current_clause.append(operator);
	add_field(current_clause, field.val());
	current_clause.prependTo(search_form);
	clause_count++;
}

function add_field(obj, val) {
	var field;
	field = obj.children(".field");
	disable_operators(obj);
	switch (val) {
		//Create ddl's
		case "field_nq":
		case "field_r":
		case "account_status_desc":
		case "ga_num":
			value = $("#"+val).clone();
			value.attr('name', obj.attr("id")+'[value][]');
		break;
		default:
			value = $("<input class='form-control' type='text' name='"+obj.attr("id")+"[value][]' value=''>");
		break;
	}
	obj.append(value);
}

function disable_operators(obj) {
	field_ddl = obj.children(".field");
	operator_ddl = obj.children(".operator");
	
	operator_ddl.children().each(function() {
		$(this).show();
	});
	
	switch (field_ddl.val()) {
		//Create ddl's
		case "field_nq":
		case "field_r":
		case "account_status_desc":
		case "ga_num":
			operator_ddl.children().each(function() {
				switch ($(this).attr("rel")) { 
					case "like":
					case "between":
					case "gt":
					case "lt":
						$(this).hide();
					break;
				}
			});
		break;
	}
}


function add_picker(obj) {
	var ddl = $("#"+obj.target.id);
	var parent = $("#"+obj.target.id).parent();
	var dp_options = { showOn: 'button', buttonImageOnly: true, showAnim: 'fadeIn', buttonImage: '<?php echo $GLOBALS['root_url'];?>img/icon/calendar_select.png' };
	var sibling, sibling_sel, field_ddl, operator_ddl;
	//Remove all datepickers
	parent.children("input[type=text]").datepicker('destroy');
	
	//Field change
	if (ddl.hasClass("field")) { 
		//Find sibling operator field
		sibling = parent.children(".operator");
		field_ddl = ddl;
		operator_ddl = sibling;
		operator_ddl.val("=");
	}
	//Operator change
	if (ddl.hasClass("operator")) {
		//Find sibling field field
		sibling = parent.children(".field");
		field_ddl = sibling;
		operator_ddl = ddl;
	}
	//Remove unprotected fields (everything but field and operator)
	parent.children().each(function() {
		if (!$(this).hasClass("protected")) {
			$(this).remove();
		}
	});
	
	add_field(parent, field_ddl.val());
	
	//Get selected operator
	var selected = $("#"+operator_ddl.attr("id")+" :selected");
	//Add an additional field if necessary
	switch (selected.attr("rel")) {
		case "between":
			parent.append("<span> and </span>");
			add_field(parent, field_ddl.val());
		break;
		case "in":
		case "notin":
		break;
	}	
	
	switch (field_ddl.val()) {
		case "dob":
		case "iss_date":
			parent.children("input[type=text]").datepicker(dp_options);
		break;
	}
}

function validate_search() {
	var date_exp = /^\d{1,2}(\/)\d{1,2}\1\d{4}$/;
	var policy_exp = /^\d{9}$/;
	var currency_exp = /^\s*(\+|-)?((\d+(\.\d\d)?)|(\.\d\d))\s*$/;
	var expr, msg;
	var valid = true;
	$(".field").each(function() {
		expr = '';
		switch ($(this).val()) {
			case "dob":
			case "iss_date":
				expr = date_exp;
				msg = "Invalid date specified";
			break;
			case "acct_value":
			case "init_deposit":
				expr = currency_exp;
				msg = "Invalid amount specified";
			break;
		}
		if (expr > '') {
			$(this).parent().children("input[type=text]").each(function() {
				if(!expr.test($(this).val())) {
					$(this).css("backgroundColor", "#FF7373");
					$(this).animate({
						backgroundColor: "#FFFFFF"
					}, 5000);
					valid = false;
					display_message($("#search_msg"), msg, "error");
				}
			});
		}
	});
	return valid;
}

function account_search(start_row, to_show) {
	var form = $("#account_search");
	var result = $("#search_result");
	var out = validate_search();
	if (out != false) {
		document.getElementById("search_result").innerHTML = '';
		result.addClass("wait");
		$.post("<?php echo $GLOBALS['ajax_url'];?>query.php?r="+start_row+"&s="+to_show, form.serialize(),
			function(msg){
				result.removeClass().html(msg);
		});
	}
}

function update_db() {
	var form = $("#upload_file");
	var upl_target = $("#upload_target"); 
	var result = $("#upload_result");
	var f = upl_target[0];
	document.getElementById("upload_file").target = upl_target.attr("name");
	form.attr("action", "<?php echo $GLOBALS['ajax_url'];?>upload.php");
	form.submit(function () {
		if (f.attachEvent) {
			f.attachEvent('onload', validate_file_element);
		} else {
			f.addEventListener('load', validate_file_element, false);
		}
	});
	var out = validate_upload();
	if (out != false) {
		result.html('');
		result.addClass("wait");
		form.submit();
	}
}

function validate_file_element() {
	var result = $("#upload_result");
	var upl_target = $("#upload_target"); 
	var status = eval("("+upl_target.contents().find("body").text()+")");
	result.html('').removeClass();
	if (status.success == true) {
		display_message($("#upload_msg"), status.out, "success");
	} else {
		display_message($("#upload_msg"), status.error, "error");		
	}
}

function validate_upload() {
	if ($("#upload_file input[type=file]").val() == "") {
		display_message($("#upload_msg"), "Please choose a file to upload", "error");
		return false;
	}
	return true;
}

</script>
</head>
<body>
<div id="header"> <!-- Begin header -->
	<div id="logo" class="small">
	<h1>Midwood Financial Services</h1>
    </div>
</div> <!-- End header -->
<div id="pagewrapper"><!-- Begin pagewrapper -->
	<div id="header"><h2>Midwood Financial Annuity Search Tool</h2></div>
	<div id="left"> <!-- Begin left -->
	<div class="panel" id="menu_tools">
	<h3 class="title tools">Tools</h3>
	<ul>
	<li id="annuity_help" rel="help"><i class="fas fa-question-circle"></i> Getting started</li>
	<li id="annuity_search" rel="search"><i class="fas fa-search"></i> Search by criteria</li>
<!--	<li id="annuity_update" rel="upload">Update the database</li> -->
	<li id="annuity_logout" rel="logout"><i class="fas fa-sign-out"></i> Log out</li>	
	</ul>
    </div>
	</div> <!-- End left -->
	<div id="contentwrapper"> <!-- Begin contentwrapper -->
	<div id="content">
	<p>To begin searching, click <strong>Search by criteria</strong> in the Tools menu.  If you need help, click <strong>Getting started</strong>.</p>
    </div> <!-- End content -->
    </div> <!-- End contentwrapper -->

	<div id="footer"> <!-- Begin footer -->
	</div> <!-- End footer -->
</div> <!-- End pagewrapper -->
</body>
</html>
