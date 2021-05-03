<?php
Load::model2('sieva_lineamientos');

class sieva_evaluadorController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
       //echo "sieva_evaluador";  
        View::render('evaluador/index.php'); 
    }  

    public function guardar(){
       View::add_js('public/js/jsTree/jstree.min.js');
       View::add_js('public/js/bootbox.min.js');       
       View::add_js('modules/sievas/scripts/sieva_evaluador.js');
       View::add_css('public/js/jsTree/themes/default/style.min.css');
       
       View::render('sieva_evaluador.php');    
    } 
    
    public function get_arbol(){
        header('Content-Type: application/json');
        $model_sieva_lineamientos = new sieva_lineamientos();
        echo json_encode($model_sieva_lineamientos->cargarArbol());
    }
   
    
     
    public function comite_externo(){
        View::render('comite_externo.php');  
    }
    
}
