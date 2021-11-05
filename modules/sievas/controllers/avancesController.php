<?php
Load::model2('evaluacion');
Load::model2('momento_evaluacion');
Load::model2('comite');
Load::model2('sysusers_acceso');
Load::model2('sysusers');
Load::model2('evaluacion_revisiones');
Load::model2('GeneralModel');

//include_once  LIBS_PATH.'/tcpdf/tcpdf.php';

include_once  LIBS_PATH.'/tcpdf/tcpdf.php';
require_once (LIBS_PATH.'/jpgraph/jpgraph.php');
require_once (LIBS_PATH.'/jpgraph/jpgraph_radar.php');
require_once (LIBS_PATH.'/jpgraph/jpgraph_bar.php');
require_once (LIBS_PATH.'/jpgraph/jpgraph_iconplot.php');
require_once (LIBS_PATH.'/vsword/VsWord.php');

class avancesController extends ControllerBase{    
   
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        
    }
    
    public function generate_word_test(){
        
    }
    
    public function demo_monitor(){
        View::add_js('https://canvasjs.com/assets/script/canvasjs.min.js');
        View::add_js('modules/sievas/scripts/avances/demo_monitor.js');
        View::render('avances/demo_monitor.php', $vars);
    }
    
    public function select_graficas_estadisticas(){
        $evaluacion = Auth::info_usuario('evaluacion');
       // $sql = "select * from tablas_estadisticas_indicadores ";        
        $sql = sprintf("select tablas_estadisticas_indicadores.* from tablas_estadisticas_indicadores
        inner join lineamientos_conjuntos on tablas_estadisticas_indicadores.cod_conjunto = lineamientos_conjuntos.id
        inner join evaluacion on evaluacion.cod_conjunto = lineamientos_conjuntos.id
        where evaluacion.id = %s ", $evaluacion);        
        $indicadores = BD::$db->queryAll($sql);    
        
        $sql2 = "select * from tablas_estadisticas";
        $tablas = BD::$db->queryAll($sql2);
        $vars['indicadores'] = $indicadores;      
        $vars['tablas'] = $tablas;      
        
        View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/avances/tablas_estadisticas_graficas_p.js'); 
        View::render('avances/select_graficas_estadisticas.php', $vars);
    }
    
    public function tabla_graficas_estadisticas(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $indicadores = $_GET['i'];  
        
        $indicadores = explode('|', $indicadores);
        $indicadores_arr = array();
        $campo = '';

        foreach($indicadores as $ind){

        $sql_campo = sprintf("select campo from tablas_estadisticas_indicadores where id=%s", $ind);
        $campo = BD::$db->queryOne($sql_campo);
       
        $sql = sprintf("SELECT td.anio, %s as campo, li.id as lineamiento, e.etiqueta, tei.nombre_indicador FROM tablas_estadisticas_datos as td 
                inner join lineamientos as li on td.lineamientos_id = li.id
                inner join lineamientos_detalle_conjuntos as ldc on ldc.cod_lineamiento = li.id
                inner join lineamientos_conjuntos as lc on lc.id = ldc.cod_conjunto
                inner join evaluacion as e on e.id = td.cod_evaluacion
                inner join tablas_estadisticas te on td.lineamientos_id = te.rubro
                inner join tablas_estadisticas_indicadores tei on tei.cod_tablaestadistica = te.id                
                where tei.id = %s and e.id = %s order by td.anio asc", $campo, $ind, $evaluacion);
        
        //var_dump($sql);
        
        $indicadores_aux = BD::$db->queryAll($sql);
        $campos = array_map(function($e){
            return $e['campo'];
        }, $indicadores_aux);
        $anios = array_map(function($e){
            return $e['anio'];
        }, $indicadores_aux);
        $indicadores_arr[] = array(
            'campos' => implode(',',$campos),
            'anios' => implode(',',$anios),
            'etiqueta' => $indicadores_aux[0]['etiqueta'],
            'nombre_indicador' => $indicadores_aux[0]['nombre_indicador'],
            'indicador' => $ind
        );
        
        }
       
        

        
        $vars['indicadores'] = $indicadores_arr;
//        var_dump($indicadores_arr);

        View::add_js('public/lmcc/vendors/Highcharts-5.0.10/code/highcharts.js'); 
        View::add_js('public/lmcc/vendors/Highcharts-5.0.10/code/highcharts-more.js'); 
        View::add_js('public/lmcc/vendors/Highcharts-5.0.10/code/js/modules/exporting.js'); 
//        View::add_js('public/lmcc/vendors/Highcharts-5.0.10/modules/exporting.js'); 
        View::add_js('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js'); 
        View::add_js('modules/sievas/scripts/avances/tablas_estadisticas_graficas_pr.js'); 
        View::render('avances/tabla_graficas_estadisticas.php', $vars);
    }
    
    public function indicadores_data(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $indicadores = $_GET['i'];        
        $indicadores = explode('|', $indicadores);
        $indicadores_arr = array();
        $campo = '';

        foreach($indicadores as $ind){

        $sql_campo = sprintf("select campo from tablas_estadisticas_indicadores where id=%s", $ind);
        $campo = BD::$db->queryOne($sql_campo);
       
        $sql = sprintf("SELECT td.anio, %s as campo, li.id as lineamiento, e.etiqueta, tei.nombre_indicador FROM tablas_estadisticas_datos as td 
                inner join lineamientos as li on td.lineamientos_id = li.id
                inner join lineamientos_detalle_conjuntos as ldc on ldc.cod_lineamiento = li.id
                inner join lineamientos_conjuntos as lc on lc.id = ldc.cod_conjunto
                inner join evaluacion as e on e.id = td.cod_evaluacion
                inner join tablas_estadisticas te on td.lineamientos_id = te.rubro
                inner join tablas_estadisticas_indicadores tei on tei.cod_tablaestadistica = te.id                
                where tei.id = %s and e.id = %s order by td.anio asc", $campo, $ind, $evaluacion);
        
//         $sql = sprintf("SELECT td.anio, %s as campo, e.etiqueta, tei.nombre_indicador FROM tablas_estadisticas_datos as td 
//                inner join evaluacion as e on e.id = td.cod_evaluacion
//                inner join tablas_estadisticas_indicadores tei on tei.cod_tablaestadistica = te.id                
//                where tei.id = %s and e.id = %s order by td.anio asc", $campo, $ind, $evaluacion);
        
        
        $indicadores_aux = BD::$db->queryAll($sql);
        $campos = array_map(function($e){
            return $e['campo'];
        }, $indicadores_aux);
        $anios = array_map(function($e){
            return $e['anio'];
        }, $indicadores_aux);
        $indicadores_arr[] = array(
            'campos' => $campos,
            'anios' => $anios,
            'etiqueta' => $indicadores_aux[0]['etiqueta'],
            'nombre_indicador' => $indicadores_aux[0]['nombre_indicador'],
            'indicador' => $ind
        );
        }
        echo json_encode($indicadores_arr);
    }
    
    
    
    public function evaluaciones_anteriores(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_ev = sprintf("SELECT 
            etiqueta, ruta, nombre
        FROM
            evaluacion
        inner join
                gen_documentos on evaluacion.dictamen_id = gen_documentos.id
        WHERE
            evaluacion.id = (SELECT 
                    padre
                FROM
                    evaluacion
                WHERE
                    id = %s)", $evaluacion);
        $result = BD::$db->queryAll($sql_ev);
        $vars['anteriores'] = $result;
        View::render('avances/evaluaciones_anteriores.php', $vars);
    }
    
    public function get_data_comparativa_items(){
        header('Content-Type: application/json');  
        $evaluacion = Auth::info_usuario('evaluacion');  
        $ev_anterior = Auth::info_usuario('ev_anterior');
        $tooltips = array();
        $labels = array();
        $data = array(
            'einternaact' => array(),
            'eexternaact' => array(),
            'einternaant' => array(),
            'eexternaant' => array()
        );
        
        
        $items_sql = sprintf("select lineamientos.id, lineamientos.atributos_lineamiento, 
            lineamientos.num_orden, lineamientos.nom_lineamiento from lineamientos 
            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = lineamientos_detalle_conjuntos.cod_conjunto
            inner join evaluacion on lineamientos_conjuntos.id = evaluacion.cod_conjunto            
            where lineamientos.padre_lineamiento > 0 and evaluacion.id = %s order by id asc", $evaluacion);
        
        $items = BD::$db->queryAll($items_sql);
        $max = 0;
        foreach($items as $key=>$item){
            $labels[] = $item['atributos_lineamiento'].$item['num_orden'];
            $tooltips[] = $item['atributos_lineamiento'].$item['num_orden'].' '.$item['nom_lineamiento'];
            
//            $data['eexternaact'][] = 0;
//            $data['einternaant'][] = 0;
//            $data['eexternaant'][] = 0;
            //einterna actual
            $einternaact_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $evaluacion, 1, $item['id']);
            
            $tmp = BD::$db->queryOne($einternaact_sql);
            
            if($tmp >= 0){
                $data['einternaact'][] = (int)$tmp;
                if($tmp > $max){
                    $max = $tmp;
                }
            }
            else{
                $data['einternaact'][] = 0;
            }
            
            //eexterna actual
            $eexternaact_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $evaluacion, 2, $item['id']);
            
            $tmp = BD::$db->queryOne($eexternaact_sql);
            
            if($tmp >= 0){
                $data['eexternaact'][] = (int)$tmp;
                if($tmp > $max){
                    $max = $tmp;
                }
            }
            else{
                $data['eexternaact'][] = 0;
            }
            
            if($ev_anterior > 0){                
                //einterna anterior
                $einternaant_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                    inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                    inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                    inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                    inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                    inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                    where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $ev_anterior, 1, $item['id']);

                $tmp = BD::$db->queryOne($einternaant_sql);

                if($tmp >= 0){
                    $data['einternaant'][] = (int)$tmp;
                    if($tmp > $max){
                    $max = $tmp;
                    }
                }
                else{
                    $data['einternaant'][] = 0;
                }
                        
                //eexterna anterior
                $eexternaant_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                    inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                    inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                    inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                    inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                    inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                    where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $ev_anterior, 2, $item['id']);

                $tmp = BD::$db->queryOne($eexternaant_sql);

                if($tmp >= 0){
                    $data['eexternaant'][] = (int)$tmp;
                    if($tmp > $max){
                     $max = $tmp;
                    }
                }
                else{
                    $data['eexternaant'][] = 0;
                }
            }
            
            
            
        }
       
//       $data['einternaact'] = implode(',', $data['einternaact']);
//       $data['eexternaact'] = implode(',', $data['eexternaact']);
////       var_dump($data['eexternaact']);
//       $data['einternaant'] = implode(',', $data['einternaant']);
//       $data['eexternaant'] = implode(',', $data['eexternaant']);
       
//       $vars['data'] = $data;
//       $vars['max'] = $max;
////       var_dump(count($tooltips));
       $data['tooltips'] = $tooltips;
       $data['labels'] = $labels;
       echo json_encode($data);
    }
    
    public function reporte_linea_calificaciones_h(){
        View::add_js('public/js/Highcharts-5.0.10/code/highcharts.js'); 
        View::add_js('public/js/Highcharts-5.0.10/code/modules/data.js'); 
        View::add_js('public/js/Highcharts-5.0.10/code/modules/exporting.js'); 
        View::add_js('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js'); 
        View::add_js('modules/sievas/scripts/avances/linea_calificaciones_h.js'); 
        $vars['evaluacion'] =  Auth::info_usuario('evaluacion');  
        View::render('avances/reporte_linea_calificaciones_h.php', $vars);
    }
    
    public function reporte_linea_calificaciones(){
        $evaluacion = Auth::info_usuario('evaluacion');  
        $ev_anterior = Auth::info_usuario('ev_anterior');
        $tooltips = array();
        $labels = array();
        $data = array(
            'einternaact' => array(),
            'eexternaact' => array(),
            'einternaant' => array(),
            'eexternaant' => array()
        );
        
        
        $items_sql = sprintf("select lineamientos.id, lineamientos.atributos_lineamiento, 
            lineamientos.num_orden, lineamientos.nom_lineamiento from lineamientos 
            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = lineamientos_detalle_conjuntos.cod_conjunto
            inner join evaluacion on lineamientos_conjuntos.id = evaluacion.cod_conjunto            
            where lineamientos.padre_lineamiento > 0 and evaluacion.id = %s order by id asc", $evaluacion);
        
        $items = BD::$db->queryAll($items_sql);
        $max = 0;
        foreach($items as $key=>$item){
            $labels[] = $item['atributos_lineamiento'].$item['num_orden'];
            $tooltips[] = $item['atributos_lineamiento'].$item['num_orden'].' '.$item['nom_lineamiento'];
            
//            $data['eexternaact'][] = 0;
//            $data['einternaant'][] = 0;
//            $data['eexternaant'][] = 0;
            //einterna actual
            $einternaact_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $evaluacion, 1, $item['id']);
            
            $tmp = BD::$db->queryOne($einternaact_sql);
            
            if($tmp >= 0){
                $data['einternaact'][] = $tmp;
                if($tmp > $max){
                    $max = $tmp;
                }
            }
            else{
                $data['einternaact'][] = 0;
            }
            
            //eexterna actual
            $eexternaact_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $evaluacion, 2, $item['id']);
            
            $tmp = BD::$db->queryOne($eexternaact_sql);
            
            if($tmp >= 0){
                $data['eexternaact'][] = $tmp;
                if($tmp > $max){
                    $max = $tmp;
                }
            }
            else{
                $data['eexternaact'][] = 0;
            }
            
            if($ev_anterior > 0){                
                //einterna anterior
                $einternaant_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                    inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                    inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                    inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                    inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                    inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                    where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $ev_anterior, 1, $item['id']);

                $tmp = BD::$db->queryOne($einternaant_sql);

                if($tmp >= 0){
                    $data['einternaant'][] = $tmp;
                    if($tmp > $max){
                    $max = $tmp;
                    }
                }
                else{
                    $data['einternaant'][] = 0;
                }
                        
                //eexterna anterior
                $eexternaant_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
                    inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                    inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                    inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                    inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                    inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                    where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $ev_anterior, 2, $item['id']);

                $tmp = BD::$db->queryOne($eexternaant_sql);

                if($tmp >= 0){
                    $data['eexternaant'][] = $tmp;
                    if($tmp > $max){
                     $max = $tmp;
                    }
                }
                else{
                    $data['eexternaant'][] = 0;
                }
            }
            
            
            
        }
       
       $data['einternaact'] = implode(',', $data['einternaact']);
       $data['eexternaact'] = implode(',', $data['eexternaact']);
//       var_dump($data['eexternaact']);
       $data['einternaant'] = implode(',', $data['einternaant']);
       $data['eexternaant'] = implode(',', $data['eexternaant']);
       
       $vars['data'] = $data;
       $vars['max'] = $max;
//       var_dump(count($tooltips));
       $vars['tooltips'] = implode('&',$tooltips);
       $vars['labels'] = implode('&',$labels);
        
        View::add_js('public/js/RGraph/libraries/RGraph.common.core.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.dynamic.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.annotate.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.context.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.effects.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.key.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.resizing.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.tooltips.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.zoom.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.bar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.pie.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.radar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.line.js'); 
        View::add_js('modules/sievas/scripts/avances/linea_calificaciones.js'); 
        
        View::render('avances/reporte_linea_calificaciones.php', $vars);
    }
    
    public function tablas_estadisticas_graficas(){
        $evaluacion = Auth::info_usuario('evaluacion');  
//        $ev_anterior = Auth::info_usuario('ev_anterior');
//        $tooltips = array();
//        $labels = array();
//        $data = array(
//            'numegresados' => array()
//        );
//        
//        
//        $items_sql = sprintf("select lineamientos.id, lineamientos.atributos_lineamiento, 
//            lineamientos.num_orden, lineamientos.nom_lineamiento from lineamientos 
//            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
//            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = lineamientos_detalle_conjuntos.cod_conjunto
//            inner join evaluacion on lineamientos_conjuntos.id = evaluacion.cod_conjunto            
//            where lineamientos.padre_lineamiento > 0 and evaluacion.id = %s order by id asc", $evaluacion);
//        
//        $items = BD::$db->queryAll($items_sql);
//        $max = 0;
//        foreach($items as $key=>$item){
//            $labels[] = $item['atributos_lineamiento'].$item['num_orden'];
//            $tooltips[] = $item['atributos_lineamiento'].$item['num_orden'].' '.$item['nom_lineamiento'];
//            
////            $data['eexternaact'][] = 0;
////            $data['einternaant'][] = 0;
////            $data['eexternaant'][] = 0;
//            //einterna actual
//            $einternaact_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
//                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
//                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
//                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
//                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
//                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
//                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $evaluacion, 1, $item['id']);
//            
//            $tmp = BD::$db->queryOne($einternaact_sql);
//            
//            if($tmp >= 0){
//                $data['einternaact'][] = $tmp;
//                if($tmp > $max){
//                    $max = $tmp;
//                }
//            }
//            else{
//                $data['einternaact'][] = 0;
//            }
//            
//            //eexterna actual
//            $eexternaact_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
//                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
//                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
//                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
//                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
//                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
//                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $evaluacion, 2, $item['id']);
//            
//            $tmp = BD::$db->queryOne($eexternaact_sql);
//            
//            if($tmp >= 0){
//                $data['eexternaact'][] = $tmp;
//                if($tmp > $max){
//                    $max = $tmp;
//                }
//            }
//            else{
//                $data['eexternaact'][] = 0;
//            }
//            
//            if($ev_anterior > 0){                
//                //einterna anterior
//                $einternaant_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
//                    inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
//                    inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
//                    inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
//                    inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
//                    inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
//                    where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $ev_anterior, 1, $item['id']);
//
//                $tmp = BD::$db->queryOne($einternaant_sql);
//
//                if($tmp >= 0){
//                    $data['einternaant'][] = $tmp;
//                    if($tmp > $max){
//                    $max = $tmp;
//                    }
//                }
//                else{
//                    $data['einternaant'][] = 0;
//                }
//                        
//                //eexterna anterior
//                $eexternaant_sql = sprintf("select gradacion_escalas.valor_escala from evaluacion 
//                    inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
//                    inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
//                    inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
//                    inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
//                    inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
//                    where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.id=%s", $ev_anterior, 2, $item['id']);
//
//                $tmp = BD::$db->queryOne($eexternaant_sql);
//
//                if($tmp >= 0){
//                    $data['eexternaant'][] = $tmp;
//                    if($tmp > $max){
//                     $max = $tmp;
//                    }
//                }
//                else{
//                    $data['eexternaant'][] = 0;
//                }
//            }
//            
//            
//            
//        }
//       
//       $data['einternaact'] = implode(',', $data['einternaact']);
//       $data['eexternaact'] = implode(',', $data['eexternaact']);
////       var_dump($data['eexternaact']);
//       $data['einternaant'] = implode(',', $data['einternaant']);
//       $data['eexternaant'] = implode(',', $data['eexternaant']);
//       
//       $vars['data'] = $data;
//       $vars['max'] = $max;
////       var_dump(count($tooltips));
//       $vars['tooltips'] = implode('&',$tooltips);
//       $vars['labels'] = implode('&',$labels);
        
        View::add_js('public/js/RGraph/libraries/RGraph.common.core.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.dynamic.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.annotate.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.context.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.effects.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.key.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.resizing.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.tooltips.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.zoom.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.bar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.pie.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.radar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.line.js'); 
        View::add_js('modules/sievas/scripts/avances/tablas_estadisticas_graficas.js'); 
        
        View::render('avances/tablas_estadisticas_graficas.php', $vars);
    }
    
    public function get_data_grafica(){
        header('Content-Type: application/json');  
        $e = 0;
        $evaluacion = Auth::info_usuario('evaluacion');   
        $lineamiento = 1;
        $ev_arr = array();
        $json = array();
        $param = $_GET['param'];
        
        switch($param){
            case 'numero_egresados':
                $sql = sprintf("SELECT td.anio, td.campo_a, e.etiqueta FROM tablas_estadisticas_datos as td 
                inner join lineamientos as li on td.lineamientos_id = li.id
                inner join lineamientos_detalle_conjuntos as ldc on ldc.cod_lineamiento = li.id
                inner join lineamientos_conjuntos as lc on lc.id = ldc.cod_conjunto
                inner join evaluacion as e on e.id = td.cod_evaluacion
                where li.id = %s and e.id = %s order by td.anio asc", $lineamiento, $evaluacion);
                
//                var_dump($sql);


                $data = BD::$db->queryAll($sql);

                $valores = array_map(function($d){
                    return (int)$d['campo_a'];
                }, $data);
                $campos= array_map(function($d){
                    return $d['anio'];
                }, $data);

                $json['campos'] = $campos; 
                $json['valores'] = $valores; 
                $json['etiqueta'] = $data[0]['etiqueta']; 

                break;
            case 'egr_001':
                $sql = sprintf("SELECT td.anio, td.campo_b, e.etiqueta FROM tablas_estadisticas_datos as td 
                inner join lineamientos as li on td.lineamientos_id = li.id
                inner join lineamientos_detalle_conjuntos as ldc on ldc.cod_lineamiento = li.id
                inner join lineamientos_conjuntos as lc on lc.id = ldc.cod_conjunto
                inner join evaluacion as e on e.id = td.cod_evaluacion
                where li.id = %s and e.id = %s order by td.anio asc", $lineamiento, $evaluacion);


                $data = BD::$db->queryAll($sql);

                $valores = array_map(function($d){
                    return (int)$d['campo_b'];
                }, $data);
                $campos= array_map(function($d){
                    return $d['anio'];
                }, $data);

                $json['campos'] = $campos; 
                $json['valores'] = $valores; 
                $json['etiqueta'] = $data[0]['etiqueta']; 
                break;
            case 'egr_002':
                $sql = sprintf("SELECT td.anio, td.campo_c, e.etiqueta FROM tablas_estadisticas_datos as td 
                inner join lineamientos as li on td.lineamientos_id = li.id
                inner join lineamientos_detalle_conjuntos as ldc on ldc.cod_lineamiento = li.id
                inner join lineamientos_conjuntos as lc on lc.id = ldc.cod_conjunto
                inner join evaluacion as e on e.id = td.cod_evaluacion
                where li.id = %s and e.id = %s order by td.anio asc", $lineamiento, $evaluacion);


                $data = BD::$db->queryAll($sql);

                $valores = array_map(function($d){
                    return (int)$d['campo_c'];
                }, $data);
                $campos= array_map(function($d){
                    return $d['anio'];
                }, $data);

                $json['campos'] = $campos; 
                $json['valores'] = $valores; 
                $json['etiqueta'] = $data[0]['etiqueta']; 
                break;
            case 'egr_003':
                $sql = sprintf("SELECT td.anio, td.campo_d, e.etiqueta FROM tablas_estadisticas_datos as td 
                inner join lineamientos as li on td.lineamientos_id = li.id
                inner join lineamientos_detalle_conjuntos as ldc on ldc.cod_lineamiento = li.id
                inner join lineamientos_conjuntos as lc on lc.id = ldc.cod_conjunto
                inner join evaluacion as e on e.id = td.cod_evaluacion
                where li.id = %s and e.id = %s order by td.anio asc", $lineamiento, $evaluacion);


                $data = BD::$db->queryAll($sql);

                $valores = array_map(function($d){
                    return (int)$d['campo_d'];
                }, $data);
                $campos= array_map(function($d){
                    return $d['anio'];
                }, $data);

                $json['campos'] = $campos; 
                $json['valores'] = $valores; 
                $json['etiqueta'] = $data[0]['etiqueta']; 
                break;
        }
        
        
        

        echo json_encode($json);
    }
    
    public function reporte_historico_tablas_estadisticas(){
        $evaluacion = Auth::info_usuario('evaluacion');        
        
        View::add_js('public/js/RGraph/libraries/RGraph.common.core.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.dynamic.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.annotate.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.context.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.effects.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.key.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.resizing.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.tooltips.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.zoom.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.bar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.pie.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.radar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.line.js'); 
        View::add_js('modules/sievas/scripts/avances/historico_tablas.js'); 
        
        View::render('avances/reporte_historico_tablas_estadisticas.php', $vars);
    }
    
    public function reporte_tablas_estadisticas(){
        //viene de la sesion
        $evaluacion = Auth::info_usuario('evaluacion');
        $cod_momento = 1;
        $rol = Auth::info_usuario('rol');
        $momento_evaluacion = 0;
        if($evaluacion > 0){
            $sql_momento = sprintf("SELECT id FROM momento_evaluacion 
                                    WHERE cod_momento = %s AND cod_evaluacion = %s", 
                                    $cod_momento, $evaluacion);
            $momento_evaluacion = BD::$db->queryOne($sql_momento);
        }        
      
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.atributos_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        

        foreach($rubros as $k => $r){
            $sql_anexos = sprintf("SELECT d.fecha_creado, d.nombre, d.ruta, d.id FROM gen_documentos d
			INNER JOIN momento_resultado_anexo a ON (a.cod_documento = d.id)
			WHERE a.cod_momento_evaluacion = %s AND a.cod_lineamiento = %s
			ORDER BY d.fecha_creado DESC", 
			$momento_evaluacion, $r['id']);
            $anexos = BD::$db->queryAll($sql_anexos);
            
            
            $rubros[$k]['anexos'] = $anexos;
        }


        $vars['rubros'] = $rubros;
        View::render('avances/reporte_tablas_estadisticas.php', $vars);
    }
    
    
    public function tablas_estadisticas(){
        $vars = array();
        $evaluacion = Auth::info_usuario('evaluacion');
        
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.atributos_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        $vars['rubros'] = $rubros;
        
        View::add_js('modules/sievas/scripts/avances/tablas_estadisticas.js'); 
        View::render('avances/tablas_estadisticas.php', $vars);
    }
    
    private function plot_image($data){
         // Create the basic rtadar graph
        $graph = new RadarGraph(500,400);
        $graph->SetFrame(false);
        // Set background color and shadow
        $graph->SetColor("white");
        $graph->SetShadow();

        // Position the graph
        $graph->SetCenter(0.4,0.55);

        // Setup the axis formatting 	
        $graph->axis->SetFont(FF_FONT1,FS_BOLD);
        $graph->axis->SetWeight(2);

        // Setup the grid lines
        $graph->grid->SetLineStyle("longdashed");
        $graph->grid->SetColor("navy");
        $graph->grid->Show();
        $graph->HideTickMarks();

        // Setup graph titles
        $graph->title->SetFont(FF_FONT0,FS_BOLD);
        $graph->SetTitles(array("1","2","3","4","5","6","7","8","9","10"));
        // Create the first radar plot		
        $plot = new RadarPlot(array(10,10,10,10,10,10,10,10,10,10));
        $plot->SetColor("green","lightgreen");
        $plot->SetFillColor("green@0.5");
        $plot->SetLineWeight(2);
        // Create the first radar plot		
        $plot2 = new RadarPlot(array(2,2,2,2,2,2,2,2,2,2));
        $plot2->SetColor("red","lightred");
        $plot2->SetFillColor("red@1");
        $plot2->SetLineWeight(2);
        // Create the first radar plot		
        $plot4 = new RadarPlot(array(6,6,6,6,6,6,6,6,6,6));
        $plot4->SetColor("yellow","lightyellow");
        $plot4->SetFillColor("yellow@1");
        $plot4->SetLineWeight(2);

        // Create the second radar plot
        $plot3 = new RadarPlot($data);
        $plot3->SetColor("blue","lightred");
        $plot3->SetFillColor("blue@0.5");

        // Add the plots to the graph
        $graph->Add($plot);
        $graph->Add($plot3);
        $graph->Add($plot4);
        $graph->Add($plot2);

//        $graph->Stroke();
        // And output the graph
        ob_start();  
        $graph->stroke();
        $graphData = ob_get_contents();   
        ob_end_clean(); 
        
        return $graphData;
    }
    
    private function plot_image_2($data){
        // Create the basic rtadar graph
        $graph = new RadarGraph(500,400);
        $graph->SetFrame(false);
        // Set background color and shadow
        $graph->SetColor("white");
        $graph->SetShadow();

        // Position the graph
        $graph->SetCenter(0.4,0.55);

        // Setup the axis formatting     
        $graph->axis->SetFont(FF_FONT1,FS_BOLD);
        $graph->axis->SetWeight(2);

        // Setup the grid lines
        $graph->grid->SetLineStyle("longdashed");
        $graph->grid->SetColor("navy");
        $graph->grid->Show();
        $graph->HideTickMarks();

        // Setup graph titles
//        $graph->title->Set("Quality result");
        $graph->SetTitles(array("1","2","3","4","5","6","7","8","9","10"));
        $graph->title->SetFont(FF_FONT1,FS_BOLD);
//        $graph->SetTitles(array("One","Two","Three","Four","Five","Sex","Seven","Eight","Nine","Ten"));
        // Create the first radar plot        
        $plot = new RadarPlot(array(10,10,10,10,10,10,10,10,10,10));
        $plot->SetColor("green","lightgreen");
        $plot->SetFillColor("green@0.5");
        $plot->SetLineWeight(2);

        // Create the second radar plot
        $plot2 = new RadarPlot(array(2,2,2,2,2,2,2,2,2,2));
        $plot2->SetColor("red","lightred");
        $plot2->SetFillColor("red@1");
        $plot2->SetLineWeight(2);
        
        $plot3 = new RadarPlot(array(6,6,6,6,6,6,6,6,6,6));
        $plot3->SetColor("yellow","lightyellow");
        $plot3->SetFillColor("yellow@1");
        $plot3->SetLineWeight(2);
        
        $plot4 = new RadarPlot($data);
        $plot4->SetColor("blue","lightred");
        $plot4->SetFillColor("blue@0.5");

        // Add the plots to the graph
        $graph->Add($plot);
        $graph->Add($plot4);
        $graph->Add($plot2);
        $graph->Add($plot3);
        
        

        // And output the graph
//        header('Content-Type:'.$type);
//        header('Content-Length: ' . filesize($file));
        $type = 'image/png';
        header('Content-Type:'.$type);
//        $graph->img->Headers();
        $graph->Stroke();
    }
    
    private function plot_file_2($data){
         // Create the basic rtadar graph
        $graph = new RadarGraph(500,400);
        $graph->SetFrame(false);
        // Set background color and shadow
        $graph->SetColor("white");
        $graph->SetShadow();

        // Position the graph
        $graph->SetCenter(0.4,0.55);

        // Setup the axis formatting 	
        $graph->axis->SetFont(FF_FONT1,FS_BOLD);
        $graph->axis->SetWeight(2);

        // Setup the grid lines
        $graph->grid->SetLineStyle("longdashed");
        $graph->grid->SetColor("navy");
        $graph->grid->Show();
        $graph->HideTickMarks();

        // Setup graph titles
        $graph->title->SetFont(FF_FONT0,FS_BOLD);
        $graph->SetTitles(array("1","2","3","4","5","6","7","8","9","10"));
        // Create the first radar plot		
        $plot = new RadarPlot(array(10,10,10,10,10,10,10,10,10,10));
        $plot->SetColor("green","lightgreen");
        $plot->SetFillColor("green@0.5");
        $plot->SetLineWeight(2);
        // Create the first radar plot		
        $plot2 = new RadarPlot(array(2,2,2,2,2,2,2,2,2,2));
        $plot2->SetColor("red","lightred");
        $plot2->SetFillColor("red@1");
        $plot2->SetLineWeight(2);
        // Create the first radar plot		
        $plot4 = new RadarPlot(array(6,6,6,6,6,6,6,6,6,6));
        $plot4->SetColor("yellow","lightyellow");
        $plot4->SetFillColor("yellow@1");
        $plot4->SetLineWeight(2);

        // Create the second radar plot
        $plot3 = new RadarPlot($data);
        $plot3->SetColor("blue","lightred");
        $plot3->SetFillColor("blue@0.5");

        // Add the plots to the graph
        $graph->Add($plot);
        $graph->Add($plot3);
        $graph->Add($plot4);
        $graph->Add($plot2);

        date_default_timezone_set('America/Bogota');
        $now = new DateTime();
        $rel_path = 'files/plots/graph-'.$now->getTimestamp().rand('11111111111','99999999999').'.jpg'; 
        $graph->Stroke(PUBLIC_PATH.$rel_path);
        var_dump(PUBLIC_PATH.$rel_path);
        return '/'.$rel_path;

    }
    
    private function plot_file($data){
        date_default_timezone_set('America/Bogota');
        $now = new DateTime();
//        var_dump($now->getTimestamp());
        $rel_path = 'files/plots/graph-'.$now->getTimestamp().rand('11111111111','99999999999').'.jpg';
        file_put_contents(PUBLIC_PATH.$rel_path, $this->plot_image($data));
        return PUBLIC_PATH.$rel_path;
    }
    
    public function generar_radar(){
        $rubro = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
        $evaluacion = Auth::info_usuario('evaluacion');
        $momento_actual = $this->get_momento_actual();
        $momento = $momento_actual['cod_momento'];
        if($momento == null){
            if($_GET['momento'] > 0){
                $momento = $_GET['momento'];
            }
            else{
                $momento = 1;
            }
        }
        //verificar si es rubro
        //verificar si tiene minimo 3 datos 
        
        
        $sql_p1 = sprintf("create temporary table if not exists tmp_grafindicametros as (SELECT
        lineamientos.id, 0 as valor_escala
        FROM
        lineamientos
        WHERE
        lineamientos.padre_lineamiento = %s order by num_orden)", $rubro); 
        $sql_p2 = "ALTER TABLE tmp_grafindicametros ADD PRIMARY KEY(id)";
      
        $sql_p3 = sprintf(" insert into tmp_grafindicametros(id, valor_escala) 
        SELECT
                lineamientos.id,
                gradacion_escalas.valor_escala
        FROM
        evaluacion
        INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
        LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
        LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
        LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
        WHERE
                evaluacion.id = %s
        AND momento_evaluacion.cod_momento = %s
        AND lineamientos.padre_lineamiento = %s
        ON DUPLICATE KEY UPDATE valor_escala=VALUES(valor_escala);", $evaluacion, $momento, $rubro);
        
        $sql = "select valor_escala from tmp_grafindicametros";
        
        BD::$db->query($sql_p1);
        BD::$db->query($sql_p2);
        BD::$db->query($sql_p3);
        
        $data = BD::$db->queryAll($sql);
        
        $data = array_map(function($i){
            return $i['valor_escala'];
        }, $data);
        
//        var_dump($data);
//        
//        var_dump($sql_p1);
//        var_dump($sql_p2);
//        var_dump($sql_p3);
//        var_dump($sql);
//        
        $this->plot_image_2($data);
    }
    
    public function reporte_evaluacion_pdf(){                                                                                                                                       
                                                                                                                                                                               
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('GRANA');
            $pdf->SetTitle('Resumen de evaluación');
            $pdf->SetSubject('Resumen de evaluación');
            $pdf->SetKeywords('Evaluacion,Resumen');

        $pdf->SetHeaderData('header.png', PDF_HEADER_LOGO_WIDTH+170, '', '', array(255,255,255), array(255,255,255));
        $pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP+17, PDF_MARGIN_RIGHT+10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
//            $pdf->setPrintHeader(true);
//            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
//            $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
////            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
//            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            $pdf->SetFont('helvetica', '', 10);

            // add a page
            $pdf->AddPage();

            // create some HTML content
//            $html = '<table width="100%">
//                    <tr>
//                        <td width="25%"><img src="public/img/logos/logog.png" class="print"></td>
//                        <td width="50%" align="center"><br/><p style="font-size: 20px; font-weight:bold">Resumen de evaluación</p></td>
//                        <td width="25%"><img src="public/img/logos/oui.png" class="print"></td>
//                    </tr>
//                </table>';
            
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $momento = $momento_actual["cod_momento"];
        $rol = Auth::info_usuario('rol');
        if($rol == 1 || $momento == '2'){
            $momento = $_GET['momento'];
            if($_GET['momento'] == null)
                $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        foreach($rubros as $key=>$r){
            $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s  order by num_orden asc", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            gradacion_escalas.desc_escala,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    Auth::info_usuario('evaluacion'), 
                    $momento_evaluacion);
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            
            $calificaciones_rubro = array();
            foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                              $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                              $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                              $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                              $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                              $calificaciones_rubro[] = $ld['valor_escala'];
                        }
                    }
                }                
            }
            
            $rubros[$key]['lineamientos'] = $lineamientos;           
            $rubros[$key]['calificaciones_rubro'] = $calificaciones_rubro;  
            
            foreach($lineamientos as $idx=>$l){
                 $sql_anexos = sprintf('SELECT
                gen_documentos.ruta,
                gen_documentos.id,
                gen_documentos.nombre
                FROM
                gen_documentos
                INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                WHERE
                lineamientos.id = %s
                AND momento_evaluacion.id = %s', $l['lineamiento_id'], $momento_evaluacion);
                
                 $anexos = BD::$db->queryAll($sql_anexos);
                 $lineamientos[$idx]['anexos'] = $anexos;
            }
           $rubros[$key]['lineamientos'] = $lineamientos;           
          
        }
        
        
        
        $vars['rubros'] = $rubros;
            $html .= '<h1>Reporte general de la Evaluación '.($momento == 1 ? 'Interna': 'Externa').'</h1><br/>';
            $i = 1;
            foreach ($rubros as $r){ 
//                var_dump($r['calificaciones_rubro']);
            $grafindicametro_url = $this->plot_file($r['calificaciones_rubro']);
//            $html .= '<table class="table">';
//            $html .=   '<thead>';
//            $html .=     '<tr>';
//            if($i > 7){
            $html .=         '<p>RUBRO '.$i.". ".$r['nom_lineamiento'].'</p>';
//            $html .=     '</tr>';
//            $html .=   '</thead>';

//            $html .= '<tbody>';
            $c = 1;
            $html .=         '<table class="table" style="width:100%">';
            foreach($r['lineamientos'] as $l){ 
            $html .=         '<tr>';
            $html .=             '<td style="background:#dfa5a9;">';
            $html .=                                ' <strong>'.$i.'.'.$c.'. '. $l[nom_lineamiento].'</strong><br/></td>';
            $html .=                 '</tr>';

            $html .=         '<tr>';
            $html .=         '<td>';                   
            
            
            $html .=              '<br><strong>Fortalezas</strong>';
            $html .=              ($l['fortalezas'] == null ? 'N/A' : str_replace('<br>','',urldecode($l['fortalezas'])));


            $html .=             '<br><strong>Debilidades</strong>';
            $html .=             ($l['debilidades'] == null ? 'N/A' : str_replace('<br>','',urldecode($l['debilidades']))); 


            $html .=            '<br><strong>Plan de mejoramiento</strong>';
            $html .=            ($l['plan_mejoramiento'] == null ? 'N/A' : 
                                            str_replace('<br>','',urldecode($l['plan_mejoramiento'])));
            
            $html .=              '<br><strong>Calificación: </strong>';
            $html .=              ($l['fortalezas'] == null ? 'N/A' : $l['desc_escala']);
//          
           

            $html .=         '<br/></td>';
            $html .=         '</tr>';
                    $c++;
                    } 
            $html .= '</tbody>';
            $html .= '</table>';
            
             $html .=            '<br><strong>Grafindicámetro de rubro</strong>';
            $html .=            '<br><img src="'.$grafindicametro_url.'">';
//            }
            $i++; 
            } 
        }
            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');

//                var_dump($html);
//            echo $html;

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');                              
    }

    
    public function evaluado(){
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $usuario = Auth::info_usuario('usuario');

        if($evaluacion_actual > 0){
            $sql_tipo_evaluado = "select tipo_evaluado,cod_evaluado from evaluacion where id=$evaluacion_actual";
            $rs = BD::$db->queryRow($sql_tipo_evaluado);

            $tipo_evaluado = $rs['tipo_evaluado'];
            $id = $rs['cod_evaluado'];
            $evaluado = array();

            switch($tipo_evaluado){
                case "1":
                    $sql_evaluado = "SELECT = $id";                       
                    $evaluado['institucion'] = BD::$db->queryRow($sql_evaluado);
                    break;

                case "2":

                    $sql_evaluado = "SELECT = $id"; 
                    $evaluado['programa'] = BD::$db->queryRow($sql_evaluado);

                    $sql_evaluado = "SELECT = ".$evaluado['programa']['cod_institucion'];   
                    $evaluado['institucion'] = BD::$db->queryRow($sql_evaluado);
                    break;            
            }        

            $sql_comite = "SELECT = $evaluacion_actual";
            $comite = BD::$db->queryAll($sql_comite);         

            $_comite = array();
            foreach($comite as $c){
                $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
            }

            $evaluado['comite'] = $_comite;   
            $vars['evaluado'] = $evaluado;

            View::render('evaluaciones/evaluado.php',$vars);
        }     
    }   
    
    public function calificaciones_evaluacion_graficas(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
         $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
        if($rol == 1 || $momento_actual["cod_momento"] == '2'){
            $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        foreach($rubros as $key=>$r){
            $avg = 0;
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            $sql_lineamientos_calificacion = sprintf("SELECT
            gradacion_escalas.valor_escala,
            lineamientos.id,
            lineamientos.padre_lineamiento,
            momento_resultado_detalle.activo
            FROM
            momento_resultado_detalle
            left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            WHERE
            momento_evaluacion.id = %s", $momento_evaluacion);
            
            $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
            $inactivos = 0;
             foreach($calificaciones as $c){
                if($r['id'] == $c['padre_lineamiento']){                    
                    foreach($lineamientos as $k=>$l){                        
                        if($l['lineamiento_id'] === $c['id']){
                              if($c['activo'] > 0){
                                    $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                    $rubros[$key]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);                                    
                                    
                                    $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                              }  
                              else{
                                    $lineamientos[$k]['valor'] = 0;
                                    $rubros[$key]['calificaciones'][] = 0;
                                    $inactivos++;
                              }
                              
                        }
                    }
                }
            }

           $rubros[$key]['lineamientos'] = $lineamientos;           
           $rubros[$key]['promedio'] = $avg/(10-$inactivos);           
           
        }
        
        //grafico general
        $sql_calificaciones = sprintf("
                select s1.lineamiento_id, s2.calificacion, s1.nom_lineamiento
                from (
                        SELECT
                                e.id,
                                e.etiqueta,
                                l.id as lineamiento_id,
                                l.nom_lineamiento as nom_lineamiento,
                                l.num_orden
                                FROM
                                evaluacion as e
                                INNER JOIN lineamientos_conjuntos as lc ON e.cod_conjunto = lc.id
                                INNER JOIN lineamientos_detalle_conjuntos as ldc ON ldc.cod_conjunto = lc.id
                                INNER JOIN lineamientos as l ON ldc.cod_lineamiento = l.id
                                WHERE
                                e.id = %s AND
                                l.padre_lineamiento = 0
                ) as s1
                left join (
                SELECT
                Avg(momento_resultado_detalle.cod_gradacion_escala) as calificacion,
                lineamientos.padre_lineamiento as padre,
                evaluacion.id
                FROM
                lineamientos
                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                LEFT JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                inner JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                inner JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                evaluacion.id = %s
                AND
                momento_evaluacion.cod_momento = %s
                GROUP BY
                lineamientos.padre_lineamiento
                ) as s2 on s2.padre = s1.lineamiento_id order by s1.num_orden", $evaluacion, $evaluacion, $momento);
            
            $calificaciones = BD::$db->queryAll($sql_calificaciones);
            
            

            $nom_rubros = array_map(function($e){
                return $e['nom_lineamiento'];                
            }, $calificaciones);
            $calificaciones_generales = array_map(function($e){
                return $e['calificacion'] == null ? '1' : $e['calificacion'];                
            }, $calificaciones);
//            var_dump($sql_calificaciones);
            
            $vars['nom_rubros'] = $nom_rubros;
            $vars['calificaciones_generales'] = $calificaciones_generales;
        
        $vars['rubros'] = $rubros;
//        var_dump($rubros);
        View::add_js('public/js/RGraph/libraries/RGraph.common.core.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.dynamic.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.annotate.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.context.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.effects.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.key.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.resizing.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.tooltips.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.zoom.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.bar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.pie.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.radar.js'); 
        View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/calificaciones_evaluacion_graficas.js'); 
        View::render('avances/reporte_graficas_calificacion.php', $vars);
         }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function get_data_grafindicametro(){
        header('Content-Type: application/json');
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
         $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
        if($rol == 1 || $momento_actual["cod_momento"] == '2'){
            $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        if($momento == null || (!($momento > 0))){
            $momento = $momento_actual["cod_momento"];
        }
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        foreach($rubros as $key=>$r){
            $avg = 0;
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s  order by lineamientos.num_orden", $r['id']);
             
//             if($r['id'] == 12)
//             var_dump($sql_lineamientos);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            $sql_lineamientos_calificacion = sprintf("SELECT
            gradacion_escalas.valor_escala,
            lineamientos.id,
            momento_resultado_detalle.activo,
            lineamientos.padre_lineamiento
            FROM
            momento_resultado_detalle
            left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            WHERE
            momento_evaluacion.id = %s order by lineamientos.num_orden", $momento_evaluacion);
            
            $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
            $inactivos = 0;
             foreach($calificaciones as $c){
                if($r['id'] == $c['padre_lineamiento']){                    
                    foreach($lineamientos as $k=>$l){                        
                        if($l['lineamiento_id'] === $c['id']){
//                              var_dump($c['valor_escala']);
                            if($c['activo'] > 0){
                                $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                $rubros[$key]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                            }
                            else{
                                $lineamientos[$k]['valor'] = 0;
                                $rubros[$key]['calificaciones'][] = 0;
                                $inactivos++;
                            }
                              
                        }
                    }
                }
            }

           $rubros[$key]['lineamientos'] = $lineamientos;           
           $rubros[$key]['promedio'] = $avg/(10-$inactivos);           
           
        }
        
        //grafico general
        $sql_calificaciones = sprintf("
                select s1.lineamiento_id, s2.calificacion, s1.nom_lineamiento
                from (
                        SELECT
                                e.id,
                                e.etiqueta,
                                l.id as lineamiento_id,
                                l.nom_lineamiento as nom_lineamiento,
                                l.num_orden
                                FROM
                                evaluacion as e
                                INNER JOIN lineamientos_conjuntos as lc ON e.cod_conjunto = lc.id
                                INNER JOIN lineamientos_detalle_conjuntos as ldc ON ldc.cod_conjunto = lc.id
                                INNER JOIN lineamientos as l ON ldc.cod_lineamiento = l.id
                                WHERE
                                e.id = %s AND
                                l.padre_lineamiento = 0
                ) as s1
                left join (
                SELECT
                Avg(gradacion_escalas.valor_escala) as calificacion,
                lineamientos.padre_lineamiento as padre,
                evaluacion.id
                FROM
                lineamientos
                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                LEFT JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                inner JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                inner JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                evaluacion.id = %s
                AND
                momento_evaluacion.cod_momento = %s
                and
                momento_resultado_detalle.activo = 1
                and
                gradacion_escalas.valor_escala > 0
                GROUP BY
                lineamientos.padre_lineamiento
                ) as s2 on s2.padre = s1.lineamiento_id order by s1.num_orden", $evaluacion, $evaluacion, $momento);
            
            $calificaciones = BD::$db->queryAll($sql_calificaciones);
            
            var_dump($calificaciones);
//
            $rubros = array_map(function($e){
                return $e['calificaciones'];                
            }, $rubros);
            $calificaciones_generales = array_map(function($e){                
                return $e['calificacion'] == null ? '0' : round($e['calificacion'],1);                
            }, $calificaciones);
//            var_dump($sql_calificaciones);
            
//            $vars['nom_rubros'] = $nom_rubros;
            $json = array();
            $json['rubros'] = $rubros;
            $json['calificaciones_generales'] = $calificaciones_generales;
        
//        $vars['rubros'] = $rubros;
            echo json_encode($json);
  
         }
    }

    
    public function get_calificaciones_rubro(){
        header('Content-Type: application/json');
        $rubro_id = filter_input(INPUT_GET, 'rubro', FILTER_SANITIZE_NUMBER_INT);
        $calificaciones = array();
        $items = array();
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
        if ($rol == 1) {
            $momento = $_GET['momento'];
            if ($_GET['momento'] == null)
                $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if ($evaluacion > 0) {
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $rubro_id);
       
        $lineamientos = BD::$db->queryAll($sql_lineamientos);

        foreach($lineamientos as $key=>$l){
                $sql_lineamientos_calificacion = sprintf("SELECT
                gradacion_escalas.valor_escala
                FROM
                momento_resultado_detalle
                INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                WHERE
                momento_evaluacion.id = %s and lineamientos.id = %s", $momento_evaluacion, $l['lineamiento_id']);

                $calificacion = BD::$db->queryOne($sql_lineamientos_calificacion);    
                $items[] = ($key+1).'. '.$l['nom_lineamiento'];
                if($calificacion > 0){
                    $calificaciones[] = $calificacion;
                }
                else{
                    $calificaciones[] = 0;
                }
            }
            
          echo json_encode(array('calificaciones'=>$calificaciones, 'items'=>$items));       
    }
    
    public function get_items_rubro(){
        header('Content-Type: application/json');
        $rubro_id = filter_input(INPUT_GET, 'rubro', FILTER_SANITIZE_NUMBER_INT);
        $items = array();
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
        if ($rol == 1) {
            $momento = $_GET['momento'];
            if ($_GET['momento'] == null)
                $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if ($evaluacion > 0) {
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $rubro_id);
       
        $lineamientos = BD::$db->queryAll($sql_lineamientos);
        
        foreach($lineamientos as $key=>$l){
            
        }
        
        echo json_encode($items);
    }
    
    public function get_rubros(){
        header('Content-Type: application/json');
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
        if ($rol == 1 || $momento_actual["cod_momento"] == '2'  || $momento_actual["cod_momento"] == '1') {
            $momento = $_GET['momento'];
            if ($_GET['momento'] == null)
                $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if ($evaluacion > 0) {
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        $sql_lineamientos = sprintf("SELECT
                lineamientos.id,
                lineamientos.nom_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = %s AND
        evaluacion.id = %s", 0, $evaluacion);
        
//        var_dump($sql_lineamientos);

        $lineamientos = BD::$db->queryAll($sql_lineamientos);
        echo json_encode($lineamientos);
    }
    
    public function get_data_calificaciones_evaluacion(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        foreach($rubros as $key=>$r){
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            $sql_lineamientos_calificaciones = sprintf("SELECT
            momento_evaluacion.cod_momento,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento,
            lineamientos.id
            FROM
            momento_resultado_detalle
            INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            where evaluacion.id=".Auth::info_usuario('evaluacion'));
            
            $calificaciones = BD::$db->queryAll($sql_lineamientos_calificaciones);
            $promedios = array();
            foreach($calificaciones as $c){
                if($r['id'] == $c['padre_lineamiento']){                    
                    foreach($lineamientos as $k=>$l){                        
                        if($l['lineamiento_id'] === $c['id']){
                              
                              $lineamientos[$k]['calificaciones'][$c['cod_momento']]['valor'] = $c['valor_escala'];
                        }
                    }
                }
//                $lineamientos['calificaciones'][$c['cod_momento']]['promedio'] = $avg;
            }
            
            $rubros[$key]['lineamientos'] = $lineamientos;
           
        }
        $vars['rubros'] = $rubros;
        $vars['e_id'] = $evaluacion;
        $vars['evaluaciones_esp'] = array(
            46 => 1,
            47 => 1,
            48 => 1,
            49 => 1,
            50 => 1,
            51 => 1,
            52 => 1,
            77 => 1,
            87 => 1,
            88 => 1,
            89 => 1,
            107 => 1,
            108 => 1,
            109 => 1,
            110 => 1,
            111 => 1,
            112 => 1
        );
        
       
        
        $vars['rol'] = $rol;
        $vars['momento'] = $momento_actual['cod_momento'];
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/avances_evaluacion.js'); 
        View::render('avances/reporte_calificacion.php', $vars);
         }
    }
    
    public function get_data_calificaciones_evaluacion_res(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        foreach($rubros as $key=>$r){
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            $sql_lineamientos_calificaciones = sprintf("SELECT
            momento_evaluacion.cod_momento,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento,
            lineamientos.id
            FROM
            momento_resultado_detalle
            INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            where evaluacion.id=".Auth::info_usuario('evaluacion'));
            
            $calificaciones = BD::$db->queryAll($sql_lineamientos_calificaciones);
            $promedios = array();
            foreach($calificaciones as $c){
                if($r['id'] == $c['padre_lineamiento']){                    
                    foreach($lineamientos as $k=>$l){                        
                        if($l['lineamiento_id'] === $c['id']){                              
                              $lineamientos[$k]['calificaciones'][$c['cod_momento']]['valor'] = $c['valor_escala'];
                        }
                    }
                }
//                $lineamientos['calificaciones'][$c['cod_momento']]['promedio'] = $avg;
            }
            
            $rubros[$key]['lineamientos'] = $lineamientos;
           
        }
        echo json_encode($rubros);
        }
    }
    
    public function calificaciones_evaluacion(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $momento_actual = $this->get_momento_actual();
        $momento_evaluacion = $momento_actual["momento_id"];
        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $momento = $_GET['momento'];
            if($_GET['momento'] == null)
            $momento = 1;
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        foreach($rubros as $key=>$r){
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            $sql_lineamientos_calificaciones = sprintf("SELECT
            momento_evaluacion.cod_momento,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento,
            lineamientos.id
            FROM
            momento_resultado_detalle
            INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            where evaluacion.id=".Auth::info_usuario('evaluacion'));
            
            $calificaciones = BD::$db->queryAll($sql_lineamientos_calificaciones);
            $promedios = array();
            foreach($calificaciones as $c){
                if($r['id'] == $c['padre_lineamiento']){                    
                    foreach($lineamientos as $k=>$l){                        
                        if($l['lineamiento_id'] === $c['id']){
                              
                              $lineamientos[$k]['calificaciones'][$c['cod_momento']]['valor'] = $c['valor_escala'];
                        }
                    }
                }
//                $lineamientos['calificaciones'][$c['cod_momento']]['promedio'] = $avg;
            }
            
            $rubros[$key]['lineamientos'] = $lineamientos;
           
        }
        $vars['rubros'] = $rubros;
        $vars['e_id'] = $evaluacion;
        $vars['evaluaciones_esp'] = array(
            46 => 1,
            47 => 1,
            48 => 1,
            49 => 1,
            50 => 1,
            51 => 1,
            52 => 1,
            77 => 1,
            87 => 1,
            88 => 1,
            89 => 1,
            107 => 1,
            108 => 1,
            109 => 1,
            110 => 1,
            111 => 1,
            112 => 1
        );
        
       
        
        $vars['rol'] = $rol;
        $vars['momento'] = $momento_actual['cod_momento'];
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/avances_evaluacion.js'); 
        View::render('avances/reporte_calificacion.php', $vars);
         }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function get_momento_actual(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $username = Auth::info_usuario('usuario');
        $sql = "SELECT
        comite.cod_persona,
        evaluacion.id,
        comite.cod_momento_evaluacion,
        comite.cod_cargo,
        momento_evaluacion.cod_momento,
        momento_evaluacion.id as momento_id
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        where evaluacion.id=$evaluacion and sys_usuario.username='$username'";
        
        return BD::$db->queryRow($sql);
    }


    public function get_momento_anterior(){
        $evaluacion = Auth::info_usuario('ev_anterior');
        $username = Auth::info_usuario('usuario');
        $sql = "SELECT
        comite.cod_persona,
        evaluacion.id,
        comite.cod_momento_evaluacion,
        comite.cod_cargo,
        momento_evaluacion.cod_momento,
        momento_evaluacion.id as momento_id
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        where evaluacion.id=$evaluacion and sys_usuario.username='$username'";
        
        return BD::$db->queryRow($sql);
    }
    
    public function avances_evaluacion_old(){ 
        //58
        //2
        $evaluacion = Auth::info_usuario('evaluacion');        
        $rol = Auth::info_usuario('rol');
        
        
        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }
        if($evaluacion > 0){
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                
               
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento = $_GET['momento'];
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                
                
                
//                var_dump($momento_actual["cod_momento"]);
//                var_dump($rol);
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){                    
                    
                    if($_GET['momento'] == null)
                    $momento = 1;
                    
                    $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                    $momento_evaluacion = BD::$db->queryOne($sql_momento);
                }
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=avances_evaluacion&momento=1');
                    }
                }
            }        

        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
       
        
        
        foreach($rubros as $key=>$r){
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            gradacion_escalas.desc_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    $evaluacion, 
                    $momento_evaluacion);
            
//            var_dump($sql_lineamientos_data);
            
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            

            foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                            //validacion                            
                            $sql = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    1, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);    
                            
                            if($ld['id'] == 4){
//                                var_dump($sql);
                            }
                            
                            $revision = BD::$db->queryRow($sql);
                            
                             $sql_retro = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    2, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);
                             
                             $retroalimentacion = BD::$db->queryRow($sql_retro);

                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            $lineamientos[$k]['validacion'] = $revision['validez'];
                            $lineamientos[$k]['retroalimentacion'] = $retroalimentacion['validez'];
                        }
                    }
                }                
            }


           $rubros[$key]['lineamientos'] = $lineamientos;           
           foreach($rubros[$key]['lineamientos'] as $i=>$val){
                $sql_anexos = sprintf('SELECT
                lineamientos.id
                FROM
                lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_lineamiento = lineamientos.id 
                AND momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
                WHERE
                lineamientos.id = %s
                AND evaluacion.id = %s
                AND momento_evaluacion.id = %s', $val['lineamiento_id'],$evaluacion, $momento_evaluacion);
                $rubros[$key]['lineamientos'][$i]['anexos'] = BD::$db->queryAll($sql_anexos);
           }
           
        }
        $vars['rubros'] = $rubros;
        $vars['rol'] = $rol;

        
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/avances_evaluacion.js'); 
        View::render('avances/reporte_avance.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function avances_evaluacion(){ 
        $evaluacion = Auth::info_usuario('evaluacion');        
        $rol = Auth::info_usuario('rol');
        
//        var_dump(memory_get_usage());
        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }
        if($evaluacion > 0){
//            var_dump(memory_get_usage());
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                
               
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento = $_GET['momento'];
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                
                
                
//                var_dump($momento_actual["cod_momento"]);
//                var_dump($rol);
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){                    
                    
                    if($_GET['momento'] == null)
                    $momento = 1;
                    
                    $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                    $momento_evaluacion = BD::$db->queryOne($sql_momento);
                }
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=avances_evaluacion&momento=1');
                    }
                }
            }        

        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
       
//        var_dump(memory_get_usage());
        
        foreach($rubros as $key=>$r){
//            var_dump(memory_get_usage());
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s  order by num_orden asc", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            lineamientos.id AS lineamiento_id,
            lineamientos.nom_lineamiento,
            length(momento_resultado_detalle.fortalezas) as fortalezas,
            length(momento_resultado_detalle.debilidades) as debilidades,
            length(momento_resultado_detalle.plan_mejoramiento) as plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    $evaluacion, 
                    $momento_evaluacion);
            
//            var_dump($sql_lineamientos);
            
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            if(count($lineamientos_data) > 0){
                foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                            //validacion                            
                            $sql = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    1, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);    
                            
                            
                            $revision = BD::$db->queryRow($sql);
                            
                             $sql_retro = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    2, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);
                             
                             $retroalimentacion = BD::$db->queryRow($sql_retro);

                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            $lineamientos[$k]['validacion'] = $revision['validez'];
                            $lineamientos[$k]['retroalimentacion'] = $retroalimentacion['validez'];
                        }
                    }
                }  
                $rubros[$key]['lineamientos'] = $lineamientos; 
//                var_dump($lineamientos_data);
           
                foreach($rubros[$key]['lineamientos'] as $i=>$val){
                     $sql_anexos = sprintf('SELECT
                     count(*)
                     FROM
                     lineamientos
                     INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                     INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                     INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                     INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                     INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                     INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_lineamiento = lineamientos.id 
                     AND momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                     INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
                     WHERE
                     lineamientos.id = %s
                     AND evaluacion.id = %s
                     AND momento_evaluacion.id = %s', $val['lineamiento_id'],$evaluacion, $momento_evaluacion);
                     $rubros[$key]['lineamientos'][$i]['anexos'] = BD::$db->queryOne($sql_anexos);
                }
            }
            }
            else{
                $rubros[$key]['lineamientos'] = $lineamientos;
            }
           
            
        }
           $vars['rubros'] = $rubros;
           $vars['rol'] = $rol;

            
            View::add_js('modules/sievas/scripts/avances/main.js'); 
            View::add_js('modules/sievas/scripts/avances/avances_evaluacion.js'); 
            View::render('avances/reporte_avance.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }


    public function avances_evaluacion_n(){ 
        $evaluacion = Auth::info_usuario('evaluacion');   
        $evaluacion_anterior = Auth::info_usuario('ev_anterior');      
        $rol = Auth::info_usuario('rol');
        
//        var_dump(memory_get_usage());
        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }

        if($evaluacion > 0){
//            var_dump(memory_get_usage());
            
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                
               
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);

                $sql_momento_anterior = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion_anterior);
                $momento_evaluacion_anterior = BD::$db->queryOne($sql_momento_anterior);
            }
            else{
                $momento = $_GET['momento'];
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                $sql_momento_anterior = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento_actual["cod_momento"], $evaluacion_anterior);
                $momento_evaluacion_anterior = BD::$db->queryOne($sql_momento_anterior);

                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){                   
                    
                    if($_GET['momento'] == null)
                    $momento = 1;
                    
                    $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                    $momento_evaluacion = BD::$db->queryOne($sql_momento);

                    $sql_momento_anterior = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion_anterior);
                    $momento_evaluacion_anterior = BD::$db->queryOne($sql_momento_anterior);
                }
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=avances_evaluacion_n&momento=1');
                    }
                }
            }        

        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
       
//        var_dump(memory_get_usage());
        
        foreach($rubros as $key=>$r){
//            var_dump(memory_get_usage());
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s  order by num_orden asc", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            lineamientos.id AS lineamiento_id,
            lineamientos.nom_lineamiento,
            momento_resultado_detalle.fortalezas as fortalezas,
            length(momento_resultado_detalle.debilidades) as debilidades,
            length(momento_resultado_detalle.plan_mejoramiento) as plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    $evaluacion, 
                    $momento_evaluacion);


            $sql_lineamientos_data_anterior = sprintf("SELECT
            lineamientos.id,
            lineamientos.id AS lineamiento_id,
            lineamientos.nom_lineamiento,
            momento_resultado_detalle.fortalezas as fortalezas,
            length(momento_resultado_detalle.debilidades) as debilidades,
            length(momento_resultado_detalle.plan_mejoramiento) as plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    $evaluacion_anterior, 
                    $momento_evaluacion_anterior);
            
//            var_dump($sql_lineamientos);
            
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            $lineamientos_data_anterior = BD::$db->queryAll($sql_lineamientos_data_anterior); 
            $c = 0;
            if(count($lineamientos_data) > 0){
                foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento'] && $r['id'] === $lineamientos_data_anterior[$c]['padre_lineamiento']){
                    
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                            //validacion                            
                            $sql = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    1, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);    
                            
                            
                            $revision = BD::$db->queryRow($sql);
                            
                             $sql_retro = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    2, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);
                             
                             $retroalimentacion = BD::$db->queryRow($sql_retro);

                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'] === $lineamientos_data_anterior[$c]['fortalezas'] ? 0 : 1;
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            $lineamientos[$k]['validacion'] = $revision['validez'];
                            $lineamientos[$k]['retroalimentacion'] = $retroalimentacion['validez'];
                        }
                    }
                }  
                $rubros[$key]['lineamientos'] = $lineamientos; 
           
                foreach($rubros[$key]['lineamientos'] as $i=>$val){
                     $sql_anexos = sprintf('SELECT
                     count(*)
                     FROM
                     lineamientos
                     INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                     INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                     INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                     INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                     INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                     INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_lineamiento = lineamientos.id 
                     AND momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                     INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
                     WHERE
                     lineamientos.id = %s
                     AND evaluacion.id = %s
                     AND momento_evaluacion.id = %s', $val['lineamiento_id'],$evaluacion, $momento_evaluacion);
                     $rubros[$key]['lineamientos'][$i]['anexos'] = BD::$db->queryOne($sql_anexos);
                }
                $c++;
            }
            }
            else{
                $rubros[$key]['lineamientos'] = $lineamientos;
            }
           
            
        }
           $vars['rubros'] = $rubros;
           $vars['rol'] = $rol;

            
            View::add_js('modules/sievas/scripts/avances/main.js'); 
            View::add_js('modules/sievas/scripts/avances/avances_evaluacion.js'); 
            View::render('avances/reporte_avance.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }

    
    public function avances_reevaluacion(){ 
        $evaluacion = Auth::info_usuario('evaluacion');        
        $rol = Auth::info_usuario('rol');
        $momento = 1;
//        var_dump($rol);
        
//        var_dump(memory_get_usage());
        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }
        if($evaluacion > 0){
//            var_dump(memory_get_usage());
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                
               
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento = $_GET['momento'];
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                
                
                
//                var_dump($momento_actual["cod_momento"]);
//                var_dump($rol);
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){                    
                    
                    if($_GET['momento'] == null)
                    $momento = 1;
                    
                    $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                    $momento_evaluacion = BD::$db->queryOne($sql_momento);
                }
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=avances_evaluacion&momento=1');
                    }
                }
            }        

        $sql_rubros = sprintf('SELECT lineamientos.id as l_id, lineamientos.padre_lineamiento, lineamientos.atributos_lineamiento, lineamientos.num_orden, lineamientos.nom_lineamiento, s.fortalezas_opcion, s.fortalezas_data,
        s.debilidades_opcion, s.debilidades_data, s.planesmejora_opcion, s.planesmejora_data, s.cod_gradacion_escala
        FROM sievas.lineamientos 
        inner join lineamientos_detalle_conjuntos on lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
        left outer join (SELECT momento_resultado_reevaluacion.*,
        momento_resultado_detalle.cod_gradacion_escala, evaluacion.cod_conjunto
        FROM sievas.momento_resultado_reevaluacion
        inner join momento_resultado on momento_resultado_reevaluacion.cod_momento_resultado = momento_resultado.id
        left join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
        inner join momento_evaluacion on momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
        inner join evaluacion on momento_evaluacion.cod_evaluacion = evaluacion.id 
        where evaluacion.id=%s and momento_evaluacion.id = %s) s
        on s.cod_lineamiento = lineamientos.id
        where lineamientos_detalle_conjuntos.cod_conjunto = 1;
        ', $evaluacion, $momento_evaluacion);        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
        foreach($rubros as $key=>$r){
             $sql = sprintf("select er.validez from sievas.evaluacion_revisiones er 
            inner join (select cod_lineamiento, max(indice_revision)
             as MaxIndice from sievas.evaluacion_revisiones 
             where tipo_revision=%s and cod_lineamiento = %s 
             and cod_momento_evaluacion=%s group by cod_lineamiento) 
             as grupoer where er.indice_revision = grupoer.MaxIndice 
             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                    1, $r['l_id'], $momento_evaluacion, $r['l_id'], $momento_evaluacion);    


            $revision = BD::$db->queryRow($sql);

             $sql_retro = sprintf("select er.validez from sievas.evaluacion_revisiones er 
            inner join (select cod_lineamiento, max(indice_revision)
             as MaxIndice from sievas.evaluacion_revisiones 
             where tipo_revision=%s and cod_lineamiento = %s 
             and cod_momento_evaluacion=%s group by cod_lineamiento) 
             as grupoer where er.indice_revision = grupoer.MaxIndice 
             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                    2, $r['l_id'], $momento_evaluacion, $r['l_id'], $momento_evaluacion);

             $retroalimentacion = BD::$db->queryRow($sql_retro);
//             var_dump($revision['validez']);
             $rubros[$key]['validacion'] = $revision['validez'];
             $rubros[$key]['retroalimentacion'] = $retroalimentacion;
        }
        //validacion                            
                           
        
//        var_dump($sql_rubros);
//        var_dump($rubros);
       
           $vars['rubros'] = $rubros;
           $vars['rol'] = $rol;

            
            View::add_js('modules/sievas/scripts/avances/main.js'); 
            View::add_js('modules/sievas/scripts/avances/avances_reevaluacion.js'); 
            View::render('avances/reporte_avance_reevaluacion.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function avances_evaluacion_r(){ 
        $evaluacion = Auth::info_usuario('evaluacion');        
        $rol = Auth::info_usuario('rol');
        
//        var_dump(memory_get_usage());
        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }
        if($evaluacion > 0){
//            var_dump(memory_get_usage());
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                
               
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento = $_GET['momento'];
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                
                
                
//                var_dump($momento_actual["cod_momento"]);
//                var_dump($rol);
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){                    
                    
                    if($_GET['momento'] == null)
                    $momento = 1;
                    
                    $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                    $momento_evaluacion = BD::$db->queryOne($sql_momento);
                }
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=avances_evaluacion_r&momento=1');
                    }
                }
            }        

        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        
        
        $rubros = BD::$db->queryAll($sql_rubros);
        
       
//        var_dump(memory_get_usage());
        
        foreach($rubros as $key=>$r){
//            var_dump(memory_get_usage());
             $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s  order by num_orden asc", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            lineamientos.id AS lineamiento_id,
            lineamientos.nom_lineamiento,
            length(momento_resultado_reevaluacion.fortalezas_opcion) as fortalezas,
            length(momento_resultado_reevaluacion.debilidades_opcion) as debilidades,
            length(momento_resultado_reevaluacion.planesmejora_data) as plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN momento_resultado_reevaluacion ON momento_resultado_reevaluacion.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    $evaluacion, 
                    $momento_evaluacion);
            
//            var_dump($sql_lineamientos_data);
            
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            if(count($lineamientos_data) > 0){
                foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                            //validacion                            
                            $sql = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    1, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);    
                            
                            
                            $revision = BD::$db->queryRow($sql);
                            
                             $sql_retro = sprintf("select er.validez from sievas.evaluacion_revisiones er 
                            inner join (select cod_lineamiento, max(indice_revision)
                             as MaxIndice from sievas.evaluacion_revisiones 
                             where tipo_revision=%s and cod_lineamiento = %s 
                             and cod_momento_evaluacion=%s group by cod_lineamiento) 
                             as grupoer where er.indice_revision = grupoer.MaxIndice 
                             and er.cod_lineamiento = %s and er.cod_momento_evaluacion=%s",
                                    2, $ld['id'], $momento_evaluacion, $ld['id'], $momento_evaluacion);
                             
                             $retroalimentacion = BD::$db->queryRow($sql_retro);

                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            $lineamientos[$k]['validacion'] = $revision['validez'];
                            $lineamientos[$k]['retroalimentacion'] = $retroalimentacion['validez'];
                        }
                    }
                }  
                $rubros[$key]['lineamientos'] = $lineamientos; 
//                var_dump($lineamientos_data);
           
                foreach($rubros[$key]['lineamientos'] as $i=>$val){
                     $sql_anexos = sprintf('SELECT
                     count(*)
                     FROM
                     lineamientos
                     INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                     INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                     INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                     INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                     INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                     INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_lineamiento = lineamientos.id 
                     AND momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                     INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
                     WHERE
                     lineamientos.id = %s
                     AND evaluacion.id = %s
                     AND momento_evaluacion.id = %s', $val['lineamiento_id'],$evaluacion, $momento_evaluacion);
                     $rubros[$key]['lineamientos'][$i]['anexos'] = BD::$db->queryOne($sql_anexos);
                }
            }
            }
            else{
                $rubros[$key]['lineamientos'] = $lineamientos;
            }
           
            
        }
           $vars['rubros'] = $rubros;
           $vars['rol'] = $rol;

            
            View::add_js('modules/sievas/scripts/avances/main.js'); 
            View::add_js('modules/sievas/scripts/avances/avances_evaluacion.js'); 
            View::render('avances/reporte_avance.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    
    public function monitor_avances(){
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/monitor_avances.js');
        View::render('avances/monitor_avances.php', $vars);
    }
    
    
    public function validacion_avances(){
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/validacion_avances.js');
        View::render('avances/validacion_avances.php', $vars);
    }
    
    public function validar_avance(){
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $momento_evaluacion = filter_input(INPUT_GET, 'momento_evaluacion', FILTER_SANITIZE_NUMBER_INT);
        //Datos actuales del lineamiento
        
        $sql_evaluacion = sprintf("select cod_evaluacion from momento_evaluacion where id=%s", $momento_evaluacion);
        $evaluacion = BD::$db->queryOne($sql_evaluacion);
        
        $usuario = Auth::info_usuario('usuario');
        
        $sql_usuario = sprintf("select cod_persona from sys_usuario where username='%s'", $usuario);
        $cod_usuario = BD::$db->queryOne($sql_usuario);
        
        $sql_lineamiento = sprintf("select lineamientos.nom_lineamiento,
            lineamientos.atributos_lineamiento,lineamientos.num_orden,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            gradacion_escalas.desc_escala,
            gradacion_escalas.id as gradacion_id
            from lineamientos  
            left join momento_resultado_detalle on lineamientos.id = momento_resultado_detalle.cod_lineamiento
            left join momento_resultado on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
            left join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
            where lineamientos.id=%s and momento_resultado.cod_momento_evaluacion = %s", $lineamiento, $momento_evaluacion);
        
        
        $sql_anexos = sprintf('SELECT
                gen_documentos.ruta,
                gen_documentos.id,
                gen_documentos.nombre
                FROM
                gen_documentos
                left JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                left JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                left JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                WHERE
                lineamientos.id = %s
                AND momento_evaluacion.id = %s', $lineamiento, $momento_evaluacion);
//        var_dump($sql_lineamiento);
        
        $anexos = BD::$db->queryAll($sql_anexos);
        
        $lineamiento_data = BD::$db->queryRow($sql_lineamiento);
        
        if(count($lineamiento_data) == 0){
            $sql_lineamiento = sprintf("select * from lineamientos where lineamientos.id = %s", $lineamiento);
            $lineamiento_data = BD::$db->queryRow($sql_lineamiento);
        }
        
        //Revisiones
        $sql_revisiones = sprintf("select *, gen_persona.nombres as revisor, 
            evaluacion_revisiones.id as revision_id, gen_persona.id as cod_revisor 
            from evaluacion_revisiones 
            inner join gen_persona on cod_revisor = gen_persona.id
            where evaluacion_revisiones.cod_lineamiento = %s and evaluacion_revisiones.cod_momento_evaluacion=%s 
            and evaluacion_revisiones.tipo_revision = 1
            order by evaluacion_revisiones.indice_revision desc", $lineamiento, $momento_evaluacion);
        $revisiones = BD::$db->queryAll($sql_revisiones);
        
        $vars['cod_usuario'] = $cod_usuario;
        $vars['evaluacion'] = $evaluacion;
        $vars['revisiones'] = $revisiones;
        $vars['rol'] = Auth::info_usuario('rol');
        $vars['lineamiento_data'] = $lineamiento_data;
        $vars['anexos'] = $anexos;
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/validar_avance.js'); 
        View::render('avances/validar_avance.php', $vars);
    }
    
    public function retroalimentar_avance(){
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $momento_evaluacion = filter_input(INPUT_GET, 'momento_evaluacion', FILTER_SANITIZE_NUMBER_INT);
        //Datos actuales del lineamiento
        
        $sql_evaluacion = sprintf("select cod_evaluacion, cod_momento from momento_evaluacion where id=%s", $momento_evaluacion);
        $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
        
        $evaluacion = $evaluacion_data['cod_evaluacion'];
        $momento = $evaluacion_data['cod_momento'];
        
        $usuario = Auth::info_usuario('usuario');
        
        $sql_usuario = sprintf("select cod_persona from sys_usuario where username='%s'", $usuario);
        $cod_usuario = BD::$db->queryOne($sql_usuario);
        
        $sql_lineamiento = sprintf("select lineamientos.nom_lineamiento,
            lineamientos.atributos_lineamiento,lineamientos.num_orden,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            gradacion_escalas.desc_escala,
            gradacion_escalas.id as gradacion_id
            from lineamientos  
            left join momento_resultado_detalle on lineamientos.id = momento_resultado_detalle.cod_lineamiento
            left join momento_resultado on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
            left join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
            where lineamientos.id=%s and momento_resultado.cod_momento_evaluacion = %s", $lineamiento, $momento_evaluacion);
        
        
        $sql_anexos = sprintf('SELECT
                gen_documentos.ruta,
                gen_documentos.id,
                gen_documentos.nombre
                FROM
                gen_documentos
                left JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                left JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                left JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                WHERE
                lineamientos.id = %s
                AND momento_evaluacion.id = %s', $lineamiento, $momento_evaluacion);
//        var_dump($sql_lineamiento);
        
        $anexos = BD::$db->queryAll($sql_anexos);
        
        $lineamiento_data = BD::$db->queryRow($sql_lineamiento);
        
        if(count($lineamiento_data) == 0){
            $sql_lineamiento = sprintf("select * from lineamientos where lineamientos.id = %s", $lineamiento);
            $lineamiento_data = BD::$db->queryRow($sql_lineamiento);
        }
        
        //Revisiones
        $sql_revisiones = sprintf("select *, gen_persona.nombres as revisor, 
            evaluacion_revisiones.id as revision_id, gen_persona.id as cod_revisor 
            from evaluacion_revisiones 
            inner join gen_persona on cod_revisor = gen_persona.id
            where evaluacion_revisiones.cod_lineamiento = %s and evaluacion_revisiones.cod_momento_evaluacion=%s 
            and evaluacion_revisiones.tipo_revision = 2
            order by evaluacion_revisiones.indice_revision desc", $lineamiento, $momento_evaluacion);
        $revisiones = BD::$db->queryAll($sql_revisiones);
        
        $momento_actual = $this->get_momento_actual();
        
        $vars['momento'] = $momento_actual['cod_momento'];
        $vars['cod_usuario'] = $cod_usuario;
        $vars['evaluacion'] = $evaluacion;
        $vars['revisiones'] = $revisiones;
        $vars['rol'] = Auth::info_usuario('rol');
        $vars['lineamiento_data'] = $lineamiento_data;
        $vars['anexos'] = $anexos;
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/validar_avance.js'); 
        View::render('avances/retroalimentar_avance.php', $vars);
    }
    
    public function eliminar_revision_avance(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $res = array();
        if($id > 0){
            $sql = sprintf("delete from evaluacion_revisiones where id=%s", $id);
//            var_dump($sql);
            $result = BD::$db->query($sql);
            if(!PEAR::isError($result)){
                $res['status'] = 'ok';
            }
        }
        echo json_encode($res);
    }
    
    public function get_revision_avance(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $revisiones = array();
        if($id > 0){
            $sql = sprintf("select *, gradacion_escalas.desc_escala as calificacion
                from evaluacion_revisiones 
                left join gradacion_escalas on gradacion_escalas.id = evaluacion_revisiones.cod_gradacion
                where evaluacion_revisiones.id = %s", $id);
            $revisiones = BD::$db->queryRow($sql);
            $revisiones['fortalezas'] = urldecode($revisiones['fortalezas']);
            $revisiones['debilidades'] = urldecode($revisiones['debilidades']);
            $revisiones['plan_mejoramiento'] = urldecode($revisiones['plan_mejoramiento']);
            $revisiones['anexos'] = urldecode($revisiones['anexos']);
        }
        echo json_encode($revisiones);
    }
    
    public function guardar_revision_avance(){
        header('Content-Type: application/json');
        $validez = $_POST['validez'];
        $comentario = $_POST['comentario'];
        $cod_lineamiento = $_POST['cod_lineamiento'];
        $cod_momento_evaluacion = $_POST['cod_momento_evaluacion'];
        $cod_gradacion = $_POST['cod_gradacion'];
        $fortalezas = $_POST['fortalezas'];
        $debilidades = $_POST['debilidades'];
        $plan_mejoramiento = $_POST['plan_mejoramiento'];
        $anexos = $_POST['anexos'];
        $revision_id = $_POST['revision_id'];
        $tipo_revision = $_POST['tipo_revision'];
        
        $now = new DateTime(); 
        if($revision_id > 0){
            $evaluacion_revision = new evaluacion_revisiones();
            $evaluacion_revision->id = $revision_id;
            $evaluacion_revision->comentarios = $comentario;
            $evaluacion_revision->validez = ($validez === 'true') ? 1 : 0;
            
            if($evaluacion_revision->update()){      
                $evaluacion_revision->find($evaluacion_revision->id);
                echo json_encode(array(
                    'status' => 'ok',
                    'fecha' => $evaluacion_revision->fecha_creacion,
                    'comentarios' => $evaluacion_revision->comentarios,
                    'validez' => $evaluacion_revision->validez,
                    'revision_id' => $evaluacion_revision->id
                ));
            }
            else{
                echo $evaluacion_revision->error_sql();
            }
        }
        else{
            $usuario = Auth::info_usuario('usuario');

            $sql_revisor = sprintf("select cod_persona from sys_usuario where username='%s'", $usuario);
            $cod_revisor = BD::$db->queryOne($sql_revisor);

            $sql_c_revisiones = sprintf("select count(*) from evaluacion_revisiones where cod_lineamiento = %s and cod_momento_evaluacion = %s and tipo_revision = 1", $cod_lineamiento, $cod_momento_evaluacion);
            $c_revisiones = BD::$db->queryOne($sql_c_revisiones);

            $evaluacion_revision = new evaluacion_revisiones();
            $evaluacion_revision->cod_lineamiento = $cod_lineamiento;
            $evaluacion_revision->cod_revisor = $cod_revisor;
            $evaluacion_revision->cod_momento_evaluacion = $cod_momento_evaluacion;
            $evaluacion_revision->fortalezas = urlencode($fortalezas);
            $evaluacion_revision->debilidades = urlencode($debilidades);
            $evaluacion_revision->plan_mejoramiento = urlencode($plan_mejoramiento);
            if($cod_gradacion > 0){
                $evaluacion_revision->cod_gradacion = $cod_gradacion;
            }

            $evaluacion_revision->anexos = urlencode($anexos);
            $evaluacion_revision->comentarios = $comentario;
            $evaluacion_revision->indice_revision = $c_revisiones;
            $evaluacion_revision->validez = ($validez === 'true') ? 1 : 0;
            if($tipo_revision > 0){
                $evaluacion_revision->tipo_revision = $tipo_revision;
            }

            if($evaluacion_revision->save()){
                $revisor = BD::$db->queryOne(sprintf("select gen_persona.nombres from gen_persona where id = %s", $evaluacion_revision->cod_revisor));
                $evaluacion_revision->find($evaluacion_revision->id);            
                echo json_encode(array(
                    'status' => 'ok',
                    'fecha' => $evaluacion_revision->fecha_creacion,
                    'comentarios' => $evaluacion_revision->comentarios,
                    'validez' => $evaluacion_revision->validez,
                    'revisor' => $revisor,
                    'indice' => $evaluacion_revision->indice_revision,
                    'revision_id' => $evaluacion_revision->id
                ));
            }
            else{
                echo $evaluacion_revision->error_sql();
            }

        
        }
        
    }
    
    public function resultados_evaluacion(){  
        $evaluacion = Auth::info_usuario('evaluacion');
        $rol = Auth::info_usuario('rol');
        $cod_idioma = Auth::info_usuario('cod_idioma');
        $momento_evaluacion = 0;
        $momento = 0;
        $evaluacion_data = array();

        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, e.ev_cna, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }
        if($evaluacion > 0){
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                $momento = $_GET['momento'];

                if($_GET['momento'] == null)
                      $momento = 1;
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){
                    $evaluacion = Auth::info_usuario('evaluacion');
                    if($evaluacion > 0){
                        $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                        $momento_evaluacion = BD::$db->queryOne($sql_momento);
                    }
                }  
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&momento=1');
                    }
                }
            }
        
//        var_dump($evaluacion_data);
            
        $sql_rubros = '';    
        switch($cod_idioma){
            case "4":
//                $sql_rubros = sprintf('SELECT
//                lineamientos.id,
//                lineamientos.nom_lineamiento,
//                lineamientos.padre_lineamiento
//                FROM
//                lineamientos
//                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
//                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
//                INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
//                WHERE
//                lineamientos.padre_lineamiento = 0 AND
//                evaluacion.id = %s
//                ', $evaluacion);  
                $sql_rubros = sprintf('SELECT 
                    lineamientos.id,
                    tr.nom_lineamiento,
                    lineamientos.padre_lineamiento,
                    tr.id as traduccion_id
                FROM
                    lineamientos
                        INNER JOIN
                    lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                        INNER JOIN
                    lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                        INNER JOIN
                    evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                                inner join 
                        (SELECT 
                    lineamientos.id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento,
                    lineamientos_asociaciones.cod_lineamiento1,
                    lineamientos_conjuntos_asociaciones.id as asociacion
                FROM
                    lineamientos
                        INNER JOIN
                    lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                        INNER JOIN
                    lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                    inner join
                    lineamientos_conjuntos_traducciones on lineamientos_conjuntos_traducciones.cod_conjunto_traduccion = lineamientos_conjuntos.id    
                        INNER JOIN
                    evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos_traducciones.cod_conjunto
                    inner join
                    lineamientos_conjuntos_asociaciones on lineamientos_conjuntos_asociaciones.cod_conjunto1 = lineamientos_conjuntos_traducciones.cod_conjunto and lineamientos_conjuntos_asociaciones.cod_conjunto2 = lineamientos_conjuntos_traducciones.cod_conjunto_traduccion
                    inner join 
                    lineamientos_asociaciones on lineamientos_asociaciones.cod_asociacion_conjunto = lineamientos_conjuntos_asociaciones.id and lineamientos_asociaciones.cod_lineamiento2 = lineamientos.id

                WHERE
                    lineamientos.padre_lineamiento = 0
                        AND evaluacion.id = %s
                        and lineamientos_conjuntos_traducciones.gen_idiomas_id = 4
                 ) as tr on tr.cod_lineamiento1 = lineamientos.id
                WHERE
                    lineamientos.padre_lineamiento = 0
                        AND evaluacion.id = %s
                        ORDER BY lineamientos.num_orden ASC
                ', $evaluacion, $evaluacion); 
                
//                var_dump($sql_rubros);
                break;
            default:
                $sql_rubros = sprintf('SELECT
                lineamientos.id,
                lineamientos.nom_lineamiento,
                lineamientos.padre_lineamiento
                FROM
                lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                WHERE
                lineamientos.padre_lineamiento = 0 AND
                evaluacion.id = %s
                ', $evaluacion);   
                break;
        }
             
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        foreach($rubros as $key=>$r){
            $sql_lineamientos = '';
            
            switch($cod_idioma){
                case "4":
                    $sql_lineamientos = sprintf("SELECT 
                        lineamientos_asociaciones.cod_lineamiento1 AS lineamiento_id,
                        lineamientos.nom_lineamiento,
                        lineamientos.padre_lineamiento
                    FROM
                        lineamientos
                            INNER JOIN
                        lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                            INNER JOIN
                        lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                        inner join
                        lineamientos_conjuntos_traducciones on lineamientos_conjuntos_traducciones.cod_conjunto_traduccion = lineamientos_conjuntos.id    
                            inner join
                        lineamientos_conjuntos_asociaciones on lineamientos_conjuntos_asociaciones.cod_conjunto1 = lineamientos_conjuntos_traducciones.cod_conjunto and lineamientos_conjuntos_asociaciones.cod_conjunto2 = lineamientos_conjuntos_traducciones.cod_conjunto_traduccion
                        inner join 
                        lineamientos_asociaciones on lineamientos_asociaciones.cod_asociacion_conjunto = lineamientos_conjuntos_asociaciones.id and lineamientos_asociaciones.cod_lineamiento2 = lineamientos.id
                    WHERE
                        lineamientos.padre_lineamiento = %s
                    ORDER BY lineamientos.num_orden ASC", $r['traduccion_id']);
                    
//                    var_dump($sql_lineamientos);
                    break;
                default:
                    $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
                    FROM
                            lineamientos
                    INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                    INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                    WHERE
                            lineamientos.padre_lineamiento = %s order by num_orden asc", $r['id']);
                    break;
            }
            
            
            
//            var_dump($sql_lineamientos);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            $lineamientos_data = null;
            switch($cod_idioma){
                case "4":
                    $sql_lineamientos_data = sprintf("SELECT
                    lineamientos.id,
                    momento_resultado_detalle_traducciones.fortalezas,
                    momento_resultado_detalle_traducciones.debilidades,
                    momento_resultado_detalle_traducciones.plan_mejoramiento,
                    momento_resultado_detalle.cod_gradacion_escala,
                    gradacion_escalas.desc_escala,
                    lineamientos.padre_lineamiento
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    left join momento_resultado_detalle_traducciones on momento_resultado_detalle_traducciones.momento_resultado_detalle_id = momento_resultado_detalle_id
                    LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s and momento_resultado_detalle_traducciones.gen_idiomas_id = %s",
                            $r['id'], 
                            $evaluacion, 
                            $momento_evaluacion,
                            4);
                    
//                    var_dump($sql_lineamientos_data);
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data); 
                    break;
                default:
                    $sql_lineamientos_data = sprintf("SELECT
                    lineamientos.id,
                    momento_resultado_detalle.fortalezas,
                    momento_resultado_detalle.debilidades,
                    momento_resultado_detalle.plan_mejoramiento,
                    momento_resultado_detalle.cod_gradacion_escala,
                    gradacion_escalas.desc_escala,
                    lineamientos.padre_lineamiento
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                            $r['id'], 
                            $evaluacion, 
                            $momento_evaluacion);

                    $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data); 
                    break;
            }
              
            
//            foreach($lineamientos as $k=>$l){

//            }
            foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){                            
                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            
                        }
                    }
                }                
            }
            
            foreach($lineamientos as $idx=>$l){
                 $sql_anexos = sprintf('SELECT
                gen_documentos.ruta,
                gen_documentos.id,
                gen_documentos.nombre,
                momento_resultado_anexo.nuevo
                FROM
                gen_documentos
                INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                WHERE
                lineamientos.id = %s
                AND momento_evaluacion.id = %s', $l['lineamiento_id'], $momento_evaluacion);
                 
//                 var_dump($sql_anexos); 
                
                 $anexos = BD::$db->queryAll($sql_anexos);
                 
                 
                if(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0 ){
                    $sql_indicador = sprintf("select lineamientos.id, lineamientos.nom_lineamiento, evaluacion_analisis_indicadores.analisis from lineamientos 
                        left join evaluacion_analisis_indicadores on evaluacion_analisis_indicadores.cod_lineamiento = lineamientos.id
                        where lineamientos.padre_indicador = %s and evaluacion_analisis_indicadores.cod_momento = %s", $l['lineamiento_id'], $momento_evaluacion);
                    $analisis_indicador = BD::$db->queryAll($sql_indicador);
                    if($analisis_indicador == null){
                        $sql_indicador = sprintf("select lineamientos.id, lineamientos.nom_lineamiento from lineamientos where padre_indicador = %s", $l['lineamiento_id']);
                        $analisis_indicador = BD::$db->queryAll($sql_indicador);
                    }      
                     foreach($analisis_indicador as $jx=>$m){
                        $sql_anexos = sprintf('SELECT
                       gen_documentos.ruta,
                       gen_documentos.id,
                       gen_documentos.nombre
                       FROM
                       gen_documentos
                       INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                       INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                       INNER JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                       WHERE
                       lineamientos.id = %s
                       AND momento_evaluacion.id = %s', $m['id'], $momento_evaluacion);

       //                 var_dump($sql_anexos); 

                        $anexos = BD::$db->queryAll($sql_anexos);
                        $analisis_indicador[$jx]['anexos'] = $anexos;
                     }
                    $lineamientos[$idx]['analisis_indicador'] = $analisis_indicador;
                }
                 
                 
                 $lineamientos[$idx]['anexos'] = $anexos;
            }
           $rubros[$key]['lineamientos'] = $lineamientos;           
          
        }
        $vars['rol'] = $rol;
        $vars['rubros'] = $rubros;
        $vars['momento'] = $momento;
        $vars['momento_evaluacion'] = $momento_evaluacion;
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/reporte_evaluacion.js'); 
        View::render('avances/reporte_evaluacion.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function resultados_evaluacion_html(){  
        $evaluacion = Auth::info_usuario('evaluacion');
        $rol = Auth::info_usuario('rol');
        $momento_evaluacion = 0;
        $momento = 0;
        $evaluacion_data = array();
        if($rol == null){
            $evaluacion = $_GET['evaluacion'];
            $sql_evaluacion = sprintf("SELECT e.etiqueta, e.ev_cna, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion);
            
            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;
        }
        if($evaluacion > 0){
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 1;
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                        $momento = 1;
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){
                    $evaluacion = Auth::info_usuario('evaluacion');
                    if($evaluacion > 0){
                        $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                        $momento_evaluacion = BD::$db->queryOne($sql_momento);
                    }
                }  
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
                        header('Location: index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&momento=1');
                    }
                }
            }
        
//        var_dump($evaluacion_data);
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        foreach($rubros as $key=>$r){
            $calificaciones_rubro = array();
            $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s order by num_orden asc", $r['id']);
            
//            var_dump($sql_lineamientos);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            gradacion_escalas.desc_escala,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    $evaluacion, 
                    $momento_evaluacion);
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   

            $sql_lineamientos_calificacion = sprintf("SELECT
            gradacion_escalas.valor_escala,
            lineamientos.id,
            lineamientos.padre_lineamiento,
            momento_resultado_detalle.activo
            FROM
            momento_resultado_detalle
            left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            WHERE
            momento_evaluacion.id = %s", $momento_evaluacion);
            
            $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
            $inactivos = 0;
             foreach($calificaciones as $c){
                if($r['id'] == $c['padre_lineamiento']){                    
                    foreach($lineamientos as $k=>$l){                        
                        if($l['lineamiento_id'] === $c['id']){
                              if($c['activo'] > 0){
                                    $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                    $rubros[$key]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);                                    
                                    
                                    $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                              }  
                              else{
                                    $lineamientos[$k]['valor'] = 0;
                                    $rubros[$key]['calificaciones'][] = 0;
                                    $inactivos++;
                              }
                              
                        }
                    }
                }
            }
            

            
            foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){                            
                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            $lineamientos[$k]['valor_escala'] = $ld['valor_escala'];
                            $calificaciones_rubro[] =  $ld['valor_escala'];
                        }
                    }
                }                
            }
            
            foreach($lineamientos as $idx=>$l){
                 $sql_anexos = sprintf('SELECT
                gen_documentos.ruta,
                gen_documentos.id,
                gen_documentos.nombre
                FROM
                gen_documentos
                INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                WHERE
                lineamientos.id = %s
                AND momento_evaluacion.id = %s', $l['lineamiento_id'], $momento_evaluacion);
                 
//                 var_dump($sql_anexos); 
                
                 $anexos = BD::$db->queryAll($sql_anexos);
                 
                 
                if(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0 ){
                    $sql_indicador = sprintf("select lineamientos.id, lineamientos.nom_lineamiento, evaluacion_analisis_indicadores.analisis from lineamientos 
                        left join evaluacion_analisis_indicadores on evaluacion_analisis_indicadores.cod_lineamiento = lineamientos.id
                        where lineamientos.padre_indicador = %s and evaluacion_analisis_indicadores.cod_momento = %s", $l['lineamiento_id'], $momento_evaluacion);
                    $analisis_indicador = BD::$db->queryAll($sql_indicador);
                    if($analisis_indicador == null){
                        $sql_indicador = sprintf("select lineamientos.id, lineamientos.nom_lineamiento from lineamientos where padre_indicador = %s", $l['lineamiento_id']);
                        $analisis_indicador = BD::$db->queryAll($sql_indicador);
                    }      
                     foreach($analisis_indicador as $jx=>$m){
                        $sql_anexos = sprintf('SELECT
                       gen_documentos.ruta,
                       gen_documentos.id,
                       gen_documentos.nombre
                       FROM
                       gen_documentos
                       INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
                       INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                       INNER JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
                       WHERE
                       lineamientos.id = %s
                       AND momento_evaluacion.id = %s', $m['id'], $momento_evaluacion);

       //                 var_dump($sql_anexos); 

                        $anexos = BD::$db->queryAll($sql_anexos);
                        $analisis_indicador[$jx]['anexos'] = $anexos;
                     }
                    $lineamientos[$idx]['analisis_indicador'] = $analisis_indicador;
                }
                 
                 
                 $lineamientos[$idx]['anexos'] = $anexos;
            }
           $rubros[$key]['lineamientos'] = $lineamientos;        
           $rubros[$key]['calificaciones_rubro'] = $calificaciones_rubro;
//           var_dump($calificaciones_rubro);
//           $rubros[$key]['img_rubro'] = $this->plot_file($calificaciones_rubro);
          
        }
        $vars['rol'] = $rol;
        $vars['rubros'] = $rubros;
        $vars['momento'] = $momento;
        $vars['momento_evaluacion'] = $momento_evaluacion;
        View::add_js('public/js/wordgen/FileSaver.min.js'); 
        View::add_js('public/js/wordgen/jquery.wordexport.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.core.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.dynamic.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.annotate.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.context.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.effects.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.key.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.resizing.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.tooltips.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.zoom.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.bar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.pie.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.radar.js'); 
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/reporte_evaluacion.js'); 
        View::render('avances/reporte_evaluacion_html.php', $vars, 'reporte.php');
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    public function resultados_evaluacion_html_test(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        if(!($momento > 0)){
            $momento = 1;
        }
        
        $sql_evaluador = sprintf("SELECT 
            comite.cod_persona
        FROM
            sievas.evaluacion
                INNER JOIN
            sievas.momento_evaluacion ON evaluacion.id = momento_evaluacion.cod_evaluacion
                INNER JOIN
            sievas.comite ON comite.cod_momento_evaluacion = momento_evaluacion.id
        WHERE
            evaluacion.id = %s
                AND momento_evaluacion.cod_momento = 1
        AND comite.cod_cargo = 1;", $evaluacion, $momento);
        
        $evaluador = BD::$db->queryOne($sql_evaluador);
        
        $sql = sprintf("SELECT evaluacion.id, eval_programas.programa FROM sievas.evaluacion
        inner join sievas.eval_programas on eval_programas.id = evaluacion.cod_evaluado
        inner join sievas.eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
        where evaluacion.padre = %s", $evaluacion);
        
        $evaluaciones = BD::$db->queryAll($sql);
//        var_dump($sql);
       

        if($evaluacion > 0){
        
//        var_dump($evaluacion_data);
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        
        foreach($rubros as $k=>$r){
            $calificaciones_rubro = array();
            $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s order by num_orden asc", $r['id']);
           
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            //para saber si es comun se consultan los permisos del comite de centro
            $comun = 1;

            
            foreach($lineamientos as $key=>$l){
                    $sql_data_comun = sprintf("SELECT count(*) FROM 
                        sievas.evaluacion_privilegios_miembros 
                        where cod_evaluacion=%s and cod_persona=%s and cod_item=%s",
                            $evaluacion, $evaluador, $l['lineamiento_id']);
                    
                    $comun = BD::$db->queryOne($sql_data_comun);
                    
//                    var_dump($sql_data_comun);
                    
                    if($comun > 0){                      
                        //sino la data se saca de cada evaluacion 
                        $evaluacion_data = array();
                        foreach($evaluaciones as $e){
                            $sql_lineamientos_data = sprintf("SELECT
                            lineamientos.id,
                            momento_resultado_detalle.fortalezas,
                            momento_resultado_detalle.debilidades,
                            momento_resultado_detalle.plan_mejoramiento,
                            momento_resultado_detalle.cod_gradacion_escala,
                            gradacion_escalas.desc_escala,
                            gradacion_escalas.valor_escala,
                            lineamientos.padre_lineamiento
                            FROM
                            evaluacion
                            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                            WHERE lineamientos.id = %s AND evaluacion.id = %s AND momento_evaluacion.cod_momento = %s",
                                    $l['lineamiento_id'], 
                                    $e['id'], 
                                    $momento);

    //                        var_dump($sql_lineamientos_data);

                            $lineamientos_data = BD::$db->queryRow($sql_lineamientos_data);
                            $lineamientos_data['programa'] = $e['programa'];
                            $evaluacion_data[] = $lineamientos_data;
//                            echo json_encode($lineamientos_data);
                        }
                        $lineamientos[$key]['evaluacion_data'] = $evaluacion_data;
                        $lineamientos[$key]['comun'] = $comun;
                
                    }
                    else{
//                        echo "no comun";
                        
                    }
                       
            }
            $rubros[$k]['lineamientos'] = $lineamientos;
//           var_dump($lineamientos_data);
                

          
        }

        $vars['rubros'] = $rubros;
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/reporte_evaluacion.js'); 
        View::render('avances/reporte_evaluacion_html_test.php', $vars, 'reporte.php');
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    public function resultados_evaluacion_html_test_2(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        if(!($momento > 0)){
            $momento = 1;
        }
        
        $sql_evaluador = sprintf("SELECT 
            comite.cod_persona
        FROM
            sievas.evaluacion
                INNER JOIN
            sievas.momento_evaluacion ON evaluacion.id = momento_evaluacion.cod_evaluacion
                INNER JOIN
            sievas.comite ON comite.cod_momento_evaluacion = momento_evaluacion.id
        WHERE
            evaluacion.id = %s
                AND momento_evaluacion.cod_momento = 1
        AND comite.cod_cargo = 1;", $evaluacion, $momento);
        
        $evaluador = BD::$db->queryOne($sql_evaluador);
        
        $sql = sprintf("SELECT evaluacion.id, eval_programas.programa FROM sievas.evaluacion
        inner join sievas.eval_programas on eval_programas.id = evaluacion.cod_evaluado
        inner join sievas.eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
        where evaluacion.id = %s", $evaluacion);
        
        $evaluaciones = BD::$db->queryAll($sql);
//        var_dump($sql);
       

        if($evaluacion > 0){
        
//        var_dump($evaluacion_data);
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', $evaluacion);        
        
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        
        
        foreach($rubros as $k=>$r){
            $calificaciones_rubro = array();
            $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s order by num_orden asc", $r['id']);
           
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            //para saber si es comun se consultan los permisos del comite de centro
            $comun = 1;

            
            foreach($lineamientos as $key=>$l){
                    $sql_data_comun = sprintf("SELECT count(*) FROM 
                        sievas.evaluacion_privilegios_miembros 
                        where cod_evaluacion=%s and cod_persona=%s and cod_item=%s",
                            $evaluacion, $evaluador, $l['lineamiento_id']);
                    
                    $comun = BD::$db->queryOne($sql_data_comun);
                    
//                    var_dump($sql_data_comun);
                    
                    if($comun > 0){                      
                        //sino la data se saca de cada evaluacion 
                        $evaluacion_data = array();
                        foreach($evaluaciones as $e){
                            $sql_lineamientos_data = sprintf("SELECT
                            lineamientos.id,
                            momento_resultado_detalle.fortalezas,
                            momento_resultado_detalle.debilidades,
                            momento_resultado_detalle.plan_mejoramiento,
                            momento_resultado_detalle.cod_gradacion_escala,
                            gradacion_escalas.desc_escala,
                            gradacion_escalas.valor_escala,
                            lineamientos.padre_lineamiento
                            FROM
                            evaluacion
                            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                            WHERE lineamientos.id = %s AND evaluacion.id = %s AND momento_evaluacion.cod_momento = %s",
                                    $l['lineamiento_id'], 
                                    $e['id'], 
                                    $momento);

    //                        var_dump($sql_lineamientos_data);

                            $lineamientos_data = BD::$db->queryRow($sql_lineamientos_data);
                            $lineamientos_data['programa'] = $e['programa'];
                            $evaluacion_data[] = $lineamientos_data;
//                            echo json_encode($lineamientos_data);
                        }
                        $lineamientos[$key]['evaluacion_data'] = $evaluacion_data;
                        $lineamientos[$key]['comun'] = $comun;
                
                    }
                    else{
//                        echo "no comun";
                        
                    }
                       
            }
            $rubros[$k]['lineamientos'] = $lineamientos;
//           var_dump($lineamientos_data);
                

          
        }

        $vars['rubros'] = $rubros;
        $vars['e_id'] = $evaluacion;
        $vars['evaluaciones_esp'] = array(
            46 => 1,
            47 => 1,
            48 => 1,
            49 => 1,
            50 => 1,
            51 => 1,
            52 => 1,
            77 => 1,
            87 => 1,
            88 => 1,
            89 => 1,
            107 => 1,
            108 => 1,
            109 => 1,
            110 => 1,
            111 => 1,
            112 => 1
        );
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/reporte_evaluacion.js'); 
        View::render('avances/reporte_evaluacion_html_test_2.php', $vars, 'reporte.php');
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function word_gen(){
        VsWord::autoLoad();
        $html = $_POST['word_data'];
        $doc = new VsWord(); 
        $parser = new HtmlParser($doc);
        $parser->parse($html);
        $file = '/var/tmp/reporte.docx';
        $doc->saveAs($file); 
        
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
    
    public function resultados_evaluacion_red(){  
        $evaluacion = Auth::info_usuario('evaluacion');
        $rubros_data = array();
        if($evaluacion > 0){                        
            $momento = 1;
            if($_GET['momento'] > 0){
                $momento = $_GET['momento'];
            }
            //verificar que todas las evaluaciones de la red tengan los mismos conjuntos de rubros
            $sql_check_1 = sprintf("SELECT DISTINCT
            evaluacion.cod_conjunto
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            WHERE
            evaluacion.padre = %s
            AND
            momento_evaluacion.cod_momento = %s", $evaluacion, $momento);
            
            $check_1 = BD::$db->queryAll($sql_check_1);      
            
            $sql_evaluaciones = sprintf("SELECT
            evaluacion.id
            FROM
            evaluacion
            WHERE
            evaluacion.padre = %s", $evaluacion);
            
            $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
            
//            var_dump($check_1);
            if(count($check_1) == 1){
                $sql_rubros = sprintf("SELECT
                lineamientos.id,
                lineamientos.nom_lineamiento,
                lineamientos.num_orden
                FROM
                lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                WHERE
                lineamientos.padre_lineamiento = 0 AND
                lineamientos_conjuntos.id = %s", $check_1[0]['cod_conjunto']);
                
                $rubros = BD::$db->queryAll($sql_rubros);
                
                /*
                 * Para cada rubro buscar los resultados de cada evaluacion del conjunto de rubros
                 */
   
                foreach($rubros as $key=>$r){
                    $lineamientos_data = array();
                    $rubros_data[$key]['rubro'] = $r;
                    $sql_lineamientos = sprintf("SELECT
                    lineamientos.id,
                    lineamientos.nom_lineamiento,
                    lineamientos.num_orden
                    FROM
                    lineamientos
                    INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                    INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                    WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']
                    );
                    $lineamientos = BD::$db->queryAll($sql_lineamientos);
                    
                    
                    foreach($lineamientos as $k=>$l){
                        $lineamientos_data[$k]['lineamiento'] = $l;
                        //evaluaciones
                        foreach($evaluaciones as $m=>$e){
                            $sql_resultados = sprintf("SELECT
                            gradacion_escalas.desc_escala,
                            momento_resultado_detalle.fortalezas,
                            momento_resultado_detalle.debilidades,
                            momento_resultado_detalle.plan_mejoramiento,
                            lineamientos.nom_lineamiento,
                            eval_instituciones.nom_institucion,
                            eval_programas.programa,
                            evaluacion.id as e_id,
                            evaluacion.etiqueta
                            FROM
                            evaluacion
                            INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
                            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                            INNER JOIN lineamientos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id AND momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                            INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
                            INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
                            WHERE
                            evaluacion.id = %s AND
                            momento_evaluacion.cod_momento = %s AND
                            lineamientos.id = %s", $e['id'], $momento, $l['id']);
                            
//                            var_dump($sql_resultados);

                           $resultados = BD::$db->queryAll($sql_resultados);
                           if($key == 0 && $k==0 && $m == 2){
//                               var_dump($sql_resultados);
//                                var_dump($resultados);
                            }
                           $lineamientos_data[$k]['resultados'][$e['id']] = $resultados;
                           
                            
                            
                        }
                        
                    }
                    $rubros_data[$key]['lineamientos'] = $lineamientos_data;
                }
                
                $vars['rubros_data'] = $rubros_data;
//                var_dump($rubros_data);
                $vars['momento'] = $momento;
                View::add_js('public/js/bootbox.min.js'); 
                View::add_js('modules/sievas/scripts/avances/main.js'); 
                View::add_js('modules/sievas/scripts/avances/reporte_evaluacion_red.js'); 
                View::render('avances/reporte_evaluacion_red.php', $vars);
                
            }
            else{
                //diferentes conjuntos de lineamientos
            }
        }
        
    }
    
    public function get_anexos_evaluacion(){
        header('Content-Type: application/json');      
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $evaluacion = filter_input(INPUT_GET, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $momento = 1;        
        
        $sql_anexos = sprintf('SELECT
        gen_documentos.ruta,
        gen_documentos.id,
        gen_documentos.nombre
        FROM
        gen_documentos
        INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_documento = gen_documentos.id
        INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        WHERE
        lineamientos.id = %s
        AND evaluacion.id = %s
        AND momento_evaluacion.cod_momento = %s', $lineamiento, $evaluacion, $momento);

        $anexos = BD::$db->queryAll($sql_anexos);
        
        echo json_encode($anexos);
    }
    
    public function get_data_graficas_consolidados(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){     
            $momento = 1;
            $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
            $momento_evaluacion_interna = BD::$db->queryOne($sql_momento);
            $momento = 2;
            $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
            $momento_evaluacion_externa = BD::$db->queryOne($sql_momento);
        
            $sql_rubros = sprintf('SELECT
            lineamientos.id,
            lineamientos.nom_lineamiento,
            lineamientos.padre_lineamiento
            FROM
            lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
            WHERE
            lineamientos.padre_lineamiento = 0 AND
            evaluacion.id = %s
            ', Auth::info_usuario('evaluacion'));        
        
        
            $rubros = BD::$db->queryAll($sql_rubros);
        
        
            foreach($rubros as $key=>$r){
                $avg = 0;
                 $sql_lineamientos = sprintf("SELECT
                        lineamientos.id AS lineamiento_id,
                        lineamientos.nom_lineamiento,
                        lineamientos.padre_lineamiento
                FROM
                        lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                WHERE
                        lineamientos.padre_lineamiento = %s", $r['id']);

                $lineamientos = BD::$db->queryAll($sql_lineamientos);

                $sql_lineamientos_calificacion = sprintf("SELECT
                gradacion_escalas.valor_escala,
                lineamientos.id,
                lineamientos.padre_lineamiento
                FROM
                momento_resultado_detalle
                left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                WHERE
                momento_evaluacion.id = %s", $momento_evaluacion_interna);

                $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
                
                 foreach($calificaciones as $c){
                    if($r['id'] == $c['padre_lineamiento']){                    
                        foreach($lineamientos as $k=>$l){                        
                            if($l['lineamiento_id'] === $c['id']){
                                  $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $rubros[$key][1]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                            }
                        }
                    }
                }

               $rubros[$key][1]['lineamientos'] = $lineamientos;           
               $rubros[$key][1]['promedio'] = $avg/10;      

               $avg = 0;
                $sql_lineamientos_calificacion = sprintf("SELECT
                gradacion_escalas.valor_escala,
                lineamientos.id,
                lineamientos.padre_lineamiento
                FROM
                momento_resultado_detalle
                left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                left JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                left JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                WHERE
                momento_evaluacion.id = %s", $momento_evaluacion_externa);

                $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
                
                if($calificaciones == null){
                    foreach($lineamientos as $k=>$l){                        
                      $lineamientos[$k]['valor'] = 0;
                      $rubros[$key][2]['calificaciones'][] = 0;
                      $avg += 0;
                    }
                }
                
                 foreach($calificaciones as $c){
                    if($r['id'] == $c['padre_lineamiento']){                    
                        foreach($lineamientos as $k=>$l){                        
                            if($l['lineamiento_id'] === $c['id']){
                                  $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $rubros[$key][2]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                            }
                        }
                    }
                }

               $rubros[$key][2]['lineamientos'] = $lineamientos;           
               $rubros[$key][2]['promedio'] = $avg/10;           

            }
        
        //grafico general
        $sql_calificaciones = sprintf("
                select s1.lineamiento_id, s2.calificacion, s1.nom_lineamiento
                from (
                        SELECT
                                e.id,
                                e.etiqueta,
                                l.id as lineamiento_id,
                                l.nom_lineamiento as nom_lineamiento
                                FROM
                                evaluacion as e
                                INNER JOIN lineamientos_conjuntos as lc ON e.cod_conjunto = lc.id
                                INNER JOIN lineamientos_detalle_conjuntos as ldc ON ldc.cod_conjunto = lc.id
                                INNER JOIN lineamientos as l ON ldc.cod_lineamiento = l.id
                                WHERE
                                e.id = %s AND
                                l.padre_lineamiento = 0
                ) as s1
                left join (
                SELECT
                Avg(momento_resultado_detalle.cod_gradacion_escala) as calificacion,
                lineamientos.padre_lineamiento as padre,
                evaluacion.id
                FROM
                lineamientos
                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                LEFT JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                inner JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                inner JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                evaluacion.id = %s
                AND
                momento_evaluacion.cod_momento = %s
                GROUP BY
                lineamientos.padre_lineamiento
                ) as s2 on s2.padre = s1.lineamiento_id", $evaluacion, $evaluacion, $momento);
            
            $calificaciones = BD::$db->queryAll($sql_calificaciones);
            
            

            $nom_rubros = array_map(function($e){
                return $e['nom_lineamiento'];                
            }, $calificaciones);
            $calificaciones_generales = array(1 => implode(',', array_map(function($e){
                return ($e[1]['promedio'] == null ? '1' : $e[1]['promedio']);                
            }, $rubros)), 2 => implode(',', array_map(function($e){
                return ($e[2]['promedio'] == null ? '1' : $e[2]['promedio']);                
            }, $rubros)));
            
            $vars['nom_rubros'] = $nom_rubros;
            $vars['calificaciones_generales'] = $calificaciones_generales;
        
        $calificaciones_interna = array_map(function($e){
                return implode($e[1]['calificaciones'], ',');                
            }, $rubros);
        $calificaciones_externa = array_map(function($e){
                return implode($e[2]['calificaciones'], ',');                
            }, $rubros);
        
        $json = array(
            'calificaciones_generales' => $calificaciones_generales,
            'calificaciones_interna' => $calificaciones_interna,
            'calificaciones_externa' => $calificaciones_externa
        );
        
        echo json_encode($json);
        }
    }
    
    public function calificaciones_evaluacion_graficas_consolidados(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){     
            $momento = 1;
            $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
            $momento_evaluacion_interna = BD::$db->queryOne($sql_momento);
            $momento = 2;
            $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
            $momento_evaluacion_externa = BD::$db->queryOne($sql_momento);
        
            $sql_rubros = sprintf('SELECT
            lineamientos.id,
            lineamientos.nom_lineamiento,
            lineamientos.padre_lineamiento
            FROM
            lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
            WHERE
            lineamientos.padre_lineamiento = 0 AND
            evaluacion.id = %s
            ', Auth::info_usuario('evaluacion'));        
        
        
            $rubros = BD::$db->queryAll($sql_rubros);
        
        
            foreach($rubros as $key=>$r){
                $avg = 0;
                 $sql_lineamientos = sprintf("SELECT
                        lineamientos.id AS lineamiento_id,
                        lineamientos.nom_lineamiento,
                        lineamientos.padre_lineamiento
                FROM
                        lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                WHERE
                        lineamientos.padre_lineamiento = %s", $r['id']);

                $lineamientos = BD::$db->queryAll($sql_lineamientos);

                $sql_lineamientos_calificacion = sprintf("SELECT
                gradacion_escalas.valor_escala,
                lineamientos.id,
                lineamientos.padre_lineamiento
                FROM
                momento_resultado_detalle
                left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                WHERE
                momento_evaluacion.id = %s", $momento_evaluacion_interna);

                $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
                
                 foreach($calificaciones as $c){
                    if($r['id'] == $c['padre_lineamiento']){                    
                        foreach($lineamientos as $k=>$l){                        
                            if($l['lineamiento_id'] === $c['id']){
                                  $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $rubros[$key][1]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                            }
                        }
                    }
                }

               $rubros[$key][1]['lineamientos'] = $lineamientos;           
               $rubros[$key][1]['promedio'] = $avg/10;      

               $avg = 0;
                $sql_lineamientos_calificacion = sprintf("SELECT
                gradacion_escalas.valor_escala,
                lineamientos.id,
                lineamientos.padre_lineamiento
                FROM
                momento_resultado_detalle
                left JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                left JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                left JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                left JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                WHERE
                momento_evaluacion.id = %s", $momento_evaluacion_externa);

                $calificaciones = BD::$db->queryAll($sql_lineamientos_calificacion);
                
                if($calificaciones == null){
                    foreach($lineamientos as $k=>$l){                        
                      $lineamientos[$k]['valor'] = 0;
                      $rubros[$key][2]['calificaciones'][] = 0;
                      $avg += 0;
                    }
                }
                
                 foreach($calificaciones as $c){
                    if($r['id'] == $c['padre_lineamiento']){                    
                        foreach($lineamientos as $k=>$l){                        
                            if($l['lineamiento_id'] === $c['id']){
                                  $lineamientos[$k]['valor'] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $rubros[$key][2]['calificaciones'][] = ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                                  $avg += ($c['valor_escala'] > 0 ? $c['valor_escala'] : 0);
                            }
                        }
                    }
                }

               $rubros[$key][2]['lineamientos'] = $lineamientos;           
               $rubros[$key][2]['promedio'] = $avg/10;           

            }
        
        //grafico general
        $sql_calificaciones = sprintf("
                select s1.lineamiento_id, s2.calificacion, s1.nom_lineamiento
                from (
                        SELECT
                                e.id,
                                e.etiqueta,
                                l.id as lineamiento_id,
                                l.nom_lineamiento as nom_lineamiento
                                FROM
                                evaluacion as e
                                INNER JOIN lineamientos_conjuntos as lc ON e.cod_conjunto = lc.id
                                INNER JOIN lineamientos_detalle_conjuntos as ldc ON ldc.cod_conjunto = lc.id
                                INNER JOIN lineamientos as l ON ldc.cod_lineamiento = l.id
                                WHERE
                                e.id = %s AND
                                l.padre_lineamiento = 0
                ) as s1
                left join (
                SELECT
                Avg(momento_resultado_detalle.cod_gradacion_escala) as calificacion,
                lineamientos.padre_lineamiento as padre,
                evaluacion.id
                FROM
                lineamientos
                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                LEFT JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                inner JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                inner JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                evaluacion.id = %s
                AND
                momento_evaluacion.cod_momento = %s
                GROUP BY
                lineamientos.padre_lineamiento
                ) as s2 on s2.padre = s1.lineamiento_id", $evaluacion, $evaluacion, $momento);
            
            $calificaciones = BD::$db->queryAll($sql_calificaciones);
            
            

            $nom_rubros = array_map(function($e){
                return $e['nom_lineamiento'];                
            }, $calificaciones);
            $calificaciones_generales = array(1 => implode(',', array_map(function($e){
                return ($e[1]['promedio'] == null ? '1' : $e[1]['promedio']);                
            }, $rubros)), 2 => implode(',', array_map(function($e){
                return ($e[2]['promedio'] == null ? '1' : $e[2]['promedio']);                
            }, $rubros)));
            
            $vars['nom_rubros'] = $nom_rubros;
            $vars['calificaciones_generales'] = $calificaciones_generales;

        
        $vars['rubros'] = $rubros;
//        var_dump($rubros);
        View::add_js('public/js/RGraph/libraries/RGraph.common.core.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.dynamic.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.annotate.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.context.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.effects.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.key.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.resizing.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.tooltips.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.common.zoom.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.bar.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.pie.js'); 
        View::add_js('public/js/RGraph/libraries/RGraph.radar.js'); 
        View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/avances/main.js'); 
        View::add_js('modules/sievas/scripts/avances/calificaciones_evaluacion_graficas_consolidados.js'); 
       View::render('avances/reporte_graficas_consolidados.php', $vars);
         }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
            
            
        
        
        
        
        
    }
    
     public function comite_externo(){
        $sql_comite = "SELECT distinct comite.cod_persona, 
        evaluacion_comite_revision.cod_evaluacion, gen_persona.nombres, evaluacion.etiqueta, comite_cargos.cargo, sys_usuario.username
        FROM sievas.evaluacion_comite_revision
        inner join sievas.evaluacion on sievas.evaluacion_comite_revision.cod_evaluacion = evaluacion.id
        inner join sievas.momento_evaluacion on sievas.evaluacion.id = sievas.momento_evaluacion.cod_evaluacion
        inner join sievas.comite on sievas.momento_evaluacion.id = sievas.comite.cod_momento_evaluacion
        inner join sievas.gen_persona on sievas.comite.cod_persona = sievas.gen_persona.id
        inner join sievas.comite_cargos on sievas.comite.cod_cargo = sievas.comite_cargos.id
        inner join sievas.sys_usuario on sievas.sys_usuario.cod_persona = sievas.gen_persona.id
        where momento_evaluacion.cod_momento = 2
        order by gen_persona.id asc";    
        
        $comite = BD::$db->queryAll($sql_comite);
        $vars['comite'] = $comite; 
        View::render('avances/comite_externo.php', $vars);  
    }

}

