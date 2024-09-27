<?php
    include ("../connect.inc.php");
    $receiver = $_POST['receiver'];
    $sender = $_POST['sender'];
    $balance = $_POST['balance'];

    $sql = "SELECT * FROM tb_user WHERE user_id = '$sender';";
    $result = mysqli_query($conn,$sql);
    $arr = mysqli_fetch_array($result);
    if($arr['wallet_balance'] < $balance){
        echo "ยอดเงินของคุณไม่พอ";
    }else{
        $sql2 = "SELECT * FROM tb_user WHERE username = '$receiver';";
        $result2 = mysqli_query($conn,$sql2);
        $row = mysqli_num_rows($result2);
        $arr2= mysqli_fetch_array($result2);
        if(empty($row)){
            echo "ไม่มีชื่อผู้รับในระบบ";
        }else{
            $id = $arr['user_id'];
            $add = $arr2['wallet_balance'] + $balance;
            $sql3 = "UPDATE tb_user SET wallet_balance = '$add' WHERE user_id = '$id';";
            $result3 = mysqli_query($conn,$sql3);

            $sub = $arr['wallet_balance'] - $balance;
            $sql4 = "UPDATE tb_user SET wallet_balance = '$sub' WHERE user_id = '$sender';";
            $result4 = mysqli_query($conn,$sql4);

            if($result4 && $result3){
                echo "โอนเงินสำเร็จ";
            }else echo "โอนไม่สำเร็จ";

        }
    }


?>