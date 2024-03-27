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
    <link rel="stylesheet" href="vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="icon" href="./imgs/generic/apple.png">
</head>
<header class="nav">
    <div class='center' style="width: 10%; height:100%">
    <div class="circle-hover-menu">
    <div class="hamburguer center" onclick="toggleMenu()">
        <div id="bar1" class="bar"></div>
        <div id="bar2" class="bar"></div>
        <div id="bar3" class="bar"></div>
    </div>
    </div>
    </div>

    <div style="width: 70%; height:100% ">

    </div>

    <div class="nav-session" style="width: 20%; height:100%">

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

   
    <div class="center" onclick="toggleTasks()" style="width:20%">
        <div class="bi bi-bell-fill"></div>
    </div>

    <div class="profile">
        <div class="center">
            <div class="profile-circle">
             <?php $profile = $_SESSION['profile'] ? $_SESSION['profile'] :'noProfile.png' ;?> 
             <img class='header-profile-img'src="./imgs/profile/<?=$profile?>" alt="" height="35" width="35">
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
        align-items: center;
    }
    .profile-circle{
        width: 36px;
        height: 36px;
    }
    .header-profile-img{
        border-radius: 50%;
    }

    @media (min-width: 1300px) {
    .nav{
        font-size: x-small;;
    }
    }

    @media (min-width: 1600px) {
    .nav{
        font-size: medium;
    }
    }
    
</style>
