<?php
Load::model2('GeneralModel');
class paginasController extends ControllerBase{
    
    private $model;
    
    public function __construct(){
        parent::__construct();
        $this->model = new GeneralModel($this->db);
    }
    
    public function index(){
        View::render('roles/paginas.php');
    }
    
    public function guardar(){        
        $this->model->guardar();
    }
    
    public function listar(){
        $this->model->listar();
    }
    
    public function eliminar(){
        $this->model->eliminar();
    }
    
    public function editar(){
        $this->model->editar();
    }
    
    public function get_fkeys(){
        $this->model->get_fkeys();
    }
    
    
}
