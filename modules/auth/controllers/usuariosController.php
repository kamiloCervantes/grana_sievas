<?php
class usuariosController extends ControllerBase
{    
    public function login()//Login
    {        
        $config =  Config::singleton();
        if(!Auth::autenticado()){            
            if (isset($_POST['submit'])) {
                $usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $contrasena = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_STRING);
                if (Auth::autenticar($usuario, $contrasena)) {    
                    if(isset($_GET['evaluacion'])){
                        header('Location: index.php?mod=auth&controlador=usuarios&accion=locacion&evaluacion='.$_GET['evaluacion']);
                    }
                    else{
                        $sql = sprintf("SELECT
                        evaluacion.id,
                        count(evaluacion.id) as total,
                        comite.comite_centro
                        FROM
                        sys_usuario
                        INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
                        INNER JOIN comite ON comite.cod_persona = gen_persona.id
                        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
                        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                        WHERE
                        sys_usuario.username = '%s' and sys_usuario.autorizado = 1", Auth::info_usuario('usuario'));
                        
                        $evaluacion = BD::$db->queryRow($sql);
                        
                        $sql_rol = sprintf("SELECT
                        count(sysusers.idrol)
                        FROM
                        sysusers
                        INNER JOIN sys_usuario ON sysusers.username = sys_usuario.username
                        WHERE
                        sys_usuario.username = '%s' and sysusers.idrol = 1", Auth::info_usuario('usuario'));
                        
                        $rol = BD::$db->queryOne($sql_rol);
                        
//                        var_dump($rol);
//                        if($evaluacion['total'] > 1 && $rol == 0){
//                            Auth::info_usuario('comite_centro', $evaluacion['comite_centro']);
//                        }
                        
                        if($evaluacion['total'] == 1 && $rol == 0){
                            Auth::info_usuario('comite_centro', $evaluacion['comite_centro']);
                            header('Location: index.php?mod=auth&controlador=usuarios&accion=locacion&evaluacion='.$evaluacion['id']);
                        }
                        else{    
                            if($evaluacion['total'] == 0 && $rol == 0){
                                $sql_rol = sprintf("SELECT
                                count(sysusers.idrol)
                                FROM
                                sysusers
                                INNER JOIN sys_usuario ON sysusers.username = sys_usuario.username
                                WHERE
                                sys_usuario.username = '%s' and sysusers.idrol = 7", Auth::info_usuario('usuario'));
                                $rol = BD::$db->queryOne($sql_rol);
                                if($rol == 1){
                                    header('Location: index.php?mod=sievas&controlador=usuarios&accion=instituciones_cee');
                                }
                                else{
                                    header('Location: index.php?mod=auth&controlador=usuarios&accion=locacion');
                                }
                            }
                            else{
                               header('Location: index.php?mod=auth&controlador=usuarios&accion=locacion'); 
                            }
                        }
                        
                    }
                    
                } else {
                    require_once $config->get('modulesFolder').'auth/views/login.php';
                }            
            } else {                
                require_once $config->get('modulesFolder').'auth/views/login.php';
            }
        }else{//Si ya esta autenticado, lo redirige al index
            if(Session::isset_data('rol', 'auth')){
                header('Location: index.php?mod=sievas&controlador=index_sieva&accion=index');
            }else{
                header('Location: index.php?mod=auth&controlador=usuarios&accion=locacion');
            }
        }

    }
    public function locacion() {      
        
        if(!Auth::autenticado()){
            header('Location: index.php?mod=auth&controlador=usuarios&accion=logout');
        }
        
        if(Auth::info_usuario('lmcc_access') > 0){
            header('Location: index.php?mod=sievas&controlador=lmcc&accion=index');
        }
		
        $locaciones =array();        
		foreach (Auth::info_usuario('accesos') as $acceso) {
			switch ($acceso['tipo_acceso']) {
                
				case 1:
                    $consulta     = sprintf('SELECT c.nom_colegio, s.nom_sede 
									FROM aca_sedes s join aca_colegios c on s.cod_colegio=c.id 
									WHERE s.id = %s',
									$acceso['cod_acceso']);                   
                    BD::$db->setLimit(1);
                    $resultado    = BD::$db->query($consulta);
                    $info         = $resultado->fetchRow();
                    $locaciones[] = array(
									'id'=>$acceso['cod_acceso'],
									'tipo'=>$acceso['tipo_acceso'],
									'nombre'=>$info['nom_colegio'].'. Sede '.$info['nom_sede']);
                    break;
                
				case 2:
                    $consulta     = sprintf('select c.nom_colegio 
									FROM aca_sedes s join aca_colegios c on s.cod_colegio=c.id 
									WHERE c.id = %s',
									$acceso['cod_acceso']);
                    BD::$db->setLimit(1);
                    $resultado    = BD::$db->query($consulta);
                    $info         = $resultado->fetchRow();
                    $locaciones[] = array(
									'id'=>$acceso['cod_acceso'],
									'tipo'=>$acceso['tipo_acceso'],
									'nombre'=>$info['nom_colegio']); 
                    break;
                
				case 3:
                    $consulta     = sprintf('select secretaria FROM aca_secretarias WHERE id = %s',
									$acceso['cod_acceso']);
                    BD::$db->setLimit(1);
                    $resultado    = BD::$db->query($consulta);
                    $info         = $resultado->fetchRow();
                    $locaciones[] = array(
									'id'=>$acceso['cod_acceso'],
									'tipo'=>$acceso['tipo_acceso'],
									'nombre'=>$info['secretaria']); 
                    break;
                
				case 4:
                    $consulta     = sprintf('SELECT secretaria FROM aca_secretarias WHERE id = %s',
									$acceso['cod_acceso']);
                    BD::$db->setLimit(1);
                    $resultado    = BD::$db->query($consulta);
                    $info         = $resultado->fetchRow();
                    $locaciones[] = array(
									'id'=>$acceso['cod_acceso'],
									'tipo'=>$acceso['tipo_acceso'],
									'nombre'=>$info['secretaria']); 
                    break;
                
				case 5://ADMIN                   
                    $locaciones[] =array(
									'id'=>0,
									'tipo'=>5,
									'nombre'=>'PAIS || INSTITUCION || SEDE || PROGRAMA'); 
                    break;
                                case 7:
                                    echo "redireccion a evaluadores externos";
                                    break;
                case 10:
                    $consulta     = sprintf('select c.nom_colegio 
									FROM aca_sedes s join aca_colegios c on s.cod_colegio=c.id 
									WHERE c.id = %s',
									$acceso['cod_acceso']);
                    BD::$db->setLimit(1);
                    $resultado    = BD::$db->query($consulta);
                    $info         = $resultado->fetchRow();
                    $locaciones[] = array(
									'id'=>$acceso['cod_acceso'],
									'tipo'=>$acceso['tipo_acceso'],
									'nombre'=>$info['nom_colegio']); 
                    break;
            }            
        }
        $config =  Config::singleton();
        require_once $config->get('modulesFolder').'auth/views/locacion.php';
    }
    public function rol() {
        if(!Auth::autenticado())
            header('Location: index.php?mod=auth&controlador=usuarios&accion=logout');
        if(!isset($_GET['cl'])||!isset($_GET['tl'])){
            header('Location:index.php?mod=auth&controlador=usuarios&accion=locacion');
        }
        
		$codigo =  filter_input(INPUT_GET, 'cl', FILTER_SANITIZE_NUMBER_INT);
        $tipo   =  filter_input(INPUT_GET, 'tl', FILTER_SANITIZE_NUMBER_INT);        
        
        Auth::info_usuario('cod_locacion',$codigo);
        Auth::info_usuario('tipo_locacion',$tipo);
        
		$consulta   =  sprintf('select ua.idrol id, r.idrol nombre 
			FROM sysusers_acceso ua join roles r on ua.idrol=r.id 
			WHERE ua.username = "%s" and ua.cod_acceso = %s and ua.tipo_acceso = %s', 
			Auth::info_usuario('usuario'),
			$codigo,
			$tipo);
        $roles  =  BD::$db->queryAll($consulta);   
        $config =  Config::singleton();
        require_once $config->get('modulesFolder').'auth/views/roles.php';        
    }
    
    public function cargar_permisos() {        
        if(!Auth::autenticado())
            header('Location: index.php?mod=auth&controlador=usuarios&accion=logout');
        if(!isset($_GET['cr'])){            
            $cl =  Auth::info_usuario('cod_locacion');
            $tl =  Auth::info_usuario('tipo_locacion');
            if($cl==null || $tl==null){
                header('Location:index.php?mod=auth&controlador=usuarios&accion=locacion');
            }else{
                header("Location:index.php?mod=auth&controlador=usuarios&accion=rol&tl=$tl&cl=$cl");
            }
        }
        $codigo =  filter_input(INPUT_GET, 'cr',FILTER_SANITIZE_NUMBER_INT);
        Auth::cargar_permisos($codigo);
        header('Location: index.php?mod=sievas&controlador=index_sieva&accion=index'); //GO TO SIEVAS
		//header('Location: index.php?mod=default&controlador=menu&accion=modulos');     GO TO MODULOS
    }
    public function logout(){
        if(Auth::autenticado()){
            Auth::salir();
        }
        header('Location: index.php?mod=auth&controlador=usuarios&accion=login');
    }
    
    public function prueba(){
        echo Auth::info_usuario('usuario');
    }
    
    public function register() {
       if(!empty($_POST)){
           echo '<pre>';
       print_r($_POST);
       }else{
           require 'views/usuarios/register.php';
       }
    }
    
    public function continuar(){  

         if(!Auth::autenticado()){
         
             header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
         }
         else{
     
             $rol = filter_input(INPUT_GET, 'rol', FILTER_SANITIZE_NUMBER_INT);
             $pais = filter_input(INPUT_GET, 'pais', FILTER_SANITIZE_NUMBER_INT);
             $tipo_evaluado = filter_input(INPUT_GET, 'tipo_evaluado', FILTER_SANITIZE_NUMBER_INT);
             $evaluado = filter_input(INPUT_GET, 'evaluado', FILTER_SANITIZE_NUMBER_INT);
             $evaluacion = filter_input(INPUT_GET, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
             
             if($evaluacion > 0){
                $sql_evaluacion = sprintf("SELECT
                evaluacion.ev_red,
                evaluacion.padre,
                evaluacion.ev_cna,
                evaluacion.ev_anterior,
                evaluacion.version,
                evaluacion.tablero
                FROM
                evaluacion
                WHERE
                evaluacion.id = %s
                ", $evaluacion);
                
                $evaluacion_data = BD::$db->queryRow($sql_evaluacion);
                //var_dump($evaluacion_data);
                $ev_red = 0;
                
               // if($evaluacion_data['ev_red'] == '1' && $evaluacion_data['padre'] == null){
                if($evaluacion_data['ev_red'] == '1'){
                    $ev_red = 1;
                }
                
                $sql = sprintf("SELECT
                        evaluacion.id,
                        count(evaluacion.id) as total,
                        comite.comite_centro
                        FROM
                        sys_usuario
                        INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
                        INNER JOIN comite ON comite.cod_persona = gen_persona.id
                        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
                        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                        WHERE
                        sys_usuario.username = '%s' and sys_usuario.autorizado = 1 and evaluacion.id = %s", 
                        Auth::info_usuario('usuario'), $evaluacion);
                        
                $evaluacion_centro = BD::$db->queryRow($sql);

                Auth::info_usuario('rol',$rol);
                Auth::info_usuario('pais',$pais);
                Auth::info_usuario('tipo_evaluado',$tipo_evaluado);
                Auth::info_usuario('evaluado',$evaluado);
                Auth::info_usuario('evaluacion',$evaluacion);
                Auth::info_usuario('ev_red',$ev_red);
                Auth::info_usuario('ev_padre',$evaluacion_data['padre']);
                Auth::info_usuario('comite_centro',$evaluacion_centro['comite_centro']);
                Auth::info_usuario('ev_cna',$evaluacion_data['ev_cna']);
                Auth::info_usuario('ev_anterior',$evaluacion_data['ev_anterior']);
                Auth::info_usuario('ev_version',$evaluacion_data['version']);
                Auth::info_usuario('ev_tablero',$evaluacion_data['tablero']);

//                Auth::cargar_permisos($rol);
                
                echo json_encode(array('status' => 'ok'));
                
             }
             else{
                 if($rol == 6){
                     Auth::info_usuario('rol',$rol);
                     echo json_encode(array('status' => 'ok'));
                 }
                 else{
                     header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "Debe seleccionar una evaluación para continuar";
                 }  
             }
         } 
    }
	
    public function oops() {
        //require 'views/usuarios/oops.php';
        View::render('oops.php',null,null);
    }
        public function listar(){
      
/*
SELECT u.cod_persona, u.username, r.idrol, r.id, n.cod_sede, se.nom_sede, s.activo  
		FROM sys_usuario u 
		INNER JOIN evaluacion e ON s.username = u.username)
		INNER JOIN sysusers s ON (s.username = u.username)
		INNER JOIN roles AS r ON (s.idrol = r.id) 
*/	  
	  
	   $sql = "SELECT u.cod_persona, u.username, r.idrol, r.id, n.cod_sede, se.nom_sede, s.activo 
	   FROM sys_usuario AS u INNER JOIN sysusers AS s ON s.username = u.username 
	   LEFT JOIN ( 
	   		SELECT am.cod_persona, aoan.cod_sede FROM aca_matricula AS am 
				INNER JOIN aca_oferta_academica AS aoa ON am.cod_oferta_academica = aoa.id 
				INNER JOIN aca_oferta_anual AS aoan ON aoa.cod_oferta_anual = aoan.id 
				UNION 
					SELECT ac.cod_persona, ac.cod_sede FROM acrh_contratos AS ac 
					UNION 
						SELECT ma.acudiente, oan.cod_sede FROM gen_grupo_fam_acudiente AS ma 
						INNER JOIN aca_matricula AS m ON ma.cod_matricula = m.id 
						INNER JOIN aca_oferta_academica AS oa ON m.cod_oferta_academica = oa.id 
						INNER JOIN aca_oferta_anual AS oan ON oa.cod_oferta_anual = oan.id ) AS n ON u.cod_persona = n.cod_persona 
						INNER JOIN roles AS r ON s.idrol = r.id LEFT JOIN aca_sedes AS se ON se.id = n.cod_sede";
       
	   $lista_usuarios = $this->db->queryAll($sql);
       
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json; charset=utf-8');
        
        $data['aaData'] = array();
        
        foreach($lista_usuarios as $usuario){
            array_push($data['aaData'],array_values($usuario));
        }
        
        echo json_encode($data);
    }
    
    public function crear(){
        if(!empty($_POST)){
            $this->model->cod_persona   =  filter_input(INPUT_POST, 'cod_persona',FILTER_SANITIZE_NUMBER_INT);
            $this->model->passwd        = md5(filter_input(INPUT_POST, 'passwd',FILTER_SANITIZE_STRING));
            $this->model->autorizado    = 'SI';//filter_input(INPUT_POST, 'autorizado',FILTER_SANITIZE_STRING);
            $this->model->nombres       =  filter_input(INPUT_POST, 'nombres',FILTER_SANITIZE_STRING);
            $this->model->username      =  filter_input(INPUT_POST, 'username',FILTER_SANITIZE_STRING);
            $this->model->creador       = Auth::info_usuario('usuario');
            $this->model->save();
       }else{
           require 'views/usuarios/crear.php';
       }
    }
    
    public function get_roles(){
        header('Content-Type: application/json');  
        $usuario = Auth::info_usuario('usuario');
        $sql_roles = "SELECT *
        FROM
        sysusers
        INNER JOIN roles ON sysusers.idrol = roles.id where 
        sysusers.username = ".BD::$db->quote($usuario);
        $roles = BD::$db->queryAll($sql_roles);
        echo json_encode($roles);
    }
}