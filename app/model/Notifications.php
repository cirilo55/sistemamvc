<?php

namespace App\Model;

use Sys\Model;

class Notifications  extends Model{

    protected $table = 'notifications';

    protected $fillable = ['id', 'title', 'title', 'userType', 'user_notification'];

    protected $id = 'id';

    // protected $url = '';

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

    public function joinUserNotification()
    {
        return $this->innerJoin('users', 'id', 'user_notification');
    }
  
}
