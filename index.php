
<?php
ob_start();
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
        <!-- <div style='height:100%; width:100%;place-items:center'> -->
    <div class="section-stage">
        <div class='left-stage'>

        <?php
            include './app/view/Generic/menu.php';
        ?>
        </div>

        <div id='main-stage' class='main-stage'>
       
        <div class='grid-stage'>
            <?php
            include 'route.php';
            ?>
        </div>
        </div>

       <?php
            include './app/view/Generic/tasks.php';
        ?>

    </div>
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
        ob_end_flush();
?>
</body>
<link rel="stylesheet" href="/global.css">

<style>
    section{
        height: 100%;
    }

    .section-stage{
        width: 100%;
        height: 100%;
        display: flex;
        flex-wrap: wrap;

    }
    .left-stage{
        width: 15%;
    }
    .main-stage{
        height: inherit;
        width: 70%;

    }
    .grid-stage{
        background-color: whitesmoke;
        height: 96%;
        width: 98%;
        margin: 1%;
        border: 1px dotted;

    }

    .main-stage-2{
        z-index: 0;
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