<?php
function search_parents($directory, $filename) {
	$dirstack = explode('/', $directory);
	for($i=count($dirstack)-1; $i>1; $i--){
		$file = implode('/', array_slice($dirstack, 0, $i)).'/'.$filename;
		if(file_exists($file)) return $file;
	}
	return false;
}

function human_filesize($bytes, $dec = 2) {
	$size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$factor = floor((strlen($bytes) - 1) / 3);

	return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) .' '. @$size[$factor];
}

function get_quota($dir) {
	$quotafile = search_parents($dir, '.quota');
	if($quotafile){
		$total = intval(file_get_contents($quotafile));
		$io = popen('/usr/bin/du -sk '.dirname($quotafile), 'r');
		$used = fgets($io, 4096);
		$used = intval(substr($used, 0, strpos ($used, "\t")));

		return ['total'=>$total, 'used'=>$used, 'free'=>$total-$used];
	}else{
		return False;
	}
}

function canonicalurl($path) {
	global $username;
	$path = str_replace($_SERVER['DOCUMENT_ROOT'].'/raw/user/'.$username, $_SERVER['DOCUMENT_ROOT'].'/raw/user', $path);
	$path = substr($path, strlen($_SERVER['DOCUMENT_ROOT'].'/raw/'));
	$path = urlencode($path);
	$path = str_replace('%2F', '/', $path);
	return '/'.$path;
}
?>