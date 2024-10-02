<?php
use common\Point;

include "../connect.inc.php";
require_once "../common/Point.php";
$userPoint = new Point($conn,$_SESSION['user_id']);
$pointTransaction = $userPoint->getTransactionHistories();
$pointHistory = $userPoint->getPointHistory();

$transactionDataMap = [];

foreach ($pointTransaction as $transaction) {
    $transactionDataMap[$transaction['point_transaction_id']] = [
        'point_amount' => $transaction['point_amount'],
        'transfer_type_name' => $transaction['transfer_type_name'],
        'created_at' => $transaction['created_at']
    ];
}

foreach ($pointHistory as $history) {
    $pointHistoryDataMap[$history['created_at']] = [
        'point_amount' => $history['points'],
        'expiration_date' => $history['expiration_date']
    ];
}

?>
<div class="recent-activity">
<h2>ประวัติ แต้ม</h2>
<table class="activity-table">
    <thead>
    <tr>
        <th>Points</th>
        <th>Transaction Type</th>
        <th>Date</th>
        <th>Expire Date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($transactionDataMap as $transactionId => $transaction) {
        $createdAt = $transaction['created_at'];
        $expirationDate = $pointHistoryDataMap[$createdAt]['expiration_date'] ?? 'N/A';

        $prefix = '';
        if (strpos($transaction['transfer_type_name'], 'earn') !== false)
            $prefix = '+';
         elseif (strpos($transaction['transfer_type_name'], 'use') !== false)
            $prefix = '-';


        echo "<tr>
            <td>{$prefix}{$transaction['point_amount']}</td>
            <td>{$transaction['transfer_type_name']}</td>
            <td>{$createdAt}</td>
            <td>{$expirationDate}</td>
        </tr>";
    }
    ?>
    </tbody>
</table>
</div>

