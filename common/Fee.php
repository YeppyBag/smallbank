<?php

namespace common;

class Fee {
    private $conn;
    private $table_name = "tb_fee"; // ชื่อของตาราง

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getFeeByAmount($amount) {
        $query = "SELECT fee_percentage FROM {$this->table_name} 
                  WHERE amount_min <= $amount AND amount_max >= $amount";

        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['fee_percentage'];
        }

        return null;
    }
}

