<?php
	require_once "php/connect.php";
	session_start();

	if((!isset($_SESSION['logged'])) || ($_SESSION['logged'] == false))
	{
		header('Location: login.php');
		exit();
    }
    
    if(isset($_POST['prm_code']))
    {
        if($_POST['prm_code'] != "")
        {
            $prm_code = $_POST['prm_code'];
        }
        else $discount = 0;
    }
    else{
		header('Location: cart.php');
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
                <a href="cart.php"><i class="icon-left-circled"></i> TWÓJ KOSZYK</a><i class="icon-right-open"></i> KASA
            </header>
        </div>
        <div class="centerBox">
            <div class="container-fluid">
                <div class="row horizontalPad">
                    <div class="col-12 col-md-6 padding">
                        <div class="tile autoHeight">
                            <header class="title"><i class="icon-basket"></i> PRODUKTY W KOSZYKU <?php if(isset($all_nr)) echo "(".$all_nr.")"; ?></header>
                            <div class="ordersBox noPadding">
                                <?php
                                    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
            
                                    if ($connection->connect_errno!=0)
                                    {
                                        echo "<div class='article error'><i class='icon-cancel'></i> Error $connection->connect_errno</div>";
                                    }
                                    else
                                    {
                                        $user_id = $_SESSION['user_id'];
                                        $order_sql = sprintf('SELECT o.order_id, o.prod_id, o.quantity, o.size, p.name, p.price, p.prod_img1 FROM orders AS o, products AS p 
                                        WHERE o.prod_id = p.prod_id AND o.user_id = %u AND o.order_status = 0 ORDER BY o.order_date DESC',
                                        mysqli_real_escape_string($connection, $user_id));

                                        if($order_result = @$connection->query($order_sql))
                                        {
                                            $orders_nr = mysqli_num_rows($order_result);
                                            if($orders_nr > 0)
                                            {
                                                $all_price = 0;
                                                for ($i = 1; $i <= $orders_nr; $i++)
                                                {
                                                    $row = mysqli_fetch_assoc($order_result);
                                                    $order_id = $row['order_id'];
                                                    $prod_id = $row['prod_id'];
                                                    $quantity = $row['quantity'];
                                                    $size = $row['size'];
                                                    $name = $row['name'];
                                                    $price = $row['price'];
                                                    $prod_img1 = $row['prod_img1'];

                                                    if($quantity != 1) $for_one = "<br><span class='blue'>($price PLN za sztukę)</span>";
                                                    else $for_one = "";
                                                    $new_price = number_format((float)$quantity*$price, 2, ".", "");
                                                    $all_price += $new_price;
                                                    
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
                                                                        <div class="one_third_height descTile">
                                                                            <div class="container-fluid vertAlign">
                                                                                <div class="row">
                                                                                    <div class="s2 desc col-12 noPadding">$name ($size)</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="one_third_height descTile">
                                                                            <div class="container-fluid vertAlign">
                                                                                <div class="row">
                                                                                    <div class="desc col-12 noPadding">
                                                                                        $new_price PLN$for_one
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="one_third_height descTile">
                                                                            <div class="container-fluid vertAlign">
                                                                                <div class="row">
                                                                                    <div class="desc col-4 noPadding title">
                                                                                        <a href="php/getchange_quantity.php?order_id=$order_id&quantity=$quantity&sign=minus"><i class="icon-minus-1"></i></a>
                                                                                    </div>
                                                                                    <div class="desc col-4 noPadding title">
                                                                                        $quantity
                                                                                    </div>
                                                                                    <div class="desc col-4 noPadding title">
                                                                                        <a href="php/getchange_quantity.php?order_id=$order_id&quantity=$quantity&sign=plus"><i class="icon-plus-1"></i></a>
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
                                                $all_price = number_format((float)$all_price, 2, ",", "");
                                            }
                                            else{
                                                echo "<div class='article error'><i class='icon-info-circled-alt'></i> Aktualnie nie posiadasz żadnego produktu w koszyku</div>";
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
                        <form action="php/getchange.php" method="post" class="tile autoHeight">
                            <header class="title"><i class="icon-wallet"></i> PŁATNOŚĆ</header>
                            <div class='article'>Upewnij się, że dane na twoim profilu są poprawne zanim dokonasz transakcji</div>
							<?php
								if(isset($_SESSION['error'])){	
									$error = $_SESSION['error'];
									echo "<div class='article error'>$error</div>";
                                    unset($_SESSION['error']);
								}
							?>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6 noPadding">
                                        <div class="descTile blue">
                                            <span class="vertAlign">Wartość Koszyka</span>
                                        </div>
                                    </div>
                                    <div class="col-6 noPadding">
                                        <div class="descTile title">
                                            <span class="vertAlign">
                                                <?php 
                                                    if($connection->connect_errno == 0 && isset($all_price)) echo "$all_price PLN";
                                                    else echo "0 PLN";
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6 noPadding">
                                        <div class="descTile blue">
                                            <span class="vertAlign">Kod Promocyjny</span>
                                        </div>
                                    </div>
                                    <div class="col-6 noPadding">
                                        <input name="prm_code" type="text" class="descTile" spellcheck="false"/>
                                    </div>
                                    <div class="col-12 noPadding">
                                        <button type="submit" class="descTile button fancyButton">
                                            <span class="vertAlign"><i class="icon-right-circled"></i> ZAMAWIAM</span>
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