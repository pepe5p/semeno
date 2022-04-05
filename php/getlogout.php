<?php

	session_start();
    session_unset();

    $_SESSION['success'] = 'Wylogowanie przebiegło pomyślnie';
    header('Location: ../login.php');
    exit();
?>