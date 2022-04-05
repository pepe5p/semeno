<nav class="fixedNav logoNav">
    <header><a href="home.php" class="title">SEMENOSTORE</a></header>
</nav>
<nav class="fixedNav topNav">
    <div class="container-fluid">
        <div class="row">
            <div class="navTile">
                <a href="profile.php"><i class="icon-user"></i></a>
            </div>
            <div class="navTile cart">
                <a href="cart.php"><i class="icon-basket"></i></a>
                <div class="productsNumber">
                    <?php
                        require_once "connect.php";
                        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
                        if($connection->connect_errno == 0)
                        {
                            if(!isset($_SESSION['logged'])) session_start();
                            if(!isset($_SESSION['user_id'])) echo "0";
                            else{
                                $user_id = $_SESSION['user_id'];
                                $cart_sql = "SELECT order_id, quantity FROM orders WHERE user_id = $user_id AND order_status = 0";
                                if($cart_result = @$connection->query($cart_sql))
                                {
                                    $orders_nr = mysqli_num_rows($cart_result);
                                    if($orders_nr > 0)
                                    {
                                        $all_nr = 0;
                                        for($i = 1; $i <= $orders_nr; $i++) $all_nr += mysqli_fetch_assoc($cart_result)['quantity'];
                                        echo $all_nr;
                                    } 
                                    else echo "0";
                                }
                            }
                        }
                        $connection->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="titleScreen"></div>