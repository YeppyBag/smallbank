<?php
session_start();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link rel="stylesheet" type="text/css" href="../css/redirect.css">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>" />
    <script src="../script/login.ts"></script>
    <title>Sign Up / Login</title>
</head>
<body>
<div class="main">
    <input type="checkbox" id="mm" aria-hidden="true">

    <div class="signup">
        <form name="register-form" method="post" action="../action/register.php">
            <label for="mm" aria-hidden="true">Sign up</label>
            <div class="error-message">
                <?php if (isset($_GET['register-error'])) echo htmlspecialchars($_GET['register-error']); ?>
            </div>
            <div class="handle-message">
                <?php if (isset($_GET['register-handle'])) echo htmlspecialchars($_GET['register-handle']); ?>
            </div>
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
            <div class="error-message">
                <?php if (isset($_GET['login-error'])) echo htmlspecialchars($_GET['login-error']); ?>
            </div>
            <div class="handle-message">
                <?php if (isset($_GET['login-handle'])) echo htmlspecialchars($_GET['login-handle']); ?>
            </div>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button>Login</button>

            <a href="../index.php">  Back to Home</a>
            <br>
            <a href="../index.php">  Forgot the password</a>
        </form>
    </div>
</div>
</body>
</html>