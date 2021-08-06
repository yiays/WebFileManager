<?php
$root = __DIR__."/raw/";
$cwd = __DIR__."/raw".urldecode($_SERVER['REQUEST_URI']);

$username = 'null';
$dirpasswd = search_parents($cwd, '.passwd');
if($dirpasswd){
	if(empty($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic');

    die('<h1>401 Unauthorized</h1>');
	}else{
		$passwds = file_get_contents($dirpasswd);
		if(strpos($passwds, md5("$_SERVER[PHP_AUTH_USER]:$_SERVER[PHP_AUTH_PW]")) === false){
			header('HTTP/1.1 401 Unauthorized');
			die('Authorization failed.');
		}else{
			$username = $_SERVER['PHP_AUTH_USER'];
		}
	}
}

if(strpos($cwd, __DIR__.'/raw/user') == 0){
	$cwd = str_replace(__DIR__.'/raw/user', __DIR__.'/raw/user/'.$username, $cwd);
	if(!file_exists(__DIR__.'/raw/user/'.$username.'/')){
		mkdir(__DIR__.'/raw/user/'.$username);
	}
}

$filemode = false;
if(!file_exists($cwd)){
	http_response_code(404);
	die();
}elseif(!is_dir($cwd)){
	$filemode = true;
}

$ignores = [];
$dirignore = search_parents($cwd, '.ignore');
if($dirignore){
	$ignores = array_merge($ignores, explode("\n", file_get_contents($dirignore)));
	if(in_array(basename($cwd), $ignores)){
		http_response_code(403);
		die();
	}
}

if(!$filemode){
	$viewmode = 'grid';
	if(isset($_POST['viewmode'])){
		setcookie('viewmode', $_POST['viewmode'], strtotime('+1 month'));
		$viewmode = $_POST['viewmode'];
	}
	elseif(isset($_COOKIE['viewmode'])){
		$viewmode = $_COOKIE['viewmode'];
	}
	$viewsize = '4em';
	if(isset($_POST['size'])){
		setcookie('size', $_POST['size'], strtotime('+1 month'));
		$viewsize = $_POST['size'];
	}
	elseif(isset($_COOKIE['size'])){
		$viewsize = $_COOKIE['size'];
	}
}else{
	include('types.php');

	$previewtype = find_type(pathinfo($cwd, PATHINFO_EXTENSION));
	$viewmode = 'preview filetype-'.$previewtype;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>X Drive Remote Access</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/index.css?v=25">
</head>
<body>
	<div class="fm">
		<nav class="breadcrumb">
			<?php
				$url = $_SERVER['HTTP_HOST'].(strlen($_SERVER['REQUEST_URI'])>1?$_SERVER['REQUEST_URI']:'');
					
				$partsum = "";
				foreach(explode('/', $url) as $part){
					if($part!=""){
						if($partsum==""){
							$partsum .= "http://$part";
							$prefix = "";
						}else{
							$partsum .= "/$part";
							$prefix = "<span>&gt;</span>";
						}
						
						$part = urldecode($part);
						print("$prefix<a href=\"$partsum/\">$part</a>");
					}
				}
			?>
		</nav>
		<div class="fileview <?php echo $viewmode; ?>" style="--size:<?php echo $viewsize; ?>;">
			<?php
				if(!$filemode){
					$dirs = glob($cwd.'*', GLOB_ONLYDIR);
					$files = glob($cwd.'*');
					$files = array_diff($files, $dirs);
					
					natsort($dirs);
					natsort($files);
					
					$printed = 0;
					
					foreach($dirs as $dirf){
						$extra = '';
						if($cwd == $root){
							$type = 'drive';
							if($dirf == $cwd.'user'){
								$quota = get_quota($cwd.'user/'.$username.'/');
								$free = $quota['free'];
								$total = $quota['total'];
							}else{
								$free = disk_free_space($dirf);
								$total = disk_total_space($dirf);
							}
							$percentage = round((($total-$free) / $total) * 100);
							$freeh = human_filesize($free);
							$totalh = human_filesize($total);
							$extra = "<span class=\"usage\">$freeh free</span>\n".
											"<span class=\"usage-bar\"><progress value=$percentage max=100 title=\"$totalh total\"></span>";
						}
						else $type = glob($dirf.'/*')?'folderfull':'folder';
						$modtime = date("d/m/Y H:i:s", filemtime($dirf));
						$dir = basename($dirf);
						$url = canonicalurl($dirf);
						if(strpos($dir, '.') !== 0 && !in_array($dir, $ignores)){
							$printed++;
							print("
							<a class=\"item ".($type=='drive'?'drive':'dir')."\" href=\"$url/\" title=\"$dir\">
								<img src=\"/icongen.php?type=$type\" alt=\"folder icon\"/>
								<span class=\"name\">$dir</span>
								<span class=\"moddate\">$modtime</span>
								$extra
							</a>");
						}
					}
					foreach($files as $filef){
						$ext = pathinfo($filef, PATHINFO_EXTENSION);
						$modtime = date("d/m/Y H:i:s", filemtime($filef));
						$size = human_filesize(filesize($filef));
						$file = basename($filef);
						$url = canonicalurl($filef);
						if(strpos($file, '.') !== 0 && !in_array($file, $ignores)){
							$printed++;
							print("
							<a class=\"item file\" href=\"$url\" title=\"$file\">
								<img src=\"/icongen.php?type=file&ext=$ext\" alt=\"$ext icon\"/>
								<span class=\"name\">$file</span>
								<span class=\"moddate\">$modtime</span>
								<span class=\"size\">$size</span>
							</a>");
						}
					}
				}else{
					$ext = pathinfo($cwd, PATHINFO_EXTENSION);
					$modtime = date("d/m/Y H:i:s", filemtime($cwd));
					$size = human_filesize(filesize($cwd));
					$file = basename($cwd);
					$dlurl = str_replace(['%2F','+'], ['/','%20'], urlencode(substr($cwd, strlen(__DIR__))));
					print("
					<div class=\"file-properties flex-row\">
						<img src=\"/icongen.php?type=file&ext=$ext\" alt=\"$ext icon\"/>
						<div class=\"flex-stack\">
							<span class=\"name\"><i>File name:</i> $file</span>
							<span class=\"moddate\"><i>Last modified:</i> $modtime</span>
							<span class=\"size\"><i>Size:</i> $size</span>
							<a class=\"btn btn-primary btn-large\" href=\"$dlurl\">Download</a>
						</div>
					</div>");
					if($previewtype == 'video') {
						$mimetype = mime_content_type($cwd);
						print("
						<video width=\"100%\" controls>
							<source src=\"$dlurl\" type=\"$mimetype\">
							Unable to play this video in your browser
						</video>");
					}elseif($previewtype == 'image') {
						print("
						<img src=\"$dlurl\" style=\"max-width:100%;\" alt=\"$file\"/>");
					}elseif($previewtype == 'audio') {
						$mimetype = mime_content_type($cwd);
						print("
						<audio style=\"width:100%;\" controls>
							<source src=\"$dlurl\" type=\"$mimetype\">
							Unable to play this audio in your browser
						</audio>");
					}elseif($previewtype == 'plaintext'){
						if($size <= 2000){
							print("
							<form method=\"POST\">
								<textarea name=\"filecontent\" rows=\"10\"  style=\"width:100%;\">".htmlspecialchars(file_get_contents($cwd))."</textarea>
								<input type=\"submit\" class=\"btn\" value=\"Save changes\">
							</form>");
						}
					}
					$printed = 1;
				}
				
				if($printed == 0){
					print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
				}
			?>
		</div>
		<?php if(!$filemode){ ?>
		<div class="fv-footer">
			<span class="file-count"><?php echo "$printed item(s)"; ?></span>
			<form class="view-selector" method="POST">
				<select name="size">
					<option value="4em"<?php echo $viewsize=='4em'?' selected':''; ?>>Small</option>
					<option value="6em"<?php echo $viewsize=='6em'?' selected':''; ?>>Medium</option>
					<option value="8em"<?php echo $viewsize=='8em'?' selected':''; ?>>Large</option>
				</select>
				&nbsp;|&nbsp;
				<input type="radio" id="gridview" name="viewmode" value="grid"<?php echo $viewmode=='grid'?' checked':''; ?>>
				<label for="gridview">Grid</label>
				<input type="radio" id="listview" name="viewmode" value="list"<?php echo $viewmode=='list'?' checked':''; ?>>
				<label for="listview">List</label>
				&nbsp;
				<input type="submit" value="💾">
			</form>
		</div>
		<?php } ?>
	</div>
</body>
</html>
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
	$path = str_replace(__DIR__.'/raw/user/'.$username, __DIR__.'/raw/user', $path);
	$path = substr($path, strlen(__DIR__.'/raw/'));
	$path = urlencode($path);
	$path = str_replace('%2F', '/', $path);
	return '/'.$path;
}
?>
