<?php

session_start();

class FrontController
{
    static function main()
    {
        
        //Incluimos algunas clases:
       
        require_once CORE_PATH.'db/config.php';
        
        require_once CORE_PATH.'db/eval.php';

        require CORE_PATH.'Config.php'; //de configuracion
        require APP_PATH.DS.'config.php'; //Archivo con configuraciones.
        require CORE_PATH.'SPDO.php'; //PDO con singleton
        require CORE_PATH.'ControllerBase.php'; //Clase controlador base
        require CORE_PATH.'ModelBase.php'; //Clase modelo base
        require CORE_PATH.'ModelBase2.php'; //Clase modelo base
        require CORE_PATH.'View.php'; //Mini motor de plantillas
        require CORE_PATH.'Session.php'; //motor de sesion
        require LIBS_PATH.'load.php';

        
        
        $config = Config::singleton();
//                    
        require $config->get('modulesFolder').'auth/controllers/Auth.php';
//        
//		
        if(!empty($_GET['mod'])){
            $moduleName = trim($_GET['mod']);
        }else{
            header('Location:index.php?mod=auth&controlador=usuarios&accion=login');
            $moduleName = 'auth';
        }
		
//        //Con el objetivo de no repetir nombre de clases, nuestros controladores
//        //terminaran todos en Controller. Por ej, la clase controladora Items, ser� ItemsController
//
//        //Formamos el nombre del Controlador o en su defecto, tomamos que es el IndexController
        if(! empty($_GET['controlador']))
              $controllerName = $_GET['controlador'] . 'Controller';
        else
              $controllerName = "indexController";

        //Lo mismo sucede con las acciones, si no hay accion, tomamos index como accion
        if(! empty($_GET['accion']))
              $actionName = $_GET['accion'];
        else
              $actionName = "index";
//             
//             
        if(is_dir(APP_PATH.DS.$config->get('modulesFolder').$moduleName)){
            $controllerPath = APP_PATH.DS.$config->get('modulesFolder').$moduleName.DS.$config->get('controllersFolder') . $controllerName . '.php';
        }else{
            die('El modulo no existe - 404 not found');
        }	

            //Incluimos el fichero que contiene nuestra clase controladora solicitada	
        if(is_file($controllerPath))
              require $controllerPath;
        else
            die('El controlador no existe - 404 not found');

        //Si no existe la clase que buscamos y su accion, tiramos un error 404
        if (is_callable(array($controllerName, $actionName)) == false) 
        {
            trigger_error ($controllerName . '->' . $actionName . '` no existe', E_USER_NOTICE);
            return false;
        }  
        
//
	  if(!Auth::autenticado()){
            if(!Auth::tiene_permiso($_GET['controlador'], $_GET['accion'])){
//                header('Location:index.php?mod=auth&controlador=usuarios&accion=login');
            }else{
                if($actionName === 'index')
                    header('Location:index.php?controlador=usuarios&accion=login');
            }
        }else{

        }


        //Si todo esta bien, creamos una instancia del controlador y llamamos a la accion
        $controller = new $controllerName();
        $controller->$actionName();
    }
}