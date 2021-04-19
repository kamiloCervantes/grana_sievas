<?php
Load::model2('GeneralModel');
Load::model2('gen_idiomas');

class gen_idiomasController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
        
    }
    
    public function index(){
         View::add_js('modules/sievas/scripts/gen_idiomas/main.js');
        View::add_js('modules/sievas/scripts/gen_idiomas/listar.js');
        View::render('gen_idiomas/listar.php');
    }
    
    public function get_dt_idiomas(){
         $model = new GeneralModel();
         $model->listar();
    }
    
    public function agregar(){
        View::add_js('public/js/bootbox.min.js');
        View::add_js('modules/sievas/scripts/gen_idiomas/main.js');
        View::add_js('modules/sievas/scripts/gen_idiomas/agregar.js');
        View::render('gen_idiomas/add.php');
    }
    
    public function editar(){
        $idioma_id = $_GET['id'];
        $vars = array();
        if($idioma_id > 0){
            $sql = "select * from gen_idiomas where id = $idioma_id";
            $idioma = BD::$db->queryRow($sql);
            $vars['idioma'] = $idioma;
        }
        View::add_js('modules/sievas/scripts/gen_idiomas/main.js');
        View::add_js('modules/sievas/scripts/gen_idiomas/editar.js');
        View::render('gen_idiomas/edit.php', $vars);
    }
    
    public function guardar_idioma(){
        header('Content-Type: application/json');
        $idioma =  filter_input(INPUT_POST, 'idioma', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            //editar
            $model = new gen_idiomas();
            $model->id = $id;
            $model->nombre = $idioma;
            if($model->update()){
                echo json_encode(array(
                    'status' => 'ok',
                    'mensaje' => 'El idioma ha sido actualizado exitosamente'
                ));
            }
            else{
                echo $model->error_sql();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "El idioma no pudo ser actualizado";
            }
        }
        else{
            $model = new gen_idiomas();
            $model->nombre = $idioma;
            if($model->save()){
                echo json_encode(array(
                    'status' => 'ok',
                    'mensaje' => 'El idioma ha sido guardado exitosamente'
                ));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "El idioma no pudo ser guardado";
            }
        }
    }
    
    public function eliminar_idioma(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from gen_idiomas where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el idioma";
            }
            else{
                echo json_encode(array('mensaje' => 'El idioma fue eliminado correctamente'));
            }
        }
    }
    
   
    
}
