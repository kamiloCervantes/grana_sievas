<?php

/**
 * Clase para administrar los datos que se almacenan en la sesion de todo el sistema.
 */
class Session {
    /**
     * Modifica un elemento de la sesión
     * @param String $index Nombre del elemento al que se desea modificar el dato.
     * @param mixed $value Valor asignado al elemento
     * @param string $namespace Nombre del modulo al que se desea acceder
     */
    static function set($index, $value, $namespace='auth')
    {        
        $_SESSION['EVAL'][$namespace][$index] = $value;
    }
    /**
     * Obtiene un elemento de la sesion
     * @param String $index Nombre del elemento del que se desea obtener el dato.
     * @param string $namespace Nombre del modulo al que se desea acceder.
     * @return mixed Valor contenido en el lemento de la sesion
     */
    static function get($index, $namespace='auth')
    {
        if(isset($_SESSION['EVAL'][$namespace][$index])) {
            return $_SESSION['EVAL'][$namespace][$index];
        } else {
            return null;
        }
    }
    /**
     * Destruye un elemento de la sesion y devuelve su valor
     * @param String $index Nombre del elemento del que se destruir.
     * @param string $namespace Nombre del modulo al que se desea acceder.
     * @return mixed Valor contenido en el lemento de la sesion
     */
    static function unset_data($index, $namespace='auth')
    {
        $valor  =Session::get($index, $namespace);
        unset($_SESSION['EVAL'][$namespace][$index]);
        return  $valor;
    }
    /**
     * Verifica si un elemento está registrado en la sesion de modulo
     * @param String $index Nombre del elemento del que se desea obtener el dato.
     * @param string $namespace Nombre del modulo al que se desea acceder.
     * @return Bool True si el elemento existe, false si no existe
     */
    static function isset_data($index, $namespace='auth'){
        return isset($_SESSION['EVAL'][$namespace][$index]);
    }   
}