<?php
function
user_mail_address_check($maddr)
{
	if (! preg_match('/^[a-zA-Z0-9\._-]+\@([a-zA-Z0-9\-\.]+[^\.]+)$/',
	    $maddr, $matches))
		return false;
	$domain = $matches[1];
	if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A'))
		return true;
	return false;
}

function
user_exists($username)
{
}

function
user_add($user)
{

	$user['password'] = password_hash($user['password']);
	$rc = db_record_insert('user', $user);
	if ($rc === false)
		$rc = 'ERROR: ' . db_error();
	return $rc;
}

function
user_update()
{
}

function
user_del()
{
}

function
user_get($username)
{

	return db_record_get('user', 'username', $username);
}

function
user_is_loggedin()
{
	global $_SESSION;

	return isset($_SESSION['user']);
}

function
user_login()
{

	if (user_is_loggedin())
		return;
	http_redirect('login.php');
	exit(1);
}

function
user_setup()
{
	global $_SESSION, $USER;

	if ($_SESSION)
		return;

	define('IELOG_SESSION_NAME',		'IELOG_SESSION');
	define('IELOG_SESSION_TIMEOUT', 	2 * 60 * 60);

	session_name(IELOG_SESSION_NAME);
	session_start(array('cookie_lifetime' => IELOG_SESSION_TIMEOUT));
	if (isset($_SESSION['user']))
		$USER = $_SESSION['user'];
}

function
user_logout($user)
{
	
	if (! isset($_COOKIE[session_name()]))
		return;
	setcookie(session_name(), '', time() - 3600);
	session_destroy();
	$_SESSION = array();
	session_unset();
}

function
user_authenticate($username, $password)
{
	global $USER, $_SESSION;

	$user = user_get($username);
	if ($user === false)
		return false;
	if (! password_verify($password, $user['password']))
		return false;
	$USER = new stdClass();
	$USER->id = $user['id'];
	$USER->username = $user['username'];
	$USER->lastname = $user['lastname'];
	$USER->firstname = $user['firstname'];
	$_SESSION['user'] = $USER;
	return true;
}

function
user_name()
{
	global $USER;

	if (! user_is_loggedin())
		return '';
	return implode(' ', array($USER->lastname, $USER->firstname));
}

function
user_link_puts()
{
	$loginurl = IELOG_URI . 'user/login.php';
	$logouturl = IELOG_URI . 'user/logout.php';
	echo('<div align="right">');
	if (user_is_loggedin())
		echo(user_name() .
		    '（<a href="' . $logouturl . '">ログアウト</a>）');
	else
		echo('ログインしていません
			（<a href="' . $loginurl . '">ログイン</a>）');
	echo('</div>');
}
?>
