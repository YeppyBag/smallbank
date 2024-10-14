<?php
enum TransactionType: int {
    case Receive = 1;
    case Send = 2;
    case Deposit = 3;
    case Withdraw = 4;
    case Earn = 5;
    case Use = 6;
}

function getTransactionTypeValue(TransactionType $transaction): int {
    return $transaction->value;
}
