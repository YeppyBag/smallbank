<?php
include "../connect.inc.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <title>Deposit</title>
</head>
<body>
<form name="disposit_money" method="post" action="../action/deposit.php">
    <div class="handle-message">
        <?php if (isset($_GET['disposit-handle'])) echo htmlspecialchars($_GET['disposit-handle']); ?>
    </div>
    <div class="error-message">
        <?php if (isset($_GET['disposit-error'])) echo htmlspecialchars($_GET['disposit-error']); ?>
    </div>
    <input type="number" name="amount" placeholder="ใส่จำนวนเงิน"><br>
    <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
    <input type="submit" value="deposit">
    <input type="reset" value="cancel">
</form>
</body>
</html>