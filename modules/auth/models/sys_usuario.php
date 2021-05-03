<?php

/**
 * Description of sys_usuario
 *
 * @author Jaime Hernandez
 */
class sys_usuario extends ModelBase{
    public function edit(){
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
            $s .= "$key='$value',";
        }
        $s = rtrim($s, ',');
        $s .= ' ';
        $s .= "WHERE username='{$this->id}'";
        $resultado  =ModelBase::$db->query($s);
        return !PEAR::isError($resultado);
    }
}