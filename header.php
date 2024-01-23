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

    <script src="vendor/components/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>
<header class="nav">
    <div class="circle-hover-menu">
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

   
    <div class="center" onclick="toggleTasks()" style="margin: 0 0.5rem;">
        <img src="./imgs/generic/bell.png" alt="" height="35" width="35">
    </div>

    <div class="profile">
        <div class="center" style="margin: 0 0.5rem;">
            <div class="profile-circle">
             <img src="./imgs/profile/noProfile.png" alt="" height="35" width="35">
            </div>
        </div>
        <div class='profile-options'>
        <a href="/myprofile">
            <div>
                <?=$_SESSION['userName'] .' '.$_SESSION['lastName']?>
            </div> 
        </a>
        <a href="/SignOut">
            <div>
                Sign/Out
            </div>
        </a>
        </div>
    </div>
    </div>
    <?php }?>
    </div>

</header>
</div>
<style>
    .profile{
        display: flex;
    }
</style>
