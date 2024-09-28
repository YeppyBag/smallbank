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
    <script src="script/index.js" defer></script>
    <title>Small Bank</title>
</head>

<body>
    <div class="container">
        <dialog id="iframe-dialog">
            <a class="x"href="">X</a><br>
            <iframe id="iframe-content"></iframe>

        </dialog>
        <div class="nav">
            <?php
            if (!empty($_SESSION['user_id'])) {
                echo "<a href='action/logout.php'>logout</a>";
            } else {
                echo "<a href='form/login.php'>login</a>";
            }
            ?>
        </div>
        <div class="frame"></div>
        <div class="containerLR">
            <div class="left">
                <div class="profile"></div>
                <div class="balance">
                    <div class="name">
                        <div class="current">
                            <h3>Balance</h3>
                        </div>
                        <div class="username">
                            <?php
                            if (!empty($_SESSION['user_id'])) {
                                echo "<H4>" . $_SESSION['username'] . "</H4>";
                            } else
                                echo "";
                            ?>
                        </div>
                    </div>
                    <span class="line"></span>
                    <div class="wallet">
                        <?php
                        if (!empty($_SESSION['user_id'])) {
                            $id = $_SESSION['user_id'];
                            $user = new User($conn, $id);
                            echo "<h1>฿" . $user->getWalletBalance() . "</h1>";
                        }
                        ?>
                    </div>
                </div>
                <div class="option">
                    <button onclick="Iframe('form/deposit.php')">ฝากเงิน</button>
                    <button onclick="Iframe('form/withdraw.php')">ถอน</button>

                </div>
            </div>
            <div class="right">
                <div class="transaction">
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
    </div>
</body>

</html>