<?php
require_once('filebrowser.php');

$filter = "";
$fileBrowser = new FileBrowser("rootFile/");
// ugh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: it is only accepting one filter for now
    $filter = $_POST['filter'];
    $fileBrowser->SetExtensionFilter([$filter]);
} else {
    if ($path = $_GET['path']) {
        $fileBrowser->SetCurrentPath($path);
    }
}
$files = $fileBrowser->Get();
?>
<!doctype html>
<html lang="en">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

     <title>File browser</title>
 </head>
 <body>

    <h1>File Browser (WIP)</h1>

    <form class="form-inline" method="post">
        <div class="form-group mx-sm-3 mb-2">
            <!-- This could be defined from the list of current types on the current folder - via ajax -->
            <!-- And also using some nice multiple select -->
            <select class="form-control" name="filter">
                <option value="" <?php echo $filter == '' ? 'selected' : '' ?>>All</option>
                <option value="txt" <?php echo $filter == 'txt' ? 'selected' : '' ?>>TXT Files</option>
                <option value="html" <?php echo $filter == 'html' ? 'selected' : '' ?>>HTML Files</option>
                <option value="jpeg,jpg,png" <?php echo $filter == 'jpeg,jpg,png' ? 'selected' : '' ?>>Image Files</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mb-2">Filter</button>
    </form>

     <table class="table table-bordered">
         <thead>
         <tr>
             <th scope="col">Name</th>
             <th scope="col">Size</th>
             <th scope="col">Type</th>
         </tr>
         </thead>
         <tbody>
         <?php if (empty($files)): ?>
             <tr>
                 <td colspan="4">No files found</td>
             </tr>
         <?php else:
            foreach ($files as $file): ?>
                <tr>
                    <td scope="row">
                        <?php if ($file['directory']): ?><a href="?path=<?=$file["file_name"]?>"><?php endif; ?>
                        <?php echo $file["file_name"]; ?>
                        <?php if ($file['directory']): ?></a><?php endif; ?>
                    </td>
                    <td><?php echo $file["extension"]; ?></td>
                    <td><?php echo $file["size"]; ?></td>
                </tr>
            <?php endforeach;
         endif ?>
         </tbody>
     </table>
 <?php


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
