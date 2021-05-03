<?php
Load::model2('gen_persona');
Load::model2('gen_persona_formacion');
Load::model2('eval_instituciones');
Load::model2('eval_institucion_rector');
Load::model2('gen_paises_idiomas');
Load::model2('GeneralModel');

class institucionesController extends ControllerBase{    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){   
        View::add_js('modules/sievas/scripts/instituciones/main.js'); 
        View::add_js('modules/sievas/scripts/instituciones/listar.js');               
        View::render('instituciones/listar.php');                 
    }  
    
    public function agregar(){
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/instituciones/main.js');
        View::add_js('modules/sievas/scripts/instituciones/agregar.js');
        View::render('instituciones/add.php'); 
    }
    
    public function editar(){
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/instituciones/main.js');
        View::add_js('modules/sievas/scripts/instituciones/agregar.js');
        
        
        $id = $_GET['id'];
        
        $sql_institucion = "SELECT
        eval_instituciones.id,
        eval_instituciones.nom_institucion,
        eval_instituciones.direccion,
        eval_instituciones.telefonos,
        eval_instituciones.email as institucion_email,
        eval_instituciones.nit,
        eval_instituciones.nombre_corto,
        eval_instituciones.celular,
        eval_instituciones.fax,
        eval_instituciones.apartado_aereo,
        eval_instituciones.web,
        eval_instituciones.dv,
        eval_instituciones.indicativo_comp,
        eval_instituciones.cod_pais,
        eval_instituciones.cod_nivel_academico,
        eval_instituciones.cod_municipio,
        eval_institucion_rector.cod_persona,
        eval_institucion_rector.periodo,
        gen_persona.nombres as nombre_rector,
        gen_persona.primer_apellido,
        gen_persona.segundo_apellido,
        gen_persona.nro_documento,
        gen_persona.cod_tipo_documento,
        gen_persona.telefono as telefono_rector,
        gen_persona.email,
        gen_persona_formacion.titulo,
        gen_persona_formacion.institucion,
        gen_persona_formacion.anio,
        gen_persona.lugar_nacimiento,
        gen_municipios.municipio,
        gen_departamentos.departamento,
        gen_municipios.cod_departamento,
        gen_paises.indicativo,
        gen_persona_formacion.cod_nivel_formacion
        FROM
        eval_instituciones
        INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
        INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
        INNER JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
        INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
        INNER JOIN gen_departamentos ON gen_municipios.cod_departamento = gen_departamentos.id
        INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id AND gen_departamentos.cod_pais = gen_paises.id
        where eval_instituciones.id=$id";
        
        $institucion = BD::$db->queryRow($sql_institucion);
        $vars['institucion'] = $institucion;
        
        View::render('instituciones/edit.php', $vars); 
    }
    
    public function get_niveles_academicos(){
        header('Content-Type: application/json');      
        $sql_niveles = "select * from niveles_academicos";
        $niveles = BD::$db->queryAll($sql_niveles);
        echo json_encode($niveles);
    }
    
    public function get_nivel_academico(){
        header('Content-Type: application/json'); 
        $nivel_academico = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_nivel_academico = "select * from niveles_academicos where id=$nivel_academico";
        $nivel = BD::$db->queryRow($sql_nivel_academico);
        echo json_encode($nivel);
    }
    
    public function get_niveles_formacion(){
        header('Content-Type: application/json');      
        $sql_niveles = "select * from niveles_formacion";
        $niveles = BD::$db->queryAll($sql_niveles);
        echo json_encode($niveles);
    }
    
    public function get_nivel_formacion(){
        header('Content-Type: application/json'); 
        $nivel_academico = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_nivel_academico = "select * from niveles_formacion where id=$nivel_academico";
        $nivel = BD::$db->queryRow($sql_nivel_academico);
        echo json_encode($nivel);
    }
    
    public function get_departamentos_pais(){
        header('Content-Type: application/json');  
        $pais = filter_input(INPUT_GET, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $departamentos = array();
        if($pais > 0){
            $sql_departamentos = "select * from gen_departamentos where cod_pais=$pais";
            $departamentos = BD::$db->queryAll($sql_departamentos);
        }        
        echo json_encode($departamentos);
    }
    
    public function get_departamento(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_departamento = "select * from gen_departamentos where id=$id";
        $departamento = BD::$db->queryRow($sql_departamento);
        echo json_encode($departamento);
    }
    
    public function get_municipios_departamento(){
        header('Content-Type: application/json');  
        $dpto = filter_input(INPUT_GET, 'dpto', FILTER_SANITIZE_NUMBER_INT);
        $municipios = array();
        if($dpto > 0){
            $sql_municipios = "select * from gen_municipios where cod_departamento=$dpto";
            $municipios = BD::$db->queryAll($sql_municipios);
        }        
        echo json_encode($municipios);
    }
    
    public function get_municipio(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_municipio = "select * from gen_municipios where id=$id";
        $municipio = BD::$db->queryRow($sql_municipio);
        echo json_encode($municipio);
    }
    
    public function guardar_institucion(){
        header('Content-Type: application/json');
        //institucion
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $aa = filter_input(INPUT_POST, 'aa', FILTER_SANITIZE_STRING);
        $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);        
        $dv = filter_input(INPUT_POST, 'dv', FILTER_SANITIZE_NUMBER_INT);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $nivel_academico = filter_input(INPUT_POST, 'nivel_academico', FILTER_SANITIZE_NUMBER_INT);
        $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_STRING);        
        $municipio = filter_input(INPUT_POST, 'municipio', FILTER_SANITIZE_NUMBER_INT);
        $nit = filter_input(INPUT_POST, 'nit', FILTER_SANITIZE_NUMBER_INT);
        $nom_institucion = filter_input(INPUT_POST, 'nom_institucion', FILTER_SANITIZE_STRING);
        $nombre_corto  = filter_input(INPUT_POST, 'nombre_corto', FILTER_SANITIZE_STRING);
        $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        $web = filter_input(INPUT_POST, 'web', FILTER_SANITIZE_STRING);
        $indicativo_comp = filter_input(INPUT_POST, 'indicativo_comp', FILTER_SANITIZE_STRING);
        
        //rector
        $nivel_formacion = filter_input(INPUT_POST, 'nivel_formacion', FILTER_SANITIZE_NUMBER_INT);        
        $nombre_rector  = filter_input(INPUT_POST, 'nombre_rector', FILTER_SANITIZE_STRING);
        $email_rector = filter_input(INPUT_POST, 'email_rector', FILTER_SANITIZE_STRING);       
        $pais_origen = filter_input(INPUT_POST, 'pais_origen', FILTER_SANITIZE_NUMBER_INT); 
        $periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);       
        $telefono_rector  = filter_input(INPUT_POST, 'telefono_rector', FILTER_SANITIZE_STRING);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
        $tipodocumento = filter_input(INPUT_POST, 'tipodocumento', FILTER_SANITIZE_STRING);
        $institucion = filter_input(INPUT_POST, 'institucion', FILTER_SANITIZE_STRING);       
        
        if($id > 0){
            $model = new eval_instituciones();
            $model->begin();
            $valid = true;
            $model->id = $id;
            $model->nom_institucion = $nom_institucion;
            $model->direccion = $direccion;
            $model->telefonos = $telefono;
            $model->email = $email;
            $model->cod_pais = $pais;
            $model->cod_nivel_academico = $nivel_academico;
            $model->nit = $nit;
            $model->dv = $dv;
            $model->nombre_corto = $nombre_corto;
            $model->cod_municipio = $municipio;
            $model->celular = $celular;
            $model->fax = $fax;
            $model->apartado_aereo = $aa;
            $model->web = $web;
            $model->indicativo_comp = $indicativo_comp;

            if($model->update()){
                $sql_rector = 'select cod_persona from eval_institucion_rector where cod_institucion ='.$model->id;
                $rector = BD::$db->queryOne($sql_rector);
                $model_rector = new gen_persona();
                $model_rector->id = $rector;
                $model_rector->nro_documento = $documento;
                if($tipodocumento > 0){
                    $model_rector->cod_tipo_documento = $tipodocumento;
                }           
                $model_rector->nombres = $nombre_rector;
                $model_rector->telefono = $telefono_rector;
                $model_rector->email = $email_rector;
                $model_rector->lugar_nacimiento = $pais_origen;
                if($model_rector->update()){
                    $sql_rector_institucion = sprintf('select id from eval_institucion_rector where cod_persona = %s and cod_institucion=%s', $model_rector->id, $model->id);
                    $rector_institucion = BD::$db->queryOne($sql_rector_institucion);
                    
                    $model_rector_institucion = new eval_institucion_rector();
                    $model_rector_institucion->id = $rector_institucion;
                    $model_rector_institucion->cod_institucion = $model->id;
                    $model_rector_institucion->cod_persona = $model_rector->id;    
                    $model_rector_institucion->periodo = $periodo;
                    
                    if($model_rector_institucion->update()){
                        $sql_rector_formacion = 'select id from gen_persona_formacion where cod_persona ='.$model_rector->id;
                        $rector_formacion = BD::$db->queryOne($sql_rector_formacion);

                        $model_rector_formacion = new gen_persona_formacion();
                        $model_rector_formacion->cod_nivel_formacion = $nivel_formacion;
                        $model_rector_formacion->id = $rector_formacion;
                        $model_rector_formacion->titulo = $titulo;
                        $model_rector_formacion->institucion = $institucion;
                        $model_rector_formacion->cod_persona = $model_rector->id;  
                        if(!$model_rector_formacion->update()){
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

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'La institución fue actualizada correctamente'));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "La institución no pudo ser actualizada";
            }
        }
        else{
            $model = new eval_instituciones();
            $model->begin();
            $valid = true;
            $model->nom_institucion = $nom_institucion;
            $model->direccion = $direccion;
            $model->telefonos = $telefono;
            $model->email = $email;
            $model->cod_pais = $pais;
            $model->cod_nivel_academico = $nivel_academico;
            $model->nit = $nit;
            $model->dv = $dv;
            $model->nombre_corto = $nombre_corto;
            $model->cod_municipio = $municipio;
            $model->celular = $celular;
            $model->fax = $fax;
            $model->apartado_aereo = $aa;
            $model->web = $web;
            $model->indicativo_comp = $indicativo_comp;
            
            $err_msg = array();

            if($model->save()){
                $model_rector = new gen_persona();
                $model_rector->nro_documento = $documento;
                if($tipodocumento > 0){
                    $model_rector->cod_tipo_documento = $tipodocumento;
                }           
                $model_rector->nombres = $nombre_rector;
                $model_rector->telefono = $telefono_rector;
                $model_rector->email = $email_rector;
                $model_rector->lugar_nacimiento = $pais_origen;
                if($model_rector->save()){
                    $model_rector_institucion = new eval_institucion_rector();
                    $model_rector_institucion->cod_institucion = $model->id;
                    $model_rector_institucion->cod_persona = $model_rector->id;    
                    $model_rector_institucion->periodo = $periodo;
                    if($model_rector_institucion->save()){
                        $model_rector_formacion = new gen_persona_formacion();
                        $model_rector_formacion->cod_nivel_formacion = $nivel_formacion;
                        $model_rector_formacion->titulo = $titulo;
                        $model_rector_formacion->institucion = $institucion;
                        $model_rector_formacion->cod_persona = $model_rector->id;    
                    if(!$model_rector_formacion->save()){
                            $valid = false;
                            $err_msg[] = 'No se pudo guardar la formacion del rector';
                    }
                    }
                    else{
                    $valid = false;
                    $err_msg[] = 'No se pudo guardar el rector de la institución';
                    }
                }
                else{
                    $valid = false;
                    $err_msg[] = 'No se pudo guardar el rector';
                }
            }
            else{
                $valid = false;
                $err_msg[] = 'No se pudo guardar la institución';
            }

            if($valid){
                $model->commit();
                echo json_encode(array('mensaje' => 'La institución fue creada correctamente'));
            }
            else{
                $model->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo $err_msg[0];
            }
        }

    }
    
    public function eliminar_institucion(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from eval_instituciones where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar la institución";
            }
            else{
                echo json_encode(array('mensaje' => 'La institución fue eliminada correctamente'));
            }
        }
    }
    
    public function listar_instituciones(){
        
    }
    
    public function get_dt_instituciones(){
         $model = new GeneralModel();
         $model->listar();
    }
 
}
