<?php
namespace App\Model;

use Sys\Model;

class Menu extends Model{

    protected $table = 'systemModule';

    protected $fillable = ['id', 'moduleName', 'order'];

    protected $id = 'id';

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
  
    public function joinModuleItems()
    {
        return $this->innerJoin('moduleItem', 'idModulo', $this->id);
    }
}
