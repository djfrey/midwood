function display_message(obj, text, cl) {
	//Displays status message
	//Valid class values are info, warning, error, success
	//Initialize message box
	var msg_timeout = 0;
	var wait = 5000;
	var move = 300;
	var obj_clone = obj.clone();
	
	obj.hide();
	
	//Remove all css classes, add the message class
	obj_clone.removeClass().addClass("message");
	//Set the text
	obj_clone.html(text);
	//Add the passed in class
	obj_clone.addClass(cl);
	//Reset the height to fix display bug
	//obj_clone.css('height', obj.css('height'));
	obj_clone.css('height', '20');
	obj_clone.insertBefore(obj);
	obj_clone.slideDown(move, function() {
		msg_timeout = setTimeout(function() {
			obj_clone.slideUp(move, function () {
				clearTimeout(msg_timeout);
				msg_timeout = 0;
				obj_clone.remove();
			}); 
		}, wait);
	});
}

function bind_account(obj, path) {
	obj.addClass("element-collapsed");
	obj.bind("dblclick", function(){
		if (obj.parent().data("data").init == true) { //Element hasn't been double-clicked yet
			obj.addClass("element-expanded");
			var w = $("<div class='wait'></div>");
			obj.parent().append(w).slideDown(300);
			$.get(
				path+"?account_id="+obj.parent().data("data").item_id,
				function(html) {
					obj.parent().children("div.wait").remove();
					obj.parent().children(".carrier-list").append(html);
					obj.parent().children(".carrier-list").find(".element-title").children("div").each(function() {
						bind_toggle($(this), "collapsed");
					});
				}
			);
			obj.parent().data("data").init = false;
		}
  	});
}

function bind_toggle(obj, state) {
	if(obj.parent().hasClass("link-title")) {
		return;	
	}
	switch (state) {
		case "expanded":
			obj.addClass("element-expanded");
		break;
		case "collapsed":
			obj.addClass("element-collapsed");
			obj.parent().children("ul").hide();
		break;
	}
	obj.bind("dblclick", function(e){							  
		clear_selection();
		//Hide/show child elements
		//Account exception - when an account is expanded, collapse other accounts
		if(obj.parent().hasClass("account-title")) {
			$(".account-title").each(function() {
				if ($(this).attr("id") != obj.parent().attr("id")) {
					$(this).children("ul").slideUp();
					$(this).children("div").addClass('element-collapsed');
					$(this).children("div").removeClass('element-expanded');
				}
			});			
		}
		//End account exception
		obj.parent().children("ul").slideToggle();
		obj.toggleClass('element-collapsed');
		obj.toggleClass('element-expanded');
	});

}

function clear_selection() {
    if(document.selection && document.selection.empty) {
        document.selection.empty();
    } else if(window.getSelection) {
        var sel = window.getSelection();
        sel.removeAllRanges();
    }
}

function add_click_class(obj, cl) {
	$(obj).siblings().removeClass(cl);
	$(obj).addClass(cl);
}

function list_add_click_class(obj, cl) {
	//Remove class from siblings
	$(obj).siblings().removeClass(cl);
	//Add class to clicked element
	$(obj).addClass(cl);
}

function add_hover_class(obj, cl) {
	$(obj).hover(function () {
		$(this).addClass(cl);
	}, function() {
		$(this).removeClass(cl);
	});
}

function is_numeric(input) {
	var num = "0123456789";
	var current;
	var result = true;
	
	if (input.length == 0) {
		return false;
	}

	for (i = 0; i < input.length && result == true; i++) {
		current = input.charAt(i);
		if (num.indexOf(current) == -1) {
			result = false;
		}
	}
	return result;
}

function login(sendto) {
	var app_url = "http://midwoodfinancial.com/portal/";
	var success;
	//Create auth dialog
	$("#auth").dialog({
		autoOpen: true,
		bgiframe: true,
		height: 200,
		width: 440,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.3
		},
		closeOnEscape: false,
		beforeclose: function(event, ui) {
			if (success == undefined) {
				//window.location = hsr_url+"?autherr=notloggedin";
				window.location = app_url;
			}
		},
		buttons: {
			"Login": function() {
				if (jQuery.trim($("#username input").val()) == "") { 
					display_message($("#auth_message"), "Please enter your Onyen", "warning");
					return false;
				}
				if (jQuery.trim($("#password input").val()) == "") { 
					display_message($("#auth_message"), "Please enter your Onyen password", "warning");
					return false;
				}				
				$.post(app_url+"inc/ajax/authenticate.php", $("#auth form").serialize(),	
				function(msg){
					response = parse_response(msg);
					if (response['error'] > "") {
						display_message($("#auth_message"), response['error'], "error");
						return;
					}
					if (response['redirect'] > "") {
						$(this).dialog('close');
						window.location=hsr_url+"?autherr="+response['redirect'];
						return;
					}
					success = "yes";
					$(this).dialog('close');
					window.location=hsr_url+sendto;
				});
			},	
			"Cancel": function() {
				$(this).dialog("close");
				//window.location=hsr_url+"?autherr=notloggedin";
				window.location=hsr_url;
			}
		}
	});
}
