<?php
Load::model2('gen_persona');
Load::model2('GeneralModel');
Load::model2('evaluador');
Load::model2('experto');
Load::model2('comite');
Load::model2('eval_profesores');
Load::model2('gen_persona_formacion');
Load::model2('gen_persona_experiencia');
Load::model2('sys_usuario');
Load::model2('sysusers');
Load::model2('sysusers_acceso');
Load::model2('lmcc_users');
Load::model2('lmcc_accesos');
Load::model2('gen_documentos');

include LIBS_PATH.'/Mail/MailHelper.php';

class usuariosController extends ControllerBase{    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function instituciones_cee(){
        $usuario = Auth::info_usuario('usuario');
        
        $sql_persona = sprintf("select cod_persona from sys_usuario where username='%s'",
                $usuario);
        
        $persona = BD::$db->queryOne($sql_persona);
        
        $sql_instituciones = sprintf("SELECT
        eval_instituciones.nom_institucion,
        eval_instituciones.id
        FROM
        evaluacion_comite_revision
        INNER JOIN evaluacion ON evaluacion_comite_revision.cod_evaluacion = evaluacion.id
        INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
        INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
        WHERE
        evaluacion_comite_revision.cod_persona = %s
        GROUP BY
        eval_instituciones.id", $persona);
        
        $instituciones = BD::$db->queryAll($sql_instituciones);
        $vars = array();
        $vars['instituciones'] = $instituciones;
        
        View::render('usuarios/instituciones_cee.php', $vars);      
    }
    
    public function listar_usuarios_lmcc(){
        $sql_users_lmcc = "select * from lmcc_users";        
        $users_lmcc = BD::$db->queryAll($sql_users_lmcc);        
        $vars['users'] = $users_lmcc;
        View::render('usuarios/listar_usuarios_lmcc.php', $vars);
    }
    
    
    public function agregar_usuario_lmcc(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_STRING);
            $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
            $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_STRING);
            $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
            $email_personal = filter_input(INPUT_POST, 'email_personal', FILTER_SANITIZE_STRING);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $skype = filter_input(INPUT_POST, 'skype', FILTER_SANITIZE_STRING);
            $foto = filter_input(INPUT_POST, 'foto_perfil', FILTER_SANITIZE_STRING);
            $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
            $nombre_usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING);
            $passwd1 = filter_input(INPUT_POST, 'passwd1', FILTER_SANITIZE_STRING);
            $passwd2 = filter_input(INPUT_POST, 'passwd2', FILTER_SANITIZE_STRING);
            $evaluaciones = $_POST['to'];

            $valid = true;
            $messages = array();

            //debe seleccionar al menos una evaluacion
            if(count($evaluaciones)==0){
                $valid = false;
                $messages[] = 'Debe seleccionar al menos una evaluación';
            }
            //nombre no puede quedar en blanco
            if(is_null($nombre) || $nombre == ''){
                $valid = false;
                $messages[] = 'El campo Nombre es obligatorio';
            }
            //Contraseña no puede quedar en blanco
            if(is_null($passwd1) || $passwd1 == ''){
                $valid = false;
                $messages[] = 'El campo Contraseña es obligatorio';
            }
            //Repetir contraseña no puede quedar en blanco
            if(is_null($passwd2) || $passwd2 == ''){
                $valid = false;
                $messages[] = 'El campo Repetir contraseña es obligatorio';
            }
            //nombre de usuario no puede quedar en blanco
            if(is_null($nombre_usuario) || $nombre_usuario == ''){
                $valid = false;
                $messages[] = 'El campo Nombre de usuario es obligatorio';
            }

            //contraseñas deben coincidir
            if($passwd1 != $passwd2){
                $valid = false;
                $messages[] = 'Las contraseñas no coinciden';
            }
            
            
            if($valid){
                $commit = true;
                $lmcc_user = new lmcc_users();
                $lmcc_user->begin();
                $lmcc_user->nombre = $nombre;
                $lmcc_user->genero = $genero;
                $lmcc_user->direccion = $direccion;
                $lmcc_user->celular = $celular;
                $lmcc_user->tipodocumento = $tipodocumento;
                $lmcc_user->documento = $documento;
                $lmcc_user->fecha = $fecha;
                $lmcc_user->email_personal = $email_personal;
                $lmcc_user->telefono = $telefono;
                $lmcc_user->skype = $skype;
                $lmcc_user->username = $nombre_usuario;
                $lmcc_user->passwd = md5($passwd1);
                if($pais > 0)
                $lmcc_user->cod_pais = $pais;
                if($foto > 0)
                $lmcc_user->foto = $foto;

                if($lmcc_user->save()){
                    foreach($evaluaciones as $key=>$e){
                        $lmcc_acceso = new lmcc_accesos();
                        $lmcc_acceso->lmcc_users_id = $lmcc_user->id;
                        $lmcc_acceso->evaluacion_id = $e;
                        if(!$lmcc_acceso->save()){
                            $commit = false;
                            $messages[] = 'No se pudo guardar el acceso '.$key;
                        }
                    }
                }
                else{
                    
                    $commit = false;
                    $messages[] = 'No se pudo guardar el usuario';
                }

                if($commit){
                    $lmcc_user->commit();
                    header('Location: index.php?mod=sievas&controlador=usuarios&accion=listar_usuarios_lmcc');
                }
                else{
                    $lmcc_user->rollback();
                }

            }
        

        }
        $sql_evaluaciones = sprintf("SELECT
        evaluacion.id,
        evaluacion.etiqueta,
        evaluacion.finalizado
        FROM
        evaluacion
        WHERE
        evaluacion.finalizado = %s",
                0);
        
        $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
        
        $vars['evaluaciones'] = $evaluaciones;
        $vars['messages'] = $messages;
        
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/multiselect.min.js');
        View::add_js('modules/sievas/scripts/usuarios_lmcc/main.js');
        View::add_js('modules/sievas/scripts/usuarios_lmcc/agregar_usuario.js');
        View::render('usuarios/agregar_usuario_lmcc.php', $vars);
    }
    
    public function editar_usuario_lmcc(){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        if($id>0){
            $sql_usuario_lmcc = sprintf("select *, lmcc_users.nombre as nombre, gen_documentos.nombre as nom from lmcc_users inner join gen_documentos on lmcc_users.foto = gen_documentos.id where lmcc_users.id=%s", $id);  
            $usuario_lmcc = BD::$db->queryRow($sql_usuario_lmcc);            
            $vars['usuario'] = $usuario_lmcc;
            
            $sql_accesos_usuario = sprintf("select evaluacion_id from lmcc_accesos where lmcc_users_id = %s", $id);
            $accesos_usuario = BD::$db->queryAll($sql_accesos_usuario);
            $vars['accesos'] = implode(',',array_map(function($e){
                return $e['evaluacion_id'];
            },$accesos_usuario));
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_STRING);
            $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
            $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_STRING);
            $fecha = $_POST['fecha_nacimiento'];
            $email_personal = filter_input(INPUT_POST, 'email_personal', FILTER_SANITIZE_STRING);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $skype = filter_input(INPUT_POST, 'skype', FILTER_SANITIZE_STRING);
            $foto = filter_input(INPUT_POST, 'foto_perfil', FILTER_SANITIZE_STRING);
            $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
            $nombre_usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING);
            $passwd1 = filter_input(INPUT_POST, 'passwd1', FILTER_SANITIZE_STRING);
            $passwd2 = filter_input(INPUT_POST, 'passwd2', FILTER_SANITIZE_STRING);
            $evaluaciones = $_POST['to'];

            $valid = true;
            $messages = array();

            //debe seleccionar al menos una evaluacion
            if(count($evaluaciones)==0){
                $valid = false;
                $messages[] = 'Debe seleccionar al menos una evaluación';
            }
            //nombre no puede quedar en blanco
            if(is_null($nombre) || $nombre == ''){
                $valid = false;
                $messages[] = 'El campo Nombre es obligatorio';
            }
            
            //nombre de usuario no puede quedar en blanco
            if(is_null($nombre_usuario) || $nombre_usuario == ''){
                $valid = false;
                $messages[] = 'El campo Nombre de usuario es obligatorio';
            }

            //contraseñas deben coincidir
            if(!(is_null($passwd1) || $passwd1 == '') && !(is_null($passwd2) || $passwd2 == '') && $passwd1 != $passwd2){
                $valid = false;
                $messages[] = 'Las contraseñas no coinciden';
            }
            
            
            if($valid){
                $commit = true;
                $lmcc_user = new lmcc_users();
                $lmcc_user->id = $id;
                $lmcc_user->find($id);
                $lmcc_user->begin();
                
                $lmcc_user->nombre = $nombre;
                $lmcc_user->genero = $genero;
                $lmcc_user->direccion = $direccion;
                $lmcc_user->celular = $celular;
                $lmcc_user->tipodocumento = $tipodocumento;
                $lmcc_user->documento = $documento;
                $lmcc_user->fecha = $fecha;
                $lmcc_user->email_personal = $email_personal;
                $lmcc_user->telefono = $telefono;
                $lmcc_user->skype = $skype;
                $lmcc_user->username = $nombre_usuario;
                if($passwd1 != '')
                $lmcc_user->passwd = md5($passwd1);
                if($pais > 0)
                $lmcc_user->cod_pais = $pais;
                if($foto > 0)
                $lmcc_user->foto = $foto;

                if($lmcc_user->update()){
                    $sql_delete_accesos = sprintf('delete from lmcc_accesos where lmcc_users_id = %s', $id);
                    $delete_accesos = BD::$db->query($sql_delete_accesos);
                    foreach($evaluaciones as $key=>$e){
                        $lmcc_acceso = new lmcc_accesos();
                        $lmcc_acceso->lmcc_users_id = $lmcc_user->id;
                        $lmcc_acceso->evaluacion_id = $e;
                        if(!$lmcc_acceso->save()){
                            $commit = false;
                            $messages[] = 'No se pudo guardar el acceso '.$key;
                        }
                    }
                }
                else{
//                    echo $lmcc_user->error_sql();
                    $commit = false;
                    $messages[] = 'No se pudo guardar el usuario';
                }

                if($commit){
                    $lmcc_user->commit();
                    header('Location: index.php?mod=sievas&controlador=usuarios&accion=listar_usuarios_lmcc');
                }
                else{
                    $lmcc_user->rollback();
                }

            }
        

        }
        $sql_evaluaciones = sprintf("SELECT
        evaluacion.id,
        evaluacion.etiqueta,
        evaluacion.finalizado
        FROM
        evaluacion
        WHERE
        evaluacion.finalizado = %s",
                0);
        
        $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
        
        $vars['evaluaciones'] = $evaluaciones;
        $vars['messages'] = $messages;
        
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/multiselect.min.js');
        View::add_js('modules/sievas/scripts/usuarios_lmcc/main.js');
        View::add_js('modules/sievas/scripts/usuarios_lmcc/editar_usuario.js');
        View::render('usuarios/editar_usuario_lmcc.php', $vars);
    }
    
    
    
    public function programas_cee(){ 
        $usuario = Auth::info_usuario('usuario');
        $institucion = filter_input(INPUT_GET, 'i', FILTER_SANITIZE_STRING);
        $sql_persona = sprintf("select cod_persona from sys_usuario where username='%s'",
                $usuario);
        
        $persona = BD::$db->queryOne($sql_persona);
        
        $sql_programas = sprintf("SELECT
        eval_programas.programa,
        eval_programas.id,
        eval_instituciones.nom_institucion,
        eval_instituciones.id AS institucion_id
        FROM
        evaluacion_comite_revision
        INNER JOIN evaluacion ON evaluacion_comite_revision.cod_evaluacion = evaluacion.id
        INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
        INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
        WHERE
        evaluacion_comite_revision.cod_persona = %s AND
        eval_instituciones.id = %s 
        GROUP BY
        eval_programas.id", $persona, $institucion);
        
        $programas = BD::$db->queryAll($sql_programas);
        $vars = array();
        $vars['programas'] = $programas;
        
        View::render('usuarios/programas_cee.php', $vars);  
    }
    
    
    public function evaluaciones_cee(){ 
        $usuario = Auth::info_usuario('usuario');
        $programa = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);
        $sql_persona = sprintf("select cod_persona from sys_usuario where username='%s'",
                $usuario);
        
        $persona = BD::$db->queryOne($sql_persona);
        
        $sql_evaluaciones = sprintf("SELECT
        evaluacion.id,
        evaluacion.etiqueta,
        eval_programas.programa,
        eval_programas.id AS programa_id,
        eval_instituciones.nom_institucion,
        eval_instituciones.id AS institucion_id
        FROM
        evaluacion_comite_revision
        INNER JOIN evaluacion ON evaluacion_comite_revision.cod_evaluacion = evaluacion.id
        INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
        INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
        WHERE
        evaluacion_comite_revision.cod_persona = %s AND
        eval_programas.id = %s
        ", $persona, $programa);
        
        $evaluaciones = BD::$db->queryAll($sql_evaluaciones);
        
//        var_dump($evaluaciones);
        
        $vars = array();
        $vars['evaluaciones'] = $evaluaciones;
        
        View::render('usuarios/evaluaciones_cee.php', $vars);  
    }
    
    public function detalle_cee(){ 
        $evaluacion = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_STRING);
        
        $sql_evaluacion = sprintf("SELECT
        gen_persona.nombres,
        fotografia.ruta AS foto,
        cv.ruta AS cv,
        evaluacion.etiqueta,
        evaluacion.id AS ev_id,
        eval_programas.programa,
        eval_programas.id AS programa_id,
        eval_instituciones.nom_institucion,
        eval_instituciones.id AS institucion_id
        FROM
        comite
        INNER JOIN momento_evaluacion ON momento_evaluacion.id = comite.cod_momento_evaluacion
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN gen_persona ON gen_persona.id = comite.cod_persona
        LEFT JOIN gen_documentos AS fotografia ON gen_persona.foto = fotografia.id
        LEFT JOIN gen_documentos AS cv ON gen_persona.cv = cv.id
        INNER JOIN eval_programas ON eval_programas.id = evaluacion.cod_evaluado
        INNER JOIN eval_instituciones ON eval_programas.cod_institucion = eval_instituciones.id
        WHERE
        momento_evaluacion.cod_momento = 2 AND
        evaluacion.id = %s
        ", $evaluacion);
        
        $ev = BD::$db->queryAll($sql_evaluacion);
        $vars = array();
        $vars['evaluacion'] = $ev;
        
        View::render('usuarios/detalle_cee.php', $vars);  
    }
    
    public function registro(){
        View::render('usuarios/registro.php');      
    }
    
    public function cambiar_clave(){
        $msg = '';
        $username = $_GET['u'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $clave = filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING);                  
            if($username != '' && $clave != ''){
                $sql = sprintf("update sys_usuario set passwd='%s' where username='%s'",
                    md5($clave), $username);
                if(PEAR::isError(BD::$db->query($sql))){
                    $msg = 'No se pudo actualizar la clave del usuario';
                }
                else{
                    $msg = 'Se ha actualizado la clave del usuario';
                    header('Location: index.php?mod=sievas&controlador=usuarios&accion=listar_usuarios');
                }
            }
            else{
                $msg = 'No se pudo actualizar la clave del usuario';
            }

        }
        $vars['msg'] = $msg;
        $vars['username'] = $username;
        View::render('usuarios/cambiarclave.php', $vars);      
    }
    
    public function evaluador(){  
//        var_dump(Auth::info_usuario('usuario'));
        View::add_js('public/js/dropzone.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/evaluador.js');
        $id = $_GET['id'];
        if($id > 0){
            $sql_persona = 'SELECT
                    gen_persona.id,
                    gen_persona.nro_documento,
                    gen_persona.nombres,
                    gen_persona.primer_apellido,
                    gen_persona.segundo_apellido,
                    gen_persona.genero,
                    gen_persona.direccion,
                    gen_persona.telefono,
                    gen_persona.celular,
                    gen_persona.email,
                    gen_persona.fecha_nacimiento,
                    gen_persona.foto,
                    gen_persona.cod_estado_civil,
                    gen_persona.lugar_nacimiento,
                    gen_tipo_documento.abreviatura,
                    gen_paises.nom_pais,
                    gen_estado_civil.estado_civil,
                    evaluador.email,
                    gen_documentos.ruta
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            INNER JOIN gen_tipo_documento ON gen_persona.cod_tipo_documento = gen_tipo_documento.id
            INNER JOIN gen_paises ON gen_persona.lugar_nacimiento = gen_paises.id
            INNER JOIN gen_estado_civil ON gen_persona.cod_estado_civil = gen_estado_civil.id
            LEFT JOIN evaluador ON evaluador.cod_persona = gen_persona.id
            INNER JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
            where evaluador.id = '.BD::$db->quote($id);
            
            $persona = BD::$db->queryRow($sql_persona);

            
            $sql_info_academica = 'SELECT
            gen_persona_formacion.fecha,
            gen_persona_formacion.titulo,
            gen_persona_formacion.institucion,
            gen_persona_formacion.anio,
            niveles_formacion.nivel_formacion
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            INNER JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
            INNER JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
            WHERE
            sys_usuario.cod_persona = '.BD::$db->quote($persona['id']);


            $sql_info_experiencia = 'SELECT
            gen_persona_experiencia.institucion,
            gen_persona_experiencia.status_laboral,
            gen_persona_experiencia.periodo
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            INNER JOIN gen_persona_experiencia ON gen_persona_experiencia.cod_persona = gen_persona.id
            WHERE
            sys_usuario.cod_persona = '.BD::$db->quote($persona['id']);

            
            $persona_formacion = BD::$db->queryAll($sql_info_academica);
            $persona_experiencia = BD::$db->queryAll($sql_info_experiencia);
            $evaluador = array();
            $evaluador['info_personal'] = $persona;
            $evaluador['info_academica'] = $persona_formacion;
            $evaluador['info_experiencia'] = $persona_experiencia;
            $vars['evaluador'] = $evaluador;

            View::render('usuarios/evaluador.php', $vars);        
        }
        else{
           View::render('usuarios/evaluador.php');      
        }            
    }      
    
    public function experto(){  
        View::add_js('public/js/dropzone.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/experto.js');
        $id = $_GET['id'];
        if($id > 0){
            $sql_persona = 'SELECT
                    gen_persona.id,
                    gen_persona.nro_documento,
                    gen_persona.nombres,
                    gen_persona.primer_apellido,
                    gen_persona.segundo_apellido,
                    gen_persona.genero,
                    gen_persona.direccion,
                    gen_persona.telefono,
                    gen_persona.celular,
                    gen_persona.email,
                    gen_persona.fecha_nacimiento,
                    gen_persona.foto,
                    gen_persona.cod_estado_civil,
                    gen_persona.lugar_nacimiento,
                    gen_tipo_documento.abreviatura,
                    gen_paises.nom_pais,
                    gen_estado_civil.estado_civil,
                    evaluador.email,
                    gen_documentos.ruta
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            INNER JOIN gen_tipo_documento ON gen_persona.cod_tipo_documento = gen_tipo_documento.id
            INNER JOIN gen_paises ON gen_persona.lugar_nacimiento = gen_paises.id
            INNER JOIN gen_estado_civil ON gen_persona.cod_estado_civil = gen_estado_civil.id
            LEFT JOIN evaluador ON evaluador.cod_persona = gen_persona.id
            INNER JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
            where evaluador.id = '.BD::$db->quote($id);
            
            $persona = BD::$db->queryRow($sql_persona);

            
            $sql_info_academica = 'SELECT
            gen_persona_formacion.fecha,
            gen_persona_formacion.titulo,
            gen_persona_formacion.institucion,
            gen_persona_formacion.anio,
            niveles_formacion.nivel_formacion
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            INNER JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
            INNER JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
            WHERE
            sys_usuario.cod_persona = '.BD::$db->quote($persona['id']);


            $sql_info_experiencia = 'SELECT
            gen_persona_experiencia.institucion,
            gen_persona_experiencia.status_laboral,
            gen_persona_experiencia.periodo
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            INNER JOIN gen_persona_experiencia ON gen_persona_experiencia.cod_persona = gen_persona.id
            WHERE
            sys_usuario.cod_persona = '.BD::$db->quote($persona['id']);

            
            $persona_formacion = BD::$db->queryAll($sql_info_academica);
            $persona_experiencia = BD::$db->queryAll($sql_info_experiencia);
            $evaluador = array();
            $evaluador['info_personal'] = $persona;
            $evaluador['info_academica'] = $persona_formacion;
            $evaluador['info_experiencia'] = $persona_experiencia;
            $vars['evaluador'] = $evaluador;

            View::render('usuarios/experto.php', $vars);        
        }
        else{
           View::render('usuarios/experto.php');      
        }            
    }  
    
    public function autorizar(){
        View::add_js('public/js/bootbox.min.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/autorizar.js');
        
        $sql = "select sys_usuario.nombres, roles.idrol, sys_usuario.username from sys_usuario inner join sysusers on sys_usuario.username = sysusers.username join roles on sysusers.idrol = roles.id where creador is null";        
        $usuarios = BD::$db->queryAll($sql);        
        $vars['usuarios'] = $usuarios;
        View::render('usuarios/autorizar.php', $vars);        
    }
    
    public function autorizar_usuario(){
        header('Content-Type: application/json');
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $sql = sprintf("update sys_usuario set creador='%s' where username = '%s'", Auth::info_usuario('usuario'), $username);
        $rs = BD::$db->query($sql);
        if(PEAR::isError($rs)){
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se pudo autorizar el usuario";
            echo $sql;
        }
        else{
            echo json_encode(array('mensaje' => 'El usuario ha sido autorizado correctamente'));
        }
    }
    
    public function editar_evaluador(){
        View::add_js('public/js/dropzone.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/editar_evaluador.js');
        $id = $_GET['id'];
        if($id > 0){
            $sql_persona = 'SELECT
                    gen_persona.id,
                    gen_persona.nro_documento,
                    gen_persona.nombres,
                    gen_persona.primer_apellido,
                    gen_persona.segundo_apellido,
                    gen_persona.genero,
                    gen_persona.direccion,
                    gen_persona.telefono,
                    gen_persona.celular,
                    gen_persona.email,
                    gen_persona.fecha_nacimiento,
                    gen_persona.foto,
                    gen_persona.celular,
                    gen_persona.skype,
                    gen_persona.cod_estado_civil,
                    gen_persona.lugar_nacimiento,
                    gen_tipo_documento.abreviatura,
                    gen_persona.cod_tipo_documento,
                    gen_persona.nro_documento,
                    gen_paises.nom_pais,
                    gen_estado_civil.estado_civil,
                    evaluador.email as email_grana,
                    evaluador.id as evaluador_id,                    
                    gen_documentos.ruta,
                    cv.ruta as cv_ruta
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            LEFT JOIN gen_tipo_documento ON gen_persona.cod_tipo_documento = gen_tipo_documento.id
            LEFT JOIN gen_paises ON gen_persona.lugar_nacimiento = gen_paises.id
            LEFT JOIN gen_estado_civil ON gen_persona.cod_estado_civil = gen_estado_civil.id
            LEFT JOIN evaluador ON evaluador.cod_persona = gen_persona.id
            LEFT JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
            LEFT JOIN gen_documentos as cv ON gen_persona.cv = cv.id
            where evaluador.id = '.BD::$db->quote($id);            
            
            $persona = BD::$db->queryRow($sql_persona);
            
            $sql_info_academica = 'SELECT
            gen_persona_formacion.fecha,
            gen_persona_formacion.titulo,
            gen_persona_formacion.institucion,
            gen_persona_formacion.anio,
            evaluador.publicaciones,
            evaluador.categoria_academica,
            niveles_formacion.nivel_formacion,
            niveles_formacion.id as nivel_formacion
            FROM
            sys_usuario
            LEFT JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            LEFT JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
            LEFT JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
            INNER JOIN evaluador ON evaluador.cod_persona = gen_persona.id
            WHERE
            sys_usuario.cod_persona = '.BD::$db->quote($persona['id']).' order by fecha desc';


            
            $sql_info_experiencia = 'SELECT
            gen_persona_experiencia.institucion,
            gen_persona_experiencia.status_laboral,
            gen_persona_experiencia.periodo
            FROM
            sys_usuario
            LEFT JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            LEFT JOIN gen_persona_experiencia ON gen_persona_experiencia.cod_persona = gen_persona.id
            WHERE
            sys_usuario.cod_persona = '.BD::$db->quote($persona['id']);

            
            $persona_formacion = BD::$db->queryAll($sql_info_academica);
            $persona_experiencia = BD::$db->queryAll($sql_info_experiencia);
            $evaluador = array();
            $evaluador['info_personal'] = $persona;
            $evaluador['info_academica'] = $persona_formacion;
            $evaluador['info_experiencia'] = $persona_experiencia;
            $vars['evaluador'] = $evaluador;
            
            View::render('usuarios/editar_evaluador.php', $vars);        
        }
        else{
            
        }            
    }
    
    public function agregar_docente(){  
        View::add_js('public/js/dropzone.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/profesor.js');
        View::render('usuarios/agregar_docente.php');         
    }  
    
    public function editar_docente(){  
        View::add_js('public/js/dropzone.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_css('public/css/fa410/css/font-awesome.min.css');
        View::add_js('public/summernote/summernote.min.js');
        View::add_js('public/summernote/summernote-es-ES.js');
        View::add_css('public/summernote/summernote.css');
        View::add_js('public/summernote/helper.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/profesor.js');
        
        $id = $_GET['id'];
        
        $sql_data_docente = sprintf("SELECT
	eval_profesores.id,
	gen_persona.nombres,
	gen_persona.nro_documento,
	gen_persona.cod_tipo_documento,
	gen_persona.fecha_nacimiento,
	gen_persona.genero,
	gen_persona.email,
	gen_persona.direccion,
	gen_persona.telefono,
	gen_documentos.ruta,
	eval_instituciones.id AS institucion_id,
	gen_paises.id AS pais_institucion
        FROM
        eval_profesores
        INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
        INNER JOIN eval_instituciones ON eval_profesores.cod_institucion = eval_instituciones.id
        LEFT JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
        LEFT JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
        WHERE
        eval_profesores.id = %s", $id);
        

        
        $data_docente = BD::$db->queryRow($sql_data_docente);
        $vars['data_docente'] = $data_docente;
        
        
        $sql_formacion_academica = sprintf("SELECT
        gen_persona_formacion.cod_nivel_formacion,
        gen_persona_formacion.titulo,
        gen_persona_formacion.institucion,
        gen_persona_formacion.anio
        FROM
        eval_profesores
        INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
        INNER JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
        where eval_profesores.id = %s", $id);
        
        $data_formacion_academica = BD::$db->queryAll($sql_formacion_academica);
        $vars['data_formacion_academica'] = $data_formacion_academica;
        
        $sql_experiencia = sprintf("SELECT
        gen_persona_experiencia.institucion,
        gen_persona_experiencia.status_laboral,
        gen_persona_experiencia.periodo
        FROM
        eval_profesores
        INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
        INNER JOIN gen_persona_experiencia ON gen_persona_experiencia.cod_persona = gen_persona.id
        where eval_profesores.id = %s", $id);
        
        $data_experiencia = BD::$db->queryAll($sql_experiencia);
        $vars['data_experiencia'] = $data_experiencia;
        
        View::render('usuarios/editar_docente.php', $vars);         
    }  
    
    public function evaluador_interno(){           
        View::render('usuarios/evaluador_interno.php');         
    }  

    
    public function listar_evaluadores(){
         View::add_js('modules/sievas/scripts/sieva_evaluador/main.js');
         View::add_js('modules/sievas/scripts/sieva_evaluador/listar.js');
         View::render('evaluador/listar.php'); 
    }
    
    public function listar_docentes(){
         View::add_js('modules/sievas/scripts/usuarios/main.js');
         View::add_js('modules/sievas/scripts/usuarios/listar_maestros.js');
         View::render('profesor/listar.php'); 
    }
    
    public function get_dt_evaluadores(){
        $model = new GeneralModel();
        $model->listar();
    }
    
    public function get_dt_profesores(){
        $model = new GeneralModel();
        $model->listar();
    }    
    
    public function eliminar_evaluador(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from evaluador where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el evaluador";
            }
            else{
                echo json_encode(array('mensaje' => 'El evaluador fue eliminado correctamente'));
            }
        }
    }
    
    
    public function get_estados_civiles(){
        header('Content-Type: application/json');  
        $sql_estados_civiles = "select * from gen_estado_civil";
        $estados_civiles = BD::$db->queryAll($sql_estados_civiles);      
        echo json_encode($estados_civiles);
    }
    
    public function get_estado_civil(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_estado_civil = "select * from gen_estado_civil where id=$id";
        $estado_civil = BD::$db->queryRow($sql_estado_civil);
        echo json_encode($estado_civil);
    }
    
    public function get_cargos_evaluador(){
        header('Content-Type: application/json');  
        $tipo_evaluacion = filter_input(INPUT_GET, 'tipo_evaluacion', FILTER_SANITIZE_STRING);
        $sql_cargos_evaluador = "select * from evaluador_cargos where tipo_evaluacion = '$tipo_evaluacion'";
        $cargos_evaluador = BD::$db->queryAll($sql_cargos_evaluador);      
        echo json_encode($cargos_evaluador);
    }
    
    public function get_cargo_evaluador(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_cargo_evaluador = "select * from evaluador_cargos where id=$id";
        $cargo_evaluador = BD::$db->queryRow($sql_cargo_evaluador);
        echo json_encode($cargo_evaluador);
    }
    
    public function guardar_evaluador(){
        header('Content-Type: application/json');
        
        //id
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        $formacion = filter_input(INPUT_POST, 'formacion', FILTER_SANITIZE_STRING);
        //personal
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
        $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_STRING);
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_STRING);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $estado_civil = filter_input(INPUT_POST, 'estado_civil', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $email_personal = filter_input(INPUT_POST, 'email_personal', FILTER_SANITIZE_STRING);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING); 
        $foto = filter_input(INPUT_POST, 'foto', FILTER_SANITIZE_NUMBER_INT);
        $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_NUMBER_INT);
        $skype = filter_input(INPUT_POST, 'skype', FILTER_SANITIZE_NUMBER_INT);
        
        //tipo evaluador
        $tipo_evaluador = filter_input(INPUT_POST, 'titulo_evaluador', FILTER_SANITIZE_STRING); 
        $cargo_evaluador = filter_input(INPUT_POST, 'cargo_evaluador', FILTER_SANITIZE_NUMBER_INT); 
        
        //info academica
        $nivel_formacion = filter_input(INPUT_POST, 'nivel_formacion', FILTER_SANITIZE_NUMBER_INT);
        $titulo_evaluador = filter_input(INPUT_POST, 'titulo_evaluador', FILTER_SANITIZE_STRING); 
        $institucion_titulo = filter_input(INPUT_POST, 'institucion_titulo', FILTER_SANITIZE_STRING);
        $anio_egreso = filter_input(INPUT_POST, 'anio_egreso', FILTER_SANITIZE_STRING);
        $publicaciones = filter_input(INPUT_POST, 'publicaciones', FILTER_SANITIZE_STRING); 
        $escalafon = filter_input(INPUT_POST, 'escalafon', FILTER_SANITIZE_STRING);
        
        //experiencia
        $status_laboral = $_POST['exp_status_laboral'];
        $institucion_experiencia = $_POST['exp_institucion_experiencia'];
        $periodo_experiencia = $_POST['exp_periodo'];
        
        foreach($status_laboral as $status_laboral_idx => $s){
            if($status_laboral[$status_laboral_idx] === ''){
                unset($status_laboral[$status_laboral_idx]);
                unset($institucion_experiencia[$status_laboral_idx]);
                unset($periodo_experiencia[$status_laboral_idx]);
            }
        }
        
        foreach($institucion_experiencia as $institucion_experiencia_idx => $s){
            if($institucion_experiencia[$institucion_experiencia_idx] === ''){
                unset($institucion_experiencia[$institucion_experiencia_idx]);
                unset($status_laboral[$institucion_experiencia_idx]);
                unset($periodo_experiencia[$institucion_experiencia_idx]);
            }
        }
        
        foreach($periodo_experiencia as $periodo_experiencia_idx => $s){
            if($periodo_experiencia[$periodo_experiencia_idx] === ''){
                unset($institucion_experiencia[$periodo_experiencia_idx]);
                unset($status_laboral[$periodo_experiencia_idx]);
                unset($periodo_experiencia[$periodo_experiencia_idx]);
            }
        }

        
        //datos acceso
        $usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING);
        
        if($id > 0){
            $sql_persona = "select cod_persona from evaluador where id=$id";
            $persona = BD::$db->queryOne($sql_persona);
            
            $model = new gen_persona();
            $model->begin();
            $valid = true;
            $model->nro_documento = $documento;
            if($tipodocumento > 0){
                $model->cod_tipo_documento = $tipodocumento;
            }        
            $model->nombres = $nombre;
            $model->genero = $genero;
            $model->direccion = $direccion;
            $model->telefono = $telefono;
            $model->email = $email_personal;
            $model->fecha_nacimiento = $fecha_nacimiento;
             if($estado_civil > 0){
                $model->cod_estado_civil = $estado_civil;
            }
            if($pais > 0){
                $model->lugar_nacimiento = $pais;
            }            
            if($foto > 0){
                $model->foto = $foto;
            }
            $model->celular = $celular;
            $model->skype = $skype;
            $model->id = $persona;

            if($model->update()){
                $model_evaluador = new evaluador();            
                $model_evaluador->publicaciones = $publicaciones;
                $model_evaluador->email = $email.'@certification-grana.org';
                $model_evaluador->cod_persona = $model->id;
                $model_evaluador->categoria_academica = $escalafon;
                $model_evaluador->id = $id;
                if($model_evaluador->update()){
                    $sql_ef = 'select id from gen_persona_formacion where cod_persona='.$model->id;
                    $ef = BD::$db->queryOne($sql_ef);
                    $model_evaluador_formacion = new gen_persona_formacion();
                    $model_evaluador_formacion->id = $ef;
                    $model_evaluador_formacion->cod_nivel_formacion = $nivel_formacion;
                    $model_evaluador_formacion->titulo = $titulo_evaluador;
                    $model_evaluador_formacion->institucion = $institucion_titulo;
                    $model_evaluador_formacion->anio = $anio_egreso;
                    $model_evaluador_formacion->cod_persona = $model->id;
                    
                    if($model_evaluador_formacion->update()){
                        //experiencia evaluador
                        $sql_delete = 'delete from gen_persona_experiencia where cod_persona='.$model->id;
                        $rs = BD::$db->query($sql_delete);
                        if(!PEAR::isError($rs)){
                          $v = true;
                          foreach($periodo_experiencia as $key => $val){
                                $model_evaluador_experiencia = new gen_persona_experiencia();
                                $model_evaluador_experiencia->institucion = $institucion_experiencia[$key];
                                $model_evaluador_experiencia->status_laboral = $status_laboral[$key];
                                $model_evaluador_experiencia->periodo = $periodo_experiencia[$key];
                                $model_evaluador_experiencia->cod_persona  = $model->id;
                                if(!$model_evaluador_experiencia->save()){
                                    $v = false;      
                                }
                            }
                            if(!$v){
                                $valid = false;
                            }
                        }                        
                    }
                    else{
                        $valid = false;
                    }   
                }
                else{
                    $valid = false; 
                }
            }
            else{
                $valid = false;   
            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'El evaluador ha sido actualizado correctamente'));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido actualizar el evaluador";
            }
        }
        else{
            $model = new gen_persona();
            $model->begin();
            $valid = true;
            $model->nro_documento = $documento;
            if($tipodocumento > 0){
                $model->cod_tipo_documento = $tipodocumento;
            }        
            $model->nombres = $nombre;
            $model->genero = $genero;
            $model->direccion = $direccion;
            $model->telefono = $telefono;
            $model->email = $email_personal;
            $model->fecha_nacimiento = $fecha_nacimiento;
            if($estado_civil > 0){
                $model->cod_estado_civil = $estado_civil;
            }
            if($pais > 0){
                $model->lugar_nacimiento = $pais;
            }   
            if($foto > 0){
                $model->foto = $foto;
            }
            $model->celular = $celular;
            $model->skype = $skype;

            if($model->save()){
                $model_evaluador = new evaluador();            
                $model_evaluador->publicaciones = $publicaciones;
                $model_evaluador->email = $email.'@certification-grana.org';
                $model_evaluador->categoria_academica = $escalafon;
                $model_evaluador->cod_persona = $model->id;
                if($model_evaluador->save()){
                    //formacion evaluador
                    $model_evaluador_formacion = new gen_persona_formacion();
                    if($nivel_formacion > 0){
                        $model_evaluador_formacion->cod_nivel_formacion = $nivel_formacion;
                    }                    
                    $model_evaluador_formacion->titulo = $titulo_evaluador;
                    $model_evaluador_formacion->institucion = $institucion_titulo;
                    $model_evaluador_formacion->anio = $anio_egreso;
                    $model_evaluador_formacion->cod_persona = $model->id;
                    if($model_evaluador_formacion->save()){
                        //experiencia evaluador
                        $v = true;
                        foreach($periodo_experiencia as $key => $val){
                            $model_evaluador_experiencia = new gen_persona_experiencia();
                            $model_evaluador_experiencia->institucion = $institucion_experiencia[$key];
                            $model_evaluador_experiencia->status_laboral = $status_laboral[$key];
                            $model_evaluador_experiencia->periodo = $periodo_experiencia[$key];
                            $model_evaluador_experiencia->cod_persona  = $model->id;
                            if(!$model_evaluador_experiencia->save()){
                                $v = false;      
                            }
                        }
                        if($v){   
                            $model_usuario = new sys_usuario();
                            $model_usuario->username = $usuario;
                            $model_usuario->passwd = md5($usuario);
                            $model_usuario->cod_persona = $model->id;
                            $model_usuario->autorizado = 1;
                            $model_usuario->nombres = $nombre;
                            if(Auth::info_usuario('rol') == '1')
                                $model_usuario->creador = Auth::info_usuario('usuario');
                            if($model_usuario->save()){
                                $model_usuario_rol = new sysusers();
                                $model_usuario_rol->idrol = 2;
                                $model_usuario_rol->username = $usuario;
                                $model_usuario_rol->activo = 'SI';
                                if($model_usuario_rol->save()){
    //                                $model_usuario_acceso = new sysusers_acceso();
    //                                $model_usuario_acceso->idrol = 2;
    //                                $model_usuario_acceso->username = $usuario;
    //                                $model_usuario_acceso->cod_acceso = 0;
    //                                $model_usuario_acceso->tipo_acceso = 5;
    //                                if(!$model_usuario_acceso->save()){
    //                                    $valid = false;   
    //                                }
                                }
                                else{
                                    $valid = false;
                                }
                            }
                            else{
                                $valid = false;
                            }
                        }
                        else{
                            $valid = false;
                        }
                    }
                    else{
                        $valid = false;
                    }   
                }
                else{
                    $valid = false; 
                }
            }
            else{
                $valid = false;  
                echo $model->error_sql();
            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'El evaluador ha sido creado correctamente'));
                if(Auth::info_usuario('usuario') === null){
                    MailHelper::mail('admin_group', 'Se ha registrado un nuevo evaluador en SIEVAS. Revise el panel de autorización de usuarios para permitirle su acceso.', 'Nuevo evaluador en SIEVAS');
                    MailHelper::mail($model->email, 'Bienvenido al sistema SIEVAS. Estos son sus datos de usuario: <br/><br/> Nombre de usuario: '.$model_usuario->username.' <br/> Password: '.$model_usuario->username.'<br/><br/> En este momento su usuario se encuentra en proceso de validación. Se le enviará una notificación por correo cuando su usuario sea autorizado. Luego de esto podrá acceder al sistema SIEVAS.', 'Bienvenido a SIEVAS!');
                }
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido crear el evaluador";
            }
        }
        
        
    }
    
    public function guardar_experto(){
        header('Content-Type: application/json');
        
        //id
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        $formacion = filter_input(INPUT_POST, 'formacion', FILTER_SANITIZE_STRING);
        //personal
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
        $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_STRING);
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_STRING);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $estado_civil = filter_input(INPUT_POST, 'estado_civil', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $email_personal = filter_input(INPUT_POST, 'email_personal', FILTER_SANITIZE_STRING);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING); 
        $foto = filter_input(INPUT_POST, 'foto', FILTER_SANITIZE_NUMBER_INT);
        $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_NUMBER_INT);
        $skype = filter_input(INPUT_POST, 'skype', FILTER_SANITIZE_NUMBER_INT);
        
        //tipo evaluador
        $tipo_evaluador = filter_input(INPUT_POST, 'titulo_evaluador', FILTER_SANITIZE_STRING); 
        $cargo_evaluador = filter_input(INPUT_POST, 'cargo_evaluador', FILTER_SANITIZE_NUMBER_INT); 
        
        //info academica
        $nivel_formacion = filter_input(INPUT_POST, 'nivel_formacion', FILTER_SANITIZE_NUMBER_INT);
        $titulo_evaluador = filter_input(INPUT_POST, 'titulo_evaluador', FILTER_SANITIZE_STRING); 
        $institucion_titulo = filter_input(INPUT_POST, 'institucion_titulo', FILTER_SANITIZE_STRING);
        $anio_egreso = filter_input(INPUT_POST, 'anio_egreso', FILTER_SANITIZE_STRING);
        $publicaciones = filter_input(INPUT_POST, 'publicaciones', FILTER_SANITIZE_STRING); 
        $escalafon = filter_input(INPUT_POST, 'escalafon', FILTER_SANITIZE_STRING);
        
        //experiencia
        $status_laboral = $_POST['exp_status_laboral'];
        $institucion_experiencia = $_POST['exp_institucion_experiencia'];
        $periodo_experiencia = $_POST['exp_periodo'];
        
        foreach($status_laboral as $status_laboral_idx => $s){
            if($status_laboral[$status_laboral_idx] === ''){
                unset($status_laboral[$status_laboral_idx]);
                unset($institucion_experiencia[$status_laboral_idx]);
                unset($periodo_experiencia[$status_laboral_idx]);
            }
        }
        
        foreach($institucion_experiencia as $institucion_experiencia_idx => $s){
            if($institucion_experiencia[$institucion_experiencia_idx] === ''){
                unset($institucion_experiencia[$institucion_experiencia_idx]);
                unset($status_laboral[$institucion_experiencia_idx]);
                unset($periodo_experiencia[$institucion_experiencia_idx]);
            }
        }
        
        foreach($periodo_experiencia as $periodo_experiencia_idx => $s){
            if($periodo_experiencia[$periodo_experiencia_idx] === ''){
                unset($institucion_experiencia[$periodo_experiencia_idx]);
                unset($status_laboral[$periodo_experiencia_idx]);
                unset($periodo_experiencia[$periodo_experiencia_idx]);
            }
        }

        
        //datos acceso
        $usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING);
        
        if($id > 0){
            $sql_persona = "select cod_persona from evaluador where id=$id";
            $persona = BD::$db->queryOne($sql_persona);
            
            $model = new gen_persona();
            $model->begin();
            $valid = true;
            $model->nro_documento = $documento;
            if($tipodocumento > 0){
                $model->cod_tipo_documento = $tipodocumento;
            }        
            $model->nombres = $nombre;
            $model->genero = $genero;
            $model->direccion = $direccion;
            $model->telefono = $telefono;
            $model->email = $email_personal;
            $model->fecha_nacimiento = $fecha_nacimiento;
             if($estado_civil > 0){
                $model->cod_estado_civil = $estado_civil;
            }
            if($pais > 0){
                $model->lugar_nacimiento = $pais;
            }            
            if($foto > 0){
                $model->foto = $foto;
            }
            $model->celular = $celular;
            $model->skype = $skype;
            $model->id = $persona;

            if($model->update()){
                $model_evaluador = new experto();            
                $model_evaluador->publicaciones = $publicaciones;
                $model_evaluador->email = $email.'@certification-grana.org';
                $model_evaluador->cod_persona = $model->id;
                $model_evaluador->categoria_academica = $escalafon;
                $model_evaluador->id = $id;
                if($model_evaluador->update()){
                    $sql_ef = 'select id from gen_persona_formacion where cod_persona='.$model->id;
                    $ef = BD::$db->queryOne($sql_ef);
                    $model_evaluador_formacion = new gen_persona_formacion();
                    $model_evaluador_formacion->id = $ef;
                    $model_evaluador_formacion->cod_nivel_formacion = $nivel_formacion;
                    $model_evaluador_formacion->titulo = $titulo_evaluador;
                    $model_evaluador_formacion->institucion = $institucion_titulo;
                    $model_evaluador_formacion->anio = $anio_egreso;
                    $model_evaluador_formacion->cod_persona = $model->id;
                    
                    if($model_evaluador_formacion->update()){
                        //experiencia evaluador
                        $sql_delete = 'delete from gen_persona_experiencia where cod_persona='.$model->id;
                        $rs = BD::$db->query($sql_delete);
                        if(!PEAR::isError($rs)){
                          $v = true;
                          foreach($periodo_experiencia as $key => $val){
                                $model_evaluador_experiencia = new gen_persona_experiencia();
                                $model_evaluador_experiencia->institucion = $institucion_experiencia[$key];
                                $model_evaluador_experiencia->status_laboral = $status_laboral[$key];
                                $model_evaluador_experiencia->periodo = $periodo_experiencia[$key];
                                $model_evaluador_experiencia->cod_persona  = $model->id;
                                if(!$model_evaluador_experiencia->save()){
                                    $v = false;  
                                }
                            }
                            if(!$v){
                                $valid = false;
                            }
                        }                        
                    }
                    else{
                        $valid = false;
                    }   
                }
                else{
                    $valid = false; 
                }
            }
            else{
                $valid = false;
            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'El evaluador ha sido actualizado correctamente'));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido actualizar el evaluador";
            }
        }
        else{
            $model = new gen_persona();
            $model->begin();
            $valid = true;
            $model->nro_documento = $documento;
            if($tipodocumento > 0){
                $model->cod_tipo_documento = $tipodocumento;
            }        
            $model->nombres = $nombre;
            $model->genero = $genero;
            $model->direccion = $direccion;
            $model->telefono = $telefono;
            $model->email = $email_personal;
            $model->fecha_nacimiento = $fecha_nacimiento;
            if($estado_civil > 0){
                $model->cod_estado_civil = $estado_civil;
            }
            if($pais > 0){
                $model->lugar_nacimiento = $pais;
            }   
            if($foto > 0){
                $model->foto = $foto;
            }
            $model->celular = $celular;
            $model->skype = $skype;

            if($model->save()){
                $model_evaluador = new evaluador();            
                $model_evaluador->publicaciones = $publicaciones;
                $model_evaluador->email = $email.'@certification-grana.org';
                $model_evaluador->categoria_academica = $escalafon;
                $model_evaluador->cod_persona = $model->id;
                if($model_evaluador->save()){
                    //formacion evaluador
                    $model_evaluador_formacion = new gen_persona_formacion();
                    if($nivel_formacion > 0){
                        $model_evaluador_formacion->cod_nivel_formacion = $nivel_formacion;
                    }                    
                    $model_evaluador_formacion->titulo = $titulo_evaluador;
                    $model_evaluador_formacion->institucion = $institucion_titulo;
                    $model_evaluador_formacion->anio = $anio_egreso;
                    $model_evaluador_formacion->cod_persona = $model->id;
                    if($model_evaluador_formacion->save()){
                        //experiencia evaluador
                        $v = true;
                        foreach($periodo_experiencia as $key => $val){
                            $model_evaluador_experiencia = new gen_persona_experiencia();
                            $model_evaluador_experiencia->institucion = $institucion_experiencia[$key];
                            $model_evaluador_experiencia->status_laboral = $status_laboral[$key];
                            $model_evaluador_experiencia->periodo = $periodo_experiencia[$key];
                            $model_evaluador_experiencia->cod_persona  = $model->id;
                            if(!$model_evaluador_experiencia->save()){
                                $v = false;      
                            }
                        }
                        if($v){   
                            $model_usuario = new sys_usuario();
                            $model_usuario->username = $usuario;
                            $model_usuario->passwd = md5($usuario);
                            $model_usuario->cod_persona = $model->id;
                            $model_usuario->autorizado = 1;
                            $model_usuario->nombres = $nombre;
                            if(Auth::info_usuario('rol') == '1')
                                $model_usuario->creador = Auth::info_usuario('usuario');
                            if($model_usuario->save()){
                                $model_usuario_rol = new sysusers();
                                $model_usuario_rol->idrol = 4;
                                $model_usuario_rol->username = $usuario;
                                $model_usuario_rol->activo = 'SI';
                                if($model_usuario_rol->save()){
    //                                $model_usuario_acceso = new sysusers_acceso();
    //                                $model_usuario_acceso->idrol = 2;
    //                                $model_usuario_acceso->username = $usuario;
    //                                $model_usuario_acceso->cod_acceso = 0;
    //                                $model_usuario_acceso->tipo_acceso = 5;
    //                                if(!$model_usuario_acceso->save()){
    //                                    $valid = false;   
    //                                }
                                }
                                else{
                                    $valid = false;
                                }
                            }
                            else{
                                $valid = false;
                            }
                        }
                        else{
                            $valid = false;
                        }
                    }
                    else{
                        $valid = false;
                    }   
                }
                else{
                    $valid = false; 
                }
            }
            else{
                $valid = false;  
                echo $model->error_sql();
            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'El evaluador ha sido creado correctamente'));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido crear el evaluador";
            }
        }
        
        
    }
    
    public function listar_expertos(){
        
    }
    
    
    
    public function guardar_imagen(){
         header('Content-Type: application/json');
         date_default_timezone_set('America/Bogota');
         $now = new DateTime();
         $valid = true;
         $archivo = $_FILES['archivo'];
         if($archivo['error'] === UPLOAD_ERR_OK){
            if($archivo['size'] < 10000000){
                $rel_path = 'public/files/'.$now->getTimestamp().'-'.$archivo['name'];
                $real_path = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$rel_path;
                if(move_uploaded_file($archivo['tmp_name'], $real_path)){
                    $model = new gen_documentos();
                    $model->begin();
                    $model->ruta = $rel_path;
                    $model->nombre = $archivo['name'];
                    if(!$model->save()){
                       $valid = false;
                    }
                   
                    if($valid){
                        $model->commit();
                        echo json_encode(array('status' => 'ok', 'id' => $model->id, 'ruta' => $rel_path));
                    }
                    else{
                       header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                       echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                    }
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo json_encode(array('error' => 'No se pudo guardar el archivo'));
                 }                
            }
            else{
                //el archivo no puede superar los 10 Mb
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo json_encode(array('error' => 'El archivo no puede superar los 10 Mb'));
            }            
        }
        else{
           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
           echo json_encode(array('error' => 'No se pudo guardar el archivo'));
        }
    }
    
    public function guardar_profesor(){
        header('Content-Type: application/json');
        //personal
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
        $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_STRING);
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_STRING);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $email_personal = filter_input(INPUT_POST, 'email_personal', FILTER_SANITIZE_STRING);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING); 
        $foto = filter_input(INPUT_POST, 'foto', FILTER_SANITIZE_NUMBER_INT);
        
        //usuario
        $nombre_usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING); 
        
        //datos profesor
        $institucion = filter_input(INPUT_POST, 'institucion', FILTER_SANITIZE_NUMBER_INT);
        $publicaciones = filter_input(INPUT_POST, 'publicaciones', FILTER_SANITIZE_STRING);
       
        //info academica
        $formacion_academica = $_POST['formacion_academica'];
        
        //experiencia
        $experiencia = $_POST['experiencia'];
        
        //id docente
        $docente_id = filter_input(INPUT_POST, 'docente_id', FILTER_SANITIZE_NUMBER_INT);

        if($docente_id == 0){
            //crear
            $model = new gen_persona();
            $model->begin();
            $valid = true;
            $model->nombres = $nombre;
            $model->nro_documento = $documento;
            if($tipodocumento > 0){
                $model->cod_tipo_documento = $tipodocumento;
            } 
            $model->lugar_nacimiento = $pais;
            $model->fecha_nacimiento = $fecha_nacimiento;
            $model->genero = $genero;
            $model->email = $email_personal;
            $model->direccion = $direccion;
            $model->telefono = $telefono;
            if($foto > 0)
            $model->foto = $foto;         

            if($model->save()){
                $model_profesor = new eval_profesores();            
                $model_profesor->publicaciones = $publicaciones;
                $model_profesor->cod_persona = $model->id;
                $model_profesor->cod_institucion = $institucion;
                if($model_profesor->save()){
                    $formacion_academica = json_decode($formacion_academica);
                    foreach($formacion_academica as $fa){
                        //validar
                        $model_formacion_academica = new gen_persona_formacion();
                        $model_formacion_academica->cod_persona = $model->id;
                        $model_formacion_academica->cod_nivel_formacion = $fa->nivel_formacion;
                        $model_formacion_academica->titulo = $fa->titulo_profesor;
                        $model_formacion_academica->institucion = $fa->institucion_titulo;
                        $model_formacion_academica->anio = $fa->anio_egreso;
                        $model_formacion_academica->save();
                    }

                    $experiencia = json_decode($experiencia);
                    foreach($experiencia as $e){
                        $model_experiencia = new gen_persona_experiencia();
                        $model_experiencia->institucion = $e->institucion;
                        $model_experiencia->status_laboral = $e->status_laboral;
                        $model_experiencia->periodo = $e->periodo;
                        $model_experiencia->cod_persona = $model->id;
                        $model_experiencia->save();
                    }
                    $model_sys_usuario = new sys_usuario();
                    $model_sys_usuario->username = $nombre_usuario;
                    $model_sys_usuario->cod_persona = $model->id;
                    $model_sys_usuario->passwd = md5($nombre_usuario);
                    $model_sys_usuario->autorizado = 1;
                    $model_sys_usuario->creador = Auth::info_usuario('usuario');
                    if($model_sys_usuario->save()){
                        $model_sysusers = new sysusers();
                        $model_sysusers->idrol = 3;
                        $model_sysusers->username = $nombre_usuario;
                        $model_sysusers->activo = 'SI';
                        if(!$model_sysusers->save()){
                            $valid = false;
                            echo "1";
                        }
                    }
                }
                else{
                    $valid = false;
                    echo "2";
                }
            }
            else{
                $valid = false;
                echo $model->error_sql();

            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'El docente ha sido creado correctamente', 'usuario' => $model_sys_usuario->username));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido crear el docente";
            }
        }
        else{
            //editar
            $sql_persona = sprintf("SELECT
            gen_persona.id
            FROM
            eval_profesores
            INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
            where eval_profesores.id = %s", $docente_id);
            
            $persona = BD::$db->queryOne($sql_persona);
            
            
            $model = new gen_persona();
            $model->begin();
            $valid = true;
            $model->id = $persona;
            $model->nombres = $nombre;
            $model->nro_documento = $documento;
            if($tipodocumento > 0){
                $model->cod_tipo_documento = $tipodocumento;
            } 
            $model->lugar_nacimiento = $pais;
            $model->fecha_nacimiento = $fecha_nacimiento;
            $model->genero = $genero;
            $model->email = $email_personal;
            $model->direccion = $direccion;
            $model->telefono = $telefono;
            if($foto > 0)
            $model->foto = $foto;         

            if($model->update()){
                $model_profesor = new eval_profesores();       
                $model_profesor->id = $docente_id;
                $model_profesor->publicaciones = $publicaciones;
                $model_profesor->cod_persona = $model->id;
                $model_profesor->cod_institucion = $institucion;
                if($model_profesor->update()){
                    $sql_delete_fa = sprintf("delete from gen_persona_formacion where cod_persona = %s", $model->id);
                    $query_fa = BD::$db->query($sql_delete_fa);
                    $sql_delete_ex = sprintf("delete from gen_persona_experiencia where cod_persona = %s", $model->id);
                    $query_ex = BD::$db->query($sql_delete_ex);
                    
                    $formacion_academica = json_decode($formacion_academica);
                    foreach($formacion_academica as $fa){
                        //validar
                        $model_formacion_academica = new gen_persona_formacion();
                        $model_formacion_academica->cod_persona = $model->id;
                        $model_formacion_academica->cod_nivel_formacion = $fa->nivel_formacion;
                        $model_formacion_academica->titulo = $fa->titulo_profesor;
                        $model_formacion_academica->institucion = $fa->institucion_titulo;
                        $model_formacion_academica->anio = $fa->anio_egreso;
                        $model_formacion_academica->save();
                    }

                    $experiencia = json_decode($experiencia);
                    foreach($experiencia as $e){
                        $model_experiencia = new gen_persona_experiencia();
                        $model_experiencia->institucion = $e->institucion;
                        $model_experiencia->status_laboral = $e->status_laboral;
                        $model_experiencia->periodo = $e->periodo;
                        $model_experiencia->cod_persona = $model->id;
                        $model_experiencia->save();
                    }                  
                }
                else{
                    $valid = false;
                }
            }
            else{
                $valid = false;

            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'El docente ha sido actualizado correctamente', 'usuario' => $model_sys_usuario->username));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido actualizar el docente";
            }
        }
        
    }
    
    public function get_programas(){
        header('Content-Type: application/json');   
        $institucion = filter_input(INPUT_GET, 'institucion', FILTER_SANITIZE_NUMBER_INT);
        $programas = array();
        if($institucion > 0){
            $sql_programas = "select * from eval_programas where cod_institucion=$institucion";
            $programas = BD::$db->queryAll($sql_programas);
        }        
        echo json_encode($programas);
    }
    
    public function get_programa(){
        header('Content-Type: application/json');      
        $programa = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_programa = "select * from eval_programas where id=$programa";
        $programa = BD::$db->queryRow($sql_programa);
        echo json_encode($programa);
    }
    
    public function listar_usuarios(){
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/listar_usuarios.js');
        View::render('usuarios/listar.php'); 
    }
    
    public function get_dt_usuarios(){
        $model = new GeneralModel();
        $model->listar();
    }
    
    public function agregar_usuario(){
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/agregar_usuario.js');
        
        $sql_roles = 'select * from roles';
        $roles = BD::$db->queryAll($sql_roles);
        $vars['roles'] = $roles;
        
        View::render('usuarios/agregar_usuario.php', $vars); 
    }
    
    public function editar_usuario(){
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('public/js/bootstrap-datepicker.js');
        View::add_js('modules/sievas/scripts/usuarios/main.js');
        View::add_js('modules/sievas/scripts/usuarios/agregar_usuario.js');
        
        $sql_roles = 'select * from roles';
        $roles = BD::$db->queryAll($sql_roles);
        $vars['roles'] = $roles;
        
        
        $usuario = $_GET['id'];
        
        $sql_usuario = sprintf("SELECT
        sys_usuario.username,
        gen_persona.nro_documento,
        gen_persona.cod_tipo_documento,
        gen_persona.nombres,
        gen_persona.genero,
        gen_persona.direccion,
        gen_persona.telefono,
        gen_persona.celular,
        gen_persona.email,
        gen_persona.fecha_nacimiento,
        gen_persona.lugar_nacimiento,
        gen_documentos.ruta,
        gen_persona.skype
        FROM
        sys_usuario
        INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
        LEFT JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
        where sys_usuario.username = '%s'", $usuario);
        
        
        $datos_usuario = BD::$db->queryRow($sql_usuario);
        
        if($datos_usuario['fecha_nacimiento'] === '0000-00-00'){
           $datos_usuario['fecha_nacimiento'] = ''; 
        }
        
        if($datos_usuario['ruta'] === null){
            $datos_usuario['ruta'] = 'public/img/profile.jpg';
        }
        
        $vars['usuario'] = $datos_usuario;
        
        
        
        var_dump($datos_usuario);

        View::render('usuarios/editar_usuario.php', $vars); 
    }
    
    public function guardar_usuario(){
        header('Content-Type: application/json');   
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
        $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
        $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_NUMBER_INT);
        $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
        $fechanacimiento = filter_input(INPUT_POST, 'fechanacimiento', FILTER_SANITIZE_STRING);
        $emailpersonal = filter_input(INPUT_POST, 'emailpersonal', FILTER_SANITIZE_STRING);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        $skype = filter_input(INPUT_POST, 'skype', FILTER_SANITIZE_STRING);
        $foto = filter_input(INPUT_POST, 'foto', FILTER_SANITIZE_NUMBER_INT);
        $nombreusuario = filter_input(INPUT_POST, 'nombreusuario', FILTER_SANITIZE_STRING);
        $passwd1 = filter_input(INPUT_POST, 'passwd1', FILTER_SANITIZE_STRING);
        $passwd2 = filter_input(INPUT_POST, 'passwd2', FILTER_SANITIZE_STRING);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_STRING);
        
        if(!isset($usuario_id) || $usuario_id == ''){           
        
        $valid = true;
        $msg_err = array();
        
        if($passwd1 !== $passwd2){
            $valid = false;
            $msg_err[] = 'Las contraseñas ingresadas deben coincidir';
        }
        
        if($passwd1 == ''){
            $valid = false;
            $msg_err[] = 'Debe ingresar ambas contraseñas';
        }
        
        if($passwd2 == ''){
            $valid = false;
            $msg_err[] = 'Debe ingresar ambas contraseñas';
        }
        
        if($nombreusuario !== ''){
            $sql_usuario_check = sprintf("select count(*) from sys_usuario where username = '%s'", $nombreusuario);
            $usuario_check = BD::$db->queryOne($sql_usuario_check);

            if($usuario_check > 0){
                $valid = false;
                $msg_err[] = 'El nombre de usuario ya se encuentra registrado en el sistema';
            }
        }
        else{
             $valid = false;
             $msg_err[] = 'Debe proporcionar un nombre de usuario';
        }
        
        if($emailpersonal !== ''){
            $sql_mail_check = sprintf("SELECT
            Count(sys_usuario.username)
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            WHERE
            gen_persona.email = '%s'", $emailpersonal);
            $mail_check = BD::$db->queryOne($sql_mail_check);

            if($mail_check > 0){
                $valid = false;
                $msg_err[] = 'El email personal ya se encuentra registrado en el sistema';
            }
        }
        
        
        if($valid){
            $model_persona = new gen_persona();
            $model_persona->begin();
            $model_persona->nro_documento = $documento;
            if($tipodocumento > 0)
            $model_persona->cod_tipo_documento = $tipodocumento;
            $model_persona->nombres = $nombre;
            $model_persona->genero = $genero;
            $model_persona->titulo = '';
            if($pais > 0)
            $model_persona->lugar_nacimiento = $pais;    
            $model_persona->direccion = $direccion;
            $model_persona->telefono = $telefono;
            $model_persona->celular = $celular;
            $model_persona->email = $emailpersonal;
            $model_persona->fecha_nacimiento = $fechanacimiento;
            if($foto > 0)
            $model_persona->foto = $foto;
            $model_persona->skype = $skype;
            if($model_persona->save()){
                $model_sys_usuario = new sys_usuario();
                $model_sys_usuario->username = $nombreusuario;
                $model_sys_usuario->cod_persona = $model_persona->id;
                $model_sys_usuario->passwd = md5($passwd1);
                $model_sys_usuario->autorizado = 1;
                $model_sys_usuario->nombres = $nombre;
                $model_sys_usuario->creador = Auth::info_usuario('usuario');
                if($model_sys_usuario->save()){
                    echo json_encode(array('usuario' => $model_sys_usuario->username, 'mensaje' => 'El usuario ha sido guardado exitosamente'));
                    $model_persona->commit();
                }
                else{
                    //model_sys_usuario_err
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo guardar el usuario";
                }
            }
            else{
                //model_persona_err
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo guardar el usuario";
            }

        }
        else{
            //invalid
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $msg_err[0];
        }
      }
      else{
        //editar
        $valid = true;
        $msg_err = array();
        
        if($passwd1 !== $passwd2){
            $valid = false;
            $msg_err[] = 'Las contraseñas ingresadas deben coincidir';
        }
        
        if($passwd1 == ''){
            $valid = false;
            $msg_err[] = 'Debe ingresar ambas contraseñas';
        }
        
        if($passwd2 == ''){
            $valid = false;
            $msg_err[] = 'Debe ingresar ambas contraseñas';
        }
        
        if($nombreusuario !== ''){
           if($nombreusuario !== $usuario_id){
                $sql_usuario_check = sprintf("select count(*) from sys_usuario where username = '%s'", $nombreusuario);
                $usuario_check = BD::$db->queryOne($sql_usuario_check);

                if($usuario_check > 0){
                    $valid = false;
                    $msg_err[] = 'El nombre de usuario ya se encuentra registrado en el sistema';
                }
           }
        }
        else{
             $valid = false;
             $msg_err[] = 'Debe proporcionar un nombre de usuario';
        }
        
        if($emailpersonal !== ''){
            $sql_mail_check = sprintf("SELECT
            Count(sys_usuario.username)
            FROM
            sys_usuario
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            WHERE
            gen_persona.email = '%s'", $emailpersonal);
            $mail_check = BD::$db->queryOne($sql_mail_check);

            if($mail_check > 0){
                $valid = false;
                $msg_err[] = 'El email personal ya se encuentra registrado en el sistema';
            }
        }
        
        
        if($valid){
            $sql_usuario = sprintf("SELECT
            sys_usuario.cod_persona
            FROM
            sys_usuario
            WHERE
            sys_usuario.username = %s
            ", $usuario_id);
            
            $usuario = BD::$db->queryRow($sql_usuario);           
            
            $model_persona = new gen_persona();
            $model_persona->begin();
            $model_persona->id = $usuario['cod_persona'];
            $model_persona->nro_documento = $documento;
            if($tipodocumento > 0)
            $model_persona->cod_tipo_documento = $tipodocumento;
            $model_persona->nombres = $nombre;
            $model_persona->genero = $genero;
            $model_persona->titulo = '';
            if($pais > 0)
            $model_persona->lugar_nacimiento = $pais;    
            $model_persona->direccion = $direccion;
            $model_persona->telefono = $telefono;
            $model_persona->celular = $celular;
            $model_persona->email = $emailpersonal;
            $model_persona->fecha_nacimiento = $fechanacimiento;
            if($foto > 0)
            $model_persona->foto = $foto;
            $model_persona->skype = $skype;
            if($model_persona->update()){
                $update_sys_usuario = sprintf("UPDATE sys_usuario SET username='%s',cod_persona='%s',passwd='%s',autorizado=%s,nombres='%s',creador='%s' WHERE username='%s'",
                        $nombreusuario, $model_persona->id, md5($passwd1), 1, $nombre, Auth::info_usuario('usuario'), $usuario_id);
                $query = BD::$db->query($update_sys_usuario);
                if(PEAR::isError($query)){
                    //model_sys_usuario_err
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo actualizar el usuario";
                }
                else{
                    echo json_encode(array('usuario' => $nombreusuario, 'mensaje' => 'El usuario ha sido actualizado'));
                    $model_persona->commit();
                }               
            }
            else{
                //model_persona_err
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo actualizar el usuario";
            }

        }
        else{
            //invalid
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $msg_err[0];
        }
      }
    }
    
    
    public function guardar_rol_usuario(){
        header('Content-Type: application/json');   
        $evaluacion = filter_input(INPUT_POST, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
        $cargo = filter_input(INPUT_POST, 'cargo', FILTER_SANITIZE_NUMBER_INT);
        $rol =  filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $usuario =  filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $id_comite =  filter_input(INPUT_POST, 'id_comite', FILTER_SANITIZE_NUMBER_INT);
        
        //revisar si usuario no tiene rol a colocar
        // si ya lo tiene continuar
        // si no lo tiene asignar
        $valid = true;
        
        $sql_rol_check = sprintf("select count(*) from sysusers where username = '%s' and idrol = %s", $usuario, $rol);
        $rol_check = BD::$db->queryOne($sql_rol_check);
        
        $model_sysusers = new sysusers();
        $model_sysusers->begin();
        
        
        if($rol_check === "0"){            
            $model_sysusers->idrol = $rol;
            $model_sysusers->username = $usuario;
            $model_sysusers->activo = 'SI';
            if(!$model_sysusers->save()){
                $valid = false;
            }
        }
      
        
        if($valid){
            /*
             * cargos
             * 1 : 1,1
             * 2 : 1,2
             * 3 : 2,1
             * 4 : 2,2
             */
            if($evaluacion > 0){               
                if($id_comite > 0){
                    //actualizar
                    $sql_persona = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
                    $persona = BD::$db->queryOne($sql_persona);                
                        $model_comite = new comite();
                        $model_comite->id = $id_comite;
                        switch($cargo){
                           case 1:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 1, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 1;
                                break;
                            case 2:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 1, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 2;
                                break;
                            case 3:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 2, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 1;
                                break;
                            case 4:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 2, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 2;
                                break;
                        }
                        $model_comite->cod_persona = $persona;
                        if(!$model_comite->update()){
                            //no se pudo asociar el usuario a la evaluacion
                            echo "err";
                        }
                        else{
                            $model_sysusers->commit();
                            echo json_encode(array('comite' => $model_comite->id));
                        }
                    }
                else{
                    //crear
                    $sql_persona = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
                    $persona = BD::$db->queryOne($sql_persona);
                    $sql_comite_check = sprintf("SELECT
                    count(comite.id)
                    FROM
                    momento_evaluacion
                    INNER JOIN comite ON momento_evaluacion.id = comite.cod_momento_evaluacion
                    WHERE
                    comite.cod_persona = %s AND
                    momento_evaluacion.cod_evaluacion = %s", $persona, $evaluacion);
                    $comite_check = BD::$db->queryOne($sql_comite_check);
                    if($comite_check == 0){
                        $model_comite = new comite();
                        switch($cargo){
                           case 1:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 1, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 1;
                                break;
                            case 2:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 1, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 2;
                                break;
                            case 3:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 2, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 1;
                                break;
                            case 4:
                                $sql_momento_evaluacion = sprintf("SELECT
                                momento_evaluacion.id
                                FROM
                                evaluacion
                                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                                WHERE
                                momento_evaluacion.cod_momento = %s and evaluacion.id = %s", 2, $evaluacion);
                                $momento_evaluacion = BD::$db->queryOne($sql_momento_evaluacion);
                                $model_comite->cod_momento_evaluacion = $momento_evaluacion;
                                $model_comite->cod_cargo = 2;
                                break;
                        }
                        $model_comite->cod_persona = $persona;
                        if(!$model_comite->save()){
                            //no se pudo asociar el usuario a la evaluacion
                            echo "err";
                        }
                        else{
                            $model_sysusers->commit();
                            echo json_encode(array('comite' => $model_comite->id));
                        }
                    }
                    else{
                        //el usuario ya esta en ese comite
                        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                        echo "El usuario ya esta en ese comite";
                    }
                }
            }
            else{
                $model_sysusers->commit();
                echo json_encode(array('status' => 'ok'));
            }
        }
        else{
            //no se pudo asociar el rol
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se pudo asociar el rol";
        }
    }
    
    public function eliminar_rol_usuario(){
        header('Content-Type: application/json'); 
        $rol =  filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $usuario =  filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        
        $sql_delete = sprintf("delete from sysusers where username = '%s' and idrol = %s", $usuario, $rol);
        $query = BD::$db->query($sql_delete);
        if($rol === "2"){
            //borrar comites
             if(!PEAR::isError($query)){
                $sql_persona = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
                $persona = BD::$db->queryOne($sql_persona);
                $sql_delete_comite = sprintf("delete from comite where cod_persona = %s", $persona);
                $query_comite = BD::$db->query($sql_delete_comite);
                if(!PEAR::isError($query_comite)){
                    echo json_encode(array('status' => 'ok'));
                }
                else{
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo "No se pudo eliminar el rol del usuario";
                } 
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el rol del usuario";
            }             
        }
        else{
           if(!PEAR::isError($query)){
                echo json_encode(array('status' => 'ok'));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar el rol del usuario";
            } 
        }
        
    }
    
    public function eliminar_usuario_comite(){
        header('Content-Type: application/json');
        $id_comite =  filter_input(INPUT_POST, 'id_comite', FILTER_SANITIZE_NUMBER_INT);
        $sql_delete = sprintf("delete from comite where id = %s", $id_comite);
        $query = BD::$db->query($sql_delete);
        if(!PEAR::isError($query)){
           echo json_encode(array('status' => 'ok')); 
        }
        else{
           header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
           echo "No se pudo eliminar el usuario del comite";
        }
    }
    
    public function get_evaluaciones_usuario(){
        header('Content-Type: application/json');
        $usuario =  filter_input(INPUT_GET, 'usuario', FILTER_SANITIZE_STRING);
        $sql_evaluaciones = sprintf("SELECT
            evaluacion.etiqueta,
            comite.cod_cargo,
            momento_evaluacion.cod_momento,
            comite.id as comite
            FROM
            sys_usuario
            INNER JOIN comite ON sys_usuario.cod_persona = comite.cod_persona
            INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            where sys_usuario.username = '%s'", $usuario);
        $_evaluaciones = BD::$db->queryAll($sql_evaluaciones);
        
//        var_dump($sql_evaluaciones);
        
        foreach($_evaluaciones as $key=>$e){
            /*
             * cargos
             * 1 : 1,1
             * 2 : 1,2
             * 3 : 2,1
             * 4 : 2,2
             */
            $cargo = 0;
            if($e['cod_momento'] == "1"){
                if($e['cod_cargo'] == "1"){
                    $cargo = 1;
                }
                else{
                    if($e['cod_cargo'] == "2"){
                        $cargo = 2;
                    }
                }
            }
            else{
                if($e['cod_momento'] == "2"){
                    if($e['cod_cargo'] == "1"){
                        $cargo = 3;
                    }
                    else{
                        if($e['cod_cargo'] == "2"){
                            $cargo = 4;
                        }
                    }
                }
            }
            $_evaluaciones[$key]['cargo'] = $cargo;
        }
        echo json_encode($_evaluaciones);
    }
    
    public function mailtest(){
        var_dump($this->lang);
//        MailHelper::mail('admin_group', 'Correo de prueba', 'Test de agente de correos');
    }
 
}
