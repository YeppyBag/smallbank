<?php
include "../connect.inc.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];

if ($password !== $cpassword) {
    header("Location: ../form/login.php?register-error=Passwords do not match.");
    exit();
}

$sql_check = "SELECT * FROM tb_user WHERE username = '$username'";
$result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
    header("Location: ../form/login.php?register-error=Username already exists.");
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO tb_user (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
$query = mysqli_query($conn, $sql);

if ($query) {
    header("Location: ../form/login.php?register-handle=Successfully registered.");
    exit();
} else {
    echo "Error registering user: " . mysqli_error($conn);
}
?>
