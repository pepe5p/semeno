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
        <article>
            <div class="longBar hBar">
                <header class="centerBox leftPad title">PRODUKTY SEMENO</header>
            </div>
            <div class="centerBox">
                <div class="container-fluid">
                    <section class="row horizontalPad">
                        <?php
                            require_once "php/connect.php";

                            $connection = @new mysqli($host, $db_user, $db_password, $db_name);
	
                            if ($connection->connect_errno!=0)
                            {
                                echo "<div class='article error'><i class='icon-cancel'></i> Error $connection->connect_errno</div>";
                            }
                            else
                            {
                                $sql = 'SELECT prod_id, name, price, old_price, prod_img1, XS, S, M, L, XL, XXL FROM products ORDER BY prod_id DESC';

                                if($result = @$connection->query($sql))
                                {
                                    $prod_nr = mysqli_num_rows($result);
                                    for ($i = 1; $i <= $prod_nr; $i++) 
                                    {
                                        $row = mysqli_fetch_assoc($result);
                                        $prod_id = $row['prod_id'];
                                        $name = $row['name'];
                                        $price = $row['price'];
                                        $old_price = $row['old_price'];
                                        $prod_img1 = $row['prod_img1'];
                                        $XS = $row['XS'];
                                        $S = $row['S'];
                                        $M = $row['M'];
                                        $L = $row['L'];
                                        $XL = $row['XL'];
                                        $XXL = $row['XXL'];
                                        $all_quantity = $XS + $S + $M + $L + $XL + $XXL;

                                        $info = "";
                                        $old_price_span = "";
                                        if($all_quantity == 0) $info = "<div class='info soldOut'>WYPRZEDANE</div>";
                                        else
                                        {
                                            if($old_price != 0)
                                            {
                                                $discount = ceil(100-(($price*100)/$old_price));
                                                $info = "<div class='info bargain'>PROMOCJA -$discount%</div>";
                                                $old_price_span = "<span class='vertAlign oldPrice'>$old_price PLN</span>";
                                            }
                                        }

                                        echo<<<END
                                            <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                                                <div class="tile">
                                                    <div class="imgBox">
                                                        <div class="gradientStrip"></div>
                                                        $info
                                                        <img src="$prod_img1">
                                                        <a href="product.php?prod_id=$prod_id&name=$name"></a>
                                                    </div>
                                                    <div class="descTile">
                                                        <span class="vertAlign">$name</span>
                                                    </div>
                                                    <div class="descTile">
                                                        <span class="vertAlign">$price PLN</span>
                                                        $old_price_span
                                                    </div>
                                                    <div class="descTile button" onclick="document.location.href = 'product.php?prod_id=$prod_id&name=$name';">
                                                        <span class="vertAlign"><i class="icon-basket"></i> DODAJ DO KOSZYKA</span>
                                                    </div>
                                                </div>
                                            </div>
END;
                                    }
                                    $result->close();
                                }
                            }
                            $connection->close();
                        ?>
                    </section>
                    <div class="marginBottom"></div>
                </div>
            </div>
        </article>
    </main>
</body>
</html>