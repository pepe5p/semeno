<?php
    if(!isset($_GET['prod_id'])) {
		header('Location: store.php');
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
	<script>
        var number = 1;
        function img(e){
            var newImg = $(e).attr('src');
            $('#sliderImg').attr('src', newImg);
            $('#selectContainer > .col-12 > .selectBox > img').removeClass('active');
            $(e).addClass('active');
        }
        function change_nr(value, price){
            if(!(number == 1 && value == -1)){
                $('#number').text(number+value);
                $('#price').text(((number+value)*price).toFixed(2)+" PLN");
                $('#quantity').val(number+value);
                number += value;
            }
        }
        function sizeCheck(){
            var value = $('input[name="size"]:checked').val();
            if(value == undefined) alert("Wybierz rozmiar");
            else $('form').submit()   
        }
        document.addEventListener("DOMContentLoaded", function(){
            $('#submitButton').on('click', sizeCheck);
        });
	</script>
    <main>
        <article>
            <div class="longBar hBar">
                <header class="centerBox leftPad title">
                    <a href="store.php"><i class="icon-left-circled"></i> PRODUKTY SEMENO</a><i class="icon-right-open"></i> <?php echo $_GET['name']; ?>
                </header>
            </div>
            <?php
                require_once "php/connect.php";

                $connection = @new mysqli($host, $db_user, $db_password, $db_name);

                if ($connection->connect_errno!=0)
                {
                    echo "<div class='article error'><i class='icon-cancel'></i> Error $connection->connect_errno</div>";
                }
                else
                {
                    if(isset($_SESSION['error'])){	
                        $error = $_SESSION['error'];
                        $error_tile = "<div class='article error'><i class='icon-cancel'></i> $error</div>";
                        unset($_SESSION['error']);
                    }
                    else $error_tile = "";
                    
                    $prod_id = $_GET['prod_id'];
                    $sql = sprintf('SELECT name, price, old_price, description, prod_img1, prod_img2, prod_img3, prod_img4, other_color1, other_color2, other_color3, XS, S, M, L, XL, XXL FROM products WHERE prod_id = "%s"',
                    mysqli_real_escape_string($connection, $prod_id));

                    if($result = @$connection->query($sql))
                    {
                        $row = mysqli_fetch_assoc($result);
                        $name = $row['name'];
                        $price = $row['price'];
                        $old_price = $row['old_price'];
                        $description = $row['description'];
                        $prod_img1 = (string)$row['prod_img1'];
                        $prod_img2 = (string)$row['prod_img2'];
                        $prod_img3 = (string)$row['prod_img3'];
                        $prod_img4 = (string)$row['prod_img4'];
                        $other_color1 = $row['other_color1'];
                        $other_color2 = $row['other_color2'];
                        $other_color3 = $row['other_color3'];
                        $XS = $row['XS'];
                        $S = $row['S'];
                        $M = $row['M'];
                        $L = $row['L'];
                        $XL = $row['XL'];
                        $XXL = $row['XXL'];
                        if($prod_img2 != "") $prod_img2 = "<div class='col-12 noPadding'><div class='selectBox'><img onclick='img(this)' src='$prod_img2'></div></div>";
                        if($prod_img3 != "") $prod_img3 = "<div class='col-12 noPadding'><div class='selectBox'><img onclick='img(this)' src='$prod_img3'></div></div>";
                        if($prod_img4 != "") $prod_img4 = "<div class='col-12 noPadding'><div class='selectBox'><img onclick='img(this)' src='$prod_img4'></div></div>";
                        $check_XS = "";
                        $check_S = "";
                        $check_M = "";
                        $check_L = "";
                        $check_XL = "";
                        $check_XXL = "";
                        if($XS == 0) $check_XS = 'disabled';
                        if($S == 0) $check_S = 'disabled';
                        if($M == 0) $check_M = 'disabled';
                        if($L == 0) $check_L = 'disabled';
                        if($XL == 0) $check_XL = 'disabled';
                        if($XXL == 0) $check_XXL = 'disabled';

                        $bargain = "";
                        $old_price_span = "";
                        if($old_price != 0){
                            $discount = ceil(100-(($price*100)/$old_price));
                            $bargain = "<div class='info bargain'>PROMOCJA -$discount%</div>";
                            $old_price_span = "<span class='vertAlign oldPrice'>$old_price PLN</span>";
                        }

                        $img_sql = "SELECT prod_id, name, prod_img1 FROM products WHERE prod_id = $other_color1 OR prod_id = $other_color2 OR prod_id = $other_color3";
                        if($img_result = @$connection->query($img_sql))
                        {
                            $other_color_tile[0] = "";
                            $other_color_tile[1] = "";
                            $other_color_tile[2] = "";
                            $other_color_nr = mysqli_num_rows($img_result);
                            for ($i = 0; $i < $other_color_nr; $i++) 
                            {
                                $row = mysqli_fetch_assoc($img_result);
                                if($row['prod_img1'] != "") {
                                    $other_color_prod_id = $row['prod_id'];
                                    $other_color_name = $row['name'];
                                    $other_color_img1 = $row['prod_img1'];
                                    $other_color_tile[$i] = "
                                    <div class='col-6 col-md-3 padding'>
                                        <div class='imgBox'>
                                            <div class='gradientStrip'></div>
                                            <img src='$other_color_img1'>
                                            <a href='product.php?prod_id=$other_color_prod_id&name=$other_color_name'></a>
                                        </div>
                                    </div>";
                                }
                            }
                            
                            if($other_color_tile[0] == "" && $other_color_tile[1] == "" && $other_color_tile[2] == ""){
                                $other_colors = "";
                            }
                            else {
                                $other_colors = "
                                <div class='col-12 col-lg-6 padding'>
                                    <div class='container-fluid'>
                                        INNE WARIANTY KOLORYSTYCZNE:
                                        <div class='row noPadding'>".$other_color_tile[0].$other_color_tile[1].$other_color_tile[2]."</div>
                                    </div>
                                </div>";
                            }
                        } else $other_colors = "";

                        echo<<<END
                            <div class="centerBox padding marginBottom">
                                <div class="tile container-fluid">
                                    <div class="row horizontalPad">
                                        <div class="col-2 col-lg-1 verticalPad">
                                            <div class="container-fluid">
                                                <div id="selectContainer" class="row noPadding">
                                                    <div class="col-12 noPadding">
                                                        <div class="selectBox">
                                                            <img onclick='img(this)' class="active" src="$prod_img1">
                                                        </div>
                                                    </div>
                                                    $prod_img2
                                                    $prod_img3
                                                    $prod_img4
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-10 col-lg-5 padding">
                                            <div class="sliderBox">
                                                <img id="sliderImg" src="$prod_img1">
                                            </div>
                                        </div>
                                        <form action="php/add_to_cart.php" method="post" class="col-12 col-lg-6 padding">
                                            $error_tile
                                            <div class="descTile title">
                                                <span class="vertAlign">$name</span>
                                            </div>
                                            <div class="descTile title">
                                                <span class="vertAlign">$price PLN</span>
                                                $old_price_span
                                            </div>
                                            <div class="container-fluid">
                                                <div class="paragraph">WYBIERZ ROZMIAR:</div>
                                                <div class="row noPadding">
                                                    <div class="col-4 col-lg-2 noPadding"><input id="xs" value="XS" type="radio" name="size" $check_XS/><label for="xs" class="descTile button">XS</label></div>
                                                    <div class="col-4 col-lg-2 noPadding"><input id="s" value="S" type="radio" name="size" $check_S/><label for="s" class="descTile button">S</label></div>
                                                    <div class="col-4 col-lg-2 noPadding"><input id="m" value="M" type="radio" name="size" $check_M/><label for="m" class="descTile button">M</label></div>
                                                    <div class="col-4 col-lg-2 noPadding"><input id="l" value="L" type="radio" name="size" $check_L/><label for="l" class="descTile button">L</label></div>
                                                    <div class="col-4 col-lg-2 noPadding"><input id="xl" value="XL" type="radio" name="size" $check_XL/><label for="xl" class="descTile button">XL</label></div>
                                                    <div class="col-4 col-lg-2 noPadding"><input id="xxl" value="XXL" type="radio" name="size" $check_XXL/><label for="xxl" class="descTile button">XXL</label></div>
                                                </div>
                                            </div>
                                            <div class="container-fluid">
                                                <div class="paragraph">WYBIERZ ILOŚĆ:</div>
                                                <div class="row noPadding">
                                                    <div class="desc col-4 col-lg-2 noPadding title">
                                                        <a href="#" onclick="change_nr(-1, $price)"><i class="icon-minus-1"></i></a>
                                                    </div>
                                                    <div id="number" class="desc col-4 col-lg-2 noPadding title">1</div>
                                                    <div class="desc col-4 col-lg-2 noPadding title">
                                                        <a href="#" onclick="change_nr(1, $price)"><i class="icon-plus-1"></i></a>
                                                    </div>
                                                    <div id="price" class="desc col-12 col-lg-6 noPadding title">$price PLN</div>
                                                </div>
                                            </div>
                                            <input name="prod_id" value="$prod_id" type="hidden"/>
                                            <input name="quantity" id="quantity" value="1" type="hidden"/>
                                            <button id="submitButton" type="button" class="descTile button fancyButton">
                                                <span class="vertAlign"><i class="icon-basket"></i> DODAJ DO KOSZYKA</span>
                                            </button>
                                            <div class="paragraph">OPIS PRODUKTU:</div>
                                            <div class="article">$description</div>
                                        </form>
                                        <div class="col-12 col-lg-6 padding">
                                            <div class='container-fluid'>
                                                TABELA ROZMIARÓW:
                                                <div class='row noPadding'>
                                                    <img class="col-12 padding" src="img/products/rozmiary.png">
                                                </div>
                                            </div>
                                        </div>
                                        $other_colors
                                    </div>
                                </div>
                            </div>
END;
                    }
                    $result->close();
                }
                $connection->close();
            ?>
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