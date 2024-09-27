<?php
namespace common;

class Wallet {
    private $conn;
    private $userID;
    private $balance;
    private $fallback;
    private $flag;

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
        $this->balance = $this->getBalance();
    }

    public function getBalance() {
        $sql = "SELECT wallet_balance FROM tb_user WHERE user_id='$this->userID'";
        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['wallet_balance'];
        } else {
            return 0;
        }
    }

    public function deposit($amount) {
        if ($amount > 0) {
            $this->balance += $amount;
            $this->updateBalance();
            $this->flag = true;
            return $this->fallback = "Deposit successful! New balance: " . $this->balance;
        } else {
            $this->flag = false;
            return $this->fallback = "Invalid amount.";
        }
    }

    public function withdraw($amount) {
        if ($amount > 0 && $amount <= $this->balance) {
            $this->balance -= $amount;
            $this->updateBalance();
            $this->flag = true;
            return $this->fallback = "Withdrawal successful! Remaining balance: " . $this->balance;
        } else {
            $this->flag = false;
            return $this->fallback = "Invalid amount or insufficient balance.";
        }
    }

    private function updateBalance() {
        $sql = "UPDATE tb_user SET wallet_balance='$this->balance' WHERE user_id='$this->userID'";
        $this->conn->query($sql);
    }

    public function getFallback() {
        return $this->fallback;
    }
    public function getFlag() {
        return $this->flag;
    }
}