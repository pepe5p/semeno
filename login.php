<?php
	session_start();

	if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
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
</head>
<body>
    <?php 
        include "php/nav.php";
	?>
    <main>
		<div class="longBar hBar">
            <header class="centerBox leftPad title">LOGOWANIE</header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 offset-md-3 col-md-6 padding">
                        <form action="php/getlogin.php" method="post" class="tile">
                            <header class="title"><i class="icon-user"></i> LOGOWANIE</header>
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
                                        <div class="descTile blue">
                                            <span class="vertAlign">Email</span>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign">Hasło</span>
                                        </div>
                                    </div>
                                    <div class="col-7 noPadding">
										<?php
											if(isset($_SESSION['email'])){
												$email = $_SESSION['email'];
												$password = $_SESSION['password'];
											} else {
												$email = "";
												$password = "";
											}
											echo<<<END
												<input name="email" type="text" value="$email" class="descTile" spellcheck="false"/>
												<input name="password" type="password" value="$password" class="descTile" spellcheck="false"/>
END;
											session_unset();
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-xl-6 noPadding">
                                        <div class="descTile button" onclick="document.location.href = 'register.php';">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> ZAREJESTRUJ SIĘ</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6 noPadding">
                                        <button type="submit" class="descTile button fancyButton">
                                            <span class="vertAlign"><i class="icon-login"></i> ZALOGUJ SIĘ</span>
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