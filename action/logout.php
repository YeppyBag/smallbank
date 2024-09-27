<?php
include "../connect.inc.php";
session_start();
if(!isset($_SESSION['user_id'])){
    header('location: ../index.php');
}
session_destroy();
header('location: ../index.php');
