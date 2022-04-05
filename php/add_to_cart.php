<?php
    require_once "connect.php";

    session_start();
    
    if((!isset($_SESSION['logged'])) || ($_SESSION['logged'] == false))
	{
		header('Location: ../login.php');
		exit();
	}
    if(!isset($_POST['size'])) {
		header('Location: ../store.php');
		exit();
	}

    $user_id = $_SESSION['user_id'];
    $unique_id = $_SESSION['unique_id'];
    $prod_id = $_POST['prod_id'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $datetime = new DateTime(null, new DateTimeZone('Europe/Warsaw'));
    $time = $datetime->format("Y-m-d H:i:s");

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0)
    {
        $result->close();
        $_SESSION['error'] = "Error $connection->connect_errno<br> Spróbuj ponownie za jakiś czas";
        $connection->close();
        header('Location: ../cart.php');
        exit();
    }
    else
    {
        $is_in_cart = sprintf("SELECT quantity FROM `orders` WHERE user_id = '$user_id' AND unique_id = '$unique_id' AND prod_id = '%s' AND size = '%s'",
        mysqli_real_escape_string($connection, $prod_id),
        mysqli_real_escape_string($connection, $size));
        $resultp = @$connection->query($is_in_cart);

        $i = mysqli_num_rows($resultp);
        if($i == 1)
        {
            $quantity += mysqli_fetch_assoc($resultp)['quantity'];

            $update = sprintf("UPDATE `orders` SET `quantity` = '$quantity' WHERE user_id = '$user_id' AND unique_id = '$unique_id' AND prod_id = '%s' AND size = '%s'",
            mysqli_real_escape_string($connection, $prod_id),
            mysqli_real_escape_string($connection, $size));
            @$connection->query($update);

            header('Location: ../cart.php');
            exit();
        }
        else
        {
            $add = sprintf("INSERT INTO `orders` (`order_id`, `unique_id`, `order_status`, `user_id`, `prod_id`, `quantity`, `size`, `order_date`, `discount`) VALUES (NULL, '$unique_id', '0', '$user_id', '%s', '%s', '%s', '$time', '0')",
            mysqli_real_escape_string($connection, $prod_id),
            mysqli_real_escape_string($connection, $quantity),
            mysqli_real_escape_string($connection, $size));
            @$connection->query($add);

            header('Location: ../cart.php');
            exit();
        }
    }
?>