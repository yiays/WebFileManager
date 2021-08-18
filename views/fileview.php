<?php
require_once __DIR__.'/../router.php';

// Handle any view control commands
require_once __DIR__.'/../api/viewcontrol.php';

define('NODE_LIMIT', 256);

if(!isset($subview)){
  // Check for any file actions
  require_once 'api/fileactiondelegator.php';
  
  if(!isset($title)) $title = basename($cwd);
  require_once 'header.php';
  require 'breadcrumb.php';
}

$printed = 0;
$totalnodes = 0;
if(is_dir($cwd)&!isset($_GET['properties'])) {
  echo "<div class=\"fileview $viewmode\" style=\"--size:$viewsize\">";
  require 'views/dirbrowser.php';
  if($printed == 0){
    print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
  }elseif($totalnodes > NODE_LIMIT) {
    print("<span class=\"item disabled\" title=\"Further items have been hidden for performance reasons.\">...</span>");
  }
  echo "</div>";
  require 'viewcontrols.php';
} else {
  require 'views/fileproperties.php';
}

if(!isset($subview)) require_once 'footer.php';
?>