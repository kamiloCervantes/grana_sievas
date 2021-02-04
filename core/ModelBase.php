<?php
//require_once '/var/www/academico/config/config.php';
//require_once '/var/www/academico/Connections/eval.php';

abstract class ModelBase
{
    static $db;
    private static $sql_array = array();
    private static $sql_fk_var = array();
    private static $_id = 0;
    public static $_error = '';

    public function __construct()
    {
        //require_once 'db/config.php';
        //require_once 'db/eval.php';

        self::$db = BD::$db;
    }

    public function table_name()
    {
        return get_class($this);
    }

    public function save()
    {
        //empiezo la trancsaccion
        self::$db->beginTransaction();

        $sql = $this->sql_insert_principal();
        $result = self::$db->query($sql);

        if (PEAR::isError($result)) {
            self::$db->rollback();
            self::$_error = $result->getMessage() . '-' . $sql;
            return false;
            //die($result->getMessage().' - '.$sql);
        } else {
            self::$db->commit();
            return self::$db->lastInsertID();
        }
    }

    public function save_fk()
    {
        //empiezo la trancsaccion
        self::$db->beginTransaction();

        $sql = $this->sql_insert_principal();
        // y hago varios querys
        $result = self::$db->query($sql);

        if (PEAR::isError($result)) {
            self::$db->rollback();
            self::$_error = $result->getMessage() . '-' . $sql;
            return false;
            //die($result->getMessage()." - ".$sql);
        } else {
            $last_id = self::$db->lastInsertID();
            $this->sql_insert_fk($last_id);
        }

        foreach (self::$sql_array as $sql) {

            $result = self::$db->query($sql);

            if (PEAR::isError($result)) {
                self::$db->rollback();
                self::$_error = $result->getMessage() . '-' . $sql;
                return false;
                //die($result->getMessage());
            }
        }

        self::$db->commit();
        return $last_id;
    }

    public function sql_insert_principal()
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
        $s .= implode(',', array_map(array('modelbase', 'comillas'), array_values(get_object_vars
            ($this))));
        $s .= ')';

        return $s;
    }

    public function fk($fktable, $data, $col1, $col2)
    {
        if (!empty($data)) {

            if (is_array($col1)) {
                $c1 = implode(',', array_keys($col1));
            } else {
                $c1 = $col1;
            }

            self::$sql_fk_var[] = array(
                'fktable' => $fktable,
                'data' => $data,
                'col1' => $c1,
                'col2' => $col2,
                'id' => $this->id);

        } else {
            if (!empty($this->id)) {
                // eliminar informacion de la tabla principal en la tabla de relacion
                $s = $this->del($fktable, "$col2=$this->id");
                self::$sql_array[] = $s;
            }
        }
    }

    public function sql_insert_fk($id = 1)
    {
        foreach (self::$sql_fk_var as $row) {
            $s = '';
            $s .= 'INSERT INTO';
            $s .= ' ';
            $s .= $row['fktable'];
            $s .= ' ';
            $s .= '(';
            $s .= "{$row['col1']}";
            $s .= ',';
            $s .= "{$row['col2']}";
            $s .= ')';
            $s .= ' ';
            $s .= 'VALUES';
            $s .= ' ';
            foreach ($row['data'] as $d) {
                if (!is_array($d)) {
                    $s .= "($d,$id),";
                } else {
                    $s .= '(';
                    $s .= implode(',', array_map(array('modelbase', 'comillas'), $d));
                    $s .= ',';
                    $s .= $id;
                    $s .= '),';
                }
            }
            $s = rtrim($s, ',');
            self::$sql_array[] = $s;
        }
    }

    private function comillas($str)
    {
        return "'$str'";
    }

    public function find($where = '')
    {
        $s = '';
        $s .= "SELECT * FROM ";
        $s .= $this->table_name();
        if (is_numeric($where)) {
            $s .= " WHERE id = $where";
        } else {
            $s .= " WHERE $where";
        }
        self::$db->setLimit(1);
        $rs = self::$db->query($s);
        if (PEAR::isError($rs)) {
            return false;
        } else {
            if ($rs->numRows()) {
                $row = $rs->fetchRow();
                foreach ($row as $key => $val) {
                    $this->$key = $val;
                }
                return $this;
            } else {
                return false;
            }
        }
    }

    public function edit()
    {
        self::$sql_array = array();

        self::$db->beginTransaction();

        $this->sql_editar_principal();

        foreach (self::$sql_array as $sql) {

            $result = self::$db->query($sql);

            if (PEAR::isError($result)) {
                self::$db->rollback();
                self::$_error = $result->getMessage() . ' - ' . $sql;
                return false;
            } else {
                self::$db->commit();
                return true;
            }
        }
    }


    public function edit_fk()
    {
        $this->sql_editar_principal();
        $this->sql_editar_fk();

        foreach (self::$sql_array as $clave => $valor) {
            if (empty($valor))
                unset(self::$sql_array[$clave]);
        }

        self::$sql_array = array_merge(self::$sql_array);

        self::$db->beginTransaction();

        foreach (self::$sql_array as $sql) {
            $result = self::$db->query($sql);

            if (PEAR::isError($result)) {
                self::$db->rollback();
                self::$_error = $result->getMessage();
                return false;
            }
        }

        self::$db->commit();
        return true;
    }

    public function sql_editar_principal()
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
            if($value===NULL){
                $s .= "$key=NULL,";
            }else{
                $s .= "$key='$value',";
            }
        }
        $s = rtrim($s, ',');
        $s .= ' ';
        $s .= "WHERE id={$this->id}";
        self::$sql_array[] = $s;
    }

    public function sql_editar_fk()
    {
        //se recorre el array
        foreach (self::$sql_fk_var as $row) {
            // eliminar informacion de la tabla principal en la tabla de relacion
            $s = '';
            $s = $this->del($row['fktable'], "{$row['col2']}=$this->id");
            self::$sql_array[] = $s;

            // insertar de la tabla de relacion
            $s = '';
            $s .= 'INSERT INTO';
            $s .= ' ';
            $s .= $row['fktable'];
            $s .= ' ';
            $s .= '(';
            $s .= "{$row['col1']}";
            $s .= ',';
            $s .= "{$row['col2']}";
            $s .= ')';
            $s .= ' ';
            $s .= 'VALUES';
            $s .= ' ';
            foreach ($row['data'] as $d) {
                if (!is_array($d)) {
                    $s .= "($d,$this->id),";
                } else {
                    $s .= '(';
                    $s .= implode(',', array_map(array('modelbase', 'comillas'), $d));
                    $s .= ',';
                    $s .= $this->id;
                    $s .= '),';
                }
            }
            $s = rtrim($s, ',');
            self::$sql_array[] = $s;
        }
    }

    private function del($table, $where = '')
    {
        $s = '';
        $s .= 'DELETE FROM';
        $s .= ' ';
        $s .= $table;
        $s .= ' WHERE ' . $where;
        //return $s;
        self::$sql_array[] = $s;
    }

    private function sql_delete_fk()
    {
        foreach (self::$sql_fk_var as $row) {
            $this->del($row['fktable'], "{$row['col2']}={$this->id}");
        }
        $this->del($this->table_name(), "id={$this->id}");
    }

    private function sql_delete()
    {
        $this->del($this->table_name(), "id={$this->id}");
    }

    public function delete()
    {
        self::$db->beginTransaction();

        $this->sql_delete();

        foreach (self::$sql_array as $sql) {

            $result = self::$db->query($sql);

            if (PEAR::isError($result)) {
                self::$db->rollback();
                self::$_error = $result->getMessage();
                return false;
                //die($result->getMessage().' - '.$sql);
            } else {
                self::$db->commit();
                return true;
            }
        }
    }

    public function delete_fk()
    {
        $this->sql_delete_fk();

        foreach (self::$sql_array as $sql) {

            $result = self::$db->query($sql);

            if (PEAR::isError($result)) {
                self::$db->rollback();
                die($result->getMessage() . ' - ' . $sql);
            }
        }
        self::$db->commit();
    }


    public function pagination()
    {

        /*
        * Script:    DataTables server-side script for PHP and MySQL
        * Copyright: 2010 - Allan Jardine
        * License:   GPL v2 or BSD (3-point)
        */

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Easy set variables
        */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
        * you want to insert a non-database field (for example a counter or static image)
        */


        //	$aColumns = array( 'engine', 'browser', 'platform', 'version', 'grade' );
        $aColumns = explode(',', $_GET['sColumns']);

        $sTable = $_GET['sTable'];

        foreach ($aColumns as $key => $c) {
            if (strrpos($c, '.') === false) {
                $aColumns[$key] = $sTable . '.' . $c;
            }
        }


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = $_GET['sIndexColumn'];


        /* DB table to use */


        $aActions = json_decode($_GET['aActions']);

        $sJoin = "";
        if (isset($_GET['fKeys'])) {
            $fKeys = json_decode($_GET['fKeys']);
            foreach ($fKeys as $f) {
                $filtroForanea = isset($f->findice) ? $f->findice : 'id';
                $sJoin .= sprintf(" left join %s on %s.%s = %s.%s", $f->nombre, $_GET['sTable'],
                    $f->fkey, $f->nombre, $filtroForanea);
            }
        }


        /*
        * Paging
        */
        $sLimit = "";
        //	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
        //	{
        //		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
        //			mysql_real_escape_string( $_GET['iDisplayLength'] );
        //	}


        /*
        * Ordering
        */
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }


        /*
        * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) .
                    "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' .
                    $i]) . "%' ";
            }
        }

        /*
        * SQL queries
        * Get data to display
        */
        $sQuery = "
		SELECT " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
		FROM   $sTable
                $sJoin
		$sWhere
		$sOrder
	";


        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            self::$db->setLimit($_GET['iDisplayLength'], $_GET['iDisplayStart']);
        }

        //echo $sQuery;die;
        $rResult = self::$db->query($sQuery);


        $aResultFilterTotal = $rResult->numRows();
        $iFilteredTotal = $aResultFilterTotal;


        /* Total data set length */
        $sQuery = "
		SELECT COUNT(" . $sTable . '.' . $sIndexColumn . ")
		FROM   $sTable $sJoin $sWhere 
		$sOrder
	";

        $rResultTotal = self::$db->query($sQuery);
        $aResultTotal = $rResultTotal->fetchCol();
        //	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
        //	$aResultTotal = mysql_fetch_array($rResultTotal);

        $iTotal = $aResultTotal[0];
        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array());

        foreach ($aColumns as $key => $c) {
            if (strrpos($c, '.') !== false) {
                $aColumns[$key] = substr($c, strrpos($c, '.') + 1);
            }
        }

        while ($aRow = $rResult->fetchRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] === "''") {
                    /* Special output formatting for 'version' column */
                    if (count($aActions) > 0)
                        $row[] = implode(' ', $aActions);
                } else
                    if ($aColumns[$i] != ' ') {
                        /* General output */
                        $row[] = $aRow[$aColumns[$i]];
                    }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }
}
?>