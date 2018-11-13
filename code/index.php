<?php
require_once('filebrowser.php');

/*
 * Add your filebrowser definition code here
*/
?>
<!doctype html>
<html lang="en">
 <head>
  <title>File browser</title>
 </head>
 <body>
 <?php

 $fileBrowser = new FileBrowser("rootFile/");
 $fileBrowser->SetExtensionFilter(["txt", "html"]);
 $files = $fileBrowser->Get();
 print_r($files);


/*
 $dir = "rootFile/";
 $files = [];
 foreach (glob("{$dir}*.{txt,html}", GLOB_BRACE) as $filename) {
     $files[] = "$filename size " . filesize($filename) . "<br/>";
 }

 print_r($files);*/

 ?>
 </body>
</html>
