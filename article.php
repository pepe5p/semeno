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
        include "articles/".$_GET['content'].".php";
    ?>
    <main>
        <article>
            <div class="longBar hBar">
                <header class="centerBox leftPad title"><?php echo $header ?></header>
            </div>
            <div class="centerBox horizontalPad">
                <?php echo $content ?>
            </div>
        </article>
    </main>
    <?php 
        include "php/footer.php";
    ?>
</body>
</html>