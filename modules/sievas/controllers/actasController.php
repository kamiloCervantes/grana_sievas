<?php
Load::model2('sieva_lineamientos');
Load::model2('cronograma');
Load::model2('crono_responsables');
Load::model2('crono_asamblea');
Load::model2('actas');
Load::model2('gen_documentos');

class actasController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        View::render('actas/listar.php'); 
    } 
    
    public function subir_acta(){
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('modules/sievas/scripts/actas/main.js'); 
        View::add_js('modules/sievas/scripts/actas/subir.js');  
        View::render('actas/subir_acta.php');
    }
    
    public function guardar_acta(){
        date_default_timezone_set('America/Bogota');
        header('Content-Type: application/json');
        $valid = true;
        $now = new DateTime();
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $actividad = filter_input(INPUT_POST, 'actividad', FILTER_SANITIZE_STRING);
        $archivo = $_FILES['soporte'];        
        
        if($archivo['error'] === UPLOAD_ERR_OK){
            if($archivo['size'] < 10000000){
                $rel_path = 'public/files/actas/'.$now->getTimestamp().'-'.$archivo['name'];
                $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                    $model = new gen_documentos();
                    $model->begin();
                    $model->ruta = $rel_path;
                    $model->nombre = $archivo['name'];
                    if($model->save()){
                        $model_acta = new actas();
                        $model_acta->nombre = $nombre;
                        $model_acta->cod_documento = $model->id;
                        $model_acta->cod_cronograma = $actividad;
                        $model_acta->anotaciones = $descripcion;
                        if(!$model_acta->save()){
                             $valid = false;
                        }
                    }
                    else{
                        $valid = false;
                    }
                   
                    if($valid){
                        $model->commit();
                        echo json_encode(array('status' => 'ok', 'mensaje' => 'Se ha registrado el acta correctamente'));
                    }
                    else{
                       header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                       echo 'No se pudo guardar el acta';
                    }
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo 'No se pudo guardar el acta';
                 }                
            }
            else{
                //el archivo no puede superar los 10 Mb
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo 'El acta no puede superar los 10 Mb';
            }            
        }
        else{
            //el archivo no puede superar los 10 Mb
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo 'No se pudo guardar el acta';
        }  
    }
    
    public function get_actas(){
        header('Content-Type: application/json');
        $actividad = filter_input(INPUT_GET, 'actividad', FILTER_SANITIZE_NUMBER_INT);
        $sql_actas = 'SELECT
        gen_documentos.ruta,
        actas.fecha,
        actas.nombre,
        actas.anotaciones
        FROM
        actas
        INNER JOIN gen_documentos ON actas.cod_documento = gen_documentos.id
        where cod_cronograma = '.$actividad;
        
        $actas = BD::$db->queryAll($sql_actas);
        echo json_encode($actas);
    }
    
    public function get_momento_actual(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $username = Auth::info_usuario('usuario');
        $sql = "SELECT
        comite.cod_persona,
        evaluacion.id,
        comite.cod_momento_evaluacion,
        comite.cod_cargo,
        momento_evaluacion.cod_momento
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        where evaluacion.id=$evaluacion and sys_usuario.username='$username'";
        
        return BD::$db->queryRow($sql);
    }
}
    
   