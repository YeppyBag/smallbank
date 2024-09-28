<?php

use common\FeatureUtil;
use common\User;

include "../connect.inc.php";
require_once "../common/FeatureUtil.php";
require_once "../common/User.php";
?>
<!doctype html>
<html lang="th">
<head>
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/transfer.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/Login.js"></script>
    <title>Transfer</title>
</head>
<body>
<?php
$user = new User($conn, $_SESSION['user_id']);
?>
<div class="container">
    <div class="nav">
        <a href="../index.php">Home</a>
    </div>
    <h2>Transfer Money</h2>
    <div class="user-info">
        <div class="profile">
            <div class="profile-image"
                 style="background-image: url('<?php echo "../" . $user->getProfile(); ?>');"></div>
        </div>
        <p class="user-name"><?php echo $user->getUsername(); ?></p>
    </div>
    <form name="transfer_money" method="POST" action="../action/check_transaction.php">
        <?php
        FeatureUtil::displayMessage('handle', $_GET['transfer-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['transfer-error'] ?? null);
        ?>
        <div class="transfer-method">
            <label class="radio">
                <input type="radio" name="transfer_type" value="username" checked
                       onclick="updatePlaceholder('username')">
                <span class="name">By Username</span>
            </label>
            <label class="radio">
                <input type="radio" name="transfer_type" value="email" onclick="updatePlaceholder('email')">
                <span class="name">By Email</span>
            </label>
        </div>
        <input type="text" id="receiver" name="receiver" placeholder="Receiver Username" required><br>
        <script>
            function updatePlaceholder(type) {
                const receiverInput = document.getElementById('receiver');
                if (type === 'username') {
                    receiverInput.placeholder = "Receiver Username";
                } else if (type === 'email') {
                    receiverInput.placeholder = "Receiver Email";
                }
            }
        </script>

        <input type="number" name="amount" placeholder="Amount" required min="0"><br>
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="2" name="transaction_type_id"> <!-- Send in tb_transaction_type 2 -->

        <input type="submit" value="Send" class="btn-send">
        <input type="reset" value="Cancel" class="btn-cancel">
    </form>
</div>
</body>
</html>
