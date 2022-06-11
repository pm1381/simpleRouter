<?php

namespace Bramus\Core;

use Bramus\Model\DisplayFormat;

use function PHPSTORM_META\type;

class Generate extends Database
{
    private $table;
    private $row = [];
    private $where = [];
    private $limit = 0;
    private $order = [];
    // join, group
    // and complicated queries;

    public function __construct($table)
    {
        $this->connect();
        $this->table = $table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function row($row)
    {
        $this->row = $row;
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    public function generateInsertRows($data)
    {
        $keys = [];
        foreach($data as $key=>$value) {
            $keys[] = $key;
        }
        $data = '(';
        $i = 1;
        foreach ($keys as $col) {
            $data .= '`' .  $col . '`' ;
            if ($i < count($keys)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }

    public function insert($data)
    {
        $cols = $this->generateInsertRows($data);
        $bind = $this->generateBinds($data);
        $query = self::$connection->prepare("INSERT INTO " . '`' . $this->table . '`' . $cols ." VALUES " . $bind);
        $this->insertBindParams($query, $data);
        try {
            $query->execute();
            DisplayFormat::jsonFormat(true, 'inserted successfully');
        } catch (\PDOException $e) {
            http_response_code(400);
            DisplayFormat::jsonFormat(false, 'an error occured in inserting to db' . $e->getMessage());
        }
    }

    private function insertBindParams($query, $data)
    {
        foreach ($data as $col => $value){
            $column = ":".$col;
            $query->bindParam($column, $value);
        }
    }

    private function generateBinds($row)
    {
        $data = '(';
        $i = 1;
        foreach ($row as $col => $value) {
            $data .= ":" .  $col ;
            if ($i < count($row)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }

    public function direct($query)
    {
        
    }

    private function generateRows($row)
    {
        if (count($row) > 0) {
            //$data = '(';
            $data = '';
            $i = 1;
            foreach ($row as $col) {
                $data .= '`' .  $col . '`' ;
                if ($i < count($row)) {
                    $data .= ", ";
                }
                $i++;
            }
            // $x = implode(", ", $row);
            // $data .= $x;
            //$data .= ')';
            return $data;
        }
        return '*';
    }

    private function generateWhere($where)
    {
        if ($where == "") {
            return '';
        }
        if (count($where) == 0) {
            return '';
        }
        $data = ' WHERE ';
        foreach ($where as $key => $value) {
            if (is_array($value) && type($key) == 'int' ) {
                // [], ke yani har chi dakhelesh hast ba ham or beshan
                $data .= '(';
                $data .= ')';
            } else {
                if (is_array($value)) {
                    // 'id' => []
                    $data .= '(';
                    if (preg_match('~.* !~isU', $key)) {
                        // yani != dar where;
                    } else {
                        // yani == dar where;
                    }
                    $data .= ') AND ';
                } else {
                    // 'id' => 'pm'
                    $data .= '(';
                    if (preg_match('~.* !~isU', $key)) {
                        // yani != dar where;
                    } else {
                        $data .= $key . '=' . ':' . $key;
                        // yani == dar where;
                    }
                    $data .= ')';
                }
            }
        }
        return $data;
    }

    public function generateUpdateSet($set)
    {
        $data = 'SET ';
        $i = 1;
        foreach($set as $col => $value){
            $data .= $col . '=' . ':' . $col;
            if ($i < count($set)) {
                $data .= ', ';
            }
            $i++;
        }
        return $data;
    }

    public function update($data)
    {
        $where = $this->generateWhere($this->where);
        $set = $this->generateUpdateSet($data);
        $query = self::$connection->prepare("UPDATE " . '`' . $this->table .'` '.  $set . $where);
        $this->insertBindParams($query, $data);
        $this->insertBindParams($query, $this->where);
        try {
            $query->execute();
            DisplayFormat::jsonFormat(true, 'updated successfully');
        } catch (\PDOException $e) {
            http_response_code(400);
            DisplayFormat::jsonFormat(false, 'an error occured while updating' . $e->getMessage());        
        } 
    }

    public function delete($data)
    {
        $where = $this->generateWhere($data);
        $query = self::$connection->prepare("DELETE FROM " . '`' .  $this->table . '`' . $where);
        $this->insertBindParams($query, $data);
        try {
            $query->execute();
            DisplayFormat::jsonFormat(true, 'deleted successfully');
        } catch (\PDOException $e) {
            http_response_code(400);
            DisplayFormat::jsonFormat(false, 'an error occured while deleting' . $e->getMessage());
        }
    }

    public function select()
    {
        $row = $this->generateRows($this->row);
        $where = $this->generateWhere($this->where);
        $query = self::$connection->prepare("SELECT ". $row ." FROM " . '`' . $this->table . '`' . $where);
        $this->insertBindParams($query, $this->where);
        try {
            $query->execute();
            if ($this->limit == 1) {
            $data = $query->fetch();
            } else {
                $data = $query->fetchAll();
            }
            if ($data === false) {
                return [];
            }
            return $data;
        } catch (\PDOException $e) {
            http_response_code(400);
            DisplayFormat::jsonFormat(false, 'an error occured while selecting' . $e->getMessage());
        }
    }
}