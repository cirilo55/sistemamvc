<!-- login.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="/sys/process_login.php" method="post">
        <label for="username">Usuário:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
