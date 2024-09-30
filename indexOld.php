<?php

use common\Point;
use common\Transaction;
use common\TransactionType;
use common\User;

include("connect.inc.php");
require_once "common/Transaction.php";
require_once "common/TransactionType.php";
require_once "common/User.php";

if (isset($_SESSION['user_id'])) {
    $user = new User($conn, $_SESSION['user_id']);
    $userPoint = new Point($conn, $_SESSION['user_id']);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/transaction_card.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <title>Small Bank</title>
</head>

<body>
<div class="container">
    <div class="nav">
        <?php
        if (!isset($_SESSION['user_id'])) {
            echo "<a href='form/login.php'>SIGN UP / LOG IN</a>";
        } else {
            echo "<div class='dropdown'>";
            echo "<a href='#' class='dropbtn'>Profile</a>";
            echo "<div class='dropdown-content'>";
            echo "<a href='form/setting.php'>Setting</a>";
            echo "<a href='action/logout.php'>Logout</a>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
    <div class="frame"></div>
    <div class="containerLR">
        <div class="left">
            <div class="profile">
                <div class="profile-image" style="background-image: url('<?php echo $user->getProfile(); ?>');"></div>
            </div>
            <div class="balance">
                <div class="name">indexOld.php
                    <div class="current">
                        <h3>Balance</h3>
                        <p>Avalible</p>
                    </div>
                    <div class="username">
                        <?php
                        if (!empty($_SESSION['user_id'])) {
                            echo "<h3>" . $_SESSION['username'] . "</h3>";
                        } else
                            echo "";
                        ?>
                    </div>
                </div>
                <span class="line"></span>
                <div class="wallet">
                    <?php
                    if (!empty($_SESSION['user_id'])) {
                        echo "<h1>฿ " .number_format( $user->getWalletBalance() , 2) . "</h1>";
                    }
                    ?>
                    <span class="line"></span><br>
                    <h2>P: <?php echo $userPoint->getPoints(); ?> </h2>
                </div>
            </div>
            <div class="option">
                <button onclick="window.location.href='form/deposit.php';">ฝากเงิน</button>
                <button onclick="window.location.href='form/withdraw.php';">ถอน</button>
                <button onclick="window.location.href='form/transfer.php';">โอนเงิน</button>
            </div>
        </div>
        <div class="right">
            <div class="transaction">
                <?php
                if (!empty($_SESSION['user_id'])) {
                    $transaction = new Transaction($conn, $_SESSION['user_id']);
                    $transactionType = new TransactionType($conn);
                    $transactionData = $transaction->getTransactionByUserIdOrderBy($_SESSION['user_id'], "created_at DESC");

                    if (!empty($transactionData)) {
                        foreach ($transactionData as $transaction) {
                            $transactionTime = date('H:i', strtotime($transaction['created_at']));
                            $transactionDate = date('d/m/Y', strtotime($transaction['created_at']));

                            $tranType = $transactionType->getTransactionTypeByIndex($transaction['transaction_type_id'] - 1);

                            $amountClass = ($transaction['transaction_type_id'] == 1 || $transaction['transaction_type_id'] == 3) ? 'amount-deposit' : 'amount-withdrawal';
                            $amountPrefix = ($amountClass === 'amount-deposit') ? '+' : '-';

                            $transactionMessage = '';
                            $username = '';

                            switch ($transaction['transaction_type_id']) {
                                case 1:
                                case 3:
                                    $transactionMessage = 'Received money from ';
                                    $senderUserId = $transaction['recipient_user_id'];
                                    break;
                                case 2:
                                    $transactionMessage = 'Sent money to ';
                                    $senderUserId = $transaction['recipient_user_id'];
                                    break;
                            }

                            if (!empty($senderUserId)) {
                                $senderUser = new User($conn, $senderUserId);
                                $username = $senderUser->getUsername();
                            }

                            echo "<div class='card'>
                                    <p class='transaction_amount $amountClass'><span>{$amountPrefix}{$transaction['amount']} Baht</span></p>
                                    <p class='transaction_type'><span>{$tranType}</span><span class='sub_time'>{$transactionTime}</span></p>
                                    <p class='transaction_user'><span>User: {$user->getUsername()}</span><span class='sender'>{$transactionMessage}{$username}</span><span class='sub_date'>{$transactionDate}</span></p>
                                 <svg fill='#ffffff' height='40px' width='40px' version='1.1' id='Layer_1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' xml:space='preserve' stroke='#ffffff' class='icon'><g id='SVGRepo_bgCarrier' stroke-width='0'></g><g id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round'></g><g id='SVGRepo_iconCarrier'><g><g><path d='M335.367,257.951c-7.187,0-13.033,5.847-13.033,13.034v43.562c0,7.187,5.847,13.033,13.033,13.033H512v-69.628H335.367z M357.347,312.858c-11.097,0-20.094-8.996-20.094-20.094c0-11.097,8.996-20.094,20.094-20.094s20.094,8.996,20.094,20.094 C377.44,303.862,368.444,312.858,357.347,312.858z'/></g></g><g><g><path d='M335.367,227.534H512V152.95c0-23.959-19.492-43.45-43.45-43.45h-6.084H133.703H43.45C19.492,109.5,0,128.991,0,152.95v310.047c0,23.959,19.492,43.451,43.45,43.451h425.099c23.959,0,43.45-19.492,43.45-43.451v-105H335.367c-23.959,0-43.45-19.491-43.45-43.45v-43.562C291.917,247.026,311.41,227.534,335.367,227.534z'/></g></g><g><g><polygon points='425.425,5.552 219.067,79.082 451.626,79.082 '/></g></g></g></svg>
                            </div>";
                        }
                    } else {
                        echo "ไม่มีข้อมูลธุรกรรม";
                    }
                }
//                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>