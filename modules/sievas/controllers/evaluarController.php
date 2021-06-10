<?php

require_once LIBS_PATH.'/PHPExcel-1.8/Classes/PHPExcel.php';

Load::model2('sieva_lineamientos');
Load::model2('momento_resultado');
Load::model2('momento_resultado_detalle');
Load::model2('plan_mejoramiento');
Load::model2('plan_mejoramiento_acciones');
Load::model2('plan_mejoramiento_metas');
Load::model2('momento_evaluacion');
Load::model2('gen_documentos');
Load::model2('momento_resultado_anexo');
Load::model2('momento_resultado_detalle');
Load::model2('momento_resultado_detalle_traducciones');
Load::model2('momento_resultado_reevaluacion');
Load::model2('momento_resultado');
Load::model2('evaluacion_complemento');
Load::model2('lineamiento_archivo_tabla');
Load::model2('metaevaluacion');
Load::model2('evaluacion_complemento_documentos');
Load::model2('evaluacion_nal_documentos');
Load::model2('evaluacion_analisis_indicadores');
Load::model2('evaluacion_cuadrosmaestros');
Load::model2('etapas_proceso_avance');

class evaluarController extends ControllerBase{

    public function __construct(){
        parent::__construct();
    }
    
    public function guardar_encuestas(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $vars['e_id'] = $evaluacion;
        $sql = sprintf("select url_encuesta from evaluacion_encuestas where evaluacion_id = %s order by orden asc", $evaluacion);
        $encuestas = BD::$db->queryAll($sql);
        $vars['encuestas'] = $encuestas;
        View::add_css('public/dropzone/dropzone.css');
        View::add_js('public/dropzone/dropzone.js');
        View::add_js('modules/sievas/scripts/evaluar/guardar_encuestas.js');  
        View::render('evaluar/guardar_encuestas.php', $vars);
    }
    
    public function cargar_traduccion(){
        header('Content-Type: application/json');
        $momento_resultado_detalle = filter_input(INPUT_GET, 'momento_resultado_detalle', FILTER_SANITIZE_NUMBER_INT);
        $idioma = filter_input(INPUT_GET, 'idioma', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);
        //buscar traduccion por idioma y momento_resultado_detalle
        $sql_check = sprintf("select * from momento_resultado_detalle_traducciones where momento_resultado_detalle_id = %s and gen_idiomas_id = %s",
                $momento_resultado_detalle, $idioma);
        $check = BD::$db->queryRow($sql_check);
        $traduccion = array();
        switch($tipo){
            case 'fortalezas':
                $traduccion = array(
                    'traduccion' => $check['fortalezas']
                );
                break;
            case 'debilidades':
                $traduccion = array(
                    'traduccion' => $check['debilidades']
                );
                break;
            case 'plan_mejoramiento':
                $traduccion = array(
                    'traduccion' => $check['plan_mejoramiento']
                );
                break;
        }
        echo json_encode($traduccion);
    }
    
    public function renovarsesion(){
        session_start();
    }
    
    public function guardar_traduccion(){
        //guardar y editar traducciones de datos de evaluacion
        //debe ir asociado a un momento_resultado_detalle
        //si no existe crearlo
        //requiere cod_momento_resultado y cod_lineamiento
        //idioma
        
        $momento_resultado_detalle = filter_input(INPUT_POST, 'momento_resultado_detalle', FILTER_SANITIZE_NUMBER_INT);
        if(!$momento_resultado_detalle > 0){
            $momento_resultado = filter_input(INPUT_POST, 'momento_resultado', FILTER_SANITIZE_NUMBER_INT);
            $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
            $sql = sprintf("select id from momento_resultado_detalle where cod_momento_resultado = %s and cod_lineamiento = %s", $momento_resultado, $lineamiento);
            $momento_resultado_detalle = BD::$db->queryOne($sql);
        }
        
        if($momento_resultado_detalle > 0){            
            $fortalezas_data = filter_input(INPUT_POST, 'fortalezas', FILTER_SANITIZE_STRING);
            $debilidades_data = filter_input(INPUT_POST, 'debilidades', FILTER_SANITIZE_STRING);
            $planesmejora_data = filter_input(INPUT_POST, 'plan_mejoramiento', FILTER_SANITIZE_STRING);
            $idioma = filter_input(INPUT_POST, 'idioma', FILTER_SANITIZE_STRING);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            
            
            //buscar traduccion por idioma y momento_resultado_detalle
            $sql_check = sprintf("select id from momento_resultado_detalle_traducciones where momento_resultado_detalle_id = %s and gen_idiomas_id = %s",
                    $momento_resultado_detalle, $idioma);
            $check = BD::$db->queryOne($sql_check);
            
            if($check > 0){
                 //actualizar
                $traduccion = new momento_resultado_detalle_traducciones();
                $traduccion->id = $check;
                $traduccion->momento_resultado_detalle_id = $momento_resultado_detalle;
                $traduccion->gen_idiomas_id = $idioma;
                switch($tipo){
                    case 'fortalezas':
                        $traduccion->fortalezas = $fortalezas_data;
                        break;
                    case 'debilidades':
                        $traduccion->debilidades = $debilidades_data;
                        break;
                    case 'plan_mejoramiento':
                        $traduccion->plan_mejoramiento = $planesmejora_data;
                        break;
                }

                if($traduccion->update()){
                    echo "ok";
                }
                else{
                    echo $traduccion->error_msg();
                    echo $traduccion->error_sql();
                }
            }
            else{
                //crear nuevo
                $traduccion = new momento_resultado_detalle_traducciones();
                $traduccion->momento_resultado_detalle_id = $momento_resultado_detalle;
                $traduccion->gen_idiomas_id = $idioma;
                switch($tipo){
                    case 'fortalezas':
                        $traduccion->fortalezas = $fortalezas_data;
                        break;
                    case 'debilidades':
                        $traduccion->debilidades = $debilidades_data;
                        break;
                    case 'plan_mejoramiento':
                        $traduccion->plan_mejoramiento = $planesmejora_data;
                        break;
                }

                if($traduccion->save()){
                    echo "ok";
                }
                else{
                    echo "err";
                }
            }
            
        }
        else{
            var_dump($sql);
            echo "sql";
        }
    }
    
    public function subirarchivo(){
//        var_dump($_FILES);
        $valid_file = true;
        $id_evaluacion = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
        var_dump($tipo);
        if($id_evaluacion > 0 && $tipo != null && $_FILES['file']['name']){
            if(!$_FILES['file']['error']){
                $rel_path = 'public/files/'.time().'-'.$_FILES['file']['name'];
                $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
		if($_FILES['file']['size'] > (10240000)) //can't be larger than 1 MB
		{
			$valid_file = false;
//			$message = 'Oops!  Your file\'s size is to large.';
		}
                
		
		//if the file has passed the test
		if($valid_file)
		{
			//move it to where we want it to be
//                        var_dump($real_path);
                        $move = move_uploaded_file($_FILES['file']['tmp_name'], $real_path);
//                        var_dump($move);
			if($move){
                            $model = new gen_documentos();
                            $model->ruta = $rel_path;
                            $model->nombre = $_FILES['file']['name'];
                            if($model->save()){
                                switch($tipo){
                                    case 'predictamen':
                                        $predictamen_id = $model->id;
                                        $sql = sprintf('update evaluacion set predictamen_id=%s where id=%s', $predictamen_id, $id_evaluacion);
                                        BD::$db->query($sql);
                                        break;
                                    case 'dictamen':
                                        $dictamen_id = $model->id;
                                        $sql = sprintf('update evaluacion set dictamen_id=%s where id=%s', $dictamen_id, $id_evaluacion);
                                        BD::$db->query($sql);
                                        break;
                                    case 'encuesta':
                                        //validar archivo
                                        echo "revisando encuesta";
                                        $objPHPExcel = PHPExcel_IOFactory::load($real_path);
                                        $objPHPExcel->setActiveSheetIndex(0);
                                        echo $objPHPExcel->getActiveSheet()->getHighestColumn();
                                        echo $objPHPExcel->getActiveSheet()->getHighestRow();
//                                        echo $objPHPExcel->getActiveSheet()->getCell('Y2');
                                        
                                        //guardar datos
                                        break;
                                }
                                
                                echo json_encode(array(
                                    'nombre' => $_FILES['file']['name'],
                                    'url' => $rel_path
                                ));
                            }
                            else{                   
                                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                                echo "No se pudo subir el archivo1";
                            }
                            
                        }
                        else{                        
                            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                            echo "No se pudo subir el archivo2";
                        }
//			$message = 'Congratulations!  Your file was accepted.';
                       
		}
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo subir el archivo3";
                }
            }
            
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se pudo subir el archivo4";
//            var_dump($id_evaluacion);
//            var_dump($tipo);
        }
        
    }
    
    

    public function guardar_avance_tablero_control(){
        header('Content-Type: application/json');
        $evaluacion = Auth::info_usuario('evaluacion');
        $id_proceso = filter_input(INPUT_POST, 'proceso', FILTER_SANITIZE_NUMBER_INT);
        $id_etapa = filter_input(INPUT_POST, 'etapa', FILTER_SANITIZE_NUMBER_INT);
        $avance = filter_input(INPUT_POST, 'avance', FILTER_SANITIZE_NUMBER_INT);

        $etapa = new etapas_proceso_avance();
//        $etapa->begin();

        $etapa->etapas_proceso_id = $id_proceso;
        $etapa->evaluacion_id = $evaluacion;
        $etapa->escala_avance = $avance;
        if($id_etapa > 0){
            $etapa->id = $id_etapa;
            if($etapa->update()){
                echo json_encode(array('id' => $etapa->id));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            }
        }
        else{
            $sql = sprintf('select id from etapas_proceso_avance where evaluacion_id=%s and etapas_proceso_id=%s',
                    $etapa->evaluacion_id, $etapa->etapas_proceso_id);
            $etapa_id = BD::$db->queryOne($sql);

            if($etapa_id > 0){
                $etapa->id = $etapa_id;
                if($etapa->update()){
                    echo json_encode(array('id' => $etapa->id));
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                }
            }
            else{
                if($etapa->save()){
                    echo json_encode(array('id' => $etapa->id));
                }
                else{
                   header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                }
            }

        }

    }

    public function tablero_control(){
        $evaluacion = Auth::info_usuario('evaluacion');

       $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];

        $sql = sprintf('select * from etapas_proceso_avance where evaluacion_id=%s order by etapas_proceso_id asc',
                    $evaluacion);
        $etapas = BD::$db->queryAll($sql);

        $etapas_aux = array();
        foreach($etapas as $e){
            $etapas_aux[$e['etapas_proceso_id']] = $e;
        }

        $vars['etapas'] = $etapas_aux;
        /*
         * 1 -> no se ha conformado el cei
         * 2 -> se completo la integracion de la ei
         */
        $vars['err'] = filter_input(INPUT_GET, 'err', FILTER_SANITIZE_NUMBER_INT);

//        var_dump($etapas_aux);

        View::add_js('public/js/bootstrap-slider/bootstrap-slider.min.js');
        View::add_css('public/js/bootstrap-slider/css/bootstrap-slider.min.css');
        View::add_js('modules/sievas/scripts/evaluar/tablero_control.js');
        View::render('evaluar/tablero_control.php', $vars);
    }

    public function generar_tabla_estadistica(){
        $rubro = $_GET['id'];
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql = sprintf('select num_orden from lineamientos where lineamientos.id = %s', $rubro);
        $data_rubro = BD::$db->queryOne($sql);
        
        switch($data_rubro){
            case 1:
                //desde A4
                $col_inicial = 65;
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R01.ImpactoSocialdelaFormacion.xlsx");
                $objPHPExcel->setActiveSheetIndex(0);
               
                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h, campo_i, campo_j, campo_k, campo_l,
                    campo_m, campo_n from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);

                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+4), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+4), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+4), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+4), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+4), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+4), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+4), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+4), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+4), $f['campo_h']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+9).($key+4), $f['campo_i']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+10).($key+4), $f['campo_j']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+11).($key+4), $f['campo_k']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+12).($key+4), $f['campo_l']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+13).($key+4), $f['campo_m']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+14).($key+4), $f['campo_n']);
                }

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 2:
                //desde A4
                $col_inicial = 65;               
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R02.Investigacion.xlsx");
//                var_dump($objPHPExcel);
//                echo "hola2";
                $objPHPExcel->setActiveSheetIndex(0);
//                echo "hola3";
                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h, campo_i, campo_j, campo_k, campo_l,
                    campo_m, campo_n, campo_o, campo_p, campo_q, campo_r, campo_s, campo_t from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);
//                var_dump($sql2);
                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+4), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+4), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+4), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+4), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+4), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+4), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+4), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+4), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+4), $f['campo_h']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+9).($key+4), $f['campo_i']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+10).($key+4), $f['campo_j']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+11).($key+4), $f['campo_k']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+12).($key+4), $f['campo_l']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+13).($key+4), $f['campo_m']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+14).($key+4), $f['campo_n']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+15).($key+4), $f['campo_o']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+16).($key+4), $f['campo_p']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+17).($key+4), $f['campo_q']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+18).($key+4), $f['campo_r']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+19).($key+4), $f['campo_s']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+20).($key+4), $f['campo_t']);
                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 3:
                $col_inicial = 65;
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R03.Ingreso,PermanenciayEficienciaTerminalenlaFormacion.xlsx");
                $objPHPExcel->setActiveSheetIndex(0);

                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h, campo_i, campo_j, campo_k, campo_l,
                    campo_m, campo_n, campo_o from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);

                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+4), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+4), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+4), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+4), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+4), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+4), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+4), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+4), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+4), $f['campo_h']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+9).($key+4), $f['campo_i']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+10).($key+4), $f['campo_j']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+11).($key+4), $f['campo_k']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+12).($key+4), $f['campo_l']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+13).($key+4), $f['campo_m']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+14).($key+4), $f['campo_n']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+15).($key+4), $f['campo_o']);

                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 4:
                $col_inicial = 65;
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R04.ProfesoresVinculadosalaFormacion.xlsx");
                $objPHPExcel->setActiveSheetIndex(0);

                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h, campo_i, campo_j, campo_k, campo_l,
                    campo_m, campo_n, campo_o from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);

                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+4), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+4), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+4), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+4), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+4), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+4), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+4), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+4), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+4), $f['campo_h']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+9).($key+4), $f['campo_i']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+10).($key+4), $f['campo_j']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+11).($key+4), $f['campo_k']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+12).($key+4), $f['campo_l']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+13).($key+4), $f['campo_m']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+14).($key+4), $f['campo_n']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+15).($key+4), $f['campo_o']);

                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 5:
                 $col_inicial = 65;
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R05.PertinenciadelModeloEducativoyEstructuraCurricular.xlsx");
                $objPHPExcel->setActiveSheetIndex(0);

                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h, campo_i, campo_j, campo_k, campo_l,
                    campo_m, campo_n, campo_o, campo_p, campo_q, campo_r, campo_s, campo_t
                    , campo_u, campo_v, campo_w, campo_x, campo_y, campo_z, campo_aa,
                    campo_ab, campo_ac, campo_ad, campo_ae, campo_af, campo_ag, campo_ah
                    , campo_ai, campo_aj, campo_ak, campo_al from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);

                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+5), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+5), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+5), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+5), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+5), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+5), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+5), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+5), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+5), $f['campo_h']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+9).($key+5), $f['campo_i']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+10).($key+5), $f['campo_j']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+11).($key+5), $f['campo_k']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+12).($key+5), $f['campo_l']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+13).($key+5), $f['campo_m']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+14).($key+5), $f['campo_n']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+15).($key+5), $f['campo_o']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+16).($key+5), $f['campo_p']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+17).($key+5), $f['campo_q']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+18).($key+5), $f['campo_r']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+19).($key+5), $f['campo_s']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+20).($key+5), $f['campo_t']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+21).($key+5), $f['campo_u']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+22).($key+5), $f['campo_v']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+23).($key+5), $f['campo_w']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+24).($key+5), $f['campo_x']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+25).($key+5), $f['campo_y']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial).($key+5), $f['campo_z']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+1).($key+5), $f['campo_aa']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+2).($key+5), $f['campo_ab']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+3).($key+5), $f['campo_ac']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+4).($key+5), $f['campo_ad']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+5).($key+5), $f['campo_ae']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+6).($key+5), $f['campo_af']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+7).($key+5), $f['campo_ag']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+8).($key+5), $f['campo_ah']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+9).($key+5), $f['campo_ai']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+10).($key+5), $f['campo_aj']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+11).($key+5), $f['campo_ak']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+12).($key+5), $f['campo_al']);

                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 6:
                $col_inicial = 65;
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R06.EstrategiasMetodologicasdeApredizajeenlosProcesosFormativos.xlsx");
                $objPHPExcel->setActiveSheetIndex(0);

                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);

                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+4), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+4), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+4), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+4), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+4), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+4), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+4), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+4), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+4), $f['campo_h']);


                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 7:
                $col_inicial = 65;
                $objPHPExcel = PHPExcel_IOFactory::load(LIBS_PATH . "/PHPExcel-1.8/templates/R07.Infraestructura.xlsx");
                $objPHPExcel->setActiveSheetIndex(0);

                $sql2 = sprintf('select anio, campo_a, campo_b, campo_c, campo_d, campo_e,
                    campo_f, campo_g, campo_h, campo_i, campo_j, campo_k, campo_l,
                    campo_m, campo_n, campo_o, campo_p, campo_q, campo_r, campo_s, campo_t
                    , campo_u, campo_v, campo_w, campo_x, campo_y, campo_z, campo_aa,
                    campo_ab, campo_ac, campo_ad, campo_ae from tablas_estadisticas_datos where lineamientos_id=%s
                    and cod_evaluacion=%s order by anio desc', $rubro, $evaluacion);

                $filas = BD::$db->queryAll($sql2);

                foreach($filas as $key=>$f){
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).($key+4), $f['anio']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+1).($key+4), $f['campo_a']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+2).($key+4), $f['campo_b']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+3).($key+4), $f['campo_c']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+4).($key+4), $f['campo_d']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+5).($key+4), $f['campo_e']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+6).($key+4), $f['campo_f']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+7).($key+4), $f['campo_g']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+8).($key+4), $f['campo_h']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+9).($key+4), $f['campo_i']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+10).($key+4), $f['campo_j']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+11).($key+4), $f['campo_k']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+12).($key+4), $f['campo_l']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+13).($key+4), $f['campo_m']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+14).($key+4), $f['campo_n']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+15).($key+4), $f['campo_o']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+16).($key+4), $f['campo_p']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+17).($key+4), $f['campo_q']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+18).($key+4), $f['campo_r']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+19).($key+4), $f['campo_s']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+20).($key+4), $f['campo_t']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+21).($key+4), $f['campo_u']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+22).($key+4), $f['campo_v']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+23).($key+4), $f['campo_w']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+24).($key+4), $f['campo_x']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial+25).($key+4), $f['campo_y']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial).($key+4), $f['campo_z']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+1).($key+4), $f['campo_aa']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+2).($key+4), $f['campo_ab']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+3).($key+4), $f['campo_ac']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+4).($key+4), $f['campo_ad']);
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($col_inicial).chr($col_inicial+5).($key+4), $f['campo_ae']);


                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="reporte.xlsx"');
                header('Cache-Control: max-age=0');
                ob_end_clean();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                ob_end_clean();
                break;
            case 8:
                break;
            case 9:
                break;
            case 10:
                break;
        }

    }

    public function eliminar_data_tabla_estadistica(){
        header('Content-Type: application/json');
        $id = $_POST['dato_id'];
        $tipo = $_POST['tipo'];
        if($tipo>0){
            $sql_del = sprintf("delete from tablas_estadisticas_profesores where tablas_estadisticas_datos_id = %s", $id);
            BD::$db->query($sql_del);
        }
        
        $sql = sprintf("delete from tablas_estadisticas_datos where id=%s", $id);
//        var_dump($sql);
        if(!PEAR::isError(BD::$db->query($sql))){
            echo json_encode(array('status' => 'ok'));
        }
        else{
            echo json_encode(array('error' => 'true'));
        }
    }

    public function guardar_data_tabla_estadistica_profesores(){
        header('Content-Type: application/json');
        $evaluacion = Auth::info_usuario('evaluacion');
        $id = $_POST['id'];
        $data = $_POST['data'];
       
        $result = [];
//        var_dump($_SERVER['CONTENT_LENGTH']);
//        $rawPost = file_get_contents('php://input');
//        var_dump(strlen($rawPost));
//        $rawPost = file_get_contents('php://input');
//        $r_ini = strpos($rawPost, 'rubro=');
//        $r_end = strrpos($rawPost, '&', $r_ini);
//        $raw_rubro = substr($rawPost, $r_ini+6, $r_end-$r_ini-6);
//        $d_ini = strpos($rawPost, 'dato_id=');
//        $d_end = strlen($rawPost);
//        $raw_d = substr($rawPost, $d_ini+8, $d_end);
        $dato_id = $_POST['dato_id'] > 0 ? $_POST['dato_id'] : $raw_d;
        $rubro = $_POST['rubro'] > 0 ? $_POST['rubro'] : $raw_rubro;
        $campos = [];
        $valores = [];
        $update = [];

        $profesores = [];
//        var_dump($data['anio']);
        $tr = BD::$db->beginTransaction();
        foreach($data['data'] as $dt){
//            var_dump("hola");
//            var_dump($dt);
            $campos_aux = [];
            $valores_aux = [];
            $update_aux = [];
            foreach($dt as $d){
                $campos_aux[] = $d["campo"];
                $valores_aux[] = "'".$d["valor"]."'";
                $update_aux[] = $d["campo"].'='."'".$d["valor"]."'";
            }


            $profesores[] = array(
                'campos_aux' => $campos_aux,
                'valores_aux' => $valores_aux,
                'update_aux' => $update_aux
            );
//
//            var_dump($campos_aux);
//            var_dump($valores_aux);
//            var_dump($update_aux);

        }

        $campos[] = 'lineamientos_id';
        $valores[] = $rubro;
        $campos[] = 'cod_evaluacion';
        $valores[] = $evaluacion;

        $campos = join(',', $campos);
        $valores = join(',', $valores);
        $update = join(',', $update_aux);


        if($dato_id > 0){

            
            $sql = sprintf("update tablas_estadisticas_datos set %s where id=%s",
                    $update, $dato_id);
           // var_dump($sql);
            if(PEAR::isError(BD::$db->query($sql))){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('status' => 'error', 'sql' => $sql));
                $tr = BD::$db->rollback();
            }
            else{
                $sql_del = sprintf("delete from tablas_estadisticas_profesores where tablas_estadisticas_datos_id = %s", $dato_id);
                BD::$db->query($sql_del);
                $id = $dato_id;
                foreach($profesores as $p){
                    $p['campos_aux'][] = 'tablas_estadisticas_datos_id';
                    $p['valores_aux'][] = $id;
                    $p['campos_aux'][] = 'anio';
                    $p['valores_aux'][] = $data['anio'];
                    $sql_profesores = sprintf("insert into tablas_estadisticas_profesores(%s) values (%s)",
                        join(',', $p['campos_aux']), join(',', $p['valores_aux']));
                    //var_dump($sql_profesores);
                    if(PEAR::isError(BD::$db->query($sql_profesores))){
                      header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                      echo json_encode(array('status' => 'error', 'sql' => $sql));
                      $tr = BD::$db->rollback();
                    }
                    else{
                      $tr = BD::$db->commit();
                    }
                }
                echo json_encode(array('id' => $id));
            }
        }
        else{
            $sql = sprintf("insert into tablas_estadisticas_datos(%s) values (%s)",
                    $campos, $valores);
            //var_dump($valores);
            if(PEAR::isError(BD::$db->query($sql))){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('status' => 'error', 'sql' => $sql));
                $tr = BD::$db->rollback();
            }
            else{
                $id = BD::$db->lastInsertID();
                foreach($profesores as $p){
                    $p['campos_aux'][] = 'tablas_estadisticas_datos_id';
                    $p['valores_aux'][] = $id;
                    $p['campos_aux'][] = 'anio';
                    $p['valores_aux'][] = $data['anio'];
                    $sql_profesores = sprintf("insert into tablas_estadisticas_profesores(%s) values (%s)",
                        join(',', $p['campos_aux']), join(',', $p['valores_aux']));
                    //var_dump($sql_profesores);
                    if(PEAR::isError(BD::$db->query($sql_profesores))){
                      header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                      echo json_encode(array('status' => 'error', 'sql' => $sql));
                      $tr = BD::$db->rollback();
                    }
                    else{
                      $tr = BD::$db->commit();
                    }
                }
                echo json_encode(array('id' => $id));
            }
        }
    }

    public function guardar_data_tabla_estadistica(){
        header('Content-Type: application/json');
        $evaluacion = Auth::info_usuario('evaluacion');
        $id = $_POST['id'];
        $data = $_POST['data'];
        $subtabla = $_POST['subtabla'];
        $dato_id = $_POST['dato_id'];
        $rubro = $_POST['rubro'];
        $campos = [];
        $valores = [];
        $update = [];

//        var_dump($data);

        foreach($data as $d){
            $campos[] = $d["campo"];
            $valores[] = "'".$d["valor"]."'";
            $update[] = $d["campo"].'='."'".$d["valor"]."'";
        }
        if($subtabla > 0){
            $campos[] = 'subtabla';
            $valores[] = $rubro.'.'.$subtabla;
        }
        $campos[] = 'lineamientos_id';
        $valores[] = $rubro;
        $campos[] = 'cod_evaluacion';
        $valores[] = $evaluacion;

        $campos = join(',', $campos);
        $valores = join(',', $valores);
        $update = join(',', $update);


        if($dato_id > 0){
            $sql = sprintf("update tablas_estadisticas_datos set %s where id=%s",
                    $update, $dato_id);
//            echo $sql;
            if(PEAR::isError(BD::$db->query($sql))){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('status' => 'error', 'sql' => $sql));
            }
            else{
                echo json_encode(array('id' => $dato_id));
            }
        }
        else{
            $sql = sprintf("insert into tablas_estadisticas_datos(%s) values (%s)",
                    $campos, $valores);
            if(PEAR::isError(BD::$db->query($sql))){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('status' => 'error', 'sql' => $sql));
            }
            else{
                echo json_encode(array('id' => BD::$db->lastInsertID()));
            }
        }
    }

    public function tablas_estadisticas_gestor(){
        $rubro_id = filter_input(INPUT_GET, 'r', FILTER_SANITIZE_NUMBER_INT);
        $subtabla = filter_input(INPUT_GET, 'subtabla', FILTER_SANITIZE_NUMBER_INT);
        $evaluacion = Auth::info_usuario('evaluacion');

        $sql_rubro = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento,
        lineamientos.num_orden
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.id = %s
        ', $rubro_id);


        $rubro = BD::$db->queryRow($sql_rubro);
//        var_dump($rubro);
        $vars['rubro'] = $rubro;
        $sql_tabla_datos = '';
        if($rubro['num_orden'] == 4){
            $sql_tabla_datos = sprintf('SELECT *
                from tablas_estadisticas_datos
                where lineamientos_id = %s and cod_evaluacion=%s order by anio desc', $rubro_id, $evaluacion);

//            var_dump($sql_tabla_datos);
            $tabla_datos = BD::$db->queryAll($sql_tabla_datos);
//            var_dump($tabla_datos);
            foreach($tabla_datos as $key=>$tdata){
                $sql_tabla_profesores = sprintf('SELECT *
                    from tablas_estadisticas_profesores 
                    where tablas_estadisticas_profesores.tablas_estadisticas_datos_id = %s', $tdata['id']);

                $tabla_profesores = BD::$db->queryAll($sql_tabla_profesores);
                $tabla_datos[$key]['profesores'] = $tabla_profesores;
            }
//            $sql_tabla_profesores = sprintf('SELECT tablas_estadisticas_profesores.*
//                from tablas_estadisticas_datos
//                inner join tablas_estadisticas_profesores on tablas_estadisticas_datos.id = tablas_estadisticas_profesores.tablas_estadisticas_datos_id
//                where tablas_estadisticas_datos.lineamientos_id = %s and tablas_estadisticas_datos.cod_evaluacion=%s order by tablas_estadisticas_datos.anio desc', $rubro_id, $evaluacion);
//
//            $tabla_profesores = BD::$db->queryAll($sql_tabla_profesores);
//            var_dump($tabla_profesores);
//            var_dump($sql_tabla_profesores);

            $vars['tabla_datos'] = $tabla_datos;
            $vars['evaluacion'] = $evaluacion;
            $vars['tabla_profesores'] = $tabla_profesores;
        }
        else{
              $sql_tabla_datos = sprintf('SELECT *
                from tablas_estadisticas_datos
                where lineamientos_id = %s and cod_evaluacion=%s order by anio desc', $rubro_id, $evaluacion);
            if($subtabla > 0){
                  $sql_tabla_datos = sprintf('SELECT *
                from tablas_estadisticas_datos
                where lineamientos_id = %s and cod_evaluacion=%s and subtabla="%s" order by anio desc', $rubro_id, $evaluacion, $rubro_id.'.'.$subtabla);
            }
            
//            var_dump($sql_tabla_datos);
          

            $tabla_datos = BD::$db->queryAll($sql_tabla_datos);

            $vars['tabla_datos'] = $tabla_datos;
            
//            echo json_encode($tabla_datos);
        }

        $vars['subtabla'] = $subtabla;
        View::add_js('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js'); 
        View::add_js('modules/sievas/scripts/evaluar/gestor_tablas.js');
        View::render('evaluar/tablas_estadisticas_gestor.php', $vars);
    }

    public function tablas_estadisticas_reporte(){
        $rubro_id = filter_input(INPUT_GET, 'r', FILTER_SANITIZE_NUMBER_INT);
        $subtabla = filter_input(INPUT_GET, 'subtabla', FILTER_SANITIZE_NUMBER_INT);
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_rubro = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento,
        lineamientos.num_orden
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.id = %s
        ', $rubro_id);


        $rubro = BD::$db->queryRow($sql_rubro);
//        var_dump($rubro);
        $vars['rubro'] = $rubro;

        $sql_tabla_datos = sprintf('SELECT *
            from tablas_estadisticas_datos
            where lineamientos_id = %s and cod_evaluacion=%s order by anio desc', $rubro_id, $evaluacion);

        $tabla_datos = BD::$db->queryAll($sql_tabla_datos);

        $vars['tabla_datos'] = $tabla_datos;
        $vars['subtabla'] = $subtabla;

        View::add_js('modules/sievas/scripts/evaluar/gestor_tablas.js');
        View::render('evaluar/tablas_estadisticas_reporte.php', $vars);
    }

    public function tablas_estadisticas(){
         if(Auth::info_usuario('ev_tablero') > 0){
           $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
               inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
               etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 1', Auth::info_usuario('evaluacion'));
           $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
           if($rs_tab_cond['escala_avance'] == null){
               $rs_tab_cond['escala_avance'] = 0;
           }
           $sql_tab_cond_2 = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
               inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
               etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 2', Auth::info_usuario('evaluacion'));
           $rs_tab_cond_2 = BD::$db->queryRow($sql_tab_cond_2);
           if($rs_tab_cond_2['escala_avance'] == null){
               $rs_tab_cond_2['escala_avance'] = 0;
           }

           if($rs_tab_cond['escala_avance'] != 10 ){
               header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=1');
           }
           else{
               if($rs_tab_cond_2['escala_avance'] == 10){
                   header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=2');
               }
           }
       }
        
        
        $evaluacion = Auth::info_usuario('evaluacion');

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

        $vars['rubros'] = $rubros;

        $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];

        View::render('evaluar/tablas_estadisticas.php', $vars);
    }

    public function tablas_estadisticas_r(){
        $evaluacion = Auth::info_usuario('evaluacion');

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

        $vars['rubros'] = $rubros;
        View::render('evaluar/tablas_estadisticas_r.php', $vars);
    }
    
    
    public function resetearevaluacion(){
        $evaluacion_anterior = Auth::info_usuario('ev_anterior');
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        
        $sql = sprintf("SELECT 
			momento_resultado_detalle.id as mr
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE evaluacion.id = %s AND momento_evaluacion.cod_momento in (1,2)", $evaluacion_actual);
        
        $data = BD::$db->queryAll($sql);
        $data = array_map(function($d){
            return $d['mr'];
        }, $data);
        $data = implode(',', $data);
        //vaciar momento_resultado_detalle
        $sql_update = sprintf("update momento_resultado_detalle set fortalezas='', 
                debilidades='', plan_mejoramiento='', cod_gradacion_escala=null 
                where id in (%s)", $data);
        
        var_dump($sql_update);
        
        //eliminar anexos
        $sql_delete = sprintf("delete momento_resultado_anexo.* from momento_resultado_anexo
            inner join momento_evaluacion on momento_evaluacion.id = momento_resultado_anexo.cod_momento_evaluacion
            inner join evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
            where evaluacion.id = %s", $evaluacion_actual);
        
        var_dump($sql_delete);
        
        
        
        if($evaluacion_anterior > 0){
            //reevaluacion
            //vaciar momento_resultado_reevaluacion
            $sql_update_reev = sprintf("update momento_resultado_reevaluacion set fortalezas_opcion=null, 
                debilidades_opcion=null, planesmejora_opcion=null, fortalezas_data='', 
                debilidades_data='', planesmejora_data='' 
                where cod_momento_resultado in (%s)", $data);
            
            var_dump($sql_update_reev);
            
            //reintegrar anexos de la evaluacion anterior
            
            
        }
  
    }

    public function reevaluar(){
        $evaluacion = Auth::info_usuario('ev_anterior');
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $rol = Auth::info_usuario('rol');
        $momento_evaluacion = 2;
        $momento = 0;
        $evaluacion_data = array();
        $momento_actual_arr = $this->get_momento_actual();
        if($rol == null){
            $evaluacion_actual = $_GET['evaluacion'];

        }

        $sql_evaluacion = sprintf("SELECT e.etiqueta, e.ev_cna, p.programa, i.nom_institucion, pa.nom_pais FROM sievas.evaluacion as e
            inner join sievas.eval_programas as p on e.cod_evaluado = p.id
            inner join sievas.eval_instituciones as i on p.cod_institucion = i.id
            inner join sievas.gen_paises as pa on i.cod_pais = pa.id
            where e.tipo_evaluado = 2 and e.id = %s", $evaluacion_actual);

            $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
            $vars['evaluacion_data'] = $evaluacion_data;

        if($evaluacion > 0){
            if($rol == null){
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                $momento = 2;
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
            else{
                $momento_actual = $this->get_momento_actual();
                $momento_evaluacion = $momento_actual["momento_id"];
                $momento = $_GET['momento'];
                if($_GET['momento'] == null)
                        $momento = 2;
                if($rol == 1 || $momento_actual["cod_momento"] == '2' || $rol == null){
                    $evaluacion = Auth::info_usuario('evaluacion');
                    if($evaluacion > 0){
                        $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $momento, $evaluacion);
                        $momento_evaluacion = BD::$db->queryOne($sql_momento);
                    }
                }
                else{
                    if($rol == 2 && $momento_actual["cod_momento"] == '1' && $momento == 2){
//                        header('Location: index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&momento=1');
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
        ', $evaluacion_actual);


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
                    lineamientos.padre_lineamiento = %s order by num_orden asc", $r['id']);

//            var_dump($sql_lineamientos);

            $lineamientos = BD::$db->queryAll($sql_lineamientos);

            if($momento_actual_arr['cod_momento'] == 1){

            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            momento_resultado_detalle.id as momento_resultado_detalle_id,
            gradacion_escalas.desc_escala,
            gradacion_escalas.id as escala_id,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.cod_momento = %s",
                    $r['id'],
                    $evaluacion,
                    1);

            }
            else{
                $sql_lineamientos_data = sprintf("SELECT
                lineamientos.id,
                momento_resultado_detalle.fortalezas,
                momento_resultado_detalle.debilidades,
                momento_resultado_detalle.plan_mejoramiento,
                momento_resultado_detalle.cod_gradacion_escala,
                momento_resultado_detalle.id as momento_resultado_detalle_id,
                gradacion_escalas.desc_escala,
                gradacion_escalas.id as escala_id,
                lineamientos.padre_lineamiento
                FROM
                evaluacion
                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.cod_momento = %s",
                        $r['id'],
                        $evaluacion_actual,
                        1);
            }
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);

//            foreach($lineamientos as $k=>$l){

//            }
            foreach($lineamientos_data as $ld){

                $sql_lineamientos_data_reevaluacion = sprintf("SELECT
                    momento_resultado_reevaluacion.*,
                    momento_resultado_reevaluacion.id as momento_resultado_reevaluacion_id
                FROM
                    sievas.momento_resultado_reevaluacion
                        INNER JOIN
                    lineamientos ON momento_resultado_reevaluacion.cod_lineamiento = lineamientos.id
                        INNER JOIN
                    momento_resultado ON momento_resultado_reevaluacion.cod_momento_resultado = momento_resultado.id
                        INNER JOIN
                    momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                        INNER JOIN
                    evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                    lineamientos.id = %s
                        AND momento_evaluacion.cod_momento = %s
                        AND evaluacion.id = %s",
                        $ld['id'],
                        $momento_actual_arr['cod_momento'] ,
                        $evaluacion_actual);

                $lineamientos_data_reevaluacion = BD::$db->queryRow($sql_lineamientos_data_reevaluacion);

                $sql_evaluacion_actual_data = sprintf("SELECT
                lineamientos.id,
                momento_resultado_detalle.id as resultado,
                momento_resultado_detalle.fortalezas,
                momento_resultado_detalle.debilidades,
                momento_resultado_detalle.plan_mejoramiento,
                momento_resultado_detalle.cod_gradacion_escala,
                momento_resultado_detalle.id as momento_resultado_detalle_id,
                gradacion_escalas.desc_escala,
                gradacion_escalas.id as escala_id,
                lineamientos.padre_lineamiento
                FROM
                evaluacion
                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                WHERE lineamientos.id = %s AND evaluacion.id = %s AND momento_evaluacion.cod_momento = %s",
                        $ld['id'],
                        $evaluacion_actual,
                        $momento_actual_arr['cod_momento'] );
                $evaluacion_actual_data = BD::$db->queryRow($sql_evaluacion_actual_data);


				if(!PEAR::isError($ld) && $ld['escala_id'] > 0){
					$sql_gradacion = sprintf("SELECT
					gradacion.nivel_bajo,
					gradacion.nivel_medio,
					gradacion.nivel_alto
					FROM
					gradacion_escalas
					INNER JOIN gradacion ON gradacion_escalas.cod_gradacion = gradacion.id
					WHERE
					gradacion_escalas.id = %s", $ld['escala_id'] );
				}
				else{
					if($evaluacion_actual_data['escala_id'] > 0){;
						$sql_gradacion = sprintf("SELECT
						gradacion.nivel_bajo,
						gradacion.nivel_medio,
						gradacion.nivel_alto
						FROM
						gradacion_escalas
						INNER JOIN gradacion ON gradacion_escalas.cod_gradacion = gradacion.id
						WHERE
						gradacion_escalas.id = %s", $evaluacion_actual_data['escala_id'] );
					}
					else{
						$sql_gradacion = sprintf("SELECT
						gradacion.nivel_bajo,
						gradacion.nivel_medio,
						gradacion.nivel_alto
						FROM
						evaluacion
						INNER JOIN gradacion ON evaluacion.gradacion_id = gradacion.id
						WHERE
						evaluacion.id = %s", $evaluacion_actual );
					}
				}

             
				
				//var_dump($sql_gradacion);
                $gradacion = BD::$db->queryRow($sql_gradacion);
				
                $sql_calificacion_item = sprintf('select
                    gradacion_escalas.valor_escala
                    from gradacion_escalas where id=%s', $ld['escala_id']);

                $_calificacion = BD::$db->queryOne($sql_calificacion_item);

                $calificacion = (($_calificacion/10)*100);
                if($momento_actual_arr['cod_momento'] == 1){
                    $sql_puntaje = sprintf("SELECT
                    Sum(gradacion_escalas.valor_escala)
                    FROM
                    momento_resultado_detalle
                    INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    WHERE
                    momento_evaluacion.cod_momento = %s AND
                    evaluacion.id = %s", 2, $evaluacion);
                }
                else{
                    $sql_puntaje = sprintf("SELECT
                    Sum(gradacion_escalas.valor_escala)
                    FROM
                    momento_resultado_detalle
                    INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    WHERE
                    momento_evaluacion.cod_momento = %s AND
                    evaluacion.id = %s", 1, $evaluacion_actual);
                }


                $puntaje = BD::$db->queryOne($sql_puntaje);

                $puntaje = round($puntaje/100,2);




                //hermanos del item
                if($momento_actual_arr['cod_momento'] == 1){
                    $sql_calificacion_rubro = sprintf('SELECT
                    Avg(gradacion_escalas.valor_escala)
                    FROM
                    lineamientos
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    where padre_lineamiento = %s and momento_evaluacion.cod_momento = %s and momento_evaluacion.cod_evaluacion= %s', $ld['padre_lineamiento'], 2, $evaluacion);
                }
                else{
                    $sql_calificacion_rubro = sprintf('SELECT
                    Avg(gradacion_escalas.valor_escala)
                    FROM
                    lineamientos
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    where padre_lineamiento = %s and momento_evaluacion.cod_momento = %s and momento_evaluacion.cod_evaluacion= %s', $ld['padre_lineamiento'], 1, $evaluacion_actual);
                }


                $_calificacion_rubro = BD::$db->queryOne( $sql_calificacion_rubro);

                $calificacion_rubro = (($_calificacion_rubro/10)*100);

                $estadisticas = array('calificacion_item' => $calificacion, 'calificacion_rubro' => array('valor' => round($_calificacion_rubro,2),

                    'porcentaje' => round($calificacion_rubro,1)), 'puntaje' => $puntaje);








                $sql_calificacion_item_n = sprintf('select
                    gradacion_escalas.valor_escala
                    from gradacion_escalas where id=%s', $ld['escala_id']);

                $_calificacion_n = BD::$db->queryOne($sql_calificacion_item_n);

                $calificacion_n = (($_calificacion_n/10)*100);

                $sql_puntaje_n = sprintf("SELECT
                Sum(gradacion_escalas.valor_escala)
                FROM
                momento_resultado_detalle
                INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                WHERE
                momento_evaluacion.cod_momento = %s AND
                evaluacion.id = %s", $momento_actual_arr['cod_momento'], $evaluacion_actual);

//                var_dump($sql_puntaje_n);

                $puntaje_n = BD::$db->queryOne($sql_puntaje_n);

//                var_dump($sql_puntaje_n);

                $puntaje_n = round($puntaje_n/100,2);

//                var_dump($puntaje_n);



                //hermanos del item

                $sql_calificacion_rubro_n = sprintf('SELECT
                Avg(gradacion_escalas.valor_escala)
                FROM
                lineamientos
                INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                where padre_lineamiento = %s and momento_evaluacion.cod_momento = %s and momento_evaluacion.cod_evaluacion= %s', $ld['padre_lineamiento'], $momento_actual_arr['cod_momento'], $evaluacion_actual);

                $_calificacion_rubro_n = BD::$db->queryOne( $sql_calificacion_rubro_n);

                $calificacion_rubro_n = (($_calificacion_rubro_n/10)*100);

                $estadisticas_n = array('calificacion_item' => $calificacion_n, 'calificacion_rubro' => array('valor' => round($_calificacion_rubro_n,2),

                    'porcentaje' => round($calificacion_rubro_n,1)), 'puntaje' => $puntaje_n);

                $sql = sprintf('select documentos from lineamientos_datos where cod_lineamiento = %s', $ld['id']);
                $documentos = BD::$db->queryOne($sql);
                

                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                            $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                            $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                            $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                            $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                            $lineamientos[$k]['momento_resultado_detalle_id'] = $ld['momento_resultado_detalle_id'];
                            $lineamientos[$k]['reevaluacion'] = $lineamientos_data_reevaluacion;
                            $lineamientos[$k]['evaluacion_actual'] = $evaluacion_actual_data;
                            $lineamientos[$k]['gradacion'] = $gradacion;
                            $lineamientos[$k]['estadisticas'] = $estadisticas;
                            $lineamientos[$k]['estadisticas_n'] = $estadisticas_n;
                            $lineamientos[$k]['documentos'] = $documentos;
                            
                            if($_GET['mostrar_ee'] == 1){
                                $sql_lineamientos_data_anterior = sprintf("SELECT
                                lineamientos.id,
                                momento_resultado_detalle.fortalezas,
                                momento_resultado_detalle.debilidades,
                                momento_resultado_detalle.plan_mejoramiento,
                                momento_resultado_detalle.cod_gradacion_escala,
                                momento_resultado_detalle.id as momento_resultado_detalle_id,
                                gradacion_escalas.desc_escala,
                                gradacion_escalas.id as escala_id,
                                lineamientos.padre_lineamiento
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                                LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                                LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                                LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                                WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.cod_momento = %s 
                                and lineamientos.id = %s",
                                        $r['id'],
                                        $evaluacion,
                                        2,$ld['id']);
                                $lda = BD::$db->queryRow($sql_lineamientos_data_anterior);
                                $lineamientos[$k]['ee']['fortalezas'] = $lda['fortalezas'];
                                $lineamientos[$k]['ee']['debilidades'] = $lda['debilidades'];
                                $lineamientos[$k]['ee']['plan_mejoramiento'] = $lda['plan_mejoramiento'];
                                $lineamientos[$k]['ee']['desc_escala'] = $lda['desc_escala'];
                                $lineamientos[$k]['ee']['momento_resultado_detalle_id'] = $lda['momento_resultado_detalle_id'];
                            }

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
                AND momento_evaluacion.cod_momento = %s and momento_evaluacion.cod_evaluacion = %s', $l['lineamiento_id'], 1 , $evaluacion_actual);

                 if($momento_actual_arr['cod_momento'] == 2){
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
                and momento_evaluacion.cod_evaluacion = %s', $l['lineamiento_id'],  $evaluacion_actual);
                 }
//                 var_dump($sql_anexos); $momento_actual_arr['cod_momento']

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
        $vars['mostrar_ee'] = $_GET['mostrar_ee'];
        $vars['acceso_rapido'] = $_GET['acceso_rapido'];
        $vars['rubros'] = $rubros;
        $vars['momento'] = $momento;
        $vars['momento_evaluacion'] = $momento_evaluacion;
        $vars['momento_actual'] = $momento_actual_arr['cod_momento_evaluacion'];
        $vars['cod_momento_actual'] = $momento_actual_arr['cod_momento'];
        $vars['controls'] = $_GET['controls'];



        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_css('public/css/sievas/styles.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_css('public/summernote/tooltip_fix.css');
        View::add_js('public/summernote/helper-v2.js');
        View::add_css('public/js/textext/css/textext.core.css');
        View::add_css('public/js/textext/css/textext.plugin.tags.css');
        View::add_js('public/js/textext/js/textext.core.js');
        View::add_js('public/js/textext/js/textext.plugin.tags.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('modules/sievas/scripts/evaluar/main.js');
        View::add_js('modules/sievas/scripts/evaluar/reevaluacion.js');

        if(isset($_GET['controls']) && $_GET['controls'] == 0 ){
            View::render('evaluar/reportereevaluacion.php', $vars);
        }
        else{
            View::render('evaluar/reevaluacion.php', $vars);
        }
        
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }

    public function index(){
    }

    public function zip_exe_anexos(){
        exec( "zip /home/donatovallin/sievas/file.zip /home/donatovallin/sievas/file.pdf /home/donatovallin/sievas/file.txt", $output, $res);
        var_dump($res);
    }

    public function zip_anexos(){
        header('Content-Type: application/json');

        $now = new DateTime();

        $evaluacion = filter_input(INPUT_GET, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $momento_evaluacion = filter_input(INPUT_GET, 'momento_evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);

        if($momento == 2){
            if($momento_evaluacion > 0){
                $sql_momento_evaluacion = sprintf("SELECT
                momento_evaluacion.id
            FROM
                sievas.momento_evaluacion
            WHERE
                momento_evaluacion.cod_momento = 1 and
                momento_evaluacion.cod_evaluacion = (SELECT
                        momento_evaluacion.cod_evaluacion
                    FROM
                        sievas.momento_evaluacion
                    WHERE
                        momento_evaluacion.id = %s)
                    AND momento_evaluacion.id != %s", $momento_evaluacion, $momento_evaluacion);
            }
            else{
                $sql_momento_evaluacion = sprintf("SELECT
                momento_evaluacion.id
            FROM
                sievas.momento_evaluacion
            WHERE
                momento_evaluacion.cod_momento = 1 and
                momento_evaluacion.cod_evaluacion = %s", $evaluacion);
            }

            $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);

//            var_dump($sql_momento_evaluacion);
        }

        $sql_anexos = sprintf("SELECT gen_documentos.ruta, gen_documentos.nombre FROM momento_resultado_anexo
        inner join gen_documentos on momento_resultado_anexo.cod_documento = gen_documentos.id
        where momento_resultado_anexo.cod_momento_evaluacion = '%s' and momento_resultado_anexo.cod_lineamiento = '%s'",
                $momento_evaluacion, $lineamiento);

        $anexos = BD::$db->queryAll($sql_anexos);

//        var_dump($sql_anexos);

//        var_dump($anexos);



        $relative_path = '/sievas/public/files/'.$now->getTimestamp().'-'.'adjuntos_item'.'-'.$momento_evaluacion.$lineamiento.'.zip';

//        $anexos = join(' ', $anexos);
//
//        exec( "zip /home/donatovallin/public_html$relative_path $anexos", $output, $res);

//        if($res == 0){
//            echo json_encode(array('zip' => $relative_path));
//        }
//        else{
//
//        }


        if($this->create_zip($anexos, '/home/donatovallin/public_html'.$relative_path)){

            echo json_encode(array('zip' => $relative_path));
        }
        else{
            //error
        }

    }

 /* creates a compressed zip file */
    private function create_zip($files = array(),$destination = '',$overwrite = true) {
            $files_tmp = $files;

            $files = array_map(function($anexos){
            return '/home/donatovallin/public_html/sievas/'.$anexos['ruta'];
            }, $files);
            //if the zip file already exists and overwrite is false, return false
            if(file_exists($destination) && !$overwrite) { return false; }
            //vars
            $valid_files = array();
            //if files were passed in...
            if(is_array($files)) {
                    //cycle through each file
                    foreach($files as $key => $file) {
//                        var_dump($file);
                            //make sure the file exists
//                        var_dump(file_exists($file));
                            if(file_exists($file)) {
//                                echo "existe";
                                $valid_files[] = $file;
                            }
                            else{
                                  if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $file)) {
                                    $file_url = "/home/donatovallin/public_html/sievas/public/files/".str_replace('/', '', $files_tmp[$key]['nombre']).'.txt';
                                    file_put_contents($file_url, $files_tmp[$key]['ruta']);
                                    $valid_files[] = $file_url;
                                  }
                            }
                    }

            }

//            var_dump($valid_files);
            //if we have good files...
            if(count($valid_files)) {
//                echo "hola";
                    //create the archive
                    $zip = new ZipArchive();
                    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                            return false;
                    }
                    //add the files
                    foreach($valid_files as $file) {
                            $zip->addFile($file,substr($file,strrpos($file,'/') + 1));
                    }
                    //debug
//                    echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

                    //close the zip -- done!
                    $zip->close();

                    //check to make sure the file exists
                    return file_exists($destination);

//                $valid_files = array_map(function($f){
//                    return '"'.$f.'"';
//                }, $valid_files);
//
//                $valid_files = join(' ', $valid_files);
//                var_dump($valid_files);

//                exec( "zip $destination $valid_files", $output, $res);
//                var_dump($output);
                return $res == 0;
            }
            else
            {
                    return false;
            }
    }

    public function replicar_lineamiento(){
        header('Content-Type: application/json');
        $valid = true;
        $momento_resultado = filter_input(INPUT_POST, 'momento_resultado', FILTER_SANITIZE_NUMBER_INT);
        $evaluaciones = $_POST['evaluaciones'];
//        var_dump($evaluaciones);
        $momento = filter_input(INPUT_POST, 'momento', FILTER_SANITIZE_NUMBER_INT);

        $sql_data_resultado = sprintf("SELECT
        momento_resultado_detalle.cod_lineamiento,
        momento_resultado_detalle.cod_gradacion_escala,
        momento_resultado_detalle.fortalezas,
        momento_resultado_detalle.debilidades,
        momento_resultado_detalle.plan_mejoramiento
        FROM
        momento_resultado_detalle
        WHERE
        momento_resultado_detalle.id = %s
        ", $momento_resultado);

//        var_dump($sql_data_resultado);

        $data_resultado = BD::$db->queryRow($sql_data_resultado);

        $sql_data_anexos = sprintf("SELECT
        momento_resultado_anexo.cod_documento,
        momento_resultado_anexo.cod_momento_evaluacion,
        momento_resultado_anexo.cod_lineamiento
        FROM
        momento_resultado_anexo
        INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
        WHERE
        momento_evaluacion.cod_evaluacion = %s AND
        momento_evaluacion.cod_momento = %s AND
        momento_resultado_anexo.cod_lineamiento = %s",
                Auth::info_usuario('evaluacion'), $momento, $data_resultado['cod_lineamiento']);

        $data_anexos = BD::$db->queryAll($sql_data_anexos);

//        var_dump($data_anexos);

        $aux = new momento_resultado_detalle();
        $aux->begin();

        $err = array();
        foreach($evaluaciones as $e){
            //momento
            $sql_check_evaluacion = sprintf("SELECT
            momento_resultado_detalle.id,
            momento_resultado_detalle.cod_gradacion_escala,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado.id as cod_momento_resultado,
            momento_evaluacion.id as momento_evaluacion,
            evaluacion.etiqueta
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            WHERE
            momento_evaluacion.cod_momento = %s AND
            evaluacion.id = %s and momento_resultado_detalle.cod_lineamiento = %s", $momento, $e, $data_resultado['cod_lineamiento']);

//            var_dump($sql_check_evaluacion);

            $data_check_evaluacion = BD::$db->queryRow($sql_check_evaluacion);

            if(!($data_resultado['cod_gradacion_escala'] > 0)){
                $valid = false;
                $err[] = "El item a replicar debe tener una calificación";
            }
            else{

//            var_dump($data_check_evaluacion);
            if($data_check_evaluacion != null){
                $model_detalle = new momento_resultado_detalle();
                $model_detalle->id = $data_check_evaluacion['id'];
                $model_detalle->cod_gradacion_escala = $data_resultado['cod_gradacion_escala'];
                $model_detalle->fortalezas = $data_resultado['fortalezas'];
                $model_detalle->debilidades = $data_resultado['debilidades'];
                $model_detalle->plan_mejoramiento = $data_resultado['plan_mejoramiento'];
                if($model_detalle->update()){
                    $query = sprintf("delete from momento_resultado_anexo where cod_momento_evaluacion = %s and cod_lineamiento = %s",
                            $data_check_evaluacion['momento_evaluacion'],$data_anexos[0]['cod_lineamiento']);
                    $result = BD::$db->query($query);
                    foreach($data_anexos as $anexo){
                          $query = sprintf("INSERT INTO momento_resultado_anexo(cod_documento, cod_momento_evaluacion,
                              cod_lineamiento) values(%s, %s, %s)
                        ", $anexo['cod_documento'], $data_check_evaluacion['momento_evaluacion'],$anexo['cod_lineamiento']
                                  );
//                          var_dump($query);
                          $result = BD::$db->query($query);
                          if(PEAR::isError($result)){
                              $valid = false;
                              $err[] = 'No se pudo replicar el item para la evaluación '.$data_check_evaluacion['etiqueta'];
                          }

                    }
                }
                else{
                    $valid = false;
                    $err[] = 'No se pudo replicar el item para la evaluación '.$data_check_evaluacion['etiqueta'];
                }
            }
            else{
                //crear momento resultado
                $sql_momento_evaluacion = sprintf("SELECT
                momento_evaluacion.id,
                evaluacion.etiqueta
                FROM
                momento_evaluacion
                INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                evaluacion.id = %s AND
                momento_evaluacion.cod_momento = %s
                ", $e, $momento);

                $momento_evaluacion = BD::$db->queryRow($sql_momento_evaluacion);
                $momento_resultado = new momento_resultado();
                $momento_resultado->desc_resultado = '';
                $momento_resultado->cod_momento_evaluacion = $momento_evaluacion['id'];
                if($momento_resultado->save()){
                    $model_detalle = new momento_resultado_detalle();
                    $model_detalle->cod_lineamiento = $data_resultado['cod_lineamiento'];
                    $model_detalle->cod_gradacion_escala = $data_resultado['cod_gradacion_escala'];
                    $model_detalle->fortalezas = $data_resultado['fortalezas'];
                    $model_detalle->debilidades = $data_resultado['debilidades'];
                    $model_detalle->plan_mejoramiento = $data_resultado['plan_mejoramiento'];
                    $model_detalle->cod_momento_resultado = $momento_resultado->id;

                    if($model_detalle->save()){
//                        echo $model_detalle->id;
                    }
                    else{
                       $valid = false;
                       $err[] = 'No se pudo replicar el item para la evaluación '.$momento_evaluacion['etiqueta'];
                    }
                }
                else{

                }
            }}
        }

        if($valid){
            $aux->commit();
            echo json_encode(array('status' => 'ok'));
        }
        else{
            $aux->rollback();
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $err[0];
        }
    }

    public function replicar_indicador(){
        header('Content-Type: application/json');
        $valid = true;
        $momento = filter_input(INPUT_POST, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $momento_evaluacion = filter_input(INPUT_POST, 'momento_evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $evaluaciones = $_POST['evaluaciones'];


        $sql_analisis_indicador = sprintf("select * from evaluacion_analisis_indicadores
            where cod_lineamiento = %s and cod_momento = %s", $lineamiento, $momento_evaluacion);

        $data_analisis_indicador = BD::$db->queryAll($sql_analisis_indicador);


        $sql_data_anexos = sprintf("SELECT
        momento_resultado_anexo.cod_documento,
        momento_resultado_anexo.cod_momento_evaluacion,
        momento_resultado_anexo.cod_lineamiento
        FROM
        momento_resultado_anexo
        INNER JOIN momento_evaluacion ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
        WHERE
        momento_evaluacion.id = %s AND
        momento_resultado_anexo.cod_lineamiento = %s",
                $momento_evaluacion, $lineamiento);

        $data_anexos = BD::$db->queryAll($sql_data_anexos);
        $aux = new evaluacion_analisis_indicadores();
        $aux->begin();

        $err = array();
        foreach($evaluaciones as $e){
            //momento
            $sql_check_evaluacion = sprintf("SELECT
            evaluacion_analisis_indicadores.id,
            evaluacion_analisis_indicadores.analisis,
            momento_evaluacion.id as momento_evaluacion,
            evaluacion.etiqueta
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN evaluacion_analisis_indicadores ON evaluacion_analisis_indicadores.cod_momento = momento_evaluacion.id
            WHERE
            momento_evaluacion.cod_momento = %s AND
            evaluacion.id = %s and evaluacion_analisis_indicadores.cod_lineamiento = %s", $momento, $e, $data_analisis_indicador['cod_lineamiento']);

            $data_check_evaluacion = BD::$db->queryRow($sql_check_evaluacion);

            if(false){
                $valid = false;
                $err[] = "El item a replicar debe tener una calificación";
            }
            else{

//            var_dump($data_check_evaluacion);
            if($data_check_evaluacion != null){
                $model_detalle = new evaluacion_analisis_indicadores();
                $model_detalle->id = $data_check_evaluacion['id'];
                $model_detalle->analisis = $data_analisis_indicador['analisis'];
                $model_detalle->cod_lineamiento = $lineamiento;
                $model_detalle->cod_momento = $momento_evaluacion;
                if($model_detalle->update()){
                    $query = sprintf("delete from momento_resultado_anexo where cod_momento_evaluacion = %s and cod_lineamiento = %s",
                            $data_check_evaluacion['momento_evaluacion'],$data_anexos[0]['cod_lineamiento']);
                    $result = BD::$db->query($query);
                    foreach($data_anexos as $anexo){
                          $query = sprintf("INSERT INTO momento_resultado_anexo(cod_documento, cod_momento_evaluacion,
                              cod_lineamiento) values(%s, %s, %s)
                        ", $anexo['cod_documento'], $data_check_evaluacion['momento_evaluacion'],$anexo['cod_lineamiento']
                                  );

                          $result = BD::$db->query($query);
                          if(PEAR::isError($result)){
                              $valid = false;
                              $err[] = 'No se pudo replicar el item para la evaluación '.$data_check_evaluacion['etiqueta'];
                          }

                    }
                }
                else{
                    $valid = false;
                    $err[] = 'No se pudo replicar el item para la evaluación '.$data_check_evaluacion['etiqueta'];
                }
            }
            else{
                //crear momento resultado
                $sql_momento_evaluacion = sprintf("SELECT
                momento_evaluacion.id,
                evaluacion.etiqueta
                FROM
                momento_evaluacion
                INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                WHERE
                evaluacion.id = %s AND
                momento_evaluacion.cod_momento = %s
                ", $e, $momento);

                $momento_evaluacion = BD::$db->queryRow($sql_momento_evaluacion);
                $momento_resultado = new momento_resultado();
                $momento_resultado->desc_resultado = '';
                $momento_resultado->cod_momento_evaluacion = $momento_evaluacion['id'];
                if($momento_resultado->save()){
                    $model_detalle = new evaluacion_analisis_indicadores();
                    $model_detalle->analisis = $data_analisis_indicador['analisis'];
                    $model_detalle->cod_lineamiento = $lineamiento;
                    $model_detalle->cod_momento = $momento_evaluacion;

                    if($model_detalle->save()){

                    }
                    else{
                       $valid = false;
                       $err[] = 'No se pudo replicar el item para la evaluación '.$momento_evaluacion['etiqueta'];
                    }
                }
                else{

                }
            }}
        }

        if($valid){
            $aux->commit();
            echo json_encode(array('status' => 'ok'));
        }
        else{
            $aux->rollback();
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $err[0];
        }
    }
    

    public function guardar(){
       
        $momento = $_GET['cod_momento'];
        if($momento == null){
            $tmp = $this->get_momento_actual();
            $momento = $tmp['cod_momento'];
        }
       if(Auth::info_usuario('ev_tablero') > 0){
           switch($momento){
               case 1:
                   $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                        inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                        etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 1', Auth::info_usuario('evaluacion'));
                    $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
                    if($rs_tab_cond['escala_avance'] == null){
                        $rs_tab_cond['escala_avance'] = 0;
                    }
                    $sql_tab_cond_2 = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                        inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                        etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 2', Auth::info_usuario('evaluacion'));
                    $rs_tab_cond_2 = BD::$db->queryRow($sql_tab_cond_2);
                    if($rs_tab_cond_2['escala_avance'] == null){
                        $rs_tab_cond_2['escala_avance'] = 0;
                    }

                    if($rs_tab_cond['escala_avance'] != 10 ){
                        header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=1');
                    }
                    else{
                        if($rs_tab_cond_2['escala_avance'] == 10){
                            header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=2');
                        }
                    }
                   break;
               case 2:
                   $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                    inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                    etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 3', Auth::info_usuario('evaluacion'));
                     $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
                     if($rs_tab_cond['escala_avance'] == null){
                         $rs_tab_cond['escala_avance'] = 0;
                     }

                     if($rs_tab_cond['escala_avance'] != 10 ){
                         header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=3');
                     }
                   break;
           }
          
       }
       
       
        
       View::add_js('public/js/bootstrap-datepicker.js');
       View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
       View::add_css('public/css/fa410/css/font-awesome.min.css');
       View::add_css('public/css/sievas/styles.css');
       View::add_css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
       View::add_js('public/summernote/summernote.min.js');
       View::add_js('public/summernote/summernote-es-ES.js');
       View::add_css('public/summernote/summernote.css');
       View::add_css('public/summernote/tooltip_fix.css');
       View::add_js('public/summernote/helper-v2.js');
       View::add_css('public/js/textext/css/textext.core.css');
       View::add_css('public/js/textext/css/textext.plugin.tags.css');
       View::add_js('public/js/textext/js/textext.core.js');
       View::add_js('public/js/textext/js/textext.plugin.tags.js');
       View::add_js('public/js/jquery.validate.js');
       View::add_js('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js'); 
       View::add_js('https://code.jquery.com/ui/1.12.1/jquery-ui.js'); 
       View::add_js('modules/sievas/scripts/evaluar.js');
//       View::add_js('public/js/bootbox.min.js');

       $rol = Auth::info_usuario('rol');
       $vars = array();
       $privilegio_replica_red = false;
       if(Auth::info_usuario('ev_red') > 0){
           $evaluacion = Auth::info_usuario('evaluacion');
           $sql_evaluaciones = sprintf("SELECT
            evaluacion.id as e_id,
            evaluacion.etiqueta
            FROM
            evaluacion
            WHERE
            evaluacion.padre = %s", $evaluacion);

            $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
//            var_dump($evaluaciones);

       }

       $sql_evaluaciones_centro = sprintf("select *,e.id as e_id
            from
            evaluacion as e
            inner join eval_programas on eval_programas.id = e.cod_evaluado
            inner join eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
            where eval_instituciones.id = (
            SELECT
            eval_instituciones.id
            FROM
            evaluacion
            inner join eval_programas on eval_programas.id = evaluacion.cod_evaluado
            inner join eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
            WHERE
            evaluacion.id = %s
            )
            and e.tipo_evaluado = (
            SELECT
            evaluacion.tipo_evaluado
            FROM
            evaluacion
            WHERE
            evaluacion.id = %s
            )
            and e.id != %s", Auth::info_usuario('evaluacion'),
               Auth::info_usuario('evaluacion'), Auth::info_usuario('evaluacion'));

       $evaluaciones_centro = BD::$db->queryAll($sql_evaluaciones_centro);
//       var_dump($sql_evaluaciones_centro);



       $sql_padre_red = sprintf("select count(*) from evaluacion where id = %s and padre is null",
               Auth::info_usuario('evaluacion'));
       $padre_red = BD::$db->queryOne($sql_padre_red);

       if(Auth::info_usuario('ev_red') > 0 && $padre_red > 0){
           $privilegio_replica_red = true;
       }

       $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);
//       if(Auth::info_usuario('evaluacion') == 93){
//           var_dump($evaluacion_data['cod_conjunto']);
//       }
        
       
       
       if($rol == 1){
           $vars['cod_momento'] = $_GET['cod_momento'];
           $vars['cod_cargo'] = 1;
           $vars['cod_conjunto'] = $evaluacion_data['cod_conjunto'];
           $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
           $bandera = BD::$db->queryOne($sql_bandera);
           $vars['evaluacion_data'] = $evaluacion_data;
           $vars['bandera'] = $bandera;
           $vars['evaluacion'] = Auth::info_usuario('evaluacion');
           $vars['evaluaciones_red'] = $evaluaciones;
           $vars['evaluaciones_centro'] = $evaluaciones_centro;
           $vars['privilegio_replica_red'] = $privilegio_replica_red;
           $vars['rol'] = $rol;
           if($evaluacion_data['traducciones'] > 0){
              $sql_idiomas = "select * from gen_idiomas";
              $idiomas = BD::$db->queryAll($sql_idiomas);
              $vars['idiomas'] = $idiomas;
          }
           View::render('evaluar/evaluar.php', $vars);
       }

       else{
          $vars = $this->get_momento_actual();
          $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
          $bandera = BD::$db->queryOne($sql_bandera);
          if($vars['cod_momento'] == 2){
              $vars['cod_cargo'] = 1;
          }
          $vars['bandera'] = $bandera;
          $vars['evaluacion_data'] = $evaluacion_data;
          $vars['evaluacion'] = Auth::info_usuario('evaluacion');
          $vars['evaluaciones_red'] = $evaluaciones;
          $vars['evaluaciones_centro'] = $evaluaciones_centro;
          if($vars['comite_centro'] == 1){
              Auth::info_usuario('ev_red', 1);
              $vars['evaluaciones_red'] = $evaluaciones_centro;
//              var_dump($evaluaciones_centro);
          }
          
         
          
          $vars['privilegio_replica_red'] = $privilegio_replica_red;
          $vars['cod_conjunto'] = $evaluacion_data['cod_conjunto'];
          $vars['rol'] = $rol;
          $sql_check_nal = sprintf("select ev_nal from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
          $check_nal = BD::$db->queryOne($sql_check_nal);
           $vars['nal'] = $check_nal;
           if($check_nal > 0){
               //documentos registrados
               $sql_documentos = sprintf("select * from evaluacion_nal_documentos where evaluacion_id = %s", Auth::info_usuario('evaluacion'));
               $documentos = BD::$db->queryAll($sql_documentos);
               $vars['documentos'] = $documentos;
           }
          if($evaluacion_data['traducciones'] > 0){
              $sql_idiomas = "select * from gen_idiomas";
              $idiomas = BD::$db->queryAll($sql_idiomas);
              $vars['idiomas'] = $idiomas;
          }
          View::render('evaluar/evaluar.php', $vars);
       }
    }

    public function guardar_v2(){
       
        $momento = $_GET['cod_momento'];
        if($momento == null){
            $tmp = $this->get_momento_actual();
            $momento = $tmp['cod_momento'];
        }
       if(Auth::info_usuario('ev_tablero') > 0){
           switch($momento){
               case 1:
                   $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                        inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                        etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 1', Auth::info_usuario('evaluacion'));
                    $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
                    if($rs_tab_cond['escala_avance'] == null){
                        $rs_tab_cond['escala_avance'] = 0;
                    }
                    $sql_tab_cond_2 = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                        inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                        etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 2', Auth::info_usuario('evaluacion'));
                    $rs_tab_cond_2 = BD::$db->queryRow($sql_tab_cond_2);
                    if($rs_tab_cond_2['escala_avance'] == null){
                        $rs_tab_cond_2['escala_avance'] = 0;
                    }

                    if($rs_tab_cond['escala_avance'] != 10 ){
                        header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=1');
                    }
                    else{
                        if($rs_tab_cond_2['escala_avance'] == 10){
                            header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=2');
                        }
                    }
                   break;
               case 2:
                   $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                    inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                    etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 3', Auth::info_usuario('evaluacion'));
                     $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
                     if($rs_tab_cond['escala_avance'] == null){
                         $rs_tab_cond['escala_avance'] = 0;
                     }

                     if($rs_tab_cond['escala_avance'] != 10 ){
                         header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=3');
                     }
                   break;
           }
          
       }
       
       
        
       View::add_js('public/js/bootstrap-datepicker.js');
       View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
       View::add_css('public/css/fa410/css/font-awesome.min.css');
       View::add_css('public/css/sievas/styles.css');
       View::add_css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
       View::add_js('public/summernote/summernote.min.js');
       View::add_js('public/summernote/summernote-es-ES.js');
       View::add_css('public/summernote/summernote.css');
       View::add_css('public/summernote/tooltip_fix.css');
//       View::add_js('public/summernote/helper-v2.js');
       View::add_js('public/js/tinymce/js/tinymce/tinymce.min.js');
       View::add_js('public/js/tinyhelper.js');
       View::add_css('public/js/textext/css/textext.core.css');
       View::add_css('public/js/textext/css/textext.plugin.tags.css');
       View::add_js('public/js/textext/js/textext.core.js');
       View::add_js('public/js/textext/js/textext.plugin.tags.js');
       View::add_js('public/js/jquery.validate.js');
       View::add_js('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js'); 
       View::add_js('https://code.jquery.com/ui/1.12.1/jquery-ui.js'); 
       View::add_js('modules/sievas/scripts/evaluar_v2.js');
//       View::add_js('public/js/bootbox.min.js');

       $rol = Auth::info_usuario('rol');
       $vars = array();
       $privilegio_replica_red = false;
       if(Auth::info_usuario('ev_red') > 0){
           $sql_evaluaciones = "";
           $evaluacion = Auth::info_usuario('evaluacion');
           if(Auth::info_usuario('ev_padre') > 0){
              $sql_evaluaciones = sprintf("SELECT
               evaluacion.id as e_id,
               evaluacion.etiqueta
               FROM
               evaluacion
               WHERE
               evaluacion.id = %s", Auth::info_usuario('ev_padre'));
           } 
           else{              
              $sql_evaluaciones = sprintf("SELECT
               evaluacion.id as e_id,
               evaluacion.etiqueta
               FROM
               evaluacion
               WHERE
               evaluacion.padre = %s", $evaluacion);
           }

            $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
//            var_dump($evaluaciones);

       }

       $sql_evaluaciones_centro = sprintf("select *,e.id as e_id
            from
            evaluacion as e
            inner join eval_programas on eval_programas.id = e.cod_evaluado
            inner join eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
            where eval_instituciones.id = (
            SELECT
            eval_instituciones.id
            FROM
            evaluacion
            inner join eval_programas on eval_programas.id = evaluacion.cod_evaluado
            inner join eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
            WHERE
            evaluacion.id = %s
            )
            and e.tipo_evaluado = (
            SELECT
            evaluacion.tipo_evaluado
            FROM
            evaluacion
            WHERE
            evaluacion.id = %s
            )
            and e.id != %s", Auth::info_usuario('evaluacion'),
               Auth::info_usuario('evaluacion'), Auth::info_usuario('evaluacion'));

       $evaluaciones_centro = BD::$db->queryAll($sql_evaluaciones_centro);
//       var_dump($sql_evaluaciones_centro);



       $sql_padre_red = sprintf("select count(*) from evaluacion where id = %s and padre is null",
               Auth::info_usuario('evaluacion'));
       $padre_red = BD::$db->queryOne($sql_padre_red);

       if(Auth::info_usuario('ev_red') > 0 && $padre_red > 0){
           $privilegio_replica_red = true;
       }

       $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);
//       if(Auth::info_usuario('evaluacion') == 93){
//           var_dump($evaluacion_data['cod_conjunto']);
//       }
        
       
       
       if($rol == 1){
           $vars['cod_momento'] = $_GET['cod_momento'];
           $vars['cod_cargo'] = 1;
           $vars['cod_conjunto'] = $evaluacion_data['cod_conjunto'];
           $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
           $bandera = BD::$db->queryOne($sql_bandera);
           $vars['evaluacion_data'] = $evaluacion_data;
           $vars['bandera'] = $bandera;
           $vars['evaluacion'] = Auth::info_usuario('evaluacion');
           $vars['evaluaciones_red'] = $evaluaciones;
           $vars['evaluaciones_centro'] = $evaluaciones_centro;
           $vars['privilegio_replica_red'] = $privilegio_replica_red;
           $vars['rol'] = $rol;
           if($evaluacion_data['traducciones'] > 0){
              $sql_idiomas = "select * from gen_idiomas";
              $idiomas = BD::$db->queryAll($sql_idiomas);
              $vars['idiomas'] = $idiomas;
          }
           View::render('evaluar/evaluar.php', $vars);
       }

       else{
          $vars = $this->get_momento_actual();
          $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
          $bandera = BD::$db->queryOne($sql_bandera);
          if($vars['cod_momento'] == 2){
              $vars['cod_cargo'] = 1;
          }
          $vars['bandera'] = $bandera;
          $vars['evaluacion_data'] = $evaluacion_data;
          $vars['evaluacion'] = Auth::info_usuario('evaluacion');
          $vars['evaluaciones_red'] = $evaluaciones;
          $vars['evaluaciones_centro'] = $evaluaciones_centro;
          if($vars['comite_centro'] == 1){
              Auth::info_usuario('ev_red', 1);
              $vars['evaluaciones_red'] = $evaluaciones_centro;
//              var_dump($evaluaciones_centro);
          }
          
         
          
          $vars['privilegio_replica_red'] = $privilegio_replica_red;
          $vars['cod_conjunto'] = $evaluacion_data['cod_conjunto'];
          $vars['rol'] = $rol;
          $sql_check_nal = sprintf("select ev_nal from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
          $check_nal = BD::$db->queryOne($sql_check_nal);
           $vars['nal'] = $check_nal;
           if($check_nal > 0){
               //documentos registrados
               $sql_documentos = sprintf("select * from evaluacion_nal_documentos where evaluacion_id = %s", Auth::info_usuario('evaluacion'));
               $documentos = BD::$db->queryAll($sql_documentos);
               $vars['documentos'] = $documentos;
           }
          if($evaluacion_data['traducciones'] > 0){
              $sql_idiomas = "select * from gen_idiomas";
              $idiomas = BD::$db->queryAll($sql_idiomas);
              $vars['idiomas'] = $idiomas;
          }
          View::render('evaluar/evaluar.php', $vars);
       }
    }
    
    public function visor_placeholder(){
        View::render('evaluar/evaluar.php', array(), 'visor_placeholder.php');
    }
    
    public function get_view_document(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'doc_id', FILTER_SANITIZE_NUMBER_INT);
        $sql_doc = sprintf("select gen_documentos.ruta as src from evaluacion_nal_documentos inner join 
            gen_documentos on evaluacion_nal_documentos.cod_documento = 
            gen_documentos.id where evaluacion_nal_documentos.id = %s", $id);
//        var_dump($sql_doc);
        $doc = BD::$db->queryRow($sql_doc);
        echo json_encode($doc);
        
    }


    public function cuadros_maestros(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_cuadro_data = sprintf("select gen_documentos.ruta, gen_documentos.nombre, evaluacion_cuadrosmaestros.id
            from evaluacion_cuadrosmaestros
            inner join gen_documentos on gen_documentos.id = evaluacion_cuadrosmaestros.cod_documento
            where evaluacion_cuadrosmaestros.cod_evaluacion = %s", $evaluacion);
        $cuadro_data = BD::$db->queryRow($sql_cuadro_data);
        $vars['cuadro_data'] = $cuadro_data;
        View::add_js('modules/sievas/scripts/evaluar/cuadros_maestros.js');
        View::render('evaluar/cuadros_maestros.php', $vars);
    }

    public function guardar_cuadro_maestro(){
        header('Content-Type: application/json');
        $now = new DateTime();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $file = $_FILES['file'];
        $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$file['name'];
        $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
        $valid = true;
        $cuadro_data = array();
        if($file['error'] === UPLOAD_ERR_OK){
            if(move_uploaded_file($file['tmp_name'], $real_path)){

                $error_msg = array();
                $documento = new gen_documentos();
                $documento->begin();
                $documento->nombre = $file['name'];
                $documento->ruta = $rel_path;
                if($documento->save()){
                    //ok
                    $cuadro = new evaluacion_cuadrosmaestros();
                    $cuadro->cod_documento = $documento->id;
                    $cuadro->cod_evaluacion = Auth::info_usuario('evaluacion');

                    $sql_persona = sprintf("select cod_persona from sys_usuario where username='%s'", Auth::info_usuario('usuario'));
                    $persona = BD::$db->queryOne($sql_persona);
                    $cuadro->cod_evaluador = $persona;

                    if($id > 0){
                    $cuadro->id = $id;
                    if($cuadro->update()){
                        $cuadro_data['id'] = $cuadro->id;
                        $cuadro_data['ruta'] = $documento->ruta;
                        $cuadro_data['nombre'] = $documento->nombre;
                    }
                    else{
                        $valid = false;
                        $error_msg[] = "No se pudo actualizar el cuadro maestro";
                        //no se pudo actualizar
                    }
                }
                else{
                    if($cuadro->save()){
                        $cuadro_data['id'] = $cuadro->id;
                        $cuadro_data['ruta'] = $documento->ruta;
                        $cuadro_data['nombre'] = $documento->nombre;
                    }
                    else{
                        //no se pudo guardar
                        $valid = false;
                        $error_msg[] = "No se pudo guardar el cuadro maestro";
                    }
                }
                }
                else{
                    //no se pudo guardar
                    $valid = false;
                        $error_msg[] = "No se pudo guardar el cuadro maestro";
                }

            }
            else{
                //no se pudo mover
                $valid = false;
                $error_msg[] = "No se pudo subir el cuadro maestro";
            }
        }
        else{
            //no se pudo subir
            $valid = false;
            $error_msg[] = "No se pudo subir el cuadro maestro";
        }

        if($valid){
            $documento->commit();
            echo json_encode($cuadro_data);
        }
        else{
            $documento->rollback();
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $error_msg[0];
        }

    }

    public function analisis_indicadores(){
       View::add_js('public/js/bootstrap-datepicker.js');
       View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
       View::add_css('public/css/fa410/css/font-awesome.min.css');
       View::add_css('public/css/sievas/styles.css');
       View::add_js('public/summernote/summernote.min.js');
       View::add_js('public/summernote/summernote-es-ES.js');
       View::add_css('public/summernote/summernote.css');
       View::add_css('public/summernote/tooltip_fix.css');
       View::add_js('public/summernote/helper.js');
       View::add_css('public/js/textext/css/textext.core.css');
       View::add_css('public/js/textext/css/textext.plugin.tags.css');
       View::add_js('public/js/textext/js/textext.core.js');
       View::add_js('public/js/textext/js/textext.plugin.tags.js');
       View::add_js('public/js/jquery.validate.js');
       View::add_js('modules/sievas/scripts/evaluar/analisis_indicadores.js');
//       View::add_js('public/js/bootbox.min.js');

       $rol = Auth::info_usuario('rol');
       $vars = array();
       if(Auth::info_usuario('ev_red') > 0){
           $evaluacion = Auth::info_usuario('evaluacion');
           $sql_evaluaciones = sprintf("SELECT
            evaluacion.id,
            evaluacion.etiqueta
            FROM
            evaluacion
            WHERE
            evaluacion.padre = %s", $evaluacion);

            $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
//            var_dump($evaluaciones);

       }

       $sql_evaluaciones_centro = sprintf("select *,e.id as e_id
            from
            evaluacion as e
            inner join eval_programas on eval_programas.id = e.cod_evaluado
            inner join eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
            where eval_instituciones.id = (
            SELECT
            eval_instituciones.id
            FROM
            evaluacion
            inner join eval_programas on eval_programas.id = evaluacion.cod_evaluado
            inner join eval_instituciones on eval_instituciones.id = eval_programas.cod_institucion
            WHERE
            evaluacion.id = %s
            )
            and e.tipo_evaluado = (
            SELECT
            evaluacion.tipo_evaluado
            FROM
            evaluacion
            WHERE
            evaluacion.id = %s
            )
            and e.id != %s", Auth::info_usuario('evaluacion'),
               Auth::info_usuario('evaluacion'), Auth::info_usuario('evaluacion'));

       $evaluaciones_centro = BD::$db->queryAll($sql_evaluaciones_centro);

       $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);
       $privilegio_replica_red = false;
       $sql_padre_red = sprintf("select count(*) from evaluacion where id = %s and padre is null",
               Auth::info_usuario('evaluacion'));
       $padre_red = BD::$db->queryOne($sql_padre_red);

       if(Auth::info_usuario('ev_red') > 0 && $padre_red > 0){
           $privilegio_replica_red = true;
       }

//       var_dump($evaluacion_data);
//       if(Auth::info_usuario('evaluacion') == 93){
//           var_dump($evaluacion_data['cod_conjunto']);
//       }
       if($rol == 1){
           $vars['cod_momento'] = $_GET['cod_momento'];
           $vars['cod_cargo'] = 1;
           $vars['cod_conjunto'] = $evaluacion_data['cod_conjunto'];
           $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
           $bandera = BD::$db->queryOne($sql_bandera);
           $vars['bandera'] = $bandera;
           $vars['privilegio_replica_red'] = $privilegio_replica_red;
           $vars['evaluacion'] = Auth::info_usuario('evaluacion');
//           $vars['evaluacion_data'] = $evaluacion_data;
           $vars['evaluaciones_red'] = $evaluaciones;
           $vars['evaluaciones_centro'] = $evaluaciones_centro;
           View::render('evaluar/analisis_indicadores.php', $vars);
       }

       else{
          $vars = $this->get_momento_actual();
          $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
          $bandera = BD::$db->queryOne($sql_bandera);
          $vars['bandera'] = $bandera;
          $vars['privilegio_replica_red'] = $privilegio_replica_red;
          $vars['evaluacion'] = Auth::info_usuario('evaluacion');
//          $vars['evaluacion_data'] = $evaluacion_data;
          $vars['evaluaciones_red'] = $evaluaciones;
          $vars['evaluaciones_centro'] = $evaluaciones_centro;
          $vars['cod_conjunto'] = $evaluacion_data['cod_conjunto'];
//          var_dump($vars);
          View::render('evaluar/analisis_indicadores.php', $vars);
       }
    }

    public function get_resultado_red(){
        header('Content-Type: application/json');
        $evaluacion = filter_input(INPUT_GET, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        
        $sql_resultados = "";
        if(Auth::info_usuario('ev_padre') > 0){
            $sql_resultados = sprintf("SELECT distinct
            gradacion_escalas.desc_escala,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_evaluacion.id as momento_evaluacion,
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
            lineamientos.id = %s", $evaluacion, $momento, $lineamiento);
        }
        else{
            $sql_resultados = sprintf("SELECT distinct
            gradacion_escalas.desc_escala,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_evaluacion.id as momento_evaluacion,
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
            inner JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
            INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
            WHERE
            evaluacion.id = %s AND
            momento_evaluacion.cod_momento = %s AND
            lineamientos.id = %s", $evaluacion, $momento, $lineamiento);
        }
        


        //var_dump($sql_resultados);

        $resultados['resultados'] = BD::$db->queryRow($sql_resultados);

        if($resultados['resultados'] == null){
            $sql_resultados = sprintf("SELECT
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
            INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
            INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
            WHERE
            evaluacion.id = %s AND
            lineamientos.id = %s", $evaluacion, $lineamiento);

            $resultados['resultados'] = BD::$db->queryRow($sql_resultados);
        }
        else{
            $sql = sprintf("SELECT
            momento_resultado_anexo.cod_momento_evaluacion,
            gen_documentos.id,
            gen_documentos.ruta,
            gen_documentos.nombre,
            gen_documentos.fecha_creado
            FROM
            momento_resultado_anexo
            INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
            where momento_resultado_anexo.cod_momento_evaluacion = %s and
            momento_resultado_anexo.cod_lineamiento = %s" , $resultados['resultados']['momento_evaluacion'], $lineamiento);
            $resultados['resultados']['anexos'] = BD::$db->queryAll($sql);
//            var_dump($sql);
        }

        echo json_encode($resultados);
    }

    public function get_resultado_red_indicador(){
        header('Content-Type: application/json');
        $evaluacion = filter_input(INPUT_GET, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $momento_evaluacion = filter_input(INPUT_GET, 'momento_evaluacion', FILTER_SANITIZE_NUMBER_INT);
        
        $sql_resultados = sprintf("SELECT distinct
        evaluacion_analisis_indicadores.id,
        evaluacion_analisis_indicadores.analisis,
        momento_evaluacion.id as momento_evaluacion,
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
        LEFT JOIN evaluacion_analisis_indicadores ON evaluacion_analisis_indicadores.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
        INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
        WHERE
        momento_evaluacion.id = %s AND
        lineamientos.id = %s", $momento_evaluacion, $lineamiento);

        var_dump($sql_resultados);

        $resultados['resultados'] = BD::$db->queryRow($sql_resultados);

        if($resultados['resultados'] == null){
            $sql_resultados = sprintf("SELECT
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
            INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
            INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
            WHERE
            evaluacion.id = %s AND
            lineamientos.id = %s", $evaluacion, $lineamiento);

            $resultados['resultados'] = BD::$db->queryRow($sql_resultados);
        }
        else{
            $sql = sprintf("SELECT
            momento_resultado_anexo.cod_momento_evaluacion,
            gen_documentos.id,
            gen_documentos.ruta,
            gen_documentos.nombre,
            gen_documentos.fecha_creado
            FROM
            momento_resultado_anexo
            INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
            where momento_resultado_anexo.cod_momento_evaluacion = %s and
            momento_resultado_anexo.cod_lineamiento = %s" , $resultados['resultados']['momento_evaluacion'], $lineamiento);
            $resultados['resultados']['anexos'] = BD::$db->queryAll($sql);
        }

        echo json_encode($resultados);
    }

    public function guardar_plan_mejoramiento(){


       $evaluacion = Auth::info_usuario('evaluacion');
       if($evaluacion > 0){

       View::add_js('public/js/bootstrap-datepicker.js');
       View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
       View::add_css('public/css/fa410/css/font-awesome.min.css');
       View::add_css('public/css/sievas/styles.css');
       View::add_js('public/summernote/summernote.min.js');
       View::add_js('public/summernote/summernote-es-ES.js');
       View::add_css('public/summernote/summernote.css');
       View::add_css('public/summernote/tooltip_fix.css');
       View::add_js('public/summernote/helper.js');
       View::add_css('public/js/textext/css/textext.core.css');
       View::add_css('public/js/textext/css/textext.plugin.tags.css');
       View::add_js('public/js/textext/js/textext.core.js');
       View::add_js('public/js/textext/js/textext.plugin.tags.js');
       View::add_js('modules/sievas/scripts/evaluar_plan_mejoramiento.js');

       if($rol == 1){
           $vars = array();
           $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];
           $vars['cod_momento'] = $_GET['cod_momento'];
           $vars['cod_cargo'] = 1;
           $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
           $bandera = BD::$db->queryOne($sql_bandera);
           $vars['bandera'] = $bandera;
           View::render('evaluar/evaluar_plan_mejoramiento.php', $vars);
       }
       else{
          $vars = array();
          $vars = $this->get_momento_actual();
          $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];
          $sql_bandera = sprintf("select finalizado from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
          $bandera = BD::$db->queryOne($sql_bandera);
          $vars['bandera'] = $bandera;
          View::render('evaluar/evaluar_plan_mejoramiento.php', $vars);
       }
       }
       else{
           header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
       }
    }

    public function get_arbol(){
        header('Content-Type: application/json');
        $model_sieva_lineamientos = new sieva_lineamientos();
        echo json_encode($model_sieva_lineamientos->cargarArbol());
    }

    public function get_arbol_rubros(){
        header('Content-Type: application/json');
        $model_sieva_lineamientos = new sieva_lineamientos();
        echo json_encode($model_sieva_lineamientos->cargarArbolRubros());
    }

    public function get_momento_actual(){

        $evaluacion = Auth::info_usuario('evaluacion');

        $username = Auth::info_usuario('usuario');

        $sql = "SELECT

        comite.cod_persona,

        evaluacion.id,

        comite.cod_momento_evaluacion,

        comite.cod_cargo,

        comite.comite_centro,

        momento_evaluacion.cod_momento

        FROM

        comite

        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id

        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id

        INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona

        where evaluacion.id=$evaluacion and sys_usuario.username='$username'";



        return BD::$db->queryRow($sql);

    }



    public function get_lineamiento(){

        header('Content-Type: application/json');

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        $cod_momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $indicadores = filter_input(INPUT_GET, 'indicadores', FILTER_SANITIZE_NUMBER_INT);

        //viene de la sesion

        $momento = $this->get_momento_actual();

        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');

//        var_dump($momento);

        if($rol == 1){

            $evaluacion = Auth::info_usuario('evaluacion');

            if($evaluacion > 0){

                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);

                $momento_evaluacion = BD::$db->queryOne($sql_momento);

            }

        }

        if(isset($_REQUEST['momento_evaluacion'])){

             $momento_evaluacion = $_REQUEST['momento_evaluacion'];

        }

        $lineamiento = array();

        if($id > 0){
            if($indicadores > 0){
                $sql = "SELECT
                evaluacion_analisis_indicadores.id,
                evaluacion_analisis_indicadores.analisis,
                evaluacion_analisis_indicadores.cod_lineamiento,
                evaluacion_analisis_indicadores.cod_momento,
                momento_evaluacion.id as cod_momento_evaluacion,
                lineamientos.padre_lineamiento
                FROM
                evaluacion_analisis_indicadores
                INNER JOIN momento_evaluacion ON evaluacion_analisis_indicadores.cod_momento = momento_evaluacion.id
                INNER JOIN lineamientos ON evaluacion_analisis_indicadores.cod_lineamiento = lineamientos.id
                WHERE
                lineamientos.id = $id AND
                momento_evaluacion.id = $momento_evaluacion";

                $lineamiento = BD::$db->queryAll($sql);

//                var_dump($sql);
            }
            else {


            $sql = "SELECT

            momento_resultado_detalle.id,
            momento_resultado_detalle.fortalezas,

            momento_resultado_detalle.debilidades,

            momento_resultado_detalle.plan_mejoramiento,

            momento_resultado_detalle.cod_gradacion_escala,

            momento_resultado_detalle.cod_momento_resultado,

            momento_resultado_detalle.cod_lineamiento,
            momento_resultado.cod_momento_evaluacion,

            lineamientos.padre_lineamiento

            FROM

            lineamientos

            INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id

            INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id

            INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id

            WHERE

            lineamientos.id = $id AND

            momento_evaluacion.id = $momento_evaluacion";

            $lineamiento = BD::$db->queryAll($sql);

            //var_dump($sql);



            if(count($lineamiento) > 0){

                //estadisticas

                $cod_gradacion_escala = $lineamiento[0]['cod_gradacion_escala'];

                if($cod_gradacion_escala > 0){

                    //gradacion

                    $sql_gradacion = sprintf("SELECT

                    gradacion.nivel_bajo,

                    gradacion.nivel_medio,

                    gradacion.nivel_alto

                    FROM

                    gradacion_escalas

                    INNER JOIN gradacion ON gradacion_escalas.cod_gradacion = gradacion.id

                    WHERE

                    gradacion_escalas.id = %s", $cod_gradacion_escala);



                    $gradacion = BD::$db->queryRow($sql_gradacion);



                    $lineamiento[0]['gradacion'] = $gradacion;



                    $sql_calificacion_item = sprintf('select gradacion_escalas.valor_escala from gradacion_escalas where id=%s', $cod_gradacion_escala);

                    $_calificacion = BD::$db->queryOne($sql_calificacion_item);

                    $calificacion = (($_calificacion/10)*100);

                    $sql_puntaje = sprintf("SELECT
                    Sum(gradacion_escalas.valor_escala)
                    FROM
                    momento_resultado_detalle
                    INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
                    WHERE
                    momento_evaluacion.id = %s AND
                    evaluacion.id = %s", $momento_evaluacion, Auth::info_usuario('evaluacion'));

                    $puntaje = BD::$db->queryOne($sql_puntaje);

                    $puntaje = round($puntaje/100,2);





                    //hermanos del item

                    $sql_calificacion_rubro = sprintf('SELECT

                    Avg(gradacion_escalas.valor_escala)

                    FROM

                    lineamientos

                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id

                    INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id

                    INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id

                    INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id

                    where padre_lineamiento = %s and momento_evaluacion.id = %s', $lineamiento[0]['padre_lineamiento'], $momento_evaluacion);

                    $_calificacion_rubro = BD::$db->queryOne( $sql_calificacion_rubro);

                    $calificacion_rubro = (($_calificacion_rubro/10)*100);

                    $lineamiento[0]['estadisticas'] = array('calificacion_item' => $calificacion, 'calificacion_rubro' => array('valor' => $_calificacion_rubro,

                        'porcentaje' => $calificacion_rubro), 'puntaje' => $puntaje);

                    }


            }

            //cerrar condicional
            }
            $sql = sprintf('select documentos from lineamientos_datos where cod_lineamiento = %s', $id);
                $documentos = BD::$db->queryOne($sql);

                $lineamiento[0]['documentos'] = $documentos;

            //privilegiado
            $privilegios = array(
                'privilegio' => 0,
                'privilegiado' => 0
            );

            $privilegio = BD::$db->queryOne(sprintf("SELECT
            evaluacion.tiene_privilegios
            FROM
            evaluacion
            WHERE
            evaluacion.id = %s
            ", Auth::info_usuario('evaluacion')));
            if($privilegio == 1){
                $privilegios['privilegio'] = 1;
                $priv_q = sprintf("SELECT
                Count(evaluacion_privilegios_miembros.id) as privilegiado
                FROM
                evaluacion
                LEFT JOIN evaluacion_privilegios_miembros ON evaluacion.id = evaluacion_privilegios_miembros.cod_evaluacion
                INNER JOIN sys_usuario ON sys_usuario.cod_persona = evaluacion_privilegios_miembros.cod_persona
                WHERE
                evaluacion.tiene_privilegios = 1 AND
                evaluacion_privilegios_miembros.cod_evaluacion = %s AND
                evaluacion_privilegios_miembros.cod_item = %s AND
                sys_usuario.username = '%s'",
                Auth::info_usuario('evaluacion'),$id, Auth::info_usuario('usuario'));

                $priv = BD::$db->queryOne($priv_q);

                if($priv != null){
                    $privilegios['privilegiado'] = $priv;
                }
            }
            if($cod_momento == 2 && Auth::info_usuario('comite_centro') == 0){
                $privilegios = array(
                    'privilegio' => 0,
                    'privilegiado' => 0
                );
            }
            $lineamiento[0]['privilegios'] = $privilegios;

        }
        echo json_encode($lineamiento);



    }

    public function guardar_reevaluacion(){
        header('Content-Type: application/json');

        $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $fortalezas_data = filter_input(INPUT_POST, 'fortalezas_data', FILTER_SANITIZE_STRING);
        $debilidades_data = filter_input(INPUT_POST, 'debilidades_data', FILTER_SANITIZE_STRING);
        $planesmejora_data = filter_input(INPUT_POST, 'planesmejora_data', FILTER_SANITIZE_STRING);
        /*
         * Opciones
         * 0 -> nulo
         * 1 -> No opino
         * 2 -> Si opino
         * 3 -> No opino pero
         */
        $fortalezas_opcion = filter_input(INPUT_POST, 'fortalezas_opcion', FILTER_SANITIZE_NUMBER_INT);
        $debilidades_opcion = filter_input(INPUT_POST, 'debilidades_opcion', FILTER_SANITIZE_NUMBER_INT);
        $planesmejora_opcion = filter_input(INPUT_POST, 'planesmejora_opcion', FILTER_SANITIZE_NUMBER_INT);
        $calificacion_nueva = filter_input(INPUT_POST, 'calificacion_nueva', FILTER_SANITIZE_NUMBER_INT);
        $evaluacion = Auth::info_usuario('evaluacion');
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
        
        $cod_momento = $momento['cod_momento'];

        $fortalezas = '';
        $debilidades = '';
        $plan_mejoramiento = '';
        $calificacion = 0;

        if($calificacion_nueva > 0){
            $calificacion = $calificacion_nueva;
        }

        //check de evaluacion
        $sql_check = sprintf("SELECT
        lineamientos.id,
        momento_resultado.id as resultado_id,
        momento_evaluacion.id as momento_id,
        evaluacion.ev_seguimiento
        FROM
        lineamientos
        INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
        INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
        INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        WHERE
        lineamientos.id = %s and momento_evaluacion.id=%s", $lineamiento, $momento_evaluacion);
        $evaluacion_chk = BD::$db->queryAll($sql_check);

        //habilitar funcionalidad de evaluacion
        if($evaluacion_chk['ev_seguimiento'] > 0){
            $cod_momento = 1;
        }
//        var_dump($sql_check);

         /*
             * Logica de opciones
             * Si opcion es nula error
             * Si opcion es no opino se guarda una copia de la evaluacion externa anterior
             * Si opcion es si opino se guarda la informacion ingresada como nueva evaluacion
             * Si opcion es no opino pero se guarda la evaluacion externa anterior mas la informacion ingresada
             */
             
            switch($fortalezas_opcion){
                case '0':
                    //si trigger es fortalezas mandar error
                    break;
                case '1':
                    $query_anterior = sprintf("SELECT
                    momento_resultado_detalle.fortalezas
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.ev_anterior
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    WHERE
                    evaluacion.id = %s and lineamientos.id = %s and momento_evaluacion.cod_momento = %s", $evaluacion, $lineamiento, $cod_momento);

                    $fortalezas = BD::$db->queryOne($query_anterior);
                    break;
                case '2':
                    $fortalezas = $fortalezas_data;
                    break;
                case '3':
                    $query_anterior = sprintf("SELECT
                    momento_resultado_detalle.fortalezas
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.ev_anterior
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    WHERE
                    evaluacion.id = %s and lineamientos.id = %s and momento_evaluacion.cod_momento = %s", $evaluacion, $lineamiento, $cod_momento);

                    $fortalezas = BD::$db->queryOne($query_anterior);

                    $fortalezas .= $fortalezas_data;
                    break;
            }

            switch($debilidades_opcion){
                case '0':
                    //si trigger es debilidades mandar error
                    break;
                case '1':
                    $query_anterior = sprintf("SELECT
                    momento_resultado_detalle.debilidades
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.ev_anterior
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    WHERE
                    evaluacion.id = %s and lineamientos.id = %s and momento_evaluacion.cod_momento = %s", $evaluacion, $lineamiento, $cod_momento);

                    $debilidades = BD::$db->queryOne($query_anterior);
                    break;
                case '2':
                    $debilidades = $debilidades_data;
                    break;
                case '3':
                    $query_anterior = sprintf("SELECT
                    momento_resultado_detalle.debilidades
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.ev_anterior
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    WHERE
                    evaluacion.id = %s and lineamientos.id = %s and momento_evaluacion.cod_momento = %s", $evaluacion, $lineamiento, $cod_momento);

                    $debilidades = BD::$db->queryOne($query_anterior);

                    $debilidades .= $debilidades_data;
                    break;
            }

            switch($planesmejora_opcion){
                case '0':
                    //si trigger es planesmejora mandar error
                    break;
                case '1':
                    $query_anterior = sprintf("SELECT
                    momento_resultado_detalle.plan_mejoramiento
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.ev_anterior
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    WHERE
                    evaluacion.id = %s and lineamientos.id = %s and momento_evaluacion.cod_momento = %s", $evaluacion, $lineamiento, $cod_momento);

                    $plan_mejoramiento = BD::$db->queryOne($query_anterior);
                    break;
                case '2':
                    $planesmejora = $planesmejora_data;
                    $plan_mejoramiento .= $planesmejora_data;
                    break;
                case '3':
                    $query_anterior = sprintf("SELECT
                    momento_resultado_detalle.plan_mejoramiento
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.ev_anterior
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
                    WHERE
                    evaluacion.id = %s and lineamientos.id = %s and momento_evaluacion.cod_momento = %s", $evaluacion, $lineamiento, $cod_momento);

                    $plan_mejoramiento = BD::$db->queryOne($query_anterior);

                    $plan_mejoramiento .= $planesmejora_data;
                    break;
            }



        if(count($evaluacion_chk) === 0){
            //guardar evaluacion
            $model = new momento_resultado();
            $model->begin();
            $model->desc_resultado = '';
            $model->cod_momento_evaluacion = $momento_evaluacion;
            if($model->save()){
                $model_detalle = new momento_resultado_detalle();
                $model_detalle->cod_momento_resultado = $model->id;
                $model_detalle->cod_lineamiento = $lineamiento;
                if($calificacion > 0){
                    $model_detalle->cod_gradacion_escala = $calificacion;
                }
                if($fortalezas != '' || $fortalezas != null)
                $model_detalle->fortalezas = $fortalezas;
                if($debilidades != '' || $debilidades != null)
                $model_detalle->debilidades = $debilidades;
                if($plan_mejoramiento != '' || $plan_mejoramiento != null)
                $model_detalle->plan_mejoramiento = $plan_mejoramiento;
                if($model_detalle->save()){
                    //guardar reevaluacion
                    $model_reevaluacion = new momento_resultado_reevaluacion();
                    if($fortalezas_opcion > 0)
                    $model_reevaluacion->fortalezas_opcion = $fortalezas_opcion;
                    if($fortalezas_data != '' || $fortalezas_data != null)
                    $model_reevaluacion->fortalezas_data = $fortalezas_data;
                     if($debilidades_opcion > 0)
                    $model_reevaluacion->debilidades_opcion = $debilidades_opcion;
                     if($debilidades_data != '' || $debilidades_data != null)
                    $model_reevaluacion->debilidades_data = $debilidades_data;
                     if($planesmejora_opcion > 0)
                    $model_reevaluacion->planesmejora_opcion = $planesmejora_opcion;
                     if($planesmejora_data != '' || $planesmejora_data != null)
                    $model_reevaluacion->planesmejora_data = $planesmejora_data;
                     if($lineamiento > 0)
                    $model_reevaluacion->cod_lineamiento = $lineamiento;
                    $model_reevaluacion->cod_momento_resultado = $model->id;

                    if($model_reevaluacion->save()){
                        $model->commit();
                        echo json_encode(array(
                            'status' => 'ok',
                            'fortalezas' => $fortalezas,
                            'debilidades' => $debilidades,
                            'planesmejora' => $plan_mejoramiento
                        ));
                    }
                    else{
                        $model->rollback();
                    }
                }
                else{
                    $model->rollback();
                }
            }
            else{
                $model->rollback();
            }

        }
        else{
            //actualizar evaluacion
            $model_detalle = new momento_resultado_detalle();
            $model_detalle->begin();
            $model_detalle->find('cod_momento_resultado='.$evaluacion_chk[0]['resultado_id']);
            $model_detalle->cod_lineamiento = $lineamiento;
            if($calificacion > 0){
                $model_detalle->cod_gradacion_escala = $calificacion_nueva;
            }
            if($fortalezas != '' || $fortalezas != null)
            $model_detalle->fortalezas = $fortalezas;
            if($debilidades != '' || $debilidades != null)
            $model_detalle->debilidades = $debilidades;
            if($plan_mejoramiento != '' || $plan_mejoramiento != null)
            $model_detalle->plan_mejoramiento = $plan_mejoramiento;
//            var_dump($plan_mejoramiento);
//            var_dump($model_detalle->plan_mejoramiento);
            if($model_detalle->update()){
                //actualizar reevaluacion
                $model_reevaluacion = new momento_resultado_reevaluacion();
                $model_reevaluacion->find('cod_momento_resultado='.$evaluacion_chk[0]['resultado_id']);
                if($fortalezas_opcion > 0)
                $model_reevaluacion->fortalezas_opcion = $fortalezas_opcion;
                if($fortalezas_data != '' || $fortalezas_data != null)
                $model_reevaluacion->fortalezas_data = $fortalezas_data;
                 if($debilidades_opcion > 0)
                $model_reevaluacion->debilidades_opcion = $debilidades_opcion;
                 if($debilidades_data != '' || $debilidades_data != null)
                $model_reevaluacion->debilidades_data = $debilidades_data;
                 if($planesmejora_opcion > 0)
                $model_reevaluacion->planesmejora_opcion = $planesmejora_opcion;
                 if($planesmejora_data != '' || $planesmejora_data != null)
                $model_reevaluacion->planesmejora_data = $planesmejora_data;
                 if($lineamiento > 0)
                $model_reevaluacion->cod_lineamiento = $lineamiento;


                if($model_reevaluacion->update()){
                    $model_detalle->commit();
                     echo json_encode(array(
                            'status' => 'ok',
                            'fortalezas' => $fortalezas,
                            'debilidades' => $debilidades,
                            'planesmejora' => $plan_mejoramiento
                        ));
                }
                else{
                    $model_reevaluacion->cod_momento_resultado = $evaluacion_chk[0]['resultado_id'];
                    if($model_reevaluacion->save()){
                        $model_detalle->commit();
                        echo json_encode(array('status' => 'ok'));
                    }
                    else{
                        $model_detalle->rollback();
//                        var_dump($model_reevaluacion->error_sql());
                    }

                }
            }
        }



    }



    public function guardar_evaluacion_interna(){

        header('Content-Type: application/json');

        $calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_SANITIZE_NUMBER_INT);

        $fortalezas = addslashes($_POST['fortalezas']);

        $debilidades = addslashes($_POST['debilidades']);

        $plan_mejoramiento = addslashes($_POST['plan_mejoramiento']);

        $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);

        $cod_momento = filter_input(INPUT_POST, 'cod_momento', FILTER_SANITIZE_NUMBER_INT);

        $valid = true;

        //debe estar definido en la sesion

        $momento = $this->get_momento_actual();

        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');

        if($rol == 1){

            $evaluacion = Auth::info_usuario('evaluacion');

//            var_dump($evaluacion);

            if($evaluacion > 0){

                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);

                $momento_evaluacion = BD::$db->queryOne($sql_momento);

            }

        }



//        //revisar si existe calificacion para el lineamiento

        $sql_check = "SELECT

        lineamientos.id,

        momento_resultado.id as resultado_id,

        momento_evaluacion.id as momento_id

        FROM

        lineamientos

        INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_lineamiento = lineamientos.id

        INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id

        INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id

        WHERE

        lineamientos.id = $lineamiento and momento_evaluacion.id=$momento_evaluacion";



        $evaluacion_chk = BD::$db->queryAll($sql_check);

//        var_dump($evaluacion_chk);

//        //tener en cuenta la autoevaluacion

        if(count($evaluacion_chk) === 0){

            $model = new momento_resultado();

            $model->begin();

            $model->desc_resultado = '';

            $model->cod_momento_evaluacion = $momento_evaluacion;

            if($model->save()){

                $model_detalle = new momento_resultado_detalle();

                $model_detalle->cod_momento_resultado = $model->id;

                $model_detalle->cod_lineamiento = $lineamiento;

                if($calificacion > 0){

                    $model_detalle->cod_gradacion_escala = $calificacion;

                }

                $model_detalle->fortalezas = $fortalezas;

                $model_detalle->debilidades = $debilidades;

                $model_detalle->plan_mejoramiento = $plan_mejoramiento;

                if($model_detalle->save()){

                    if(false && count($plan_mejoramiento) > 0){

                        //verificar si existe plan de mejoramiento y actualizar

                        $model_plan_mejoramiento = new plan_mejoramiento();

                        $model_plan_mejoramiento->cod_momento_resultado = $model->id;

                        $metas = array();

                        $acciones = array();

                        foreach($plan_mejoramiento as $p){

                            switch($p->name){

                                case 'titulo':

                                    $model_plan_mejoramiento->titulo = $p->value;

                                    break;

                                case 'subtitulo':

                                    $model_plan_mejoramiento->subtitulo = $p->value;

                                    break;

                                case 'presupuesto':

                                    $model_plan_mejoramiento->presupuesto = $p->value;

                                    break;

                                case 'fecha_cumplimiento':

                                    $model_plan_mejoramiento->fecha_cumplimiento = $p->value;

                                    break;

                                case 'objetivos':

                                    $model_plan_mejoramiento->objetivos = $p->value;

                                    break;

                                case 'estrategias':

                                    $model_plan_mejoramiento->estrategias = $p->value;

                                    break;

                                case 'metas':

                                    $metas = $p->value;

                                    break;

                                case 'acciones':

                                    $acciones = $p->value;

                                    break;

                            }

                        }

                        if($model_plan_mejoramiento->save()){

                            //borrar metas y acciones anteriores



                            foreach(json_decode($metas) as $m){

                                $model_pm_metas = new plan_mejoramiento_metas();

                                $model_pm_metas->meta = $m;

                                $model_pm_metas->cod_plan_mejoramiento = $model_plan_mejoramiento->id;

                                if(!$model_pm_metas->save()){

                                    $valid = false;

                                }

                            }



                            foreach(json_decode($acciones) as $a){

                                $model_pm_acciones = new plan_mejoramiento_acciones();

                                $model_pm_acciones->accion = $a;

                                $model_pm_acciones->cod_plan_mejoramiento = $model_plan_mejoramiento->id;

                                if(!$model_pm_acciones->save()){

                                    $valid = false;

                                }

                            }



                        }

                        else{

                            $valid = false;

                        }

                    }

                }

                else{

                    $valid = false;
                    echo $model_detalle->error_sql();

                }

            }

            else{

                $valid = false;
                echo "err2";
            }



            if($valid){

                $model->commit();

                echo json_encode(array('status' => 'ok'));

            }

            else{

                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                echo json_encode(array('error' => 'No se pudo guardar la información'));

            }

        }

        else{

            $model_detalle = new momento_resultado_detalle();

            $model_detalle->find('cod_momento_resultado='.$evaluacion_chk[0]['resultado_id']);

            $model_detalle->cod_lineamiento = $lineamiento;



            if($calificacion > 0){

                $model_detalle->cod_gradacion_escala = $calificacion;

            }



            $model_detalle->fortalezas = $fortalezas;

            $model_detalle->debilidades = $debilidades;

            $model_detalle->plan_mejoramiento = $plan_mejoramiento;

            if($model_detalle->update()){

                if(false && count($plan_mejoramiento) > 0){

                    $model_plan_mejoramiento = new plan_mejoramiento();

                    $model_plan_mejoramiento->find('cod_momento_resultado='.$evaluacion[0]['resultado_id']);

                    $metas = array();

                    $acciones = array();

                    foreach($plan_mejoramiento as $p){

                        switch($p->name){

                            case 'titulo':

                                $model_plan_mejoramiento->titulo = $p->value;

                                break;

                            case 'subtitulo':

                                $model_plan_mejoramiento->subtitulo = $p->value;

                                break;

                            case 'presupuesto':

                                $model_plan_mejoramiento->presupuesto = $p->value;

                                break;

                            case 'fecha_cumplimiento':

                                $model_plan_mejoramiento->fecha_cumplimiento = $p->value;

                                break;

                            case 'objetivos':

                                $model_plan_mejoramiento->objetivos = $p->value;

                                break;

                            case 'estrategias':

                                $model_plan_mejoramiento->estrategias = $p->value;

                                break;

                            case 'metas':

                                $metas = $p->value;

                                break;

                            case 'acciones':

                                $acciones = $p->value;

                                break;

                        }

                    }

                    if($model_plan_mejoramiento->update()){

                        $sql_delete_metas = "delete from plan_mejoramiento_metas where cod_plan_mejoramiento=$model_plan_mejoramiento->id";

                        BD::$db->query($sql_delete_metas);

                        $sql_delete_acciones = "delete from plan_mejoramiento_acciones where cod_plan_mejoramiento=$model_plan_mejoramiento->id";

                        BD::$db->query($sql_delete_acciones);



                        foreach(json_decode($metas) as $m){

                            $model_pm_metas = new plan_mejoramiento_metas();

                            $model_pm_metas->meta = $m;

                            $model_pm_metas->cod_plan_mejoramiento = $model_plan_mejoramiento->id;

                            if(!$model_pm_metas->save()){

                                $valid = false;

                                echo "err1";

                            }

                        }



                        foreach(json_decode($acciones) as $a){

                            $model_pm_acciones = new plan_mejoramiento_acciones();

                            $model_pm_acciones->accion = $a;

                            $model_pm_acciones->cod_plan_mejoramiento = $model_plan_mejoramiento->id;

                            if(!$model_pm_acciones->save()){

                                $valid = false;

                                echo "err2";

                            }

                        }



                    }

                    else{

                        $valid = false;

                        echo "err3";

                        echo $model_plan_mejoramiento->error_sql();

                    }

                }

                else{

//                    $valid = false;

//                    echo "err4";

                }



            }

            else{

                $valid = false;

                 echo "err5";

                 echo $model_detalle->error_sql();

            }



            if($valid){

                $model_detalle->commit();

                echo json_encode(array('status' => 'ok'));

            }

            else{

                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                echo json_encode(array('error' => 'No se pudo guardar la información'));

            }

        }



    }


    public function guardar_analisis(){
        header('Content-Type: application/json');
        $analisis = addslashes($_POST['analisis']);
        $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $cod_momento = filter_input(INPUT_POST, 'cod_momento', FILTER_SANITIZE_NUMBER_INT);
        $valid = true;

        //debe estar definido en la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
//            var_dump($evaluacion);
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        //revisar si existe analisis para indicador
        $sql_check = sprintf("select evaluacion_analisis_indicadores.id from evaluacion_analisis_indicadores
            where evaluacion_analisis_indicadores.cod_momento = %s and evaluacion_analisis_indicadores.cod_lineamiento = %s", $momento_evaluacion, $lineamiento);
        $check = BD::$db->queryOne($sql_check);

        if($check > 0){
            $model = new evaluacion_analisis_indicadores();
            $model->begin();
            $model->id = $check;
            $model->analisis = $analisis;
            $model->cod_lineamiento = $lineamiento;
            $model->cod_momento = $momento_evaluacion;
            if(!$model->update()){
               $valid = false;
            }

            if($valid){
                $model->commit();
                echo json_encode(array('status' => 'ok'));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'No se pudo guardar la información'));
            }
        }
        else{
            $model = new evaluacion_analisis_indicadores();
            $model->begin();
            $model->analisis = $analisis;
            $model->cod_lineamiento = $lineamiento;
            $model->cod_momento = $momento_evaluacion;
            if(!$model->save()){
               $valid = false;
            }

            if($valid){
                $model->commit();
                echo json_encode(array('status' => 'ok'));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'No se pudo guardar la información'));
            }

        }
    }



    public function get_metaevaluacion_rubro(){

        header('Content-Type: application/json');

        $rubro = filter_input(INPUT_GET, 'rubro', FILTER_SANITIZE_NUMBER_INT);

        $evaluacion = Auth::info_usuario('evaluacion');



        $sql_metaevaluacion = sprintf('select * from metaevaluacion where cod_evaluacion=%s and cod_lineamiento=%s', $evaluacion, $rubro);

        $metaevaluacion = BD::$db->queryAll($sql_metaevaluacion);



        echo json_encode($metaevaluacion);

    }



    public function guardar_metaevaluacion(){

        header('Content-Type: application/json');

        $anotacion = $_POST['anotacion'];

        $rubro = filter_input(INPUT_POST, 'rubro', FILTER_SANITIZE_NUMBER_INT);

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_SANITIZE_NUMBER_INT);

        $evaluacion = Auth::info_usuario('evaluacion');





        if($id > 0){

            $metaevaluacion = new metaevaluacion();

            $metaevaluacion->cod_lineamiento = $rubro;

            $metaevaluacion->cod_evaluacion = $evaluacion;

            $metaevaluacion->anotacion = $anotacion;

            $metaevaluacion->cod_gradacion_escala = $calificacion;

            $metaevaluacion->id = $id;



            if($metaevaluacion->update()){

                echo json_encode(array('mensaje' => 'Se ha guardado correctamente', 'id' => $metaevaluacion->id));

            }

            else{

                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                echo "No se pudo guardar la información";

                echo $metaevaluacion->error_sql();

            }

        }

        else{

            $metaevaluacion = new metaevaluacion();

            $metaevaluacion->cod_lineamiento = $rubro;

            $metaevaluacion->cod_evaluacion = $evaluacion;

            $metaevaluacion->anotacion = $anotacion;

            $metaevaluacion->cod_gradacion_escala = $calificacion;



            if($metaevaluacion->save()){

                echo json_encode(array('mensaje' => 'Se ha guardado correctamente', 'id' => $metaevaluacion->id));

            }

            else{

                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                echo "No se pudo guardar la información";

            }

        }



    }



    public function get_plan_mejoramiento(){

        header('Content-Type: application/json');

        $evaluacion = Auth::info_usuario('evaluacion');

        $cod_momento = filter_input(INPUT_GET, 'cod_momento', FILTER_SANITIZE_NUMBER_INT);

        $momento = $this->get_momento_actual();

        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');

        if($rol == 1){

            $evaluacion = Auth::info_usuario('evaluacion');

//            var_dump($evaluacion);

            if($evaluacion > 0){

                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);

                $momento_evaluacion = BD::$db->queryOne($sql_momento);

            }

        }

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);



        $sql_plan_mejoramiento = "select * from plan_mejoramiento where cod_evaluacion = $evaluacion and cod_lineamiento = $id and cod_momento_evaluacion= $momento_evaluacion";

        $_plan_mejoramiento = BD::$db->queryRow($sql_plan_mejoramiento);



        $plan_mejoramiento = array();

        $metas = array();

        $acciones = array();

        if($_plan_mejoramiento != null){

            $sql_acciones = "SELECT

            plan_mejoramiento_acciones.id,
            plan_mejoramiento_acciones.accion,
            plan_mejoramiento_acciones.metas,
            plan_mejoramiento_acciones.responsables

            FROM

            plan_mejoramiento

            INNER JOIN plan_mejoramiento_acciones ON plan_mejoramiento_acciones.cod_plan_mejoramiento = plan_mejoramiento.id

            WHERE

            plan_mejoramiento.id =

            ".$_plan_mejoramiento['id'];



            $rs_acciones = BD::$db->queryAll($sql_acciones);



            if(count($_plan_mejoramiento) > 0){

                foreach($_plan_mejoramiento as $key=>$p){

                    $plan_mejoramiento[] = array('name' => $key, 'value' => $p);

                }

            }

            if(count($rs_acciones) > 0){

                foreach($rs_acciones as $key=>$a){

                    $acciones[] = $a;

                }

            }

            $plan_mejoramiento[] = array('name' => 'acciones', 'value' => json_encode($acciones));

        }



        echo json_encode($plan_mejoramiento);

    }



    public function save_plan_mejoramiento(){

        header('Content-Type: application/json');

        $evaluacion = Auth::info_usuario('evaluacion');

        $cod_momento = filter_input(INPUT_POST, 'cod_momento', FILTER_SANITIZE_NUMBER_INT);

        $momento = $this->get_momento_actual();

        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');

        if($rol == 1){

            $evaluacion = Auth::info_usuario('evaluacion');

//            var_dump($evaluacion);

            if($evaluacion > 0){

                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);

                $momento_evaluacion = BD::$db->queryOne($sql_momento);

            }

        }

        $plan_mejoramiento = $_POST['plan_mejoramiento'];

        $model_plan_mejoramiento = new plan_mejoramiento();

        $model_plan_mejoramiento->cod_evaluacion = $evaluacion;

        $model_plan_mejoramiento->cod_momento_evaluacion = $momento_evaluacion;

        $acciones = array();

        $valid = true;

        $id=0;

        foreach($plan_mejoramiento as $p){

            switch($p['name']){

                case 'titulo':

                    $model_plan_mejoramiento->titulo = $p['value'];

                    break;

                case 'subtitulo':

                    $model_plan_mejoramiento->subtitulo = $p['value'];

                    break;

                case 'presupuesto':

                    $model_plan_mejoramiento->presupuesto = addslashes($p['value']);

                    break;

                case 'fecha_inicio':

                    $model_plan_mejoramiento->fecha_inicio = $p['value'];

                    break;

                case 'fecha_fin':

                    $model_plan_mejoramiento->fecha_fin = $p['value'];

                    break;

                case 'objetivos':

                    $model_plan_mejoramiento->objetivos = addslashes($p['value']);

                    break;

                case 'estrategias':

                    $model_plan_mejoramiento->estrategias = addslashes($p['value']);

                    break;

                case 'lineamiento':

                    $model_plan_mejoramiento->cod_lineamiento = $p['value'];

                    break;

                case 'acciones':

                    $acciones = json_decode($p['value']);

                    break;

                case 'id':

                    $id = $p['value'];

                    break;

            }

        }

        if($id > 0){

            $model_plan_mejoramiento->id = $id;

            if($model_plan_mejoramiento->update()){

            //borrar metas y acciones anteriores

                $sql_delete_metas = "delete from plan_mejoramiento_metas where cod_plan_mejoramiento=$model_plan_mejoramiento->id";

                BD::$db->query($sql_delete_metas);

                $sql_delete_acciones = "delete from plan_mejoramiento_acciones where cod_plan_mejoramiento=$model_plan_mejoramiento->id";

                BD::$db->query($sql_delete_acciones);


                if(count($acciones) > 0){

                    foreach($acciones as $a){

                        $model_pm_acciones = new plan_mejoramiento_acciones();

                        $model_pm_acciones->accion = $a->nombre_accion;
                        $model_pm_acciones->metas = $a->metas_accion;
                        $model_pm_acciones->responsables = $a->responsables_accion;

                        $model_pm_acciones->cod_plan_mejoramiento = $model_plan_mejoramiento->id;

                        if(!$model_pm_acciones->save()){
                            $valid = false;
                        }

                    }

                }

            }

            else{

                $valid = false;

                 echo $model_plan_mejoramiento->error_sql();

            }



             if($valid){

                    $model_plan_mejoramiento->commit();

                    echo json_encode(array('id' => $model_plan_mejoramiento->id));

                }

                else{

                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                    echo json_encode(array('error' => 'No se pudo guardar la información'));

                }

            }

        else{

            if($model_plan_mejoramiento->save()){

            //borrar metas y acciones anteriored

                if(count($acciones) > 0){

                    foreach($acciones as $a){

                        $model_pm_acciones = new plan_mejoramiento_acciones();

                        $model_pm_acciones->accion = $a->nombre_accion;
                        $model_pm_acciones->metas = $a->metas_accion;
                        $model_pm_acciones->responsables = $a->responsables_accion;

                        $model_pm_acciones->cod_plan_mejoramiento = $model_plan_mejoramiento->id;

                        if(!$model_pm_acciones->save()){

                            $valid = false;

                        }

                    }

                }

            }

            else{

                $valid = false;

                 echo $model_plan_mejoramiento->error_sql();

            }



             if($valid){

                    $model_plan_mejoramiento->commit();

                    echo json_encode(array('id' => $model_plan_mejoramiento->id));

                }

                else{

                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

                    echo json_encode(array('error' => 'No se pudo guardar la información'));

                }

            }
    }


    public function get_listado_anexos(){
        header('Content-Type: application/json');
        $cod_momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        $sql = "SELECT
        momento_resultado_anexo.cod_momento_evaluacion,
        gen_documentos.id,
        gen_documentos.ruta,
        gen_documentos.nombre,
        gen_documentos.fecha_creado
        FROM
        momento_resultado_anexo
        INNER JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
        where momento_resultado_anexo.cod_momento_evaluacion = $momento_evaluacion and momento_resultado_anexo.cod_lineamiento != $lineamiento";


        $anexos = BD::$db->queryAll($sql);
        echo json_encode($anexos);
    }

    public function asociar_anexo(){
        $valid = true;
        header('Content-Type: application/json');
        $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_NUMBER_INT);
        $lineamiento_id = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $cod_momento = filter_input(INPUT_POST, 'momento', FILTER_SANITIZE_NUMBER_INT);
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        header('Content-Type: application/json');
        $model_anexos = new momento_resultado_anexo();
        $model_anexos->cod_documento = $documento;
        $model_anexos->cod_momento_evaluacion = $momento_evaluacion;
        $model_anexos->cod_lineamiento = $lineamiento_id;
        if(!$model_anexos->save()){
            $valid = false;
        }

        if($valid){
            $model_anexos->commit();
            echo json_encode(array('status' => 'ok'));
        }
        else{
           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
           echo json_encode(array('error' => 'No se pudo asociar el archivo'));
        }
    }

    public function get_escala_completa(){
        header('Content-Type: application/json');
        //gradacion por defecto debe venir de alguna config
         $evaluacion = Auth::info_usuario('evaluacion');
         $sql_check_gradacion = sprintf("select gradacion_id from evaluacion where id = %s", $evaluacion);
//         var_dump($sql_check_gradacion);
         $gradacion = BD::$db->queryOne($sql_check_gradacion);
         
         if(!($gradacion > 0)){
             $gradacion = 1;
         }        
        
        $sql = '';
        $cod_idioma = Auth::info_usuario('cod_idioma');
        switch($cod_idioma){
            case "4":
                 $sql = sprintf("SELECT
                gradacion_escalas_traducciones.gradacion_escalas_1 as id,
                gradacion_escalas.desc_escala,
                gradacion_escalas.valor_escala
                FROM
                gradacion
                inner join gradacion_traducciones on gradacion.id = gradacion_traducciones.gradacion_1
                INNER JOIN gradacion_escalas ON gradacion_escalas.cod_gradacion = gradacion_traducciones.gradacion_2
                inner join gradacion_escalas_traducciones on gradacion_escalas.id = gradacion_escalas_traducciones.gradacion_escalas_2
                where gradacion.id=%s and gradacion_traducciones.cod_idioma=4 order by gradacion_escalas.valor_escala desc", $gradacion);
                break;
            default:
                 $sql = "SELECT
                gradacion_escalas.id,
                gradacion_escalas.desc_escala,
                gradacion_escalas.valor_escala
                FROM
                gradacion
                INNER JOIN gradacion_escalas ON gradacion_escalas.cod_gradacion = gradacion.id
                where gradacion.id=$gradacion order by gradacion_escalas.valor_escala desc";
                break;
        }
//        var_dump($sql);
        $escala = BD::$db->queryAll($sql);

        echo json_encode($escala);
    }

    public function get_escala(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $escala = array();
        if($id > 0){
            $sql = "SELECT
            gradacion_escalas.id,
            gradacion_escalas.desc_escala
            FROM
            gradacion_escalas
            where gradacion_escalas.id=$id";
            $escala = BD::$db->queryAll($sql);
        }
        echo json_encode($escala);
    }

    public function get_referencias(){

        header('Content-Type: application/json');

        $referencias = array();

        //viene de la sesion

        $momento = $this->get_momento_actual();
        $cod_momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
//        $cod_momento = 1;

        $rol = Auth::info_usuario('rol');
        if($rol == 1 || $momento['cod_momento'] == '2'){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
//                var_dump($sql_momento);
            }
        }

        $sql_evaluacion = "SELECT

        momento_evaluacion.cod_momento,

        momento_evaluacion.fecha_inicia,

        evaluacion.tipo_evaluado,

        evaluacion.cod_evaluado,

        momentos.momento

        FROM

        momento_evaluacion

        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id

        INNER JOIN momentos ON momento_evaluacion.cod_momento = momentos.id

        where momento_evaluacion.id = $momento_evaluacion";

//        var_dump($sql_evaluacion);

        $evaluacion = BD::$db->queryAll($sql_evaluacion);

        if(!PEAR::isError($evaluacion) && count($evaluacion) > 0){

            //si evaluacion es interna se muestran los resultados de evaluaciones anteriores

            $momento = $evaluacion[0]['cod_momento'];

            $momento_readable = $evaluacion[0]['momento'];

            $tipo_evaluado = $evaluacion[0]['tipo_evaluado'];

            $cod_evaluado = $evaluacion[0]['cod_evaluado'];

            $fecha_inicia = $evaluacion[0]['fecha_inicia'];

            switch($momento){

                case "1":

                    //buscar evaluaciones del mismo paciente

                    $sql_referencias = sprintf("SELECT
                    evaluacion.cod_evaluado,
                    evaluacion.tipo_evaluado,
                    momento_evaluacion.fecha_inicia,
                    momento_evaluacion.cod_momento,
                    momento_evaluacion.id as momento_evaluacion
                    FROM
                    momento_evaluacion
                    INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    WHERE
                    momento_evaluacion.fecha_inicia <= '%s' AND
                    evaluacion.cod_evaluado = %s AND
                    evaluacion.tipo_evaluado = %s AND
                    momento_evaluacion.id != %s and momento_evaluacion.cod_momento = 1", $fecha_inicia, $cod_evaluado, $tipo_evaluado, $momento_evaluacion);

                    $referencias = BD::$db->queryAll($sql_referencias);


                    if(count($referencias) > 0){
                        foreach($referencias as $key=>$r){
                            $referencias[$key]['momento'] = $r['cod_momento'] == 1 ? 'Evaluación interna' : 'Evaluación externa';
                        }
                    }

                    //buscar resultados de las evaluaciones (1 por evaluacion)

                    break;

                case "2":


                    $sql_referencias = sprintf("SELECT
                    evaluacion.cod_evaluado,
                    evaluacion.tipo_evaluado,
                    momento_evaluacion.fecha_inicia,
                    momento_evaluacion.cod_momento,
                    momento_evaluacion.id as momento_evaluacion
                    FROM
                    momento_evaluacion
                    INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    WHERE
                    evaluacion.cod_evaluado = %s AND
                    evaluacion.tipo_evaluado = %s AND
                    momento_evaluacion.id != %s", $cod_evaluado, $tipo_evaluado, $momento_evaluacion);


//                    var_dump($sql_referencias);

                    $referencias = BD::$db->queryAll($sql_referencias);

                    if(count($referencias) > 0){
                        foreach($referencias as $key=>$r){
                            $referencias[$key]['momento'] = $r['cod_momento'] == 1 ? 'Evaluación interna' : 'Evaluación externa';
                        }


                    }

                    break;

            }

        }



        echo json_encode($referencias);

    }

    public function upload_anexo(){
        header('Content-Type: application/json');
        date_default_timezone_set('America/Bogota');
        $now = new DateTime();
        $archivo = $_FILES['archivo'];
        $cod_momento = filter_input(INPUT_POST, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $nuevo = filter_input(INPUT_POST, 'nuevo', FILTER_SANITIZE_NUMBER_INT);
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        $lineamiento_id = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);

        $anexo = array();

        $valid = true;

        if($archivo['error'] === UPLOAD_ERR_OK){
            if($archivo['size'] < 50000000 || true){
                $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$archivo['name'];
                $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                    $model = new gen_documentos();
                    $model->begin();
                    $model->ruta = $rel_path;
                    $model->nombre = $archivo['name'];
                    if($model->save()){
                        $model_anexos = new momento_resultado_anexo();
                        $model_anexos->cod_documento = $model->id;
                        $model_anexos->cod_momento_evaluacion = $momento_evaluacion;
                        $model_anexos->cod_lineamiento = $lineamiento_id;
                        if($nuevo > 0){
                            $model_anexos->nuevo = $nuevo;
                        }
                        if(!$model_anexos->save()){
                            $valid = false;
                        }
                    }
                    else{
                        $valid = false;
                    }
                    if($valid){
                        $model->commit();
                        echo json_encode(array('status' => 'ok', 'ruta' => $rel_path, 'nombre' => $model->nombre, 'id' => $model->id));
                    }
                    else{
                       header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                       echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                    }
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                 }
            }
            else{
                //el archivo no puede superar los 10 Mb
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'El archivo no puede superar los 10 Mb'));
            }
        }
        else{
           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
           echo json_encode(array('error' => 'No se pudo guardar el archivo'));
        }
    }

    public function upload_anexo_c(){
        header('Content-Type: application/json');
        date_default_timezone_set('America/Bogota');
        $now = new DateTime();
        $archivo = $_FILES['archivo'];
        $evaluacion_complemento_id = filter_input(INPUT_POST, 'evaluacion_complementaria_id', FILTER_SANITIZE_NUMBER_INT);

        $anexo = array();
        $valid = true;

        if($archivo['error'] === UPLOAD_ERR_OK){
            if($archivo['size'] < 50000000 || true){
                $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$archivo['name'];
                $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                    $model = new gen_documentos();
                    $model->begin();
                    $model->ruta = $rel_path;
                    $model->nombre = $archivo['name'];
                    if($model->save()){
                        $model_anexos = new evaluacion_complemento_documentos();
                        $model_anexos->evaluacion_complemento_id = $evaluacion_complemento_id;
                        $model_anexos->gen_documentos_id = $model->id;
//                        $model_anexos->cod_lineamiento = $lineamiento_id;
                        if(!$model_anexos->save()){
                            $valid = false;
                        }
                    }
                    else{
                        $valid = false;
                    }
                    if($valid){
                        $model->commit();
                         echo json_encode(array(
                            'status' => 'ok',
                            'ruta' => $rel_path,
                            'nombre' => $archivo['name'],
                            'id' => $model->id
                            ));
                    }
                    else{
                       header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                       var_dump($model->error_sql());
                       echo json_encode(array('error' => 'No se pudo guardar el archivo1'));
                    }
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('error' => 'No se pudo guardar el archivo2'));
                 }
            }
            else{
                //el archivo no puede superar los 10 Mb
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'El archivo no puede superar los 10 Mb'));
            }
        }
        else{
           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
           echo json_encode(array('error' => 'No se pudo guardar el archivo3'));
        }
    }

	public function getAnexos(){
        header('Content-Type: application/json');
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
        $lineamiento_id = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $cod_momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $rol = Auth::info_usuario('rol');

//        var_dump($momento_evaluacion);

        if($rol == 1 || $momento['cod_momento'] == 2 ){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s",
					$cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }

        $sql_anexos = sprintf("SELECT d.fecha_creado, d.nombre, d.ruta, d.id, d.color FROM gen_documentos d
			INNER JOIN momento_resultado_anexo a ON (a.cod_documento = d.id)
			WHERE a.cod_momento_evaluacion = %s AND a.cod_lineamiento = %s
			ORDER BY d.fecha_creado DESC",
			$momento_evaluacion, $lineamiento_id);
        $anexos = BD::$db->queryAll($sql_anexos);

        echo json_encode($anexos);
    }

    public function guardar_url_anexo(){
        header('Content-Type: application/json');
        $cod_momento = filter_input(INPUT_POST, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $url = $_POST['url'];
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $lineamiento_id = filter_input(INPUT_POST, 'lineamiento_id', FILTER_SANITIZE_NUMBER_INT);
        $nuevo = filter_input(INPUT_POST, 'nuevo', FILTER_SANITIZE_NUMBER_INT);
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];

        $valid = true;

        $model = new gen_documentos();
        $model->begin();
        $model->ruta = $url;
        $model->nombre = $nombre;
        if($model->save()){
            $model_anexos = new momento_resultado_anexo();
            $model_anexos->cod_documento = $model->id;
            $model_anexos->cod_momento_evaluacion = $momento_evaluacion;
            $model_anexos->cod_lineamiento = $lineamiento_id;
            if($nuevo > 0){
                $model_anexos->nuevo = $nuevo;
            }
            if(!$model_anexos->save()){
                $valid = false;
                echo "no se pudo guardar1";
            }
        }
        else{
            $valid = false;
            echo "no se pudo guardar2";
        }
        if($valid){
            $model->commit();
            echo json_encode(array('status' => 'ok', 'ruta' => $model->ruta, 'nombre' => $model->nombre));
        }
        else{
            echo "no se pudo guardar";
        }
    }

    public function guardar_url_anexo_c(){
        header('Content-Type: application/json');
        $url = $_POST['url'];
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $evaluacion_complemento_id = filter_input(INPUT_POST, 'evaluacion_complementaria_id', FILTER_SANITIZE_STRING);


        $valid = true;

        $model = new gen_documentos();
        $model->begin();
        $model->ruta = $url;
        $model->nombre = $nombre;
        if($model->save()){
            $model_anexos = new evaluacion_complemento_documentos();
            $model_anexos->evaluacion_complemento_id = $evaluacion_complemento_id;
            $model_anexos->gen_documentos_id = $model->id;
            if(!$model_anexos->save()){
                $valid = false;
                echo "no se pudo guardar1";
            }
        }
        else{
            $valid = false;
            echo "no se pudo guardar2";
        }
        if($valid){
            $model->commit();
            echo json_encode(array(
                'status' => 'ok',
                'ruta' => $url,
                'nombre' => $nombre,
                'id' => $model->id
                ));
        }
        else{
            echo "no se pudo guardar";
        }
    }

    public function eliminarAnexo(){
        $documento_id = filter_input(INPUT_POST, 'documento_id', FILTER_SANITIZE_NUMBER_INT);
        $sql_eliminar = sprintf("DELETE FROM momento_resultado_anexo WHERE cod_documento = %s", $documento_id);
        $rs = BD::$db->query($sql_eliminar);
        if(!PEAR::isError($rs)){
            echo json_encode(array('status' => 'ok'));
        }
        else{
            echo json_encode(array('error' => 'No se pudo eliminar el anexo'));
        }
    }

     public function eliminarAnexoC(){
        header('Content-Type: application/json');
        $documento_id = filter_input(INPUT_POST, 'documento_id', FILTER_SANITIZE_NUMBER_INT);
        $sql_eliminar = sprintf("DELETE FROM evaluacion_complemento_documentos WHERE gen_documentos_id = %s", $documento_id);
        $rs = BD::$db->query($sql_eliminar);
        if(!PEAR::isError($rs)){
            echo json_encode(array('status' => 'ok'));
        }
        else{
            echo json_encode(array('error' => 'No se pudo eliminar el anexo'));
        }
    }

    public function upload_tabla_estadistica(){
        header('Content-Type: application/json');
        date_default_timezone_set('America/Bogota');
        $archivo = $_FILES['archivo'];
        $valid = true;
        $tabla = array();
        $now = new DateTime();
        //Verificar si el archivo ya existe

        $cod_momento = filter_input(INPUT_POST, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $rol = Auth::info_usuario('rol');
        $lineamiento_id = filter_input(INPUT_POST, 'rubro', FILTER_SANITIZE_NUMBER_INT);

         if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s",
					$cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        else{
            $momento = $this->get_momento_actual();
            $momento_evaluacion = $momento['cod_momento_evaluacion'];

        }


        $sql_tabla = sprintf("SELECT count(d.id) FROM gen_documentos d
			INNER JOIN lineamiento_archivo_tabla t ON t.cod_documento = d.id
			WHERE t.cod_momento_evaluacion = %s and t.cod_lineamiento = %s
			ORDER BY d.fecha_creado DESC",
			$momento_evaluacion, $lineamiento_id);
        $t = BD::$db->queryOne($sql_tabla);

        if($t > 0){
            //update
            $documento_id = filter_input(INPUT_POST, 'documento_id', FILTER_SANITIZE_NUMBER_INT);
            if($archivo['error'] === UPLOAD_ERR_OK){
                if($archivo['size'] < 10000000){
                    $rel_path = 'public/files/tablas_estadisticas/'.$now->getTimestamp().'-'.$archivo['name'];
                    $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                    if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                        $model = new gen_documentos();
                        $model->begin();
                        $model->ruta = $rel_path;
                        $model->nombre = $archivo['name'];
                        if($model->save()){
                            $model_tabla = new lineamiento_archivo_tabla();
                            $model_tabla->find((int)$documento_id);
                            $model_tabla->cod_documento = $model->id;
                           if(!$model_tabla->update()){
                                $valid = false;
                                echo $model_tabla->error_sql();
                            }
                            else{
                                $tabla['id'] = $model_tabla->id;
                            }
                        }
                        else{
                            $valid = false;
                        }
                        if($valid){
                            $model->commit();
                            echo json_encode($tabla);
                        }
                        else{
                           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                           echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                        }
                    }
                    else{
                       header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                       echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                    }
                }
                else{
                    //el archivo no puede superar los 10 Mb
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('error' => 'El archivo no puede superar los 10 Mb'));
                }
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'No se pudo guardar el archivo'));
             }
        }
        else{
            //insertar
            if($archivo['error'] === UPLOAD_ERR_OK){
                if($archivo['size'] < 10000000){
                    $rel_path = '/public/files/'.$now->getTimestamp().'-'.$archivo['name'];
                    $real_path = dirname(dirname(dirname(dirname(__FILE__)))).$rel_path;
                    if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                        $model = new gen_documentos();
                        $model->begin();
                        $model->ruta = $rel_path;
                        $model->nombre = $archivo['name'];
                        if($model->save()){
                            $model_tabla = new lineamiento_archivo_tabla();
                            $model_tabla->cod_documento = $model->id;
                            $model_tabla->cod_momento_evaluacion = $momento_evaluacion;
                            $model_tabla->cod_lineamiento = $lineamiento_id;
                            if(!$model_tabla->save()){
                               $valid = false;
                            }
                            else{
                                $tabla['id'] = $model_tabla->id;
                            }
                        }
                        else{
                            $valid = false;
                        }
                        if($valid){
                            $model->commit();
                            echo json_encode($tabla);
                        }
                        else{
                           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                           echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                        }
                    }
                    else{
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                        echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                     }
                }
                else{
                    //el archivo no puede superar los 10 Mb
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('error' => 'El archivo no puede superar los 10 Mb'));
                }
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'No se pudo guardar el archivo'));
             }
        }
    }



    public function get_momento_anterior($momento_actual){

        $sql_momento = "SELECT

        momento_evaluacion.cod_momento,

        momento_evaluacion.id,

        evaluacion.id as cod_evaluacion

        FROM

        momento_evaluacion

        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id

        where cod_momento = 1 and evaluacion.id = (select cod_evaluacion from momento_evaluacion where id=$momento_actual)";
        return BD::$db->queryRow($sql_momento);

    }

    public function getTablaLineamiento(){
        header('Content-Type: application/json');
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $cod_momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        $rol = Auth::info_usuario('rol');


        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("SELECT id FROM momento_evaluacion
					WHERE cod_momento = %s AND cod_evaluacion = %s",
					$cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        else{
            if($momento['cod_momento'] == 1)
	        $momento_evaluacion = $momento['cod_momento_evaluacion'];
            else{
                $momento_anterior = $this->get_momento_anterior($momento['cod_momento_evaluacion']);
                $momento_evaluacion = $momento_anterior['id'];
            }
        }
        $lineamiento_id = filter_input(INPUT_GET, 'rubro', FILTER_SANITIZE_NUMBER_INT);
        $sql_tabla = sprintf("SELECT d.fecha_creado, d.nombre, d.ruta, d.id, t.id AS archivo_id
			FROM gen_documentos d
			INNER JOIN lineamiento_archivo_tabla t ON (t.cod_documento = d.id)
			WHERE t.cod_momento_evaluacion = %s AND t.cod_lineamiento = %s
			ORDER BY d.fecha_creado DESC", $momento_evaluacion, $lineamiento_id);
//        var_dump($sql_tabla);
		$tabla = BD::$db->queryAll($sql_tabla);

        echo json_encode($tabla);
    }

    public function getPlantillaEstadisticas(){
        header('Content-Type: application/json');
        //viene de la sesion
        $momento = $this->get_momento_actual();
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
        $lineamiento_id = filter_input(INPUT_GET, 'rubro', FILTER_SANITIZE_NUMBER_INT);
        $sql_tabla = sprintf("SELECT ruta FROM tablas_estadisticas  WHERE rubro = %s", $lineamiento_id);
	    $tabla = BD::$db->queryAll($sql_tabla);

		echo json_encode($tabla);
    }

    public function getDatoRubro(){
        header('Content-Type: application/json');
        //viene de la sesion
        $rubro = filter_input(INPUT_GET, 'rubro', FILTER_SANITIZE_NUMBER_INT);
        $campo = filter_input(INPUT_GET, 'campo', FILTER_SANITIZE_STRING);
        $sql_dato = sprintf("SELECT $campo FROM lineamientos_datos_rubro WHERE cod_rubro = %s", $rubro);
        $dato = BD::$db->queryOne($sql_dato);

        echo json_encode(array('dato' => $dato));
    }

    public function getNombreLineamiento(){
        header('Content-Type: application/json');
        //viene de la sesion
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $sql_nombre = '';
        $cod_idioma = Auth::info_usuario('cod_idioma');
        switch($cod_idioma){
            case "4":
                $sql_nombre = sprintf("SELECT nom_lineamiento, num_orden 
                FROM lineamientos 
                WHERE id = (SELECT lineamientos_asociaciones.cod_lineamiento2 
                FROM sievas.lineamientos 
                inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
                inner join lineamientos_conjuntos_traducciones on lineamientos_conjuntos_traducciones.cod_conjunto = lineamientos_detalle_conjuntos.cod_conjunto
                inner join lineamientos_conjuntos_asociaciones on lineamientos_conjuntos_asociaciones.cod_conjunto2 = lineamientos_conjuntos_traducciones.cod_conjunto_traduccion
                inner join lineamientos_asociaciones on lineamientos_asociaciones.cod_asociacion_conjunto = lineamientos_conjuntos_asociaciones.id
                where lineamientos.id =%s and lineamientos_conjuntos_traducciones.gen_idiomas_id = 4 and lineamientos_asociaciones.cod_lineamiento1 = %s)", $lineamiento, $lineamiento);
                break;
            default:
                $sql_nombre = sprintf("SELECT nom_lineamiento, num_orden FROM lineamientos WHERE id = %s", $lineamiento);
                break;
        }
        $lineamiento = BD::$db->queryRow($sql_nombre);

		echo json_encode($lineamiento);
    }
    
    public function evaluacion_complementaria_nal(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_documentos = sprintf("select * from evaluacion_nal_documentos where evaluacion_id = %s", $evaluacion);
        $documentos = BD::$db->queryAll($sql_documentos);
        
        $vars['documentos'] = $documentos;
        $vars['tipos'] = array(
            1 => "Dictamen acreditación",
            2 => "Datos estadísticos",
            3 => "Documento autoevaluación",
            4 => "Otro",
        );
                
        
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_css('public/dropzone/dropzone.css');
        View::add_js('public/dropzone/dropzone.js');
        View::add_js('modules/sievas/scripts/evaluar/evaluacion_complementaria_nal.js');
        View::render('evaluar/evaluacion_complementaria_nal.php', $vars);
    }
    
    public function guardar_documento_nal(){
        header('Content-Type: application/json');
        $nombre_doc = filter_input(INPUT_POST, 'nombre_doc', FILTER_SANITIZE_STRING);
        $fecha_doc = filter_input(INPUT_POST, 'fecha_doc', FILTER_SANITIZE_STRING);
        $tipo_doc = filter_input(INPUT_POST, 'tipo_doc', FILTER_SANITIZE_NUMBER_INT); 
        $archivo = $_FILES['documento'];
        
        if($archivo['error'] === UPLOAD_ERR_OK){
                if($archivo['size'] < 10000000){
                    $rel_path = '/public/files/'.$archivo['name'];
                    $real_path = dirname(dirname(dirname(dirname(__FILE__)))).$rel_path;
                    if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                        $model = new gen_documentos();
                        $model->begin();
                        $model->ruta = $rel_path;
                        $model->nombre = $archivo['name'];
                        if($model->save()){
                            $model2 = new evaluacion_nal_documentos();
                            $model2->nombre_documento = $nombre_doc;
                            $model2->fecha_documento = $fecha_doc;
                            $model2->tipo_documento = $tipo_doc;
                            $model2->evaluacion_id = Auth::info_usuario('evaluacion');
                            $model2->cod_documento = $model->id;
                            if($model2->save()){
                                $model->commit();
                                echo json_encode(array('id' => $model2->id));
                            }
                            else{
                                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                                echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                            }
                        }
                        else{
                            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                            echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                        }
                    }
                    else{
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                        echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                    }
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                }
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo json_encode(array('error' => 'No se pudo guardar el archivo'));
        }
    }

    public function evaluacion_complementaria(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_check_nal = sprintf("select ev_nal from evaluacion where id=%s", $evaluacion);
        $check_nal = BD::$db->queryOne($sql_check_nal);
        if($check_nal > 0){
            header("Location: index.php?mod=sievas&controlador=evaluar&accion=evaluacion_complementaria_nal");
        }
        
          $momento = $_GET['cod_momento'];
        if($momento == null){
            $tmp = $this->get_momento_actual();
            $momento = $tmp['cod_momento'];
        }
       if(Auth::info_usuario('ev_tablero') > 0){
           switch($momento){
               case 1:
                   $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                        inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                        etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 1', Auth::info_usuario('evaluacion'));
                    $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
                    if($rs_tab_cond['escala_avance'] == null){
                        $rs_tab_cond['escala_avance'] = 0;
                    }
                    $sql_tab_cond_2 = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                        inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                        etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 2', Auth::info_usuario('evaluacion'));
                    $rs_tab_cond_2 = BD::$db->queryRow($sql_tab_cond_2);
                    if($rs_tab_cond_2['escala_avance'] == null){
                        $rs_tab_cond_2['escala_avance'] = 0;
                    }

                    if($rs_tab_cond['escala_avance'] != 10 ){
                        header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=1');
                    }
                    else{
                        if($rs_tab_cond_2['escala_avance'] == 10){
                            header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=2');
                        }
                    }
                   break;
               case 2:
                   $sql_tab_cond = sprintf('select etapas_proceso_avance.escala_avance from etapas_proceso_avance 
                    inner join etapas_proceso on etapas_proceso_avance.etapas_proceso_id = 
                    etapas_proceso.id where evaluacion_id=%s and etapas_proceso.num_proceso = 3', Auth::info_usuario('evaluacion'));
                     $rs_tab_cond = BD::$db->queryRow($sql_tab_cond);
                     if($rs_tab_cond['escala_avance'] == null){
                         $rs_tab_cond['escala_avance'] = 0;
                     }

                     if($rs_tab_cond['escala_avance'] != 10 ){
                         header('Location: index.php?mod=sievas&controlador=evaluar&accion=tablero_control&err=3');
                     }
                   break;
           }
          
       }
       
         $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];
        $momento = $this->get_momento_actual();
        if($momento == null){
            if($_GET['momento'] == null){
                $momento['cod_momento'] = 1;
            }
            else{
                $momento['cod_momento'] = $_GET['momento'];
            }

        }

        

        $sql_anexos = sprintf("SELECT
        gen_documentos.ruta,
        gen_documentos.nombre,
        gen_documentos.id
        FROM
        gen_documentos
        INNER JOIN evaluacion_complemento_documentos ON evaluacion_complemento_documentos.gen_documentos_id = gen_documentos.id
        INNER JOIN evaluacion_complemento ON evaluacion_complemento_documentos.evaluacion_complemento_id = evaluacion_complemento.id
        INNER JOIN evaluacion ON evaluacion_complemento.cod_evaluacion = evaluacion.id
        WHERE
        evaluacion.id = %s", $evaluacion);

        $anexos = BD::$db->queryAll($sql_anexos);

        $vars['anexos'] = $anexos;
//        var_dump($anexos);

        $sql_creador = sprintf('select cod_persona from sys_usuario where username="%s"', Auth::info_usuario('usuario'));
        $creador = BD::$db->queryOne($sql_creador);
        $sql_ev_comp = sprintf('select id,evaluacion from evaluacion_complemento where momento=%s and cod_evaluacion = %s and creador=%s',
        $momento['cod_momento'], $evaluacion, $creador);

        $ev_comp_def = BD::$db->queryRow($sql_ev_comp);
        $vars['ev_comp'] = $ev_comp_def;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ev_comp = $_POST['ev_complemento'];
            if($ev_comp_def['id'] > 0){
                $ev_complemento = new evaluacion_complemento();
                $ev_complemento->id = $ev_comp_def['id'];
                $ev_complemento->evaluacion = $ev_comp;
                $ev_complemento->update();
                $sql_ev_comp = sprintf('select id,evaluacion from evaluacion_complemento where momento=%s and cod_evaluacion = %s and creador=%s',
                $momento['cod_momento'], $evaluacion, $creador);

                $ev_comp_def = BD::$db->queryRow($sql_ev_comp);
                $vars['ev_comp'] = $ev_comp_def;
            }
            else{
                $ev_complemento = new evaluacion_complemento();
                $ev_complemento->evaluacion = $ev_comp;
                $ev_complemento->creador = $creador;
                $ev_complemento->momento = $momento['cod_momento'];
                $ev_complemento->cod_evaluacion = $evaluacion;
                $ev_complemento->save();
                $sql_ev_comp = sprintf('select id,evaluacion from evaluacion_complemento where momento=%s and cod_evaluacion = %s and creador=%s',
                $momento['cod_momento'], $evaluacion, $creador);

                $ev_comp_def = BD::$db->queryRow($sql_ev_comp);
                $vars['ev_comp'] = $ev_comp_def;
            }

        }

        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_css('public/css/sievas/styles.css');
        View::add_js('modules/sievas/scripts/evaluar/evaluacion_complementaria.js');
//        View::add_js('modules/sievas/scripts/evaluar.js');
        View::render('evaluar/evaluacion_complementaria.php', $vars);
    }

}
