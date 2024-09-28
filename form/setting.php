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
    <title>Profile Settings</title>
    <link rel="stylesheet" href="../css/setting.css">
    <link rel="stylesheet" href="../css/nav.css">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/Login.js"></script>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="../index.php">Home</a>
    </div>
    <?php
    if (isset($_SESSION['user_id'])) {
        $user = new User($conn, $_SESSION['user_id']);
    }
    ?>
    <div class="setting">
        <form action="../action/update_profile.php" method="POST" enctype="multipart/form-data">
            <h2>Update Profile</h2>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value = "<?php echo $user->getUsername(); ?>"required>

            <label for="user_profile">Pic Profile:</label>
            <input type="file" id="profile_picture" name="profile" accept="image/png,image/jfif,image/jpeg"
                   onchange="previewImage(event)">

            <div class="image-preview" id="imagePreview" style="display: none;">
                <img id="preview" src="#" alt="Profile Preview">
            </div>

            <input type="hidden" value="<?php echo $user->getId(); ?>" name="user_id">
            <input type="submit" value="Update Profile">
        </form>
    </div>
</div>
<script src="../script/PreviewImg.js"></script>
<script src="../script/BlockMessageCallback.js"></script>
</body>
</html>
