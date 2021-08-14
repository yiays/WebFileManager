<?php
if(basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
  http_response_code(403);
  die("This script cannot be called directly.");
}

require_once __DIR__.'/fileactiondelegator.php';

$files = [];
foreach($ziptargets as $target) {
  $file = basename($target);
  if(strpos($file, '.') === 0) {
    print("$file starts with a dot");
    continue;
  }
  if(in_array($file, $ignores)) {
    print("$file is in ignores");
    continue;
  }
  if(!file_exists($target)) continue;
  $files []= escapeshellarg(basename($cwd).'/'.substr($target, strlen($cwd)));
}

if(count($files) == 0) {
  http_response_code(403);
  print_r($ziptargets);
  die("None of the provided files appear to be valid.");
}

/*
print(":".dirname($cwd)."$ /usr/bin/zip -q - ".escapeshellarg(basename($cwd))." -i ".implode(' ', $files));
die();
*/

header('content-type: application/octet-stream');
header('content-disposition: attachment; filename="'.preg_replace("/[^A-Za-z0-9 ]/", '', basename($ziptargets[0])).'-'.date("ymd-His").'.zip"');

chdir(dirname($cwd));
passthru("/usr/bin/zip -qr - ".escapeshellarg(basename($cwd))." -i ".implode(' ', $files));
die();
?>