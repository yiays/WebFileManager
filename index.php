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
	<link rel="stylesheet" href="/index.css?v=9">
</head>
<body>
	<nav class="breadcrumb">
		<?php
			$url = $_SERVER['HTTP_HOST'].(strlen($_SERVER['REQUEST_URI'])>1?$_SERVER['REQUEST_URI']:'');
				
			$partsum = "";
			foreach(explode('/', $url) as $part){
				if($part!=""){
					if($partsum=="") $partsum .= "http://$part";
					else $partsum .= "/$part";
					
					$part = urldecode($part);
					print("<a href=\"$partsum/\">$part</a>");
				}
			}
		?>
	</nav>
	<div class="fileview grid" style="--icon-size:4em;">
		<?php
			$dirs = glob($cwd.'*', GLOB_ONLYDIR);
			$files = glob($cwd.'*');
			$files = array_diff($files, $dirs);
			
			natsort($dirs);
			natsort($files);
			
			$printed = 0;
			
			foreach($dirs as $dir){
				$type = glob($dir.'/*')?'folderfull':'folder';
				$dir = str_replace($cwd, '', $dir);
				$url = urlencode($dir);
				if(strpos($dir, '.') !== 0 && !in_array(basename($dir), $ignores)){
					$printed++;
					print("<a class=\"item dir\" href=\"$url/\" title=\"$dir\"><img src=\"/icongen.php?type=$type\" alt=\"folder icon\"/><span class=\"name\">$dir</span></a>");
				}
			}
			foreach($files as $filef){
				$ext = pathinfo($filef, PATHINFO_EXTENSION);
				$file = str_replace($cwd, '', $filef);
				$url = urlencode($file);
				if(strpos($file, '.') !== 0 && !in_array(basename($file), $ignores)){
					$printed++;
					print("<a class=\"item file\" href=\"$url\" title=\"$file\"><img src=\"/icongen.php?type=file&ext=$ext\" alt=\"$ext icon\"/><span class=\"name\">$file</span></a>");
				}
			}
			
			if($printed == 0){
				print("<span class=\"item disabled\">¯\_(ツ)_/¯</span>");
			}
		?>
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
?>
