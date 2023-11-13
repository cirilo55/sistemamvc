<?php
namespace Sys;

use Sys\Component\ButtonComponent;
use Sys\Component\InputLabelComponent;

require_once 'component/ButtonComponent.php';
require_once 'component/InputLabelComponent.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Login</title>
</head>
<div class="login-screen">
    <div class="container-login">    

        <div class="login-label">
        <div class="title">Login</div>
        </div>
        <div class="line"></div>

        <form action="/sys/process_login.php" method="post">

            <?=InputLabelComponent::render('Usuario', 'text', 'username')?>

            <div>

            </div>
            <?=InputLabelComponent::render('Senha', 'password', 'password')?>


            <div class='submit'>
            <?=ButtonComponent::render('Entrar', 'btn-primary')?>
            </div>

            <div class="center">Esqueceu a Senha?</div>

            <div class='sign-with'>
                <img src="./../imgs/generic/png-transparent-google-logo-google-text-trademark-logo.png" height="50px" width="50px">
                <img src="./../imgs/generic/images.png" height="50px" width="50px">
                <img src="./../imgs/generic/apple.png" height="50px" width="50px">
            </div>
            </div>
            
        </form>

    <div class="extra-box">
    Precisa criar login? <div class="sign-in">Cadastre-se</div> 
    </div>

</div>

</html>
<script>
    
</script>
<style>

.sign-with {
    border: 1px solid black;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    width: 50%;
    margin-left: 25%;
    margin-top: 40px;
}
.sign-with img{
    margin: 5px;
    cursor: pointer;
}
.login-screen{
    position: absolute;
    top: 20%;
    left: 50%;
    transform: translate(-50%, 0);

}
.container-login{
    width: 40vh;
    height: 40vh;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 2px 2px 2px black;
    background-color: whitesmoke !important;

}
.login-label{
    display: flex;
    justify-content: center;
}
.extra-box{
    display: flex;
}
.sign-in{
    cursor: pointer;
    text-decoration: underline;

}

.line
{
    border: 1px solid black;
    width: calc(100% - 20px);
    margin-left: 10px;
    margin-bottom: 15px;
}
.title{
    font-size: 34px;
    margin: 10px;
    font-weight: bold;
}

</style>
