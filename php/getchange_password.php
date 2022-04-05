<?php
    require_once "connect.php";

    session_start();

    if(!isset($_POST['fpassword'])) {
		header('Location: ../profile.php');
		exit();
	}
    
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    $user_id = $_SESSION["user_id"];
    $fpassword = $_POST['fpassword'];
    $spassword = $_POST['spassword'];
    $opassword = $_POST['opassword'];

    $_SESSION['fpassword'] = $fpassword;
    $_SESSION['spassword'] = $spassword;
    $_SESSION['opassword'] = $opassword;

    if($fpassword == ""){
        $_SESSION['error'] = 'Podaj nowe hasło';
        header('Location: ../change_password.php');
        exit();
    }
    if($fpassword != $spassword)
    {
        $_SESSION['error'] = 'Podałeś różne hasła, spróbuj jeszcze raz';
        header('Location: ../change_password.php');
        exit();
    }
    if($fpassword == $opassword)
    {
        $_SESSION['error'] = 'Nowe i stare hasło są takie same';
        header('Location: ../change_password.php');
        exit();
    }

    $fpassword = htmlentities($fpassword);
    $spassword = htmlentities($spassword);
    $opassword = htmlentities($opassword);

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    $goodpass = "SELECT password FROM `users` WHERE user_id = '$user_id'";
    $result = @$connection->query($goodpass);

    $row = mysqli_fetch_assoc($result);
    if(password_verify($opassword, $row['password']))
    {
        //AKTUALIZACJA HASŁA
        $password = password_hash($fpassword, PASSWORD_BCRYPT);
        $chg = sprintf("UPDATE `users` SET `password` = '%s' WHERE `user_id` ='$user_id'",
        mysqli_real_escape_string($connection, $password));
        @$connection->query($chg);
        $result->close();
        $connection->close();
        
        unset($_SESSION['fpassword']);
        unset($_SESSION['spassword']);
        unset($_SESSION['opassword']);
        
    
        $_SESSION['success'] = 'Hasło zostało pomyślnie zaktualizowane';
        header('Location: ../profile.php');
        exit();
    }
    else
    {
        $result->close();
        $connection->close();
        $_SESSION['error'] = 'Stare hasło jest błędne';
        header('Location: ../change_password.php');
        exit();
    }

?>