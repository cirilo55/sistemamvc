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
    protected $url;
    protected $timestamps;

    public function __construct()
    {
        $this->db = new Database();
    }


    /** 
     * Retorna todos os campos do Model
     * order by e seach by com os nomes do GridComponent;
     * SELECT * FROM {$this->table}{$orderBy}
     * 
     * @orderby: default 'default_column', 
     * 
    **/
    public function all($pagination=0)
    {
        $orderBy = isset($_GET['orderby']) ? $orderBy = $_GET['orderby'] : NULL;
        $orderType = isset($_GET['order-type']) ? $searchField = $_GET['order-type'] : NULL;

        $searchBy = isset($_GET['grid-search-input']) ? $searchBy = $_GET['grid-search-input'] : NULL;
        $searchField = isset($_GET['grid-combo']) ? $searchField = $_GET['grid-combo'] : NULL;

        $order = '';
        $search = '';
        $limit = '';
        if($pagination)
        {
            $limit = "LIMIT {$pagination}";
        }

        if($searchField)
        {
            $search .=  "WHERE {$searchField} LIKE '{$searchBy}'";
        }

        if($orderBy)
        {
           $order .= "ORDER BY {$orderBy}";
           if($orderType == 'desc')
           {
             $order .= ' DESC';
           }
           if($orderType == 'asc')
           {
            $order .= ' ASC';
           }
        }

        // var_dump($this->timestamps);die();

        $db = new Database();
        $query = "SELECT * FROM {$this->table} {$search} {$order} {$limit}";
        // var_dump($query);die();

        $stmt = $db->query($query, false);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findOld($id)
    {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM {$this->table} WHERE {$this->id} = {$id}", false);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function find($id)
    {
        $db = new Database();
        $params = [':id' => $id];
        $stmt = $db->prepareAndExecute("SELECT * FROM {$this->table} WHERE {$this->id} = :id", $params);
        // var_dump($stmt);die();
        $r =  $stmt->fetchAll(PDO::FETCH_OBJ);
        return $r;
        
    }

    public function findByField(string $field,$id)
    {
        $db = new Database();
        $params = [':id' => $id];
        $stmt = $db->prepareAndExecute("SELECT * FROM {$this->table} WHERE $field = :id", $params);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }


    public function create($data)
    {
        $db = new Database();
        $this->gererateTimestamp();

        $data['createdAt'] = "'" . date('Y-m-d H:i:s') . "'";

        $keys = array_keys($data);
        $values = array_values($data);
        $values = implode(',',$values);
        $sql = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES ($values)";
        // var_dump($sql);die();
        $stmt = $db->query($sql);

        $db->saveQuery($sql);
        return false;
    }

    public function update($id, $data)
    {
        $db = new Database();
        $this->gererateTimestamp();
        // Adicionar o campo UpdatedAt e seu valor
        $data['updatedAt'] =  date('Y-m-d H:i:s') ;

        $sets = [];
        
        foreach ($data as $key => $value) {
            // Check if the value is a string, and if so, enclose it in single quotes
            $value = is_string($value) ? "'" . $value . "'" : $value;
            $sets[] = "$key = $value";
        }

        $sql = "UPDATE $this->table SET " . implode(',', $sets) . " WHERE {$this->id} = {$id}";
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
        // var_dump($query);die();
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

    public function getTotalRecords(): int
    {
        $db = new Database();
        $stmt = $db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'];
    }
    public function getUrl()
    {
        return $this->url;
    }

    public function gererateTimestamp()
    {
        $db = new Database();

        $sql = "SHOW COLUMNS FROM {$this->table} LIKE 'createdAt'";
        $result = $db->query($sql);

        if ($result->rowCount() === 0) {
            $alterSql = "ALTER TABLE {$this->table} ADD createdAt DATETIME";
            $db->query($alterSql);
        }

        $sql = "SHOW COLUMNS FROM {$this->table} LIKE 'updatedAt'";
        $result = $db->query($sql);

        if ($result->rowCount() === 0) {
            $alterSql = "ALTER TABLE {$this->table} ADD createdAt DATETIME";
            $db->query($alterSql);
        }
    }


    


}


