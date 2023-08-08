
<?php
require_once 'vendor/autoload.php';
define('CSS_PATH', 'global.css');
session_start();

if($_SESSION){
include 'header.php';
?>
<body>
<section>

        <div class='palco'>
            <?php
            include 'route.php';
            ?>
        </div>
    <?php
        include 'menu.php';
    ?>

</section>

<?php }else{?>
        <?php
            include 'login.php';
        ?>
    
<?php }?>

    <?php
        include 'footer.php';
    ?>

</body>
<style>
    .palco{
        position: absolute;
        left: 15%;
        margin:5px;
        background-color: whitesmoke;
        border: 1px dotted ;
        height: 88%;
        width: 70%;
    }
    section {
        height: 90%;
    }
    .footer-container{
        z-index: -2;
        position: absolute;
    }
</style>