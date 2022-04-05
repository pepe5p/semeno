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
            <header class="centerBox leftPad title">TWÓJ PROFIL</header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 col-md-6 col-xl-4 offset-xl-2 padding">
                        <div class="tile">
                            <div class="descTile button" onclick="document.location.href = 'php/getlogout.php';">
                                <span class="vertAlign"><i class="icon-logout"></i> WYLOGUJ SIĘ</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 padding">
                        <div class="tile">
                            <div class="descTile button" onclick="document.location.href = 'delete_account.php';">
                                <span class="vertAlign"><i class="icon-cancel"></i> USUŃ KONTO</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 padding">
                        <form action="php/getchange.php" method="post" class="tile autoHeight">
                            <header class="title"><i class="icon-user"></i> DANE PROFILU</header>
                            <?php
                                $connection = @new mysqli($host, $db_user, $db_password, $db_name);
        
                                if ($connection->connect_errno!=0)
                                {
                                    echo "<div class='article error'><i class='icon-cancel'></i> Error $connection->connect_errno</div>";
                                }
                                else
                                {
                                    echo "<div class='article'>Kliknij na pole aby edytować dane (email i hasło zmienia się osobno)</div>";
                                    if(isset($_SESSION['error'])){	
                                        $error = $_SESSION['error'];
                                        echo "<div class='article error'><i class='icon-cancel'></i> $error</div>";
                                        unset($_SESSION['error']);
                                    }
                                    if(isset($_SESSION['success'])){	
                                        $success = $_SESSION['success'];
                                        echo "<div class='article success'>$success</div>";
                                        unset($_SESSION['success']);
                                    }

                                    $user_id = $_SESSION['user_id'];
                                    $user_sql = sprintf('SELECT fname, lname, email, phone_nr, postal_code, city, street, number FROM users WHERE user_id = %u',
                                    mysqli_real_escape_string($connection, $user_id));

                                    if($user_result = @$connection->query($user_sql))
                                    {
                                        $row = mysqli_fetch_assoc($user_result);
                                        $fname = $row['fname'];
                                        $lname = $row['lname'];
                                        $phone_nr = $row['phone_nr'];
                                        $postal_code = $row['postal_code'];
                                        $city = $row['city'];
                                        $street = $row['street'];
                                        $email = $row['email'];
                                        $number = $row['number'];

                                        if(isset($_SESSION['fname'])){
                                            $fname = $_SESSION['fname'];
                                            $lname = $_SESSION['lname'];
                                            $phone_nr = $_SESSION['phone_nr'];
                                            $postal_code = $_SESSION['postal_code'];
                                            $city = $_SESSION['city'];
                                            $street = $_SESSION['street'];
                                            $number = $_SESSION['number'];
                                            
                                            unset($_SESSION['fname']);
                                            unset($_SESSION['lname']);
                                            unset($_SESSION['phone_nr']);
                                            unset($_SESSION['postal_code']);
                                            unset($_SESSION['city']);
                                            unset($_SESSION['street']);
                                            unset($_SESSION['number']);
                                        }

                                        echo<<<END
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
                                                </div>
                                                <div class="col-7 noPadding">
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
                                                </div>
                                                <div class="col-5 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign">Numer Telefonu</span>
                                                    </div>
                                                </div>
                                                <div class="col-7 noPadding">
                                                    <input name="phone_nr" type="text" value="$phone_nr" class="descTile" spellcheck="false"/>
                                                </div>
                                                <div class="col-5 noPadding">
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
                                                </div>
                                                <div class="col-7 noPadding">
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
                                                </div>
                                                <div class="col-5 noPadding">
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
                                                </div>
                                                <div class="col-7 noPadding">
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
                                                </div>
                                                <div class="col-5 noPadding">
                                                    <div class="descTile blue">
                                                        <span class="vertAlign"><i class="icon-key"></i> Email</span>
                                                    </div>
                                                </div>
                                                <div class="col-7 noPadding">
                                                    <input name="email" type="text" value="$email" class="descTile" spellcheck="false" disabled/>
                                                </div>
                                            </div>
                                        </div>
END;
                                        $user_result->close();
                                    }
                                }
                            ?>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-xl-6 noPadding">
                                        <div class="descTile button" onclick="document.location.href = 'profile.php';">
                                            <span class="vertAlign"><i class="icon-cancel"></i> PRZYWRÓĆ</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6 noPadding">
                                        <button type="submit" class="descTile button fancyButton">
                                            <span class="vertAlign"><i class="icon-user"></i> ZAKTUALIZUJ</span>
                                        </button>
                                    </div>
                                    <div class="col-12 col-xl-6 noPadding">
                                        <div class="descTile button" onclick="document.location.href = 'change_email.php';">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> ZMIEŃ EMAIL</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6 noPadding">
                                        <div class="descTile button" onclick="document.location.href = 'change_password.php';">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> ZMIEŃ HASŁO</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-md-6 padding">
                        <div class="tile autoHeight">
                            <header class="title"><i class="icon-basket"></i> HISTORIA ZAMÓWIEŃ</header>
                            <div class="ordersBox noPadding">
                                <?php
                                    if($connection->connect_errno!=0)
                                    {
                                        echo "<div class='article error'><i class='icon-cancel'></i> Error $connection->connect_errno</div>";
                                    }
                                    else
                                    {
                                        $order_sql = sprintf('SELECT o.unique_id, o.order_status, o.quantity, o.order_date,
                                        p.price FROM orders AS o, products AS p 
                                        WHERE o.prod_id = p.prod_id AND o.user_id = %u AND o.order_status != 0 ORDER BY o.order_date DESC, o.unique_id',
                                        mysqli_real_escape_string($connection, $user_id));

                                        if($order_result = @$connection->query($order_sql))
                                        {
                                            $orders_nr = mysqli_num_rows($order_result);
                                            $quantity = false;
                                            $unique_id = false;
                                            $order_status = false;
                                            if($orders_nr>0)
                                            {
                                                for ($i = 1; $i <= ($orders_nr+1); $i++)
                                                {
                                                    $row = mysqli_fetch_assoc($order_result);

                                                    if($unique_id == $row['unique_id']){
                                                        $quantity += $row['quantity'];
                                                        $price = $row['price'];
                                                        $new_price = number_format(((float)$new_price + $row['quantity'] * $price), 2, ",", "");
                                                    }
                                                    else{

                                                        if($quantity == 1) $new_quantity = "PRODUKT";
                                                        else if($quantity < 5) $new_quantity = "PRODUKTY";
                                                        else $new_quantity = "PRODUKTÓW";

                                                        if($order_status == 1){
                                                            $new_order_status = "DO OPŁACENIA";
                                                            $color = "s1";
                                                        }
                                                        else if($order_status == 2){
                                                            $new_order_status = "OPŁACONE";
                                                            $color = "s2";
                                                        }
                                                        else if($order_status == 3){
                                                            $new_order_status = "WYSŁANE";
                                                            $color = "s3";
                                                        }
                                                        else{
                                                            $new_order_status = "?";
                                                            $color = "s0";
                                                        }

                                                        if($unique_id != false){
                                                            echo<<<END
                                                                <div class="orderTile">
                                                                    <div class="container-fluid">
                                                                        <div class="row">
                                                                            <div class="header col-4 noPadding">$unique_id</div>
                                                                            <div class="details col-4 noPadding">$hour</div>
                                                                            <div class="details col-4 noPadding">$date</div>
                                                                            <div class="desc col-4 noPadding">$quantity $new_quantity</div>
                                                                            <div class="desc col-4 noPadding">$new_price PLN</div>
                                                                            <div class="$color desc col-4 noPadding">$new_order_status</div>
                                                                            <div class="details col-12 noPadding">
                                                                                <a href='order.php?unique_id=$unique_id'><i class="icon-right-circled"></i> SZCZEGÓŁY</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
END;
                                                        }
                                                        $unique_id = $row['unique_id'];
                                                        $order_status = $row['order_status'];
                                                        $quantity = $row['quantity'];
                                                        $order_date = $row['order_date'];
                                                        $price = $row['price'];
                                                        $new_price =  number_format((float)$quantity * $price, 2, ",", "");
                                                        $hour = substr($order_date, -8, 5);
                                                        $date = substr($order_date, 0, -9);
                                                    }
                                                }
                                            }
                                            else{
                                                echo "<div class='article error'><i class='icon-info-circled-alt'></i> Jeszcze niczego nie zamówiłeś w naszym sklepie. Chyba pora to zmienić</div>";
                                            } 
                                            $order_result->close();
                                        }
                                        $connection->close();
                                    }
                                ?>
                            </div>
                            <div class="container-fluid">
                                <form action="order.php"  method="get" class="row">
                                    <div class="col-5 noPadding">
                                        <div class="descTile blue">
                                            <span class="vertAlign">Identyfikator Zamówienia</span>
                                        </div>
                                    </div>
                                    <div class="col-7 noPadding">
                                        <input name="unique_id" type="text" maxlength="13" class="descTile" spellcheck="false"/>
                                    </div>
                                    <div class="col-12 col-xl-6 offset-xl-3 noPadding">
                                        <button type="submit" class="descTile button button">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> WYSZKUAJ ZAMÓWIENIE</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>