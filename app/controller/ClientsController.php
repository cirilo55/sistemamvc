<?php
namespace App\Controller;
use App\Model\User;
use App\Model\Clients;

class ClientsController 
{

    public function index()
    {                       

        $model = new Clients;
        $clients = $model->all();

        $title = "list User";
        include dirname(__FILE__, 2).'\view\Client\index.phtml';
    }



}

