<?php 
namespace Sys;
use Sys\Model;


class Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function index()
    {
        $results = $this->model->all();
        return $results;

    }

    public function show($id)
    {
        $result = $this->model->find($id);
        return $result;

    }

    public function store($data)
    {
        $result = $this->model->create($data);
        return $result;

    }

    public function update($id, $data)
    {
        $result = $this->model->update($id, $data);
        return $result;

    }

    public function delete($id)
    {
        $result = $this->model->delete($id);
        return $result;

    }
}
