<?php
require_once 'fileview.php';

echo "<div class=\"fileview $viewmode\" style=\"--size:$viewsize\">";

$type = 'file';
if(is_link($cwd)) $type .= '&shortcut';
$ext = pathinfo($cwd, PATHINFO_EXTENSION);
$modtime = date("d/m/Y H:i:s", filemtime($cwd));
$size = human_filesize(filesize($cwd));
$file = basename($cwd);
$dlurl = str_replace(['%2F','+'], ['/','%20'], urlencode(substr($cwd, strlen($_SERVER['DOCUMENT_ROOT']))));
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
}elseif(in_array($ext, $editable)){
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