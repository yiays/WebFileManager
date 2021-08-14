<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/router.php';

$sharedata = explode("\n", file_get_contents($_SERVER['DOCUMENT_ROOT'].'/.shares'));
$shareid = explode('/', $rawrequrl)[2];
$matchedshare = null;
foreach($sharedata as $share) {
  if(strpos($share, $shareid.'=') === 0) {
    $matchedshare = $share;
    break;
  }
}

if(is_null($matchedshare)) {
  http_response_code(404);
  die("This share might no longer exist or the URL is malformed. ($shareid not found)");
}

// SCHEMA: `id=sharer:/request/uri:sharee,sharee`
$sharepayload = explode(':', substr($matchedshare, strlen($shareid)+1));
$sharer = $sharepayload[0];
$sharedir = $sharepayload[1];
$sharees = explode(',',$sharepayload[2]);

if(!in_array($username, $sharees) & $sharees != ['*'] & $username != $sharer) {
  http_response_code(403);
  die("You must be logged in as a user that this item was shared with.");
}

$sharedirext = substr($cwd, strlen($root.'share/'.$shareid.'/'));
$cwd = $_SERVER['DOCUMENT_ROOT'].'/raw'.$sharedir.$sharedirext;

if(!file_exists($cwd)) die("This share no longer exists.");

// Check for any file actions
require_once 'api/fileactiondelegator.php';

$title = "$shareid by $sharer";
require_once 'header.php';

print("
  <div class=\"fv-header\">
    <p><b>Share <i>$shareid</i> by <i>$sharer</i></b></p>
    <p>Visible to <i>$sharepayload[2]</i></p>
  </div>
");

if($sharedirext) {
  $breadcrumbskip = 2;
  require 'breadcrumb.php';
}

$subview = true;
require 'fileview.php';

require_once 'footer.php';
?>