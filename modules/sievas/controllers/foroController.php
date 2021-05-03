<?php
Load::model2('foro_temas');
Load::model2('foro_comentarios');


class foroController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $cod_momento = filter_input(INPUT_GET, 'momento', FILTER_SANITIZE_NUMBER_INT);
        
        //viene de la sesion
        $momento = $this->get_momento_actual();      
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }        
        
        $sql_temas = sprintf("SELECT
        foro_temas.tema,
        foro_temas.fecha,
        gen_persona.nombres,
        foro_temas.id,
        Count(foro_comentarios.id) as comentarios
        FROM
        foro_temas
        LEFT JOIN gen_persona ON foro_temas.cod_autor = gen_persona.id
        LEFT JOIN foro_comentarios ON foro_comentarios.cod_tema = foro_temas.id
        where foro_temas.cod_momento_evaluacion = %s GROUP BY
        foro_temas.id order by foro_temas.orden", $momento_evaluacion);
        
        
        $temas = BD::$db->queryAll($sql_temas);
        $vars['temas'] = $temas;
        
         $query_evaluacion_data = sprintf("select * from evaluacion where id=%s", Auth::info_usuario('evaluacion'));
       $evaluacion_data = BD::$db->queryRow($query_evaluacion_data);

       $vars['evaluacion'] = $evaluacion_data['etiqueta'];
        
        
        View::add_js('modules/sievas/scripts/foro/main.js'); 
        View::add_js('modules/sievas/scripts/foro/discusion.js'); 
        View::render('foro/discusion.php', $vars); 
    } 
    
    public function eliminar_comentario(){
        header('Content-Type: application/json');
        //verificar si es el último comentario del foro
        $comentario_id = filter_input(INPUT_POST, 'comentario_id', FILTER_SANITIZE_NUMBER_INT);
        $sql_foro_comentarios = sprintf("SELECT
            count(foro_comentarios.id)
            FROM
            foro_comentarios
            INNER JOIN foro_temas ON foro_comentarios.cod_tema = foro_temas.id
            WHERE
            foro_temas.id = (SELECT
            foro_comentarios.cod_tema
            from foro_comentarios WHERE
            foro_comentarios.id = %s
            )", $comentario_id);
        $comentarios = BD::$db->queryOne($sql_foro_comentarios);

        if($comentarios > 1){
            $sql_delete = sprintf("delete from foro_comentarios where id=%s", $comentario_id);
            $query = BD::$db->query($sql_delete);
            if(PEAR::isError($query)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo 'No se pudo eliminar el comentario del tema';
            }
            else{
                echo json_encode(array('msg' => 'El comentario del tema fue eliminado correctamente'));
            }
        }
        else{
             header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
             echo 'Para eliminar este comentario debe eliminar el tema.';
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
        momento_evaluacion.cod_momento
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        where evaluacion.id=$evaluacion and sys_usuario.username='$username'";
        return BD::$db->queryRow($sql);
    }
    
    
    
    public function agregar(){
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
       View::add_js('modules/sievas/scripts/foro/main.js'); 
       View::add_js('modules/sievas/scripts/foro/add.js'); 
       View::render('foro/add.php'); 
    } 
    
    public function responder(){
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
       View::add_js('modules/sievas/scripts/foro/main.js'); 
       View::add_js('modules/sievas/scripts/foro/responder.js'); 
        View::render('foro/responder.php'); 
    }
    
    public function ver_tema(){
        $tema_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_comentarios = sprintf('select foro_comentarios.id AS comentario_id,
        foro_temas.id AS tema_id,
        foro_temas.tema,
        foro_temas.cod_autor,
        foro_comentarios.comentario,
        foro_comentarios.id as comentario_id,
        foro_comentarios.cod_autor as comentario_autor,
        foro_comentarios.fecha AS fecha_comentario,
        foro_temas.fecha AS fecha_tema,
        gen_persona.nombres,
        gen_documentos.ruta
        FROM
        foro_comentarios
        LEFT JOIN foro_temas ON foro_comentarios.cod_tema = foro_temas.id
        LEFT JOIN gen_persona ON foro_comentarios.cod_autor = gen_persona.id
        LEFT JOIN gen_documentos ON gen_persona.foto = gen_documentos.id 
        where foro_temas.id =%s order by foro_comentarios.fecha', $tema_id);
        
        if(isset($_GET['momento'])){
            $vars['momento'] = $momento;
        }
        
        $sql_persona = sprintf("select cod_persona from sys_usuario where username = '%s'", Auth::info_usuario('usuario'));
        $persona = BD::$db->queryOne($sql_persona);
        $comentarios = BD::$db->queryAll($sql_comentarios);
        $vars['comentarios'] = $comentarios;
        $vars['persona'] = $persona;
        View::add_js('modules/sievas/scripts/foro/main.js'); 
        View::add_js('modules/sievas/scripts/foro/tema.js'); 
        View::render('foro/tema.php', $vars);
    }
    
    public function eliminar_tema(){
        header('Content-Type: application/json');
        $tema_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql = sprintf("delete from foro_temas where foro_temas.id = %s", $tema_id);
//        var_dump($sql);
        $query = BD::$db->query($sql);
        if(PEAR::isError($query)){
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo 'No se pudo eliminar el tema';
        }
        else{
            echo json_encode(array('msg' => 'El tema fue eliminado correctamente'));
        }
    }
    
    public function guardar_tema(){
        header('Content-Type: application/json');
        $tema = filter_input(INPUT_POST, 'tema', FILTER_SANITIZE_STRING);
        $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);
        
        $valid = true;        
        $cod_momento = filter_input(INPUT_POST, 'cod_momento', FILTER_SANITIZE_NUMBER_INT);
        
        //viene de la sesion
        $momento = $this->get_momento_actual();      
        $momento_evaluacion = $momento['cod_momento_evaluacion'];
        $rol = Auth::info_usuario('rol');
        if($rol == 1){
            $evaluacion = Auth::info_usuario('evaluacion');
            if($evaluacion > 0){
                $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", $cod_momento, $evaluacion);
                $momento_evaluacion = BD::$db->queryOne($sql_momento);
            }
        }
        
        $sql_temas = sprintf("SELECT
        count(foro_temas.id)
        FROM
        foro_temas
        where foro_temas.cod_momento_evaluacion = %s", $momento_evaluacion);
        
        $temas = BD::$db->queryOne($sql_temas);
        
        
        $usuario = Auth::info_usuario('usuario');
        $sql_usuario = sprintf("select cod_persona from sys_usuario where username ='%s'", $usuario);
        $persona = BD::$db->queryOne($sql_usuario);
        
        $model_tema = new foro_temas();
        $model_tema->begin();
        $model_tema->tema = $tema;
        $model_tema->cod_momento_evaluacion = $momento_evaluacion;
        $model_tema->cod_autor = $persona;
        $model_tema->orden = $temas+1;
        if($model_tema->save()){
            $model_comentario = new foro_comentarios();
            $model_comentario->cod_tema = $model_tema->id;
            $model_comentario->comentario = $comentario;
            $model_comentario->cod_autor = $persona;
            if(!$model_comentario->save()){
                $valid = false;
            }
        }
        else{
            $valid = false;
        }
        
        if($valid){
            $model_tema->commit();
            echo json_encode(array('status' => 'ok', 'mensaje' => 'El tema ha sido creado correctamente'));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo json_encode(array('error' => 'No se pudo guardar el tema'));
        }        
    }
    
    public function guardar_comentario(){
        header('Content-Type: application/json');
        $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);
        $cod_tema = filter_input(INPUT_POST, 'tema', FILTER_SANITIZE_STRING);
        $valid = true;               
       
        $usuario = Auth::info_usuario('usuario');
        $sql_usuario = sprintf("select cod_persona from sys_usuario where username ='%s'", $usuario);
        $persona = BD::$db->queryOne($sql_usuario);
        
        $model_comentario = new foro_comentarios();
        $model_comentario->cod_tema = $cod_tema;
        $model_comentario->comentario = $comentario;
        $model_comentario->cod_autor = $persona;
        if(!$model_comentario->save()){
            $valid = false;
        }

        
        if($valid){
            $model_comentario->commit();
            echo json_encode(array('status' => 'ok', 'mensaje' => 'El comentario ha sido creado correctamente'));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo json_encode(array('error' => 'No se pudo guardar el tema'));
        }
        
    }
    
    
}
    
   