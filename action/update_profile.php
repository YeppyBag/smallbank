<?php
include "../connect.inc.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
}

$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];

// Fetch the current user profile to get the old file path
$sql_fetch = "SELECT user_profile FROM tb_user WHERE user_id='$user_id'";
$result_fetch = mysqli_query($conn, $sql_fetch);

if (!$result_fetch) {
    echo "Error fetching user profile: " . mysqli_error($conn);
    exit();
}

$row = mysqli_fetch_assoc($result_fetch);
$old_file_path = $row['user_profile'];
// Check if a file was uploaded
if (isset($_FILES['user_profile']) && $_FILES['user_profile']['error'] == UPLOAD_ERR_OK) {
    // Specify the directory to save the uploaded images
    $upload_dir = '../uploads/'; // Ensure this directory exists and is writable
    $file_name = $_FILES['user_profile']['name'];
    $file_tmp = $_FILES['user_profile']['tmp_name'];

    // Generate a unique file name to prevent overwriting
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = 'profile_' . $user_name . '.' . $file_ext; // Include the file extension

    // Move the uploaded file to the designated directory
    if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
        $file_path = 'uploads/' . $new_file_name; // Store relative path for database

        // Optional: Delete the old file
        if (!empty($old_file_path) && file_exists('../' . $old_file_path)) {
            unlink('../' . $old_file_path); // Delete the old file
        }

        // Prepare SQL statement to update user details
        $sql = "UPDATE tb_user SET user_name='$user_name', user_profile='$file_path' WHERE user_id='$user_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $_SESSION['user_name'] = $user_name;
            session_write_close();
            echo "<script>
                    sessionStorage.setItem('successMessage', 'Profile updated successfully!');
                    window.location.href = '../index.php'; // Redirect to your page
                </script>";
            exit();
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload image: " . $_FILES['user_profile']['error'];
    }
} else {
    echo "No image uploaded or there was an error: " . ($_FILES['user_profile']['error'] ?? 'Unknown error');
}
