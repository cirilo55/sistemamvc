<?php
// process_login.php
namespace Sys;
require_once 'Database.php';
use PDO;
use PDOException;
use Sys\Database;

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar as credenciais (supondo que você tenha uma tabela "users" no banco de dados)
    $db = new Database();   
    $pdo = $db->connect();
    $stmt = $pdo->query("SELECT * FROM users WHERE userName = '{$username}'");
    $user = $stmt->fetch();

    // $stmt->execute(['username' => $username]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Credenciais corretas, fazer a autenticação do usuário
        session_start();
        $_SESSION['idUser'] = $user['idUser']; // Por exemplo, salve o ID do usuário na sessão
        $_SESSION['password'] = $user['password'];
        $_SESSION['userName'] = $user['userName']; // Por exemplo, salve o ID do usuário na sessão
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['lastName'] = $user['lastName'];
        // var_dump($_SESSION);die();
        // Redirecionar para a página de boas-vindas ou outra página restrita
        header('Location: ../');
        exit();
    } else {
        // Credenciais inválidas, redirecionar para a página de login novamente
        header('Location: ../');
        exit();
    }
}
?>
