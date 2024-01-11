<?php
    
    $title = false;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=($title? $title :'Titulo')?></title>
    <!-- Incluindo o arquivo "global.css" -->   
    <script src="main.js"></script>
    <link rel="stylesheet" href="/libs/bootstrap-4.5.3-dist/css/bootstrap.min.css">

    <script src="main.js"></script>        
    <!-- Include Jquery CSS from node_modules -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Include Bootstrap CSS from node_modules -->
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<header class="nav">

<div class=''>
    <div class="hamburguer center" onclick="toggleMenu()">
        <div id="bar1" class="bar"></div>
        <div id="bar2" class="bar"></div>
        <div id="bar3" class="bar"></div>
    </div>
</div>

<div class="session">

    <?php if(empty($_SESSION)){?>
        <div class="sign-in">
            <div>
                No Login / 
            </div>
            <a href="/login">
                <div>
                    Sign/In
                </div>
            </a>
        </div>

    <?php }else{?>

    <div class="center" onclick="toggleTasks()">
        <img src="./imgs/generic/bell.png" alt="" height="35" width="35">
    </div>

    <div class="profile">
        <div class="center">
            <div class="profile-circle">
             <img src="./imgs/profile/noProfile.png" alt="" height="35" width="35">
            </div>
        </div>
        <div style="display: flex; justify-content:center">
        <a href="/myprofile">
            <div>
                <?=$_SESSION['userName'] .' '.$_SESSION['lastName']?>
            </div> 
        </a>
        <div> | </div>
        <a href="/SignOut">
            <div>
                Sign/Out
            </div>
        </a>
        </div>
    </div>

    <?php }?>
    </div>

</header>
</div>
