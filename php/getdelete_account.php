<?php
    require_once "connect.php";

    session_start();

    if(!isset($_POST['password'])) {
		header('Location: ../profile.php');
		exit();
    }
    
    $user_id = $_SESSION["user_id"];
    $password = $_POST['password'];
    $_SESSION['password'] = $password;
    
    if($password == ""){
        $_SESSION['error'] = 'Podaj hasło';
        header('Location: ../delete_account.php');
        exit();
    }

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0)
    {
        $result->close();
        $_SESSION['error'] = "Error $connection->connect_errno<br> Spróbuj ponownie za jakiś czas";
        $connection->close();
        header('Location: ../delete_account.php');
        exit();
    }
    else
    {
        $goodpass = "SELECT password FROM `users` WHERE user_id = '$user_id'";
        $resultp = @$connection->query($goodpass);

        $row = mysqli_fetch_assoc($resultp);
        if(password_verify($password, $row['password']))
        {
            //USUWANIE KONTA
            $delu = sprintf("DELETE FROM `users` WHERE `users`.`user_id` = '$user_id'");
            @$connection->query($delu);
            $delo = sprintf("DELETE FROM `orders` WHERE `orders`.`user_id` = '$user_id'");
            @$connection->query($delo);
            $resultp->close();
            $connection->close();
            
            unset($_SESSION['password']);
            $_SESSION['logged'] = false;
            
            $_SESSION['success'] = 'Konto zostało pomyślnie usunięte';
            header('Location: ../login.php');
            exit();
        }
        else
        {
            $resultp->close();
            $connection->close();
            $_SESSION['error'] = 'Podałeś złe hasło';
            header('Location: ../delete_account.php');
            exit();
        }
    }
?>