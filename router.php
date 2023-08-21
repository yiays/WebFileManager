<?php
require_once 'includes/common.php';

$root = __DIR__.'/raw/';
$rawrequrl = explode('?', $_SERVER['REQUEST_URI'])[0];
$cwd;
$auth = true; // Tracks if authentication is required

$username = array_key_exists('PHP_AUTH_USER', $_SERVER)?$_SERVER['PHP_AUTH_USER']:'null';

// Directory handlers
// /drives
if(strpos($rawrequrl, '/drives') === 0) {
  $title = 'Drives';
  $cwd = $root;
}
// /user
elseif(strpos($rawrequrl, '/user') === 0) {
  if(!str_starts_with($rawrequrl, "/user/$username/")) {
    http_response_code(302);
    if($username == 'null') {
      header("Location: /");
      die();
    }
    header("Location: /user/$username/");
    die();
  }
  $cwd = __DIR__.'/raw'.str_replace(['[',']'], ['[[]','[]]'], urldecode($rawrequrl));
}
// /share
elseif(strpos($rawrequrl, '/share') === 0) {
  $auth = false;
}
// /download
elseif(strpos($rawrequrl, '/download') === 0) {
  $cwd = __DIR__.'/raw'.str_replace(['[',']'], ['[[]','[]]'], urldecode(substr($rawrequrl, 9)));
}
// /*
else {
  $cwd = __DIR__.'/raw'.str_replace(['[',']'], ['[[]','[]]'], urldecode($rawrequrl));
  if($rawrequrl == '/') $auth = false;
}

// Authentication
// Users can access the homepage and some shares without logging in
if($auth) {
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
}

// Trailing / redirect
if(isset($cwd) && (!file_exists($cwd) || is_dir($cwd)) && !str_ends_with($cwd, '/')) {
  http_response_code(301);
  header("Location: ".$rawrequrl.'/');
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
if($rawrequrl == '/') {
  require 'views/home.php';
}elseif(strpos($rawrequrl, '/share/') === 0){
  require 'views/share.php'; 
}else{
  // 404
  if(!file_exists($cwd) || !isset($cwd)){
    http_response_code(404);
    die();
  }

  require 'views/fileview.php';
}
?>