
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Login</title>
</head>
<div class="login-screen">
    <div class="container-login">    
        <h1>Login</h1>
        <form action="/sys/process_login.php" method="post">

            <div class="input-label-component">
            <div>
            <label for="username">Usuário</label>
            </div>
            <input type="text" name="username" id="username" required>
            </div>
          
            <div>

            </div>
            <div class="input-label-component">
            <div class="label">
            <label for="password">Senha</label>
            </div>
            <input type="password" name="password" id="password" required>
            </div>

            <div class='submit'>
            <input type="submit" value="Entrar">
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
}
.container-login h1{
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

/* css do input-label-component */
.input-label-component{
    border: 1px solid black; border-radius: 1px;
    font-size:18px;
    text-shadow:1px 0px 0px rgba(42,42,42,.49); 
    padding: 3px;
   }

.input-label-component input{
    border: 0 !important;
    text-shadow:1px 0px 0px rgba(42,42,42,.49); 
     font-family:sans-serif; border-color:#fafafa;
     font-size: 14px
}
.input-label-component label
{
    font-size: 10px;
    font-family:sans-serif; 
}
.submit{
    display: flex;
    flex-direction: row-reverse;
    
}
.input-label-component input:focus { outline:none; } 

</style>
