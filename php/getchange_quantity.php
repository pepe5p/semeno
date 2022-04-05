<?php
	require_once "connect.php";

    session_start();
    
    if(!isset($_GET['quantity'])) {
		header('Location: ../cart.php');
		exit();
    }
    
    $user_id = $_SESSION["user_id"];
    $order_id = $_GET['order_id'];
    $quantity = $_GET['quantity'];
    $sign = $_GET['sign'];
    $new_quantity = $quantity;

    if($sign == "minus") $new_quantity -= 1;
    else if($sign == "plus") $new_quantity += 1;
    else if($sign == "delete") $new_quantity = 0;

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno != 0)
    {
        $_SESSION['error'] = "Error $connection->connect_errno<br> Spróbuj ponownie za jakiś czas";
        $connection->close();
        header('Location: ../cart.php');
        exit();
    }
    else
    {
        if($new_quantity > 0){
            $chg = sprintf("UPDATE `orders` SET `quantity` = '%s' WHERE `order_id` = '%s' AND `user_id` ='$user_id'",
            mysqli_real_escape_string($connection, $new_quantity),
            mysqli_real_escape_string($connection, $order_id));
            @$connection->query($chg);
            $connection->close();
            header('Location: ../cart.php');
            exit();
        }
        else if($new_quantity == 0){
            $del = sprintf("DELETE FROM `orders` WHERE `order_id` = '%s' AND `user_id` ='$user_id'",
            mysqli_real_escape_string($connection, $order_id));
            @$connection->query($del);
            $connection->close();
            header('Location: ../cart.php');
            exit();
        }
    }
?>