<?php

namespace app\classes;

use PDO, PDOException;

class DB extends PDO{ 
    private $servername= "localhost";
    private $username= "root";
    private $password= "";
    private $dbname= "greennet";

    //Variables - Atributos de control para las consutlas 
    protected $s = " * "; //Select
    protected $j = "";    // Join
    protected $w = " 1 "; //Where
    protected $o = "";    // Order by
    protected $l = "";    // Limit
    protected $r;         // Resultado de consulta
    protected $c = "";    // Count
    protected $concat = ""; //Concat
    protected $g ="";    //group by
    protected $campos;
    public $valores;

    public function __construct()
    {
        try{
            parent::__construct("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function select($cc = []){
        if(count($cc) > 0){
            $this->s = implode(",", $cc);
        }
        return $this;
    }

    public function count($c = ["*"]){
        if(count($c) > 0){
            foreach($c as $counts){
                if($counts[0] == "*"){
                    $this->c .= ",count(" .$counts[0] . ")". " AS " . "tt"; 
                }
                else{
                    $this->c .= ",count(" .$counts[0] . ")". $counts[1];
                }
            }
        }
        return $this;
    }

    public function join($join = []){
        if(count($join) > 0){
            foreach($join as $joins){
                if($joins[0] != "" && $joins[1] != "" && $joins[2] != ""){
                    $this->j .= " " . $joins[2] . ' join ' . $joins[0] . ' on ' . $joins[1];
                }
            }
        }
        return $this;
    }

    public function group_concat($con, $name){
        if($con != ""){
            $this->concat .=  ",GROUP_CONCAT(".$con. ")" . $name;
        }
        return $this;
    }

    public function where($ww = []){
        $this->w = "";
        if(count($ww) > 0){
            foreach($ww as $wheres){
                $this->w .= $wheres[0] . " like '" . $wheres[1] . "'" . " and ";
            }
        }
        $this->w.= '1';
        return $this;
    }

    public function orderBy($ob = []){
        $this->o = "";
        if(count($ob) > 0){
            foreach($ob as $orderBy){
                $this->o .= $orderBy[0] . ' ' . $orderBy[1] . ',';
            }
            $this->o = ' order by ' . trim($this->o, ',');
        }
        return $this;
    }

    public function groupby($group = ""){
        $this->g = "";
        if($group != ""){
           $this-> g = ' group by ' . $group;
        }
        return $this;
    }

    public function limit($l = ""){
        $this->l = "";
        if($l != ""){
            $this->l = ' limit ' . $l;
        }
        return $this;
    }

    public function getAll(){
        $sql = "select " . $this->s .
               $this->c .
               $this->concat .
               " from " . str_replace("app\\models\\", "", get_class($this)) .
               ($this->j != "" ? " a" . $this->j : "") .
               " where " . $this->w .
               $this->o . 
               $this->g . 
               $this->l;
            //echo $sql;
        $this->r = $this->query($sql);
        return $this->r->fetchAll(PDO::FETCH_CLASS);
    }

    public function insert() {
        $keys = implode(', ', array_fill(0, count($this->campos), '?'));
        $columnas = implode(', ', $this->campos);            
        $query = "INSERT INTO " . str_replace("app\\models\\", "", get_class($this)) . " ($columnas) VALUES ($keys)";
        $stmt = $this->prepare($query);
        return $stmt->execute($this->valores);
    }

    public function delete() {
        $sql = "DELETE FROM " . str_replace("app\\models\\", "", get_class($this)) . " WHERE " . $this->w;
        $stmt = $this->prepare($sql);
        return $stmt->execute();
    }

    public function update($valores){
        $sql = "UPDATE " . str_replace("app\\models\\", "", get_class($this)) .
        " SET " . implode(', ', array_map(fn($key) => "$key = :$key", array_keys($valores))) . 
        ' WHERE ' . $this->w;
        $stmt = $this->prepare($sql);
        foreach ($valores as $key => $value)
            $stmt->bindValue(":$key", $value);
        return $stmt->execute();
    }
}
?>
