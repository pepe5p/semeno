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
            <div class="centerBox">
                <div class="marginBottom"></div>
                <div class="container-fluid">
                    <!-- <section class="row horizontalPad">
                        <div class="col-12 col-md-6 offset-md-3 padding">
                            <div class="tile">
                                <?php
                                    // if(isset($_SESSION['error'])){	
                                    //     $error = $_SESSION['error'];
                                    //     echo "<div class='article error'><i class='icon-cancel'></i> $error</div>";
                                    //     unset($_SESSION['error']);
                                    // }
                                    // if(isset($_SESSION['success'])){	
                                    //     $success = $_SESSION['success'];
                                    //     echo "<div class='article success'>$success</div>";
                                    //     unset($_SESSION['success']);
                                    // }
                                ?>
                            </div>
                        </div>
                    </section> -->
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
                                $sql = 'SELECT prod_id, name, price, old_price, prod_img1, XS, S, M, L, XL, XXL FROM products ORDER BY prod_id DESC LIMIT 4';

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
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 offset-sm-6 offset-md-8 offset-xl-9 padding">
                            <div class="tile">
                                <div class="descTile button fancyButton" onclick="document.location.href = 'store.php';">
                                    <span class="vertAlign"><i class="icon-right-circled"></i> ZOBACZ WSZYSTKO</span>
                                </div>
                            </div>
                        </div> 
                        <!-- <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <header class="title">EKSTRA KOSZULECZKA</header>
                                <article class="article">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in neque auctor, posuere lorem eget, elementum lorem. Etiam facilisis varius fringilla. Etiam posuere malesuada lacinia. Praesent quam lectus, suscipit in commodo eu, rhoncus ac felis. Quisque ac lorem turpis. Vivamus tincidunt auctor ex, ut congue nunc porta sed. Cras sed tellus nibh. Sed enim sem, varius a massa et, venenatis efficitur elit.
                                </article>
                                <div class="descTile button down">
                                    <span class="vertAlign"><i class="icon-right-circled"></i> ZOBACZ</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <div class="imgBox">
                                    <div class="gradientStrip"></div>
                                    <div class="info soldOut">WYPRZEDANE</div>
                                    <img src="img/products/product_1.jpg">
                                    <a href=""></a>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">EKSTRA KOSZULECZKA</span>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">54,99 PLN</span>
                                </div>
                                <div class="descTile button">
                                    <span class="vertAlign"><i class="icon-basket"></i> DO KOSZYKA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <div class="imgBox">
                                    <div class="gradientStrip"></div>
                                    <div class="info bargain">PROMOCJA -30%</div>
                                    <img src="img/products/product_1.jpg">
                                    <a href=""></a>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">EKSTRA KOSZULECZKA</span>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">54.99 PLN</span>
                                    <span class="vertAlign oldPrice">64,99 PLN</span>
                                </div>
                                <div class="descTile button">
                                    <span class="vertAlign"><i class="icon-basket"></i> DO KOSZYKA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <div class="imgBox">
                                    <div class="gradientStrip"></div>
                                    <img src="img/products/product_1.jpg">
                                    <a href=""></a>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">EKSTRA KOSZULECZKA</span>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">54,99 PLN</span>
                                </div>
                                <div class="descTile button">
                                    <span class="vertAlign"><i class="icon-basket"></i> DO KOSZYKA</span>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="row horizontalPad">
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <header class="title">EKSTRA KOSZULECZKA</header>
                                <article class="article">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in neque auctor, posuere lorem eget, elementum lorem. Etiam facilisis varius fringilla. Etiam posuere malesuada lacinia. Praesent quam lectus, suscipit in commodo eu, rhoncus ac felis. Quisque ac lorem turpis. Vivamus tincidunt auctor ex, ut congue nunc porta sed. Cras sed tellus nibh. Sed enim sem, varius a massa et, venenatis efficitur elit.
                                </article>
                                <div class="descTile button down"><i class="icon-right-circled"></i> ZOBACZ</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <div class="imgBox">
                                    <div class="gradientStrip"></div>
                                    <div class="info soldOut">WYPRZEDANE</div>
                                    <img src="img/products/product_1.jpg">
                                    <a href=""></a>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">EKSTRA KOSZULECZKA</span>
                                </div>
                                <div class="descTile">
                                    <span class="vertAlign">54,99 PLN</span>
                                </div>
                                <div class="descTile button">
                                    <span class="vertAlign"><i class="icon-basket"></i> DO KOSZYKA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 padding">
                            <div class="tile">
                                <div class="imgBox">
                                    <div class="gradientStrip"></div>
                                    <div class="info bargain">PROMOCJA -30%</div>
                                    <img src="img/products/product_1.jpg">
                                    <a href=""></a>
                                </div>
                                <div class="descTile name">
                                    <span class="vertAlign">EKSTRA KOSZULECZKA</span>
                                </div>
                                <div class="descTile price">
                                    <span class="vertAlign">54.99 PLN</span>
                                    <span class="vertAlign oldPrice">64,99 PLN</span>
                                </div>
                                <div class="descTile button">
                                    <span class="vertAlign"><i class="icon-basket"></i> DO KOSZYKA</span>
                                </div>
                            </div>
                        </div>-->
                    </section>
                    <div class="centerBox marginBottom"></div>
                </div>
            </div>
        </article>
        <article class="hiddenOverflow">
            <section class="centerBox">
                <div class="containerBox boxLeft">
                    <div class="circle"></div>
                    <img src="img/cotton.jpg">
                    <header class="title">MATERIAŁ</header>
                    <div class="article">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in neque auctor, posuere lorem eget, elementum lorem. Etiam facilisis varius fringilla. Etiam posuere malesuada lacinia. Praesent quam lectus, suscipit in commodo eu, rhoncus ac felis. Quisque ac lorem turpis. Vivamus tincidunt auctor ex, ut congue nunc porta sed. Cras sed tellus nibh. Sed enim sem, varius a massa et, venenatis efficitur elit.
                    </div>
                </div>
            </section>
            <section class="centerBox">
                <div class="containerBox boxRight">
                    <div class="circle"></div>
                    <img src="img/cotton.jpg">
                    <header class="title">KRÓJ</header>
                    <div class="article">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in neque auctor, posuere lorem eget, elementum lorem. Etiam facilisis varius fringilla. Etiam posuere malesuada lacinia. Praesent quam lectus, suscipit in commodo eu, rhoncus ac felis. Quisque ac lorem turpis. Vivamus tincidunt auctor ex, ut congue nunc porta sed. Cras sed tellus nibh. Sed enim sem, varius a massa et, venenatis efficitur elit.
                    </div>
                </div>
            </section>
            <section class="centerBox">
                <div class="containerBox boxLeft">
                    <header class="title">O NAS</header>
                    <div class="article">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in neque auctor, posuere lorem eget, elementum lorem. Etiam facilisis varius fringilla. Etiam posuere malesuada lacinia. Praesent quam lectus, suscipit in commodo eu, rhoncus ac felis. Quisque ac lorem turpis. Vivamus tincidunt auctor ex, ut congue nunc porta sed. Cras sed tellus nibh. Sed enim sem, varius a massa et, venenatis efficitur elit.
                        <br><br>
                        <a href="article.php"><i class="icon-right-circled"></i> CZYTAJ DALEJ</a>
                    </div>
                </div>
            </section>
        </article>
        <article>
            <div class="longBar advancementsBar">
                <div class="centerBox">
                    <div class="container-fluid">
                        <div class="row padding">
                            <div class="col-12 col-md-6 col-xl-3 padding">
                                <div class="aTile">
                                    <i class="icon-lock"></i>
                                    <div class="title">BEZPIECZEŃSTWO</div>
                                    <div class="desc">POSIADAMY AKTUALNY CERTYFIKAT SSL</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3 padding">
                                <div class="aTile">
                                    <i class="icon-users-outline" style="padding-right: 15px;"></i>
                                    <div class="title">FIRMA Z POLSKI</div>
                                    <div class="desc">NASZE UBRANIA SĄ PRODUKOWANE W 100% W POLSCE</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3 padding">
                                <div class="aTile">
                                    <i class="icon-truck"></i>
                                    <div class="title">DOSTAWA</div>
                                    <div class="desc">POSIADAMY AKTUALNY CERTYFIKAT SSL</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3 padding">
                                <div class="aTile">
                                    <i class="icon-money"></i>
                                    <div class="title">ZWROTY</div>
                                    <div class="desc">POSIADAMY AKTUALNY CERTYFIKAT SSL</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </main>
    <?php 
        include "php/footer.php";
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="bootstrap_js/bootstrap.min.js"></script>
</body>
</html>