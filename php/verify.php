<?php
    require_once "connect.php";
    session_start();

    $email = $_GET['email'];
    $v_token = $_GET['token'];

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    $get_info = sprintf("SELECT `user_id`, `cart_unique_id` FROM `users` WHERE `email`='%s' AND `verification_token`='%s'",
    mysqli_real_escape_string($connection, $email),
    mysqli_real_escape_string($connection, $v_token));

    if($resultg = @$connection->query($get_info))
    {
        $row = mysqli_fetch_assoc($resultg);
        $verify = "UPDATE `users` SET `verification` = 1 WHERE `email` = '$email' AND `verification_token`='$v_token'";
        @$connection->query($verify);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['unique_id'] = $row['cart_unique_id'];
        $_SESSION['logged'] = true;
        
        $resultg->close();
        $connection->close();
        header('Location: ../profile.php');
        exit();
    }
    else
    {
        $connection->close();
        $_SESSION['error'] = "Próba weryfikacji nie powiodła się, spróbuj ponownie";
        header('Location: ../register.php');
        exit();
    }
?>