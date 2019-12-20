<?php
session_start();
if ($_POST) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	if ($username == 'midwood' && $password == 'annuity') {
		$_SESSION['annuity_auth'] = true;
		header("Location: index.php");
	} else {
		$err = '<div class="login error">That username and password combination is invalid</div>';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Midwood Financial - Annuity Search Authorization</title>
<link rel="stylesheet" type="text/css" href="style/application.css" media="screen">
</head>

<body>
<div id="header"> <!-- Begin header -->
	<div id="logo">
	<h1>Midwood Financial Services</h1>
    </div>
</div> <!-- End header -->

<?php echo $err ? $err : '';?>
<form action="" method="post">
<fieldset id="login">
<legend>Log In to the Annuity Search Tool</legend>
<div>
<label for="username">Username</label>
<input type="text" name="username" id="username" class="text" />
</div>
<div>
<label for="password">Password</label>
<input type="password" name="password" id="password" class="text" />
</div>
<div>
<input type="submit" value="Login" />
</div>
</fieldset>
</form>
</body>
</html>