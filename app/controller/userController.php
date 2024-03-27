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
       GridComponent::render($model, $users, ['id'=>'id', 'userName'=>'Nome do Usuario', 'lastName'=>'Sobrenome do usuario', 'userType' => 'Tipo Usuario', 'updatedAt' => 'Ultima Atualização'], true, false,false);
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
        $user = $mod ->findOld($id);
        include dirname(__FILE__, 2).'\view\users\edit.phtml';
        
    }

    public function submitForm()
    {
        $user = new User();
        $user->userName = inputFormat($_POST['userName']) ;
        $user->lastName = inputFormat($_POST['lastName']);
        $user->userType = $_POST['role'];

        $data = $user->getData();

        if($_REQUEST['id']){
            $user->update($_REQUEST['id'], $data);
        }else{
            $user->create($data);
        }
    
    }

    public function submitUpdate()
    {
        $user = new User();
        $user->id = $_POST['id'];
        $user->userName = inputFormat($_POST['userName']) ;
        $user->lastName = inputFormat($_POST['lastName']);
        $user->userType = $_POST['role'];

        $data = $user->getData();
        $return = $user->update($_POST['id'], $data);

        if($return)
        {
            $_SESSION['userName'] = $data['userName'];
            $_SESSION['lastName'] = $data['lastName'];
            
        }

    }
    public function submitProfile()
    {
        $user = new User();
        $fileInputName = 'fileToUpload';  // Adjust based on your form input name
        $uploadDir = dirname(__DIR__, 2). DIRECTORY_SEPARATOR . "imgs" . DIRECTORY_SEPARATOR . "profile".DIRECTORY_SEPARATOR; 
        $oldProfilePath = null;
        if($_SESSION['profile'])
        {
            $oldProfilePath= $uploadDir.$_SESSION['profile'];
        }
        $upload = handleFileUpload($fileInputName, $uploadDir, $oldProfilePath);
        if (strpos($upload, "File uploaded successfully") !== false) {
            $data = $user->getData();
            $user->update($_SESSION['id'], $data);
    
            $_SESSION['profile'] = basename($_FILES[$fileInputName]['name']);
        }
        // header();
    }
    
    

    public function myProfile()
    {
        $model = new User;
        $user = $model->findOld($_SESSION['id']);
        $nameType = array('Administrador', 'Usuario', 'Visitante');
        $user->userType = $nameType[$user->userType];

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

}

