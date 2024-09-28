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
    <form name="transfer.php" method="POST" action="confirm_transfer.php">
        <?php
        FeatureUtil::displayMessage('handle', $_GET['transfer-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['transfer-error'] ?? null);
        ?>
        <div class="transfer-method">
            <label>
                <input type="radio" name="transfer_type" value="username" checked>
                By Username
            </label>
            <label>
                <input type="radio" name="transfer_type" value="email">
                By Email
            </label>
        </div>

        <input type="text" name="receiver" placeholder="Receiver Username or Email" required><br>
        <input type="number" name="amount" placeholder="Amount" required min="0"><br>

        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="2" name="transaction_type_id"> <!-- Send in tb_transaction_type 2 -->

        <input type="submit" value="Send" class="btn-send">
        <input type="reset" value="Cancel" class="btn-cancel">
    </form>
</div>
</body>
</html>
