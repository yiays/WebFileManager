<?php
if(basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
  http_response_code(403);
  die("This script cannot be called directly.");
}

require_once __DIR__.'/../router.php';

if(array_key_exists('action', $_POST)){
  switch($_POST['action']) {
    case 'zipdl':
      $ziptargets = [$cwd];
      $keybase = basename($cwd).'/';

      // Manually selected files to download
      foreach(array_keys($_POST) as $key) {
        $key = urldecode($key);
        if(strpos($key, $keybase) === 0) {
          $ziptargets []= $cwd.substr($key, strlen($keybase) - 1);
        }
      }

      // Download the entire folder otherwise
      if(count($ziptargets) == 1) {
        $ziptargets = array_merge($ziptargets, scan_children($cwd));
      }

      require_once 'zipdl.php';
    break;
  }
}
?>