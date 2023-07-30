<?php
namespace App\Controller;
use App\Model\User;

class UserController 
{

    public function index()
    {

        $model = new User;
        $users = $model->all();

        $title = "list User";

        include dirname(__FILE__, 2).'\view\users\index.phtml';
    }

    public function show($id)
    {
        $model = new User;
        $user = $model->find($id);
        // Carregue a view de exibição do usuário passando os dados
        // Exemplo: include 'views/users/show.php';
    }

    public function add()
    {
        include dirname(__FILE__, 2).'\view\users\add.phtml';
        
    }
    public function edit($id)
    {
        $mod = new User();
        $user = $mod ->find($id);
        include dirname(__FILE__, 2).'\view\users\edit.phtml';
        
    }

    public function submitForm()
    {
        $user = new User();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user->nomeUsuario = "'".$_REQUEST['nomeUsuario']."'" ;
        $user->lastName = $_REQUEST['lastName'];
        $user->userType = $_REQUEST['role'];
        $user->password = "'".password_hash($_REQUEST['password'], PASSWORD_DEFAULT)."'";
        // var_dump($user);die();
        $data = $user->getData();
   
        $user->create($data);

        }
    }

    public function submitUpdate()
    {
        $user = new User();

        $user->nomeUsuario = "'".$_REQUEST['nomeUsuario']."'" ;
        $user->lastName = $_REQUEST['lastName'];
        $user->userType = $_REQUEST['role'];
        $user->password = "'".password_hash($_REQUEST['password'], PASSWORD_DEFAULT)."'";
        $data = $user->getData();
  
   
        $user->update($_REQUEST['idUsuario'],$data);
    }
    
    

    public function myProfile()
    {
        $model = new User;
        $user = $model->find($_SESSION['idUsuario']);
        include dirname(__FILE__, 2).'\view\users\profile.phtml';
    }


    public function delete($id)
    {
        // Aqui você pode implementar a lógica para excluir o usuário do banco de dados
        $userModel = new User();

        // Verifica se o usuário existe
        if (!$userModel->find($id)) {
            echo "Usuário não encontrado.";
            return;
        }

        // Realiza a exclusão no banco de dados
        $deleted = $userModel->delete($id);

        if ($deleted) {
            echo "Usuário excluído com sucesso!";
        } else {
            echo "Erro ao excluir usuário.";
        }
    }
}

    // Adicione outros métodos conforme necessário
