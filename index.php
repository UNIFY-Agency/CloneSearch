<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Search in code by Renan</title>
    <style media="screen">
    div {
    width: 100%;
    }

    h2 {
    font: 400 40px/1.5 Helvetica, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    }

    ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    }

    li {
    font: 200 20px/1.5 Helvetica, Verdana, sans-serif;
    border-bottom: 1px solid #ccc;
    }

    li:last-child {
    border: none;
    }

    li a {
    text-decoration: none;
    color: #000;
    display: block;
    width: 200px;

    -webkit-transition: font-size 0.3s ease, background-color 0.3s ease;
    -moz-transition: font-size 0.3s ease, background-color 0.3s ease;
    -o-transition: font-size 0.3s ease, background-color 0.3s ease;
    -ms-transition: font-size 0.3s ease, background-color 0.3s ease;
    transition: font-size 0.3s ease, background-color 0.3s ease;
    }

    li a:hover {
    font-size: 30px;
    background: #f6f6f6;
    }

    code {
      font-family: Consolas,"courier new";
      color: crimson;
      background-color: #f1f1f1;
      padding: 2px;
      font-size: 105%;
    }

    </style>
  </head>
  <body>

    <div>
      <h2>Search in code by Renan</h2>

      <form class="" action="" method="post">
        <label for="">Procurar ocorrência no projeto: </label>
        <input type="text" name="busca" value="">
        <input type="submit" value="Buscar">
      </form>

    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    ini_set('memory_limit', '256M');

    if(empty($_POST)){ exit(); }

    $directory = '../';
    $busca = $_POST['busca'];

    function listFolderFiles($dir, $busca){
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        echo '<ol>';
        search_in_files($dir, $busca);
        foreach($ffs as $ff){
            //search_in_files($ffs, $busca);
            //echo '<li>'.$ff;
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff, $busca);
            //echo '</li>';
        }
        echo '</ol>';
    }

    function search_in_files($directory, $busca){
      $dir = new DirectoryIterator($directory);
      foreach ($dir as $file) {
          $content = file_get_contents($file->getPathname());
          if (strpos($content, $busca) !== false) {

            $contagem = preg_match_all('/\b'.$busca.'\b/i', $content);
            $lastPos = 0;
            $positions = array();
            while (($lastPos = strpos($content, $busca, $lastPos))!== false) {
                  $positions[] = $lastPos;
                  $lastPos = $lastPos + strlen($busca);
              }

              // Displays 3 and 10
              foreach ($positions as $value) {
                  echo '<li><code>' .$directory. '/' .$file. ' na linha ' .$value. '</code></li>';
              }
          }
      }
    }

    listFolderFiles($directory, $busca);


    // $dir = $directory;
    // foreach (glob("$dir/*") as $file) {
    //     $content = file_get_contents("$dir/$file");
    //     if (strpos($content, $string) !== false) {
    //         echo $file;
    //     }
    // }

    ?>

    </div>

  </body>
</html>
