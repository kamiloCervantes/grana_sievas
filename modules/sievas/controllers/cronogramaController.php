<?php


Load::model2('sieva_lineamientos');
Load::model2('cronograma');
Load::model2('crono_responsables');
Load::model2('crono_asamblea');
Load::model2('cronograma_evaluadores');


class cronogramaController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
       //echo "sieva_evaluador";  
        View::render('evaluador/index.php'); 
    }  
    
//    public function guardar_cronograma(){
//        echo "Hola";
//    }
    
    
    /*
     * 
[
    {
        "fecha_inicio": "2014-11-11",
        "fecha_fin": "2014-11-11",
        "actividad_gen": "1",
        "etapa": "1",
        "medio": "2",
        "anotaciones": "Reuni%C3%B3n",
        "responsables": [
            3
        ],
        "invitados": [
            {
                "nombre": "Jesus Medina",
                "email": "djerus.jerus@gmail.com"
            }
        ],
        "itinerario": []
    }
]
     */
    
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
    
    
    public function guardar_cronograma_comite(){
        header('Content-Type: application/json');       
        $actividades = json_decode($_POST['actividades']);
        $momento_actual = $this->get_momento_actual();
        
        
        $sql = sprintf("select cod_persona from sys_usuario where username = '%s'", Auth::info_usuario('usuario')); 
        $cod_creador = BD::$db->queryOne($sql);
        
        $sql_orden = sprintf("select count(*) from cronograma_evaluadores where cod_momento_evaluacion = %s", $momento_actual['momento_id']);
        $orden = BD::$db->queryOne($sql_orden);
        
        $valid = true;
        $helper = new cronograma();
        $helper->begin();
        foreach($actividades as $a){
            $model_cronograma = new cronograma_evaluadores();
            $model_cronograma->fecha_inicia = $a->fecha_inicio;
            $model_cronograma->fecha_fin = $a->fecha_fin;
            $model_cronograma->anotaciones = $a->anotaciones;
            $model_cronograma->cod_actividad = $a->actividad_gen;
            $model_cronograma->cod_momento_evaluacion = $momento_actual['momento_id'];
            $model_cronograma->cod_medio = $a->medio;
            $model_cronograma->cod_etapa = $a->etapa;
            $model_cronograma->cod_creador = $cod_creador;
            $model_cronograma->orden = $orden+1;
            if(!$model_cronograma->save()){
                $valid = false;                
            }
        }
        if($valid){
            $helper->commit();
            echo json_encode(array(
                'mensaje' => 'El cronograma fue actualizado correctamente',
                'id' => $model_cronograma->id
                ));
        }
        else{
            $helper->rollback();
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se pudo actualizar el cronograma";
            
        }
        
      
    }
    
    public function guardar_cronograma(){
        header('Content-Type: application/json');       
        $actividades = json_decode($_POST['actividades']);
        $sql_check = 'select count(*) from cronograma where cod_evaluacion = '.Auth::info_usuario('evaluacion');
        $check = BD::$db->queryOne($sql_check);
        
        if($check > 0){
            $sql_delete = 'delete from cronograma where cod_evaluacion = '.Auth::info_usuario('evaluacion');
            $rs = BD::$db->query($sql_delete);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo actualizar el cronograma";
            }
            else{
                $valid = true;
                $helper = new cronograma();
                $helper->begin();
                foreach($actividades as $a){
                    $model_cronograma = new cronograma();
                    $model_cronograma->fecha_inicia = $a->fecha_inicio;
                    $model_cronograma->fecha_fin = $a->fecha_fin;
                    $model_cronograma->anotaciones = $a->anotaciones;
                    $model_cronograma->cod_actividad = $a->actividad_gen;
                    $model_cronograma->cod_evaluacion = Auth::info_usuario('evaluacion');
                    $model_cronograma->cod_medio = $a->medio;
                    $model_cronograma->cod_etapa = $a->etapa;
                    $model_cronograma->usuario = Auth::info_usuario('usuario');
                    if($model_cronograma->save()){
                        $v = true;
                        foreach($a->responsables as $r){                            
                            $sql_usuario = "SELECT
                            sys_usuario.username
                            FROM
                            gen_persona
                            INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id
                            where gen_persona.id = $r";

                            $usuario = BD::$db->queryOne($sql_usuario);

                            $model_crono_responsable = new crono_responsables();
                            $model_crono_responsable->usuario = $usuario;
                            $model_crono_responsable->cod_cronograma = $model_cronograma->id;
                            if(!$model_crono_responsable->save()){
                                $v = false;
                                echo $model_crono_responsable->error_sql(); 
                            }
                        }
                        foreach($a->invitados as $invitado){                    
                            $model_crono_asamblea = new crono_asamblea();
                            $model_crono_asamblea->nombres = $invitado->nombre;
                            $model_crono_asamblea->email = $invitado->email;
                            $model_crono_asamblea->cod_cronograma = $model_cronograma->id;
                            if(!$model_crono_asamblea->save()){
                                $v = false;
                            }
                        }
                        
                        foreach($a->itinerario as $it){      
                            $model_cronograma_itinerario = new cronograma();
                            $model_cronograma_itinerario->fecha_inicia = $it->fecha_inicio;
                            $model_cronograma_itinerario->fecha_fin = $it->fecha_fin;
                            $model_cronograma_itinerario->anotaciones = $it->anotaciones;
                            $model_cronograma_itinerario->cod_actividad = $it->actividad;
                            $model_cronograma_itinerario->cod_evaluacion = Auth::info_usuario('evaluacion');
                            $model_cronograma_itinerario->cod_medio = $it->medio;
                            $model_cronograma_itinerario->cod_etapa = $it->etapa;
                            $model_cronograma_itinerario->usuario = Auth::info_usuario('usuario');
                            $model_cronograma_itinerario->cod_actividad_padre = $model_cronograma->id;
                            if(!$model_cronograma_itinerario->save()){
                                $v = false;
                            }
                        }
                        $valid = $v;
                    }
                    else{
                        $valid = false;
                    }
                }
                if($valid){
                    $helper->commit();
                    echo json_encode(array('mensaje' => 'El cronograma fue actualizado correctamente'));
                }
                else{
                    $helper->rollback();
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo actualizar el cronograma";
                }
            }
        }
        else{
            $valid = true;
            $helper = new cronograma();
            $helper->begin();
            foreach($actividades as $a){
                $model_cronograma = new cronograma();
                $model_cronograma->fecha_inicia = $a->fecha_inicio;
                $model_cronograma->fecha_fin = $a->fecha_fin;
                if(isset($a->anotaciones) && $a->anotaciones != '')
                $model_cronograma->anotaciones = $a->anotaciones;
                $model_cronograma->cod_actividad = $a->actividad;
                $model_cronograma->cod_evaluacion = Auth::info_usuario('evaluacion');
                $model_cronograma->cod_medio = $a->medio;
                $model_cronograma->cod_etapa = $a->etapa;
                $model_cronograma->usuario = Auth::info_usuario('usuario');
                if($model_cronograma->save()){
                    $v = true;
                    foreach($a->responsables as $r){
                        $sql_usuario = "SELECT
                        sys_usuario.username
                        FROM
                        gen_persona
                        INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id
                        where gen_persona.id = $r";

                        $usuario = BD::$db->queryOne($sql_usuario);

                        $model_crono_responsable = new crono_responsables();
                        $model_crono_responsable->usuario = $usuario;
                        $model_crono_responsable->cod_cronograma = $model_cronograma->id;
                        if(!$model_crono_responsable->save()){
                            $v = false;
                        }
                    }
                    foreach($a->invitados as $invitado){                    
                        $model_crono_asamblea = new crono_asamblea();
                        $model_crono_asamblea->nombres = $invitado->nombre;
                        $model_crono_asamblea->email = $invitado->email;
                        $model_crono_asamblea->cod_cronograma = $model_cronograma->id;
                        if(!$model_crono_asamblea->save()){
                            $v = false;
                        }
                    }
                    foreach($a->itinerario as $it){                    
                        $model_cronograma_itinerario = new cronograma();
                        $model_cronograma_itinerario->fecha_inicia = $it->fecha_inicio;
                        $model_cronograma_itinerario->fecha_fin = $it->fecha_fin;
                        if(isset($it->anotaciones) && $it->anotaciones != '')
                        $model_cronograma_itinerario->anotaciones = $it->anotaciones;
                        $model_cronograma_itinerario->cod_actividad = $it->actividad_gen;
                        $model_cronograma_itinerario->cod_evaluacion = Auth::info_usuario('evaluacion');
                        $model_cronograma_itinerario->cod_medio = $it->medio;
                        $model_cronograma_itinerario->cod_etapa = $it->etapa;
                        $model_cronograma_itinerario->usuario = Auth::info_usuario('usuario');
                        $model_cronograma_itinerario->cod_actividad_padre = $model_cronograma->id;
                        if(!$model_cronograma_itinerario->save()){
                            $v = false;
                        }
                    }
                    $valid = $v;
                }
                else{
                    $valid = false;
                }
            }
            if($valid){
                $helper->commit();
                echo json_encode(array('mensaje' => 'El cronograma fue guardado correctamente'));
            }
            else{
                $helper->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo guardar el cronograma";
            }
        }
    }
    
    public function get_cronograma_evaluacion(){
        header('Content-Type: application/json'); 
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_cronograma = sprintf('select * from cronograma where cod_evaluacion=%s and cod_actividad_padre is null', $evaluacion);
        $cronograma = BD::$db->queryAll($sql_cronograma);
        
        foreach($cronograma as $key=>$c){
            $sql_responsables = sprintf('SELECT
            gen_persona.nombres,
            gen_persona.primer_apellido,
            gen_persona.id,
            gen_persona.segundo_apellido
            FROM
            crono_responsables
            INNER JOIN sys_usuario ON crono_responsables.usuario = sys_usuario.username
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id where cod_cronograma=%s', $c['id']);
            $responsables = BD::$db->queryAll($sql_responsables);
            $cronograma[$key]['responsables'] = $responsables;
            $sql_invitados = sprintf('select * from crono_asamblea where cod_cronograma=%s', $c['id']);
            $invitados = BD::$db->queryAll($sql_invitados);
            $cronograma[$key]['invitados'] = $invitados;
            $sql_itinerario = sprintf('select * from cronograma where cod_evaluacion=%s and cod_actividad_padre = %s', $evaluacion, $c['id']);
            $itinerario = BD::$db->queryAll($sql_itinerario);
            $cronograma[$key]['itinerario'] = $itinerario;
        }        
        echo json_encode($cronograma);
    }
    
    public function borrar_actividad(){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_delete = "delete from cronograma where id=$id";
        $rs = BD::$db->query($sql_delete);
        if(PEAR::isError($rs)){
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se pudo eliminar el cronograma";
        }
        else{
            json_encode(array('status' => 'ok', 'mensaje' => 'La actividad fue eliminada exitosamente'));
        }
    }

    public function crear(){
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        View::add_css('public/css/sievas/select2.css');
        View::add_js('modules/sievas/scripts/cronograma/main.js'); 
        View::add_js('modules/sievas/scripts/cronograma/add.js');  
        
        View::render('cronogramas/add.php'); 
    } 
    
    public function ver_cronograma(){
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $sql_cronograma = sprintf('SELECT
        cronograma.id,
        cronograma.fecha_inicia,
        cronograma.fecha_fin,
        cronograma.anotaciones,
        cronograma.usuario,
        crono_actividades.actividad,
        crono_medios.medio,
        crono_etapas.etapa
        FROM
        cronograma
        INNER JOIN crono_actividades ON cronograma.cod_actividad = crono_actividades.id
        INNER JOIN crono_medios ON cronograma.cod_medio = crono_medios.id
        INNER JOIN crono_etapas ON cronograma.cod_etapa = crono_etapas.id
        where cod_evaluacion=%s and cod_actividad_padre is null order by cronograma.id asc', $evaluacion);
        $cronograma = BD::$db->queryAll($sql_cronograma);

        foreach($cronograma as $key=>$c){
            
            $fecha_inicia_tmp = explode('-', $cronograma[$key]['fecha_inicia']);
            $cronograma[$key]['fecha_inicia'] = $fecha_inicia_tmp[2].'/'.$fecha_inicia_tmp[1].'/'.$fecha_inicia_tmp[0];
            $fecha_fin_tmp = explode('-', $cronograma[$key]['fecha_fin']);
            $cronograma[$key]['fecha_fin'] = $fecha_fin_tmp[2].'/'.$fecha_fin_tmp[1].'/'.$fecha_fin_tmp[0];
            
            $sql_responsables = sprintf('SELECT
            gen_persona.nombres,
            gen_persona.primer_apellido,
            gen_persona.id,
            gen_persona.segundo_apellido
            FROM
            crono_responsables
            INNER JOIN sys_usuario ON crono_responsables.usuario = sys_usuario.username
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id where cod_cronograma=%s', $c['id']);
            
            $responsables = BD::$db->queryAll($sql_responsables);
            $cronograma[$key]['responsables'] = $responsables;
            $sql_invitados = sprintf('select * from crono_asamblea where cod_cronograma=%s', $c['id']);
            $invitados = BD::$db->queryAll($sql_invitados);
            $cronograma[$key]['invitados'] = $invitados;
            $sql_itinerario = sprintf('SELECT
                cronograma.id,
                cronograma.fecha_inicia,
                cronograma.fecha_fin,
                cronograma.anotaciones,
                cronograma.usuario,
                crono_actividades.actividad,
                crono_medios.medio,
                crono_etapas.etapa
                FROM
                cronograma
                INNER JOIN crono_actividades ON cronograma.cod_actividad = crono_actividades.id
                INNER JOIN crono_medios ON cronograma.cod_medio = crono_medios.id
                INNER JOIN crono_etapas ON cronograma.cod_etapa = crono_etapas.id
                where cod_evaluacion=%s and cod_actividad_padre = %s order by cronograma.id asc', $evaluacion, $c['id']);
            $itinerario = BD::$db->queryAll($sql_itinerario);
            foreach($itinerario as $idx=>$i){
                $fecha_inicia_tmp = explode('-', $itinerario[$idx]['fecha_inicia']);
                $itinerario[$idx]['fecha_inicia'] = $fecha_inicia_tmp[2].'/'.$fecha_inicia_tmp[1].'/'.$fecha_inicia_tmp[0];
                $fecha_fin_tmp = explode('-', $itinerario[$idx]['fecha_fin']);
                $itinerario[$idx]['fecha_fin'] = $fecha_fin_tmp[2].'/'.$fecha_fin_tmp[1].'/'.$fecha_fin_tmp[0];
            }
            $cronograma[$key]['itinerario'] = $itinerario;
            
        } 
        $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];
        $vars['cronograma'] = $cronograma;
        View::add_js('public/js/imprimir.js'); 
        View::add_js('modules/sievas/scripts/cronograma/main.js'); 
        View::add_js('modules/sievas/scripts/cronograma/reporte.js');
        View::render('cronogramas/cronograma.php',$vars); 
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function eliminar_cronograma_comite(){
        header('Content-Type: application/json'); 
        $id = $_GET['id'];
        if($id > 0){
            $sql = sprintf("delete from cronograma_evaluadores where id=%s", $id);
            $result = BD::$db->query($sql);
            if(!PEAR::isError($result)){
                echo json_encode(array(
                    'status' => 'ok'
                ));
            }
            else{
                //error
                echo $sql;
            }
        }
    }
    
    public function ver_cronograma_comite(){
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        
        $momento_actual = $this->get_momento_actual();
        $rol = Auth::info_usuario('rol');
        $privilegio = 0;
        if($rol == 2 && $momento_actual['cod_momento'] == 2 && $momento_actual['cod_cargo'] == 1){
            $privilegio = 1;
        }
        
        //rol evaluador
        //coordinador comite evaluacion actual
        
        
        
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $sql_cronograma = sprintf('SELECT
        cronograma.id,
        cronograma.fecha_inicia,
        cronograma.fecha_fin,
        cronograma.anotaciones,
        crono_actividades.actividad,
        crono_medios.medio
        FROM
        cronograma_evaluadores as cronograma
        inner join crono_actividades on crono_actividades.id = cronograma.cod_actividad
        inner join crono_medios on crono_medios.id = cronograma.cod_medio
        where cod_momento_evaluacion=%s order by cronograma.orden asc', $momento_actual['momento_id']);
        
        $cronograma = BD::$db->queryAll($sql_cronograma);

        $vars['cronograma'] = $cronograma;
        $vars['privilegio'] = $privilegio;
        $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];
        $vars['cronograma'] = $cronograma;
        View::add_js('public/js/imprimir.js'); 
        View::add_js('modules/sievas/scripts/cronograma/main.js'); 
        View::add_js('modules/sievas/scripts/cronograma/crono_comite.js');
        View::add_css('public/css/sievas/select2.css');
        View::render('cronogramas/cronograma_comite.php',$vars); 
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
//    public function imprimir_cronograma(){        
//        View::render('cronogramas/cronograma-print.php'); 
//    }
    
    public function get_actividades(){
        header('Content-Type: application/json');     
        $actividades = array();
        $agenda = filter_input(INPUT_GET, 'agenda', FILTER_SANITIZE_NUMBER_INT);
        $sql_actividades = "select * from crono_actividades";
        if($agenda > 0){
             $sql_actividades .= " where agenda='1'";
        }           
        $actividades = BD::$db->queryAll($sql_actividades);   
//        var_dump($actividades);
        echo json_encode($actividades);
    }
    
    public function get_actividad(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $actividad = array();
        if($id > 0){
            $sql_actividad = sprintf("select * from crono_actividades where id=%s", BD::$db->quote($id));
            $actividad = BD::$db->queryRow($sql_actividad);
        }       
        echo json_encode($actividad);
    }
    
    public function get_etapas(){
        header('Content-Type: application/json');     
        $etapas = array();
        $sql_etapas = "select * from crono_etapas";
        $etapas = BD::$db->queryAll($sql_etapas);       
        echo json_encode($etapas);
    }
    
    public function get_etapa(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $etapa = array();
        if($id > 0){
            $sql_etapa = sprintf("select * from crono_etapas where id=%s", BD::$db->quote($id));
            $etapa = BD::$db->queryRow($sql_etapa);
        }       
        echo json_encode($etapa);
    }    
    
    public function get_medios(){
        header('Content-Type: application/json');     
        $medios = array();
        $sql_medios = "select * from crono_medios";
        $medios = BD::$db->queryAll($sql_medios);       
        echo json_encode($medios);
    }
    
    public function get_medio(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $medio = array();
        if($id > 0){
            $sql_medio = sprintf("select * from crono_medios where id=%s", BD::$db->quote($id));
            $medio = BD::$db->queryRow($sql_medio);
        }       
        echo json_encode($medio);
    }
    
    
    
}
