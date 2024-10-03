<?php

namespace common;

use Config;

require_once "Config.php";

class Point {
    private $conn;
    private int $userId;
    private int $expireDays = 3;
    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function addPoints($amount, $transactionId) {
        $expirationDate = date('Y-m-d', strtotime('+'.$this->expireDays.' days'));
        $insertPoints = "INSERT INTO tb_point (user_id, points, expiration_date) 
                     VALUES ($this->userId, $amount, '$expirationDate')";
        $this->executeQuery($insertPoints);

        $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id, transaction_id) 
                       VALUES ($this->userId, $amount, 5, $transactionId)";
        $this->executeQuery($logTransaction);
    }

    public function usePoints($amount, $transactionId) {
        $currentPoints = $this->getPoints();
        if ($currentPoints >= $amount) {
            $updatePoints = "UPDATE tb_point SET points = points - $amount WHERE user_id = $this->userId";
            $this->executeQuery($updatePoints);

            $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id, transaction_id) 
                               VALUES ($this->userId, $amount, 6, $transactionId)";
            $this->executeQuery($logTransaction);
        }
    }

    public function getPoints() {
        $query = "SELECT SUM(points) AS total_points FROM tb_point WHERE user_id = $this->userId";
        $result = $this->executeQuery($query);
        if ($row = mysqli_fetch_assoc($result))
            return $row['total_points'] ?? 0;
        return 0;
    }

    public function getTransactionHistory(): ?array {
        $query = "SELECT * FROM tb_point_transaction WHERE user_id = $this->userId ORDER BY created_at DESC";
        return $this->fetchQuery($query);
    }
    public function getTransactionHistories(): ?array {
        $query = "SELECT tb.*, ty.transaction_type_name AS transfer_type_name
              FROM tb_point_transaction tb 
              JOIN tb_transaction_type ty ON tb.transaction_type_id = ty.transaction_type_id 
              WHERE tb.user_id = $this->userId ORDER BY created_at DESC";
        return $this->fetchQuery($query);
    }

    public function getPointHistory(): ?array {
        $query = "SELECT * FROM tb_point WHERE user_id = $this->userId";
        return $this->fetchQuery($query);
    }
    public function handleSendPoint($amount, $transaction_id) { // โอน
        if ($amount >= Config::$reachGainPoint) {
            $this->addPoints(self::promotionPointGain($amount), $transaction_id); // 1200 * 0.01 =  12
        }
    }

    public static function promotionPointGain($amount) {
        return floor($amount * self::eventX2());
    }

    private static function eventX2(): float {
        $currentDay = date('N'); // มี ตัว D 'wed'
        $currentHour = date('H');

        $pointMultiplier = Config::$pointGain;

        if ($currentDay == 3 || ($currentHour >= 19 && $currentHour < 21)) {
            $pointMultiplier = Config::$extraPointGain;
        }
        return $pointMultiplier;
    }

    public function deleteExpiredPoints() { //ลบแต้ม บูด
        $query = "DELETE FROM tb_point WHERE expiration_date <= CURDATE()";
        return $this->executeQuery($query);
    }
    public function getPointsExpiringInOneDay(): int {
        $expire_date = date('Y-m-d', strtotime('+1 day'));
        $query = "SELECT SUM(points) AS expiring_points 
              FROM tb_point 
              WHERE user_id = $this->userId 
              AND expiration_date = '$expire_date'";

        $result = $this->executeQuery($query);

        if ($row = mysqli_fetch_assoc($result))
            return (int)($row['expiring_points'] ?? 0);

        return 0;
    }

    private function executeQuery($query) {
        return mysqli_query($this->conn, $query);
    }
    private function fetchQuery($query) {
        $result = $this->executeQuery($query);
        if (!$result) die("Query failed: " . mysqli_error($this->conn));
        $data = [];
        while ($row = mysqli_fetch_assoc($result))
            $data[] = $row;
        return $data;
    }
}