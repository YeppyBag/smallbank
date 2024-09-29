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
        $query = "UPDATE tb_user SET points = points + $amount WHERE user_id = $this->userId";
        if (mysqli_query($this->conn, $query)) {
            return true;
        }
        return false;
    }

    public function usePoints($amount) {
        $currentPoints = $this->getPoints();
        if ($currentPoints >= $amount) {
            $query = "UPDATE tb_user SET points = points - $amount WHERE user_id = $this->userId";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    public function getPoints() {
        $query = "SELECT points FROM tb_user WHERE user_id = $this->userId";
        $result = mysqli_query($this->conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['points'];
        }
        return 0;
    }
}