<?php
    include("../connect.inc.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>" />
    <script src="../script/login.js"></script>
    <title>Transfer</title>
</head>
<body>
    <div class="container">
        <form action="../action/money_transaction.php" method="POST">
            <input type="text" name="receiver" placeholder="Receiver Username"><br>
            <input type="number" name="balance" placeholder="Amount"><br>
            <input type="hidden" name="sender" value="<?php echo $_SESSION['user_id']?>">
            <input type="submit" value="Send">
            <input type="reset" value="Cancel">
        </form>
    </div>
</body>
</html>
