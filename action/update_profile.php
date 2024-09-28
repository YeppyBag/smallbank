<?php

use common\User;

include "../connect.inc.php";
require_once "../common/User.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
}

$user_id = $_POST['user_id'];
$user_name = $_POST['username'];
$user = new User($conn, $user_id);

if (!$user->getUserInfo()) {
    echo "Error fetching user profile: " . mysqli_error($conn);
    exit();
}

$row = $user->getUserInfo();
$old_file_path = $row['profile'];

if (isset($_FILES['profile']) && $_FILES['profile']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = '../profiles/';
    $file_name = $_FILES['profile']['name'];
    $file_tmp = $_FILES['profile']['tmp_name'];

    // Corrected: extract the extension from the actual file being uploaded
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check file size (limit to 2MB)
    if ($_FILES['profile']['size'] > 2097152) {
        echo "Sorry, your file is too large.";
        exit;
    }

    // Allowed file types
    $allowed_types = ['jpg', 'png', 'jpeg', 'jfif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & JFIF files are allowed.";
        exit;
    }

    // Generate a unique file name to prevent overwriting
    $new_file_name = 'profile_' . $user_name . '.' . $imageFileType; // Corrected: Use the correct file extension

    // Move the uploaded file to the designated directory
    if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
        $file_path = 'profiles/' . $new_file_name; // Store relative path for database

        // Optional: Delete the old file, but skip if it's the default profile image
        if (!empty($old_file_path) && file_exists('../' . $old_file_path) && $old_file_path !== 'img/default-profile.png') {
            unlink('../' . $old_file_path); // Delete the old file, but not the default
        }

        // Prepare SQL statement to update user details
        $sql = "UPDATE tb_user SET username='$user_name', profile='$file_path' WHERE user_id='$user_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $_SESSION['username'] = $user_name;
            session_write_close();
            echo "<script>
                    sessionStorage.setItem('successMessage', 'Profile updated successfully!');
                    window.location.href = '../index.php';
                </script>";
            exit();
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload image: " . $_FILES['profile']['error'];
    }
} else {
    echo "No image uploaded or there was an error: " . ($_FILES['profile']['error'] ?? 'Unknown error');
}
