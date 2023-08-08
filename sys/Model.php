<?php

namespace Sys;

use PDO;
use PDOException;
use stdClass;
use Sys\Database;

class Model
{
    private $db;
    protected $table;
    protected $id;
    protected $tableColumns;
    protected $inner = [];
    protected $left = [];


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
        $stmt = $db->query("DELETE FROM {$this->table} WHERE {$this->id} = {$id}");

        return $stmt;
    }

    public function read($sql)
    {
        $db = new Database();
        $stmt = $db->query($sql);

        return $stmt;
    }

    public function allWithRelations(): array
    {
        $db = new Database();
        $joins = $this->getJoins();
    
        $query = "SELECT * FROM {$this->table}";
    
        $stmt = $db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        // Prepara um array para armazenar os dados das relações
        $relatedData = [];
        $final = array();

    
        // Associa os dados das relações aos objetos resultantes
        foreach($results as $result)
        {
   
            if($this->inner){
                foreach($this->inner as $in){
                $relatedData = $this->getRelatedData($db,$in['table'],$in['foreign'], $result->{$in['relatedColumn']});
                $result->{$in['table']} = $relatedData;
                array_push($final, $result);
                }
            }
        }
        return $final;
    }

    private function getJoins()
    {
        $joins = "";
        $columns = [];
        
        if ($this->inner) {
            foreach ($this->inner as $in) {
                $joins .= " INNER JOIN {$in['table']} ON {$this->table}.{$in['relatedColumn']} = {$in['table']}.{$in['foreign']}";
            }
        }
    
        if ($this->left) {
            foreach ($this->left as $in) {
                $joins .= " LEFT JOIN {$in['table']} ON {$this->table}.{$in['relatedColumn']} = {$in['table']}.{$in['foreign']}";
            }
        }
    
        return $joins;
    }
    
    /** 
     * This method add Relations to the model
     * 
     * @table: tabela da relação
     * @foreign: chave da tabela relação
     * @relatedColumn: chave do model
     * 
    **/
    protected function innerJoin(string $table, string $foreign, string $relatedColumn)
    {
        $this->inner[] = [
            'table' => $table,
            'foreign' => $foreign,
            'relatedColumn' => $relatedColumn
        ];
    }
    
    /** 
     * This method add Relations to the model
     * 
     * @table: tabela da relação
     * @foreign: chave da tabela relação
     * @relatedColumn: chave do model
     * 
    **/
    protected function LeftJoin(string $table,string $foreign,string $relatedColumn)
    {
        $this->left[] = [
            'table' => $table,
            'foreign' => $foreign,
            'relatedColumn' => $relatedColumn
        ];
    }
    
    /** 
    * Pra fazer os Inners e os joins.
    * SELECT * FROM $relatedTable WHERE $foreignColumn = {$relatedColumn}
    **/
    private function getRelatedData($db, $relatedTable, $foreignColumn, $relatedColumn): array
    {
        $query = "SELECT * FROM $relatedTable WHERE $foreignColumn = {$relatedColumn}";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    


}


