<?php
Load::model2('sieva_lineamientos');
Load::model2('lineamientos');
Load::model2('lineamientos_conjuntos');
Load::model2('lineamientos_detalle_conjuntos');
Load::model2('tablas_estadisticas');
Load::model2('lineamientos_datos_rubro');
Load::model2('lineamientos_datos');
Load::model2('lineamientos_conjuntos_asociaciones');
Load::model2('lineamientos_asociaciones');
Load::model2('gen_documentos');
Load::model2('GeneralModel');

class lineamientosController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
       
    } 
    
    public function definir(){
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
       View::add_js('modules/sievas/scripts/lineamientos/main.js');  
       View::add_js('modules/sievas/scripts/lineamientos/definir.js');  
       View::render('lineamientos/definir.php', $vars);
    }
    
    public function conjuntos_lineamientos(){
       View::add_js('modules/sievas/scripts/lineamientos/lineamientos.js');  
       View::add_js('modules/sievas/scripts/lineamientos/conjuntos.js');  
       View::render('lineamientos/conjuntos.php');
    }
    
    
    public function eliminar_asociacion_lineamientos(){
        header('Content-Type: application/json');        
        $action = $_POST['action'];      
            if($action == 'remove_el'){
                $asociaciones = $_POST['asociaciones'];
                $asociaciones_helper = new lineamientos_asociaciones();
                if(count($asociaciones) > 0){
                $asociaciones = array_map(function($el){
                    return array(
                        'param' => 'id',
                        'value' => $el
                    );
                }, $asociaciones);
                
                foreach($asociaciones as $a){
                    $asociaciones_helper->helper_delete('lineamientos_asociaciones', array($a));
                }
                }
            }
            if($action == 'remove_all'){
                $conjunto1 = $_POST['conjunto1'];     
                $conjunto2 = $_POST['conjunto2'];    
                
                //encontrar conjunto de lineamientos
                $sql = sprintf("select id from lineamientos_conjuntos_asociaciones where cod_conjunto1 = %s and cod_conjunto2 = %s", 
                        $conjunto1, $conjunto2);
                $asociacion_conjunto = BD::$db->queryOne($sql);
                
                if($asociacion_conjunto > 0){
                    $asociaciones_helper = new lineamientos_asociaciones();
                    $asociaciones_helper->helper_delete('lineamientos_asociaciones', array(array(
                        'param' => 'cod_asociacion_conjunto',
                        'value' => $asociacion_conjunto
                    )));
                }
            }
            
        
        echo json_encode(array('status' => 'ok'));
    }
    
    public function guardar_asociacion_lineamientos(){
        header('Content-Type: application/json');
        //validacion datos
        $left = $_POST['left'];
        $right = $_POST['right'];
        $conjunto1 = $_POST['conjunto1'];
        $conjunto2 = $_POST['conjunto2'];
        $valid = true;
        $err = 0;
        if(!$left > 0){
            $valid = false;
            $err = 1;
        }
        if(!count($right) > 0){
            $valid = false;
            $err = 2;
        }        
        if(!$conjunto1 > 0){
            $valid = false;
            $err = 3;
        }
        if(!$conjunto2 > 0){
            $valid = false;
            $err = 4;
        }
        
        if($conjunto1 == $conjunto2){
             $valid = false;
             $err = 5;
        }
        
        if($valid){
            //verificar si existe asociacion entre conjuntos
            $sql_asociacion_conjuntos = sprintf("select id from lineamientos_conjuntos_asociaciones 
                where cod_conjunto1 = %s and cod_conjunto2 = %s", $conjunto1, $conjunto2);
            $asociacion_conjuntos = BD::$db->queryOne($sql_asociacion_conjuntos);
            //si no existe crear asociacion entre conjuntos
            if($asociacion_conjuntos == null){
                //crear asociacion
                $model = new lineamientos_conjuntos_asociaciones();
                $model->cod_conjunto1 = $conjunto1;
                $model->cod_conjunto2 = $conjunto2;
                $model->save();
                $asociacion_conjuntos = $model->id;                
            }
            //asociar items
            foreach($right as $item){
                //verificar si existe asociacion
                $sql_check_asociacion = sprintf("select id from lineamientos_asociaciones where
                    cod_asociacion_conjunto = %s and cod_lineamiento1 = %s and cod_lineamiento2 = %s",
                        $asociacion_conjuntos, $left, $item);
                $check_asociacion = BD::$db->queryOne($sql_check_asociacion);
                if($check_asociacion == null){
                     $model_tmp = new lineamientos_asociaciones();
                     $model_tmp->cod_asociacion_conjunto = $asociacion_conjuntos;
                     $model_tmp->cod_lineamiento1 = $left;
                     $model_tmp->cod_lineamiento2 = $item;  
                     $model_tmp->save();
                }
               
                
            }
            echo json_encode(array('error' => '-1'));
        }
        else{
            echo json_encode(array('error' => $err));
        }
    }
    
    public function cargar_asociaciones(){
        $conjunto1 = $_GET['conjunto1'];
        $conjunto2 = $_GET['conjunto2'];
        header('Content-Type: application/json');
        $sql_asoc = sprintf("select lin1.nom_lineamiento as nom1, lin2.nom_lineamiento as nom2, 
            lin1.atributos_lineamiento as p1, lin2.atributos_lineamiento as p2,
            lin1.num_orden as o1, lin2.num_orden as o2, lineamientos_asociaciones.id from lineamientos_asociaciones 
            inner join lineamientos as lin1 on lin1.id = lineamientos_asociaciones.cod_lineamiento1
            inner join lineamientos as lin2 on lin2.id = lineamientos_asociaciones.cod_lineamiento2
            inner join lineamientos_conjuntos_asociaciones on lineamientos_asociaciones.cod_asociacion_conjunto = lineamientos_conjuntos_asociaciones.id
            where lineamientos_conjuntos_asociaciones.cod_conjunto1 = %s and lineamientos_conjuntos_asociaciones.cod_conjunto2 = %s",
               $conjunto1, $conjunto2);       
//        var_dump($sql_asoc);
        $asoc = BD::$db->queryAll($sql_asoc);
        echo json_encode($asoc);
    }
    
     public function asociar_lineamientos(){
       
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
       View::add_js('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js'); 
       View::add_js('public/js/bootbox.min.js');
       View::add_js('modules/sievas/scripts/lineamientos/asociar_lineamiento.js');
       

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
           View::render('lineamientos/asociar_lineamientos.php', $vars);
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
          View::render('lineamientos/asociar_lineamientos.php', $vars);
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
    
    public function get_dt_conjuntos(){
        if(Auth::info_usuario('rol') == 6){
            $aColumns = explode(',',$_GET['sColumns']);
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
                                           ".$_GET['sSortDir_'.$i]  .", ";
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
                           $sWhere .= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%' OR ";
                   }
                   $sWhere = substr_replace( $sWhere, "", -3 );
                   $sWhere .= ')';
           }

           /* Individual column filtering */
           for ( $i=0 ; $i<count($aColumns) ; $i++ )
           {
                   if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
                   {
                           $sWhere .= " AND ";
                           $sWhere .= $aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
                   }
           }
	 
            
            
            $sQuery = sprintf("SELECT
            lineamientos_conjuntos.nom_conjunto,
            lineamientos_conjuntos.id
            FROM
            lineamientos_conjuntos
            INNER JOIN lineamientos_conjuntos_revisores ON lineamientos_conjuntos_revisores.cod_lineamiento_conjunto = lineamientos_conjuntos.id
            INNER JOIN gen_persona ON lineamientos_conjuntos_revisores.cod_revisor = gen_persona.id
            INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id where sys_usuario.username = '%s' ".$sWhere.$sOrder, Auth::info_usuario('usuario'));
            
            if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
            {
                BD::$db->setLimit($_GET['iDisplayLength'], $_GET['iDisplayStart']);
            }
          
            
            $rResult = BD::$db->query($sQuery);
        

            $aResultFilterTotal = $rResult->numRows();
            $iFilteredTotal = $aResultFilterTotal;
            
            $sQuery = sprintf("SELECT
            count(lineamientos_conjuntos.id)
            FROM
            lineamientos_conjuntos
            INNER JOIN lineamientos_conjuntos_revisores ON lineamientos_conjuntos_revisores.cod_lineamiento_conjunto = lineamientos_conjuntos.id
            INNER JOIN gen_persona ON lineamientos_conjuntos_revisores.cod_revisor = gen_persona.id
            INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id where sys_usuario.username = '%s' ".$sWhere.$sOrder, Auth::info_usuario('usuario'));
            
            $rResultTotal = BD::$db->query($sQuery);
            $aResultTotal = $rResultTotal->fetchCol();

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
                            else if ( $aColumns[$i] != ' ' && $aColumns[$i] != null)
                            {
                                    /* General output */
                                    $row[] = $aRow[ $aColumns[$i] ];
                            }

                    }
                    $output['aaData'][] = $row;
            }


            echo json_encode( $output );
        }
        else{
           $model = new GeneralModel();
           $model->listar(); 
        }
        
    }
    
    public function add_conjunto(){
       View::add_js('modules/sievas/scripts/lineamientos/lineamientos.js');  
       View::add_js('modules/sievas/scripts/lineamientos/add_conjunto.js'); 
       View::render('lineamientos/add.php');
    }
    
    public function editar_conjunto(){
       $conjunto = $_GET['id'];
       $sql_check = sprintf('select count(*) from evaluacion where cod_conjunto=%s', $conjunto);
       $check = BD::$db->queryOne($sql_check);
       if(!$check > 0){
           $sql_conjunto = sprintf('select nom_conjunto from lineamientos_conjuntos where id=%s',$conjunto);
           $nom_conjunto = BD::$db->queryOne($sql_conjunto);
           $vars['conjunto'] = $conjunto;
           $vars['nom_conjunto'] = $nom_conjunto;
       }       
       View::add_js('modules/sievas/scripts/lineamientos/lineamientos.js');  
       View::add_js('modules/sievas/scripts/lineamientos/add_conjunto.js'); 
       View::render('lineamientos/edit.php', $vars);
    }
    
    public function editar_lineamiento(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $nom_lineamiento = filter_input(INPUT_POST, 'nom_lineamiento', FILTER_SANITIZE_STRING);
        $padre = filter_input(INPUT_POST, 'padre', FILTER_SANITIZE_NUMBER_INT);
        $conjunto = filter_input(INPUT_POST, 'conjunto', FILTER_SANITIZE_NUMBER_INT);
        
        if($padre > 0){
            $sql_data_lineamiento = sprintf("select * from lineamientos where id = %s", $id);
            $data_lineamiento = BD::$db->queryRow($sql_data_lineamiento);
            
            if($data_lineamiento['padre_lineamiento'] === $padre){
                $sql_orden_padre = sprintf('select num_orden from lineamientos where id=%s', $padre);
                $orden_padre = BD::$db->queryOne($sql_orden_padre);


                $sql = sprintf("update lineamientos set nom_lineamiento = '%s', padre_lineamiento = %s, num_orden='%s', atributos_lineamiento='%s' where id=%s", $nom_lineamiento, $padre, $data_lineamiento['num_orden'], $orden_padre.'.',$id);
                $rs = BD::$db->query($sql);
                if(PEAR::isError($rs)){
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo editar el lineamiento";
                }
                else{
                    echo json_encode(array('mensaje' => 'El lineamiento fue actualizado correctamente'));
                }
            }
            else{
                $sql_lineamientos = sprintf('SELECT
                Count(lineamientos.id) as total
                FROM
                lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                where lineamientos_conjuntos.id = %s and lineamientos.padre_lineamiento = %s', $conjunto, $padre);

                $total = BD::$db->queryOne($sql_lineamientos);

                $sql_orden_padre = sprintf('select num_orden from lineamientos where id=%s', $padre);
                $orden_padre = BD::$db->queryOne($sql_orden_padre);


                $sql = sprintf("update lineamientos set nom_lineamiento = '%s', padre_lineamiento = %s, num_orden='%s', atributos_lineamiento='%s' where id=%s", $nom_lineamiento, $padre, $total+1, $orden_padre.'.',$id);
                $rs = BD::$db->query($sql);
                if(PEAR::isError($rs)){
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo editar el lineamiento";
                }
                else{
                    echo json_encode(array('mensaje' => 'El lineamiento fue actualizado correctamente'));
                }
            }
            
        }
        else{
            $sql_data_lineamiento = sprintf("select * from lineamientos where id = %s", $id);
            $data_lineamiento = BD::$db->queryRow($sql_data_lineamiento);            
            
        
            $total = BD::$db->queryOne($sql_lineamientos);

            if($data_lineamiento['padre_lineamiento'] === $padre){
                $sql = sprintf("update lineamientos set nom_lineamiento = '%s', padre_lineamiento = %s, num_orden='%s', atributos_lineamiento='%s' where id=%s", $nom_lineamiento, 0, $data_lineamiento['num_orden'], '',$id);
                $rs = BD::$db->query($sql);
                if(PEAR::isError($rs)){
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo editar el lineamiento";
                }
                else{
                    echo json_encode(array('mensaje' => 'El lineamiento fue actualizado correctamente'));
                }
            }
            else{
                
                $sql_lineamientos = sprintf('SELECT
                Count(lineamientos.id) as total
                FROM
                lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                where lineamientos_conjuntos.id = %s and lineamientos.padre_lineamiento = %s', $conjunto, 0);
                
                
                $sql = sprintf("update lineamientos set nom_lineamiento = '%s', padre_lineamiento = %s, num_orden='%s', atributos_lineamiento='%s' where id=%s", $nom_lineamiento, 0, $total+1, '',$id);
                $rs = BD::$db->query($sql);
                if(PEAR::isError($rs)){
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo editar el lineamiento";
                }
                else{
                    echo json_encode(array('mensaje' => 'El lineamiento fue actualizado correctamente'));
                }
            }
            
        }
    }
    
    public function get_datos_complementarios(){
        header('Content-Type: application/json');
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $tipo_lineamiento = filter_input(INPUT_GET, 'tipo_lineamiento', FILTER_SANITIZE_STRING);
        switch($tipo_lineamiento){
            case 'rubro':
                // significado, contexto, referencia, glosario
                $sql_datos_rubro = sprintf('select * from lineamientos_datos_rubro where cod_rubro=%s', $lineamiento);
                $datos_rubro = BD::$db->queryRow($sql_datos_rubro);
  
                if($datos_rubro['documento_contexto'] > 0){
                    $sql = sprintf("select ruta from gen_documentos where id = %s", $datos_rubro['documento_contexto']);
                    $documento_contexto = BD::$db->queryOne($sql);
                    $datos_rubro['documento_contexto'] = $documento_contexto;
                }
                if($datos_rubro['documento_glosario'] > 0){
                    $sql = sprintf("select ruta from gen_documentos where id = %s", $datos_rubro['documento_glosario']);
                    $documento_glosario = BD::$db->queryOne($sql);
                    $datos_rubro['documento_glosario'] = $documento_glosario;
                }
                if($datos_rubro['documento_referencias'] > 0){
                    $sql = sprintf("select ruta from gen_documentos where id = %s", $datos_rubro['documento_referencias']);
                    $documento_referencias = BD::$db->queryOne($sql);
                    $datos_rubro['documento_referencias'] = $documento_referencias;
                }
                
                $sql_tabla_estadistica = sprintf('select ruta from tablas_estadisticas where rubro = %s', $lineamiento);
                $tabla_estadistica = BD::$db->queryOne($sql_tabla_estadistica);

                $datos_rubro['tabla_estadistica'] = $tabla_estadistica;                
                echo json_encode($datos_rubro);
                break;
            case 'item':
                $sql_datos_item = sprintf('select * from lineamientos_datos where cod_lineamiento = %s', $lineamiento);
                $datos_item = BD::$db->queryRow($sql_datos_item);
                echo json_encode($datos_item);
                break;
        }
    }
    
    public function editar_datos_complementarios(){
        $conjunto = $_GET['id'];
        $vars['conjunto'] = $conjunto;
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_css('public/summernote/tooltip_fix.css');
        View::add_js('public/summernote/helper.js');
        View::add_js('modules/sievas/scripts/lineamientos/lineamientos.js');  
        View::add_js('modules/sievas/scripts/lineamientos/complementarios.js'); 
        View::render('lineamientos/complementarios.php', $vars);
    }
    
    public function editar_complementarios_lineamiento(){
        header('Content-Type: application/json');
        date_default_timezone_set('America/Bogota');
        $now = new DateTime();
        $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $tipo_lineamiento = filter_input(INPUT_POST, 'tipo_lineamiento', FILTER_SANITIZE_STRING);
        
        switch($tipo_lineamiento){
            case 'rubro':
                $sql_check = sprintf('select id from lineamientos_datos_rubro where cod_rubro = %s', $lineamiento);
                $check = BD::$db->queryOne($sql_check);
                
                $significado = $_POST['significado'];
                $contexto = $_POST['contexto'];
                $referencias = $_POST['referencias'];
                $glosario = $_POST['glosario'];
                $documento_contexto = $_FILES['documento_contexto'];      
                $documento_glosario = $_FILES['documento_glosario'];    
                $documento_referencias = $_FILES['documento_referencias'];
                $tabla_estadistica = $_FILES['tabla_estadistica'];   
                
                $model_lineamientos_datos_rubro = new lineamientos_datos_rubro(); 
                    $model_lineamientos_datos_rubro->cod_rubro = $lineamiento;
                
                    if($significado != '')
                    $model_lineamientos_datos_rubro->significado = $significado;
                    if($contexto != '')
                    $model_lineamientos_datos_rubro->contexto = $contexto;
                    if($glosario != '')
                    $model_lineamientos_datos_rubro->glosario = $glosario;
                    if($referencias != '')
                    $model_lineamientos_datos_rubro->referencia = $referencias;
                    if($documento_contexto['error'] == UPLOAD_ERR_OK){
                        if($documento_contexto['size'] < 10000000){
                            $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$documento_contexto['name'];
                            $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                            if(move_uploaded_file($documento_contexto['tmp_name'], $real_path)){
                                $model = new gen_documentos();
                                $model->ruta = $rel_path;
                                $model->nombre = $documento_contexto['name'];
                                if($model->save()){
                                    $model_lineamientos_datos_rubro->documento_contexto = $model->id;
                                }
                            }
                        }                    
                    }
                    if($documento_glosario['error'] == UPLOAD_ERR_OK){
                        if($documento_glosario['size'] < 10000000){
                            $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$documento_glosario['name'];
                            $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                            if(move_uploaded_file($documento_glosario['tmp_name'], $real_path)){
                                $model = new gen_documentos();
                                $model->ruta = $rel_path;
                                $model->nombre = $documento_glosario['name'];
                                if($model->save()){
                                    $model_lineamientos_datos_rubro->documento_glosario = $model->id;
                                }
                            }
                        }                    
                    }
                    if($documento_referencias['error'] == UPLOAD_ERR_OK){
                        if($documento_referencias['size'] < 10000000){
                            $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$documento_referencias['name'];
                            $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                            if(move_uploaded_file($documento_referencias['tmp_name'], $real_path)){
                                $model = new gen_documentos();
                                $model->ruta = $rel_path;
                                $model->nombre = $documento_referencias['name'];
                                if($model->save()){
                                    $model_lineamientos_datos_rubro->documento_referencias = $model->id;
                                }
                            }
                        }                    
                    }
                
                if($check > 0){
                    $model_lineamientos_datos_rubro->id = $check;                    
                    if(!$model_lineamientos_datos_rubro->update()){
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    }   
                    else{
                        echo json_encode(array('mensaje' => 'Se actualizaron los datos correctamente'));
                    }                    
                   
                }  
                else{
                    if(!$model_lineamientos_datos_rubro->save()){
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                        echo "No se pudo actualizar la información";
                    }   
                    else{
                        echo json_encode(array('mensaje' => 'Se actualizaron los datos correctamente'));
                    }    
                }
                
                if($tabla_estadistica['error'] == UPLOAD_ERR_OK){
                        if($tabla_estadistica['size'] < 10000000){
                            $rel_path = 'public/files/documentos/'.$now->getTimestamp().'-'.$tabla_estadistica['name'];
                            $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                            if(move_uploaded_file($tabla_estadistica['tmp_name'], $real_path)){
                                $sql_tabla_check = sprintf('select id from tabla_estadistica where rubro=%s',$lineamiento);
                                $tabla_check = BD::$db->query($sql_tabla_check);
                                if($tabla_check > 0){
                                    $model = new tablas_estadisticas();
                                    $model->ruta = $rel_path;
                                    $model->rubro = $lineamiento;
                                    $model->save();
                                }
                                else{
                                    $model = new tablas_estadisticas();
                                    $model->ruta = $rel_path;
                                    $model->rubro = $lineamiento;
                                    $model->update();
                                }
                                
                            }
                        }                    
                    }
                break;
            case 'item':
                $sql_check = sprintf('select id from lineamientos_datos where cod_lineamiento = %s', $lineamiento);

                $check = BD::$db->queryOne($sql_check);
                $indicadores = filter_input(INPUT_POST, 'indicadores', FILTER_SANITIZE_STRING);
                $documentos = filter_input(INPUT_POST, 'documentos', FILTER_SANITIZE_STRING);
                
                $model_lineamientos_datos_item = new lineamientos_datos();
                $model_lineamientos_datos_item->indicadores = $indicadores;
                $model_lineamientos_datos_item->documentos = $documentos;
                $model_lineamientos_datos_item->cod_lineamiento = $lineamiento;
                if($check > 0){
                    $model_lineamientos_datos_item->id = $check;
                    if($model_lineamientos_datos_item->update()){
                        echo json_encode(array('mensaje' => 'Se actualizaron los datos correctamente'));
                    }
                    else{
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                        echo "No se pudo actualizar la información";
                        echo $model_lineamientos_datos_item->error_sql();
                    }
                }
                else{
                    if($model_lineamientos_datos_item->save()){
                        echo json_encode(array('mensaje' => 'Se actualizaron los datos correctamente'));
                    }
                    else{
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                        echo "No se pudo actualizar la información";
                    }
                }                
                break;
        }
        
    }
    
    public function copiar_conjunto(){
       $conjunto = $_GET['id'];
       $padres = array();
       
       $sql_conjunto = sprintf('select * from lineamientos_conjuntos where id=%s', $conjunto);
       $rs_conjunto = BD::$db->queryRow($sql_conjunto);       
       
       $conjunto_copia = new lineamientos_conjuntos();
       $conjunto_copia->nom_conjunto = 'Copia de '.$rs_conjunto['nom_conjunto'];
       $conjunto_copia->cod_nivel = 2;
       $conjunto_copia->activo = 'SI';
       if($conjunto_copia->save()){
           $sql_lineamientos_conjunto = sprintf('select * from lineamientos inner join lineamientos_detalle_conjuntos on lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento where lineamientos_detalle_conjuntos.cod_conjunto = %s order by lineamientos.id', $conjunto);
           $lineamientos_conjunto = BD::$db->queryAll($sql_lineamientos_conjunto);
 
           
           foreach($lineamientos_conjunto as $l){
               $lineamiento = new lineamientos();
               $lineamiento->nom_lineamiento = $l['nom_lineamiento'];
               $lineamiento->padre_lineamiento = $l['padre_lineamiento'];
               $lineamiento->num_orden = $l['num_orden'];
               $lineamiento->tipo_lineamiento = $l['tipo_lineamiento'];
               $lineamiento->atributos_lineamiento = $l['atributos_lineamiento'];
               $lineamiento->save();
               
               $lineamiento_detalle = new lineamientos_detalle_conjuntos();
               $lineamiento_detalle->cod_conjunto = $conjunto_copia->id;
               $lineamiento_detalle->cod_lineamiento = $lineamiento->id;
               $lineamiento_detalle->save();
               if($l['padre_lineamiento'] == 0){
                   $padres[] = array('padre_viejo' => $l['id'], 'padre_nuevo' => $lineamiento->id);
               }               
               
           }    
           
           foreach($padres as $p){
               $update_lineamiento = sprintf('update lineamientos inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id set padre_lineamiento = %s where lineamientos_detalle_conjuntos.cod_conjunto = %s and lineamientos.padre_lineamiento = %s',
                       $p['padre_nuevo'], $conjunto_copia->id, $p['padre_viejo']);
               $rs = BD::$db->query($update_lineamiento);
           }
       }
       
       
       $vars['conjunto'] = $conjunto_copia->id;      
       $vars['nom_conjunto'] = $rs_conjunto['nom_conjunto'];      
       View::add_js('modules/sievas/scripts/lineamientos/lineamientos.js');  
       View::add_js('modules/sievas/scripts/lineamientos/add_conjunto.js'); 
       View::render('lineamientos/add.php', $vars);
    }
    
    public function eliminar_lineamiento(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from lineamientos where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el ineamiento";
            }
            else{
                echo json_encode(array('mensaje' => 'El lineamiento fue eliminado correctamente'));
            }
        }
    }
    
    public function eliminar_conjunto_lineamientos(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from lineamientos_conjuntos where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el conjunto de lineamientos";
            }
            else{
                echo json_encode(array('mensaje' => 'El conjunto de lineamientos fue eliminado correctamente'));
            }
        }
    }
    
    public function guardar_lineamiento(){
        header('Content-Type: application/json');
        $lineamiento = filter_input(INPUT_POST, 'lineamiento', FILTER_SANITIZE_STRING);
        $padre = filter_input(INPUT_POST, 'padre', FILTER_SANITIZE_NUMBER_INT);
        $conjunto = filter_input(INPUT_POST, 'conjunto', FILTER_SANITIZE_NUMBER_INT);
        $nom_conjunto = filter_input(INPUT_POST, 'nom_conjunto', FILTER_SANITIZE_STRING);
        
        $valid = true;
        
        if(!($conjunto > 0)){
            $model_conjunto = new lineamientos_conjuntos();
            $model_conjunto->nom_conjunto = $nom_conjunto;
            $model_conjunto->cod_nivel = 2;
            $model_conjunto->activo = 'SI';
            if($model_conjunto->save()){
                $conjunto = $model_conjunto->id;
            }
            else{
                $valid = false;
            }
        }        
        if($padre > 0){
            $sql_lineamientos = sprintf('SELECT
            Count(lineamientos.id) as total
            FROM
            lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            where lineamientos_conjuntos.id = %s and lineamientos.padre_lineamiento = %s', $conjunto, $padre);
            
            $total = BD::$db->queryOne($sql_lineamientos);
            
            if($total < 30){
                $model_lineamientos = new lineamientos();
                $model_lineamientos->begin();
                $model_lineamientos->nom_lineamiento = $lineamiento;
                $model_lineamientos->padre_lineamiento = $padre;
                $model_lineamientos->tipo_lineamiento = 1;

                $sql_orden_padre = sprintf('select num_orden from lineamientos where id=%s', $padre);
                $num_orden = BD::$db->queryOne($sql_orden_padre);

                $model_lineamientos->atributos_lineamiento = $num_orden.'.';
                $model_lineamientos->num_orden = $total+1;
                if($model_lineamientos->save()){
                    $model_lineamiento_detalle = new lineamientos_detalle_conjuntos();
                    $model_lineamiento_detalle->cod_conjunto = $conjunto;
                    $model_lineamiento_detalle->cod_lineamiento = $model_lineamientos->id;
                    if(!$model_lineamiento_detalle->save()){
                        $valid = false;
                    }
                    else{
                        $model_lineamientos->commit();
                    }
                }
                else{
                    $valid = false;
                }
                
                 if($valid){
                    echo json_encode(array('mensaje' => 'ok', 'conjunto' => $conjunto));
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('mensaje' => 'No se pudo guardar el lineamiento', 'conjunto' => $conjunto));
                }
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('mensaje' => 'Máximo 30 Ítems por rubro', 'conjunto' => $conjunto));
            }
        }
        else{
            $sql_lineamientos = sprintf('SELECT
            Count(lineamientos.id) as total
            FROM
            lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            where lineamientos_conjuntos.id = %s and lineamientos.padre_lineamiento = %s', $conjunto, 0);
            
            $total = BD::$db->queryOne($sql_lineamientos);
            if($total < 15){
            $model_lineamientos = new lineamientos();
            $model_lineamientos->begin();
            $model_lineamientos->nom_lineamiento = $lineamiento;
            $model_lineamientos->padre_lineamiento = 0;
            $model_lineamientos->tipo_lineamiento = 1;
            $model_lineamientos->num_orden = $total+1;
            if($model_lineamientos->save()){
                $model_lineamiento_detalle = new lineamientos_detalle_conjuntos();
                $model_lineamiento_detalle->cod_conjunto = $conjunto;
                $model_lineamiento_detalle->cod_lineamiento = $model_lineamientos->id;
                if(!$model_lineamiento_detalle->save()){
                    $valid = false;
                }
                else{
                    $model_lineamientos->commit();
                }
            }
            else{
                $valid = false;
            }
            
             if($valid){
                echo json_encode(array('mensaje' => 'ok', 'conjunto' => $conjunto));
             }
             else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('mensaje' => 'No se pudo guardar el lineamiento', 'conjunto' => $conjunto));
                }
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('mensaje' => 'Máximo 15 rubros', 'conjunto' => $conjunto));
            }
        }

    }    
    
    public function guardar_conjunto(){
        header('Content-Type: application/json');
        $conjunto = filter_input(INPUT_POST, 'conjunto', FILTER_SANITIZE_NUMBER_INT);
        $nom_conjunto = filter_input(INPUT_POST, 'nom_conjunto', FILTER_SANITIZE_STRING);

        if(!($conjunto > 0)){
            $model_conjunto = new lineamientos_conjuntos();
            $model_conjunto->nom_conjunto = $nom_conjunto;
            $model_conjunto->cod_nivel = 2;
            $model_conjunto->activo = 'SI';
            if($model_conjunto->save()){
                $conjunto = $model_conjunto->id;
                echo json_encode(array('mensaje' => 'ok'));
            }
            else{
               header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
               echo "No se pudo guardar el conjunto de lineamientos";
            }
            
        }
        else{
           $sql_update = sprintf("update lineamientos_conjuntos set nom_conjunto='%s' where id=%s", $nom_conjunto, $conjunto);
           $rs = BD::$db->query($sql_update);
           if(PEAR::isError($rs)){
               header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
               echo "No se pudo actualizar el conjunto de lineamientos";
               echo $sql_update;
           }
           else{
               echo json_encode(array('mensaje' => 'ok'));
           }
        }
    }
    
    public function cargar_arbol_conjunto(){
        header('Content-Type: application/json');
        $arbol  = array();
        $arbol[]    = $this->crearnodo(0,'#', 'Rubros');
        $conjunto = filter_input(INPUT_GET, 'conjunto', FILTER_SANITIZE_NUMBER_INT);
//        $consulta   = 'SELECT
//        lineamientos.id,
//        lineamientos.nom_lineamiento,
//        lineamientos.num_orden,
//        lineamientos.atributos_lineamiento,
//        lineamientos.padre_lineamiento
//        FROM
//        lineamientos
//        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
//        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
//        INNER JOIN evaluacion ON lineamientos_conjuntos.id = evaluacion.cod_conjunto
//        order by num_orden';
        
        if($conjunto > 0){
            $consulta = sprintf('SELECT
            lineamientos.id,
            lineamientos.nom_lineamiento,
            lineamientos.num_orden,
            lineamientos.atributos_lineamiento,
            lineamientos.padre_lineamiento
            FROM
            lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            where lineamientos_conjuntos.id = %s
            order by num_orden',$conjunto);

            $resultado  = BD::$db->query($consulta);
            if (PEAR::isError($resultado)) {                 
                header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
                die($resultado->getMessage().' '.$consulta);
            }
            while ($unidad = $resultado->fetchRow()) {
                $arbol[]   = $this->crearnodo($unidad['id'],
                                    ($unidad['padre_lineamiento'] === null ? 0 : $unidad['padre_lineamiento']), 
                                    $unidad['atributos_lineamiento']."".$unidad['num_orden'].". ".$unidad['nom_lineamiento']);
            }
        }
        echo json_encode($arbol);
    }
    
    
    public function cargar_arbol_evaluacion(){
        header('Content-Type: application/json');
        $arbol  = array();
        $arbol[]    = $this->crearnodo(0,'#', 'Rubros');
        $cod_idioma = Auth::info_usuario('cod_idioma');
        $consulta = '';
        $conjunto = filter_input(INPUT_GET, 'conjunto', FILTER_SANITIZE_NUMBER_INT);
        
        
        if($conjunto > 0){
            $consulta   = sprintf('SELECT
            lineamientos.id,
            lineamientos.nom_lineamiento,
            lineamientos.num_orden,
            lineamientos.atributos_lineamiento,
            lineamientos.padre_lineamiento,
            lineamientos.padre_indicador
            FROM
            lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            where lineamientos_conjuntos.id = %s
            order by num_orden', $conjunto);
        }
        else{
        $consulta = '';    
        $evaluacion = Auth::info_usuario('evaluacion');
        switch($cod_idioma){
            case "4":
                $consulta = sprintf('SELECT 
                    lineamientos.id,
                    tr.nom_lineamiento,
                    lineamientos.padre_lineamiento,
                    lineamientos.num_orden,
                    lineamientos.atributos_lineamiento,
                    lineamientos.padre_indicador,
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
                        evaluacion.id = %s
                        and lineamientos_conjuntos_traducciones.gen_idiomas_id = 4
                 ) as tr on tr.cod_lineamiento1 = lineamientos.id
                WHERE
                        evaluacion.id = %s
                        order by num_orden
                ', $evaluacion, $evaluacion); 
                
//                var_dump($sql_rubros);
                break;
            default:
                $consulta   = sprintf('SELECT
                lineamientos.id,
                lineamientos.nom_lineamiento,
                lineamientos.num_orden,
                lineamientos.atributos_lineamiento,
                lineamientos.padre_lineamiento,
                lineamientos.padre_indicador
                FROM
                lineamientos
                INNER JOIN lineamientos_detalle_conjuntos ON lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
                INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                INNER JOIN evaluacion ON lineamientos_conjuntos.id = evaluacion.cod_conjunto
                where evaluacion.id = %s
                order by num_orden', Auth::info_usuario('evaluacion'));  
                break;
        }
            
            
            
        }
        

            $resultado  = BD::$db->query($consulta);
            if (PEAR::isError($resultado)) {                 
                header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
                die($resultado->getMessage().' '.$consulta);
            }
            while ($unidad = $resultado->fetchRow()) {
                $caracteristica = '';
                
                if($unidad['padre_indicador'] > 0){
                    $sql_caracteristica = sprintf("select nom_lineamiento from lineamientos where id=%s", $unidad['padre_indicador']);
                    $caracteristica = BD::$db->queryOne($sql_caracteristica);
                }
                $arbol[]   = $this->crearnodo($unidad['id'],
                                    ($unidad['padre_lineamiento'] === null ? 0 : $unidad['padre_lineamiento']), 
                                    $unidad['atributos_lineamiento']."".$unidad['num_orden'].". ".$unidad['nom_lineamiento'], 
                        array(
                            'caracteristica' => $caracteristica
                        ));
            }

        echo json_encode($arbol);
    }
    
    public function get_documentos_lineamiento(){
        $lineamiento = filter_input(INPUT_GET, 'lineamiento', FILTER_SANITIZE_NUMBER_INT);
        $documentos = array();
        if($lineamiento > 0){
            $sql = sprintf('select documentos from lineamientos_datos where cod_lineamiento = %s', $lineamiento);
            $documentos = BD::$db->queryRow($sql);
        }
        echo json_encode($documentos);
        
    }
    
    
    private function crearnodo($id, $padre_id, $text, $data = array()){
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
        $nodo['a_attr'] = $data;  
        return $nodo;
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
    
   