<?php

namespace common;

class Fee {
    private $conn;
    private string $table_name = "tb_fee"; // ชื่อของตาราง

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getFeeRate($amount) {
        $query = "SELECT fee_percentage FROM {$this->table_name} 
                  WHERE amount_min <= $amount AND amount_max >= $amount AND fee_id != 5";

        $result = mysqli_query($this->conn, $query);
        if (!$result) die("Query failed: " . mysqli_error($this->conn));
        if ($row = mysqli_fetch_assoc($result)) return $row['fee_percentage'];
        return null;
    }
    public function getFeeAmount($amount) {
        $feeRate = $this->getFeeRate($amount);
        return $amount < 100 ? $feeRate : ($feeRate * 0.01) * $amount;
    }
    public function getSenderFee() {
        $query = "SELECT fee_percentage FROM {$this->table_name}
        WHERE fee_id = 5";

        $result = mysqli_query($this->conn, $query);
        if (!$result) die("Query failed: " . mysqli_error($this->conn));
        if ($row = mysqli_fetch_assoc($result)) return $row['fee_percentage'];

        return null;
    }
}

