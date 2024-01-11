
<?php
require_once 'vendor/autoload.php';
include './sys/helpers.php';

define('CSS_PATH', 'global.css');
session_start();
if($_SESSION){
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    include 'route.php';
    exit();
}else{
include 'header.php';
?>
<body>
<section>
        <div id='main-stage' class='main-stage'>
            <?php
            include 'route.php';
            ?>
        </div>

    <?php
        include './app/view/Generic/menu.php';
    ?>

    <?php
        include './app/view/Generic/tasks.php';

    ?>

                <!-- Recebe os Modais -->
        <div id="overlay" class="overlay">
            <div class='stage-popup'>

                <div class="popuphead">
                <div>POPUP</div>
                <button id="close-overlay" onclick="closeLevel2()">X</button>
                </div>

                <div id='main-stage-2'>
                        
                </div>
            </div>
         <div>

<?php } }else{?>
        <?php
            include 'sys/login.php';
        ?>
    
<?php }?>
</section>
<?php
        include 'footer.php';
?>
</body>
<link rel="stylesheet" href="/global.css">

<style>
    .main-stage{
        position: absolute;
        left: 15%;
        background-color: whitesmoke;
        border: 1px dotted;
        height: inherit;
        width: 70%;

    }
 
    .main-stage-2{
        z-index: 0;
    }
    section {
        height: 90%;
    }
    .footer-container{
        z-index: -2;
        position: absolute;
    }
    .popuphead{
        display: flex;
        border-bottom: 1px solid;
    }
    #close-overlay{
        margin-left: auto;
    }
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
    }
    .stage-popup{
        position: absolute;
        top: 50%;
        left: 50%;
        height: 70%;
        width: 80%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);

    }
</style>
<script>
    require('bootstrap');
    function openLevel2(response)
    {
        $('.overlay').show();
        $('#main-stage-2').append(response); 
    }
    function closeLevel2()
    {       
         $('.overlay').hide();
         $('#main-stage-2').empty();

    }
</script>