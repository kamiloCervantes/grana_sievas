<?php

/**
 * Description of Auth
 *
 * @author Jaime Hernandez
 */
Auth::$db = \BD::$db;

class Auth
{   
    static public $db;
    
    static public function autenticar($usuario, $contrasena)
    {
        $consultaUsuario = sprintf('select su.username, su.cod_persona, su.creador, su.idioma, su.lmcc_access, su.cod_idioma FROM sys_usuario su 
			WHERE su.username = "%s" and passwd = "%s" and autorizado="1"',
			\BD::$db->escape($usuario), 
			md5(\BD::$db->escape($contrasena)));
//        var_dump($consultaUsuario);
//        var_dump($consultaUsuario);
        $resultadoCU = \BD::$db->query($consultaUsuario);    
        $creador = null;
        if ($resultadoCU->numRows() > 0) { //Si el usuario esta registrado
            $row = $resultadoCU->fetchRow();
            $creador = $row['creador'] !== null ? $row['creador'] : null;
            if($creador != null){                
                \Session::set('usuario', $usuario, 'auth');
                \Session::set('cod_persona', $row['cod_persona'], 'auth');
                \Session::set('lmcc_access', $row['lmcc_access'], 'auth');
                \Session::set('idioma', $row['idioma'], 'auth');
                \Session::set('cod_idioma', $row['cod_idioma'], 'auth');
                $accesos    =  self::cargar_accesos($usuario);
                \Session::set('accesos', $accesos,'auth');
                $permisosTmp=array('usuarios'=>'logout,oops,locacion,rol,cargar_permisos');
                \Session::set('permisos', $permisosTmp,'auth');
            }            
        }        
        return $resultadoCU->numRows() > 0 && $creador !== null;
    }
    static public function cargar_permisos($id_rol) {
        var_dump("permisos");
        $rol = $id_rol;
        \Session::set('rol', $rol, 'auth');
        $cl =  Auth::info_usuario('cod_locacion');
        $tl =  Auth::info_usuario('tipo_locacion');
        if($tl==1){//Esta a nivel de sede
            $consultaPermisos   =  sprintf("select ra.nom_pagina as controlador, ra.permisos as permisos, 
					m.id as idm,m.modulo as modulo 
					FROM roles_acceso  as ra
					JOIN paginas_eval as pag on(pag.nom_pagina=ra.nom_pagina)
					JOIN sys_modulos as m on(m.id=pag.cod_modulo)
					JOIN sys_modulo_institucion as mi on (mi.cod_modulo=m.id)
					JOIN aca_sedes as s on(s.cod_colegio=mi.cod_locacion)
					WHERE ra.idrol = %s AND s.id = %s AND mi.tipo_locacion=2 AND ra.activo = %s",
					$rol,$cl,"SI");
        }elseif($tl==2){//Está a nivel de colegio
            $consultaPermisos   =  sprintf('select ra.nom_pagina as controlador,ra.permisos as permisos,m.id as idm,m.modulo as modulo from roles_acceso  as ra
                                            JOIN paginas_eval as pag on(pag.nom_pagina=ra.nom_pagina)
                                            JOIN sys_modulos as m on(m.id=pag.cod_modulo)
                                            JOIN sys_modulo_institucion as mi on (mi.cod_modulo=m.id)
                                            where ra.idrol=%s and mi.cod_locacion=%s and mi.tipo_locacion=2 and ra.activo="SI"',$rol,$cl);
        }else{//Esta a nivel de secretaria
            $consultaPermisos   =  sprintf('select ra.nom_pagina as controlador,ra.permisos as permisos,m.id as idm,m.modulo as modulo from roles_acceso  as ra
                                            JOIN paginas_eval as pag on(pag.nom_pagina=ra.nom_pagina)
                                            JOIN sys_modulos as m on(m.id=pag.cod_modulo)
                                            JOIN sys_modulo_institucion as mi on (mi.cod_modulo=m.id)
                                            where ra.idrol=%s and mi.cod_locacion=%s and mi.tipo_locacion=%s and ra.activo="SI"',$rol,$cl,$tl);
        }
        $resultadoCP = \BD::$db->query($consultaPermisos);
        var_dump($resultadoCP->numRows());
        if ($resultadoCP->numRows() > 0) { //Tiene permisos
            
            $permisosTmp = array();
            $modulos_tmp    =array();
            while ($permiso = $resultadoCP->fetchRow()) {
                $permisosTmp[$permiso['controlador']] = $permiso['permisos'];
                if(!array_key_exists($permiso['idm'], $modulos_tmp)){
                    $modulos_tmp[$permiso['idm']]=$permiso['modulo'];
                }
            }
            \Session::set('permisos', $permisosTmp,'auth');
            \Session::set('modulos', $modulos_tmp,'auth');
        } else { //No tiene permisos
            var_dump("permisosf");
             \Session::set('permisos', null,'auth');
            \Session::set('modulos', null,'auth');
        }  
        
    }
    static private function cargar_accesos($usuario) {
        $consulta   =  sprintf('select distinct ua.cod_acceso,ua.tipo_acceso from sysusers_acceso ua where ua.username="%s" order by tipo_acceso',$usuario);
        $resultado  =\BD::$db->query($consulta);
        $salida=array();
        while ($acceso = $resultado->fetchRow()) {
            $salida[]=$acceso;
        }
        return $salida;
    }
    static public function info_usuario($info,$valor=null)
    {
        if($valor!=null){
            \Session::set($info, $valor, 'auth');
        }
        if (\Session::isset_data($info, 'auth')) {
            return \Session::get($info, 'auth');
        }
        return null;
    }
    static public function autenticado()
    {
        return \Session::isset_data('usuario', 'auth');
    }
    static public function salir()
    {
        session_unset();
        session_destroy();
        return true;
    }
    static public function tiene_permiso($controlador, $metodo)
    {                
//        if(!\Session::isset_data('permisos', 'auth'))return false;
        if(!\Session::isset_data('permisos', 'auth')){
            //permisos de invitado
            $sql_permisos = sprintf("SELECT
            roles_acceso.permisos
            FROM
            roles_acceso
            WHERE
            roles_acceso.idrol = 5 AND
            roles_acceso.activo = 'SI' AND
            roles_acceso.nom_pagina = '%s'", $controlador);
            
//            var_dump($sql_permisos);
            
            $permisos = BD::$db->queryOne($sql_permisos);
            return substr_count($permisos, $metodo) > 0;
        }
        else{
            $controladores  =  \Session::get('permisos', 'auth');
            if(!array_key_exists($controlador,$controladores)){
                return  false;
            }
            $permisos = $controladores[$controlador];
            return substr_count($permisos, $metodo) > 0;
        }
	
    }
}
