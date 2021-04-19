<?php
$config = Config::singleton();

use auth\controllers\Auth;

require_once $config->get('modulesFolder').'auth/models/sys_usuario.php';
require_once $config->get('modulesFolder').'auth/models/RolesModel.php';

class menuController {
    public function inicio() {
        $usuario=  Auth::info_usuario('usuario');
        $modelo_usuario =new sys_usuario();        
        $modelo_usuario->find("username='$usuario'");
        $nombre_usuario=$modelo_usuario->nombres;
        
        $modelo_roles =new RolesModel();
        $modelo_roles->find(Auth::info_usuario('rol'));
        $nombre_rol=$modelo_roles->idrol;
        
        $tipo_locacion  =  Auth::info_usuario('tipo_locacion');
        $cod_locacion   =  Auth::info_usuario('cod_locacion');
        $nombre_institucion ='';
        if($tipo_locacion==1){
            $modelo_sedes=new aca_sedes();
            $modelo_sedes->find($cod_locacion);
            $nombre_institucion.=' Sede: '.$modelo_sedes->nom_sede;
            $cod_locacion   =$modelo_sedes->cod_colegio;
            $tipo_locacion   =2;
        }
        if($tipo_locacion==2){
            $modelo_colegios=new aca_colegios();
            $modelo_colegios->find($cod_locacion);
            $nombre_institucion =$modelo_colegios->nom_colegio.'.'.$nombre_institucion;
        }
        if($tipo_locacion==5){
            $nombre_institucion ='GLOBAL';
        }
        $config = Config::singleton();

        View::render('inicio.php',null,'default.php');
    }
	
    public function modulos() {
        View::render('menus/modulos.php', NULL, 'default.php');
    }
}