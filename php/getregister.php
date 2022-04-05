<?php
    require_once "connect.php";

    session_start();

    if(!isset($_POST['fname'])) {
		header('Location: ../login.php');
		exit();
	}

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone_nr = $_POST['phone_nr'];
    $postal_code = $_POST['postal_code'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $fpassword = $_POST['fpassword'];
    $spassword = $_POST['spassword'];
    
    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['phone_nr'] = $phone_nr;
    $_SESSION['postal_code'] = $postal_code;
    $_SESSION['city'] = $city;
    $_SESSION['street'] = $street;
    $_SESSION['number'] = $number;
    $_SESSION['email'] = $email;
    $_SESSION['fpassword'] = $fpassword;
    $_SESSION['spassword'] = $spassword;
    
    //RECAPTCHA
    $secret_key = "6LfgK8MZAAAAAK7W8hm3TxEqQZYu9aRqSzTptbwX";
    $check = file_get_contents('https://google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
    $response = json_decode($check);
    if($response->success == false){
        $_SESSION['error'] = "Wypełnij ReCaptcha'e";
        header('Location: ../register.php');
        exit();
    }

    if($fname == ""){
        $_SESSION['error'] = 'Podaj imię';
        header('Location: ../register.php');
        exit();
    }
    if($lname == ""){
        $_SESSION['error'] = 'Podaj nazwisko';
        header('Location: ../register.php');
        exit();
    }
    if($phone_nr == ""){
        $_SESSION['error'] = 'Podaj numer telefonu';
        header('Location: ../register.php');
        exit();
    }
    if($postal_code == "" || $postal_code == "XX-XXX"){
        $_SESSION['postal_code'] = "XX-XXX";
        $_SESSION['error'] = 'Podaj kod pocztowy';
        header('Location: ../register.php');
        exit();
    }
    if($city == ""){
        $_SESSION['error'] = 'Podaj miasto';
        header('Location: ../register.php');
        exit();
    }
    if($street == ""){
        $_SESSION['error'] = 'Podaj ulicę';
        header('Location: ../register.php');
        exit();
    }
    if($number == ""){
        $_SESSION['error'] = 'Podaj numer mieszkania';
        header('Location: ../register.php');
        exit();
    }
    if($fpassword == ""){
        $_SESSION['error'] = 'Podaj hasło';
        header('Location: ../register.php');
        exit();
    }

    $fname = htmlentities($fname);
    $lname = htmlentities($lname);
    $city = htmlentities($city);
    $street = htmlentities($street);
    $number = htmlentities($number);

    //telephone number
    if((strlen($phone_nr) != 9) || (!is_numeric($phone_nr)))
    {
        $_SESSION['error'] = 'Błędny numer telefonu - powinien mieć 9 cyfr';
        header('Location: ../register.php');
        exit();
    }

    //postal code
    if((strlen($postal_code) != 6) || (substr($postal_code, 2, 1) != "-") || (!is_numeric(substr($postal_code, 0, 2))) || (!is_numeric(substr($postal_code, 3, 3))))
    {
        $_SESSION['error'] = 'Błędny kod pocztowy';
        header('Location: ../register.php');
        exit();
    }

    //email
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email))
    {
        $_SESSION['error'] = 'Błędny email';
        header('Location: ../register.php');
        exit();
    }

    //password
    if($fpassword != $spassword)
    {
        $_SESSION['error'] = 'Podałeś różne hasła, spróbuj jeszcze raz';
        header('Location: ../register.php');
        exit();
    }

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0)
    {
        $result->close();
        $_SESSION['error'] = "Error $connection->connect_errno<br> Spróbuj ponownie za jakiś czas";
        $connection->close();
        header('Location: ../register.php');
        exit();
    }
    else
    {
        //EMAIL
        $goodemail = sprintf("SELECT user_id FROM `users` WHERE `email`='%s' AND `verification` = 1",
        mysqli_real_escape_string($connection, $email));
        $result = @$connection->query($goodemail);
        if((mysqli_num_rows($result))==1)
        {
            $result->close();
            $connection->close();
            $_SESSION['error'] = 'Email jest zajęty';
            header('Location: ../register.php');
            exit();
        }
        $result->close();

        $password = password_hash($fpassword, PASSWORD_BCRYPT);
        
        $unique_id = strtoupper(uniqid());
        $reg = sprintf("INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `phone_nr`, `postal_code`, `city`, `street`, `number`, `verification`, `verification_token`, `cart_unique_id`, `password`) VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', 0, '', '$unique_id', '%s')",
        mysqli_real_escape_string($connection, $fname),
        mysqli_real_escape_string($connection, $lname),
        mysqli_real_escape_string($connection, $email),
        mysqli_real_escape_string($connection, $phone_nr),
        mysqli_real_escape_string($connection, $postal_code),
        mysqli_real_escape_string($connection, $city),
        mysqli_real_escape_string($connection, $street),
        mysqli_real_escape_string($connection, $number),
        mysqli_real_escape_string($connection, $password));
        @$connection->query($reg);

        unset($_SESSION['fname']);
        unset($_SESSION['lname']);
        unset($_SESSION['phone_nr']);
        unset($_SESSION['postal_code']);
        unset($_SESSION['city']);
        unset($_SESSION['street']);
        unset($_SESSION['number']);
        unset($_SESSION['email']);
        unset($_SESSION['fpassword']);
        unset($_SESSION['spassword']);

        $connection->close();
        
        header('Location: send_mail.php?email='.$email);
        exit();
    }
?>