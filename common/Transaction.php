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
            $query = "INSERT INTO $this->table_name (user_id, transaction_type_id, amount, fee, recipient_user_id, created_at)
                  VALUES ($user_id, $transaction_type_id, $amount, $fee, NULL, NOW())";
         else
             $query = "INSERT INTO $this->table_name (user_id, transaction_type_id, amount, fee, recipient_user_id, created_at)
                  VALUES ($user_id, $transaction_type_id, $amount, $fee, $recipient_user_id, NOW())";
        return $this->executeQuery($query);
    }

    public function deposit($amount, $transaction_type_id): string {
        if ($amount < 1)
            return "จำนวนไม่ถูกต้อง";
        if ($amount > 5000)
            return "การฝากเงินไม่เกิน 5000 ต่อครั้ง ลองอีกครั้ง";
        $feePercentage = $this->fee->getFeeByAmount($amount);
        if ($this->save($this->user->getId(), $transaction_type_id, $amount, $feePercentage)) { // 3 คือประเภทของการฝากเงิน
            $newBalance = $this->depositToUserWallet($this->user->getId(), $amount);
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
    public function withdraw($amount, $transaction_type_id): string {
        if ($amount > $this->user->getWalletBalance())
            return "เงินในบัญชีไม่เพียงพอ";
        if ($this->save($this->user->getId(), $transaction_type_id, $amount,0)) {//ไม่เสียค่าทำเนียม 4 ถอนเงิน
            $newBalance = $this->withdrawToUserWallet($this->user->getId(), $amount);
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
    public function send($amount, $transaction_type_id, $receiver_user_id) : string {
        if ($amount < 1) {
            return "จำนวนไม่ถูกต้อง";
        }
        if ($amount > $this->user->getWalletBalance()) return "ยอดเงินไม่เพียงพอ";
        $feePercentage = $this->fee->getSenderFee();
        $amountfee = $amount + $amount * $feePercentage; // 20 + (20 * 0.01) = 20.2
        if ($this->save($this->user->getId(), $transaction_type_id, $amountfee, $feePercentage, $receiver_user_id)) {
            $newBalance = $this->withdrawToUserWallet($this->user->getId(), $amount);
            $this->receive($this->user->getId(),$amount,1 ,$receiver_user_id);
            return "โอนเงินสำเร็จ ยอดคงเหลือ: " . $newBalance;
        }
        return "โอนเงินล้มเหลว";
    }
    public function receive($sender_id, $amount, $transaction_type_id, $receiver_user_id) : string {
        if ($amount < 1) {
            return "จำนวนไม่ถูกต้อง";
        }
        if ($this->save($receiver_user_id, $transaction_type_id, $amount, 0, $sender_id)) {
            $this->depositToUserWallet($receiver_user_id, $amount);
            return "รับเงินจาก :" . $amount;
        }
        return "ล้มเหลว";
    }

    public function getTransactionById($id): ?array {
        $query = "SELECT * FROM {$this->table_name} WHERE transaction_id = $id";
        return $this->fetchQuery($query);
    }
    public function getTransactionByUserId($user_id): ?array {
        $query = "SELECT * FROM {$this->table_name} WHERE user_id = $user_id";
        return $this->fetchQuery($query);
    }
    public function getTransactionByUserIdOrderBy($user_id,$order): ?array {
        $query = "SELECT * FROM {$this->table_name} WHERE user_id = $user_id ORDER BY $order";
        return $this->fetchQuery($query);
    }
    public function getAllTransactions(): ?array {
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
