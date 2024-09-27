<?php
    include "../connect.inc.php";
    $page = $_GET['id'];

    if($page == 1){
        $_SESSION['page'] = "form/deposit.php";
        $_SESSION['nameIframe'] = "ฝากเงิน";
        header('location:../index.php');
    }else {
        $_SESSION['page'] = "form/withdraw.php";
        $_SESSION['nameIframe'] = "ถอนเงิน";
        header('location:../index.php');
    }


?>