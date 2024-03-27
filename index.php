
<?php
setlocale(LC_TIME, 'pt_BR.utf8');
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
<section style="    height: 88%;">
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

       <div class='right-stage'>
            <?php
                include './app/view/Generic/notifications.php';
            ?>
        </div>

    </div>

            <!-- Recebe os Modais -->
            <div id="overlay-sm" class="overlay">
            <div class='stage-popup-sm'>

                <div class="popuphead"><?=$title ? $title : '';?>
                <div class='title-popup-sm'></div>
                <button id="close-overlay-sm" onclick="closeLevel2()">X</button>
                </div>

                <div id='main-stage-2-sm'>
                        
                </div>
            </div>
            </div>

            <div id="overlay" class="overlay">
            <div class='stage-popup'>

                <div class="popuphead">
                <div>POPUP</div>
                <button id="close-overlay" onclick="closeLevel2()">X</button>
                </div>

                <div id='main-stage-2'>
                        
                </div>
            </div>
            </div>

            <div id="overlay-lg" class="overlay">
            <div class='stage-popup-lg'>

                <div class="popuphead">
                <div>POPUP</div>
                <button id="close-overlay-lg" onclick="closeLevel2()">X</button>
                </div>

                <div id='main-stage-2-lg'>
                        
                </div>
            </div>
            </div>
            
         <div>
</section>
            <?php
            include 'footer.php';

            ?>

<?php } }else{?>
        <?php
            include 'sys/login.php';
            echo "</section>"

        ?>
    
<?php }?>
<?php
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
        z-index: 0;


    }
    .right-stage{
        width: 15%;
        height: 100%;
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

    .stage-popup-sm{
        position: absolute;
        top: 50%;
        left: 50%;
        height: 66%;
        width: 30%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);

    }

</style>
<script>
    function openLevel2(response, size='md')
    {
        if(size==='sm')
        {
        $('#overlay-sm').show();
        $('#main-stage-2-sm').append(response)
        }
        if(size==='lg')
        {
        $('#overlay-lg').show();
        $('#main-stage-2-lg').append(response)
        }
        if(size==='md'){
        $('#overlay').show();
        $('#main-stage-2').append(response)
        }
    }
    function closeLevel2()
    {       
        $('.overlay').hide();
        $('#main-stage-2').empty();
        $('.overlay-sm').hide();
        $('#main-stage-2-sm').empty()
        $('.overlay-lg').hide();
        $('#main-stage-2-lg').empty()
    }
</script>