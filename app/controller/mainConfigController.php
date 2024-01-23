<?php
namespace App\Controller;
use App\Model\MainConfig;

class MainConfigController 
{

    public function index()
    {

        $model = new MainConfig;
        $config = $model->find(1);

        $title = "list User";
        
        var_dump($config);
        include dirname(__FILE__, 2).'\view\MainConfig\index.phtml';
    }

}