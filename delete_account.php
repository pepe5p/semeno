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
                <a href="profile.php"><i class="icon-left-circled"></i> TWÓJ PROFIL</a><i class="icon-right-open"></i> USUWANIE KONTA
            </header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 offset-md-3 col-md-6 padding">
                        <form action="php/getdelete_account.php" method="post" class="tile">
                            <header class="title"><i class="icon-user"></i> USUWANIE KONTA</header>
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
                                            <span class="vertAlign">Hasło</span>
                                        </div>
                                    </div>
                                    <div class="col-7 noPadding">
										<?php
											if(isset($_SESSION['password'])){
												$password = $_SESSION['password'];
                                                unset($_SESSION['password']);
											} else {
												$password = "";
											}
											echo<<<END
												<input name="password" type="password" value="$password" class="descTile" spellcheck="false"/>
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
                                        <button type="submit" class="descTile button">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> USUŃ</span>
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