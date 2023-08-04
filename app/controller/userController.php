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
        $user->imagePath = $_REQUEST['image'];

        $image_file = $_FILES["image"];
        var_dump(dirname(__FILE__, 3) . "\imgs\profile" . $_REQUEST['image']);die();

        move_uploaded_file(
            // Temp image location
            $image_file["tmp_name"],
        
            // New image location, __DIR__ is the location of the current PHP file
            __DIR__ . "/imgs/profile" . $image_file["name"]
        );
        
        
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

