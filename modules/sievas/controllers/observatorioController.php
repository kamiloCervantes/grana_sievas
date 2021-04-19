<?php
Load::model2('observatorio');
Load::model2('observaciones');
Load::model2('observacion_categoria');
Load::model2('GeneralModel');
//define('IN_MYBB', NULL);
//require APP_PATH.DS.'../observatorio/global.php'; //Archivo con configuraciones.
//require_once CORE_PATH.'libs/MyBB/class.MyBBIntegrator.php';
//$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config); 

class observatorioController extends ControllerBase{    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){  
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
       View::add_js('modules/sievas/scripts/observatorio/main.js'); 
       View::add_js('modules/sievas/scripts/observatorio/index.js'); 
       
       //lineamientos evaluacion
       $evaluacion_actual = Auth::info_usuario('evaluacion'); 
       $usuario = Auth::info_usuario('usuario');        
       $sql = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
       $cod_persona = BD::$db->queryOne($sql);  
       
       $vars = array();
       
       $sql2 = sprintf("SELECT
        Count(observaciones.id)
        FROM
        observaciones
        WHERE
        observaciones.autor = %s AND
        observaciones.cod_evaluacion = %s", $cod_persona, $evaluacion_actual);
       $observaciones = BD::$db->queryOne($sql2);
       
       
       $vars['observaciones'] = $observaciones;
       
       $sql = sprintf("SELECT
        lineamientos.id,
        lineamientos.nom_lineamiento,
        lineamientos.num_orden
        FROM
        evaluacion
        INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN lineamientos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s order by lineamientos.num_orden asc
        ", $evaluacion_actual);
       
       $rubros = BD::$db->queryAll($sql);

       
       $vars['rubros'] = $rubros;
       
       View::render('observatorio/observatorio.php', $vars);
    } 
    
    
    public function ver_observacion(){
        $id = $_GET['id'];
        
        $sql_observaciones = sprintf("SELECT
                observaciones.id,
                observaciones.tema,
                observaciones.comentario,
                observaciones.lugar,
                observaciones.fecha,
                observacion_categoria.tipo_categoria,
                observacion_categoria.cod_categoria,
                gen_persona.nombres,
                gen_documentos.ruta
                FROM
                observaciones
                INNER JOIN observacion_categoria ON observacion_categoria.cod_observacion = observaciones.id
                INNER JOIN gen_persona ON observaciones.autor = gen_persona.id
                LEFT JOIN gen_documentos on gen_documentos.id = gen_persona.foto
                where observaciones.id=%s", $id);
        $observacion = BD::$db->queryRow($sql_observaciones);
        
        
        
        switch($observacion['tipo_categoria']){
            case 1:
                $sql_categoria = sprintf('select nom_lineamiento from lineamientos where id=%s', $observacion['cod_categoria']);
                $categoria = BD::$db->queryOne($sql_categoria);
                $observacion['categoria'] = $categoria;
                break;
            case 2:
                $sql_categoria = sprintf('select area from areas_conocimiento where id=%s', $observacion['cod_categoria']);
                $categoria = BD::$db->queryOne($sql_categoria);
                $observacion['categoria'] = $categoria;
                break;
        }

        $vars['observacion'] = $observacion;
        
        View::render('observatorio/ver_observacion.php',$vars);
    }
    
    public function agregar(){       
//       var_dump($myBBI); 
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
       View::add_js('http://www.geoplugin.net/javascript.gp'); 
       View::add_js('modules/sievas/scripts/observatorio/main.js'); 
       View::add_js('modules/sievas/scripts/observatorio/add.js'); 
       View::render('observatorio/add.php');
    } 
    
    public function get_areas_conocimiento(){
        header('Content-Type: application/json');     
        $areas_conocimiento = array();
        $sql_areas_conocimiento = "select * from areas_conocimiento";
        $areas_conocimiento = BD::$db->queryAll($sql_areas_conocimiento);       
        echo json_encode($areas_conocimiento);
    }
    
    public function get_area_conocimiento(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $area_conocimiento = array();
        if($id > 0){
            $sql_area_conocimiento = sprintf("select * from areas_conocimiento where id=%s", BD::$db->quote($id));
            $area_conocimiento = BD::$db->queryRow($sql_area_conocimiento);
        }       
        echo json_encode($area_conocimiento);
    }
    
    public function get_areas_nucleos(){
        header('Content-Type: application/json');     
        $areas_nucleos = array();
        $area_conocimiento = filter_input(INPUT_GET, 'area_conocimiento', FILTER_SANITIZE_NUMBER_INT);
        if($area_conocimiento > 0){
            $sql_areas_nucleos = "select * from areas_nucleos where cod_area = $area_conocimiento";
            $areas_nucleos = BD::$db->queryAll($sql_areas_nucleos);    
        }           
        echo json_encode($areas_nucleos);
    }
    
    public function get_area_nucleo(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $area_nucleo = array();
        if($id > 0){
            $sql_area_nucleo = sprintf("select * from areas_nucleos where id=%s", BD::$db->quote($id));
            $area_nucleo = BD::$db->queryRow($sql_area_nucleo);
        }       
        echo json_encode($area_nucleo);
    }
    
    public function get_observaciones(){
        header('Content-Type: application/json');
        $filtro = filter_input(INPUT_GET, 'filtro', FILTER_SANITIZE_NUMBER_INT);
        $observaciones = array();
        $sql_autor = sprintf("select cod_persona from sys_usuario where username = '%s'", Auth::info_usuario('usuario'));
        $autor = BD::$db->queryOne($sql_autor);
        switch($filtro){
            case 1:
                $sql_observaciones = sprintf("SELECT
                observacion_categoria.id,
                observacion_categoria.cod_tipo_categoria,
                observacion_categoria.nom_categoria,
                observacion_categoria.cod_asociado,
                observacion_categoria.creador,
                observacion_categoria_tipo.categoria_tipo
                FROM
                observacion_categoria
                INNER JOIN observacion_categoria_tipo ON observacion_categoria.cod_tipo_categoria = observacion_categoria_tipo.id");
                $observaciones = BD::$db->queryAll($sql_observaciones);
                break;
            case 2:
                $sql_observaciones = sprintf("SELECT
                observaciones.id,
                observaciones.tema,
                observaciones.comentario,
                observaciones.lugar,
                observaciones.fecha,
                observacion_categoria.tipo_categoria,
                observacion_categoria.cod_categoria,
                gen_persona.nombres,
                areas_conocimiento.area as categoria
                FROM
                observaciones
                INNER JOIN observacion_categoria ON observacion_categoria.cod_observacion = observaciones.id
                INNER JOIN gen_persona ON observaciones.autor = gen_persona.id
                INNER JOIN areas_conocimiento ON observacion_categoria.cod_categoria = areas_conocimiento.id
                where autor=%s and tipo_categoria = %s", $autor, 2);
                $observaciones = BD::$db->queryAll($sql_observaciones);
                break;
        }
        echo json_encode($observaciones);
    }    
    
    public function guardar_observacion(){
        header('Content-Type: application/json'); 
        $tema = filter_input(INPUT_POST, 'tema', FILTER_SANITIZE_STRING);
        $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);
        $lugar = filter_input(INPUT_POST, 'lugar', FILTER_SANITIZE_STRING);
        $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_NUMBER_INT);
//        $tipo_categoria = filter_input(INPUT_POST, 'tipo_categoria', FILTER_SANITIZE_STRING);
        
        $valid = true;
        
        $sql_autor = sprintf("select cod_persona from sys_usuario where username = '%s'", Auth::info_usuario('usuario'));
        $autor = BD::$db->queryOne($sql_autor);
        
        $model_observacion = new observaciones();
        $model_observacion->begin();
        $model_observacion->tema = $tema;
        $model_observacion->comentario = $comentario;
        $model_observacion->lugar = $lugar;
        $model_observacion->autor = $autor;
        $model_observacion->cod_categoria = $categoria;
        if(!$model_observacion->save()){
            $valid = false;  
        }
        
        if($valid){
            $model_observacion->commit();
            echo json_encode(array('status' => 'ok', 'mensaje' => 'La observación fue registrada correctamente'));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se ha podido registrar la observación";
        }
    }
    
    public function guardar_observaciones(){
        header('Content-Type: application/json');

        $observaciones = $_POST['observaciones'];
        $observaciones = json_decode($observaciones, true);
        $aux = new observaciones();
        $aux->begin();
        $evaluacion_actual = Auth::info_usuario('evaluacion'); 
        $usuario = Auth::info_usuario('usuario');        
        $sql = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
        $cod_persona = BD::$db->queryOne($sql);  
        $valid = true;
        
        foreach($observaciones as $key=>$ob){
            $model_observacion = new observaciones();
            $model_observacion->tema = 'Observación del rubro #'.($key+1);
            $model_observacion->comentario = htmlentities($ob['observacion']);
            $model_observacion->lugar = '';
            $model_observacion->autor = $cod_persona;
            $model_observacion->cod_rubro = $ob['rubro'];
            $model_observacion->cod_evaluacion = $evaluacion_actual;
            if(!$model_observacion->save()){
                 var_dump($model_observacion->error_sql());
                $valid = false;  
            }
            else{
               
            }
        }       
        if($valid){
            $aux->commit();
            echo json_encode(array('status' => 'ok', 'mensaje' => 'Gracias por registrar sus observaciones!'));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se han podido registrar las observaciones";
        }
    }
    
    public function guardar_experiencia(){
//        $area_conocimiento = filter_input(INPUT_POST, 'area_conocimiento', FILTER_SANITIZE_STRING);
        header('Content-Type: application/json');     
        $nucleo = filter_input(INPUT_POST, 'nucleo', FILTER_SANITIZE_NUMBER_INT);
        $experiencia = $_POST['experiencia'];
        
        $model_experiencia = new observatorio();
        $model_experiencia->cod_nucleo = $nucleo;
        $model_experiencia->experiencia = $experiencia;
        $model_experiencia->username = Auth::info_usuario('usuario');
        
        if($model_experiencia->save()){
            echo json_encode(array('status' => 'ok', 'mensaje' => 'La observación fue registrada correctamente'));
        }
        else{
            echo 'No se pudo guardar la observación';
            echo $model_experiencia->error_sql();
        }
    }
    
    public function get_rubros(){
        $sql = "SELECT
        lineamientos.id,
        lineamientos.nom_lineamiento
        FROM
        lineamientos_conjuntos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN lineamientos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        lineamientos_conjuntos.activo = 'SI'";
        
        $rubros = BD::$db->queryAll($sql);
        
        echo json_encode($rubros);
    }
    
    public function get_rubro(){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $rubro = array();
        if($id > 0){
            $sql = "SELECT
            lineamientos.id,
            lineamientos.nom_lineamiento
            FROM
            lineamientos_conjuntos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            INNER JOIN lineamientos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            WHERE
            lineamientos.padre_lineamiento = 0 AND
            lineamientos_conjuntos.activo = 'SI' and
            lineamientos.id = $id";

            $rubro = BD::$db->queryRow($sql);
        }
        
        
        echo json_encode($rubro);
    }
    
    public function categorias(){
        View::add_js('modules/sievas/scripts/ob_categorias/main.js');
        View::add_js('modules/sievas/scripts/ob_categorias/listar.js');
        View::render('ob_categorias/listar.php'); 
    }
    
    public function get_dt_categorias(){
        $model = new GeneralModel();
        $model->listar();
    }
    
    public function add_categoria(){
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/ob_categorias/main.js');
        View::add_js('modules/sievas/scripts/ob_categorias/add.js');
        View::render('ob_categorias/add.php');
    }
    
    public function get_tipos_categoria(){
        $sql = "select * from observacion_categoria_tipo order by orden asc";
        $categorias = BD::$db->queryAll($sql);
        echo json_encode($categorias);
    }
    
    public function get_tipo_categoria(){
        $categoria_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $categoria = array();
        if($categoria_id > 0){
            $sql = sprintf('select * from observacion_categoria_tipo where id=%s', $categoria_id);
            $categoria = BD::$db->queryRow($sql);
        }
        echo json_encode($categoria);
    }
    
    public function guardar_categoria(){
        header('Content-Type: application/json');
        $nombre = filter_input(INPUT_POST, 'nom_categoria', FILTER_SANITIZE_STRING);
        $tipo_categoria = filter_input(INPUT_POST, 'tipo_categoria', FILTER_SANITIZE_NUMBER_INT);
        $asociado = filter_input(INPUT_POST, 'asociado', FILTER_SANITIZE_NUMBER_INT);
        
        $model_observacion_categoria = new observacion_categoria();
        $model_observacion_categoria->cod_tipo_categoria = $tipo_categoria;
        $model_observacion_categoria->nom_categoria = $nombre;
        $model_observacion_categoria->creador = Auth::info_usuario('usuario');
        if($asociado > 0){
            $model_observacion_categoria->cod_asociado = $asociado;
        }       
        
        if($model_observacion_categoria->save()){
            echo json_encode(array('id' => $model_observacion_categoria->id));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se pudo guardar la categoría";
        }
        
    }
    
    public function get_categorias(){
        header('Content-Type: application/json');
        $categorias = array();
        $tipo_categoria = filter_input(INPUT_GET, 'tipo_categoria', FILTER_SANITIZE_NUMBER_INT);
        if($tipo_categoria > 0){
            $sql = sprintf('select * from observacion_categoria where cod_tipo_categoria = %s', $tipo_categoria);
            $categorias = BD::$db->queryAll($sql);
        }
        echo json_encode($categorias);
    }
    
    public function get_categoria(){
        header('Content-Type: application/json');
        $categoria = array();
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('select * from observacion_categoria where id = %s', $id);
            $categoria = BD::$db->queryRow($sql);
        }
        echo json_encode($categoria);
    }
}