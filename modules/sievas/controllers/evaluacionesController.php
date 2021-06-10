<?php
Load::model2('evaluacion');
Load::model2('momento_evaluacion');
Load::model2('comite');
Load::model2('sysusers_acceso');
Load::model2('sysusers');
Load::model2('GeneralModel');
Load::model2('gen_persona');
Load::model2('gen_documentos');
Load::model2('evaluacion_dictamen');
Load::model2('evaluacion_metaevaluacion');
Load::model2('momento_resultado');
Load::model2('momento_resultado_detalle');
Load::model2('momento_resultado_anexo');
Load::model2('plan_mejoramiento');
Load::model2('plan_mejoramiento_acciones');
Load::model2('plan_mejoramiento_metas');
                
include_once  LIBS_PATH.'/tcpdf/tcpdf.php';
require_once (LIBS_PATH.'/jpgraph/jpgraph.php');
require_once (LIBS_PATH.'/jpgraph/jpgraph_radar.php');
require_once (LIBS_PATH.'/jpgraph/jpgraph_bar.php');
require_once (LIBS_PATH.'/jpgraph/jpgraph_iconplot.php');

class evaluacionesController extends ControllerBase{    
    
    public function __construct(){
        parent::__construct();
    }
    
    public function configuracion(){
        $config = array();
        $config['ev_id'] = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $sql = sprintf('select tablero from evaluacion where id=%s', $config['ev_id']);
        $rs = BD::$db->queryRow($sql);
        $vars = array();
        $vars['config'] = $config;         
        $vars['tablero'] = $rs['tablero'] == 0 || $rs['tablero'] == 1? $rs['tablero']: null; 
        View::add_js('modules/sievas/scripts/evaluaciones/config.js'); 
        View::render('evaluaciones/configuracion.php', $vars);
       
    }
    
    public function viewfile(){
        $id= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT
        gen_documentos.id,
        gen_documentos.ruta,
        gen_documentos.nombre,
        gen_documentos.fecha_creado
        FROM
        gen_documentos
        where id = $id";
        

        $doc = BD::$db->queryRow($sql);
       
        $filename = '/home/donatovallin/public_html/sievas/'.$doc['ruta'];
        $finfo = finfo_open( FILEINFO_MIME_TYPE );
        $mtype = finfo_file( $finfo, $filename );
        finfo_close( $finfo );
        if( $mtype == ( "application/pdf" ) ) {
            header("Content-type: application/pdf");
            header("Content-Disposition: inline; filename=$filename");
            @readfile($filename);
        }
        else {
            header("Location: ".$doc['ruta']);
        }
        
        
        
        
        View::add_js('modules/sievas/scripts/evaluaciones/config.js'); 
        View::render('evaluaciones/configuracion.php', $vars);
    }
    
    public function guardar_configuracion(){
        header('Content-Type: application/json');
        $ev_id = filter_input(INPUT_POST, 'ev_id', FILTER_SANITIZE_STRING);
        $tablero_control = filter_input(INPUT_POST, 'tablero_control', FILTER_SANITIZE_STRING);
        $sql = sprintf('update evaluacion set tablero=%s where id=%s', $tablero_control, $ev_id);
        $rs = BD::$db->query($sql);
        if(PEAR::isError($rs)){
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo 'No se pudo guardar la configuración';
        }
        else{
            echo json_encode(array('mensaje'=>'ok'));
        }
        
    }
    
    public function index(){
        
    }
    
    public function monitoreo(){
        
    }
    
    public function evaluador(){
        $usuario = Auth::info_usuario('usuario');
        
        $sql_persona = 'SELECT
        gen_persona.nro_documento,
        gen_persona.nombres,
        gen_persona.primer_apellido,
        gen_persona.segundo_apellido,
        gen_persona.genero,
        gen_persona.direccion,
        gen_persona.telefono,
        gen_persona.celular,
        gen_persona.email as email_personal,
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
        LEFT JOIN gen_tipo_documento ON gen_persona.cod_tipo_documento = gen_tipo_documento.id
        LEFT JOIN gen_paises ON gen_persona.cod_nacionalidad = gen_paises.id
        LEFT JOIN gen_estado_civil ON gen_persona.cod_estado_civil = gen_estado_civil.id
        LEFT JOIN evaluador ON evaluador.cod_persona = gen_persona.id
        LEFT JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
        where sys_usuario.username = '.BD::$db->quote($usuario);
        
        $sql_info_academica = 'SELECT
        gen_persona_formacion.fecha,
        gen_persona_formacion.titulo,
        gen_persona_formacion.institucion,
        gen_persona_formacion.anio,
        niveles_formacion.nivel_formacion
        FROM
        sys_usuario
        INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
        LEFT JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
        LEFT JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
        WHERE
	sys_usuario.username = '.BD::$db->quote($usuario);
        
        
        $sql_info_experiencia = 'SELECT
        gen_persona_experiencia.institucion,
        gen_persona_experiencia.status_laboral,
        gen_persona_experiencia.periodo
        FROM
        sys_usuario
        INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
        LEFT JOIN gen_persona_experiencia ON gen_persona_experiencia.cod_persona = gen_persona.id
        WHERE
	sys_usuario.username = '.BD::$db->quote($usuario);
        
        $persona = BD::$db->queryRow($sql_persona);
        $persona_formacion = BD::$db->queryAll($sql_info_academica);
        $persona_experiencia = BD::$db->queryAll($sql_info_experiencia);
        $evaluador = array();
        $evaluador['info_personal'] = $persona;
        $evaluador['info_academica'] = $persona_formacion;
        $evaluador['info_experiencia'] = $persona_experiencia;
        
        $vars['evaluador'] = $evaluador;
        
        View::render('evaluaciones/evaluador.php', $vars);
    }
    
    public function generar_dictamen(){
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
        View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
        View::add_js('modules/sievas/scripts/evaluaciones/dictamen.js'); 

        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $sql_evaluacion = "SELECT
        evaluacion.fecha_inicia,
        evaluacion.anotaciones,
        evaluacion.reacreditacion,
        evaluacion.tipo_evaluado,
        evaluacion.cod_evaluado,
        lineamientos_conjuntos.nom_conjunto
        FROM
        evaluacion
        INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        where evaluacion.id = $evaluacion_actual";
        
        $evaluacion = BD::$db->queryRow($sql_evaluacion);
        
        $sql_evaluacion_dictamen = sprintf("select * from evaluacion_dictamen where cod_evaluacion = %s", $evaluacion_actual);
        $evaluacion_dictamen = BD::$db->queryRow($sql_evaluacion_dictamen);
        
        if($evaluacion_dictamen['id'] > 0){
            $vars['titulo'] = $evaluacion_dictamen['titulo'];
            $vars['introduccion'] = $evaluacion_dictamen['introduccion'];
            $vars['txt1'] = $evaluacion_dictamen['txt1'];
            $vars['txt2'] = $evaluacion_dictamen['txt2'];
            $vars['txt3'] = $evaluacion_dictamen['txt3'];
            $vars['txt4'] = $evaluacion_dictamen['txt4'];
            $vars['clave'] = $evaluacion_dictamen['clave'];
            $vars['contexto'] = $evaluacion_dictamen['contexto'];
            $vars['percepcion'] = $evaluacion_dictamen['percepcion'];
            $vars['codigo_dictamen'] = $evaluacion_dictamen['codigo_dictamen'];
        }
        else{            
        $tipo_evaluado = $evaluacion['tipo_evaluado'];
        $id = $evaluacion['cod_evaluado'];
        $evaluado = array();
        switch($tipo_evaluado){
            case "1":
                $sql_evaluado = "SELECT
                eval_instituciones.nom_institucion as evaluado,          
                gen_paises.nom_pais as pais_evaluado             
                FROM
                eval_instituciones
                INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                INNER JOIN niveles_academicos ON eval_instituciones.cod_nivel_academico = niveles_academicos.id
                INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
                INNER JOIN gen_departamentos ON gen_departamentos.cod_pais = gen_paises.id AND gen_municipios.cod_departamento = gen_departamentos.id
                INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
                INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
                where eval_instituciones.id=$id";    

                $evaluado = BD::$db->queryRow($sql_evaluado);
                $evaluado['tipo_evaluado'] = 'institución';
                break;
            case "2":
                $sql_evaluado = "SELECT
                eval_programas.programa as evaluado,
                eval_programas.adscrito,              
                eval_programas.cod_institucion              
                FROM
                eval_programas
                INNER JOIN niveles_academicos ON eval_programas.cod_nivel_academico = niveles_academicos.id
                where eval_programas.id=$id"; 

                $evaluado = BD::$db->queryRow($sql_evaluado);
                $sql_evaluado = "SELECT
                eval_instituciones.nom_institucion as institucion_evaluado,              
                gen_paises.nom_pais as pais_evaluado          
                FROM
                eval_instituciones
                INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                INNER JOIN niveles_academicos ON eval_instituciones.cod_nivel_academico = niveles_academicos.id
                INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
                INNER JOIN gen_departamentos ON gen_departamentos.cod_pais = gen_paises.id AND gen_municipios.cod_departamento = gen_departamentos.id
                INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
                INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
                where eval_instituciones.id=".$evaluado['cod_institucion'];   
                $tmp = BD::$db->queryRow($sql_evaluado);
                foreach($tmp as $key=>$t){
                    $evaluado[$key] = $t;
                }
                $evaluado['tipo_evaluado'] = 'programa educativo';
                break;            
            }        
        
        $datos_evaluado = array();
        $datos_evaluado['tipo_evaluado'] =  $evaluado['tipo_evaluado'];
        $datos_evaluado['nombre_evaluado'] =  $evaluado['evaluado'];
        $datos_evaluado['pais_evaluado'] =  $evaluado['pais_evaluado'];
        //especifico del programa
        $datos_evaluado['institucion_evaluado'] =  $evaluado['institucion_evaluado'];
        $datos_evaluado['adscrito'] =  $evaluado['adscrito'];
        
        
        $sql_puntaje = sprintf("SELECT
        Avg(gradacion_escalas.valor_escala)
        FROM
        momento_resultado_detalle
        INNER JOIN momento_resultado ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
        INNER JOIN momento_evaluacion ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
        WHERE
        momento_evaluacion.cod_momento = 2 AND
        evaluacion.id = %s", $evaluacion_actual);
        
        $puntaje = BD::$db->queryOne($sql_puntaje);
        
        $datos_evaluado['puntaje'] = $puntaje;
        
        $calidad = 'muy escasa';
        if($puntaje > 0 && $puntaje <= 1)
            $calidad = 'muy escasa';
        if($puntaje > 1 && $puntaje <= 2)
            $calidad = 'escasa';
        if($puntaje > 2 && $puntaje <= 3)
            $calidad = 'medianamente escasa';
        if($puntaje > 3 && $puntaje <= 4)
            $calidad = 'poco incipiente';
        if($puntaje > 4 && $puntaje <= 5)
            $calidad = 'medianamente incipiente';
        if($puntaje > 5 && $puntaje <= 6)
            $calidad = 'muy incipiente';
        if($puntaje > 6 && $puntaje <= 7)
            $calidad = 'incipientemente alta';
        if($puntaje > 7 && $puntaje <= 8)
            $calidad = 'medianamente alta';
        if($puntaje > 8 && $puntaje <= 9)
            $calidad = 'alta';
        if($puntaje > 9 && $puntaje <= 10)
            $calidad = 'muy alta';
        
        $datos_evaluado['calidad'] = $calidad;
//        
        $vars['datos_evaluado'] = $datos_evaluado;
        
        $sql_comite = "SELECT
            comite.cod_persona,
            evaluacion.id,
            comite.cod_momento_evaluacion,
            comite.cod_cargo,
            momento_evaluacion.cod_momento,
            momento_evaluacion.fecha_inicia,
            momento_evaluacion.fecha_termina,
            gen_persona.nombres,
            gen_persona.primer_apellido,
            gen_persona.segundo_apellido,
            niveles_formacion.nivel_formacion
            FROM
            comite
            INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            LEFT JOIN gen_persona_formacion ON gen_persona.id = gen_persona_formacion.cod_persona
            LEFT JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
            WHERE
            evaluacion.id = $evaluacion_actual";            
            
            $comite = BD::$db->queryAll($sql_comite);         
            
            $_comite = array();
            
            foreach($comite as $c){
                $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
            }
            
        $fecha_inicio = $_comite[2][1][0]['fecha_inicia'];
        $dia = substr($fecha_inicio,8,2);
        $mes = substr($fecha_inicio,5,2);
        $anio = substr($fecha_inicio,0,4);
        
       $meses = array(
           '01' => 'enero',
           '02' => 'febrero',
           '03' => 'marzo',
           '04' => 'abril',
           '05' => 'mayo',
           '06' => 'junio',
           '07' => 'julio',
           '08' => 'agosto',
           '09' => 'septiembre',
           '10' => 'octubre',
           '11' => 'noviembre',
           '12' => 'diciembre',
       );
        

        $datos_evaluacion_externa = array();
        $datos_evaluacion_externa['horas'] = '12';
        $datos_evaluacion_externa['dia'] = $dia;
        $datos_evaluacion_externa['mes'] = $meses[$mes];
        $datos_evaluacion_externa['anio'] = $anio;
        $datos_evaluacion_externa['comite']['externo'] = array();
        
        $cargos_cee = array(
            '1' => 'coordinador',
            '2' => 'evaluador',
            '3' => 'evaluador',
            '4' => 'evaluador'
        );        
        

        foreach($_comite[2] as $cee){
           $datos_evaluacion_externa['comite']['externo'][] = array(
            'nivel_academico' => $cee[0]['nivel_formacion'],
            'nombres' => $cee[0]['nombres'],
            'ocupacion' => '',
            'cargo_comite' => $cargos_cee[$cee[0]['cod_cargo']],
            'comite' => 'externo'
           ); 
        }

        
        $datos_evaluacion_externa['comite']['interno'] = array();
        
        foreach($_comite[1] as $cee){
           $datos_evaluacion_externa['comite']['interno'][] = array(
            'nivel_academico' => $cee['nivel_formacion'],
            'nombres' => $cee['nombres'],
            'ocupacion' => '',
            'cargo_comite' => $cargos_cee[$cee['cod_cargo']],
            'comite' => 'externo'
           ); 
        }

        
        $vars['datos_evaluacion_externa'] = $datos_evaluacion_externa;
        $fecha_fin = $_comite[2][1][0]['fecha_fin'];
        $fdia = substr($fecha_fin,8,2);
        $fmes = substr($fecha_fin,5,2);
        $fanio = substr($fecha_fin,0,4);

        $datos_finalizacion_evaluacion = array();
        $datos_finalizacion_evaluacion['horas'] = '20';
        $datos_finalizacion_evaluacion['dia'] = $fdia;
        $datos_finalizacion_evaluacion['mes'] = $fmes;
        $datos_finalizacion_evaluacion['anio'] = $fanio;
        
        $vars['datos_finalizacion_evaluacion'] = $datos_finalizacion_evaluacion;
        
        $titulo = "Reporte-Dictamen del Proceso de Evaluación para la Certificación Internacional ".$datos_evaluado['tipo_evaluado_conector']." ".$datos_evaluado['tipo_evaluado']." "
                . $datos_evaluado['nombre_evaluado'].", ".$datos_evaluado['institucion_evaluado'].", ".$datos_evaluado['pais_evaluado'].".";
        
        
        $introduccion = "Siendo las ".$datos_evaluacion_externa['horas']." horas del día ".$datos_evaluacion_externa['dia']." de ".$datos_evaluacion_externa['mes']." de ".$datos_evaluacion_externa['anio']." dieron inicio los trabajos relativos a la visita de ";
        $introduccion .= "los pares evaluadores externos, lo que inició con un acto de inauguración en el que quedó ";
        $introduccion .= "formalmente abierto el proceso de evaluación externa para la certificación internacional del ";
        $introduccion .= $datos_evaluacion_externa['tipo_evaluado']." ".$datos_evaluado['nombre_evaluado']." adscrita a la ".$datos_evaluado['adscrito'];
        $introduccion .= "de la ".$datos_evaluado['institucion_evaluado'].", participando por ";
        $introduccion .= "parte de GRANA, el Doctor. Donato Vallin González, Director General de GRANA; ";
        $i = 0;
        foreach($datos_evaluacion_externa['comite']['externo'] as $e){
            $i++;
            $introduccion .= $e['nivel_academico']." ".$e['nombres'].", ".$e['ocupacion']." ";
            if(count($datos_evaluacion_externa['comite']['externo']) == $i){
                $introduccion .= "como ".$e['cargo_comite']." de la evaluación ".$e['comite'].".\n";
            }
            else{
                $introduccion .= "como ".$e['cargo_comite']." de la evaluación ".$e['comite']."; ";
            }
            
        }        
        $introduccion .= "Así también por parte del Comité Evaluador Interno CEI del Programa a Evaluar: ";
        $i = 0;
        foreach($datos_evaluacion_externa['comite']['interno'] as $e){
            $i++;
            $introduccion .= $e['nivel_academico']." ".$e['nombres'].", ".$e['ocupacion']." ";
            if(count($datos_evaluacion_externa['comite']['interno']) == $i){
                $introduccion .= "como ".$e['cargo_comite']." de la evaluación ".$e['comite'].".\n ";
            }
            else{
                $introduccion .= "como ".$e['cargo_comite']." de la evaluación ".$e['comite']."; ";
            }
        }     
        
        $txt1 = "Los procesos y procedimientos que se realizaron a lo largo de la evaluación-certificación del "
        .$datos_evaluado['tipo_evaluado'].", se identifican con los siguientes momentos:\n";
        
        $txt2 = "La evaluación externa se realizó en 3 momentos: primer momento, la participación de pares "
        ."académicos y de colegios especializados. El segundo momento la visita de los evaluadores "
        ."externos a ".$datos_evaluado['adscrito'].". El tercer momento la participación de "
        ."un evaluador a distancia quien apoyó el análisis de las evaluaciones internas y externas. El "
        ."reporte del dictamen y resultados de la evaluación está basado estrictamente en la valoración de "
        ."expertos académicos, de colegios de prestigio y empresariales que bajo una óptica de modelos y "
        ."estándares internacionales de calidad educativa y profesional a seguir, constatan y dan garantía "
        ."de una certificación pertinente.";
        
        $txt3 = "De acuerdo a los procesos de evaluación, se considera que el ".$datos_evaluado['tipo_evaluado']." de la "
        .$datos_evaluado['nombre_evaluado'].", es certificada bajo el primer "
        ."nivel con un puntaje de ".$datos_evaluado['puntaje']." que corresponde a ".$datos_evaluado['calidad']." la calidad.\n";
        
        $txt4 = "En la ciudad Miami Fl, Estados Unidos y Siendo las ".$datos_finalizacion_evaluacion['horas']." horas del día ".$datos_finalizacion_evaluacion['dia']." de ".$datos_finalizacion_evaluacion['mes']." de ".$datos_finalizacion_evaluacion['anio']." "
        ."se dieron por concluidos los trabajos de evaluación-certificación del ".$datos_evaluado['tipo_evaluado']." "
        ."de ".$datos_evaluado['nombre_evaluado'].", ".$datos_evaluado['pais_evaluado'].".\n";

        $vars['titulo'] = $titulo;
        $vars['introduccion'] = $introduccion;
        $vars['txt1'] = $txt1;
        $vars['txt2'] = $txt2;
        $vars['txt3'] = $txt3;
        $vars['txt4'] = $txt4;
    }
        View::render('evaluaciones/generar_dictamen.php', $vars);
    }
    
    public function generar_dictamen_html(){
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
        View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
        View::add_js('modules/sievas/scripts/evaluaciones/dictamen.js'); 

        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $sql_rubros = "SELECT
        lineamientos.nom_lineamiento,
        lineamientos.num_orden,
        lineamientos.id,
        lineamientos.atributos_lineamiento
        FROM
        evaluacion
        INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN lineamientos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        WHERE
        evaluacion.id = 53 and lineamientos.padre_lineamiento = 0 order by num_orden asc"; 
        
        $rubros = BD::$db->queryAll($sql_rubros);
        //var_dump($rubros);
        foreach($rubros as $key => $r){
            $sql_lineamientos = "SELECT
            gradacion_escalas.valor_escala,
            gradacion_escalas.desc_escala,
            lineamientos.nom_lineamiento,
            lineamientos.num_orden,
            lineamientos.id,
            lineamientos.atributos_lineamiento,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            INNER JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            LEFT JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE
            momento_evaluacion.cod_momento = 2 AND
            evaluacion.id = $evaluacion_actual AND
            lineamientos.padre_lineamiento = ".$r['id']."
            order by lineamientos.num_orden asc";
            
            $lin = BD::$db->queryAll($sql_lineamientos);
            foreach($lin as $l){
                $rubros[$key]['lineamientos'][$l['id']]['detalle'] = $lin;
            }
            
//            $sql_anexos = "SELECT
//            gen_documentos.ruta,
//            gen_documentos.nombre,
//            lineamientos.id
//            FROM
//            evaluacion
//            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
//            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
//            LEFT JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
//            LEFT JOIN lineamientos ON momento_resultado_anexo.cod_lineamiento = lineamientos.id
//            LEFT JOIN gen_documentos ON momento_resultado_anexo.cod_documento = gen_documentos.id
//            WHERE
//            momento_evaluacion.cod_momento = 1 AND
//            evaluacion.id = $evaluacion_actual AND
//            lineamientos.padre_lineamiento = ".$r['id']."
//            order by lineamientos.num_orden asc";
//            
//            $anexos = BD::$db->queryAll($sql_anexos);
//            foreach($anexos as $a){
//                $rubros[$key]['lineamientos'][$a['id']]['anexos'] = $a;
//            }
        }
        
        if($evaluacion['ev_red'] == '1'){
            //consultar items comunes
            //consultar items no comunes de cada centro
        }
        var_dump($rubros);
        $vars['rubros'] = $rubros;
        View::render('evaluaciones/generar_dictamen_html.php', $vars);
    }
    
    public function generar_predictamen(){
         View::add_js('public/js/jquery.validate.js');
         View::add_js('public/js/bootbox.min.js');
         View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
         View::add_js('modules/sievas/scripts/evaluaciones/predictamen.js');  
         View::add_css('public/js/select2/select2.css');
         View::add_css('public/js/select2/select2-bootstrap.css');
         View::add_js('public/js/select2/select2.min.js');
         View::add_js('public/js/select2/select2_locale_es.js');
         View::render('evaluaciones/generar_predictamen.php');
    }
    
    public function codigo_etica_v1(){
      View::render('evaluaciones/codigo_etica_v1.php');
    }
    
    public function manual_sievas_v2(){
      View::render('evaluaciones/manual_sievas_v2.php');
    }
     
    public function guia_ee_v1(){
      View::render('evaluaciones/guia_ee_v1.php');
    }
    
    
    public function previsualizar_predictamen(){
        $calificacion_interes = filter_input(INPUT_POST, 'calificacion_interes', FILTER_SANITIZE_STRING);
        $comentario_interes = filter_input(INPUT_POST, 'comentario_interes', FILTER_SANITIZE_STRING);
        $calificacion_disponibilidad = filter_input(INPUT_POST, 'calificacion_disponibilidad', FILTER_SANITIZE_STRING);
        $comentario_disponibilidad = filter_input(INPUT_POST, 'comentario_disponibilidad', FILTER_SANITIZE_STRING);
        $calificacion_documentacion = filter_input(INPUT_POST, 'calificacion_documentacion', FILTER_SANITIZE_STRING);
        $comentario_documentacion = filter_input(INPUT_POST, 'comentario_documentacion', FILTER_SANITIZE_STRING);
        $calificacion_mejora = filter_input(INPUT_POST, 'calificacion_mejora', FILTER_SANITIZE_STRING);
        $comentario_mejora = filter_input(INPUT_POST, 'comentario_mejora', FILTER_SANITIZE_STRING);
        
        $programa = '';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('GRANA');
        $pdf->SetTitle('Dictamen de evaluación');
        $pdf->SetSubject('Dictamen de evaluación');
        $pdf->SetKeywords('Dictamen, Grana, evaluación');
        

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetHeaderData('header.png', PDF_HEADER_LOGO_WIDTH+170, '', '', array(255,255,255), array(255,255,255));
        $pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP+17, PDF_MARGIN_RIGHT+10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setJPEGQuality(80);
        // ---------------------------------------------------------

        // set font
//        $pdf->SetFont('helvetica', 'B', 11);
//
//        // add a page
//        $pdf->AddPage();
//        
//        $pdf->Ln(10);
//        
//        $pdf->Write(0, $titulo, '', 0, 'L', true, 0, false, false, 0);
//        
//        $pdf->SetFont('helvetica', 'N', 11);    
//        
//        $pdf->Ln(5);
       

        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "PREDICTAMEN DE EVALUACIÓN EXTERNA DEL PROGRAMA ".$programa, '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "Evaluadores externos", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Director de GRANA", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "PRESENTACIÓN", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->Write(0, "La filosofía de GRANA es impulsar la calidad de la educación superior en nuestra región, "
            ."con pertinencia, cobertura e innovación. La sociedad actual demanda que los sistemas "
            ."educativos respondan a los requerimientos de la comunidad global, donde la cooperación académica internacional y la "
            ."construcción de la verdadera internacionalización esté orientada a formar individuos "
            ."comprometidos con el desarrollo sustentable, con una verdadera vocación de servicio, un perfil "
            ."competitivo en el entorno hemisférico, con sentido ético y principios morales.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        $pdf->Write(0,"Los efectos de la globalización de la economía; el desarrollo de nuevas herramientas tecnológicas,"
                . " asi como la diversificación de fuentes y formas de empleo han dado margen a un reacomodo en las estrategias "
            ."formativas del individuo. La inclusión de internet ha ubicado a las nuevas generaciones en un espacio que"
                . " facilita el acceso ilimitado a la información, orillando a las instituciones educativas a diseñar"
                . " nuevas estrategias de enseñanza, que privilegien el análisis y la comprensión para lograr aprendizajes significativos. "
                . ""
            ."" , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        $pdf->Write(0,"Por su parte, los procesos de internacionalización son el resultado de un orden "
                . "basado en políticas, normas y estrategias de la"
                . " UNESCO, OEA, OCDE, la declaratoria de BOLOGNIA, entre otros. Ellos "
            ."coinciden en que la educación ofrecida por establecimientos educativos deberá "
            ."considerar que los entes académicos y universitarios interactúen en redes profesionales y especializadas, "
            ."que el estudiante conozca otras culturas y respete sus usos y costumbres, y "
            ."requiere que se creen las condiciones para que los destinatarios de la educación superior "
            ."participe como actor en los procesos de cambio, en el contexto "
            ."nacional e internacional.\n" , '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0,"En este contexto se impone a las instituciónes de "
            ."educación superior el desarrollo de políticas que promuevan la"
                . " atención crítica de la calidad y la pertinencia, y por ello "
                . "deberán incluir a estudiantes, egresados, profesores, administrativos. "
            ."Asimismo acciones de investigación, docencia, extensión, vinculación y difusión, "
            ."deberán entenderse y enfocarse como factores para el desarrollo y el cambio social.                                   " , '', 0, 'J', true, 0, false, false, 0);
            
        
       
        $pdf->Output('predictamen.pdf', 'I');  
        
    }
    
    public function guardar_dictamen(){
        header('Content-Type: application/json');
        $codigo_dictamen = filter_input(INPUT_POST, 'codigo_dictamen', FILTER_SANITIZE_STRING);
        $clave = filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING);
        $contexto = filter_input(INPUT_POST, 'contexto', FILTER_SANITIZE_STRING);
        $percepcion = filter_input(INPUT_POST, 'percepcion', FILTER_SANITIZE_STRING);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $introduccion = filter_input(INPUT_POST, 'introduccion', FILTER_SANITIZE_STRING);
        $txt1 = filter_input(INPUT_POST, 'txt1', FILTER_SANITIZE_STRING);
        $txt2 = filter_input(INPUT_POST, 'txt2', FILTER_SANITIZE_STRING);
        $txt3 = filter_input(INPUT_POST, 'txt3', FILTER_SANITIZE_STRING);
        $txt4 = filter_input(INPUT_POST, 'txt4', FILTER_SANITIZE_STRING);
        
        $valid = true;
        
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
            $sql_evaluacion = sprintf('select finalizado from evaluacion where id=%s', $evaluacion);
            $finalizado = BD::$db->queryOne($sql_evaluacion);
            
            if($finalizado > 0){
                $model_evaluacion_dictamen = new evaluacion_dictamen();
                $model_evaluacion_dictamen->codigo_dictamen = $codigo_dictamen;
                $model_evaluacion_dictamen->cod_evaluacion = Auth::info_usuario('evaluacion');
                $model_evaluacion_dictamen->clave = $clave;
                $model_evaluacion_dictamen->contexto = $contexto;
                $model_evaluacion_dictamen->percepcion = $percepcion;
                $model_evaluacion_dictamen->titulo = $titulo;
                $model_evaluacion_dictamen->introduccion = $introduccion;
                $model_evaluacion_dictamen->txt1 = $txt1;
                $model_evaluacion_dictamen->txt2 = $txt2;
                $model_evaluacion_dictamen->txt3 = $txt3;
                $model_evaluacion_dictamen->txt4 = $txt4;
                
                $sql_check = sprintf('select id from evaluacion_dictamen where cod_evaluacion = %s', $evaluacion);
                $check = BD::$db->queryOne($sql_check);
                if($check > 0){
                    $model_evaluacion_dictamen->id = $check;
                    if($model_evaluacion_dictamen->update()){
                        
                    }
                    else{
                        $valid = false;
                    }
                }
                else{
                    if($model_evaluacion_dictamen->save()){
                        
                    }
                    else{
                        $valid = false;
                    }
                }
            
         
        if($valid){
            
          
        $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", 2, $evaluacion);
        $momento_evaluacion = BD::$db->queryOne($sql_momento);
        
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        foreach($rubros as $key=>$r){
            $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            gradacion_escalas.desc_escala,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    Auth::info_usuario('evaluacion'), 
                    $momento_evaluacion);
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            
            $calificaciones_rubro = array();
            foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                              $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                              $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                              $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                              $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                              $calificaciones_rubro[] = $ld['valor_escala'];
                        }
                    }
                }                
            }
            
          
           $rubros[$key]['lineamientos'] = $lineamientos;           
           $rubros[$key]['calificaciones_rubro'] = $calificaciones_rubro;           
          
         }
        
        
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('GRANA');
        $pdf->SetTitle('Dictamen de evaluación');
        $pdf->SetSubject('Dictamen de evaluación');
        $pdf->SetKeywords('Dictamen, Grana, evaluación');
        

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetHeaderData('header.png', PDF_HEADER_LOGO_WIDTH+185, '', '', array(255,255,255), array(255,255,255));
        $pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP+17, PDF_MARGIN_RIGHT+10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setJPEGQuality(80);
        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();

        $pdf->Write(0, 'Reporte dictamen: '.$codigo_dictamen, '', 0, 'R', true, 0, false, false, 0);
        $pdf->Write(0, 'Clave: '.$clave, '', 0, 'R', true, 0, false, false, 0);
        
        $pdf->Ln(10);
        
        $pdf->Write(0, $titulo, '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'N', 11);    
        
        $pdf->Ln(5);
        
        $introduccion2 = "Los procesos y procedimientos de la evaluación se realizaron bajo los principios filosóficos y ";
        $introduccion2 .= "procedimentales de GRANA, de acuerdo a lo señalado en el presente documento.";
        
        $pdf->Ln(10);
        
        $pdf->Write(0, $introduccion, '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        
        $pdf->Write(0, $introduccion2, '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "I. Presentación", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->Write(0, "La misión de GRANA es impulsar la calidad de la educación superior en nuestra región, "
            ."con pertinencia, cobertura e innovación. La sociedad actual demanda que los sistemas "
            ."educativos respondan a una sociedad global, donde la cooperación académica internacional y la "
            ."construcción de la verdadera internacionalización esté orientada a formar individuos "
            ."comprometidos con el desarrollo sustentable, con una verdadera vocación de servicio, un perfil "
            ."competitivo en el entorno hemisférico, con sentido ético y principios morales.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        $pdf->Write(0,"Los efectos globalizadores de la economía han dado margen a un reacomodo en las estrategias "
            ."formativas del individuo. Los procesos de internacionalización son el resultado de un orden "
            ."basado en políticas, normas y estrategias de organismos de prestigio internacional." , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        $pdf->Write(0,"Organismos como UNESCO, OEA, OCDE, junto a la declaratoria de BOLOGNIA, entre otros, "
            ."coinciden en señalar que la educación ofrecida por establecimientos educativos deberá "
            ."considerar que los entes académicos y universitarios interactúen en redes académicas y sociales, "
            ."que el estudiante conozca otras culturas y respete los usos y costumbres de ellas, todo lo cual "
            ."requiere que se creen las condiciones para que los destinatarios de la educación superior "
            ."participen como actores activos y responsables en los procesos de cambio, en el contexto "
            ."nacional e internacional.\n" , '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0,"En este escenario global e interdisciplinario, la dimensión internacional de una institución de "
            ."educación superior deberá incluir a estudiantes, egresados, profesores, administrativos. "
            ."Asimismo tendrá que incorporar investigación, docencia, extensión, vinculación y difusión, así "
            ."como las tecnologías de la información como un factor de desarrollo y cambio." , '', 0, 'J', true, 0, false, false, 0);
            
                
        $presentacion = "En el marco de los procedimientos aplicados por GRANA, como parte del proceso de gestión "
        ."para evaluar la calidad del ".$datos_evaluado['tipo_evaluado']." de ".$datos_evaluado['nombre_evaluado'].", "
        ."se ha observado un compromiso institucional en la mejora de su oferta académica, se denota "
        ."entusiasmo, interés y compromiso por la comunidad universitaria de ".$datos_evaluado['adscrito']." "
        ."y especialmente se destaca la labor del equipo conformado por el comité de evaluación interna CEI.                                ";
        
        $pdf->Write(0, $presentacion , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "II. Metodología", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        
        $pdf->Write(0, $txt1 , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Write(0, "Conformación del comité de evaluación interna;" , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Capacitación en línea;" , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Integración de información estadística; Evaluación interna;" , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Evaluación externa en línea, visita a la de evaluadores externos; " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Entrega de predictamen a la Institución; " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Evaluación de colegio y sector social vinculado a la disciplina de la Formación;  " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Reporte-dictamen de resultados de la evaluación; la certificación y la deducción del proceso de mejora permanente; " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "y la metaevaluación del proceso. " , '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "III. Resultados en la aplicación metodológica", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Write(0, "La integración y conformación del Comité de Evaluación Interna CEI, fue adecuado toda vez "
        ."que durante el proceso de evaluación-certificación se observó un excelente equipo de trabajo "
        ."cuya comunicación entre sus integrantes: Directivos, profesores y estudiantes, fue factor "
        ."oportuno para la buena integración de la información solicitada.\n" , '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "Con la capacitación del CEI mediante el curso virtual de GRANA, se lograron "
        ."comprender los contextos y tendencias internacionales sobre la calidad de la educación superior "
        ."en el hemisferio. Así también, el manejo de formatos para la integración de información "
        ."estadística del programa educativo.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "La integración de información estadística se fundamenta en 10 rubros:            ", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Write(0, "     1. Impacto Social de la Formación.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     2. Resultados de la Investigación vinculados a la Formación. ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     3. Ingreso, Permanencia y Eficiencia Terminal en la Formación.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     4. Profesores Vinculados a la Formación. ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     5. Pertinencia del Modelo Educativo y Estructura Curricular.", '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->addPage();
        
        $pdf->Write(0, "     5. Pertinencia del Modelo Educativo y Estructura Curricular.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     6. Estrategias Metodológicas de Aprendizaje.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     7. Infraestructura, Equipamiento, Tecnologías y Bibliografía en la Formación. ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     8. Impacto de las Actividades de Extensión, Vinculación y Difusión en la Formación.  ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     9. Reconocimiento Internacional de la Formación.  ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     10. Impacto en la Pertinencia de la Normatividad, la Administración y las Finanzas como Facilitadoras en la Formación.  ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "El CEI fue responsable de integrar la evaluación interna comprendida en los 10 rubros, "
        ."conteniendo cada uno 10 items, siendo un total de 100 bajo una evaluación cualitativa a los "
        ."cuales se les adjuntó documentación de prueba a los efectos de su constatación y verificación.", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        
        
        $pdf->Write(0, $txt2 , '', 0, 'J', true, 0, false, false, 0);        
        $pdf->Ln(5);
        $pdf->Write(0, "El Proceso de Mejoramiento Permanente (PMP), deberá ser un elemento fundamental en los "
        ."planes estratégicos de desarrollo de la institución en pro de la verdadera calidad académica y "
        ."profesional que se requiere para impulsar la capacidad y competitividad de la región.", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "Deberá ser permanente y no en intervalos. Con una evaluación constante en los logros de sus "
        ."metas, es decir, el compromiso de los entes involucrados en el proceso formativo del futuro "
        ."profesional será decisivo en el cumplimiento del PMC.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "IV. Contexto", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);        
        
        //contexto        
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "V. Resultados de la evaluación", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);     
        
        //resultados
        $i = 1;
        
        foreach ($rubros as $r){ 
            $html = '';
            $html .=         '<p><strong>RUBRO '.$i.". ".$r['nom_lineamiento'].'</strong></p>';
            $c = 1;
            foreach($r['lineamientos'] as $l){ 
                $html .= '<strong>'.$i.'.'.$c.'. '. $l['nom_lineamiento'].'</strong><br/><br/>'; 
                $html .= '<strong>Valoración</strong><br/>';
                $html .= ($l['desc_escala'] == null ? 'N/A' : urldecode($l['desc_escala'])).'<br/><br/>';
                $html .= '<strong>Fortalezas</strong>';
                $html .= '<p style="text-align:justify">'.($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas'])).'</p>';
                $html .= '<p style="text-align:justify">'.($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades'])).'</p>'; 
                $html .= '<strong>Plan de mejoramiento</strong>';
                $html .= '<p style="text-align:justify">'.($l['plan_mejoramiento'] == null ? 'N/A' : urldecode($l['plan_mejoramiento'])).'</p>';
                $html .= '<br/>';
                $c++;
                        } 
                $i++; 
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->Write(0, "Grafindicámetro del rubro: ".$r['nom_lineamiento'], '', 0, 'L', true, 0, false, false, 0);
                $pdf->Ln(5);
                $pdf->SetFont('helvetica', 'N', 11); 
                $pdf->Image($this->plot_image($r['calificaciones_rubro'])); 
                $pdf->AddPage();
        }         
              
        
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "VI. Percepción de los entes involucrados en el programa educativo", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);
        $pdf->Write(0, "La percepción en general de directivos, administrativos, profesores, estudiantes y egresados "
        ."sobre el programa educativo, es aceptable toda vez que sus comentarios y observaciones fueron "
        ."realistas con una notoria responsabilidad ética y profesional, en la construcción de propuestas "
        ."viables en impulsar su calidad.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "VII. Niveles de calidad", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);
        $pdf->Write(0, "Existen 3 niveles de calidad como resultado de un proceso de evaluación para una posible "
        ."certificación. Cada nivel tiene una pequeña escala de valores que identifica con mayor precisión "
        ."la calidad.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Write(0, "Tercer nivel de calidad comprende la siguiente escala:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "0.1 a 1 Es muy escasa la calidad, 1.1 a 2 Es escasa la calidad, 2.1 a 3 medianamente escasa la calidad.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Segundo nivel de calidad comprende la siguiente escala:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "3.1 a 4 poco incipiente la calidad, 4.1 a 5 medianamente incipiente la calidad, 5.1 a 6 muy incipiente la calidad.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Primer nivel de calidad comprende la siguiente escala:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "6.1 a 7 incipientemente alta la calidad, 7.1 a 8 medianamente alta la calidad, 8.1 a 9 alta la calidad, 9.1 a 10 muy alta la calidad", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Solo es posible acceder a una certificación de calidad cuando el resultado final de los proceso de la evaluación corresponde al primer nivel de calidad.", '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "VIII. Conclusión", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);

        $pdf->Write(0, $txt3 , '', 0, 'J', true, 0, false, false, 0);
        
        //grafindicametro general
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "IX. Seguimiento al incremento en el puntaje o en el nivel de calidad", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);
        $pdf->Write(0, "GRANA al contar con un sistema de procesos y procedimientos basados en una "
        ."evaluación permanente en línea, ofrece los servicios de reevaluación ante un posible "
        ."incremento en los niveles de calidad o superación de debilidades siguiendo con el "
        ."proceso metodológico arriba señalado.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Write(0, "De acuerdo al conjunto de actividades planeadas, programadas, con recursos de apoyo y "
        ."evaluadas, así como las relacionadas a las estrategias utilizadas para superar las "
        ."debilidades y mantener las fortalezas que el programa educativo habrá que atender con "
        ."metas claras y concretas. Se recomienda accionar el conjunto de acciones que "
        ."conforman el plan de mejoramiento a la brevedad que opere de acuerdo a las "
        ."necesidades detectadas del programa educativo, considerando: estructura institucional, "
        ."metas a realizar, cronograma de actividades y responsables de cada una de ellas.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);

        $pdf->Write(0, $txt4 , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetMargins(50, 10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Ln(5);
        $pdf->Write(0, "Atentamente", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Ln(1);
        $pdf->Write(0, "Comisión evaluadora", '', 0, 'C', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(30);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "Dr. Donato Vallín Gonzalez", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->Write(0, "Director General del Programa: Generation of Resourses por Accreditaton In Nations of The Americas( GRANA)", '', 0, 'C', true, 0, false, false, 0);
        
        $evaluacion_actual = Auth::info_usuario('evaluacion');
         $sql_comite = "SELECT
            comite.cod_persona,
            evaluacion.id,
            comite.cod_momento_evaluacion,
            comite.cod_cargo,
            momento_evaluacion.cod_momento,
            momento_evaluacion.fecha_inicia,
            momento_evaluacion.fecha_termina,
            gen_persona.nombres,
            gen_persona.primer_apellido,
            gen_persona.segundo_apellido,
            niveles_formacion.nivel_formacion
            FROM
            comite
            INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            LEFT JOIN gen_persona_formacion ON gen_persona.id = gen_persona_formacion.cod_persona
            LEFT JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
            WHERE
            evaluacion.id = $evaluacion_actual";            
            
            $comite = BD::$db->queryAll($sql_comite);         
            
            $_comite = array();
            
            foreach($comite as $c){
                $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
            }
            
            $cargos_cee = array(
            '1' => 'Coordinador',
            '2' => 'Evaluador',
            '3' => 'Evaluador',
            '4' => 'Evaluador'
        ); 
            
        foreach($_comite[2] as $cee){
            $pdf->Ln(30);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Write(0, $cee[0]['nombres'], '', 0, 'C', true, 0, false, false, 0);
            $pdf->SetFont('helvetica', 'N', 11);
            $pdf->Write(0, $cargos_cee[$cee[0]['cod_cargo']]. ' de la evaluación externa', '', 0, 'C', true, 0, false, false, 0);
        }

        
        date_default_timezone_set('America/Bogota');
        $now = new DateTime();
        $nombre = 'dictamen-'.$now->getTimestamp().'.pdf';
        $rel_path = '/files/documentos/'.$nombre;
        
        $pdf->Output(PUBLIC_PATH.$rel_path, 'F');
        
        $model_documentos = new gen_documentos();
        $model_documentos->ruta = 'public'.$rel_path;
        $model_documentos->nombre = $nombre;
        if($model_documentos->save()){
            $model_evaluacion_dictamen->cod_documento = $model_documentos->id;
            if($model_evaluacion_dictamen->update()){
                echo json_encode(array('mensaje' => 'El dictamen se ha generado correctamente'));
            }
            else{
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo 'No se pudo generar el dictamen';
            }
        }        
        
            }
        else{
            
                //no se pudo guardar la informacion
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo 'No se pudo guardar la información';
            }
            }
            else{
            //la evaluacion no ha finalizado
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo 'La evaluación no ha finalizado';
            }
            
        }
        else{
            //la evaluacion no esta definida
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo 'La evaluación no esta definida';
        }
        
        
        
        }
        

    public function ver_dictamen(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_evaluacion_dictamen = sprintf("SELECT
        gen_documentos.ruta
        FROM
        evaluacion
        INNER JOIN gen_documentos ON evaluacion.dictamen_id = gen_documentos.id
        where evaluacion.id = %s", $evaluacion);
        $evaluacion_dictamen = BD::$db->queryOne($sql_evaluacion_dictamen);
        if($evaluacion_dictamen != null && $evaluacion_dictamen != ''){
            header('Location: '.$evaluacion_dictamen);
        }
        else{
            header('Location: public/files/tutorias/tramitep.pdf');
        }
    }
    
    public function ver_predictamen(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $sql_evaluacion_predictamen = sprintf("SELECT
        gen_documentos.ruta
        FROM
        evaluacion
        INNER JOIN gen_documentos ON evaluacion.predictamen_id = gen_documentos.id
        where evaluacion.id = %s", $evaluacion);
        $evaluacion_predictamen = BD::$db->queryOne($sql_evaluacion_predictamen);
        if($evaluacion_predictamen != null && $evaluacion_predictamen != ''){
            header('Location: '.$evaluacion_predictamen);
        }
        else{
            header('Location: public/files/tutorias/tramitep.pdf');
        }
    }
        
        
    public function previsualizar_dictamen(){
        $codigo_dictamen = filter_input(INPUT_POST, 'codigo_dictamen', FILTER_SANITIZE_STRING);
        $clave = filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING);
        $contexto = filter_input(INPUT_POST, 'contexto', FILTER_SANITIZE_STRING);
        $percepcion = filter_input(INPUT_POST, 'percepcion', FILTER_SANITIZE_STRING);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $introduccion = filter_input(INPUT_POST, 'introduccion', FILTER_SANITIZE_STRING);
        $txt1 = filter_input(INPUT_POST, 'txt1', FILTER_SANITIZE_STRING);
        $txt2 = filter_input(INPUT_POST, 'txt2', FILTER_SANITIZE_STRING);
        $txt3 = filter_input(INPUT_POST, 'txt3', FILTER_SANITIZE_STRING);
        $txt4 = filter_input(INPUT_POST, 'txt4', FILTER_SANITIZE_STRING);   
        
        $evaluacion = Auth::info_usuario('evaluacion');
        if($evaluacion > 0){
        $sql_momento = sprintf("select id from momento_evaluacion where cod_momento = %s and cod_evaluacion = %s", 2, $evaluacion);
        $momento_evaluacion = BD::$db->queryOne($sql_momento);
        
        $sql_rubros = sprintf('SELECT
	lineamientos.id,
	lineamientos.nom_lineamiento,
	lineamientos.padre_lineamiento
        FROM
        lineamientos
        INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
        INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
        INNER JOIN evaluacion ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        WHERE
        lineamientos.padre_lineamiento = 0 AND
        evaluacion.id = %s
        ', Auth::info_usuario('evaluacion'));        
        

        $rubros = BD::$db->queryAll($sql_rubros);
        
        foreach($rubros as $key=>$r){
            $sql_lineamientos = sprintf("SELECT
                    lineamientos.id AS lineamiento_id,
                    lineamientos.nom_lineamiento,
                    lineamientos.padre_lineamiento
            FROM
                    lineamientos
            INNER JOIN lineamientos_detalle_conjuntos ON lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            INNER JOIN lineamientos_conjuntos ON lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            WHERE
                    lineamientos.padre_lineamiento = %s", $r['id']);
            
            $lineamientos = BD::$db->queryAll($sql_lineamientos);
            
            
            $sql_lineamientos_data = sprintf("SELECT
            lineamientos.id,
            momento_resultado_detalle.fortalezas,
            momento_resultado_detalle.debilidades,
            momento_resultado_detalle.plan_mejoramiento,
            momento_resultado_detalle.cod_gradacion_escala,
            gradacion_escalas.desc_escala,
            gradacion_escalas.valor_escala,
            lineamientos.padre_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            LEFT JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            LEFT JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
            LEFT JOIN lineamientos ON momento_resultado_detalle.cod_lineamiento = lineamientos.id
            INNER JOIN gradacion_escalas ON momento_resultado_detalle.cod_gradacion_escala = gradacion_escalas.id
            WHERE lineamientos.padre_lineamiento = %s AND evaluacion.id = %s AND momento_evaluacion.id = %s",
                    $r['id'], 
                    Auth::info_usuario('evaluacion'), 
                    $momento_evaluacion);
            
            $lineamientos_data = BD::$db->queryAll($sql_lineamientos_data);   
            
            $calificaciones_rubro = array();
            foreach($lineamientos_data as $ld){
                if($r['id'] === $ld['padre_lineamiento']){
                    foreach($lineamientos as $k=>$l){
                        if($l['lineamiento_id'] === $ld['id']){
                              $lineamientos[$k]['fortalezas'] = $ld['fortalezas'];
                              $lineamientos[$k]['debilidades'] = $ld['debilidades'];
                              $lineamientos[$k]['plan_mejoramiento'] = $ld['plan_mejoramiento'];
                              $lineamientos[$k]['desc_escala'] = $ld['desc_escala'];
                              $calificaciones_rubro[] = $ld['valor_escala'];
                        }
                    }
                }                
            }
            
          
           $rubros[$key]['lineamientos'] = $lineamientos;           
           $rubros[$key]['calificaciones_rubro'] = $calificaciones_rubro;           
          
         }
        }
        
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('GRANA');
        $pdf->SetTitle('Dictamen de evaluación');
        $pdf->SetSubject('Dictamen de evaluación');
        $pdf->SetKeywords('Dictamen, Grana, evaluación');
        

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetHeaderData('header.png', PDF_HEADER_LOGO_WIDTH+185, '', '', array(255,255,255), array(255,255,255));
        $pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP+17, PDF_MARGIN_RIGHT+10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setJPEGQuality(80);
        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();

        $pdf->Write(0, 'Reporte dictamen: '.$codigo_dictamen, '', 0, 'R', true, 0, false, false, 0);
        $pdf->Write(0, 'Clave: '.$clave, '', 0, 'R', true, 0, false, false, 0);
        
        $pdf->Ln(10);
        
        $pdf->Write(0, $titulo, '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'N', 11);    
        
        $pdf->Ln(5);
        
        $introduccion2 = "Los procesos y procedimientos de la evaluación se realizaron bajo los principios filosóficos y ";
        $introduccion2 .= "procedimentales de GRANA, de acuerdo a lo señalado en el presente documento.";
        
        $pdf->Ln(10);
        
        $pdf->Write(0, $introduccion, '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        
        $pdf->Write(0, $introduccion2, '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "I. Presentación", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->Write(0, "La misión de GRANA es impulsar la calidad de la educación superior en nuestra región, "
            ."con pertinencia, cobertura e innovación. La sociedad actual demanda que los sistemas "
            ."educativos respondan a una sociedad global, donde la cooperación académica internacional y la "
            ."construcción de la verdadera internacionalización esté orientada a formar individuos "
            ."comprometidos con el desarrollo sustentable, con una verdadera vocación de servicio, un perfil "
            ."competitivo en el entorno hemisférico, con sentido ético y principios morales.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        $pdf->Write(0,"Los efectos globalizadores de la economía han dado margen a un reacomodo en las estrategias "
            ."formativas del individuo. Los procesos de internacionalización son el resultado de un orden "
            ."basado en políticas, normas y estrategias de organismos de prestigio internacional." , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Ln(5);
        $pdf->Write(0,"Organismos como UNESCO, OEA, OCDE, junto a la declaratoria de BOLOGNIA, entre otros, "
            ."coinciden en señalar que la educación ofrecida por establecimientos educativos deberá "
            ."considerar que los entes académicos y universitarios interactúen en redes académicas y sociales, "
            ."que el estudiante conozca otras culturas y respete los usos y costumbres de ellas, todo lo cual "
            ."requiere que se creen las condiciones para que los destinatarios de la educación superior "
            ."participen como actores activos y responsables en los procesos de cambio, en el contexto "
            ."nacional e internacional.\n" , '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0,"En este escenario global e interdisciplinario, la dimensión internacional de una institución de "
            ."educación superior deberá incluir a estudiantes, egresados, profesores, administrativos. "
            ."Asimismo tendrá que incorporar investigación, docencia, extensión, vinculación y difusión, así "
            ."como las tecnologías de la información como un factor de desarrollo y cambio." , '', 0, 'J', true, 0, false, false, 0);
            
                
        $presentacion = "En el marco de los procedimientos aplicados por GRANA, como parte del proceso de gestión "
        ."para evaluar la calidad del ".$datos_evaluado['tipo_evaluado']." de ".$datos_evaluado['nombre_evaluado'].", "
        ."se ha observado un compromiso institucional en la mejora de su oferta académica, se denota "
        ."entusiasmo, interés y compromiso por la comunidad universitaria de ".$datos_evaluado['adscrito']." "
        ."y especialmente se destaca la labor del equipo conformado por el comité de evaluación interna CEI.                                ";
        
        $pdf->Write(0, $presentacion , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "II. Metodología", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        
        $pdf->Write(0, $txt1 , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->Write(0, "Conformación del comité de evaluación interna;" , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Capacitación en línea;" , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Integración de información estadística; Evaluación interna;" , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Evaluación externa en línea, visita a la de evaluadores externos; " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Entrega de predictamen a la Institución; " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Evaluación de colegio y sector social vinculado a la disciplina de la Formación;  " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Reporte-dictamen de resultados de la evaluación; la certificación y la deducción del proceso de mejora permanente; " , '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "y la metaevaluación del proceso. " , '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "III. Resultados en la aplicación metodológica", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Write(0, "La integración y conformación del Comité de Evaluación Interna CEI, fue adecuado toda vez "
        ."que durante el proceso de evaluación-certificación se observó un excelente equipo de trabajo "
        ."cuya comunicación entre sus integrantes: Directivos, profesores y estudiantes, fue factor "
        ."oportuno para la buena integración de la información solicitada.\n" , '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "Con la capacitación del CEI mediante el curso virtual de GRANA, se lograron "
        ."comprender los contextos y tendencias internacionales sobre la calidad de la educación superior "
        ."en el hemisferio. Así también, el manejo de formatos para la integración de información "
        ."estadística del programa educativo.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "La integración de información estadística se fundamenta en 10 rubros:            ", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Write(0, "     1. Impacto Social de la Formación.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     2. Resultados de la Investigación vinculados a la Formación. ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     3. Ingreso, Permanencia y Eficiencia Terminal en la Formación.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     4. Profesores Vinculados a la Formación. ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     5. Pertinencia del Modelo Educativo y Estructura Curricular.", '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->addPage();
        
        $pdf->Write(0, "     5. Pertinencia del Modelo Educativo y Estructura Curricular.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     6. Estrategias Metodológicas de Aprendizaje.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     7. Infraestructura, Equipamiento, Tecnologías y Bibliografía en la Formación. ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     8. Impacto de las Actividades de Extensión, Vinculación y Difusión en la Formación.  ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     9. Reconocimiento Internacional de la Formación.  ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "     10. Impacto en la Pertinencia de la Normatividad, la Administración y las Finanzas como Facilitadoras en la Formación.  ", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "El CEI fue responsable de integrar la evaluación interna comprendida en los 10 rubros, "
        ."conteniendo cada uno 10 items, siendo un total de 100 bajo una evaluación cualitativa a los "
        ."cuales se les adjuntó documentación de prueba a los efectos de su constatación y verificación.", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        
        
        $pdf->Write(0, $txt2 , '', 0, 'J', true, 0, false, false, 0);        
        $pdf->Ln(5);
        $pdf->Write(0, "El Proceso de Mejoramiento Permanente (PMP), deberá ser un elemento fundamental en los "
        ."planes estratégicos de desarrollo de la institución en pro de la verdadera calidad académica y "
        ."profesional que se requiere para impulsar la capacidad y competitividad de la región.", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, "Deberá ser permanente y no en intervalos. Con una evaluación constante en los logros de sus "
        ."metas, es decir, el compromiso de los entes involucrados en el proceso formativo del futuro "
        ."profesional será decisivo en el cumplimiento del PMC.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "IV. Contexto", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);        
        
        //contexto        
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Write(0, "V. Resultados de la evaluación", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);     
        
        //resultados
        $i = 1;
        
        foreach ($rubros as $r){ 
            $html = '';
            $html .=         '<p><strong>RUBRO '.$i.". ".$r['nom_lineamiento'].'</strong></p>';
            $c = 1;
            foreach($r['lineamientos'] as $l){ 
                $html .= '<strong>'.$i.'.'.$c.'. '. $l['nom_lineamiento'].'</strong><br/><br/>'; 
                $html .= '<strong>Valoración</strong><br/>';
                $html .= ($l['desc_escala'] == null ? 'N/A' : urldecode($l['desc_escala'])).'<br/><br/>';
                $html .= '<strong>Fortalezas</strong>';
                $html .= '<p style="text-align:justify">'.($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas'])).'</p>';
                $html .= '<p style="text-align:justify">'.($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades'])).'</p>'; 
                $html .= '<strong>Plan de mejoramiento</strong>';
                $html .= '<p style="text-align:justify">'.($l['plan_mejoramiento'] == null ? 'N/A' : urldecode($l['plan_mejoramiento'])).'</p>';
                $html .= '<br/>';
                $c++;
                        } 
                $i++; 
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->Write(0, "Grafindicámetro del rubro: ".$r['nom_lineamiento'], '', 0, 'L', true, 0, false, false, 0);
                $pdf->Ln(5);
                $pdf->SetFont('helvetica', 'N', 11); 
                $pdf->Image($this->plot_image($r['calificaciones_rubro'])); 
                $pdf->AddPage();
        } 
        
        
              
        
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "VI. Percepción de los entes involucrados en el programa educativo", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);
        $pdf->Write(0, "La percepción en general de directivos, administrativos, profesores, estudiantes y egresados "
        ."sobre el programa educativo, es aceptable toda vez que sus comentarios y observaciones fueron "
        ."realistas con una notoria responsabilidad ética y profesional, en la construcción de propuestas "
        ."viables en impulsar su calidad.\n", '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "VII. Niveles de calidad", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);
        $pdf->Write(0, "Existen 3 niveles de calidad como resultado de un proceso de evaluación para una posible "
        ."certificación. Cada nivel tiene una pequeña escala de valores que identifica con mayor precisión "
        ."la calidad.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Write(0, "Tercer nivel de calidad comprende la siguiente escala:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "0.1 a 1 Es muy escasa la calidad, 1.1 a 2 Es escasa la calidad, 2.1 a 3 medianamente escasa la calidad.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Segundo nivel de calidad comprende la siguiente escala:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "3.1 a 4 poco incipiente la calidad, 4.1 a 5 medianamente incipiente la calidad, 5.1 a 6 muy incipiente la calidad.", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Primer nivel de calidad comprende la siguiente escala:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "6.1 a 7 incipientemente alta la calidad, 7.1 a 8 medianamente alta la calidad, 8.1 a 9 alta la calidad, 9.1 a 10 muy alta la calidad", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Solo es posible acceder a una certificación de calidad cuando el resultado final de los proceso de la evaluación corresponde al primer nivel de calidad.", '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "VIII. Conclusión", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);

        $pdf->Write(0, $txt3 , '', 0, 'J', true, 0, false, false, 0);
        
        //grafindicametro general
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(5);
        $pdf->Write(0, "IX. Seguimiento al incremento en el puntaje o en el nivel de calidad", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(5);
        $pdf->Write(0, "GRANA al contar con un sistema de procesos y procedimientos basados en una "
        ."evaluación permanente en línea, ofrece los servicios de reevaluación ante un posible "
        ."incremento en los niveles de calidad o superación de debilidades siguiendo con el "
        ."proceso metodológico arriba señalado.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Write(0, "De acuerdo al conjunto de actividades planeadas, programadas, con recursos de apoyo y "
        ."evaluadas, así como las relacionadas a las estrategias utilizadas para superar las "
        ."debilidades y mantener las fortalezas que el programa educativo habrá que atender con "
        ."metas claras y concretas. Se recomienda accionar el conjunto de acciones que "
        ."conforman el plan de mejoramiento a la brevedad que opere de acuerdo a las "
        ."necesidades detectadas del programa educativo, considerando: estructura institucional, "
        ."metas a realizar, cronograma de actividades y responsables de cada una de ellas.\n", '', 0, 'J', true, 0, false, false, 0);
        $pdf->Ln(5);

        $pdf->Write(0, $txt4 , '', 0, 'J', true, 0, false, false, 0);
        
        $pdf->AddPage();
        $pdf->SetMargins(50, 10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Ln(5);
        $pdf->Write(0, "Atentamente", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Ln(1);
        $pdf->Write(0, "Comisión evaluadora", '', 0, 'C', true, 0, false, false, 0);
        
        $pdf->SetFont('helvetica', 'N', 11);
        
        $pdf->Ln(30);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "Dr. Donato Vallín Gonzalez", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->Write(0, "Director General del Programa: Generation of Resourses por Accreditaton In Nations of The Americas( GRANA)", '', 0, 'C', true, 0, false, false, 0);
        
        $evaluacion_actual = Auth::info_usuario('evaluacion');
         $sql_comite = "SELECT
            comite.cod_persona,
            evaluacion.id,
            comite.cod_momento_evaluacion,
            comite.cod_cargo,
            momento_evaluacion.cod_momento,
            momento_evaluacion.fecha_inicia,
            momento_evaluacion.fecha_termina,
            gen_persona.nombres,
            gen_persona.primer_apellido,
            gen_persona.segundo_apellido,
            niveles_formacion.nivel_formacion
            FROM
            comite
            INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            LEFT JOIN gen_persona_formacion ON gen_persona.id = gen_persona_formacion.cod_persona
            LEFT JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
            WHERE
            evaluacion.id = $evaluacion_actual";            
            
            $comite = BD::$db->queryAll($sql_comite);         
            
            $_comite = array();
            
            foreach($comite as $c){
                $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
            }
            
            $cargos_cee = array(
            '1' => 'Coordinador',
            '2' => 'Evaluador',
            '3' => 'Evaluador',
            '4' => 'Evaluador'
        ); 
            
        foreach($_comite[2] as $cee){
            $pdf->Ln(30);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Write(0, $cee[0]['nombres'], '', 0, 'C', true, 0, false, false, 0);
            $pdf->SetFont('helvetica', 'N', 11);
            $pdf->Write(0, $cargos_cee[$cee[0]['cod_cargo']]. ' de la evaluación externa', '', 0, 'C', true, 0, false, false, 0);
        }
        
        
        $pdf->Output('example_001.pdf', 'I');   
        
    }
    
    private function plot_image($data){
         // Create the basic rtadar graph
        $graph = new RadarGraph(500,400);
        $graph->SetFrame(false);
        // Set background color and shadow
        $graph->SetColor("white");
        $graph->SetShadow();

        // Position the graph
        $graph->SetCenter(0.4,0.55);

        // Setup the axis formatting 	
        $graph->axis->SetFont(FF_FONT1,FS_BOLD);
        $graph->axis->SetWeight(2);

        // Setup the grid lines
        $graph->grid->SetLineStyle("longdashed");
        $graph->grid->SetColor("navy");
        $graph->grid->Show();
        $graph->HideTickMarks();

        // Setup graph titles
        $graph->title->SetFont(FF_FONT0,FS_BOLD);
        $graph->SetTitles(array("1","2","3","4","5","6","7","8","9","10"));
        // Create the first radar plot		
        $plot = new RadarPlot(array(10,10,10,10,10,10,10,10,10,10));
        $plot->SetColor("green","lightgreen");
        $plot->SetFillColor("green@0.5");
        $plot->SetLineWeight(2);
        // Create the first radar plot		
        $plot2 = new RadarPlot(array(2,2,2,2,2,2,2,2,2,2));
        $plot2->SetColor("red","lightred");
        $plot2->SetFillColor("red@1");
        $plot2->SetLineWeight(2);
        // Create the first radar plot		
        $plot4 = new RadarPlot(array(6,6,6,6,6,6,6,6,6,6));
        $plot4->SetColor("yellow","lightyellow");
        $plot4->SetFillColor("yellow@1");
        $plot4->SetLineWeight(2);

        // Create the second radar plot
        $plot3 = new RadarPlot($data);
        $plot3->SetColor("blue","lightred");
        $plot3->SetFillColor("blue@0.5");

        // Add the plots to the graph
        $graph->Add($plot);
        $graph->Add($plot3);
        $graph->Add($plot4);
        $graph->Add($plot2);

        
        // And output the graph
        ob_start();  
        $graph->Stroke();
        $graphData = ob_get_contents();   // retrieve buffer contents
        ob_end_clean(); 
        
        return '@'.$graphData;
    }

    public function administrar(){
        $evaluacion = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($evaluacion > 0){
            $sql = sprintf('select finalizado from evaluacion where id=%s', $evaluacion);
            $finalizado = BD::$db->queryOne($sql);
            $vars['finalizado'] = $finalizado;
            View::render('evaluaciones/administrar.php', $vars);
        }
        else{
            header('Location: index.php?mod=sievas&controlador=evaluaciones&accion=listar_evaluaciones');
        }
        
    }
    
    public function finalizar_evaluacion(){
        header('Content-Type: application/json');      
        $evaluacion = filter_input(INPUT_POST, 'evaluacion', FILTER_SANITIZE_NUMBER_INT);
        if($evaluacion > 0){
            $sql = sprintf('select finalizado from evaluacion where id=%s', $evaluacion);
            $finalizado = BD::$db->queryOne($sql);
            if($finalizado > 0){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo 'La evaluación ya ha finalizado, ¿desea reabrirla?';
            }
            else{
                $sql = sprintf('update evaluacion set finalizado=1 where id=%s', $evaluacion);
                $rs = BD::$db->query($sql);
                if(PEAR::isError($rs)){
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    echo 'No se pudo finalizar la evaluación';
                }
                else{
                    echo json_encode(array('mensaje'=>'La evaluación ha finalizado correctamente'));
                }
            }
        }
    }
    
    public function reabrir_evaluacion(){
        $evaluacion = $_GET['id'];
        $_evaluacion = array();
        $sql_evaluacion = "SELECT
        evaluacion.fecha_inicia,
        evaluacion.id,
        evaluacion.etiqueta,
        evaluacion.anotaciones,
        evaluacion.reacreditacion,
        evaluacion.tipo_evaluado,
        evaluacion.cod_evaluado,
        evaluacion.cod_conjunto,
        lineamientos_conjuntos.nom_conjunto
        FROM
        evaluacion
        INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        where evaluacion.id = $evaluacion";
        
        $_evaluacion = BD::$db->queryRow($sql_evaluacion);  

        $sql_comite = "SELECT
        comite.cod_persona,
        evaluacion.id,
        comite.cod_momento_evaluacion,
        comite.cod_cargo,
        momento_evaluacion.cod_momento,
        momento_evaluacion.fecha_inicia,
        momento_evaluacion.fecha_termina,
        gen_persona.nombres,
        gen_persona.primer_apellido,
        gen_persona.segundo_apellido
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        LEFT JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        LEFT JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
        WHERE
                evaluacion.id = $evaluacion";


        $comite = BD::$db->queryAll($sql_comite);         

        $_comite = array();

        foreach($comite as $c){
            $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
        }

        $_evaluacion['comite'] = $_comite;   

        $vars['evaluacion'] = $_evaluacion;
        
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
        View::add_css('public/dropzone/dropzone.css');
        View::add_js('public/dropzone/dropzone.js');
        View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
        View::add_js('modules/sievas/scripts/evaluaciones/add.js');  
        View::add_css('public/css/sievas/select2.css');
        
        View::render('evaluaciones/reabrir.php', $vars);
        }
    
    public function eliminar_evaluacion(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = sprintf('delete from evaluacion where id=%s', $id);
            $rs = BD::$db->query($sql);
            if(PEAR::isError($rs)){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se pudo eliminar la evaluación";
            }
            else{
                echo json_encode(array('mensaje' => 'La evaluación fue eliminada correctamente'));
            }
        }
    }  
    
    public function get_evaluaciones(){
        header('Content-Type: application/json');    
        $programa = filter_input(INPUT_GET, 'programa', FILTER_SANITIZE_NUMBER_INT);
        $sql = "select e.id, e.etiqueta from evaluacion as e";
        if($programa > 0){
            $sql = sprintf("select e.id, e.etiqueta from evaluacion as e where e.cod_evaluado = %s and e.tipo_evaluado = 2", $programa);
        }
        $evaluaciones = BD::$db->queryAll($sql);
        echo json_encode($evaluaciones);
    }
    
    public function get_evaluacion(){
        header('Content-Type: application/json');   
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $evaluaciones = array();
        if($id > 0){
            $sql = "select e.id, e.etiqueta from evaluacion as e";
            $evaluaciones = BD::$db->queryRow($sql);
        }
        echo json_encode($evaluaciones);
    }
    
    public function get_datos_evaluacion(){
        header('Content-Type: application/json');      
        $evaluacion = filter_input(INPUT_GET, 'evaluacion', FILTER_SANITIZE_STRING);
        $ev = array();
        if($evaluacion > 0){
            $sql = sprintf('SELECT
            evaluacion.id as evaluacion,
            evaluacion.etiqueta,
            evaluacion.cod_evaluado,
            evaluacion.tipo_evaluado as cod_tipo_evaluado
            FROM
            evaluacion
            where evaluacion.id = %s', $evaluacion);

            $ev = BD::$db->queryRow($sql);
            
            
            $evaluado = array();
            switch($ev['cod_tipo_evaluado']){
                case "1":
                    $sql_evaluado = "SELECT
                    eval_instituciones.id,
                    eval_instituciones.nom_institucion,
                    gen_paises.id as cod_pais,
                    gen_paises.nom_pais
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                    where eval_instituciones.id=".$ev['cod_evaluado'];    
                    
                    $evaluado = BD::$db->queryRow($sql_evaluado);
                    
                    $ev['evaluado'] = $evaluado['nom_institucion'];
                    $ev['tipo_evaluado'] = 'Institución';
                    $ev['cod_pais'] = $evaluado['cod_pais'];
                    $ev['pais'] = $evaluado['nom_pais'];
                    break;
                case "2":
                    $sql_evaluado = "SELECT
                    eval_programas.id,
                    eval_programas.programa,
                    eval_programas.cod_institucion
                    FROM
                    eval_programas
                    where eval_programas.id=".$ev['cod_evaluado']; 

                    $evaluado = BD::$db->queryRow($sql_evaluado);                    
 
                    $sql_institucion = "SELECT
                    eval_instituciones.nom_institucion,               
                    gen_paises.nom_pais,
                    gen_paises.id as cod_pais
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id       
                    where eval_instituciones.id=".$evaluado['cod_institucion']; 
                    
                    
                    $institucion = BD::$db->queryRow($sql_institucion);
                            
                    $ev['evaluado'] = $evaluado['programa'];
                    $ev['cod_pais'] = $institucion['cod_pais'];
                    $ev['tipo_evaluado'] = 'Programas presenciales';
                    $ev['pais'] = $institucion['nom_pais'];
                    break;            
                case "3":
                    $sql_evaluado = "SELECT
                    gen_persona.nombres as profesor,
                    eval_profesores.id,
                    eval_profesores.cod_institucion
                    FROM
                    eval_profesores
                    INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
                    where eval_profesores.id=".$ev['cod_evaluado']; 

                    $evaluado = BD::$db->queryRow($sql_evaluado);                    
 
                    $sql_institucion = "SELECT
                    eval_instituciones.nom_institucion,               
                    gen_paises.nom_pais,
                    gen_paises.id as cod_pais
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id       
                    where eval_instituciones.id=".$evaluado['cod_institucion']; 
                    
                    
                    $institucion = BD::$db->queryRow($sql_institucion);
                            
                    $ev['evaluado'] = $evaluado['profesor'];
                    $ev['cod_pais'] = $institucion['cod_pais'];
                    $ev['tipo_evaluado'] = 'Profesor';
                    $ev['pais'] = $institucion['nom_pais'];
                    break; 
                case "6":
                    $sql_evaluado = "SELECT
                    eval_programas.id,
                    eval_programas.programa,
                    eval_programas.cod_institucion
                    FROM
                    eval_programas
                    where eval_programas.id=".$ev['cod_evaluado']; 

                    $evaluado = BD::$db->queryRow($sql_evaluado);                    
 
                    $sql_institucion = "SELECT
                    eval_instituciones.nom_institucion,               
                    gen_paises.nom_pais,
                    gen_paises.id as cod_pais
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id       
                    where eval_instituciones.id=".$evaluado['cod_institucion']; 
                    
                    
                    $institucion = BD::$db->queryRow($sql_institucion);
                            
                    $ev['evaluado'] = $evaluado['programa'];
                    $ev['cod_pais'] = $institucion['cod_pais'];
                    $ev['tipo_evaluado'] = 'Programas virtuales';
                    $ev['pais'] = $institucion['nom_pais'];
                    break;  
                case "10":
                    $sql_evaluado = "SELECT
                    eval_programas.id,
                    eval_programas.programa,
                    eval_programas.cod_institucion
                    FROM
                    eval_programas
                    where eval_programas.id=".$ev['cod_evaluado']; 

                    $evaluado = BD::$db->queryRow($sql_evaluado);                    
 
                    $sql_institucion = "SELECT
                    eval_instituciones.nom_institucion,               
                    gen_paises.nom_pais,
                    gen_paises.id as cod_pais
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id       
                    where eval_instituciones.id=".$evaluado['cod_institucion']; 
                    
                    
                    $institucion = BD::$db->queryRow($sql_institucion);
                            
                    $ev['evaluado'] = $evaluado['programa'];
                    $ev['cod_pais'] = $institucion['cod_pais'];
                    $ev['tipo_evaluado'] = 'EUE';
                    $ev['pais'] = $institucion['nom_pais'];
                    break;
                 case "11":
                    $sql_evaluado = "SELECT
                    eval_textos.id,
                    eval_textos.titulo,
                    gen_paises.id as cod_pais,
                    gen_paises.nom_pais
                    FROM
                    eval_textos
                    INNER JOIN gen_paises ON eval_textos.cod_pais = gen_paises.id
                    where eval_textos.id=".$ev['cod_evaluado'];    
                   
//                   var_dump($sql_evaluado);
                    
                    $evaluado = BD::$db->queryRow($sql_evaluado);
                    
                    $ev['evaluado'] = $evaluado['titulo'];
                    $ev['tipo_evaluado'] = 'Ejes temáticos y textos';
                    $ev['cod_pais'] = $evaluado['cod_pais'];
                    $ev['pais'] = $evaluado['nom_pais'];
                    break;
            }        
        }
        echo json_encode(array($ev));
    }
    
    public function evaluado(){
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        if($evaluacion_actual > 0){
        $usuario = Auth::info_usuario('usuario');
            $sql_tipo_evaluado = "select tipo_evaluado,cod_evaluado from evaluacion where id=$evaluacion_actual";
            $rs = BD::$db->queryRow($sql_tipo_evaluado);
            $tipo_evaluado = $rs['tipo_evaluado'];
            $id = $rs['cod_evaluado'];
            $evaluado = array();
            $evaluado['tipo_evaluado'] = $tipo_evaluado;
            switch($tipo_evaluado){
                case "1":
                    $sql_evaluado = "SELECT
                    eval_instituciones.nom_institucion,
                    eval_instituciones.direccion,
                    eval_instituciones.telefonos,
                    eval_instituciones.email,
                    eval_instituciones.nit,
                    eval_instituciones.nombre_corto,
                    eval_instituciones.celular,
                    eval_instituciones.fax,
                    eval_instituciones.apartado_aereo,
                    eval_instituciones.web,
                    eval_instituciones.dv,
                    eval_instituciones.indicativo_comp,
                    gen_paises.nom_pais,
                    niveles_academicos.nivel_academico,
                    gen_municipios.municipio,
                    gen_departamentos.departamento,
                    gen_persona.nombres
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                    INNER JOIN niveles_academicos ON eval_instituciones.cod_nivel_academico = niveles_academicos.id
                    INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
                    INNER JOIN gen_departamentos ON gen_departamentos.cod_pais = gen_paises.id AND gen_municipios.cod_departamento = gen_departamentos.id
                    INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
                    INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
                    where eval_instituciones.id=$id";    
                    
                    $evaluado['institucion'] = BD::$db->queryRow($sql_evaluado);
                    break;
                case "2":
                    $sql_evaluado = "SELECT
                    eval_programas.programa,
                    eval_programas.adscrito,
                    eval_programas.cod_institucion,
                    eval_programas.nombre_director,
                    eval_programas.decano_escuela,
                    niveles_academicos.nivel_academico,
                    cargos.cargo
                    FROM
                    eval_programas
                    INNER JOIN niveles_academicos ON eval_programas.cod_nivel_academico = niveles_academicos.id
                    LEFT JOIN cargos on eval_programas.cod_cargo_director = cargos.id
                    where eval_programas.id=$id"; 
                    

                    $evaluado['programa'] = BD::$db->queryRow($sql_evaluado);
                    $sql_evaluado = "SELECT
                    eval_instituciones.nom_institucion,
                    eval_instituciones.direccion,
                    eval_instituciones.telefonos,
                    eval_instituciones.email,
                    eval_instituciones.nit,
                    eval_instituciones.nombre_corto,
                    eval_instituciones.celular,
                    eval_instituciones.fax,
                    eval_instituciones.apartado_aereo,
                    eval_instituciones.web,
                    eval_instituciones.dv,
                    eval_instituciones.indicativo_comp,
                    gen_paises.nom_pais,
                    niveles_academicos.nivel_academico,
                    gen_municipios.municipio,
                    gen_departamentos.departamento,
                    gen_persona.nombres
                    FROM
                    eval_instituciones
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                    INNER JOIN niveles_academicos ON eval_instituciones.cod_nivel_academico = niveles_academicos.id
                    INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
                    INNER JOIN gen_departamentos ON gen_departamentos.cod_pais = gen_paises.id AND gen_municipios.cod_departamento = gen_departamentos.id
                    INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
                    INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
                    where eval_instituciones.id=".$evaluado['programa']['cod_institucion'];   
                    $evaluado['institucion'] = BD::$db->queryRow($sql_evaluado);
                    break;            
                case "3":        
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
                    gen_tipo_documento.tipo_documento,
                    gen_paises.id AS pais_institucion
                    FROM
                    eval_profesores
                    INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
                    INNER JOIN eval_instituciones ON eval_profesores.cod_institucion = eval_instituciones.id
                    LEFT JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                    LEFT JOIN gen_documentos ON gen_persona.foto = gen_documentos.id
                    LEFT JOIN gen_tipo_documento ON gen_persona.cod_tipo_documento = gen_tipo_documento.id
                    WHERE
                    eval_profesores.id = %s", $id);

//                    var_dump($sql_data_docente);

                    $data_docente = BD::$db->queryRow($sql_data_docente);
                    $evaluado['data_docente'] = $data_docente;
                    

                    $sql_formacion_academica = sprintf("SELECT
                    gen_persona_formacion.cod_nivel_formacion,
                    gen_persona_formacion.titulo,
                    gen_persona_formacion.institucion,
                    niveles_formacion.nivel_formacion,
                    gen_persona_formacion.anio
                    FROM
                    eval_profesores
                    INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
                    INNER JOIN gen_persona_formacion ON gen_persona_formacion.cod_persona = gen_persona.id
                    INNER JOIN niveles_formacion ON gen_persona_formacion.cod_nivel_formacion = niveles_formacion.id
                    where eval_profesores.id = %s", $id);

                    $data_formacion_academica = BD::$db->queryAll($sql_formacion_academica);
                    $evaluado['data_formacion_academica'] = $data_formacion_academica;
                    
//                    var_dump($sql_formacion_academica);

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
                    $evaluado['data_experiencia'] = $data_experiencia;
                    break;
            }        
            $sql_comite = "SELECT
            comite.cod_persona,
            evaluacion.id,
            comite.cod_momento_evaluacion,
            comite.cod_cargo,
            momento_evaluacion.cod_momento,
            momento_evaluacion.fecha_inicia,
            momento_evaluacion.fecha_termina,
            gen_persona.nombres,
            gen_persona.primer_apellido,
            gen_persona.segundo_apellido
            FROM
            comite
            INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
            INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
            INNER JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
            WHERE
                    evaluacion.id = $evaluacion_actual";
            
            $comite = BD::$db->queryAll($sql_comite);         
            
            $_comite = array();
            
            foreach($comite as $c){
                $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
            }
            
            $evaluado['comite'] = $_comite;   
            
            $vars['evaluado'] = $evaluado;
            View::render('evaluaciones/evaluado.php',$vars);
         }
        else{
//            header('Location: index.php?mod=sievas&controlador=evaluar&accion=guardar');
        }
    }
    
    public function save_plan_mejoramiento(){
        
    }
    
    public function crear_persona(){
        header('Content-Type: application/json');      
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $model = new gen_persona();
        $model->nombres = $nombre;
        $model->email = $email;
        if($model->save()){  
            echo json_encode(array('id' => $model->id));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se ha podido crear la persona";
        }
    }

    
    public function metaevaluacion(){
       View::add_js('public/js/bootstrap-datepicker.js');
       View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
       View::add_css('public/css/fa410/css/font-awesome.min.css');
       View::add_css('public/css/sievas/styles.css');
       View::add_js('public/summernote/summernote.min.js');
       View::add_js('public/summernote/summernote-es-ES.js');
       View::add_css('public/summernote/summernote.css');
       View::add_css('public/summernote/tooltip_fix.css');
       View::add_js('public/summernote/helper.js');
       View::add_css('public/js/textext/css/textext.core.css');
       View::add_css('public/js/textext/css/textext.plugin.tags.css');
       View::add_js('public/js/textext/js/textext.core.js');
       View::add_js('public/js/textext/js/textext.plugin.tags.js');
       View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
       View::add_js('modules/sievas/scripts/evaluaciones/metaevaluacion.js'); 
      
       $evaluacion_actual = Auth::info_usuario('evaluacion');
       $usuario = Auth::info_usuario('usuario');
        
       $sql = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
       $cod_persona = BD::$db->queryOne($sql); 
       
       $sql2 = sprintf('select count(*) from evaluacion_metaevaluacion where cod_persona = %s and cod_evaluacion = %s', $cod_persona, $evaluacion_actual);
       $metaevaluacion_estado = BD::$db->queryOne($sql2);
       
       $vars['estado'] = $metaevaluacion_estado;
       
       View::render('evaluar/metaevaluar.php', $vars);  
    }
    
    public function guardar_metaevaluacion(){
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $usuario = Auth::info_usuario('usuario');
        
        $sql = sprintf("select cod_persona from sys_usuario where username = '%s'", $usuario);
        $cod_persona = BD::$db->queryOne($sql);       
        
        $op_1 = filter_input(INPUT_POST, 'op_1', FILTER_SANITIZE_STRING);
        $op_2 = filter_input(INPUT_POST, 'op_2', FILTER_SANITIZE_STRING);
        $op_3 = filter_input(INPUT_POST, 'op_3', FILTER_SANITIZE_STRING);
        $op_4 = filter_input(INPUT_POST, 'op_4', FILTER_SANITIZE_STRING);
        $op_5 = filter_input(INPUT_POST, 'op_5', FILTER_SANITIZE_STRING);
        $op_6 = filter_input(INPUT_POST, 'op_6', FILTER_SANITIZE_STRING);
        $op_7 = filter_input(INPUT_POST, 'op_7', FILTER_SANITIZE_STRING);
        $op_8 = filter_input(INPUT_POST, 'op_8', FILTER_SANITIZE_STRING);
        $op_9 = filter_input(INPUT_POST, 'op_9', FILTER_SANITIZE_STRING);
        $op_10 = filter_input(INPUT_POST, 'op_10', FILTER_SANITIZE_STRING);
        
        $cal_1 = filter_input(INPUT_POST, 'cal_1', FILTER_SANITIZE_NUMBER_INT);
        $cal_2 = filter_input(INPUT_POST, 'cal_2', FILTER_SANITIZE_NUMBER_INT);
        $cal_3 = filter_input(INPUT_POST, 'cal_3', FILTER_SANITIZE_NUMBER_INT);
        $cal_4 = filter_input(INPUT_POST, 'cal_4', FILTER_SANITIZE_NUMBER_INT);
        $cal_5 = filter_input(INPUT_POST, 'cal_5', FILTER_SANITIZE_NUMBER_INT);
        $cal_6 = filter_input(INPUT_POST, 'cal_6', FILTER_SANITIZE_NUMBER_INT);
        $cal_7 = filter_input(INPUT_POST, 'cal_7', FILTER_SANITIZE_NUMBER_INT);
        $cal_8 = filter_input(INPUT_POST, 'cal_8', FILTER_SANITIZE_NUMBER_INT);
        $cal_9 = filter_input(INPUT_POST, 'cal_9', FILTER_SANITIZE_NUMBER_INT);
        $cal_10 = filter_input(INPUT_POST, 'cal_10', FILTER_SANITIZE_NUMBER_INT);
        
        $model_metaevaluacion = new evaluacion_metaevaluacion();
        $model_metaevaluacion->op_1 = $op_1;
        $model_metaevaluacion->op_2 = $op_2;
        $model_metaevaluacion->op_3 = $op_3;
        $model_metaevaluacion->op_4 = $op_4;
        $model_metaevaluacion->op_5 = $op_5;
        $model_metaevaluacion->op_6 = $op_6;
        $model_metaevaluacion->op_7 = $op_7;
        $model_metaevaluacion->op_8 = $op_8;
        $model_metaevaluacion->op_9 = $op_9;
        $model_metaevaluacion->op_10 = $op_10;
        $model_metaevaluacion->cal_1 = $cal_1;
        $model_metaevaluacion->cal_2 = $cal_2;
        $model_metaevaluacion->cal_3 = $cal_3;
        $model_metaevaluacion->cal_4 = $cal_4;
        $model_metaevaluacion->cal_5 = $cal_5;
        $model_metaevaluacion->cal_6 = $cal_6;
        $model_metaevaluacion->cal_7 = $cal_7;
        $model_metaevaluacion->cal_8 = $cal_8;
        $model_metaevaluacion->cal_9 = $cal_9;
        $model_metaevaluacion->cal_10 = $cal_10;
        $model_metaevaluacion->cod_evaluacion = $evaluacion_actual;
        $model_metaevaluacion->cod_persona = $cod_persona;
        
        if($model_metaevaluacion->save()){
            echo json_encode(array('status' => 'ok'));
        }
        else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "No se ha podido guardar la metaevaluación";
        }
    }
    
     public function get_momento_actual(){
        $evaluacion = Auth::info_usuario('evaluacion');
        $username = Auth::info_usuario('usuario');
        $sql = "SELECT
        comite.cod_persona,
        evaluacion.id,
        comite.cod_momento_evaluacion,
        comite.cod_cargo,
        momento_evaluacion.cod_momento
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        where evaluacion.id=$evaluacion and sys_usuario.username='$username'";
        
        return BD::$db->queryRow($sql);
    }
    
    public function ficha_evaluacion(){
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $sql_evaluacion = "SELECT
        evaluacion.fecha_inicia,
        evaluacion.anotaciones,
        evaluacion.reacreditacion,
        evaluacion.tipo_evaluado,
        evaluacion.cod_evaluado,
        lineamientos_conjuntos.nom_conjunto
        FROM
        evaluacion
        INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        where evaluacion.id = $evaluacion_actual";
        
        $evaluacion = BD::$db->queryRow($sql_evaluacion);
        
        $tipo_evaluado = $evaluacion['tipo_evaluado'];
        $id = $evaluacion['cod_evaluado'];
        $evaluado = array();
        switch($tipo_evaluado){
            case "1":
                $sql_evaluado = "SELECT
                eval_instituciones.nom_institucion,
                eval_instituciones.direccion,
                eval_instituciones.telefonos,
                eval_instituciones.email,
                eval_instituciones.nit,
                eval_instituciones.nombre_corto,
                eval_instituciones.celular,
                eval_instituciones.fax,
                eval_instituciones.apartado_aereo,
                eval_instituciones.web,
                eval_instituciones.dv,
                eval_instituciones.indicativo_comp,
                gen_paises.nom_pais,
                niveles_academicos.nivel_academico,
                gen_municipios.municipio,
                gen_departamentos.departamento,
                gen_persona.nombres
                FROM
                eval_instituciones
                INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                INNER JOIN niveles_academicos ON eval_instituciones.cod_nivel_academico = niveles_academicos.id
                INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
                INNER JOIN gen_departamentos ON gen_departamentos.cod_pais = gen_paises.id AND gen_municipios.cod_departamento = gen_departamentos.id
                INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
                INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
                where eval_instituciones.id=$id";    

                $evaluado['institucion'] = BD::$db->queryRow($sql_evaluado);
                break;
            case "2":
                $sql_evaluado = "SELECT
                eval_programas.programa,
                eval_programas.adscrito,
                eval_programas.cod_institucion,
                eval_programas.nombre_director,
                eval_programas.decano_escuela,
                niveles_academicos.nivel_academico
                FROM
                eval_programas
                INNER JOIN niveles_academicos ON eval_programas.cod_nivel_academico = niveles_academicos.id
                where eval_programas.id=$id"; 

                $evaluado['programa'] = BD::$db->queryRow($sql_evaluado);
                $sql_evaluado = "SELECT
                eval_instituciones.nom_institucion,
                eval_instituciones.direccion,
                eval_instituciones.telefonos,
                eval_instituciones.email,
                eval_instituciones.nit,
                eval_instituciones.nombre_corto,
                eval_instituciones.celular,
                eval_instituciones.fax,
                eval_instituciones.apartado_aereo,
                eval_instituciones.web,
                eval_instituciones.dv,
                eval_instituciones.indicativo_comp,
                gen_paises.nom_pais,
                niveles_academicos.nivel_academico,
                gen_municipios.municipio,
                gen_departamentos.departamento,
                gen_persona.nombres
                FROM
                eval_instituciones
                INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id
                INNER JOIN niveles_academicos ON eval_instituciones.cod_nivel_academico = niveles_academicos.id
                INNER JOIN gen_municipios ON eval_instituciones.cod_municipio = gen_municipios.id
                INNER JOIN gen_departamentos ON gen_departamentos.cod_pais = gen_paises.id AND gen_municipios.cod_departamento = gen_departamentos.id
                INNER JOIN eval_institucion_rector ON eval_institucion_rector.cod_institucion = eval_instituciones.id
                INNER JOIN gen_persona ON eval_institucion_rector.cod_persona = gen_persona.id
                where eval_instituciones.id=".$evaluado['programa']['cod_institucion'];   
                $evaluado['institucion'] = BD::$db->queryRow($sql_evaluado);
                break;            
            }        
        
        
        
        View::render('evaluaciones/evaluacion.php');
    }
    
    public function iniciar_evaluacion(){
        View::add_js('public/js/jquery.validate.js');
        View::add_js('public/js/bootbox.min.js');
        View::add_css('public/js/select2/select2.css');
        View::add_css('public/js/select2/select2-bootstrap.css');
        View::add_js('public/js/select2/select2.min.js');
        View::add_js('public/js/select2/select2_locale_es.js');
        View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
        View::add_js('modules/sievas/scripts/evaluaciones/iniciar_evaluacion.js');  
        View::add_css('public/css/sievas/select2.css');
        View::render('evaluaciones/iniciar_evaluacion.php');
    }
    
    public function agregar(){
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
        View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
        View::add_js('modules/sievas/scripts/evaluaciones/add.js');  
        View::add_css('public/css/sievas/select2.css');
         View::add_css('public/dropzone/dropzone.css');
        View::add_js('public/dropzone/dropzone.js');
        View::render('evaluaciones/add.php');
    }
    
    public function get_escalas(){
        header('Content-Type: application/json'); 
        $sql_escalas = "select * from gradacion";
        $escalas = BD::$db->queryAll($sql_escalas);
        echo json_encode($escalas);
    }
    
    public function get_escala(){
        header('Content-Type: application/json'); 
        $escala_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_escala = sprintf("select * from gradacion where id=%s", $escala_id);
        $escala = BD::$db->queryRow($sql_escala);
        echo json_encode($escala);
    }   
    
    
    public function editar(){
        $evaluacion = $_GET['id'];
        $_evaluacion = array();
        $sql_evaluacion = "SELECT
        evaluacion.fecha_inicia,
        evaluacion.id,
        evaluacion.etiqueta,
        evaluacion.anotaciones,
        evaluacion.reacreditacion,
        evaluacion.tipo_evaluado,
        evaluacion.cod_evaluado,
        evaluacion.cod_conjunto,
        lineamientos_conjuntos.nom_conjunto
        FROM
        evaluacion
        INNER JOIN lineamientos_conjuntos ON evaluacion.cod_conjunto = lineamientos_conjuntos.id
        where evaluacion.id = $evaluacion";
        
        $_evaluacion = BD::$db->queryRow($sql_evaluacion);  

        $sql_comite = "SELECT
        comite.cod_persona,
        evaluacion.id,
        comite.cod_momento_evaluacion,
        comite.cod_cargo,
        momento_evaluacion.cod_momento,
        momento_evaluacion.fecha_inicia,
        momento_evaluacion.fecha_termina,
        gen_persona.nombres,
        gen_persona.primer_apellido,
        gen_persona.segundo_apellido
        FROM
        comite
        INNER JOIN momento_evaluacion ON comite.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        LEFT JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
        LEFT JOIN gen_persona ON sys_usuario.cod_persona = gen_persona.id
        WHERE
                evaluacion.id = $evaluacion";

        //dictamen
        $sql_dictamen = sprintf("select gen_documentos.nombre, gen_documentos.ruta from evaluacion inner join gen_documentos on evaluacion.dictamen_id = gen_documentos.id where evaluacion.id=%s", $evaluacion);
        $dictamen = BD::$db->queryRow($sql_dictamen);
        //predictamen
        $sql_predictamen = sprintf("select gen_documentos.nombre, gen_documentos.ruta from evaluacion inner join gen_documentos on evaluacion.predictamen_id = gen_documentos.id where evaluacion.id=%s", $evaluacion);
        $predictamen = BD::$db->queryRow($sql_predictamen);
        
        $comite = BD::$db->queryAll($sql_comite);         

        $_comite = array();

        foreach($comite as $c){
            $_comite[$c['cod_momento']][$c['cod_cargo']][] = $c;
        }

        $_evaluacion['comite'] = $_comite;   

        $vars['dictamen'] = $dictamen;
        $vars['predictamen'] = $predictamen;
        $vars['evaluacion'] = $_evaluacion;
        $vars['e_id'] = $evaluacion;
        
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
        View::add_css('public/dropzone/dropzone.css');
        View::add_js('public/dropzone/dropzone.js');
        View::add_js('modules/sievas/scripts/evaluaciones/main.js'); 
        View::add_js('modules/sievas/scripts/evaluaciones/add.js');  
        View::add_css('public/css/sievas/select2.css');

        
        View::render('evaluaciones/edit.php', $vars);
    }
    
    public function get_lineamientos_conjuntos(){
        header('Content-Type: application/json');      
        $sql_lineamientos_conjuntos = "select * from lineamientos_conjuntos";
        $lineamientos_conjuntos = BD::$db->queryAll($sql_lineamientos_conjuntos);
        echo json_encode($lineamientos_conjuntos);
    }
    
    public function get_lineamientos_conjunto(){
        header('Content-Type: application/json');      
        $lineamientos_conjunto = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_lineamientos_conjunto = "select * from lineamientos_conjuntos where id=$lineamientos_conjunto";
        $lineamientos_conjunto = BD::$db->queryRow($sql_lineamientos_conjunto);
        echo json_encode($lineamientos_conjunto);
    }
    
    public function get_momentos(){
        header('Content-Type: application/json');      
        $sql_momentos = "select * from momentos";
        $momentos = BD::$db->queryAll($sql_momentos);
        echo json_encode($momentos);
    }
    
    public function get_momento(){
        header('Content-Type: application/json');      
        $momento = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql_momento = "select * from momentos where id=$momento";
        $momento = BD::$db->queryRow($sql_momento);
        echo json_encode($momento);
    }
    
    public function get_evaluados(){
        header('Content-Type: application/json');      
        $tipo_evaluado = filter_input(INPUT_GET, 'tipo_evaluado', FILTER_SANITIZE_NUMBER_INT);
        $pais = filter_input(INPUT_GET, 'pais', FILTER_SANITIZE_NUMBER_INT);
        $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
        $evaluados = array();
        if($tipo_evaluado > 0){
            switch($tipo_evaluado){
                case "1":
                    $sql_evaluados = "select * from eval_instituciones";
                    if($pais > 0){
                        $sql_evaluados .= " where cod_pais=$pais";
                    }
                    $evaluados = BD::$db->queryAll($sql_evaluados);
                    break;
                case "2":
                    $sql_evaluados = "select *, eval_programas.id as id from eval_programas inner join eval_instituciones on eval_programas.cod_institucion = eval_instituciones.id  where tipo_programa=1";
                    if($pais > 0){
                        $sql_evaluados .= " and eval_programas.cod_pais=$pais";
                    }

                    $evaluados = BD::$db->queryAll($sql_evaluados);
                    break;
                case "3":
                    $sql_evaluados = "SELECT
                    eval_profesores.id,
                    gen_persona.nombres,
                    eval_instituciones.nom_institucion
                    FROM
                    eval_profesores
                    INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
                    INNER JOIN eval_instituciones ON eval_profesores.cod_institucion = eval_instituciones.id
                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id";
                    if($pais > 0){
                        $sql_evaluados .= " where eval_instituciones.cod_pais=$pais";
                    }

                    
                    $evaluados = BD::$db->queryAll($sql_evaluados);
                    break;
                case "6":
                    $sql_evaluados = "select *, eval_programas.id as id from eval_programas inner join eval_instituciones on eval_programas.cod_institucion = eval_instituciones.id  where eval_programas.tipo_programa=2";
                    if($pais > 0){
                        $sql_evaluados .= " and eval_programas.cod_pais=$pais";
                    }
//                    var_dump($sql_evaluados);
                    $evaluados = BD::$db->queryAll($sql_evaluados);
                    break;
             case "10":
                    $sql_evaluados = "select *, eval_programas.id as id from eval_programas inner join eval_instituciones on eval_programas.cod_institucion = eval_instituciones.id  where tipo_programa=1";
                    if($pais > 0){
                        $sql_evaluados .= " and eval_programas.cod_pais=$pais";
                    }

                    $evaluados = BD::$db->queryAll($sql_evaluados);
                    break;
              case "11":
                    $sql_evaluados = "select *, eval_textos.id as id from eval_textos";
                    if($pais > 0){
                        $sql_evaluados .= " where eval_textos.cod_pais=$pais";
                    }

                    $evaluados = BD::$db->queryAll($sql_evaluados);
                    break;
            }            
        }        
        echo json_encode($evaluados);
    }
    
    public function get_evaluado(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $tipo_evaluado = filter_input(INPUT_GET, 'tipo_evaluado', FILTER_SANITIZE_NUMBER_INT);
       
        switch($tipo_evaluado){
            case "1":
                $sql_evaluado = "select * from eval_instituciones where id=$id";               
                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;
            case "2":
                $sql_evaluado = "select * from eval_programas where id=$id";                
                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;            
            case "6":
                $sql_evaluado = "select * from eval_programas where id=$id";                
                $evaluado = BD::$db->queryRow($sql_evaluado);
                break;            
        }        
        echo json_encode($evaluado);
    }
    
    public function get_niveles_autoevaluacion(){
        header('Content-Type: application/json');     
        $niveles_autoevaluacion = array();
        $sql_niveles_autoevaluacion = "select * from niveles_autoevaluacion where activo=1 order by nivel asc";
        $niveles_autoevaluacion = BD::$db->queryAll($sql_niveles_autoevaluacion);       
        echo json_encode($niveles_autoevaluacion);
    }
    
    public function get_nivel_autoevaluacion(){
        header('Content-Type: application/json');      
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $nivel_autoevaluacion = array();
        if($id > 0){
            $sql_nivel_autoevaluacion = sprintf("select * from niveles_autoevaluacion where id=%s and activo=1", BD::$db->quote($id));
            $nivel_autoevaluacion = BD::$db->queryRow($sql_nivel_autoevaluacion);
        }       
        echo json_encode($nivel_autoevaluacion);
    }
    
     public function get_evaluaciones_evaluado(){
        header('Content-Type: application/json'); 
        $evaluado = filter_input(INPUT_GET, 'evaluado', FILTER_SANITIZE_NUMBER_INT);
        $tipo_evaluado = filter_input(INPUT_GET, 'tipo_evaluado', FILTER_SANITIZE_NUMBER_INT);
        $rol = filter_input(INPUT_GET, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $evaluaciones = array();
        switch($rol){
            case "1":                
                $sql_evaluaciones = "select * from evaluacion where tipo_evaluado=$tipo_evaluado and cod_evaluado=$evaluado";
                $evaluaciones = BD::$db->queryAll($sql_evaluaciones);  
                break;
            case "2":
                $usuario = Auth::info_usuario('usuario');
                $sql_evaluaciones = "SELECT DISTINCT
                evaluacion.id,
                evaluacion.fecha_inicia,
                evaluacion.etiqueta
                FROM
                evaluacion
                INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                INNER JOIN comite ON comite.cod_momento_evaluacion = momento_evaluacion.id
                INNER JOIN sys_usuario ON comite.cod_persona = sys_usuario.cod_persona
                WHERE
                evaluacion.tipo_evaluado = $tipo_evaluado AND
                evaluacion.cod_evaluado = $evaluado AND
                sys_usuario.username = ".BD::$db->quote($usuario);    

                $evaluaciones = BD::$db->queryAll($sql_evaluaciones); 
                break;
        }
             
        echo json_encode($evaluaciones);
    }
    
    public function guardar_evaluacion(){
        header('Content-Type: application/json');
        $valid = true;
        //evaluacion
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $tipo_evaluado = filter_input(INPUT_POST, 'tipo_evaluado', FILTER_SANITIZE_NUMBER_INT);
        $evaluado = filter_input(INPUT_POST, 'evaluado', FILTER_SANITIZE_NUMBER_INT);
        $conjunto_lineamientos = filter_input(INPUT_POST, 'conjunto_lineamientos', FILTER_SANITIZE_NUMBER_INT);
        $etiqueta = filter_input(INPUT_POST, 'etiqueta', FILTER_SANITIZE_STRING);
        
        //momentos
        $tipo_momento_interna = filter_input(INPUT_POST, 'tipo_momento_interna', FILTER_SANITIZE_NUMBER_INT);
        $fecha_inicio_interna = filter_input(INPUT_POST, 'fecha_inicio_interna', FILTER_SANITIZE_STRING);
        $fecha_fin_interna = filter_input(INPUT_POST, 'fecha_fin_interna', FILTER_SANITIZE_STRING);
        $coordinador_interna = filter_input(INPUT_POST, 'coordinador_interna', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_1_interna = filter_input(INPUT_POST, 'evaluador_1_interna', FILTER_SANITIZE_NUMBER_INT);
        $directivo_interna = filter_input(INPUT_POST, 'directivo_interna', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_2_interna = filter_input(INPUT_POST, 'evaluador_2_interna', FILTER_SANITIZE_NUMBER_INT);
        $estudiante_interna = filter_input(INPUT_POST, 'estudiante_interna', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_3_interna = filter_input(INPUT_POST, 'evaluador_3_interna', FILTER_SANITIZE_NUMBER_INT);
        $tipo_momento_externa = filter_input(INPUT_POST, 'tipo_momento_externa', FILTER_SANITIZE_NUMBER_INT);
        $fecha_inicio_externa = filter_input(INPUT_POST, 'fecha_inicio_externa', FILTER_SANITIZE_STRING);
        $fecha_fin_externa = filter_input(INPUT_POST, 'fecha_fin_externa', FILTER_SANITIZE_STRING);
        $coordinador_externa = filter_input(INPUT_POST, 'coordinador_externa', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_1_externa = filter_input(INPUT_POST, 'evaluador_1_externa', FILTER_SANITIZE_NUMBER_INT);
        $directivo_externa = filter_input(INPUT_POST, 'directivo_externa', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_2_externa = filter_input(INPUT_POST, 'evaluador_2_externa', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_4_externa = filter_input(INPUT_POST, 'evaluador_4_externa', FILTER_SANITIZE_NUMBER_INT);
        $evaluador_3_externa = filter_input(INPUT_POST, 'evaluador_3_externa', FILTER_SANITIZE_NUMBER_INT);
        
        //reapertura
        $evaluacion_base = filter_input(INPUT_POST, 'evaluacion_base', FILTER_SANITIZE_NUMBER_INT);
        
        //red
        $ev_red = filter_input(INPUT_POST, 'ev_red', FILTER_SANITIZE_NUMBER_INT);
        $ev_padre = filter_input(INPUT_POST, 'padre', FILTER_SANITIZE_NUMBER_INT);
        $traduccion = filter_input(INPUT_POST, 'traduccion', FILTER_SANITIZE_NUMBER_INT);
        $escala = filter_input(INPUT_POST, 'escala', FILTER_SANITIZE_NUMBER_INT);
        
        $personas = array($coordinador_interna, $evaluador_1_interna, $directivo_interna, $evaluador_2_interna, $estudiante_interna, $evaluador_3_interna, $coordinador_externa, $evaluador_1_externa, $directivo_externa, $evaluador_2_externa, $estudiante_externa, $evaluador_3_externa);

        if($id > 0){
            
            //reevaluacion
            $model_evaluacion = new evaluacion();
            $model_evaluacion->begin();
            $model_evaluacion->fecha_inicia = $fecha_inicio_interna;
            $model_evaluacion->tipo_evaluado = $tipo_evaluado;
            $model_evaluacion->cod_conjunto = $conjunto_lineamientos;
            $model_evaluacion->fecha_certificacion = $fecha_fin_externa;
            $model_evaluacion->cod_evaluado = $evaluado;
            $model_evaluacion->etiqueta = $etiqueta;
            $model_evaluacion->ev_red = $ev_red;
            if($ev_padre > 0){
                $model_evaluacion->padre = $ev_padre;
            }     
            if($escala > 0){
                $model_evaluacion->gradacion_id = $escala;
            }     
            if($evaluacion_base > 0){
                $model_evaluacion->ev_anterior = $evaluacion_base;
            }
            $model_evaluacion->id = $id;

            if($model_evaluacion->update()){
//                $sql_delete = 'delete from momento_evaluacion where cod_evaluacion = '.$model_evaluacion->id;
//                $rs = BD::$db->query($sql_delete);
                
                $sql_momento_evaluacion_interna = sprintf('select id from momento_evaluacion where cod_momento=%s and cod_evaluacion = %s', 1, $id);
                $momento_evaluacion_interna = BD::$db->queryOne($sql_momento_evaluacion_interna);
                
                $sql_comite_del = sprintf('delete from comite where cod_momento_evaluacion = %s', $momento_evaluacion_interna);
                $rs = BD::$db->query($sql_comite_del);

                        $model_comite_1 = new comite();
                        if($coordinador_interna > 0){
                             $model_comite_1->cod_persona = $coordinador_interna;
                        }               
                        $model_comite_1->cod_cargo = 1;
                        $model_comite_1->cod_momento_evaluacion = $momento_evaluacion_interna;

                        $model_comite_2 = new comite();
                        if($directivo_interna > 0){
                              $model_comite_2->cod_persona = $directivo_interna;
                        }               
                        $model_comite_2->cod_cargo = 2;
                        $model_comite_2->cod_momento_evaluacion = $momento_evaluacion_interna;

                        $model_comite_3 = new comite();
                        if($estudiante_interna > 0){
                            $model_comite_3->cod_persona = $estudiante_interna;
                        }

                        $model_comite_3->cod_cargo = 3;
                        $model_comite_3->cod_momento_evaluacion = $momento_evaluacion_interna;

                        $model_comite_4 = new comite();
                        if($evaluador_1_interna > 0){
                            $model_comite_4->cod_persona = $evaluador_1_interna;
                        }

                        $model_comite_4->cod_cargo = 4;
                        $model_comite_4->cod_momento_evaluacion = $momento_evaluacion_interna;

                        $model_comite_5 = new comite();
                        if($evaluador_2_interna > 0){
                            $model_comite_5->cod_persona = $evaluador_2_interna;
                        }

                        $model_comite_5->cod_cargo = 4;
                        $model_comite_5->cod_momento_evaluacion = $momento_evaluacion_interna;

                        $model_comite_6 = new comite();
                        if($evaluador_3_interna > 0){
                            $model_comite_6->cod_persona = $evaluador_3_interna;
                        }

                        $model_comite_6->cod_cargo = 4;
                        $model_comite_6->cod_momento_evaluacion = $momento_evaluacion_interna;


                    if( $model_comite_1->save() &&
                          $model_comite_2->save() &&
                          $model_comite_3->save() &&
                          $model_comite_4->save() &&
                          $model_comite_5->save() &&
                          $model_comite_6->save() ){
                        
                        $sql_momento_evaluacion_externa = sprintf('select id from momento_evaluacion where cod_momento=%s and cod_evaluacion = %s', 2, $id);
                        $momento_evaluacion_externa = BD::$db->queryOne($sql_momento_evaluacion_externa);
                        
                        $sql_comite_del = sprintf('delete from comite where cod_momento_evaluacion = %s', $momento_evaluacion_externa);
                        $rs = BD::$db->query($sql_comite_del);

                            $model_comite_7 = new comite();
                            if( $coordinador_externa > 0){
                              $model_comite_7->cod_persona = $coordinador_externa;
                            } 

                            $model_comite_7->cod_cargo = 1;
                            $model_comite_7->cod_momento_evaluacion = $momento_evaluacion_externa;

                            $model_comite_8 = new comite();
                            if( $directivo_externa > 0){
                              $model_comite_8->cod_persona = $directivo_externa;
                            } 

                            $model_comite_8->cod_cargo = 2;
                            $model_comite_8->cod_momento_evaluacion = $momento_evaluacion_externa;

                            $model_comite_12 = new comite();
                             if( $evaluador_4_externa > 0){
                              $model_comite_12->cod_persona = $evaluador_4_externa;
                            } 

                            $model_comite_12->cod_cargo = 4;
                            $model_comite_12->cod_momento_evaluacion = $momento_evaluacion_externa;

                            $model_comite_9 = new comite();
                             if( $evaluador_1_externa > 0){
                              $model_comite_9->cod_persona = $evaluador_1_externa;
                            } 

                            $model_comite_9->cod_cargo = 4;
                            $model_comite_9->cod_momento_evaluacion = $momento_evaluacion_externa;

                            $model_comite_10 = new comite();
                              if( $evaluador_2_externa > 0){
                              $model_comite_10->cod_persona = $evaluador_2_externa;
                            } 

                            $model_comite_10->cod_cargo = 4;
                            $model_comite_10->cod_momento_evaluacion = $momento_evaluacion_externa;

                            $model_comite_11 = new comite();
                             if( $evaluador_3_externa > 0){
                              $model_comite_11->cod_persona = $evaluador_3_externa;
                            } 
                            $model_comite_11->cod_cargo = 4;
                            $model_comite_11->cod_momento_evaluacion = $momento_evaluacion_externa;

                             if(!($model_comite_7->save() &&
                                  $model_comite_8->save() &&
                                  $model_comite_9->save() &&
                                  $model_comite_10->save() &&
                                  $model_comite_11->save() &&
                                  $model_comite_12->save() )){
                                  $valid = false;
                             }
                             else{

                             }
                        }
                    }


  
            
            else{
                 $valid = false;

            }        
            if($valid){
                $model_evaluacion->commit();
                echo json_encode(array('mensaje' => 'La evaluación ha sido actualizada correctamente'));
            }
            else{
                $model_evaluacion->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido actualizar la evaluación";
            }
        }
        else{

            $model_evaluacion = new evaluacion();
            $model_evaluacion->begin();
            $model_evaluacion->fecha_inicia = $fecha_inicio_interna;
            $model_evaluacion->tipo_evaluado = $tipo_evaluado;
            $model_evaluacion->cod_conjunto = $conjunto_lineamientos;
            $model_evaluacion->fecha_certificacion = $fecha_fin_externa;
            $model_evaluacion->cod_evaluado = $evaluado;
            $model_evaluacion->etiqueta = $etiqueta;
            $model_evaluacion->ev_red = $ev_red;
            if($ev_padre > 0){
                $model_evaluacion->padre = $ev_padre;
            }
            if($escala > 0){
                $model_evaluacion->gradacion_id = $escala;
            } 
            if($evaluacion_base > 0){
                $model_evaluacion->ev_anterior = $evaluacion_base;
            }
            
            if($model_evaluacion->save()){
                $model_momento_evaluacion = new momento_evaluacion();
                $model_momento_evaluacion->fecha_inicia = $fecha_inicio_interna;
                $model_momento_evaluacion->fecha_termina = $fecha_fin_interna;
                $model_momento_evaluacion->cod_momento = $tipo_momento_interna;
                $model_momento_evaluacion->cod_evaluacion = $model_evaluacion->id;
                if($model_momento_evaluacion->save()){
                    $model_comite_1 = new comite();
                    if($coordinador_interna > 0){
                         $model_comite_1->cod_persona = $coordinador_interna;
                    }               
                    $model_comite_1->cod_cargo = 1;
                    $model_comite_1->cod_momento_evaluacion = $model_momento_evaluacion->id;

                    $model_comite_2 = new comite();
                    if($directivo_interna > 0){
                          $model_comite_2->cod_persona = $directivo_interna;
                    }               
                    $model_comite_2->cod_cargo = 2;
                    $model_comite_2->cod_momento_evaluacion = $model_momento_evaluacion->id;

                    $model_comite_3 = new comite();
                    if($estudiante_interna > 0){
                        $model_comite_3->cod_persona = $estudiante_interna;
                    }

                    $model_comite_3->cod_cargo = 3;
                    $model_comite_3->cod_momento_evaluacion = $model_momento_evaluacion->id;

                    $model_comite_4 = new comite();
                    if($evaluador_1_interna > 0){
                        $model_comite_4->cod_persona = $evaluador_1_interna;
                    }

                    $model_comite_4->cod_cargo = 4;
                    $model_comite_4->cod_momento_evaluacion = $model_momento_evaluacion->id;

                    $model_comite_5 = new comite();
                    if($evaluador_2_interna > 0){
                        $model_comite_5->cod_persona = $evaluador_2_interna;
                    }

                    $model_comite_5->cod_cargo = 4;
                    $model_comite_5->cod_momento_evaluacion = $model_momento_evaluacion->id;

                    $model_comite_6 = new comite();
                    if($evaluador_3_interna > 0){
                        $model_comite_6->cod_persona = $evaluador_3_interna;
                    }

                    $model_comite_6->cod_cargo = 4;
                    $model_comite_6->cod_momento_evaluacion = $model_momento_evaluacion->id;


                if( $model_comite_1->save() &&
                      $model_comite_2->save() &&
                      $model_comite_3->save() &&
                      $model_comite_4->save() &&
                      $model_comite_5->save() &&
                      $model_comite_6->save() ){

                    $model_momento_evaluacion_2 = new momento_evaluacion();
                    $model_momento_evaluacion_2->fecha_inicia = $fecha_inicio_externa;
                    $model_momento_evaluacion_2->fecha_termina = $fecha_fin_externa;
                    $model_momento_evaluacion_2->cod_momento = $tipo_momento_externa;
                    $model_momento_evaluacion_2->cod_evaluacion = $model_evaluacion->id;
                    if($model_momento_evaluacion_2->save()){
                        $model_comite_7 = new comite();
                        if( $coordinador_externa > 0){
                          $model_comite_7->cod_persona = $coordinador_externa;
                        } 

                        $model_comite_7->cod_cargo = 1;
                        $model_comite_7->cod_momento_evaluacion = $model_momento_evaluacion_2->id;

                        $model_comite_8 = new comite();
                        if( $directivo_externa > 0){
                          $model_comite_8->cod_persona = $directivo_externa;
                        } 

                        $model_comite_8->cod_cargo = 2;
                        $model_comite_8->cod_momento_evaluacion = $model_momento_evaluacion_2->id;

                        $model_comite_9 = new comite();
                         if( $estudiante_externa > 0){
                          $model_comite_9->cod_persona = $estudiante_externa;
                        } 

                        $model_comite_9->cod_cargo = 3;
                        $model_comite_9->cod_momento_evaluacion = $model_momento_evaluacion_2->id;

                        $model_comite_10 = new comite();
                         if( $evaluador_1_externa > 0){
                          $model_comite_10->cod_persona = $evaluador_1_externa;
                        } 

                        $model_comite_10->cod_cargo = 4;
                        $model_comite_10->cod_momento_evaluacion = $model_momento_evaluacion_2->id;

                        $model_comite_12 = new comite();
                          if( $evaluador_4_externa > 0){
                          $model_comite_12->cod_persona = $evaluador_4_externa;
                        } 

                        $model_comite_12->cod_cargo = 4;
                        $model_comite_12->cod_momento_evaluacion = $model_momento_evaluacion_2->id;

                        $model_comite_11 = new comite();
                         if( $evaluador_3_externa > 0){
                          $model_comite_11->cod_persona = $evaluador_3_externa;
                        } 
                        $model_comite_11->cod_cargo = 4;
                        $model_comite_11->cod_momento_evaluacion = $model_momento_evaluacion->id;

                         if(!($model_comite_7->save() &&
                              $model_comite_8->save() &&
                              $model_comite_9->save() &&
                              $model_comite_10->save() &&
                              $model_comite_11->save() &&
                              $model_comite_12->save() )){
                              $valid = false;
                         }
                         else{
                             
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
                 echo $model_evaluacion->error_sql();
                 $valid = false;
            }        
            if($valid){
                if($evaluacion_base > 0){
                    $sql_momento_resultado = sprintf("SELECT
                    momento_resultado.desc_resultado,
                    momento_resultado.fecha,
                    momento_resultado_detalle.cod_lineamiento,
                    momento_resultado_detalle.cod_gradacion_escala,
                    momento_resultado_detalle.fortalezas,
                    momento_resultado_detalle.debilidades,
                    momento_resultado_detalle.plan_mejoramiento,
                    momento_evaluacion.cod_momento
                    FROM
                    evaluacion
                    INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                    INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                    INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
                    WHERE
                    evaluacion.id = %s", $evaluacion_base);
                    
                    $data_resultados = BD::$db->queryAll($sql_momento_resultado);
                    
                    foreach($data_resultados as $d){                    
                        $model_resultado = new momento_resultado();
                        $model_resultado->desc_resultado = $d['desc_resultado'];
                        $model_resultado->fecha = $d['fecha'];
                        $model_resultado->cod_momento_evaluacion = $d['cod_momento'] == "1" ? $model_momento_evaluacion->id : $model_momento_evaluacion_2->id ;
                        if($model_resultado->save()){
                            $model_resultado_detalle = new momento_resultado_detalle();
                            $model_resultado_detalle->cod_momento_resultado = $model_resultado->id;
                            $model_resultado_detalle->cod_lineamiento = $d['cod_lineamiento'];
                            $model_resultado_detalle->cod_gradacion_escala = $d['cod_gradacion_escala'];
                            $model_resultado_detalle->fortalezas = $d['fortalezas'];
                            $model_resultado_detalle->debilidades = $d['debilidades'];
                            $model_resultado_detalle->plan_mejoramiento = $d['plan_mejoramiento'];
                            $model_resultado_detalle->save();
                        }
                    }
                    
                    $sql_momento_anexos = sprintf("SELECT
                        momento_evaluacion.cod_momento,
                        momento_resultado_anexo.cod_documento,
                        momento_resultado_anexo.cod_lineamiento
                        FROM
                        evaluacion
                        INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                        INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
                        WHERE
                        evaluacion.id = %s", $evaluacion_base);
                    
                     $data_anexos = BD::$db->queryAll($sql_momento_anexos);
                     
                     foreach($data_anexos as $a){
                         $model_anexo = new momento_resultado_anexo();
                         $model_anexo->cod_documento = $a['cod_documento'];
                         $model_anexo->cod_lineamiento = $a['cod_lineamiento'];
                         $model_anexo->cod_momento_evaluacion = $model_momento_evaluacion->id;
                         $model_anexo->save();
                         
                         $model_anexo = new momento_resultado_anexo();
                         $model_anexo->cod_documento = $a['cod_documento'];
                         $model_anexo->cod_lineamiento = $a['cod_lineamiento'];
                         $model_anexo->cod_momento_evaluacion = $model_momento_evaluacion_2->id;
                         $model_anexo->save();
                     }
                     
                      $sql_plan_mejoramiento = sprintf("SELECT
                        plan_mejoramiento.id,
                        plan_mejoramiento.titulo,
                        plan_mejoramiento.subtitulo,
                        plan_mejoramiento.presupuesto,
                        plan_mejoramiento.fecha_cumplimiento,
                        plan_mejoramiento.objetivos,
                        plan_mejoramiento.estrategias,
                        plan_mejoramiento.cod_lineamiento,
                        momento_evaluacion.cod_momento,
                        plan_mejoramiento.fecha_inicio,
                        plan_mejoramiento.fecha_fin
                        FROM
                        evaluacion
                        INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
                        INNER JOIN plan_mejoramiento ON plan_mejoramiento.cod_momento_evaluacion = momento_evaluacion.id
                        WHERE
                        evaluacion.id = %s", $evaluacion_base);
                    
                     $data_plan_mejoramiento = BD::$db->queryAll($sql_plan_mejoramiento);
                     
                     $model_plan_mejoramiento = new plan_mejoramiento();
                     $model_plan_mejoramiento->titulo = $data_plan_mejoramiento['titulo'];
                     $model_plan_mejoramiento->subtitulo = $data_plan_mejoramiento['subtitulo'];
                     $model_plan_mejoramiento->presupuesto = $data_plan_mejoramiento['presupuesto'];
                     $model_plan_mejoramiento->fecha_cumplimiento = $data_plan_mejoramiento['fecha_cumplimiento'];
                     $model_plan_mejoramiento->objetivos = $data_plan_mejoramiento['objetivos'];
                     $model_plan_mejoramiento->estrategias = $data_plan_mejoramiento['estrategias'];
                     $model_plan_mejoramiento->cod_evaluacion = $model_evaluacion->id;
                     $model_plan_mejoramiento->cod_lineamiento = $data_plan_mejoramiento['cod_lineamiento'];
                     $model_plan_mejoramiento->cod_momento_evaluacion = $data_plan_mejoramiento['cod_momento'];
                     $model_plan_mejoramiento->fecha_inicio = $data_plan_mejoramiento['fecha_inicio'];
                     $model_plan_mejoramiento->fecha_fin = $data_plan_mejoramiento['fecha_fin'];
                     if($model_plan_mejoramiento->save()){
                         $sql_plan_acciones = sprintf("
                            SELECT
                            plan_mejoramiento_acciones.accion,
                            plan_mejoramiento_acciones.metas,
                            plan_mejoramiento_acciones.responsables,
                            plan_mejoramiento.id
                            FROM
                            plan_mejoramiento
                            INNER JOIN plan_mejoramiento_acciones ON plan_mejoramiento_acciones.cod_plan_mejoramiento = plan_mejoramiento.id
                            where plan_mejoramiento.id = %s", $data_plan_mejoramiento['id']);
                         
                         $data_plan_acciones = BD::$db->queryAll($sql_plan_acciones);
                         
                         foreach($data_plan_acciones as $acc){
                             $model_plan_acciones = new plan_mejoramiento_acciones();
                             $model_plan_acciones->accion = $acc['accion'];
                             $model_plan_acciones->metas = $acc['metas'];
                             $model_plan_acciones->responsables = $acc['responsables'];
                             $model_plan_acciones->cod_plan_mejoramiento= $model_plan_mejoramiento->id;
                             $model_plan_acciones->save();
                         }

                     }
                    
                }
                $model_evaluacion->commit();
                echo json_encode(array('mensaje' => 'La evaluación ha sido creada correctamente'));
            }
            else{
                $model_evaluacion->rollback();
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo "No se ha podido crear la evaluación";
            }
        }
        
        
    }
    
    public function transferir_items(){
        $evaluacion_base = 138;
        $momento_evaluacion = 243;
        $momento_evaluacion_2 = 244;
        $evaluacion = 172;
        
             
        $sql_momento_resultado = sprintf("SELECT
        momento_resultado.desc_resultado,
        momento_resultado.fecha,
        momento_resultado_detalle.cod_lineamiento,
        momento_resultado_detalle.cod_gradacion_escala,
        momento_resultado_detalle.fortalezas,
        momento_resultado_detalle.debilidades,
        momento_resultado_detalle.plan_mejoramiento,
        momento_evaluacion.cod_momento
        FROM
        evaluacion
        INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
        INNER JOIN momento_resultado ON momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
        INNER JOIN momento_resultado_detalle ON momento_resultado_detalle.cod_momento_resultado = momento_resultado.id
        WHERE
        evaluacion.id = %s", $evaluacion_base);

        $data_resultados = BD::$db->queryAll($sql_momento_resultado);
        var_dump($sql_momento_resultado);
        foreach($data_resultados as $d){                    
            $model_resultado = new momento_resultado();
            $model_resultado->desc_resultado = $d['desc_resultado'];
            $model_resultado->fecha = $d['fecha'];
            $model_resultado->cod_momento_evaluacion = $d['cod_momento'] == "1" ? $momento_evaluacion : $momento_evaluacion_2 ;
            if($model_resultado->save()){
                $model_resultado_detalle = new momento_resultado_detalle();
                $model_resultado_detalle->cod_momento_resultado = $model_resultado->id;
                $model_resultado_detalle->cod_lineamiento = $d['cod_lineamiento'];
                $model_resultado_detalle->cod_gradacion_escala = $d['cod_gradacion_escala'];
                $model_resultado_detalle->fortalezas = $d['fortalezas'];
                $model_resultado_detalle->debilidades = $d['debilidades'];
                $model_resultado_detalle->plan_mejoramiento = $d['plan_mejoramiento'];
                $model_resultado_detalle->save();
            }
        }
        echo "hola";
        $sql_momento_anexos = sprintf("SELECT
            momento_evaluacion.cod_momento,
            momento_resultado_anexo.cod_documento,
            momento_resultado_anexo.cod_lineamiento
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN momento_resultado_anexo ON momento_resultado_anexo.cod_momento_evaluacion = momento_evaluacion.id
            WHERE
            evaluacion.id = %s", $evaluacion_base);

         $data_anexos = BD::$db->queryAll($sql_momento_anexos);
  echo "hola";
         foreach($data_anexos as $a){
             $model_anexo = new momento_resultado_anexo();
             $model_anexo->cod_documento = $a['cod_documento'];
             $model_anexo->cod_lineamiento = $a['cod_lineamiento'];
             $model_anexo->cod_momento_evaluacion = $momento_evaluacion;
             $model_anexo->save();

             $model_anexo = new momento_resultado_anexo();
             $model_anexo->cod_documento = $a['cod_documento'];
             $model_anexo->cod_lineamiento = $a['cod_lineamiento'];
             $model_anexo->cod_momento_evaluacion = $momento_evaluacion_2;
             $model_anexo->save();
         }

          $sql_plan_mejoramiento = sprintf("SELECT
            plan_mejoramiento.id,
            plan_mejoramiento.titulo,
            plan_mejoramiento.subtitulo,
            plan_mejoramiento.presupuesto,
            plan_mejoramiento.fecha_cumplimiento,
            plan_mejoramiento.objetivos,
            plan_mejoramiento.estrategias,
            plan_mejoramiento.cod_lineamiento,
            momento_evaluacion.cod_momento,
            plan_mejoramiento.fecha_inicio,
            plan_mejoramiento.fecha_fin
            FROM
            evaluacion
            INNER JOIN momento_evaluacion ON momento_evaluacion.cod_evaluacion = evaluacion.id
            INNER JOIN plan_mejoramiento ON plan_mejoramiento.cod_momento_evaluacion = momento_evaluacion.id
            WHERE
            evaluacion.id = %s", $evaluacion_base);

         $data_plan_mejoramiento = BD::$db->queryAll($sql_plan_mejoramiento);

         $model_plan_mejoramiento = new plan_mejoramiento();
         $model_plan_mejoramiento->titulo = $data_plan_mejoramiento['titulo'];
         $model_plan_mejoramiento->subtitulo = $data_plan_mejoramiento['subtitulo'];
         $model_plan_mejoramiento->presupuesto = $data_plan_mejoramiento['presupuesto'];
         $model_plan_mejoramiento->fecha_cumplimiento = $data_plan_mejoramiento['fecha_cumplimiento'];
         $model_plan_mejoramiento->objetivos = $data_plan_mejoramiento['objetivos'];
         $model_plan_mejoramiento->estrategias = $data_plan_mejoramiento['estrategias'];
         $model_plan_mejoramiento->cod_evaluacion = $evaluacion;
         $model_plan_mejoramiento->cod_lineamiento = $data_plan_mejoramiento['cod_lineamiento'];
         $model_plan_mejoramiento->cod_momento_evaluacion = $data_plan_mejoramiento['cod_momento'];
         $model_plan_mejoramiento->fecha_inicio = $data_plan_mejoramiento['fecha_inicio'];
         $model_plan_mejoramiento->fecha_fin = $data_plan_mejoramiento['fecha_fin'];
         if($model_plan_mejoramiento->save()){
             $sql_plan_acciones = sprintf("
                SELECT
                plan_mejoramiento_acciones.accion,
                plan_mejoramiento_acciones.metas,
                plan_mejoramiento_acciones.responsables,
                plan_mejoramiento.id
                FROM
                plan_mejoramiento
                INNER JOIN plan_mejoramiento_acciones ON plan_mejoramiento_acciones.cod_plan_mejoramiento = plan_mejoramiento.id
                where plan_mejoramiento.id = %s", $data_plan_mejoramiento['id']);

             $data_plan_acciones = BD::$db->queryAll($sql_plan_acciones);

             foreach($data_plan_acciones as $acc){
                 $model_plan_acciones = new plan_mejoramiento_acciones();
                 $model_plan_acciones->accion = $acc['accion'];
                 $model_plan_acciones->metas = $acc['metas'];
                 $model_plan_acciones->responsables = $acc['responsables'];
                 $model_plan_acciones->cod_plan_mejoramiento= $model_plan_mejoramiento->id;
                 $model_plan_acciones->save();
             }

         }
    }
    
    public function listar_evaluaciones(){
         View::add_js('modules/sievas/scripts/evaluaciones/main.js');
         View::add_js('modules/sievas/scripts/evaluaciones/listar_evaluaciones.js');
         View::render('evaluaciones/listar.php'); 
    }
    
    public function get_dt_evaluaciones(){   
	$aColumns = explode(',',$_GET['sColumns']);
        $sTable = $_GET['sTable'];
        
        foreach($aColumns as $key => $c){            
           if(strrpos($c, '.') === false){
                $aColumns[$key] = $sTable.'.'.$c;
           } 
        }
        
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = $_GET['sIndexColumn'];
        
        
	
	/* DB table to use */
	
	
        
	$aActions = json_decode($_GET['aActions']);
        
        $sJoin = "";
        if(isset($_GET['fKeys'])){
            $fKeys = json_decode($_GET['fKeys']);
            foreach($fKeys as $f){
                $filtroForanea=isset($f->findice)?$f->findice:'id';
                $sJoin .= sprintf(" left join %s on %s.%s = %s.%s", $f->nombre, $_GET['sTable'], $f->fkey, $f->nombre, $filtroForanea);                
            }
        }     

	
	/* 
	 * Paging
	 */
	$sLimit = "";
//	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
//	{
//		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
//			mysql_real_escape_string( $_GET['iDisplayLength'] );
//	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".$_GET['sSortDir_'.$i]  .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
        
       
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
		}
	}
	 
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT ".str_replace(" , ", " ", implode(", ", $aColumns)).",tipo_evaluado
		FROM   $sTable
                $sJoin
		$sWhere
		$sOrder
	";

 
        
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
            BD::$db->setLimit($_GET['iDisplayLength'], $_GET['iDisplayStart']);
	}
        
//        var_dump($sQuery);
	$rResult = BD::$db->query($sQuery);
//        var_dump($sQuery);

        $aResultFilterTotal = $rResult->numRows();
	$iFilteredTotal = $aResultFilterTotal;
        
        
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sTable.'.'.$sIndexColumn.")
		FROM   $sTable $sJoin $sWhere 
		$sOrder
	";
        
	$rResultTotal = BD::$db->query($sQuery);
	$aResultTotal = $rResultTotal->fetchCol();
//	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
//	$aResultTotal = mysql_fetch_array($rResultTotal);
        
	$iTotal = $aResultTotal[0];
	
         
        
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
	);
        
        
        
	foreach($aColumns as $key => $c){            
            if(strrpos($c, '.') !== false){
                $aColumns[$key] = substr($c, strrpos($c,'.')+1);
            }
        }
//        var_dump($aColumns);
	while ( $aRow = $rResult->fetchRow() )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] === "''" )
			{
				/* Special output formatting for 'version' column */
                                if(count($aActions) > 0)
				$row[] = implode(' ', $aActions);
			}
			else if ( $aColumns[$i] != ' ' && $aColumns[$i] != 'cod_evaluado' && $aColumns[$i] != 'tipo_evaluado' && $aColumns[$i] != 'fecha_inicia')
			{
				/* General output */
//                                var_dump($aColumns[$i]);
//                                var_dump($aRow[ $aColumns[$i] ]);
				$row[] = $aRow[ $aColumns[$i] ];
			}
                        else if($aColumns[$i] == 'cod_evaluado'){
                            switch($aRow['tipo_evaluado']){
                                case '1':                                    
                                    $sql = 'select eval_instituciones.nom_institucion from eval_instituciones where id='.$aRow['cod_evaluado'];
                                    $rs = BD::$db->queryOne($sql);
                                    $row[] = $rs; 
                                    break;
                                case '2':
                                    $sql = 'select eval_programas.programa from eval_programas where id='.$aRow['cod_evaluado'];
                                    $rs = BD::$db->queryOne($sql);
                                     $row[] = $rs; 
                                    break;
                                case '3':
                                    $sql = 'SELECT
                                    gen_persona.nombres
                                    FROM
                                    eval_profesores
                                    INNER JOIN gen_persona ON eval_profesores.cod_persona = gen_persona.id
                                    INNER JOIN eval_instituciones ON eval_profesores.cod_institucion = eval_instituciones.id
                                    INNER JOIN gen_paises ON eval_instituciones.cod_pais = gen_paises.id where eval_profesores.id = '.$aRow['cod_evaluado'];
                                    $rs = BD::$db->queryOne($sql);
                                     $row[] = $rs; 
                                    break;
                                 case '6':
                                    $sql = 'select eval_programas.programa from eval_programas where id='.$aRow['cod_evaluado'];
                                    $rs = BD::$db->queryOne($sql);
                                     $row[] = $rs; 
                                    break;
                            }                            
                        }
                        else if($aColumns[$i] == 'tipo_evaluado'){
                            $sql = sprintf("select nivel from niveles_autoevaluacion where id=%s",$aRow['tipo_evaluado']);
                            $nivel = BD::$db->queryOne($sql);
                            $row[] = $nivel; 
                            
                                               
                        }
                        else if($aColumns[$i] == 'fecha_inicia'){
                            $row[] = substr($aRow['fecha_inicia'], 0, 10);
                        }
                       
		}
//                var_dump($row);
		$output['aaData'][] = $row;
	}
	
       
	echo json_encode( $output );
    }
    
    public function ver_historico(){
        $evaluacion_actual = Auth::info_usuario('evaluacion');
        $historico_actual = array();
        $sql_historico_actual = sprintf("SELECT 
            evaluacion.id,
            evaluacion.padre,
            predictamen.ruta AS predictamen_ruta,
            dictamen.ruta AS dictamen_ruta
        FROM
            sievas.evaluacion
                LEFT JOIN
            gen_documentos AS predictamen ON predictamen.id = evaluacion.predictamen_id
                LEFT JOIN
            gen_documentos AS dictamen ON dictamen.id = evaluacion.dictamen_id
            where evaluacion.id = %s;", $evaluacion_actual);
        $historico_actual = BD::$db->queryAll($sql_historico_actual);   
//        var_dump($sql_historico_actual);
//        var_dump($historico_actual);
        $historico_previo = array();
        $sql_historico_previo = sprintf("SELECT 
            evaluacion.id,
            evaluacion.etiqueta,
            predictamen.ruta AS predictamen_ruta,
            dictamen.ruta AS dictamen_ruta
        FROM
            sievas.evaluacion
                LEFT JOIN
            gen_documentos AS predictamen ON predictamen.id = evaluacion.predictamen_id
                LEFT JOIN
            gen_documentos AS dictamen ON dictamen.id = evaluacion.dictamen_id
            where evaluacion.id = %s;", $historico_actual[0]['padre'] == null ? $evaluacion_actual : $historico_actual[0]['padre']);
        $historico_previo = BD::$db->queryAll($sql_historico_previo);   
        $vars['historico_actual'] = $historico_actual;
        $vars['historico_previo'] = $historico_previo;
        
  
        View::render('evaluaciones/ver_historico.php', $vars);
    }
    
    
}
