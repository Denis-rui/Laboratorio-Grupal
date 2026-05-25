<?php

class Model extends conexion
{
    protected $tabla;
    protected $primaryKey = "id";

    protected $select = "*";
    protected $joins = [];
    protected $whereBuilder = [];
    protected $orderBy = "";
    protected $limit = "";


    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->tabla}";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE {$this->primaryKey} = :id";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE {$this->primaryKey} = :id";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function create($data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->tabla} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->conectar()->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "{$key} = :{$key}, ";
        }
        $setClause = rtrim($setClause, ", ");
        $sql = "UPDATE {$this->tabla} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $stmt = $this->conectar()->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function where($conditions)
    {
        foreach ($conditions as $field => $value) {
            if (is_numeric($value)) {
                $this->whereBuilder[] = "{$field} = {$value}";
            } else {
                $value = str_replace("'", "\\'", $value);
                $this->whereBuilder[] = "{$field} = '{$value}'";
            }
        }
        return $this;
    }

    // metodos para query builder

    public function select($fields)
    {
        $this->select = $fields;
        return $this;
    }

    public function join($table, $condition, $type = "INNER")
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$condition}";
        return $this;
    }

    public function orderBy($field, $direction = "ASC")
    {
        $this->orderBy = "ORDER BY {$field} {$direction}";
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = "LIMIT {$limit}";
        return $this;
    }


    // metodo get
    public function get()
    {
        $sql = "SELECT {$this->select} FROM {$this->tabla} ";
        //joins
        if (!empty($this->joins)) {
            $sql .= implode(" ", $this->joins);
        }
        //where
        if (!empty($this->whereBuilder)) {
            $sql .= " WHERE " . implode(" AND ", $this->whereBuilder);
        }
        //order by
        if (!empty($this->orderBy)) {
            $sql .= " " . $this->orderBy;
        }
        //limit
        if (!empty($this->limit)) {
            $sql .= " " . $this->limit;
        }
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute();
        $this->resetQuery();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }

    private function resetQuery()
    {
        $this->select = "*";
        $this->joins = [];
        $this->whereBuilder = [];
        $this->orderBy = "";
        $this->limit = "";
    }
}
