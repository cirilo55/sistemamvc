<?php
namespace App\Controller;
use App\Model\Task;
use App\Model\User;
use Sys\Component\GridComponent;

class TaskController 
{

    public function index()
    {                       
        $model = new Task();
        $tasks = $model->all();

        foreach($tasks as $task)
        {
            $task->limitDate = formatDataPtBr($task->limitDate);
        }
        
        GridComponent::render($model, $tasks, ['id'=>'id', 'taskName'=>'Nome do tarefa', 'limitDate'=>'Data limite', 'status' => 'Status']);
    }

    public function add()
    {
        $model = new User();
        $users = $model->all();
        $users = convertArrayOfObjectToArray($users,'id', 'userName');
        include dirname(__FILE__, 2).'\view\task\add.phtml';
        
    }
    public function submitForm()
    {
        $task = new Task();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $task->taskName = inputFormat($_REQUEST['taskName']);
        $task->limitDate = inputFormat($_REQUEST['dateTask']);
        $task->description = inputFormat($_REQUEST['description']);
        $task->user_task_owner = $_SESSION['id'];
        $task->user_task_responsible = $_REQUEST['user_task_responsible'];
        $data = $task->getData();
        
        $task->create($data);

        }
    }

}
?>
