<?php

class index_sievaController extends ControllerBase{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
       $vars   =array();
        $usuario=  Auth::info_usuario('usuario');        
        $sql = sprintf("select gen_persona.nombres from sys_usuario left join gen_persona on sys_usuario.cod_persona = gen_persona.id where sys_usuario.username = '%s'", $usuario);
        $modelo_usuario = BD::$db->queryRow($sql);
        $vars['nombre_usuario'] =$modelo_usuario['nombres'];
        
        $modelo_roles =Load::model_module('RolesModel','auth');
        $modelo_roles->find(Auth::info_usuario('rol'));
        $vars['nombre_rol']=$modelo_roles->idrol;

        //tipo de evaluado y evaluado
        $tipo_evaluado = '';
        $evaluado = '';
        $id = Auth::info_usuario('evaluado');
        
		switch(Auth::info_usuario('tipo_evaluado')){
            case '1':
                $tipo_evaluado = 'Institución';
                $sql_evaluado = "SELECT *, nom_institucion as evaluado FROM eval_instituciones WHERE id = $id";
                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;
            case '2':
                $tipo_evaluado = 'Programa';
                $sql_evaluado = "SELECT *, programa as evaluado FROM eval_programas WHERE id = $id";
                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;
            case '3':
                $tipo_evaluado = 'Docente';
                $sql_evaluado = "SELECT
                    eval_profesores.id,
                    gen_persona.nombres as evaluado,
                    eval_instituciones.nom_institucion
                    FROM
                    eval_profesores
                    INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
                    INNER JOIN eval_instituciones ON eval_profesores.cod_institucion = eval_instituciones.id
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id WHERE eval_profesores.id = $id";

                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;
            case '6':
                $tipo_evaluado = 'Programa';
                $sql_evaluado = "SELECT *, programa as evaluado FROM eval_programas WHERE id = $id";
                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;
        }
		if(isset($evaluado['evaluado']) && $evaluado['evaluado'] !== ''){
                    $_SESSION['evaluado'] = $evaluado['evaluado'];
                }
		
		
		$vars['tipo_evaluado'] = $tipo_evaluado;
        $vars['evaluado'] = $evaluado;
        Auth::info_usuario('evaluado_nombre',$evaluado['evaluado']);
		View::render('welcome.php', $vars);         
    }      
}

