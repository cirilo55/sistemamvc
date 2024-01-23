<?php

namespace App\Model;

use Sys\Model;

class User  extends Model{

    protected $table = 'users';

    protected $fillable = ['id', 'userName', 'lastName', 'userType', 'password'];

    protected $id = 'id';

    protected $url = 'users';

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
