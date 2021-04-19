<?php

class sieva_lineamientos extends ModelBase{
    
    static $arbol;
    
    public function __construct() {
        parent::__construct();
        self::$arbol = array();
        $nodo = array();
        $nodo['id'] = 0;
        $nodo['parent'] = '#';
        $nodo['text'] = '#';
        $nodo['icon'] = 'public/img/icon-nodes.png';
        $nodo['state'] = array(
            'opened' => true,
            'disabled' => false,
            'selected' => false
        );
        $nodo['li_attr'] = array(
            'rel' => 'default'
        );
        $nodo['a_attr'] = array(
            
        );
        self::$arbol[] = $nodo;
        
    }
    
    public function crearnodo($id, $padre_id, $text){
        $nodo = array();
        $nodo['id'] = $id;
        $nodo['parent'] = $padre_id;
        $nodo['text'] = $text;
        $nodo['icon'] = $this->getIcono($id, $padre_id);
        $nodo['state'] = array(
            'opened' => false,
            'disabled' => false,
            'selected' => false
        );
        $nodo['li_attr'] = array(
            'rel' => 'default'
        );
        $nodo['a_attr'] = array(
            
        );  
        return $nodo;
    }
   
    
    public function cargarArbol(){
        $arbol  = array();
        $arbol[]    = $this->crearnodo(0,'#', 'Rubros');
        $consulta   = 'SELECT id, nom_lineamiento, num_orden, atributos_lineamiento, padre_lineamiento 
					   FROM lineamientos order by num_orden';
        $resultado  = self::$db->query($consulta);
        if (PEAR::isError($resultado)) {                 
            header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
            die($resultado->getMessage().' '.$consulta);
        }
        while ($unidad = $resultado->fetchRow()) {
            $arbol[]   = $this->crearnodo($unidad['id'],
				($unidad['padre_lineamiento'] === null ? 0 : $unidad['padre_lineamiento']), 
				$unidad['atributos_lineamiento']."".$unidad['num_orden'].". ".$unidad['nom_lineamiento']);
        }
 
        return $arbol;
    }
    
    public function cargarArbolRubros(){
        $arbol  = array();
        $arbol[]    = $this->crearnodo(0,'#', 'Rubros');
        $consulta   = 'SELECT id, nom_lineamiento, num_orden, atributos_lineamiento, padre_lineamiento 
					   FROM lineamientos order by num_orden';
        $resultado  = self::$db->query($consulta);
        if (PEAR::isError($resultado)) {                 
            header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
            die($resultado->getMessage().' '.$consulta);
        }
        while ($unidad = $resultado->fetchRow()) {
            if($unidad['padre_lineamiento'] == 0){
                $arbol[]   = $this->crearnodo($unidad['id'],
				($unidad['padre_lineamiento'] === null ? 0 : $unidad['padre_lineamiento']), 
				$unidad['atributos_lineamiento']."".$unidad['num_orden'].". ".$unidad['nom_lineamiento']);
            }
            
        }
 
        return $arbol;
    }
    
    public function cargarArbolAreasConocimiento(){
        $arbol  = array();
        $arbol[]    = $this->crearnodo(0,'#', 'Rubros');
        $consulta   = 'SELECT * FROM areas_conocmiento order by area';
        $resultado  = self::$db->query($consulta);
        if (PEAR::isError($resultado)) {                 
            header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
            die($resultado->getMessage().' '.$consulta);
        }
        while ($unidad = $resultado->fetchRow()) {
            if($unidad['padre_lineamiento'] == 0){
                $arbol[]   = $this->crearnodo($unidad['id'],
				0, 
				$unidad['area']);
            }
            
        }
 
        return $arbol;
    }
    
    
    public function getIcono($lineamiento, $padre){  
        $idx_detalle = 0;
        $idx_mejoramiento = 0;
        $idx_anexo = 0;
        $idx_tabla = 0;
        $icono = 'public/img/icon-nodes-gris.png';
//        $momento_evaluacion = 3;
//        $sql_momento_resultado = "select momento_resultado.id from momento_resultado where cod_momento_evaluacion = $momento_evaluacion";
//        $momento_resultado = BD::$db->queryOne($sql_momento_resultado);
//        if($momento_resultado !== null){
//            $sql_momento_resultado_detalle = "select * from momento_resultado_detalle where cod_momento_resultado = $momento_resultado and cod_lineamiento = $lineamiento";
//            $detalle = BD::$db->queryAll($sql_momento_resultado_detalle);
//            if($detalle !== null && count($detalle)>0 && $detalle[0]['fortalezas'] !== '' && $detalle[0]['debilidades'] !== ''){
//                $idx_detalle++;
//            }
//
//            $sql_plan_mejoramiento = "SELECT * FROM plan_mejoramiento
//            INNER JOIN momento_resultado ON plan_mejoramiento.cod_momento_resultado = momento_resultado.id
//            INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
//            WHERE
//            momento_resultado.id = $momento_resultado AND
//            momento_resultado_detalle.cod_lineamiento = $lineamiento";
//
//            $mejoramiento = BD::$db->queryAll($sql_plan_mejoramiento);
//
//            if($mejoramiento !== null && count($mejoramiento)>0 && $mejoramiento[0]['titulo'] !== '' && $mejoramiento[0]['subtitulo'] !== '' && $mejoramiento[0]['presupuesto'] !== '' && $mejoramiento[0]['fecha_cumplimiento'] !== ''){
//                $idx_mejoramiento++;
//            }
//
//            $sql_momento_resultado_anexo = "select count(*) from momento_resultado_anexo where cod_momento_evaluacion = $momento_evaluacion and cod_lineamiento = $lineamiento";
//            $anexo = BD::$db->queryOne($sql_momento_resultado_anexo);
//
//            if($anexo !== null && count($anexo)>0){
//                $idx_anexo++;
//            }
//
//            $sql_lineamiento_archivo_tabla = "select count(*) from lineamiento_archivo_tabla where cod_momento_evaluacion = $momento_evaluacion and cod_lineamiento = $rubro";
//            $idx_tabla = BD::$db->queryOne($sql_lineamiento_archivo_tabla);
//
//
//            if($idx_detalle > 0 && $idx_mejoramiento > 0){
//                //naranja
//                $icono = 'public/img/icon-nodes-naranja.png';
//            }
//
//           if($idx_detalle > 0 && $idx_mejoramiento > 0 && $idx_anexo > 0 && $idx_tabla > 0){
//               //verde
//               $icono = 'public/img/icon-nodes-verde.png';
//           }
//
//        }
        return $icono;
        
    }
}
