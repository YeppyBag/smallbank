<?php

namespace common;

require_once "Fee.php";

class Transaction {
    private $conn;
    private $table_name = "tb_transaction"; // ชื่อของตาราง
    private Fee $fee;
    private User $user;

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->fee = new Fee($this->conn);
        $this->user = new User($this->conn, $userID);
    }

    public function save($user_id, $transaction_type_id, $amount, $fee, $recipient_user_id = null) {
        if ($recipient_user_id === null)
            $query = "INSERT INTO {$this->table_name} (user_id, transaction_type_id, amount, fee, recipient_user_id, created_at)
                  VALUES ($user_id, $transaction_type_id, $amount, $fee, NULL, NOW())";
         else
            $query = "INSERT INTO {$this->table_name} (user_id, transaction_type_id, amount, fee, recipient_user_id, created_at)
                  VALUES ($user_id, $transaction_type_id, $amount, $fee, $recipient_user_id, NOW())";
        return $this->executeQuery($query);
    }

    public function deposit($amount) {
        if ($amount < 1)
            return "จำนวนไม่ถูกต้อง";
        if ($amount > 5000)
            return "การฝากเงินไม่เกิน 5000 ต่อครั้ง ลองอีกครั้ง";
        $feePercentage = $this->fee->getFeeByAmount($amount);
        if ($this->save($this->user->getUserId(), 3, $amount, $feePercentage)) { // 3 คือประเภทของการฝากเงิน
            $newBalance = $this->depositToUserWallet($this->user->getUserId(), $amount);
            return "ฝากเงินสำเร็จ ยอดเงินทั้งหมด: " . $newBalance;
        }
        return "การฝากล้มเหลว";
    }

    private function depositToUserWallet($user_id, $amount) {
        $query = "UPDATE tb_user SET wallet_balance = wallet_balance + $amount WHERE user_id = $user_id";
        if ($this->executeQuery($query)) {
            $result = mysqli_query($this->conn, "SELECT wallet_balance FROM tb_user WHERE user_id = $user_id");
            if ($row = mysqli_fetch_assoc($result))
                return $row['wallet_balance'];
        }
        return "ล้มเหลว";
    }
    public function withdraw($amount) {
        if ($amount > $this->user->getWalletBalance())
            return "เงินในบัญชีไม่เพียงพอ";
        if ($this->save($this->user->getUserId(), 4, $amount,0)) {//ไม่เสียค่าทำเนียม 4 ถอนเงิน
            $newBalance = $this->withdrawToUserWallet($this->user->getUserId(), $amount);
            return "ถอนเงินสำเร็จ ยอดเงินคงเหลือ: " . $newBalance;
        }
        return "หืม?";
    }
    private function withdrawToUserWallet($user_id, $amount) {
        $query = "UPDATE tb_user SET wallet_balance = wallet_balance - $amount WHERE user_id = $user_id";
        if ($this->executeQuery($query)) {
            $result = mysqli_query($this->conn, "SELECT wallet_balance FROM tb_user WHERE user_id = $user_id");
            if ($row = mysqli_fetch_assoc($result))
                return $row['wallet_balance'];
        }
        return "ล้มเหลว";
    }

    public function getTransactionById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE transaction_id = $id";
        return $this->fetchQuery($query);
    }

    public function getAllTransactions() {
        $query = "SELECT * FROM {$this->table_name}";
        return $this->fetchQuery($query);
    }

    private function executeQuery($query) {
        return mysqli_query($this->conn, $query);
    }

    private function fetchQuery($query) {
        $result = mysqli_query($this->conn, $query);
        if (!$result) die("Query failed: " . mysqli_error($this->conn));
        $data = [];
        while ($row = mysqli_fetch_assoc($result))
            $data[] = $row;
        return $data;
    }
}
