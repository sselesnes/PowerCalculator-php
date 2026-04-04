<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

session_start();
define("ROOT_PATH", __DIR__);
require_once "engine/mysql.php";
$template = isset($_SESSION["user"]["id"]) ? "auth/" : "guest/";
?>

<html lang="uk">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="styles/styles.css" rel="stylesheet" />
    <title>PowerCalculator</title>
  </head>
  <body>
    <div class="container">
    <?php require_once "includes/" . $template . "header.php"; ?>
    <?php require_once "includes/" . $template . "content.php"; ?>
    </div>
    <?php require_once "includes/footer.php"; ?>      
  </body>
</html>
