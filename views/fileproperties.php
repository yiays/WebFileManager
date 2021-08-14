<?php
require_once 'fileview.php';
include __DIR__.'/../includes/types.php';

if(is_dir($cwd)){
  $type = 'folder';
  $contents = strval(count(glob($cwd.'*')).' item(s)');
}else{
  $type = 'file';
  $ext = pathinfo($cwd, PATHINFO_EXTENSION);
  $previewtype = find_type($ext);
  $size = human_filesize(filesize($cwd));
  $dlurl = str_replace(['%2F','+'], ['/','%20'], urlencode(substr($cwd, strlen($_SERVER['DOCUMENT_ROOT']))));
}
if(is_link($cwd)) $type .= '&shortcut';
$path = substr(dirname($cwd), strlen($root));
$name = basename($cwd);
$modtime = date("d/m/Y H:i:s", filemtime($cwd));
$sharestatus = share_status($cwd);

if($type == 'folder') {
  print("
  <div class=\"file-properties flex-row\">
    <img src=\"/icongen.php?nodetype=$type&sharing=$sharestatus\" alt=\"folder icon\"/>
    <div class=\"flex-stack\">
      <div>
        <h2>$name</h2>
        <span class=\"path\">Located in $path
      </div>
      <span class=\"moddate\"><i>Last modified:</i> $modtime</span>
      <span class=\"contents\"><i>Contents:</i> $contents</span>
      <div class=\"flex-row\">
        <a class=\"btn btn-primary btn-large\" href=\"$rawrequrl\">Browse</a>
        <form method=\"POST\">
          <input type=\"hidden\" name=\"action\" value=\"zipdl\">
          <input type=\"submit\" value=\"Download\" class=\"btn btn-primary btn-large\">
        </form>
      </div>
    </div>
  </div>");
}else{
  print("
  <div class=\"file-properties flex-row\">
    <img src=\"/icongen.php?nodetype=$type&ext=$ext&sharing=$sharestatus\" alt=\"$ext icon\"/>
    <div class=\"flex-stack\">
      <div>
        <h2>$name</h2>
        <span class=\"path\">Located in $path
      </div>
      <span class=\"moddate\"><i>Last modified:</i> $modtime</span>
      <span class=\"size\"><i>Size:</i> $size</span>
      <div class=\"flex-row\">
        <a class=\"btn btn-primary btn-large\" href=\"$dlurl\">Download</a>
      </div>
    </div>
  </div>
  <div class=\"subview\">
    <h2>Preview</h2>");
  if($previewtype == 'video') {
    $mimetype = mime_content_type($cwd);
    print("
    <video width=\"100%\" style=\"max-height: 56.25vw;\" controls>
      <source src=\"$dlurl\" type=\"$mimetype\">
      Unable to play this video in your browser
    </video>");
  }elseif($previewtype == 'image') {
    print("
    <img src=\"$dlurl\" style=\"max-width:100%;max-height: 56.25vw;\" alt=\"$name\"/>");
  }elseif($previewtype == 'audio') {
    $mimetype = mime_content_type($cwd);
    print("
    <audio style=\"width:100%;\" controls>
      <source src=\"$dlurl\" type=\"$mimetype\">
      Unable to play this audio in your browser
    </audio>");
  }elseif($previewtype == 'zip') {
    print("
      <div class=\"fileview list tree\" style=\"--size:6em\">
    ");
    $printed = 0;
    $localnodelimit = NODE_LIMIT;
    require_once 'zipbrowser.php';
    if($printed == 0){
      print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
    }elseif($totalnodes > NODE_LIMIT) {
      print("<span class=\"item disabled\" title=\"Further items have been hidden for performance reasons.\">...</span>");
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
  }else{
    print("<i>Previews are currently unavailable for this file type.</i>");
  }
  print(" </div>");
}
$printed = 1;
?>