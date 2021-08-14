<nav class="breadcrumb">
<?php
  require_once __DIR__.'/../router.php';
  $url = strlen($rawrequrl)>1?$rawrequrl:'';
    
  $partsum = '/';
  foreach(explode('/', $url) as $part){
    if($part!=''){
      $prefix = '<span>&gt;</span>';

      $partsum .= "$part/";

      if(strlen($partsum) > strlen($url)) $partsum = substr($partsum, 0, -1);

      if($partsum == $url & strrpos($url, '/') === strlen($url)-1) {
        $partsum.='?properties';
      }
      
      if(isset($breadcrumbskip)) {
        if ($breadcrumbskip > 0) {
          $breadcrumbskip--;
          continue;
        }elseif($breadcrumbskip == 0) {
          $breadcrumbskip--;
          $prefix = '';
        }
      }
      
      $part = urldecode($part);
      if(substr_count($partsum, '/') == 2){
        if(file_exists($root.$part.'/')) $part = ucfirst($part).' Drive';
        elseif($part == 'share') $part = 'Shared with me';
      }else $part = ucfirst($part);
      print("$prefix<a href=\"$partsum\">$part</a>");
    }elseif($partsum == '/') {
      print("<a href=\"/\">Home</a>");
    }
  }
?>
</nav>