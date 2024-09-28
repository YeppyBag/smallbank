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
        $sql = "SELECT wallet_balance FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $this->walletBalance = $row['wallet_balance'];
        return null;
    }

    public function getWalletBalance() {
        return $this->walletBalance;
    }

    public function getId() {
        return $this->userId;
    }
    public function getProfile() {
        $sql = "SELECT profile FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row['profile'];
        return null;
    }
    public function getEmail() {
        $sql = "SELECT email FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row['email'];
        return null;
    }
    public function getUsername() {
        $sql = "SELECT username FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row['username'];
        return null;
    }
    public function getUserInfo() {
        $sql = "SELECT * FROM $this->table_name WHERE user_id = $this->userId";
        $result = $this->conn->query($sql);
        if ($row = mysqli_fetch_assoc($result)) return $row;
        return null;
    }
}