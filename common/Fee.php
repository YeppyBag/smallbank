<?php

namespace common;

class Fee {
    private $conn;
    private $table_name = "tb_fee"; // ชื่อของตาราง

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ฟังก์ชันสำหรับการดึงค่าธรรมเนียมตามจำนวนเงิน
    public function getFeeByAmount($amount) {
        // สร้างคำสั่ง SQL สำหรับดึงค่าธรรมเนียม
        $query = "SELECT fee_percentage FROM {$this->table_name} 
                  WHERE amount_min <= $amount AND amount_max >= $amount";

        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }

        // ตรวจสอบว่ามีค่าธรรมเนียมที่ตรงตามจำนวนเงินหรือไม่
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['fee_percentage']; // คืนค่าธรรมเนียมเป็นเปอร์เซ็นต์
        }

        return null; // หากไม่มีค่าธรรมเนียมที่ตรงกัน
    }
}

