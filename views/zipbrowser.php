<?php
require_once 'fileproperties.php';

define('EXE', 0);
define('LINE_SKIP_START', 1);
define('DATA_FORMAT', 2);
define('LINE_SKIP_END', 3);

$zipdrivers = [
	'zip' => ['/usr/bin/unzip -l ', 3, ['size','datetime','path'], 3],
	'mcworld' => ['/usr/bin/unzip -l ', 3, ['junk','junk','size','junk','junk','datetime','path'], 2],
	'tar' => ['/usr/bin/tar -tvf ', 0, ['junk','path','size','datetime','junk'], 0],
	'gz' => ['/usr/bin/tar -ztvf ', 0, ['junk','path','size','datetime','junk'], 0],
	'tgz' => ['/usr/bin/tar -ztvf ', 0, ['junk','path','size','datetime','junk'], 0],
	'bz2' => ['/usr/bin/tar -jtvf ', 0, ['junk','path','size','datetime','junk'], 0],
	'rar' => ['/usr/bin/unrar l ', 8, ['junk','size','datetime','path'], 2]
];

if(array_key_exists($ext, $zipdrivers)) {
	$driver = $zipdrivers[$ext];
	$params = array_flip($driver[DATA_FORMAT]);
	
	$io = popen($driver[EXE].escapeshellarg($cwd), 'r');
	$data = [];
	while($data[] = str_replace(["\n","\r"], '', fgets($io, 1024)));
	pclose($io);

	$files = [];
	$dirs = [];
	$cd = '';
	$lastcd = '';
	for($i=$driver[LINE_SKIP_START]; $i<count($data)-$driver[LINE_SKIP_END]; $i++) {
		$line = $data[$i];
		$fileinfo = array_values(array_diff(explode("  ", $line), ['']));
		if(count($fileinfo) != count($driver[DATA_FORMAT])) {
			print("ERROR: Zip data doesn't fit the provided format!<br>");
			print_r($fileinfo);
			print('<br>!=<br>');
			print_r($driver[DATA_FORMAT]);
			break;
		}

		$fullpath = trim($fileinfo[$params['path']]);
		$type = substr($fullpath, -1) == '/'?'folder':'file';
		$modtime = trim($fileinfo[$params['datetime']]);

		if($type=='file') {
			$name = basename($fullpath);
			$size = human_filesize(intval(preg_replace('/[\W]/', '', $fileinfo[$params['size']])));
			$ext = strpos($name, '.')?explode('.', $name)[-1]:'';
			print("
			<div class=\"item file\" title=\"$fullpath\">
				<img src=\"/icongen.php?nodetype=$type&ext=$ext\">
				<span class=\"name\">$name</span>
				<span class=\"moddate\">$modtime</span>
				<span class=\"size\">$size</span>
			</div>
			");
		}else{ // folder
			$cd = $fullpath;
			if(substr_count($cd, '/') < substr_count($lastcd, '/')){
				for($i=0;$i<substr_count($cd, '/')-substr_count($lastcd, '/');$i++) {
					print("</div>");
				}
			}elseif(substr_count($cd, '/') == substr_count($lastcd, '/') & $cd != $lastcd) {
				print("</div>");
			}
			$lastcd	= $cd;
			$name = explode('/', $cd)[substr_count($cd, '/')-1];
			print("
				<label class=\"item folder\" title=\"$fullpath\" for=\"ziptree-$i\">
					<img src=\"/icongen.php?nodetype=$type\">
					<span class=\"name\">$name</span>
					<span class=\"moddate\">$modtime</span>
				</label>
				<input type=\"checkbox\" class=\"collapse-toggle\" id=\"ziptree-$i\" hidden>
				<div class=\"foldercontent collapse-content\">
			");
		}
		$printed++;
	}
	for($i=0;$i<substr_count($cd, '/');$i++) {
		print("</div>");
	}
}
?>