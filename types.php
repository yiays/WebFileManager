<?php
$types = [
  'video' => ['mp4', 'mkv', 'mov', 'avi', 'wmv', 'webm', 'mts', 'av'],
  'audio' => ['mp3', 'wav', 'ogg', 'mid', 'm4a', 'aup', 'aup3'],
  'image' => ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico'],
  'adobe' => ['ai', 'psd', 'swf', 'fla', 'aep', 'abr', 'prproj'],
  'document' => ['doc', 'docx', 'rtf', 'odf', 'pdf', 'pages'],
  'presentation' => ['ppt', 'pptx', 'key'],
  'spreadsheet' => ['xls', 'xlsx', 'numbers', 'csv'],
  'database' => ['sql', 'db', 'cfg', 'ini', 'bin'],
  'plaintext' => ['txt', 'log'],
  'markup' => ['md', 'html', 'xml', 'css'],
  'code' => ['c', 'cpp', 'h', 'hpp', 'ruby', 'py', 'pyp', 'sh', 'bat', 'ps1', 'js', 'ts'],
  'disc' => ['iso', 'img', 'cso', 'dmg', 'ciso'],
  'application' => ['exe', 'app', 'apk', 'ipa'],
  'installer' => ['msi', 'cab', 'deb'],
  'applib' => ['lib', 'dll', 'o', 'com'],
  'zip' => ['zip', '7z', 'rar', 'gz', 'tar'],
  'emulation' => ['nes', 'n64', '64z', 'nsp', 'xci', 'wbfs', 'cia', 'fbi', '3dsx', 'gba', 'pup'],
  'shortcut' => ['url', 'lnk', 'desktop']
];

$type_colors = [
  'video' => '#a33cd6',
  'audio' => '#5789d9',
  'image' => '#31c451',
  'adobe' => '#bf2d2a', // TODO: draw
  'document' => '#3021db',
  'presentation' => '#f0450c', // TODO: draw
  'spreadsheet' => '#047a0c', // TODO: draw
  'database' => '#8f220e', // TODO: draw
  'plaintext' => '#6b6b6b', // TODO: draw
  'markup' => '#cf72c8', // TODO: draw
  'code' => '#64b3ad', // TODO: draw
  'disc' => '#00bde3',
  'application' => '#d1d63e',
  'installer' => '#b7bd11', // TODO: draw
  'applib' => '#c79516', // TODO: draw
  'zip' => '#8fc716', // TODO: draw
  'emulation' => '#1e1f1d', // TODO: draw
  'shortcut' => '#24121f', // TODO: draw
  'unknown' => '#000' // TODO: draw
];

function find_type($ext){
  global $types;
  $ext = strtolower($ext);
  foreach($types as $typename => $exts){
     if(in_array($ext, $exts)){
        return $typename;
        break;
     }
  }
  return 'unknown';
}
?>