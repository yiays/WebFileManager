<?php
require_once __DIR__.'/../router.php';
require_once __DIR__.'/../includes/common.php';

$viewsize = '4em';
if(is_dir($cwd)){
	$viewmode = 'grid';
	if(isset($_POST['viewmode'])){
		setcookie('viewmode', $_POST['viewmode'], strtotime('+1 month'));
		$viewmode = $_POST['viewmode'];
	}
	elseif(isset($_COOKIE['viewmode'])){
		$viewmode = $_COOKIE['viewmode'];
	}
	if(isset($_POST['size'])){
		setcookie('size', $_POST['size'], strtotime('+1 month'));
		$viewsize = $_POST['size'];
	}
	elseif(isset($_COOKIE['size'])){
		$viewsize = $_COOKIE['size'];
	}
}else{
	include __DIR__.'/../includes/types.php';

	$previewtype = find_type(pathinfo($cwd, PATHINFO_EXTENSION));
	$viewmode = 'preview filetype-'.$previewtype;
}

$title = basename($cwd);
$subview = !require_once 'header.php';
if(!$subview) require 'breadcrumb.php';

echo "<div class=\"fileview $viewmode\" style=\"--size:$viewsize\">";

$printed = 0;
if(is_dir($cwd))
  require 'views/dirbrowser.php';
else
  require 'views/fileproperties.php';

echo "</div>";

if(is_dir($cwd)) {
  require 'viewcontrols.php';
}

if(!$subview) require 'footer.php';
?>