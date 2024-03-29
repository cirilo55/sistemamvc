<?php

namespace App\Model;

use Sys\Model;
use App\Model\User;

class Task extends Model{

    protected $table = 'task';

    protected $fillable = ['id', 'taskName', 'limitDate','description', 'status', 'user_task_responsible', 'user_task_owner'];

    protected $id = 'id';

    protected $url = 'tarefas';

    private $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function joinTaskResposible()
    {
        return $this->innerJoin('users', 'id', 'user_task_responsible');
    }
    public function joinTaskOwner()
    {
        return $this->innerJoin('users', 'id', 'user_task_owner');
    }
}
