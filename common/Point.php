<?php

namespace common;

class Point {
    private $conn;
    private int $userId;
    private int $expireDays = 3;
    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function addPoints($amount) {
        $expirationDate = date('Y-m-d', strtotime('+'.$this->expireDays.' days'));
        $insertPoints = "INSERT INTO tb_point (user_id, points, expiration_date) 
                     VALUES ($this->userId, $amount, '$expirationDate')";
        $this->executeQuery($insertPoints);

        $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id) 
                       VALUES ($this->userId, $amount, 5)";
        $this->executeQuery($logTransaction);
    }


    public function usePoints($amount) {
        $currentPoints = $this->getPoints();
        if ($currentPoints >= $amount) {
            $updatePoints = "UPDATE tb_point SET points = points - $amount WHERE user_id = $this->userId";
            $this->executeQuery($updatePoints);

            $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id) 
                               VALUES ($this->userId, $amount, 6)";
            $this->executeQuery($logTransaction);
            return true;
        } else {
            return false;
        }
    }

    public function getPoints() {
        $query = "SELECT SUM(points) AS total_points FROM tb_point WHERE user_id = $this->userId";
        $result = $this->executeQuery($query);
        if ($row = mysqli_fetch_assoc($result))
            return $row['total_points'] ?? 0;
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
    public function handleSendPoint($amount) { // โอน
        if ($amount >= 1000) {
            $this->addPoints($amount * 0.01); // 1200 * 0.01 =  12
        }
    }

    public function deleteExpiredPoints() { //ลบแต้ม บูด
        $query = "DELETE FROM tb_point WHERE expiration_date <= CURDATE()";
        return $this->executeQuery($query);
    }

    private function executeQuery($query) {
        return mysqli_query($this->conn, $query);
    }
}