<?php
    require_once "connect.php";
    session_start();
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if(!isset($_GET['email'])) 
    {
		header('Location: ../home.php');
		exit();
    }
    
    $email = $_GET['email'];
    $v_token = md5(time());
    
    $change_v_token = sprintf("UPDATE `users` SET `verification_token` = '$v_token' WHERE `email`='%s'",
    mysqli_real_escape_string($connection, $email));
    @$connection->query($change_v_token);

    $get_name = sprintf("SELECT fname, lname FROM users WHERE `email`='%s' ORDER BY `user_id` DESC",
    mysqli_real_escape_string($connection, $email));
    $result = @$connection->query($get_name);

    $row = mysqli_fetch_assoc($result);
    $fname = $row['fname'];
    $lname = $row['lname'];

    $to      = $email; // Send email to our user
    $subject = 'Semeno - email verification'; // Give the email a subject 
    $message = '
        Dziękujemy za zarejestrowanie się '.$fname.' '.$lname.'! <br>
        Twoje konto zostało stworzone, możesz zalogować się używając emaila oraz swojego hasła po aktywacji konta.<br>
        Jeśli nie rejestrowałeś się w naszym sklepie pomiń tego maila.<br><br>
        Aby aktywować konto naciśnij poniższy link.:<br>';

    $message .= '<a href="localhost/semeno/php/verify.php?email='.$email.'&token='.$v_token.'">Confirm</a>';
    
    $headers = "From: noreply@semeno.eu \r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to, $subject, $message, $headers); // Send our email

    
    $connection->close();
    header('Location: ../article.php?content=waiting_for_verification&email='.$email);
    exit();
?>