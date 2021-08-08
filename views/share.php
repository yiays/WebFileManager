<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/router.php';

$sharedata = explode("\n", file_get_contents($_SERVER['DOCUMENT_ROOT'].'/.shares'));
$shareid = explode('/', $_SERVER['REQUEST_URI'])[2];
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

if(!in_array($username, $sharees) & $sharees != ['*']) {
  http_response_code(403);
  die("You must be logged in as a user that this item was shared with.");
}

if(strpos($sharedir, '/user/') === 0) $sharedir = "/user/$sharer/".substr($sharedir, 6);
print(substr($cwd, strlen($root.'share/'.$shareid.'/')).'<br>');
$cwd = $_SERVER['DOCUMENT_ROOT'].'/raw'.$sharedir.substr($cwd, strlen($root.'share/'.$shareid.'/'));

$title = "$shareid by $sharer";
require 'header.php';

require 'fileview.php';

require 'footer.php';
?>