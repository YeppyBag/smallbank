<?php

namespace common;

use Config;
use TransactionType;

require_once "Config.php";

class Point {
    private $conn;
    private int $userId;
    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function addPoints($amount, $transactionId) {
        $expirationDate = date('Y-m-d', strtotime('+'.Config::$pointExpireDays.' days'));
        $insertPoints = "INSERT INTO tb_point (user_id, points, expiration_date) 
                     VALUES ($this->userId, $amount, '$expirationDate')";
        $this->executeQuery($insertPoints);

        $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id, transaction_id) 
                       VALUES ($this->userId, $amount,". getTransactionTypeValue(TransactionType::Earn) .", $transactionId)";
        $this->executeQuery($logTransaction);
    }

    public function usePoints($amount, $transactionId) {
        $query = "SELECT point_id, points FROM tb_point WHERE user_id = $this->userId ORDER BY point_id ASC";
        $result = $this->executeQuery($query);

        $pointsToUse = $amount;

        while ($row = mysqli_fetch_assoc($result)) {
            $pointId = $row['point_id'];
            $currentPoints = $row['points'];

            if ($pointsToUse <= $currentPoints) {
                $updatePoints = "UPDATE tb_point SET points = points - $pointsToUse WHERE point_id = $pointId";
                $this->executeQuery($updatePoints);

                $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id, transaction_id) 
                               VALUES ($this->userId, $pointsToUse,".getTransactionTypeValue(TransactionType::Use).", $transactionId)";
                $this->executeQuery($logTransaction);

                if ($currentPoints - $pointsToUse == 0) {
                    $deleteQuery = "DELETE FROM tb_point WHERE point_id = $pointId";
                    $this->executeQuery($deleteQuery);
                }
                break;
            } else {
                $pointsToUse -= $currentPoints;
                $deleteQuery = "DELETE FROM tb_point WHERE point_id = $pointId";
                $this->executeQuery($deleteQuery);
                $logTransaction = "INSERT INTO tb_point_transaction (user_id, point_amount, transaction_type_id, transaction_id) 
                               VALUES ($this->userId, $currentPoints,".getTransactionTypeValue(TransactionType::Use).", $transactionId)";
                $this->executeQuery($logTransaction);
            }
        }
        if ($pointsToUse > 0) {
            echo ("คะแนนไม่เพียงพอ");
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
    public function handleSendPoint($amount, $transaction_id): void { // โอน
        if ($amount >= Config::$reachGainPoint)
            $this->addPoints(self::promotionPointGain($amount), $transaction_id); // 1200 * 0.01 =  12
    }

    public static function promotionPointGain($amount) {
        return floor($amount * self::eventX2());
    }

    private static function eventX2(): float {
        $pointMultiplier = Config::$pointGain;
        if (self::isEventX2()) $pointMultiplier = Config::$extraPointGain;
        return $pointMultiplier;
    }
    public static function isEventX2(): bool {
        date_default_timezone_set('Asia/Bangkok');
        $currentDay = date('N'); // มี ตัว D 'wed'
        $currentHour = date('H');

        if ($currentDay == Config::$eventDay || ($currentHour >= Config::$eventTimeStart && $currentHour < Config::$eventTimeEnd))
            return true;
        return false;
    }

    public function deleteExpiredPoints() { //ลบแต้ม บูด
        $query = "DELETE FROM tb_point WHERE expiration_date <= CURDATE()";
        return $this->executeQuery($query);
    }
    public function getPointsExpiringInOneDay(): int {
        $expire_date = date('Y-m-d', strtotime('+' .Config::$pointExpireInOneDay . ' day'));
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