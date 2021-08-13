<?php
// Setup the view according to user preferences for this directory
if(basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
  $url = $_GET['cwd'];
  $cwd = $_SERVER['DOCUMENT_ROOT'].$url;
}else{
  require_once __DIR__.'/../router.php';
  $url = $rawrequrl;
}

// Defaults
$viewsize = '4em';
$viewmode = 'grid';

if(is_dir($cwd)){
  if(isset($_POST['viewmode'])){
    setcookie('viewmode', $_POST['viewmode'], strtotime('+1 month'), $url);
    $viewmode = $_POST['viewmode'];
  }
  elseif(isset($_COOKIE['viewmode'])){
    $viewmode = $_COOKIE['viewmode'];
  }
  if(isset($_POST['size'])){
    setcookie('size', $_POST['size'], strtotime('+1 month'), $url);
    $viewsize = $_POST['size'];
  }
  elseif(isset($_COOKIE['size'])){
    $viewsize = $_COOKIE['size'];
  }
}

if(basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
  header('content-type: application/json');
  die(json_encode(['viewsize' => $viewsize, 'viewmode' => $viewmode]));
}
?>