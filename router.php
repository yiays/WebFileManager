<?php
require_once 'includes/common.php';

$root = __DIR__."/raw/";
$cwd = __DIR__."/raw".urldecode($_SERVER['REQUEST_URI']);

// Hardcoded redirects
if(strpos($cwd, $root.'drives') === 0){
  $cwd = $root;
}

// Authentication
$username = 'null';
$dirpasswd = search_parents($cwd, '.passwd');
if($dirpasswd){
	if(empty($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic');

    die('<h1>401 Unauthorized</h1>');
	}else{
		$passwds = file_get_contents($dirpasswd);
		if(strpos($passwds, md5("$_SERVER[PHP_AUTH_USER]:$_SERVER[PHP_AUTH_PW]")) === false){
			header('HTTP/1.1 401 Unauthorized');
			die('Authorization failed.');
		}else{
			$username = $_SERVER['PHP_AUTH_USER'];
		}
	}
}

// Smart username URLs
if(strpos($cwd, __DIR__.'/raw/user') == 0){
	$cwd = str_replace(__DIR__.'/raw/user', __DIR__.'/raw/user/'.$username, $cwd);
	if(!file_exists(__DIR__.'/raw/user/'.$username.'/')){
		mkdir(__DIR__.'/raw/user/'.$username);
	}
}

// 404
if(!file_exists($cwd)){
	http_response_code(404);
	die();
}

// Hide specified files
$ignores = [];
$dirignore = search_parents($cwd, '.ignore');
if($dirignore){
	$ignores = array_merge($ignores, explode("\n", file_get_contents($dirignore)));
	if(in_array(basename($cwd), $ignores)){
		http_response_code(403);
		die();
	}
}

// The actual routing
if($_SERVER['REQUEST_URI'] == '/') {
  require 'views/home.php';
}else{
  require 'views/fileview.php';
}
?>