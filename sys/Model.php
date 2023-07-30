<?php

namespace Sys;

use PDO;
use PDOException;
use Sys\Database;

class Model
{
    private $db;
    protected $table;
    protected $id;
    protected $tableColumns;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function all()
    {
        $db = new Database();
        $query = "SELECT * FROM {$this->table}";
        $stmt = $db->query($query, false);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id)
    {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM {$this->table} WHERE {$this->id} = {$id}", false);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        $db = new Database();

        $keys = array_keys($data);
        $values = array_values($data);
        $values = implode(',',$values);

        $sql = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES ($values)";

        $stmt = $db->query($sql);

        $db->saveQuery($sql);
        return false;
    }

    public function update($id, $data)
    {
        $db = new Database();

        $sets = [];

        foreach ($data as $key => $value) {
            $sets[] = "$key = $value";
        }

        $sql = "UPDATE $this->table SET " . implode(',', $sets) . " WHERE {$this->id}  = {$id}";
        $stmt = $db->query($sql);

        $db->saveQuery($sql);
        return $stmt;
    }

    public function delete($id)
    {
        $db = new Database();
        $stmt = $db->query("DELETE FROM $this->table WHERE {$this->id} = {$id}");

        return $stmt;
    }


}


