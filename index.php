<?php
$cwd = __DIR__."/raw".urldecode($_SERVER['REQUEST_URI']);
if(!file_exists($cwd)){
	http_response_code(404);
	die();
}

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
		}
	}
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
	<link rel="stylesheet" href="/index.css?v=23">
</head>
<body>
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
			$dirs = glob($cwd.'*', GLOB_ONLYDIR);
			$files = glob($cwd.'*');
			$files = array_diff($files, $dirs);
			
			natsort($dirs);
			natsort($files);
			
			$printed = 0;
			
			foreach($dirs as $dir){
				$type = glob($dir.'/*')?'folderfull':'folder';
				$modtime = date("d/m/Y H:i:s", filemtime($dir));
				$dir = str_replace($cwd, '', $dir);
				$url = urlencode($dir);
				if(strpos($dir, '.') !== 0 && !in_array(basename($dir), $ignores)){
					$printed++;
					print("
					<a class=\"item dir\" href=\"$url/\" title=\"$dir\">
						<img src=\"/icongen.php?type=$type\" alt=\"folder icon\"/>
						<span class=\"name\">$dir</span>
						<span class=\"moddate\">$modtime</span>
					</a>");
				}
			}
			foreach($files as $filef){
				$ext = pathinfo($filef, PATHINFO_EXTENSION);
				$modtime = date("d/m/Y H:i:s", filemtime($filef));
				$size = human_filesize(filesize($filef));
				$file = str_replace($cwd, '', $filef);
				$url = urlencode($file);
				if(strpos($file, '.') !== 0 && !in_array(basename($file), $ignores)){
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
			
			if($printed == 0){
				print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
			}
		?>
		<div class="fv-footer">
			<span class="file-count"><?php echo "$printed items"; ?></span>
			<form class="view-selector" method="POST">
				<input type="radio" id="sizesmall" name="size" value="4em"<?php echo $viewsize=='4em'?' checked':''; ?>>
				<label for="sizesmall">Small</label>
				<input type="radio" id="sizemed" name="size" value="6em"<?php echo $viewsize=='6em'?' checked':''; ?>>
				<label for="sizemed">Medium</label>
				<input type="radio" id="sizelarge" name="size" value="8em"<?php echo $viewsize=='8em'?' checked':''; ?>>
				<label for="sizelarge">Large</label>
				&nbsp;|&nbsp;
				<input type="radio" id="gridview" name="viewmode" value="grid"<?php echo $viewmode=='grid'?' checked':''; ?>>
				<label for="gridview">Grid</label>
				<input type="radio" id="listview" name="viewmode" value="list"<?php echo $viewmode=='list'?' checked':''; ?>>
				<label for="listview">List</label>
				&nbsp;
				<input type="submit" value="💾">
			</form>
		</div>
	</div>
</body>
</html>
<?php
function search_parents($directory, $filename){
	$dirstack = explode('/', $directory);
	for($i=count($dirstack)-1; $i>1; $i--){
		$file = implode('/', array_slice($dirstack, 0, $i)).'/'.$filename;
		if(file_exists($file)) return $file;
	}
	return false;
}
function human_filesize($bytes, $dec = 2) 
{
	$size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$factor = floor((strlen($bytes) - 1) / 3);

	return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
?>
