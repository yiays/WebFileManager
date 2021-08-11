<?php
$types = [
  'video' => ['mp4', 'mkv', 'mov', 'avi', 'wmv', 'webm', 'mts', 'av'],
  'audio' => ['mp3', 'wav', 'ogg', 'mid', 'm4a'],
  'image' => ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico'],
  'adobe' => ['ai', 'psd', 'swf', 'fla', 'aep', 'abr', 'prproj'],
  'document' => ['doc', 'docx', 'rtf', 'odf', 'pdf', 'pages'],
  'presentation' => ['ppt', 'pptx', 'key'],
  'spreadsheet' => ['xls', 'xlsx', 'numbers', 'csv'],
  'database' => ['sql', 'db', 'cfg', 'conf', 'ini', 'bin', 'json'],
  'plaintext' => ['txt', 'log'],
  'markup' => ['md', 'html', 'xml', 'css'],
  'code' => ['c', 'cpp', 'h', 'hpp', 'ruby', 'py', 'pyp', 'sh', 'bat', 'ps1', 'js', 'ts'],
  'disc' => ['iso', 'img', 'cso', 'dmg', 'ciso'],
  'application' => ['exe', 'app', 'apk', 'ipa', 'msi', 'cab', 'deb'],
  'applib' => ['lib', 'dll', 'o', 'com'],
  'zip' => ['zip', '7z', 'rar', 'gz', 'tar', 'mcworld', 'tgz', 'bz2'],
  'emulation' => ['nes', 'n64', '64z', 'nsp', 'xci', 'wbfs', 'cia', 'fbi', '3dsx', 'gba', 'pup'],
  'shortcut' => ['url', 'lnk', 'desktop']
];
$editable = [
  'sql', 'cfg', 'conf', 'ini', 'json'
];
$editable = array_merge($editable, $types['plaintext']);
$editable = array_merge($editable, $types['markup']);
$editable = array_merge($editable, $types['code']);

$type_colors = [
  'video' => '#a33cd6',
  'audio' => '#5789d9',
  'image' => '#31c451',
  'adobe' => '#bf2d2a',
  'document' => '#3021db',
  'presentation' => '#f0450c',
  'spreadsheet' => '#047a0c',
  'database' => '#8f220e',
  'plaintext' => '#6b6b6b',
  'markup' => '#cf72c8',
  'code' => '#64b3ad',
  'disc' => '#00bde3',
  'application' => '#d1d63e',
  'applib' => '#c79516',
  'zip' => '#8fc716',
  'emulation' => '#1e1f1d',
  'shortcut' => '#24121f',
  'unknown' => '#000'
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