<?php

use common\Point;

include "../connect.inc.php";
require_once "../common/Point.php";

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT * FROM tb_user WHERE username = '$username'";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0) {
    $arr = $query->fetch_assoc();
    if (password_verify($password, $arr["password"])) {
        $point = new Point($conn, $arr["user_id"]);
        $point->deleteExpiredPoints();
        $_SESSION['user_id'] = $arr["user_id"];
        $_SESSION['username'] = $arr["username"];
        header("Location: ../index.php");
        exit();
    } else {
        header("Location: ../form/login.php?login-error=" . "Invalid username or password.");
        exit();
    }
} else {
    header("Location: ../form/login.php?login-error=" . "Invalid username or password.");
    exit();
}
