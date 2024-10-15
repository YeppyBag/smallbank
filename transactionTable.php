<?php
function renderTransactionTable($islogin, $user_id, $transaction) {
    if (!$islogin) {
        echo '<br><h2>เข้าสู่ระบบ เพื่อดูข้อมูลธุรกรรม</h2><br>';
        return;
    }
    $transactionData = $transaction->getTransactionByUserIdJoinTable($user_id);
    $map = [
        "1" => "รับเงิน",
        "2" => "โอนเงิน",
        "3" => "ฝากเงิน",
        "4" => "ถอนเงิน"
    ];

    echo '<table class="activity-table">';
    echo '<thead>
            <tr>
                <th>ชื่อธุรกรรม</th>
                <th>ประเภทธุรกรรม</th>
                <th>วันที่ทำการ</th>
                <th>ค่าธรรมเนียม</th>
                <th>จำนวนเงิน</th>
                <th>ยอดสุทธิ</th>
            </tr>
          </thead>';

    echo '<tbody>';

    foreach ($transactionData as $transaction) {
        $senderUserId = $transaction['user_id'];
        $receiverUsername = $transaction['recipient_username'];
        $transactionDate = date('d/m/Y H:i:s', strtotime($transaction['created_at']));
        $prefix = 'SmallBank';

        if (!empty($senderUserId)) {
            $prefix = $transaction['transaction_type_id'] == 1 ? 'โอนจาก ' . $receiverUsername :
                ($transaction['transaction_type_id'] == 2 ? 'โอนเงินไปยัง ' . $receiverUsername : $prefix);
        }

        $transactionType = $map[$transaction['transaction_type_id']] ?? 'Unknown';

        echo '<tr>';
        echo '<th>' . ($prefix) . '</th>';
        echo '<th>' . ($transactionType) . '</th>';
        echo '<th>' . $transactionDate . '</th>';
        echo '<th>฿' . number_format($transaction['fee_amount'], 2) . '</th>';
        echo '<th class="amount">฿' . number_format($transaction['amount'], 2) . '</th>';
        echo '<th class="amount">฿' . number_format($transaction['amount'] + $transaction['fee_amount'], 2) . '</th>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}
function renderTransactionHistoryTable($result, $result2) {
    ob_start();
    ?>
    <table class="activity-table">
        <thead>
        <tr>
            <th>รหัสธุรกรรม</th>
            <th>ชื่อผู้ใช้</th>
            <th>ประเภทธุรกรรม</th>
            <th>วันที่ทำการ</th>
            <th>ค่าธรรมเนียม</th>
            <th>จำนวนเงิน</th>
            <th>ผู้รับ/ผู้ส่ง</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($arr = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $arr['transaction_id']; ?></td>
            <td><a href="user_detail.php?id=<?php echo $arr['user_id']; ?>"><?php echo $arr['username']; ?></a></td>
            <td><?php echo $arr['transaction_type_name']; ?></td>
            <td><?php echo $arr['created_at']; ?></td>
            <td><?php echo number_format($arr['fee_amount'], 2); ?></td>
            <td><?php echo number_format($arr['amount'], 2); ?></td>
            <?php
            $recipient = mysqli_fetch_array($result2);
            if (!empty($recipient['recipient_user_id'])) { ?>
                <td><a href="user_detail.php?id=<?php echo $recipient['recipient_user_id']; ?>"><?php echo $recipient['username']; ?></a></td>
            <?php } else { ?>
                <td>-</td>
            <?php } ?>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}

function renderTransactionTableDateQuery($result, $includeUserLink = false) {
    ?>
    <table class="activity-table">
        <thead>
        <tr>
            <th>รหัสธุรกรรม</th>
            <th>ชื่อผู้ใช้</th>
            <th>ประเภทธุรกรรม</th>
            <th>วันที่ทำการ</th>
            <th>ค่าธรรมเนียม</th>
            <th>จำนวนเงิน</th>
            <th>ผู้รับ/ผู้ส่ง</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($arr = mysqli_fetch_array($result)) {
            $transac_id = $arr['transaction_id'];
            $sql2 = "SELECT * FROM tb_transaction t 
                     INNER JOIN tb_user u ON u.user_id = t.recipient_user_id 
                     WHERE t.transaction_id = '$transac_id'";
            $result2 = mysqli_query($GLOBALS['conn'], $sql2);
            ?>
            <tr>
                <td>
                    <?php if ($includeUserLink): ?>
                        <a href="../transaction_detail.php?id=<?php echo $arr['transaction_id']; ?>"><?php echo $arr['transaction_id']; ?></a>
                    <?php else: ?>
                        <?php echo $arr['transaction_id']; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($includeUserLink): ?>
                        <a href="../user_detail.php?id=<?php echo $arr['user_id']; ?>"><?php echo $arr['username']; ?></a>
                    <?php else: ?>
                        <?php echo $arr['username']; ?>
                    <?php endif; ?>
                </td>
                <td><?php echo $arr['transaction_type_name']; ?></td>
                <td><?php echo $arr['created_at']; ?></td>
                <td><?php echo number_format($arr['fee_amount'], 2); ?></td>
                <td><?php echo number_format($arr['amount'], 2); ?></td>
                <td>
                    <?php
                    $recipient = mysqli_fetch_array($result2);
                    if (!empty($recipient['recipient_user_id'])) { ?>
                        <a href="../user_detail.php?id=<?php echo $recipient['recipient_user_id']; ?>"><?php echo $recipient['username']; ?></a>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
}
?>
