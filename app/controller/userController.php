<?php
namespace App\Controller;
use App\Model\User;
use Sys\Component\GridComponent;
class UserController 
{

    public function index()
    {                       
        $model = new User;
        $users = $model->all();
        $nameType = array('Administrador', 'Usuario', 'Visitante');
        foreach($users as $user)
        {
            $user->userType = $nameType[$user->userType];
            $user->updatedAt = formatDataPtBr($user->updatedAt);
        }
       GridComponent::render($model, $users, ['idUser'=>'id', 'userName'=>'Nome do Usuario', 'lastName'=>'Sobrenome do usuario', 'userType' => 'Tipo Usuario', 'updatedAt' => 'Ultima Atualização']);
    }

    public function show($id)
    {
        $model = new User;
        $user = $model->find($id);
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
        $user->userName = "'".$_REQUEST['userName']."'" ;
        $user->lastName = "'".$_REQUEST['lastName']."'";
        $user->userType = $_REQUEST['role'];
        $user->password = "'".password_hash($_REQUEST['password'], PASSWORD_DEFAULT)."'";

        $data = $user->getData();
   
        $user->create($data);

        }
    }

    public function submitUpdate()
    {
        $user = new User();

        $user->userName = "'".$_REQUEST['userName']."'" ;
        $user->lastName = $_REQUEST['lastName'];
        $user->userType = $_REQUEST['role'];
        $user->password = "'".password_hash($_REQUEST['password'], PASSWORD_DEFAULT)."'";
        
        $data = $user->getData();

   
        $user->update($_REQUEST['idUser'],$data);
    }
    
    

    public function myProfile()
    {
        $model = new User;
        $user = $model->find($_SESSION['idUser']);
        include dirname(__FILE__, 2).'\view\users\profile.phtml';
    }


    public function delete($id)
    {
        $userModel = new User();

        if (!$userModel->find($id)) {
            echo "Usuário não encontrado.";
            return;
        }

        $deleted = $userModel->delete($id);

        if ($deleted) {
            echo "Usuário excluído com sucesso!";
        } else {
            echo "Erro ao excluir usuário.";
        }
    }

    // public function handleUpload()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $targetDir = '/caminho/para/pasta/de/imagens/';
    //         $targetFile = $targetDir . basename($_FILES['image']['name']);
    //         $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    //         // Verifica se o arquivo é uma imagem
    //         if (isset($_POST['submit'])) {
    //             $check = getimagesize($_FILES['image']['tmp_name']);
    //             if ($check !== false) {
    //                 // Move o arquivo temporário para o diretório de destino
    //                 if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
    //                     echo "Imagem enviada com sucesso!";
    //                     // Salve o caminho da imagem no banco de dados usando a função setImagePath() do modelo User
    //                     $userModel = new User();
    //                     $userModel->setImagePath($targetFile);
    //                     // Resto do código para salvar o usuário no banco de dados
    //                 } else {
    //                     echo "Erro ao enviar a imagem.";
    //                 }
    //             } else {
    //                 echo "O arquivo não é uma imagem válida.";
    //             }
    //         }
    //     }
    // }

}

