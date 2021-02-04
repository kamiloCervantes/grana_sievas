<?php
Load::model2('eval_programas');
Load::model2('GeneralModel');
Load::model2('comite');
Load::model2('cargos');

class programasController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){      
        View::add_js('modules/sievas/scripts/programas/main.js'); 
        View::add_js('modules/sievas/scripts/programas/listar.js');               
        View::render('programas/listar.php');
    }  
    
    public function agregar(){
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/programas/main.js');
        View::add_js('modules/sievas/scripts/programas/add.js');
        View::render('programas/add.php');         
    }
    
    public function get_instituciones(){
        header('Content-Type: application/json');   
        $pais =  filter_input(INPUT_GET, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $instituciones = array();
        if($pais > 0){
            $sql_instituciones = sprintf("select * from eval_instituciones where cod_pais = %s", BD::$db->quote($pais));
            $instituciones = BD::$db->queryAll($sql_instituciones);
        }        
        echo json_encode($instituciones);
    }
    
    public function guardar_cargo(){
        header('Content-Type: application/json');
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $tipo_cargo = filter_input(INPUT_POST, 'tipo_cargo', FILTER_SANITIZE_NUMBER_INT);
        
        $model_cargo = new cargos();
        $model_cargo->tipo_cargo = $tipo_cargo;
        $model_cargo->cargo = $titulo;
        if($model_cargo->save()){
            echo json_encode(array('id' => $model_cargo->id));
        }
        else{
           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "El cargo no pudo ser guardado"; 
        }
    }
    
    public function get_cargo(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $cargo = array();
        if($id > 0){
            $sql = sprintf('select * from cargos where id=%s', $id);
            $cargo = BD::$db->queryRow($sql);
        }
        echo json_encode($cargo);
    }
    
    public function get_institucion(){
        header('Content-Type: application/json');      
        $institucion = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_institucion = "select * from eval_instituciones where id=$institucion";
        $institucion = BD::$db->queryRow($sql_institucion);
        echo json_encode($institucion);
    }
    
    public function get_dt_programas(){
        $model = new GeneralModel();
        $model->listar();
    }
    
    public function get_cargos(){
        header('Content-Type: application/json');     
        $tipo_cargo = filter_input(INPUT_GET, 'tipo_cargo', FILTER_SANITIZE_NUMBER_INT);
        $cargos = array();
        if($tipo_cargo > 0){
            $sql_cargos = sprintf('select * from cargos where tipo_cargo=%s',$tipo_cargo);
            $cargos = BD::$db->queryAll($sql_cargos);
        }
        echo json_encode($cargos);
    }
    
    public function editar(){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf("SELECT
            eval_programas.id,
            eval_programas.programa,
            eval_programas.adscrito,
            eval_programas.cod_nivel_academico,
            eval_programas.cod_nucleo,
            areas_nucleos.cod_area,
            eval_programas.nombre_director,
            eval_programas.cod_cargo_director,
            eval_programas.cod_pais,
            eval_programas.cod_institucion
            FROM
            eval_programas
            INNER JOIN areas_nucleos ON eval_programas.cod_nucleo = areas_nucleos.id
            where eval_programas.id = %s", $id);
            
            $programa = BD::$db->queryRow($sql);
            
            $vars['programa'] = $programa;
            $vars['titulo'] ='Editar programa educativo';
            
            View::add_js('public/js/jquery.validate.js');
            View::add_js('public/js/bootbox.min.js');
            View::add_css('public/js/select2/select2.css');
            View::add_css('public/js/select2/select2-bootstrap.css');
            View::add_js('public/js/select2/select2.min.js');
            View::add_js('public/js/select2/select2_locale_es.js');
            View::add_js('modules/sievas/scripts/programas/main.js');
            View::add_js('modules/sievas/scripts/programas/add.js');
            View::render('programas/add.php', $vars); 
        }
        
        
    }
    
    public function get_personas(){
        header('Content-Type: application/json');      
        $sql_personas = "SELECT gen_persona.nombres, gen_persona.primer_apellido, gen_persona.segundo_apellido, gen_persona.id
        FROM
        gen_persona
        INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id";
        $personas = BD::$db->queryAll($sql_personas);
        echo json_encode($personas);
    }
    
    public function get_persona(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_persona = "SELECT gen_persona.nombres, gen_persona.primer_apellido, gen_persona.segundo_apellido, gen_persona.id
        FROM
        gen_persona
        INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id where gen_persona.id=$id";
        $persona = BD::$db->queryRow($sql_persona);
        echo json_encode($persona);
    }
    
    public function get_personas_p(){
        header('Content-Type: application/json');      
        $sql_personas = "SELECT gen_persona.nombres, gen_persona.primer_apellido, gen_persona.segundo_apellido, gen_persona.id
        FROM
        gen_persona
        ";
        $personas = BD::$db->queryAll($sql_personas);
        echo json_encode($personas);
    }
    
    public function get_persona_p(){
        header('Content-Type: application/json');      
        $persona = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_persona = "SELECT *
        FROM
        gen_persona
        where gen_persona.id=$persona";
        $persona = BD::$db->queryRow($sql_persona);
        echo json_encode($persona);
    }
    
    public function get_usuarios(){
        header('Content-Type: application/json');      
        $sql_personas = "SELECT gen_persona.nombres, gen_persona.primer_apellido, gen_persona.segundo_apellido, gen_persona.id
        FROM
        gen_persona
        INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id";
        $personas = BD::$db->queryAll($sql_personas);
        echo json_encode($personas);
    }
    
    public function get_usuario(){
        header('Content-Type: application/json');      
        $persona = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_persona = "SELECT gen_persona.nombres, gen_persona.primer_apellido, gen_persona.segundo_apellido, gen_persona.id
        FROM
        gen_persona
        INNER JOIN sys_usuario ON sys_usuario.cod_persona = gen_persona.id where gen_persona.id=$persona";
        $persona = BD::$db->queryRow($sql_persona);
        echo json_encode($persona);
    }
    
    public function eliminar_programa(){
        header('Content-Type: application/json');   
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from eval_programas where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el programa";
            }
            else{
                echo json_encode(array('mensaje' => 'El programa fue eliminado correctamente'));
            }
        }
    }
    
    public function guardar_programa(){
        $valid = true;
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $programa = filter_input(INPUT_POST, 'programa', FILTER_SANITIZE_STRING);
        $institucion = filter_input(INPUT_POST, 'institucion', FILTER_SANITIZE_NUMBER_INT);
        $adscrito = filter_input(INPUT_POST, 'adscrito', FILTER_SANITIZE_STRING);
//        $decano = filter_input(INPUT_POST, 'decano', FILTER_SANITIZE_STRING);
        $nivel_academico = filter_input(INPUT_POST, 'nivel_academico', FILTER_SANITIZE_NUMBER_INT);
        $director = filter_input(INPUT_POST, 'director', FILTER_SANITIZE_STRING);
        $nucleo = filter_input(INPUT_POST, 'area_nucleo', FILTER_SANITIZE_STRING);
        $cargo_responsable = filter_input(INPUT_POST, 'cargo_responsable', FILTER_SANITIZE_STRING);
        
        $model_programa = new eval_programas();
        $model_programa->begin();
        $model_programa->cod_pais = $pais;
        $model_programa->programa = $programa;
        $model_programa->adscrito = $adscrito;
        $model_programa->cod_nucleo = $nucleo;
        $model_programa->cod_institucion = $institucion;
        $model_programa->cod_nivel_academico = $nivel_academico;
        $model_programa->nombre_director = $director;
        if($cargo_responsable > 0)
        $model_programa->cod_cargo_director = $cargo_responsable;
        
        if($id > 0){
            $model_programa->id = $id;
            if($model_programa->update()){

            }
            else{
                echo $model_programa->error_sql();
                $valid = false;
            }

             if($valid){
                $model_programa->commit();
                echo json_encode(array('mensaje' => 'El programa fue actualizado correctamente'));
            }
            else{
                $model_programa->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "El programa no pudo ser guardado";
            }
        }
        else{
            if($model_programa->save()){

            }
            else{
                echo $model_programa->error_sql();
                $valid = false;
            }

             if($valid){
                $model_programa->commit();
                echo json_encode(array('mensaje' => 'El programa fue creado correctamente'));
            }
            else{
                $model_programa->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "El programa no pudo ser guardado";
            }
        }
        
        
    }
 
}
