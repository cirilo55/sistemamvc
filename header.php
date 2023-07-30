<?php
    define('CSS_PATH', 'global.css');
    session_start();
    $title = false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title><?=($title? $title :'Titulo')?></title>
    <!-- Incluindo o arquivo "global.css" -->
    <link rel="stylesheet" href="/global.css">
    <link rel="stylesheet" href="/components.css">
    <link rel="stylesheet" href="/libs/bootstrap-4.5.3-dist/css/bootstrap.min.css">
    <script src="/libs/bootstrap-4.5.3-dist/js/bootstrap.bundle.js"></script>

</head>
<body>
<div class="nav">
<?php if(empty($_SESSION)){?>

    <a href="/login">
        <div>
            Sign/In
        </div>
    </a>


<?php }else{?>

    <a href="/myprofile">
        <div>
            <?=$_SESSION['nomeUsuario'].'  '?>
        </div> 
    </a>

    <a href="/SignOut">
        <div>
            Sign/Out
        </div>
    </a>

<?php }?>
</div>
</body>
<style>
    .nav{
        color: white;
    }
</style>
