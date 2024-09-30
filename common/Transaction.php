<?php

namespace common;

require_once "Fee.php";
require_once "Point.php";

class Transaction {
    private $conn;
    private string $table_name = "tb_transaction"; // ชื่อของตาราง
    private Fee $fee;
    private User $user;
    private Point $point;

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->fee = new Fee($this->conn);
        $this->user = new User($this->conn, $userID);
        $this->point = new Point($this->conn, $userID);
    }

    public function save($user_id, $transaction_type_id, $amount, $fee,$fee_amount, $recipient_user_id = null) {
        if ($recipient_user_id === null)
            $query = "INSERT INTO $this->table_name (user_id, transaction_type_id, amount, fee,fee_amount, recipient_user_id, created_at)
                  VALUES ($user_id, $transaction_type_id, $amount, $fee,$fee_amount, NULL, NOW())";
         else
             $query = "INSERT INTO $this->table_name (user_id, transaction_type_id, amount, fee,fee_amount, recipient_user_id, created_at)
                  VALUES ($user_id, $transaction_type_id, $amount, $fee,$fee_amount, $recipient_user_id, NOW())";
        return $this->executeQuery($query);
    }

    public function deposit($amount): string {
        if ($amount < 1)
            return "จำนวนไม่ถูกต้อง";
        if ($amount > 5000)
            return "การฝากเงินไม่เกิน 5000 ต่อครั้ง ลองอีกครั้ง";
        $fee = $this->fee->getFeeByAmount($amount);
        $fee_amount = $amount < 100 ? $fee : ($fee * 0.01) * $amount;
        if ($this->save($this->user->getId(),3, $amount, $fee, $fee_amount)) {
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
    public function withdraw($amount) : string {
        if ($amount > $this->user->getWalletBalance())
            return "เงินในบัญชีไม่เพียงพอ";
        if ($this->save($this->user->getId(), 4, $amount,0,0)) {//ไม่เสียค่าทำเนียม 4 ถอนเงิน
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
    public function send($amount, $receiver_user_id) : string {  // send = 2
        if ($amount < 1) return "จำนวนไม่ถูกต้อง";
        if ($amount > $this->user->getWalletBalance()) return "ยอดเงินไม่เพียงพอ";
        $feePercentage = $this->fee->getSenderFee();
        $fee_amount = $amount * $feePercentage; // 20 + (20 * 0.01) = 20.2
        if ($this->save($this->user->getId(), 2, $amount, $feePercentage, $fee_amount, $receiver_user_id)) {
            //ระบบ point
            $this->point->handleSendPoint($amount);
            //ระบบ หลัก
            $newBalance = $this->withdrawToUserWallet($this->user->getId(), $amount);
            $this->receive($this->user->getId(),$amount, 2);
            return "โอนเงินสำเร็จ ยอดคงเหลือ: " . $newBalance;
        }
        return "โอนเงินล้มเหลว";
    }
    public function receive($sender_id, $amount, $receiver_user_id) : string { // receive = 1
        if ($amount < 1) {
            return "จำนวนไม่ถูกต้อง";
        }
        if ($this->save($receiver_user_id, 1, $amount, 0,0, $sender_id)) {
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
