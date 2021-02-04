<?php
Load::model2('gen_paises');
Load::model2('gen_paises_idiomas');
Load::model2('GeneralModel');

class gen_paisesController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){   
//        View::add_js('public/js/jquery.validate.js');
//        View::add_js('public/js/bootbox.min.js');
//        View::add_css('public/js/select2/select2.css');
//        View::add_css('public/js/select2/select2-bootstrap.css');
//        View::add_js('public/js/select2/select2.min.js');
//        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/gen_paises/main.js');
        View::add_js('modules/sievas/scripts/gen_paises/listar.js');
        View::render('gen_paises/listar.php');   
    }  
    
    public function agregar(){   
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/gen_paises/main.js');
        View::add_js('modules/sievas/scripts/gen_paises/agregar.js');
        View::render('gen_paises/add.php');   
    }  
    
    public function get_continentes(){
        header('Content-Type: application/json');
        $sql_continentes = "select * from gen_continentes";
        $continentes = BD::$db->queryAll($sql_continentes); 
        echo json_encode($continentes);
    }
    
    public function get_zonas(){
        header('Content-Type: application/json');
        $continente = filter_input(INPUT_GET, 'continente', FILTER_SANITIZE_NUMBER_INT);
        $zonas = array();
        if($continente > 0){
            $sql_zonas = "select * from gen_paises_zonas where cod_continente = $continente";
            $zonas = BD::$db->queryAll($sql_zonas);
        }
        echo json_encode($zonas);
    }
    
    public function get_idiomas(){
        header('Content-Type: application/json');
        $sql_idiomas = "select *,nombre as idioma from gen_idiomas";
        $idiomas = BD::$db->queryAll($sql_idiomas);
        echo json_encode($idiomas);
    }
    
    public function guardar_pais(){
        header('Content-Type: application/json');
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_STRING);
        $nacionalidad = filter_input(INPUT_POST, 'nacionalidad', FILTER_SANITIZE_STRING);
        $continente = filter_input(INPUT_POST, 'continente', FILTER_SANITIZE_STRING);
        $zona = filter_input(INPUT_POST, 'zona', FILTER_SANITIZE_STRING);
        $idioma = filter_input(INPUT_POST, 'idioma', FILTER_SANITIZE_STRING);
        $indicativo = filter_input(INPUT_POST, 'indicativo', FILTER_SANITIZE_STRING);
        
        $model_pais = new gen_paises();
        $model_pais->begin();
        $model_pais->nom_pais = $pais;
        $model_pais->nacionalidad = $nacionalidad;
        $model_pais->cod_continente = $continente;
        $model_pais->cod_zona = $zona;
        $model_pais->cod_idioma = $idioma;
        $model_pais->indicativo = $indicativo;
        if($model_pais->save()){
            $model_pais_idioma = new gen_paises_idiomas();
            $model_pais_idioma->gen_pais_id = $model_pais->id;
            $model_pais_idioma->gen_idiomas_id = $idioma;
            if($model_pais_idioma->save()){
                $model_pais_idioma->commit();
                echo json_encode(array('mensaje' => 'El país fue almacenado correctamente'));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error_msg' => 'El país no pudo ser guardado'));
//                echo $model_pais_idioma->error_sql();
            }
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo json_encode(array('error_msg' => 'El país no pudo ser guardado'));
        }
        
    }
    
    public function get_dt_paises(){
        $model = new GeneralModel();
        $model->listar();
    }
    
    public function get_paises(){
        header('Content-Type: application/json');     
        $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
        $sql_paises = "select * from gen_paises";
        if($q !== ''){
            $sql_paises = "select * from gen_paises where nom_pais like '%$q%'";
        }
        $paises = BD::$db->queryAll($sql_paises);
        echo json_encode($paises);
    }    
    
    public function get_pais(){
        header('Content-Type: application/json'); 
        $pais = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        if($pais > 0){
            $sql_pais = "select * from gen_paises where id=$pais";
            $pais_data = BD::$db->queryRow($sql_pais);
        }
        
        
        
        echo json_encode($pais_data);
    }
    
    
}
