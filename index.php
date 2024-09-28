<?php

use common\Transaction;
use common\User;

include("connect.inc.php");
include "common/Transaction.php";
require_once "common/User.php";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/index.css">
    <title>Small Bank</title>
</head>

<body>
<div class="container2">
    <div class="nav">
        <?php
        if (!empty($_SESSION['user_id'])) {
            echo "<a href='action/logout.php'>logout</a>";
        } else {
            echo "<a href='form/login.php'>login</a>";
        }
        ?>
    </div>
</div>
<div class="container">
    <div class="balance">
        <div class="name">
            <?php
            if (!empty($_SESSION['user_id'])) {
                echo "<H1>" . $_SESSION['username'] . "</H1>";
            } else
                echo "<h1>Welcome</h1>";
            ?>
        </div>
        <div class="wallet">
            <?php
            if (!empty($_SESSION['user_id'])) {
                $id = $_SESSION['user_id'];
                $user = new User($conn, $id);
                echo "<h1>" . $user->getWalletBalance() . "</h1>";
            }
            ?>
        </div>
        <?php
        if (!empty($_SESSION['user_id'])) {
            echo "<a href='action/getIframe.php?id=1'>ฝากเงิน</a><a href='action/getIframe.php?id=2'>ถอนเงิน</a><br><br><br><a href='action/getIframe.php?id=3'>โอนเงิน</a><a href='action/getIframe.php?id=4'>Point</a>";
        }
        ?>
    </div>
    <div class="quick">
        <?php //TODO: Iframe ใน หน้า index ดูไม่ค่อย work ต้อง คอย refresh เพื่อให้ update element ?>
        <div class="name">
            <?php
            if (!empty($_SESSION['nameIframe'])) {
                echo "<h1>" . $_SESSION['nameIframe'] . "</h1>";
            } else
                echo "";
            ?>
        </div>
        <iframe src="<?php if (empty($_SESSION['page'])) {
            echo "";
        } else {
            echo $_SESSION['page'];
        } ?>" frameborder="0"></iframe>
    </div>
    <div class="transac">
        <div class="document">
            <?php
            if (!empty($_SESSION['user_id'])) {
                $transaction = new Transaction($conn, $_SESSION['user_id']);
                $transactionData = $transaction->getTransactionByUserId($_SESSION['user_id']);
                // ตรวจสอบว่ามีธุรกรรมหรือไม่
                if (!empty($transactionData)) {
                    echo "<table border='1'>";
                    echo "<tr>
                        <th>Transaction ID</th>
                        <th>User ID</th>
                        <th>Transaction Type ID</th>
                        <th>Amount</th>
                        <th>Fee</th>
                        <th>Recipient User ID</th>
                        <th>Created At</th>
                      </tr>";

                    // ใช้ foreach เพื่อแสดงข้อมูลธุรกรรม
                    foreach ($transactionData as $transaction) {
                        echo "<tr>
                            <td>{$transaction['transaction_id']}</td>
                            <td>{$transaction['user_id']}</td>
                            <td>{$transaction['transaction_type_id']}</td>
                            <td>{$transaction['amount']}</td>
                            <td>{$transaction['fee']}</td>
                            <td>{$transaction['recipient_user_id']}</td>
                            <td>{$transaction['created_at']}</td>
                          </tr>";
                    }

                    echo "</table>";
                } else {
                    echo "ไม่มีข้อมูลธุรกรรม";
                }
            }
            ?>
        </div>
    </div>

</div>
</body>

</html>