<?php
    require_once "connect.php";

    session_start();

    if(!isset($_POST['fname'])) {
		header('Location: ../profile.php');
		exit();
	}

    $user_id = $_SESSION["user_id"];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone_nr = $_POST['phone_nr'];
    $postal_code = $_POST['postal_code'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $number = $_POST['number'];

    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['phone_nr'] = $phone_nr;
    $_SESSION['postal_code'] = $postal_code;
    $_SESSION['city'] = $city;
    $_SESSION['street'] = $street;
    $_SESSION['number'] = $number;

    if($fname == ""){
        $_SESSION['error'] = 'Podaj imię';
        header('Location: ../profile.php');
        exit();
    }
    if($lname == ""){
        $_SESSION['error'] = 'Podaj nazwisko';
        header('Location: ../profile.php');
        exit();
    }
    if($phone_nr == ""){
        $_SESSION['error'] = 'Podaj numer telefonu';
        header('Location: ../profile.php');
        exit();
    }
    if($postal_code == "" || $postal_code == "XX-XXX"){
        $_SESSION['postal_code'] = "XX-XXX";
        $_SESSION['error'] = 'Podaj kod pocztowy';
        header('Location: ../profile.php');
        exit();
    }
    if($city == ""){
        $_SESSION['error'] = 'Podaj miasto';
        header('Location: ../profile.php');
        exit();
    }
    if($street == ""){
        $_SESSION['error'] = 'Podaj ulicę';
        header('Location: ../profile.php');
        exit();
    }
    if($number == ""){
        $_SESSION['error'] = 'Podaj numer mieszkania';
        header('Location: ../profile.php');
        exit();
    }

    $fname = htmlentities($fname);
    $lname = htmlentities($lname);
    $phone_nr = htmlentities($phone_nr);
    $postal_code= htmlentities($postal_code);
    $city = htmlentities($city);
    $street = htmlentities($street);
    $number = htmlentities($number);
    
    //telephone number
    if((strlen($phone_nr) != 9) || (!is_numeric($phone_nr)))
    {
        $_SESSION['error'] = 'Błędny numer telefonu - powinien mieć 9 cyfr';
        header('Location: ../profile.php');
        exit();
    }

    //postal code
    if((strlen($postal_code) != 6) || (substr($postal_code, 2, 1) != "-") || (!is_numeric(substr($postal_code, 0, 2))) || (!is_numeric(substr($postal_code, 3, 3))))
    {
        $_SESSION['error'] = 'Błędny kod pocztowy';
        header('Location: ../profile.php');
        exit();
    }

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    //AKTUALIZACJA PROFILU
    $chg = sprintf("UPDATE `users` SET `fname` = '%s', `lname` = '%s', `phone_nr` = '%s', `postal_code` = '%s', `city` = '%s', `street` = '%s', `number` = '%s' WHERE `user_id` ='$user_id'",
    mysqli_real_escape_string($connection, $fname),
    mysqli_real_escape_string($connection, $lname),
    mysqli_real_escape_string($connection, $phone_nr),
    mysqli_real_escape_string($connection, $postal_code),
    mysqli_real_escape_string($connection, $city),
    mysqli_real_escape_string($connection, $street),
    mysqli_real_escape_string($connection, $number));
    @$connection->query($chg);
    
    unset($_SESSION['fname']);
    unset($_SESSION['lname']);
    unset($_SESSION['phone_nr']);
    unset($_SESSION['postal_code']);
    unset($_SESSION['city']);
    unset($_SESSION['street']);
    unset($_SESSION['number']);
    
    $connection->close();

    $_SESSION['success'] = 'Dane zostały pomyślnie zaktualizowane';
    header('Location: ../profile.php');
    exit();
?>