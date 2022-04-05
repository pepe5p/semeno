<?php
	require_once "php/connect.php";

    session_start();
    
    if(!isset($_GET['unique_id'])) {
		header('Location: profile.php');
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
                <a href="profile.php"><i class="icon-left-circled"></i> TWÓJ PROFIL</a><i class="icon-right-open"></i>ZAMÓWIENIE <?php echo "<span class='blue'>".$_GET["unique_id"]."</span>";?>
            </header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 col-md-6 padding">
                        <div class="tile autoHeight">
                            <header class="title"><i class="icon-basket"></i> PRODUKTY</header>
                            <div class="ordersBox noPadding">
                                <?php
                                    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
            
                                    if ($connection->connect_errno!=0)
                                    {
                                        echo "<div class='article error'><i class='icon-cancel'></i> Error $connection->connect_errno</div>";
                                    }
                                    else
                                    {
                                        $unique_id = htmlentities($_GET['unique_id']);
                                        $user_id = $_SESSION['user_id'];
                                        $order_sql = sprintf('SELECT o.order_status, o.user_id, o.prod_id, o.quantity, o.size, o.order_date, o.discount,
                                        p.name, p.price, p.prod_img1 FROM orders AS o, products AS p 
                                        WHERE o.prod_id = p.prod_id AND o.unique_id = "%s" AND o.order_status != 0',
                                        mysqli_real_escape_string($connection, $unique_id));

                                        if($order_result = @$connection->query($order_sql))
                                        {
                                            $orders_nr = mysqli_num_rows($order_result);
                                            if($orders_nr > 0)//TAK NA WSZELKI
                                            {
                                                $all_price = 0;
                                                for($i = 1; $i <= $orders_nr; $i++)
                                                {
                                                    $row = mysqli_fetch_assoc($order_result);
                                                    $order_status = $row['order_status'];
                                                    $new_user_id = $row['user_id'];
                                                    $prod_id = $row['prod_id'];
                                                    $quantity = $row['quantity'];
                                                    $size = $row['size'];
                                                    $order_date = $row['order_date'];
                                                    $discount = $row['discount'];
                                                    $name = $row['name'];
                                                    $price = $row['price'];
                                                    $prod_img1 = $row['prod_img1'];
                                                    
                                                    if($quantity != 1) $for_one = "<br><span class='blue'>($price PLN za sztukę)</span>";
                                                    else $for_one = "";
                                                    $new_price =  number_format((float)$quantity * $price, 2, ".", "");
                                                    $all_price += $new_price;
                                                    $hour = substr($order_date, -8, 5);
                                                    $date = substr($order_date, 0, -9);
                                                    if($user_id == $new_user_id && $order_status == 1){
                                                        $payment = '<div class="col-12 noPadding">
                                                            <button type="submit" class="descTile button fancyButton">
                                                                <span class="vertAlign"><i class="icon-right-circled"></i> PŁACĘ</span>
                                                            </button>
                                                        </div>';
                                                    } else $payment = "";
                                                    
                                                    echo<<<END
                                                        <div class="orderTile">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="col-5 col-lg-3 noPadding">
                                                                        <div class="imgBox">
                                                                            <div class="gradientStrip"></div>
                                                                            <img src="$prod_img1"/>
                                                                            <a href="product.php?prod_id=$prod_id&name=$name"></a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-7 col-lg-9 noPadding">
                                                                        <div class="descTile">
                                                                            <div class="container-fluid vertAlign">
                                                                                <div class="row">
                                                                                    <div class="s2 desc col-12 noPadding">$name ($size)</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="descTile">
                                                                            <div class="container-fluid vertAlign">
                                                                                <div class="row">
                                                                                    <div class="desc col-12 noPadding">
                                                                                        $new_price PLN$for_one
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="descTile">
                                                                            <div class="container-fluid vertAlign">
                                                                                <div class="row">
                                                                                    <div class="desc col-4 offset-4 noPadding title">
                                                                                        $quantity
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
END;
                                                }
                                            }
                                            else{
                                                echo "<div class='article error'><i class='icon-info-circled-alt'></i> Takie zamównienie nie istnieje</div>";
                                            } 
                                            $order_result->close();
                                        }
                                        $connection->close();
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 padding">
                        <div class="tile autoHeight">
                            <header class="title"><i class="icon-menu"></i> SZCZEGÓŁY</header>
							<?php
								if(isset($_SESSION['error'])){	
									$error = $_SESSION['error'];
									echo "<div class='article error'>$error</div>";
                                }
                                if ($connection->connect_errno == 0 && $orders_nr > 0 && $row['order_status'] != 0)
                                {
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

                                    $real_price = ceil($all_price*(100-$discount))/100;

                                    $all_price = number_format((float)$all_price, 2, ",", "");
                                    $real_price = number_format((float)$real_price, 2, ",", "");

                                    echo<<<END
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-6 noPadding">
                                                <div class="descTile blue">
                                                    <span class="vertAlign">Data</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile">
                                                    <span class="vertAlign">$date</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile blue">
                                                    <span class="vertAlign">Godzina</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile">
                                                    <span class="vertAlign">$hour</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile blue">
                                                    <span class="vertAlign">Wartość Zamówienia</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile">
                                                    <span class="vertAlign">$all_price PLN</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile blue">
                                                    <span class="vertAlign">Kod Promocyjny</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile">
                                                    <span class="vertAlign">-$discount%</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile blue">
                                                    <span class="vertAlign">Wartość Zamówienia z Kodem</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile title">
                                                    <span class="vertAlign">$real_price PLN</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile blue">
                                                    <span class="vertAlign">Status</span>
                                                </div>
                                            </div>
                                            <div class="col-6 noPadding">
                                                <div class="descTile title $color">
                                                    <span class="vertAlign">$new_order_status</span>
                                                </div>
                                            </div>
                                            $payment
                                        </div>
                                    </div>
END;
                                }
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>