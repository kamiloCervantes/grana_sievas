<?php
//Load::model2('sieva_lineamientos');

class sieva_perfilController extends ControllerBase{
    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){    
    }  
    
    public function guardar(){
       View::add_js('public/js/bootbox.min.js');
       View::add_js('modules/sievas/scripts/sieva_perfil.js');
       View::render('sieva_perfil.php');    
    } 
    
}
