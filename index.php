<?php
    include("connect.inc.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="index.css">
    <title>Small Bank</title>
</head>
<body>
    <div class="container2">
        <div class="nav">
           <?php
           if(!empty($_SESSION['user_id'])){
               echo "<a href='action/logout.php'>logout</a>";
           }else{
               echo "<a href='form/login.php'>login</a>";
           }
           ?>
        </div>
    </div>
    <div class="container">
        <div class="balance">

        </div>
        <div class="quick"></div>
    </div>
</body>
</html>
