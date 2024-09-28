<?php

namespace common;

class User {
    private string $table_name = 'tb_user';
    private $conn;
    private $userId;
    private $walletBalance;

    public function __construct($conn,$userId) {
        $this->conn = $conn;
        $this->userId = $userId;
        $this->walletBalance = $this->initWalletBalance();
    }

    public function initWalletBalance() {
        $sql = "SELECT wallet_balance FROM {$this->table_name} WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $this->walletBalance = $row['wallet_balance'];
            return $this->walletBalance;
        }
        return null;
    }

    public function getWalletBalance() {
        return $this->walletBalance;
    }

    public function getUserId() {
        return $this->userId;
    }
}