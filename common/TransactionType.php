<?php

namespace common;

class TransactionType {
    private $conn;
    private string $table_name = "tb_transaction_type"; // Ensure your table name is correct
    private array $transaction_type = []; // Initialize to an empty array

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function queryTransactionType() {
        $sql = "SELECT * FROM {$this->table_name}";
        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $this->transaction_type[] = $row;
            }
        }

        return $this->transaction_type;
    }

    public function getTransactionTypeByIndex($index) {
        if (empty($this->transaction_type)) {
            $this->queryTransactionType();
        }

        if (isset($this->transaction_type[$index])) {
            return $this->transaction_type[$index]['transaction_type_name'];
        }

        return null;
    }
}
