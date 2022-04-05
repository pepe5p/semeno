<?php
    $header = "Oczekiwanie na potwierdzenie email'a";
    if(isset($_GET['email']))
    {
        $email = $_GET['email'];
        $content = '
        <div class="textContainer">
            Aby aktywować konto musisz potwierdzić swój email klikając w link wysłany w mailu.<br>
            Email, na który został wysłany mail to '.$email.'
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-3 offset-sm-6 offset-md-8 offset-xl-9 padding">
            <div class="tile">
                <div class="descTile button fancyButton" onclick="document.location.href=`php/send_mail.php?email='.$email.'`;">
                    <span class="vertAlign"><i class="icon-right-circled"></i> WYŚLIJ PONOWNIE</span>
                </div>
            </div>
        </div>
        <div class="marginBottom"></div>';
    }
    else $content = '';
?>