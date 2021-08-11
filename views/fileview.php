<?php
require_once __DIR__.'/../router.php';
require_once __DIR__.'/../includes/common.php';
require_once __DIR__.'/../api/viewcontrol.php';

if(!isset($subview)){
  $title = basename($cwd);
  require_once 'header.php';
  require 'breadcrumb.php';
}

echo "<div class=\"fileview $viewmode\" style=\"--size:$viewsize\">";

$printed = 0;
if(is_dir($cwd))
  require 'views/dirbrowser.php';
else
  require 'views/fileproperties.php';

if($printed == 0){
  print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
}

echo "</div>";

if(is_dir($cwd)) {
  require 'viewcontrols.php';
}

if(!isset($subview)) require_once 'footer.php';
?>