<?php
require_once 'fileview.php';
include __DIR__.'/../includes/types.php';

$type = 'file';
if(is_link($cwd)) $type .= '&shortcut';
$ext = pathinfo($cwd, PATHINFO_EXTENSION);
$previewtype = find_type($ext);
$viewmode = 'preview filetype-'.$previewtype;
$modtime = date("d/m/Y H:i:s", filemtime($cwd));
$size = human_filesize(filesize($cwd));
$file = basename($cwd);
$dlurl = str_replace(['%2F','+'], ['/','%20'], urlencode(substr($cwd, strlen($_SERVER['DOCUMENT_ROOT']))));

print("<div class=\"fileview $viewmode\" style=\"--size:$viewsize\">");

print("
<div class=\"file-properties flex-row\">
  <img src=\"/icongen.php?nodetype=$type&ext=$ext\" alt=\"$ext icon\"/>
  <div class=\"flex-stack\">
    <span class=\"name\"><i>File name:</i> $file</span>
    <span class=\"moddate\"><i>Last modified:</i> $modtime</span>
    <span class=\"size\"><i>Size:</i> $size</span>
    <a class=\"btn btn-primary btn-large\" href=\"$dlurl\">Download</a>
  </div>
</div>");
if($previewtype == 'video') {
  $mimetype = mime_content_type($cwd);
  print("
  <video width=\"100%\" style=\"max-height: 56.25vw;\" controls>
    <source src=\"$dlurl\" type=\"$mimetype\">
    Unable to play this video in your browser
  </video>");
}elseif($previewtype == 'image') {
  print("
  <img src=\"$dlurl\" style=\"max-width:100%;max-height: 56.25vw;\" alt=\"$file\"/>");
}elseif($previewtype == 'audio') {
  $mimetype = mime_content_type($cwd);
  print("
  <audio style=\"width:100%;\" controls>
    <source src=\"$dlurl\" type=\"$mimetype\">
    Unable to play this audio in your browser
  </audio>");
}elseif($previewtype == 'zip') {
  print("
    <h3>File contents:</h3>
    <div class=\"fileview list tree\" style=\"--size:4em\">
  ");
  $printed = 0;
  require_once 'zipbrowser.php';
  if($printed == 0){
    print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
  }
  print("</div>");
}elseif(in_array($ext, $editable)) {
  if(filesize($cwd) <= 2000){
    print("
    <form method=\"POST\">
      <textarea name=\"filecontent\" rows=\"10\"  style=\"width:100%;\">".htmlspecialchars(file_get_contents($cwd))."</textarea>
      <input type=\"submit\" class=\"btn\" value=\"Save changes\">
    </form>");
  }
}
$printed = 1;
?>