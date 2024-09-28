<?php
include "../connect.inc.php";
$page = $_GET['id'];

switch ($page) {
    case 1:
        $_SESSION['page'] = "form/deposit.php";
        $_SESSION['nameIframe'] = "ฝากเงิน";
        header('location:../index.php');
        break;
    case 2:
        $_SESSION['page'] = "form/withdraw.php";
        $_SESSION['nameIframe'] = "ถอนเงิน";
        header('location:../index.php');
        break;
    case 3:
        $_SESSION['page'] = "form/withdraw.php";
        $_SESSION['nameIframe'] = "ถอนเงิน";
        header('location:../index.php');
        break;
    case 4:
        $_SESSION['page'] = "form/withdraw.php";
        $_SESSION['nameIframe'] = "ถอนเงิน";
        header('location:../index.php');
        break;
    default:

}


?>