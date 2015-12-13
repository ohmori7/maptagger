<?php
/*
 * this requires a configuration file, config.php, like below.
 * <?php
 * define('IELOG_URI', 'http://ielog.hogehoge.org/');
 * $dbserver	= 'localhost';
 * $dbuser	= 'username';
 * $dbpasswd	= 'password';
 * $dbname	= 'ielog';
 * ?>
 */
require_once('config.php');

require_once('config.php');

function
nav_print($links)
{

	if (empty($links))
		return;
	echo '
      <div id="nav">
        <ul>';
	foreach ($links as $name => $uri)
		echo "
          <li><a href=\"$uri\"><span>$name</span></a></li>";
	echo '
        </ul>
      </div>';
}

function
header_print($title, $links)
{
	static $uri = IELOG_URI;

	echo
'<!DOCTYPE html>
<html lang="ja">
  <head>
    <link rel="index" href="./index.php" />
    <link rev="made" href="mailto:null@mobile-ip.org" />
    <link href="' . $uri . 'css/style.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>' . $title . '</title>
  </head>
  <body>
    <h1><img class="inline" alt="logo" src="' . $uri .
        'images/logo.png" width="50" height="50">' . $title . '</h1>';
	nav_print(array('Top' =>  $uri,
	    '検索' => $uri . 'search.php',
	    '一覧' => $uri . 'list.php',
	    ));
	nav_print($links);
	echo '
    <div id="main">';
}

function
footer_print()
{

	echo
'    </div>
  </body>
</html>';
}

// old PHP does not have password_hash()...
function
password_hash($password)
{

	// XXX: salt...
	return crypt($password);
}

function
param_get($name)
{

	if (isset($_POST) && isset($_POST[$name]))
		return $_POST[$name];
	else if (isset($_GET) && isset($_GET[$name]))
		return $_GET[$name];
	else
		return '';
}
?>
