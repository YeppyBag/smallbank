<?php
    include "../connect.inc.php";
    $page = $_GET['id'];

    if($page == 1){
        $_SESSION['page'] = "form/deposit.php";
        header('location:../index.php');
    }else {
        $_SESSION['page'] = "form/withdraw.php";
        header('location:../index.php');
    }
?>