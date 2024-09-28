<?php

use common\User;

include "../connect.inc.php";
require_once "../common/User.php";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/login.js"></script>
    <link rel="stylesheet" href="../css/profile.css"> <!-- External CSS -->
</head>
<body>
<div class="container">
    <?php $user = new User($conn, $_SESSION['user_id']) ?>
    <div class="nav">
        <a href="../index.php">Home</a>
    </div>
    <div class="setting">
        <h2>Update Profile</h2>

        <form name="setting" method="post" action="../action/update_profile.php" enctype="multipart/form-data">
            <label for="user_name">Name:</label>
            <input type="text" name="username" required value="<?php echo $user->getUsername() ?>">

            <label for="user_profile">Pic Profile:</label>
            <input type="file" id="user_profile" name="profile" accept="image/png,image/jfif,image/jpeg"
                   onchange="previewImage(event)">

            <div class="image-preview" id="imagePreview" style="display: none;">
                <img id="preview" src="#" alt="Profile Preview">
            </div>

            <input type="hidden" value="<?php echo $user_id; ?>" name="user_id">
            <input type="submit" value="Edit">
        </form>

        <script>
            function previewImage(event) {
                const imagePreview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('preview');
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block'; // Show the preview
                }

                if (file) {
                    reader.readAsDataURL(file); // Read the file as Data URL
                }
            }
        </script>
    </div>
</div>
</body>
</html>
