<?php

namespace common;

class User {
    private string $table_name = 'tb_user';
    private $conn;
    private int $userId;
    private float $walletBalance;

    public function __construct($conn,$userId) {
        $this->conn = $conn;
        $this->userId = $userId;
        $this->walletBalance = $this->initWalletBalance();
    }

    public function initWalletBalance() {
        $sql = "SELECT wallet_balance FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $this->walletBalance = $row['wallet_balance'];
        return null;
    }
    public function getWalletBalance() : float {
        return $this->walletBalance;
    }

    public function getId() : int {
        return $this->userId;
    }
    public function getProfile() : string {
        $sql = "SELECT profile FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row['profile'];
        return "img/default-profile.png";
    }
    public function getUsername() : string{
        $sql = "SELECT username FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row['username'];
        return "Can't get Username";
    }
    public function getUserInfo(): ?array {
        $sql = "SELECT * FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row;
        return null;
    }
}