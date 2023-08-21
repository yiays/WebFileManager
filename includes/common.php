<?php
ini_set('default_socket_timeout', 1);

function search_parents($directory, $filename) {
	$dirstack = explode('/', $directory);
	for($i=count($dirstack)-1; $i>1; $i--){
		$file = implode('/', array_slice($dirstack, 0, $i)).'/'.$filename;
		if(file_exists($file)) return $file;
	}
	return false;
}
function scan_children($directory) {
	$files = [];
	$subnodes = glob($directory.'*');
	foreach($subnodes as $node) {
		if(is_dir($node)) $node .= '/';
		if(count($files) > NODE_LIMIT) break;
		$files []= $node;
		if(is_dir($node)) $files = array_merge($files, scan_children($node));
	}
	if(count($files) > NODE_LIMIT) {
		print("Too many items to compress in one go!<br>");
		print_r($files);
		die();
	}
	return $files;
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
		pclose($io);
		$used = intval(substr($used, 0, strpos($used, "\t")));

		return ['total'=>$total, 'used'=>$used, 'free'=>$total-$used];
	}else{
		return False;
	}
}

function canonicalurl($path) {
	$path = basename($path);
	$path = urlencode($path);
	$path = str_replace('%2F', '/', $path);
	return $path;
}

// Get share data and parse it in advance
$sharedata = explode("\n", file_get_contents($_SERVER['DOCUMENT_ROOT'].'/.shares'));
$shares = [];
foreach($sharedata as $share) {
	if(substr_count($share, ':') == 2) {
		$parsedsharedata = explode(':', $share);
		$shares [$parsedsharedata[1]] = $parsedsharedata[2];
	}
}
function share_status($node) {
	global $shares;
	foreach($shares as $rootsharepath => $sharees) {
		if(strpos(substr($node, strlen($_SERVER['DOCUMENT_ROOT'].'/raw')).'/', $rootsharepath) === 0) {
			if($sharees == '*') return 'public';
			else return 'shared';
		}
	}
	return '';//'locked';
}
?>