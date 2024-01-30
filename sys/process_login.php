<?php
// process_login.php
namespace Sys;
use PDO;
use PDOException;
use Sys\Database;
require_once 'Database.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar os dados do formulário

    $username = $_POST['username'];
    $password = $_POST['password'];
    // Verificar as credenciais (supondo que você tenha uma tabela "users" no banco de dados)
    $db = new Database();   
    $pdo = $db->connect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE userName = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $b = password_verify($password, $user['password']);
  
    // $stmt->execute(['username' => $username]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // var_dump($hashedPassword);die();

    if ($user && password_verify($password, $user['password'])) {
        // Credenciais corretas, fazer a autenticação do usuário
        session_start();

        $_SESSION['id'] = $user['id']; // Por exemplo, salve o ID do usuário na sessão
        $_SESSION['password'] = $user['password'];
        $_SESSION['userName'] = $user['userName']; // Por exemplo, salve o ID do usuário na sessão
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['lastName'] = $user['lastName'];
        $_SESSION['imagePath'] = $user['imagePath'];
        $_SESSION['profile'] = $user['imagePath'];

        // var_dump($_SESSION);die();
        // Redirecionar para a página de boas-vindas ou outra página restrita
        header('Location: ../');
        exit();
    } else {
        // Credenciais inválidas, redirecionar para a página de login novamente
        die('errou');
        header('Location: ../');
        exit();
    }
}
?>
