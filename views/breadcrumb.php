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
      
      if(isset($breadcrumbskip) & $breadcrumbskip > 0) {
        $breadcrumbskip--;
        continue;
      }
      
      $part = urldecode($part);
      print("$prefix<a href=\"$partsum/\">$part</a>");
    }
  }
?>
</nav>