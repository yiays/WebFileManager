<?php
require_once 'fileview.php';

$dirs = glob($cwd.'*', GLOB_ONLYDIR);
$files = glob($cwd.'*');
$files = array_diff($files, $dirs);

natsort($dirs);
natsort($files);

foreach($dirs as $dirf){
	$extra = '';
	if($cwd == $root){
		if($dirf == $cwd.'user'){
			$type = 'drive&mod=personal';
			$quota = get_quota($cwd.'user/'.$username.'/');
			$free = $quota['free'];
			$total = $quota['total'];
		}else{
			$type = 'drive';
			$free = disk_free_space($dirf);
			$total = disk_total_space($dirf);
		}
		$percentage = round((($total-$free) / $total) * 100);
		$freeh = human_filesize($free);
		$totalh = human_filesize($total);
		$extra = "<span class=\"usage\">$freeh free</span>\n".
						"<span class=\"usage-bar\"><progress value=$percentage max=100 title=\"$totalh total\"></span>";
	}
	else{
		$type = glob($dirf.'/*')?'folder':'folder&mod=empty';
		if(is_link($dirf)) $type .= '&shortcut';
	}
	$modtime = date("d/m/Y H:i:s", filemtime($dirf));
	$dir = basename($dirf);
	$url = canonicalurl($dirf);
	if(strpos($dir, '.') !== 0 && !in_array($dir, $ignores)){
		$printed++;
		print("
		<a class=\"item ".(strpos($type, 'drive')===0?'drive':'dir')."\" href=\"$url/\" title=\"$dir\">
			<img src=\"/icongen.php?nodetype=$type\" alt=\"folder icon\"/>
			<span class=\"name\">$dir</span>
			<span class=\"moddate\">$modtime</span>
			$extra
		</a>");
	}
}
foreach($files as $filef){
	$type = 'file';
	if(is_link($filef)) $type .= '&shortcut';
	$ext = pathinfo($filef, PATHINFO_EXTENSION);
	$modtime = date("d/m/Y H:i:s", filemtime($filef));
	$size = human_filesize(filesize($filef));
	$file = basename($filef);
	$url = canonicalurl($filef);
	if(strpos($file, '.') !== 0 && !in_array($file, $ignores)){
		$printed++;
		print("
		<a class=\"item file\" href=\"$url\" title=\"$file\">
			<img src=\"/icongen.php?nodetype=$type&ext=$ext\" alt=\"$ext icon\"/>
			<span class=\"name\">$file</span>
			<span class=\"moddate\">$modtime</span>
			<span class=\"size\">$size</span>
		</a>");
	}
}
?>