<?php
//require_once '/var/www/academico/config/config.php';
//require_once '/var/www/academico/Connections/eval.php';

abstract class ModelBase2
{
    private static $db;
    private static $_error_msg = '';
    private static $_error_sql = '';
    private static $_error_code = '';
    private static $_sql = '';

    public function __construct()
    {
        //require_once 'db/config.php';
        //require_once 'db/eval.php';
        self::$db = BD::$db;
    }

    public function begin()
    {
        self::$db->beginTransaction();
    }

    public function commit()
    {
        self::$db->commit();
    }

    public function rollback()
    {
        self::$db->rollback();
    }

    public function table_name()
    {
        return get_class($this);
    }

    public function save()
    {
        /* inicio insercion principal*/
        $s = '';
        $s .= 'INSERT INTO';
        $s .= ' ';
        $s .= $this->table_name();
        $s .= ' ';
        $s .= '(';
        $s .= implode(',', array_keys(get_object_vars($this)));
        $s .= ')';
        $s .= ' ';
        $s .= 'VALUES';
        $s .= ' ';
        $s .= '(';
        $s .= implode(',', array_map(array('modelbase2', 'comillas'), array_values(get_object_vars
            ($this))));
        $s .= ')';

        $r = self::$db->query($s);
        
        self::$_sql = $s;
        
        //borrar variables de errores
        self::$_error_sql = '';
        self::$_error_msg = '';

        if (PEAR::isError($r)) {
            
            self::$_error_sql = $s;
            self::$_error_msg = $r->getMessage();
            self::$_error_code = $r->getCode();
            
            return false;
        } else {
            $this->id = self::$db->lastInsertID();
//            $this->find($this->id);
            return $this;
        }
    }
    
    private function comillas($str)
    {
        return "'$str'";
    }

    public function find($where = '')
    {
        if (is_int($where)) {
            $s = '';
            $s .= "SELECT * FROM ";
            $s .= $this->table_name();
            $s .= " WHERE id = $where";
            
            self::$db->setLimit(1);
//            var_dump($s);
            $r = self::$db->queryAll($s);

            if (count($r) == 1) {
                
                foreach ($r[0] as $key => $val) {
                    $this->$key = $val;
                }
                
                return $this;

            } else {
                return false;
            }
        } else {
            
            $s = '';
            $s .= "SELECT * FROM ";
            $s .= $this->table_name();
            $s .= ' WHERE ';
            $s .= trim($where);
                       
            $r = self::$db->queryAll($s);
            
            if (count($r) == 1) {
                
                foreach ($r[0] as $key => $val) {
                    $this->$key = $val;
                }
                
                return $this;

            } else {
                return false;
            }
        }
    }

    public function update()
    {
        $s = '';
        $s .= 'UPDATE';
        $s .= ' ';
        $s .= $this->table_name();
        $s .= ' ';
        $s .= 'SET';
        $s .= ' ';
        $tem = get_object_vars($this);

        unset($tem['id']);

        foreach ($tem as $key => $value) {
            $s .= "$key=".self::$db->quote($value).",";
        }

        $s = rtrim($s, ',');
        $s .= ' ';
        $s .= "WHERE id={$this->id}";

        $r = self::$db->query($s);
        
        self::$_sql = $s;
        
        //borrar variables de errores
        self::$_error_sql = '';
        self::$_error_msg = '';

        if(PEAR::isError($r)){
            self::$_error_sql = $s;
            self::$_error_msg = $r->getMessage();
            return false;
        }else{
            return true;
        }
    }

    public function delete()
    {
        $s = '';
        $s .= 'DELETE FROM';
        $s .= ' ';
        $s .= $this->table_name();
        $s .= " WHERE id=$this->id";
        
        $r = self::$db->query($s);

        //borrar variables de errores
        self::$_error_sql = '';
        self::$_error_msg = '';

        if(PEAR::isError($r)){
            self::$_error_sql = $s;
            self::$_error_msg = $r->getMessage();
            return false;
        }else{
            return true;
        }
    }
    
    public function helper_delete($table_name, $conditions = array())
    {
        $s = '';
        $s .= 'DELETE FROM';
        $s .= ' ';
        $s .= $table_name;
        $s .= " WHERE ";
        foreach($conditions as $k => $cond){
            $s .= $cond['param']. '=' .$cond['value']. (count($conditions) > 1 && count($conditions) != $k+1 ? ' AND ' : '');
        }
        
               
        $r = self::$db->query($s);

        //borrar variables de errores
        self::$_error_sql = '';
        self::$_error_msg = '';

        if(PEAR::isError($r)){
            self::$_error_sql = $s;
            self::$_error_msg = $r->getMessage();
            return false;
        }else{
            return true;
        }
    }
    
    public function error_msg(){
        return self::$_error_msg;
    }
    
    public function error_sql(){
        return self::$_error_sql;
    }
    
    public function sql(){
        return self::$_sql;
    }
    
    public function error_code(){
        return self::$_error_code;
    }
}
?>