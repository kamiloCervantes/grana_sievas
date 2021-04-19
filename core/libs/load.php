<?php
class Load
{
    public static function lib($lib)
    {
        $file = LIBS_PATH . $lib .'.php';
        if (is_file($file)) {
            return include $file;
        } else {
            die("Libreria $lib no encontrada");
        }
    }

    public static function model($model)
    {   
        //Si no esta cargada la clase
        if (!class_exists($model, false)) {
            //Carga la clase
            if (!include MOD_PATH . 'models'. DS . $model.'.php') {
                die('no se pudo incluir el Modelo '.$model);
            }
        }

        return new $model();
    }
    
    public static function model2($model)
    {   
        
        
        //Si no esta cargada la clase
        /*if (!class_exists($model, false)) {
            //Carga la clase
            if (!include MOD_PATH . 'models'. DS . $model.'.php') {
                die('no se pudo incluir el Modelo '.$model);
            }
        }

        return new $model();*/
        
       $file = MOD_PATH . 'models'. DS . $model.'.php';       
       if(is_readable($file)){
            if (include_once $file) {
                if (!class_exists($model, false)){
                    $model = end(get_declared_classes());
                }
                return new $model(); 
            }else{
                die('no se pudo incluir el Modelo '.$model);
            }
       }else{
            die('no se pudo incluir el Modelo '.$model);
       }
        
    }
    
    public static function model_module($model,$modulo)
    {
        //Si no esta cargada la clase
        if (!class_exists($model, false)) {
            //Carga la clase
            if (!include MODULES_PATH.DS. $modulo .DS. 'models'. DS . $model.'.php') {
                die('no se pudo incluir');
            }
        }
        return new $model();
    }
}
