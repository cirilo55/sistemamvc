<?php
namespace App\Controller;
use App\Model\Task;
use Sys\Component\GridComponent;

class TaskController 
{

    public function index()
    {                       
        $model = new Task();
        $tasks = $model->all();

        GridComponent::render($model, $tasks, ['id'=>'id', 'taskName'=>'Nome do tarefa', 'limitDate'=>'Data limite', 'status' => 'Status']);
    }

    public function add()
    {
        include dirname(__FILE__, 2).'\view\task\add.phtml';
        
    }
    public function submitForm()
    {
        $task = new Task();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $task->taskName = "'".$_REQUEST['taskName']."'" ;
        $task->limitDate = "'".$_REQUEST['dateTask']."'";
        $task->description = "'".$_REQUEST['description']."'";
        $task->user_task_owner = $_SESSION['idUser'];
        $task->user_task_responsible = $_REQUEST['user_task_responsible'];
        $data = $task->getData();
        
        $task->create($data);

        }
    }

}
?>