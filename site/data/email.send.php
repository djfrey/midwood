<?php
define('SECRET', '6Lcn17EUAAAAAPDYIiCrYSkWJ4L0sSfhvrb_sNSi');

$to = 'djfrey@gmail.com';
//$to = 'info@midwood.com';
$subject = $_REQUEST['subject'];
$message = $_REQUEST['message'];
$from = $_REQUEST['from'];
$token = $_REQUEST['token'];

if (!$token) {
    die('{"error": "Recaptcha token is required"}'); 
}

if (!$from) {
    die('{"error": "Email address is required"}'); 
}

//Test for bots
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array (
	'secret' => SECRET,
	'response' => $token
);
$options = array(
    'http' => array (
	    'method' => 'POST',
		'content' => http_build_query($data)
	)
);

$context  = stream_context_create($options);
$verify = file_get_contents($url, false, $context);
$captcha_success = json_decode($verify);
if ($captcha_success->success === false) {
    die('{"error": "Email not sent, you have been identified as a bot"}'); 
} else if ($captcha_success->success === true) {
    $headers = "From: ".$from;
    if (mail($to, $subject, $message, $headers)) {
        echo '{"data": "Thank you for your submission!"}';
    } else {
        die('{"error": "Email could not be sent"}'); 
    }
}


