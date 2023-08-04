<?php
namespace App\Model;

use Sys\Model;

class MainConfig extends Model{

    protected $table = 'mainConfig';

    protected $fillable = ['id', 'mainColor', 'mainPage', 'createdAt', 'updatedAt'];

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
  
}
