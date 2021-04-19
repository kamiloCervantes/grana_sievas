<?php

class GeneralModel extends ModelBase{
    
    public function guardar(){
        $resultado = Array(
        'status' => 'OK',
        'str'    => '',
        'msg'    => ''
        );

        $resultado['status'] = 'ERROR';
        $resultado['msg'] = 'ERROR: No se recibieron los parametros correctamente';

        if(isset($_POST['tabla']) && trim($_POST['tabla'] != "" ))
        {   
           $campos = "(";
           $valores = "(";
           foreach($_POST as $key => $value){
              if($key != 'tabla' && $key != 'primary_key' && $key != 'tiene_autoincremento'){
                  $campos .= $key.", ";
                  $valores .= self::$db->quote($value).", ";
              }
           }
           $campos = substr(trim($campos),0,-1).")";
           $valores = substr(trim($valores),0,-1).")";
           
           //Recibidos los datos, enviar la información a la base de datos
           $sql = sprintf("INSERT INTO %s %s VALUES %s",
                         $_POST['tabla'],
                             $campos,
                                 $valores);
           $res = self::$db->query($sql);
           if (PEAR::isError($res)) {                 
              $resultado['status'] = 'ERRORDB';
              $resultado['msg'] = "ERROR al insertar en la DB: ".$res->getMessage()." ".$sql;
              $resultado['str'] = $sql;
              
              header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
                  die($resultado['msg']);	  
           }
           else {
              $resultado['status'] = 'OK';
              $resultado['msg'] = "Insercion ok"; 	 
                  if($_POST['tiene_autoincremento'] == 'NO'){
                     $id = $_POST[$_POST['primary_key']];
                  } else {
                     $id = self::$db->lastInsertID($_POST['tabla'], $_POST['primary_key']);
                  }
                  echo json_encode(array('id' => $id));       
           }	
        }
    }
    
    public function guardar_con_creador(){
        $resultado = Array(
        'status' => 'OK',
        'str'    => '',
        'msg'    => ''
        );

        $resultado['status'] = 'ERROR';
        $resultado['msg'] = 'ERROR: No se recibieron los parametros correctamente';
        
        array_push($_POST, array('creador' => Auth::info_usuario('usuario')));
        
        if(isset($_POST['tabla']) && trim($_POST['tabla'] != "" ))
        {   
           $campos = "(";
           $valores = "(";
           foreach($_POST as $key => $value){
              if($key != 'tabla' && $key != 'primary_key' && $key != 'tiene_autoincremento'){
                  $campos .= $key.", ";
                  $valores .= self::$db->quote($value).", ";
              }
           }
           $campos = substr(trim($campos),0,-1).")";
           $valores = substr(trim($valores),0,-1).")";   
           
           //Recibidos los datos, enviar la información a la base de datos
           $sql = sprintf("INSERT INTO %s %s VALUES %s",
                         $_POST['tabla'],
                             $campos,
                                 $valores);
           $res = self::$db->query($sql);
           if (PEAR::isError($res)) {                 
              $resultado['status'] = 'ERRORDB';
              $resultado['msg'] = "ERROR al insertar en la DB: ".$res->getMessage()." ".$sql;
              $resultado['str'] = $sql;
              
              header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
                  die($resultado['msg']);	  
           }
           else {
              $resultado['status'] = 'OK';
              $resultado['msg'] = "Insercion ok"; 	 
                  if($_POST['tiene_autoincremento'] == 'NO'){
                     $id = $_POST[$_POST['primary_key']];
                  } else {
                     $id = self::$db->lastInsertID($_POST['tabla'], $_POST['primary_key']);
                  }
                  echo json_encode(array('id' => $id));       
           }	
        }
    }
    
    /*
     * Listar sin fkeys
     */
    public function listar1(){
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
	$aColumns = explode(',',$_GET['sColumns']);
        
        
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = $_GET['sIndexColumn'];
        
        
	
	/* DB table to use */
	$sTable = $_GET['sTable'];
	
        
	$aActions = json_decode($_GET['aActions']);
	
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
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
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
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
	";
        
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
            self::$db->setLimit($_GET['iDisplayLength'], $_GET['iDisplayStart']);
	}

	$rResult = self::$db->query($sQuery);
        
        
        $aResultFilterTotal = $rResult->numRows();
	$iFilteredTotal = $aResultFilterTotal;
        
        
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable $sWhere
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
		"aaData" => array()
	);
	
	while ( $aRow = $rResult->fetchRow() )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] === "''" )
			{
				/* Special output formatting for 'version' column */
                                if(count($aActions) > 0)
				$row[] = implode(' ', $aActions);
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output ); 
    }
    
    
    public function listar(){

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
	$aColumns = explode(',',$_GET['sColumns']);
        
        $sTable = $_GET['sTable'];
        
        foreach($aColumns as $key => $c){
            if(strrpos($c, '.') === false){
                $aColumns[$key] = $sTable.'.'.$c;
            }
        }
        
        
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = $_GET['sIndexColumn'];
        
        
	
	/* DB table to use */
	
	
        
	$aActions = json_decode($_GET['aActions']);
        
        $sJoin = "";
        if(isset($_GET['fKeys'])){
            $fKeys = json_decode($_GET['fKeys']);
            foreach($fKeys as $f){
                $filtroForanea=isset($f->findice)?$f->findice:'id';
                $sJoin .= sprintf(" left join %s on %s.%s = %s.%s", $f->nombre, $_GET['sTable'], $f->fkey, $f->nombre, $filtroForanea);
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
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
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
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	 
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
                $sJoin
		$sWhere
		$sOrder
	";
        
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
            self::$db->setLimit($_GET['iDisplayLength'], $_GET['iDisplayStart']);
	}
        
        
	$rResult = self::$db->query($sQuery);
        

        $aResultFilterTotal = $rResult->numRows();
	$iFilteredTotal = $aResultFilterTotal;
        
        
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sTable.'.'.$sIndexColumn.")
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
		"aaData" => array()
	);
        
	foreach($aColumns as $key => $c){
            if(strrpos($c, '.') !== false){
                $aColumns[$key] = substr($c, strrpos($c,'.')+1);
            }
        }
        
	while ( $aRow = $rResult->fetchRow() )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] === "''" )
			{
				/* Special output formatting for 'version' column */
                                if(count($aActions) > 0)
				$row[] = implode(' ', $aActions);
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
       
	echo json_encode( $output );
    }
    
    
    public function eliminar(){
          $resultado = Array(
            'status' => 'OK',
            'str'    => '',
            'msg'    => ''
          );

          $resultado['status'] = 'ERROR';
          $resultado['msg'] = 'ERROR: No se recibieron los parametros correctamente';	


          if(isset($_POST['id']) && trim($_POST['id'] != "" ))
          {
                  $idtab = explode(" ", $_POST['id']);	
                  $id = $idtab[0]; //el id
                  $tabla = $idtab[1]; //la tabla
                  $key = $idtab[2]; //la llave

             //Recibidos los datos, enviar la información a la base de datos
             $sql = sprintf("DELETE FROM $tabla WHERE $key = %s", self::$db->escape($id));
             $res = self::$db->query($sql);    
             if (PEAR::isError($res)) {
                $resultado['status'] = 'ERRORDB';
                $resultado['msg'] = "ERROR no se pudo eliminar de la DB: ".$res->getMessage();
                $resultado['str'] = $sql;

                header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
                die($resultado['str']);	  
             }
             else {
                $resultado['status'] = 'ok';
                $resultado['msg'] = "Eliminacion ok"; 

//                record_log("ELIMINAR", basename($_SERVER['HTTP_REFERER']), $tabla, "$id", $db);
                die("".$id);  
             }//endif todo ok	
          }
    }
    
    public function editar(){
        $resultado = Array
        (
          'status' => 'OK',
          'str'    => '',
          'msg'    => ''
        );

        $resultado['status'] = 'ERROR';
        $resultado['msg'] = 'ERROR: No se recibieron los parametros correctamente';	

        if(isset($_POST['tabla']) && trim($_POST['tabla'] != "" ))
        {   
           $campos = "";
           foreach($_POST as $key => $value)
           {
              if($key != 'tabla' && $key != 'primary_key' && $key != 'tiene_autoincremento' && $key != 'pkey')
                  {
                    $campos .= $key."=". self::$db->quote($value).",";     
                  }
           }

           $campos = substr(trim($campos),0,-1)."";     

           //Recibidos los datos, enviar la información a la base de datos
           $sql = sprintf("UPDATE %s SET %s WHERE %s = %s",
                        $_POST['tabla'],
                        $campos,
                        $_POST['primary_key'],         
                        self::$db->quote($_POST['pkey']));
           $res = self::$db->query($sql);
           if (PEAR::isError($res)) {
              $resultado['status'] = 'ERRORDB';
              $resultado['msg'] = "ERROR al insertar en la DB: ".$res->getMessage()." ".$sql;
              $resultado['str'] = $sql;

              header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
              die($resultado['msg']);	  
           }
           else {
              $resultado['status'] = 'OK';
              $resultado['msg'] = "update all ok"; 	  
                  if($_POST['tiene_autoincremento'] == 'NO'){
                     $id = $_POST[$_POST['primary_key']];
                  } else {
                     $id = self::$db->lastInsertID($_POST['tabla'], $_POST['primary_key']);
                  }
        //	  record_log("update", basename($_SERVER['HTTP_REFERER']), $_POST['tabla'], "$id", $db);
                  echo json_encode(array('id' => $_POST['pkey']));       
           }//endif todo ok	
        }
    }
    
    public function editar_con_creador(){
        $resultado = Array
        (
          'status' => 'OK',
          'str'    => '',
          'msg'    => ''
        );

        $resultado['status'] = 'ERROR';
        $resultado['msg'] = 'ERROR: No se recibieron los parametros correctamente';	
        
        array_push($_POST, array('creador' => Auth::info_usuario('usuario')));

        if(isset($_POST['tabla']) && trim($_POST['tabla'] != "" ))
        {   
           $campos = "";
           foreach($_POST as $key => $value)
           {
              if($key != 'tabla' && $key != 'primary_key' && $key != 'tiene_autoincremento' && $key != 'pkey')
                  {
                    $campos .= $key."=". self::$db->quote($value).",";     
                  }
           }

           $campos = substr(trim($campos),0,-1)."";     

           //Recibidos los datos, enviar la información a la base de datos
           $sql = sprintf("UPDATE %s SET %s WHERE %s = %s",
                        $_POST['tabla'],
                        $campos,
                        $_POST['primary_key'],         
                        self::$db->quote($_POST['pkey']));
           $res = self::$db->query($sql);
           echo $sql;
           if (PEAR::isError($res)) {
              $resultado['status'] = 'ERRORDB';
              $resultado['msg'] = "ERROR al insertar en la DB: ".$res->getMessage()." ".$sql;
              $resultado['str'] = $sql;

              header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
              die($resultado['msg']);	  
           }
           else {
              $resultado['status'] = 'OK';
              $resultado['msg'] = "update all ok"; 	  
                  if($_POST['tiene_autoincremento'] == 'NO'){
                     $id = $_POST[$_POST['primary_key']];
                  } else {
                     $id = self::$db->lastInsertID($_POST['tabla'], $_POST['primary_key']);
                  }
        //	  record_log("update", basename($_SERVER['HTTP_REFERER']), $_POST['tabla'], "$id", $db);
                  die("".$id);  
           }//endif todo ok	
        }
    }
    
    public function get_fkeys(){
        $tablas = json_decode($_GET['tablas']);
        $id = $_GET['id'];
        $tabla = $_GET['tabla'];
        $respuesta = array();
        $respuesta['foraneas'] = array();

        if(isset($id)){
            $query_rsConsulta = sprintf("SELECT * FROM %s where id=%s limit 1", $tabla, $id);
            $rsConsulta = self::$db->query($query_rsConsulta);
            
            $respuesta['principal'] = $rsConsulta->fetchRow();

            if(count($tablas) > 0){
                foreach($tablas as $t){
                    $sql = '';
                    if(count($t->filtros) > 0){
                        $sql .= sprintf("SELECT * FROM %s where %s", $t->nombre, implode(' and ', $t->filtros));
                         
                    }    
                    else{
                       $sql .= sprintf("SELECT * FROM %s", $t->nombre); 
                    }  
                    if(count($t->orden) > 0){
                         $sql .= ' order by '. implode(' , ', $t->orden);
                     }
                    $rsConsulta = self::$db->query($sql);                    
                    $fk = array();
                    while($row_rsConsulta = $rsConsulta->fetchRow()){
                        $fk[] = $row_rsConsulta;
                    }

                    $respuesta['foraneas'][$t->nombre] = $fk;

                }
            }
        }
        else{
            $respuesta['principal'] = array();            
             if(count($tablas) > 0){
                 foreach($tablas as $t){
                    $sql = '';
                    if(count($t->filtros) > 0){
                        $sql .= sprintf("SELECT * FROM %s where %s", $t->nombre, implode(' and ', $t->filtros));
                    }    
                    else{
                       $sql .= sprintf("SELECT * FROM %s", $t->nombre);                       
                    }    
                     if(count($t->orden) > 0){
                         $sql .= ' order by '. implode(' , ', $t->orden);
                     }
                    $rsConsulta = self::$db->query($sql);
                    $fk = array();
                    while($row_rsConsulta = $rsConsulta->fetchRow()){
                            $fk[] = $row_rsConsulta;
                    }

                    $respuesta['foraneas'][$t->nombre] = $fk;

                }                
            }
        }
        echo json_encode($respuesta);
    }
    
    public function get_all(){        
        $tabla = $_GET['tabla'];

        $query_rsConsulta = sprintf("SELECT * FROM %s", $tabla);
        
        $rsConsulta = self::$db->query($query_rsConsulta);
        if (PEAR::isError($rsConsulta)) {
            $resultado['status'] = 'ERRORDB';
            $resultado['msg'] = "ERROR en la consulta del FORMULARIO: ".$res->getDebugInfo($rsConsulta);
                $resultado['str'] = "";
                die($resultado['msg']);	
        }

        $respuesta = array();
        while($row_rsConsulta = $rsConsulta->fetchRow()){
                $respuesta[] = $row_rsConsulta;
        }
        echo json_encode($respuesta);
    }
    
    public function get_autocomplete(){        
        $query = $_GET['query'];
        $tabla = $_GET['tabla'];
        $columna = $_GET['columna'];
        $sugerencia = $_GET['sugerencia'];
        $separador = $_GET['separador'];
        $col_unico = $_GET['col_unico'];
        $filter = $_GET['filter'];
        $filter_value = $_GET['filter_value'];
        $sug_array = explode(';', $sugerencia);

        if($col_unico === 'SI'){
            if($filter !== ''){
                $query_rsConsulta = sprintf("SELECT * FROM %s where %s = %s",
                                   $tabla, $filter, self::$db->quote($filter_value));
            }
            else{
                $query_rsConsulta = sprintf("SELECT * FROM %s", $tabla);
            }

        } else {
            if($filter !== ''){    
                $query_rsConsulta = sprintf("SELECT * FROM %s where %s = %s group by %s",
                                   $tabla, $filter, self::$db->quote($filter_value),$columna);
            }
            else{
                $query_rsConsulta = sprintf("SELECT * FROM %s group by %s", $tabla, $columna);
            }

        }			

        $rsConsulta = self::$db->query($query_rsConsulta);
        if (PEAR::isError($rsConsulta)) {
            $resultado['status'] = 'ERRORDB';
            $resultado['msg'] = "ERROR en la consulta a los TIPOS DE DOCUMENTO: ".$rsConsulta->getDebugInfo($rsConsulta);
                $resultado['str'] = "";
                die($resultado['msg']);	
        }
        //$row_rsConsulta = $rsConsulta->fetchRow();
        //$totalRows_rsConsulta = $rsConsulta->numRows();

        $data = array();
        $data['query'] = $query;
        $data['suggestions'] = array();
        while($r = $rsConsulta->fetchRow()){

            $permitidas = array("&ntilde;");
            $nopermitidas = array("ñ");

            $c = strtoupper($r[$columna]);
            $q = mb_strtoupper($query, 'utf-8');


            if(strpos($c, $q) !== false){
                $suggestion = '';
                foreach($sug_array as $s){
                     ($suggestion === '' ? $suggestion .= strtoupper($r[$s]) : $suggestion .= $separador.strtoupper($r[$s]));
                }
                $data['suggestions'][] = $suggestion;
                $data['data'][] = $r['id'];
            }  
        }
        echo json_encode($data);
    }
    public function get_autocomplete_2(){        
        $query = $_GET['query'];
        $tabla = $_GET['tabla'];
        $columna = $_GET['columna'];
        $sugerencia = $_GET['sugerencia'];
        $separador = $_GET['separador'];
        $col_unico = $_GET['col_unico'];
        $filter = $_GET['filter'];
        $filter_value = $_GET['filter_value'];
        $sug_array = explode(';', $sugerencia);

        if($col_unico === 'SI'){
            if($filter !== ''){
                $query_rsConsulta = sprintf("SELECT * FROM %s where %s = %s",
                                   $tabla, $filter, self::$db->quote($filter_value));
            }
            else{
                $query_rsConsulta = sprintf("SELECT * FROM %s", $tabla);
            }

        } else {
            if($filter !== ''){    
                $query_rsConsulta = sprintf("SELECT * FROM %s where %s = %s group by %s",
                                   $tabla, $filter, self::$db->quote($filter_value),$columna);
            }
            else{
                $query_rsConsulta = sprintf("SELECT * FROM %s group by %s", $tabla, $columna);
            }

        }			

        $rsConsulta = self::$db->query($query_rsConsulta);
        if (PEAR::isError($rsConsulta)) {
            $resultado['status'] = 'ERRORDB';
            $resultado['msg'] = "ERROR en la consulta a los TIPOS DE DOCUMENTO: ".$rsConsulta->getDebugInfo($rsConsulta);
                $resultado['str'] = "";
                die($resultado['msg']);	
        }
        //$row_rsConsulta = $rsConsulta->fetchRow();
        //$totalRows_rsConsulta = $rsConsulta->numRows();

        $data = array();
        $data['query'] = $query;
        $data['suggestions'] = array();
        while($r = $rsConsulta->fetchRow()){

            $permitidas = array("&ntilde;");
            $nopermitidas = array("ñ");

            $c = strtoupper($r[$columna]);
            $q = mb_strtoupper($query, 'utf-8');


            if(strpos($c, $q) !== false){
                $suggestion = '';
                foreach($sug_array as $s){
                     ($suggestion === '' ? $suggestion .= strtoupper($r[$s]) : $suggestion .= $separador.strtoupper($r[$s]));
                }
                $data['suggestions'][] = array('value'=>$suggestion,'data'=>$r['id']);
                //$data['data'][] = $r['id'];
            }  
        }
        echo json_encode($data);
    }
}