<?php
namespace App\Controller;
use App\Model\User;
use App\Model\Clients;
use Sys\Component\GridComponent;

class ClientsController 
{

    public function index()
    {                       

        $model = new Clients;
        $clients = $model->all();

        $title = "list User";
        GridComponent::render($model, $clients, ['id' => 'id', 'clientName' => 'Nome do Cliente' ]);

        // include dirname(__FILE__, 2).'\view\Client\index.phtml';
    }



}

