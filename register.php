<?php
    require_once "php/connect.php";

	session_start();

	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
		header('Location: profile.php');
		exit();
	}
    $_SESSION['logged'] = false;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php 
        include "php/head.php";
    ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php 
        include "php/nav.php";
	?>
	<main>
		<div class="longBar hBar">
            <header class="centerBox leftPad title">REJESTRACJA</header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 offset-md-3 col-md-6 padding">
                        <form action="php/getregister.php" method="post" class="tile">
                            <header class="title"><i class="icon-user"></i> REJESTRACJA</header>
							<?php
								if(isset($_SESSION['error'])){	
									$error = $_SESSION['error'];
									echo "<div class='article error'><i class='icon-cancel'></i> $error</div>";
                                    unset($_SESSION['error']);
								}
							?>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-5 noPadding">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-xl-6 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Imię</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-xl-6 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Nazwisko</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign">Numer Telefonu</span>
                                        </div>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-xl-6 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Kod Pocztowy</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-xl-6 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Miasto</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-xl-4 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Ulica</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-xl-8 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Numer Mieszkania</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign"><i class="icon-key"></i> Email</span>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign"><i class="icon-key"></i> Hasło</span>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign">Powtórz Hasło</span>
                                        </div>
                                    </div>
                                    <div class="col-7 noPadding">
										<?php
											if(isset($_SESSION['email'])){
												$fname = $_SESSION['fname'];
												$lname = $_SESSION['lname'];
												$phone_nr = $_SESSION['phone_nr'];
												$postal_code = $_SESSION['postal_code'];
												$city = $_SESSION['city'];
												$street = $_SESSION['street'];
												$number = $_SESSION['number'];
												$email = $_SESSION['email'];
												$fpassword = $_SESSION['fpassword'];
												$spassword = $_SESSION['spassword'];
											} else {
												$fname = "";
												$lname = "";
												$phone_nr = "";
												$postal_code = "XX-XXX";
												$city = "";
												$street = "";
												$phone_nr = "";
												$number = "";
												$email = "";
												$fpassword = "";
												$spassword = "";
											}
											echo<<<END
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 col-xl-6 noPadding">
                                                    <input name="fname" type="text" value="$fname" class="descTile" spellcheck="false"/>
                                                    </div>
                                                    <div class="col-12 col-xl-6 noPadding">
                                                    <input name="lname" type="text" value="$lname" class="descTile" spellcheck="false"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <input name="phone_nr" type="text" value="$phone_nr" class="descTile" spellcheck="false"/>
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 col-xl-6 noPadding">
                                                        <input name="postal_code" type="text" value="$postal_code" class="descTile" spellcheck="false"/>
                                                    </div>
                                                    <div class="col-12 col-xl-6 noPadding">
                                                        <input name="city" type="text" value="$city" class="descTile" spellcheck="false"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 col-xl-8 noPadding">
                                                        <input name="street" type="text" value="$street" class="descTile" spellcheck="false"/>
                                                    </div>
                                                    <div class="col-12 col-xl-4 noPadding">
                                                        <input name="number" type="text" value="$number" class="descTile" spellcheck="false"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <input name="email" type="text" value="$email" class="descTile" spellcheck="false"/>
                                            <input name="fpassword" type="password" value="$fpassword" class="descTile" spellcheck="false"/>
                                            <input name="spassword" type="password" value="$spassword" class="descTile" spellcheck="false"/>
END;
											session_unset();
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="g-recaptcha" data-theme="dark" data-sitekey="6LfgK8MZAAAAAB8drAIcjQw_fEpmXj0i1xcSEhU0"></div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-xl-6 noPadding">
                                        <div class="descTile button" onclick="document.location.href = 'login.php';">
                                            <span class="vertAlign"><i class="icon-left-circled"></i> WRÓĆ DO LOGOWANIA</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6 noPadding">
                                        <button type="submit" class="descTile button fancyButton">
                                            <span class="vertAlign"><i class="icon-login"></i> ZAREJESTRUJ SIĘ</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>