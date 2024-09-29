<?php

namespace common;

class Point {
    private $conn;
    private $userId;

    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function addPoints($amount) {
        $updatePoints = "UPDATE tb_point SET points = points + $amount WHERE user_id = $this->userId";
        $this->executeQuery($updatePoints);
        $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type) 
                           VALUES ($this->userId, $amount, 'earn')";
        $this->executeQuery($logTransaction);
    }

    public function usePoints($amount) {
        $currentPoints = $this->getPoints();
        if ($currentPoints >= $amount) {
            $updatePoints = "UPDATE tb_point SET points = points - $amount WHERE user_id = $this->userId";
            $this->executeQuery($updatePoints);

            $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type) 
                               VALUES ($this->userId, $amount, 'use')";
            $this->executeQuery($logTransaction);
            return true;
        } else {
            return false;
        }
    }

    public function getPoints() {
        $query = "SELECT points FROM tb_point WHERE user_id = $this->userId";
        $result = $this->executeQuery($query);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['points'];
        }
        return 0;
    }
    public function getTransactionHistory() {
        $query = "SELECT * FROM tb_point_transaction WHERE user_id = $this->userId ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $query);

        $transactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $transactions[] = $row;
        }
        return $transactions;
    }
    private function executeQuery($query) {
        return mysqli_query($this->conn, $query);
    }
}