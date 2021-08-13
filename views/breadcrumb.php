<nav class="breadcrumb">
<?php
  require_once __DIR__.'/../router.php';
  $url = $_SERVER['HTTP_HOST'].(strlen($rawrequrl)>1?$rawrequrl:'');
    
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
      
      if(isset($breadcrumbskip)) {
        if ($breadcrumbskip > 0) {
          $breadcrumbskip--;
          continue;
        }
      }
      
      $part = urldecode($part);
      print("$prefix<a href=\"$partsum/\">$part</a>");
    }
  }
?>
</nav>