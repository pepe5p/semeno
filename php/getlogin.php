<?php
    require_once "connect.php";
    session_start();

    if((!isset($_POST['email'])) || (!isset($_POST['password']))) {
		header('Location: ../login.php');
		exit();
	}

    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $email = htmlentities($email);
    
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0)
    {
        $_SESSION['error'] = "Error $connection->connect_errno<br> Spróbuj ponownie za jakiś czas";
        $connection->close();
        header('Location: ../login.php');
        exit();
    }
    else
    {
        $loginsql = sprintf("SELECT user_id, verification, cart_unique_id, password FROM users WHERE `email`='%s'",
        mysqli_real_escape_string($connection, $email));
        $result = @$connection->query($loginsql);
        $row = mysqli_fetch_assoc($result);

        if((mysqli_num_rows($result)) == 1)
        {
            if($row['verification'] == 1)
            {
                if(password_verify($password, $row['password']))
                {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['unique_id'] = $row['cart_unique_id'];
                    $_SESSION['logged'] = true;
                    unset($_SESSION['email']);
                    unset($_SESSION['password']);
    
                    header('Location: ../profile.php');
                    exit();
                }
                else
                {
                    $_SESSION['error'] = 'Nieprawidłowe email lub hasło!';
                    header('Location: ../login.php');
                    exit();
                }
            }
            else
            {
                header('Location: ../article.php?content=waiting_for_verification&email='.$email);
                exit();
            }
        } 
        else 
        {
            $_SESSION['error'] = 'Nieprawidłowy email lub hasło!';
            header('Location: ../login.php');
            exit();
        }
        $connection->close();
    }
?>