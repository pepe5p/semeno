<?php
	require_once "php/connect.php";

	session_start();

	if((!isset($_SESSION['logged'])) || ($_SESSION['logged'] == false))
	{
		header('Location: login.php');
		exit();
	}
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
            <header class="centerBox leftPad title">
                <a href="profile.php"><i class="icon-left-circled"></i> TWÓJ PROFIL</a><i class="icon-right-open"></i> ZMIANA HASŁA
            </header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 offset-md-3 col-md-6 padding">
                        <form action="php/getchange_password.php" method="post" class="tile">
                            <header class="title"><i class="icon-user"></i> ZMIANA HASŁA</header>
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
                                            <span class="vertAlign">Nowe Hasło</span>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign">Powtórz Nowe Hasło</span>
                                        </div>
                                        <div class="descTile blue">
                                            <span class="vertAlign">Stare Hasło</span>
                                        </div>
                                    </div>
                                    <div class="col-7 noPadding">
										<?php
											if(isset($_SESSION['fpassword'])){
												$fpassword = $_SESSION['fpassword'];
												$spassword = $_SESSION['spassword'];
												$opassword = $_SESSION['opassword'];
                                                unset($_SESSION['fpassword']);
                                                unset($_SESSION['spassword']);
                                                unset($_SESSION['opassword']);
											} else {
												$fpassword = "";
												$spassword = "";
												$opassword = "";
											}
											echo<<<END
												<input name="fpassword" type="password" value="$fpassword" class="descTile" spellcheck="false"/>
												<input name="spassword" type="password" value="$spassword" class="descTile" spellcheck="false"/>
												<input name="opassword" type="password" value="$opassword" class="descTile" spellcheck="false"/>
END;
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-xl-6 noPadding">
                                        <div class="descTile button" onclick="document.location.href = 'profile.php';">
                                            <span class="vertAlign"><i class="icon-left-circled"></i> WRÓĆ DO PROFILU</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6 noPadding">
                                        <button type="submit" class="descTile button fancyButton">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> ZMIEŃ HASŁO</span>
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