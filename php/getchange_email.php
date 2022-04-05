<?php
    require_once "connect.php";
    session_start();

    if(!isset($_POST['email'])) {
		header('Location: ../profile.php');
		exit();
	}
    
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    $user_id = $_SESSION["user_id"];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;

    $email = htmlentities($email);
    $password = htmlentities($password);

    //email
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email))
    {
        $_SESSION['error'] = 'Błędny email';
        header('Location: ../change_email.php');
        exit();
    }

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    $goodemail = sprintf("SELECT user_id FROM `users` WHERE `email` = '%s' AND `verification` = 1",
    mysqli_real_escape_string($connection, $email));
    $result = @$connection->query($goodemail);
    if((mysqli_num_rows($result))==1)
    {
        $_SESSION['error'] = 'Email jest zajęty';
        header('Location: ../change_email.php');
        exit();
    }
    $result->close();
    
    $goodpass = "SELECT password FROM `users` WHERE user_id = '$user_id'";
    $resultp = @$connection->query($goodpass);

    $row = mysqli_fetch_assoc($resultp);
    if(password_verify($password, $row['password']))
    {
        //AKTUALIZACJA EMAILA
        $chg = sprintf("UPDATE `users` SET `email` = '%s', `verification` = 0 WHERE `user_id` = '$user_id'",
        mysqli_real_escape_string($connection, $email));
        @$connection->query($chg);
        $resultp->close();
        $connection->close();
        
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        $_SESSION['logged'] = false;
        
        $_SESSION['success'] = 'Email został pomyślnie zaktualizowany';
        header('Location: send_mail.php?email='.$email);
        exit();
    }
    else
    {
        $resultp->close();
        $connection->close();
        $_SESSION['error'] = 'Podałeś złe hasło';
        header('Location: ../change_email.php');
        exit();
    }

?>