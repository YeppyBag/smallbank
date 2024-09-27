<?php

use common\FeatureUtil;

include "../common/FeatureUtil.php";

session_start();
?>

<html>
<head>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/pinkbutton.css">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>"/>
    <script src="../script/login.js"></script>
    <title>Sign Up / Login</title>
</head>
<body>
<div class="main">
    <input type="checkbox" id="mm" aria-hidden="true">

    <div class="signup">
        <form name="register-form" method="post" action="../action/register.php">
            <label for="mm" aria-hidden="true">Sign up</label>
            <?php
            FeatureUtil::displayMessage('error', $_GET['register-error'] ?? null);
            FeatureUtil::displayMessage('handle', $_GET['register-handle'] ?? null);
            ?>
            <input type="text" name="username" maxlength="80" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
            <button>Sign up</button>
        </form>
    </div>

    <div class="login">
        <form name="login-form" method="post" action="../action/login.php">
            <label for="mm" aria-hidden="true">Login</label>
            <?php
            FeatureUtil::displayMessage('error', $_GET['login-error'] ?? null);
            FeatureUtil::displayMessage('handle', $_GET['login-handle'] ?? null);
            ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button>Login</button>
            <button onclick="window.location.href='../index.php';">หน้าหลัก</button>
        </form>
    </div>
</div>
</body>
</html>