<?php
require_once 'fileproperties.php';

define('EXE', 0);
define('DATA_SEPARATOR', 1);
define('LINE_SKIP_START', 2);
define('DATA_FORMAT', 3);
define('LINE_SKIP_END', 4);

$zipdrivers = [
	'zip' => ['/usr/bin/unzip -l %0', '  ', 3, ['size','datetime','path'], 3],
	'mcworld' => ['/usr/bin/unzip -l %0', '  ', 3, ['size','datetime','path'], 3],
	'tar' => ['/usr/bin/tar -tvf %0', ' ', 0, ['junk','junk','size','date','time','path'], 1],
	'gz' => ['/usr/bin/tar -ztvf %0', ' ', 0, ['junk','junk','size','date','time','path'], 1],
	'tgz' => ['/usr/bin/tar -ztvf %0', ' ', 0, ['junk','junk','size','date','time','path'], 1],
	'bz2' => ['/usr/bin/tar -jtvf %0', ' ', 0, ['junk','junk','size','date','time','path'], 1],
	'rar' => ['/usr/bin/unrar l %0', '  ', 8, [12,'size','datetime','path'], 4]
];

/*
	TODO: Refactor to remove the dependancy on listing directories.
*/

if(array_key_exists($ext, $zipdrivers)) {
	$driver = $zipdrivers[$ext];
	
	$cmd = str_replace('%0', escapeshellarg($cwd), $driver[EXE]).' 2>&1';
	/*$io = popen($cmd, 'r');
	$data = [];
	while($data[] = str_replace(["\n","\r"], '', fgets($io, 1024)));
	pclose($io);*/
	$data = explode(PHP_EOL, shell_exec("/usr/bin/timeout 1.5s $cmd"));

	$totalnodes = count($data);

	$pathtree = [];
	$lasttree = [];
	$declareddirs = [];
	$error = false;
	for($i=$driver[LINE_SKIP_START]; $i<min(count($data)-$driver[LINE_SKIP_END], NODE_LIMIT); $i++) {
		$format = $driver[DATA_FORMAT];
		$line = $data[$i];
		if(is_int($format[count($format)-1])) {
			$line = substr($line, 0, strlen($line) - $format[count($format)-1]);
			unset($format[count($format)-1]);
		}
		if(is_int($format[0])) {
			$line = substr($line, $format[0]);
			unset($format[0]);
		}
		$fileinfo = array_values(array_diff(explode($driver[DATA_SEPARATOR], $line), ['']));
		$params = array_flip(array_values($format));
		if(count($fileinfo) != count($format)) {
			$error = true;
			break;
		}

		$fullpath = str_replace('//', '/', trim($fileinfo[$params['path']]));
		// Gets array of folders leading to the current file
		$pathtree = strpos($fullpath, '/')!==false?explode('/', $fullpath):[$fullpath];
		$pathdepth = count($pathtree) - 2;
		$lastdepth = count($lasttree) - 1; // This doesn't seem right, but it works.
		// We discard the last element of the array and use it to find out if this path is a file
		$type = strlen(array_pop($pathtree))?'file':'folder';
		if(array_key_exists('datetime', $params))
			$modtime = trim($fileinfo[$params['datetime']]);
		else
			$modtime = trim($fileinfo[$params['date']].' '.$fileinfo[$params['time']]);

		// Close folders when the pathtree changes
		for($j=count($lasttree) - 1; $j > $pathdepth; $j--) {
			// Triggers whenever going back up the file tree
			print("</div>");//<!-- Up the file tree -->");
		}
		// Triggers whenever changing to another folder in the same path
		if(count($lasttree) <= count($pathtree)){
			if($lasttree[$lastdepth] != $pathtree[$lastdepth]) {
				print("</div>");//<!-- Changing dir ($lasttree[$lastdepth] to $pathtree[$lastdepth]) -->");
			}else{
				//print("<!-- $lasttree[$lastdepth] == $pathtree[$lastdepth] -->");
			}
		}else{
			if($lasttree[$pathdepth] != $pathtree[$pathdepth]) {
				print("</div>");//<!-- Changing dir ($lasttree[$pathdepth] to $pathtree[$pathdepth]) -->");
			}else{
				//print("<!-- $lasttree[$pathdepth] == $pathtree[$pathdepth] -->");
			}
		}
		$lasttree = $pathtree;
		
		// List the actual file
		if($type=='file') {
			$filename = basename($fullpath);
			// Create folders if they're not declared explicitly (unrar)
			// This means we can't derive modtime
			if(!in_array(substr($fullpath, 0, strlen($fullpath) - strlen($filename)), $declareddirs)) {
				$dirpath = '';
				$ii = 0;
				foreach($pathtree as $dirname) {
					$dirpath .= $dirname.'/';
					if(!in_array($dirpath, $declareddirs)) {
						$declareddirs []= $dirpath;
						print("
							<label class=\"item folder\" title=\"$dirpath\" for=\"ziptree-$i-$ii\">
								<img src=\"/icongen.php?nodetype=folder\">
								<span class=\"name\">$dirname</span>
							</label>
							<input type=\"checkbox\" class=\"collapse-toggle\" id=\"ziptree-$i-$ii\" hidden>
							<div class=\"foldercontent collapse-content\">
						");
						$ii++;
					}
				}
			}
			$size = human_filesize(intval(preg_replace('/[\W]/', '', $fileinfo[$params['size']])));
			$ext = substr_count($filename, '.')>0?explode('.', $filename)[substr_count($filename, '.')]:'';
			print("
			<div class=\"item file\" title=\"$fullpath\">
				<img src=\"/icongen.php?nodetype=$type&ext=$ext\">
				<span class=\"name\">$filename</span>
				<span class=\"moddate\">$modtime</span>
				<span class=\"size\">$size</span>
			</div>
			");
		}else{ // folder
			$declareddirs []= $fullpath;
			$dirname = $pathtree[$pathdepth];
			print("
				<label class=\"item folder\" title=\"$fullpath\" for=\"ziptree-$i\">
					<img src=\"/icongen.php?nodetype=$type\">
					<span class=\"name\">$dirname</span>
					<span class=\"moddate\">$modtime</span>
				</label>
				<input type=\"checkbox\" class=\"collapse-toggle\" id=\"ziptree-$i\" hidden>
				<div class=\"foldercontent collapse-content\">
			");
		}
		$printed++;
	}
	for($j=0;$j<count($pathtree);$j++) {
		print("</div>");
	}
	if($error | count($data) < ($driver[LINE_SKIP_START] + $driver[LINE_SKIP_END])) {
		$errline = $i;
		print("ERROR: Zip data doesn't fit the provided format! (Line $i)<br>");
		print("<textarea rows=30 style=\"width:100%\">");
		print(htmlspecialchars('> '.$cmd) . "\n");
		for($i=0; $i<min(count($data), 1024); $i++) {
			print("$i: ".htmlspecialchars($data[$i])."\n");
			if($i == $errline) {
				print("Expected format: ");
				print_r($format);
				print("Parsed data: ");
				print_r($fileinfo);
			}
		}
		print("</textarea>");
	}
}else{
	print("ERROR: Unable to preview the contents of this archive - missing driver.");
}
?>