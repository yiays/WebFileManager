<?php
require_once 'fileview.php';

// Get folders and files
$dirs = glob($cwd.'*', GLOB_ONLYDIR);
$files = glob($cwd.'*');
$files = array_diff($files, $dirs);

// Sort and count them
natsort($dirs);
natsort($files);
$totalnodes = count($dirs) + count($files);

foreach($dirs as $dirf){
	if($printed >= NODE_LIMIT) break;
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
		$url = '/'.canonicalurl($dirf);
	}
	else{
		$type = glob($dirf.'/*')?'folder':'folder&mod=empty';
		if(is_link($dirf)) $type .= '&shortcut';
		$url = canonicalurl($dirf);
	}
	$modtime = date("d/m/Y H:i:s", filemtime($dirf));
	$dir = basename($dirf);
	$sharestatus = share_status($dirf);
	if(strpos($dir, '.') !== 0 && !in_array($dir, $ignores)){
		$printed++;
		print("
			<a class=\"item ".(strpos($type, 'drive')===0?'drive':'dir')."\" href=\"$url/\" title=\"$dir\">
				<img src=\"/icongen.php?nodetype=$type&sharing=$sharestatus\" alt=\"folder icon\"/>
				<span class=\"name\">$dir</span>
				<span class=\"moddate\">$modtime</span>
				$extra
			</a>");
	}
}
foreach($files as $filef){
	if($printed >= NODE_LIMIT) break;
	$type = 'file';
	if(is_link($filef)) $type .= '&shortcut';
	$ext = pathinfo($filef, PATHINFO_EXTENSION);
	$modtime = date("d/m/Y H:i:s", filemtime($filef));
	$size = human_filesize(filesize($filef));
	$file = basename($filef);
	$url = canonicalurl($filef);
	$sharestatus = share_status($filef);
	if(strpos($file, '.') !== 0 && !in_array($file, $ignores)){
		$printed++;
		print("
		<a class=\"item file\" href=\"$url\" title=\"$file\">
			<img src=\"/icongen.php?nodetype=$type&ext=$ext&sharing=$sharestatus\" alt=\"$ext icon\"/>
			<span class=\"name\">$file</span>
			<span class=\"moddate\">$modtime</span>
			<span class=\"size\">$size</span>
		</a>");
	}
}
?>