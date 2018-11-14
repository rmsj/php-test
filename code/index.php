<?php
require_once('filebrowser.php');

$filter = "";
$fileBrowser = new FileBrowser("rootFiles/");
// ugh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: it is only accepting one filter for now
    $filter = empty($_POST['filter']) ? "" : $_POST['filter'];
    $fileBrowser->SetExtensionFilter(explode(",", $filter));
}
if ($path = $_GET['path']) {
    $fileBrowser->SetCurrentPath($path);
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

    <main role="main">
        <div class="row m-3">
            <div class="col-12">
                <h1>JOY File Browser</h1>

                <hr/>

                <form class="form-inline float-left" method="post">
                    <div class="form-group mb-2">
                        <!-- This could be defined from the list of current types on the current folder - via ajax -->
                        <!-- And also using some nice multiple select -->
                        <label class="pr-2"> Filter by file type: </label>
                        <select class="form-control form-control-sm" name="filter">
                            <option value="" <?php echo $filter == '' ? 'selected' : '' ?>>All</option>
                            <option value="txt" <?php echo $filter == 'txt' ? 'selected' : '' ?>>TXT Files</option>
                            <option value="html" <?php echo $filter == 'html' ? 'selected' : '' ?>>HTML Files</option>
                            <option value="jpeg,jpg,png" <?php echo $filter == 'jpeg,jpg,png' ? 'selected' : '' ?>>Image Files</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm ml-2 mb-2"> Filter </button>
                </form>
                <a href="/" class="btn btn-primary btn-sm mb-2 float-right"> &#9978; Home</a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Size</th>
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
                                        <?php if ($file['directory']): ?><a href="?path=<?=$file["link"]?>"><?php endif; ?>
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
                </div>
            </div>
        </div>
    </main>
 </body>
</html>
