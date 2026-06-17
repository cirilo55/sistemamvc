<?php

namespace Sys;

use PDO;
use Sys\Database;

class Model
{
    private $db;
    private static $columnsCache = [];
    protected $table;
    protected $id;
    protected $tableColumns;
    protected $fillable = [];
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
        $db = new Database();
        $params = [];
        $search = '';
        $order = '';
        $limit = '';

        $orderBy = $_GET['orderby'] ?? null;
        $orderType = strtolower($_GET['order-type'] ?? '');
        $searchBy = $_GET['grid-search-input'] ?? null;
        $searchField = $_GET['grid-combo'] ?? null;

        if ($searchField && $searchBy !== null && $searchBy !== '' && $this->isAllowedColumn($db, $searchField)) {
            $search = "WHERE {$searchField} LIKE :search";
            $params[':search'] = '%' . $searchBy . '%';
        }

        if ($orderBy && $this->isAllowedColumn($db, $orderBy)) {
            $direction = $orderType === 'desc' ? 'DESC' : 'ASC';
            $order = "ORDER BY {$orderBy} {$direction}";
        }

        if ($pagination) {
            $limitValue = max(1, (int) $pagination);
            $limit = "LIMIT {$limitValue}";
        }

        $query = "SELECT * FROM {$this->table} {$search} {$order} {$limit}";

        $stmt = $db->prepareAndExecute($query, $params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findOld($id)
    {
        $db = new Database();
        $stmt = $db->prepareAndExecute("SELECT * FROM {$this->table} WHERE {$this->id} = :id", [':id' => $id]);
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
        if (!$this->isAllowedColumn($db, $field)) {
            return [];
        }

        $params = [':id' => $id];
        $stmt = $db->prepareAndExecute("SELECT * FROM {$this->table} WHERE $field = :id", $params);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }


    public function create($data)
    {
        $db = new Database();
        $data = $this->filterDataForTable($db, $data);

        if ($this->isAllowedColumn($db, 'createdAt') && !isset($data['createdAt'])) {
            $data['createdAt'] = date('Y-m-d H:i:s');
        }

        if ($this->isAllowedColumn($db, 'updatedAt') && !isset($data['updatedAt'])) {
            $data['updatedAt'] = date('Y-m-d H:i:s');
        }

        if (!$data) {
            return false;
        }

        $keys = array_keys($data);
        $placeholders = array_map(fn($key) => ':' . $key, $keys);
        $params = [];

        foreach ($data as $key => $value) {
            $params[':' . $key] = $value;
        }

        $sql = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $db->prepareAndExecute($sql, $params);

        $db->saveQuery($sql);
        return $stmt;
    }

    public function update($id, $data)
    {
        $db = new Database();
        $data = $this->filterDataForTable($db, $data);

        if ($this->isAllowedColumn($db, 'updatedAt')) {
            $data['updatedAt'] = date('Y-m-d H:i:s');
        }

        if (!$data) {
            return false;
        }

        $sets = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $placeholder = ':' . $key;
            $sets[] = "$key = $placeholder";
            $params[$placeholder] = $value;
        }

        $sql = "UPDATE $this->table SET " . implode(',', $sets) . " WHERE {$this->id} = :id";
        $stmt = $db->prepareAndExecute($sql, $params);
        $db->saveQuery($sql);
        
        return $stmt;
    }

    // public function update2($id, $data)
    // {
    //     $db = new Database();
    //     $this->gererateTimestamp();
    //     // Adicionar o campo UpdatedAt e seu valor
    //     $data['updatedAt'] =  date('Y-m-d H:i:s') ;

    //     $sets = [];
    //     $params = [];
    //     // var_dump($data);die();
    //     foreach ($data as $key => $value) {
    //         // Check if the value is a string, and if so, enclose it in single quotes
    //         $value = is_string($value) ? "'" . $value . "'" : $value;
    //         $sets[] = "$key = $value";
    //     }

    //     $sql = "UPDATE $this->table SET " . implode(',', $sets) . " WHERE {$this->id} = {$id}";

    //     $stmt = $db->prepareAndExecute($sql, $params);
    //     $db->saveQuery($sql);
        
    //     return $stmt;
    // }

    public function delete($id)
    {
        $db = new Database();
        $stmt = $db->prepareAndExecute("DELETE FROM {$this->table} WHERE {$this->id} = :id", [':id' => $id]);

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
        $query = "SELECT * FROM $relatedTable WHERE $foreignColumn = :relatedColumn";
        $stmt = $db->prepareAndExecute($query, [':relatedColumn' => $relatedColumn]);
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
        return null;
    }

    private function filterDataForTable(Database $db, array $data): array
    {
        $allowedColumns = $this->fillable ?: $this->getAllowedColumns($db);
        $filtered = [];

        foreach ($data as $key => $value) {
            if ($key === $this->id && ($value === null || $value === '')) {
                continue;
            }

            if (in_array($key, $allowedColumns, true)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    private function isAllowedColumn(Database $db, string $column): bool
    {
        return in_array($column, $this->getAllowedColumns($db), true);
    }

    private function getAllowedColumns(Database $db): array
    {
        if (!isset(self::$columnsCache[$this->table])) {
            $stmt = $db->query("SHOW COLUMNS FROM {$this->table}");
            $columns = [];

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $column) {
                $columns[] = $column['Field'];
            }

            self::$columnsCache[$this->table] = $columns;
        }

        return self::$columnsCache[$this->table];
    }


    


}


