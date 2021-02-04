<?php
Load::model2('GeneralModel');
class lmccController extends ControllerBase{
    
    private $model;
    private $institucion_id = 41;
    
    public function __construct(){
        parent::__construct();
        $this->model = new GeneralModel($this->db);
    }
    
    public function get_options_promedios_id(){
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $options = array();
        if($id == 0){
            $options = array(
                "id" => "0",
                "opcion" => "Promedios institucionales"
            );
        }
        else{
            $sql_prog = sprintf("select id,programa as opcion from eval_programas where id = %s", $id);
            $prog = BD::$db->queryRow($sql_prog);
            $options = $prog;
        }
        echo json_encode($options);
    }
    
    public function get_options_promedios(){
        header('Content-Type: application/json');
        $usuario = Auth::info_usuario("usuario");
        $options = array(array(
            "id" => "0",
            "opcion" => "Promedios institucionales"
        ));
         $sql_default_institucion = sprintf("select eval_instituciones_grupos_asociacion.cod_institucion, 
            eval_instituciones_grupos_asociacion.cod_grupo, eval_instituciones_grupos.logo,
            eval_instituciones_grupos.nombre, eval_instituciones_grupos.abreviatura  
            from lmcc_accesos inner join eval_instituciones_grupos on eval_instituciones_grupos.id = 
            lmcc_accesos.grupo_institucion
            inner join eval_instituciones_grupos_asociacion on eval_instituciones_grupos.id = 
            eval_instituciones_grupos_asociacion.cod_grupo where eval_instituciones_grupos_asociacion.default = 1 
            and lmcc_accesos.usuario_lmcc = '%s'", $usuario);
        $default_institucion = BD::$db->queryRow($sql_default_institucion);
//        var_dump($default_institucion);
        $grupo_unidades = 1;
        if($default_institucion['cod_grupo'] > 0){
            $grupo_unidades = $default_institucion['cod_grupo'];
        }
        $sql_unidades = sprintf("select cod_institucion, nom_institucion from eval_instituciones_grupos_asociacion
            inner join eval_instituciones on eval_instituciones.id = 
            eval_instituciones_grupos_asociacion.cod_institucion
            where cod_grupo = %s", $grupo_unidades);
        $unidades = BD::$db->queryAll($sql_unidades);
//        var_dump($unidades);
        foreach($unidades as $un){
            $sql_prog = sprintf("select id,programa as opcion from eval_programas where cod_institucion = %s", $un["cod_institucion"]);
            $prog = BD::$db->queryAll($sql_prog);
//            var_dump($prog);
            $options = array_merge($options, $prog);
        }
        echo json_encode($options);
        
    }
    
    public function index(){
        //default institucion
        $usuario = Auth::info_usuario("usuario");
        $options = array(array(
            "id" => "0",
            "opcion" => "Promedios institucionales"
        ));
        $sql_default_institucion = sprintf("select eval_instituciones_grupos_asociacion.cod_institucion, 
            eval_instituciones_grupos_asociacion.cod_grupo, eval_instituciones_grupos.logo,
            eval_instituciones_grupos.nombre, eval_instituciones_grupos.abreviatura  
            from lmcc_accesos inner join eval_instituciones_grupos on eval_instituciones_grupos.id = 
            lmcc_accesos.grupo_institucion
            inner join eval_instituciones_grupos_asociacion on eval_instituciones_grupos.id = 
            eval_instituciones_grupos_asociacion.cod_grupo where eval_instituciones_grupos_asociacion.default = 1 
            and lmcc_accesos.usuario_lmcc = '%s'", $usuario);
        $default_institucion = BD::$db->queryRow($sql_default_institucion);
//        var_dump($default_institucion);
        if($default_institucion['cod_institucion'] > 0){
            $this->institucion_id = $default_institucion['cod_institucion'];
            $vars['logo'] = $default_institucion['logo'];
            $vars['nombre'] = $default_institucion['nombre'];
            $vars['abr'] = $default_institucion['abreviatura'];
        }            
        
        if($_GET['i'] > 0){
            $this->institucion_id = $_GET['i'];
        }
        
        //por ahora el grupo de unidades academicas sera el de UDG
        $grupo_unidades = 1;
        if($default_institucion['cod_grupo'] > 0){
            $grupo_unidades = $default_institucion['cod_grupo'];
        }
        $sql_unidades = sprintf("select cod_institucion, nom_institucion from eval_instituciones_grupos_asociacion
            inner join eval_instituciones on eval_instituciones.id = 
            eval_instituciones_grupos_asociacion.cod_institucion
            where cod_grupo = %s", $grupo_unidades);
        $unidades = BD::$db->queryAll($sql_unidades);
        
        
        $sql = sprintf("SELECT evaluacion.id FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1", $this->institucion_id); 
        
        $evaluaciones = BD::$db->queryAll($sql);
        
        $sql2 = sprintf("SELECT avg(gradacion_escalas.valor_escala) FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = evaluacion.cod_conjunto
            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
            inner join lineamientos on lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
            inner join momento_evaluacion on momento_evaluacion.cod_evaluacion = evaluacion.id
            inner join momento_resultado on momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
            inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
            inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1
            and momento_evaluacion.cod_momento = 2", $this->institucion_id); 
        $pr_general = BD::$db->queryOne($sql2);
        $pr_general = round($pr_general, 2);
        
        $sql3 = sprintf("
            SELECT max(prom_query.prom) from (
                SELECT avg(gradacion_escalas.valor_escala) as prom, evaluacion.id FROM eval_instituciones 
                inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
                inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
                inner join lineamientos_conjuntos on lineamientos_conjuntos.id = evaluacion.cod_conjunto
                inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_conjunto = lineamientos_conjuntos.id
                inner join lineamientos on lineamientos.id = lineamientos_detalle_conjuntos.cod_lineamiento
                inner join momento_evaluacion on momento_evaluacion.cod_evaluacion = evaluacion.id
                inner join momento_resultado on momento_resultado.cod_momento_evaluacion = momento_evaluacion.id
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1
                and momento_evaluacion.cod_momento = 2 group by evaluacion.id) as prom_query", $this->institucion_id); 
        $pr_mayor = BD::$db->queryOne($sql3);
        $pr_mayor = round($pr_mayor, 2);
        
       $sql4 = sprintf("SELECT evaluacion.id FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1
            and evaluacion.finalizado=0", $this->institucion_id); 
        $ev_activas = BD::$db->queryAll($sql4);
        
        foreach($unidades as $un){
            $sql_prog = sprintf("select id,programa as opcion from eval_programas where cod_institucion = %s", $un["cod_institucion"]);
            $prog = BD::$db->queryAll($sql_prog);
//            var_dump($prog);
            $options = array_merge($options, $prog);
        }
        
        $vars['evaluaciones'] = $evaluaciones;
        $vars['pr_general'] = $pr_general;
        $vars['pr_mayor'] = $pr_mayor;
        $vars['ev_activas'] = $ev_activas;
        $vars['unidades'] = $unidades;
        $vars['opciones'] = $options;
        $vars['ins'] = $this->institucion_id;
//        $vars['rubros'] = array_map()
//        $vars['rubros_gral'] = json_encode($rubros_gral);
        View::add_css('public/js/select2/select2.css');
       View::add_css('public/js/select2/select2-bootstrap.css');
       View::add_js('public/js/select2/select2.min.js');
       View::add_js('public/js/select2/select2_locale_es.js');
        View::render('lmcc/index.php',$vars, 'lmcc.php'); 
    }
    
    public function get_rubros_gral(){
        if($_GET['i'] > 0){
            $this->institucion_id = $_GET['i'];
        }
        $sql5 = sprintf("
                SELECT 
                    avg(gradacion_escalas.valor_escala) as gradacion, lineamientos.padre_lineamiento, lineamientos.atributos_lineamiento as num
                FROM
                    evaluacion
                        INNER JOIN
                    momento_evaluacion ON evaluacion.id = momento_evaluacion.cod_evaluacion
                        INNER JOIN
                    momento_resultado ON momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                        INNER JOIN
                    momento_resultado_detalle ON momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                        INNER JOIN
                    gradacion_escalas ON gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                        INNER JOIN
                    lineamientos ON lineamientos.id = momento_resultado_detalle.cod_lineamiento
                WHERE
                evaluacion.id in (SELECT evaluacion.id FROM eval_instituciones 
                        inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
                        inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
                        where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1)
                    AND momento_evaluacion.cod_momento = 2
                group by lineamientos.padre_lineamiento 
                ", $this->institucion_id); 
        $rubros_gral = BD::$db->queryAll($sql5);
        echo json_encode($rubros_gral);
    }
    
    
    public function get_rubros_ev_reciente(){        
        if($_GET['i'] > 0){
            $this->institucion_id = $_GET['i'];
        }
        $sql7 = sprintf("select ev.etiqueta from (SELECT evaluacion.id, evaluacion.etiqueta, max(evaluacion.fecha_inicia) FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1
            and evaluacion.finalizado = 0) as ev", $this->institucion_id);
            $ev_reciente = BD::$db->queryOne($sql7);
        
        $sql5 = sprintf("select lineamientos.id, 
            lineamientos.num_orden, lineamientos.nom_lineamiento from lineamientos 
            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = lineamientos_detalle_conjuntos.cod_conjunto
            inner join evaluacion on lineamientos_conjuntos.id = evaluacion.cod_conjunto            
            where lineamientos.padre_lineamiento = 0 and evaluacion.id in (select ev.id from (SELECT evaluacion.id, max(evaluacion.fecha_inicia) FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1
            and evaluacion.finalizado = 0) as ev) order by id asc", $this->institucion_id);
        
        $rubros = BD::$db->queryAll($sql5);
//        var_dump($items);
        foreach($rubros as $key=>$rubro){
            $sql6 = sprintf("select avg(gradacion_escalas.valor_escala) as promedio, lineamientos.padre_lineamiento from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id in (select ev.id from (SELECT evaluacion.id, max(evaluacion.fecha_inicia) FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1
            and evaluacion.finalizado = 0) as ev) and momento_evaluacion.cod_momento=%s and lineamientos.padre_lineamiento=%s",$this->institucion_id, 1, $rubro['id']);
     
            $tmp = BD::$db->queryRow($sql6);
            $rubros[$key]['promedio'] = $tmp['promedio'];
//            var_dump($key." ".$rubros[$key]['id']." ".$tmp['promedio']);
            
//            var_dump($ev_reciente);
        }
        
        echo json_encode(array(
            "rubros" => $rubros,
            "etiqueta" => $ev_reciente
        ));
    }
    
    public function get_grafindicametros_ev(){
        if($_GET['i'] > 0){
            $this->institucion_id = $_GET['i'];
        }
        $sql = sprintf("SELECT evaluacion.id, evaluacion.etiqueta FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1 order by evaluacion.finalizado desc", $this->institucion_id); 
        $evaluaciones = BD::$db->queryAll($sql);

       foreach($evaluaciones as $evk => $ev){       
        $sql5 = sprintf("select lineamientos.id, 
            lineamientos.num_orden from lineamientos 
            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = lineamientos_detalle_conjuntos.cod_conjunto
            inner join evaluacion on lineamientos_conjuntos.id = evaluacion.cod_conjunto            
            where lineamientos.padre_lineamiento = 0 and evaluacion.id = %s order by id asc", $ev['id']);
        
        $rubros = BD::$db->queryAll($sql5);
//        var_dump($items);
        foreach($rubros as $key=>$rubro){
            $sql6 = sprintf("select avg(gradacion_escalas.valor_escala) as promedio, lineamientos.padre_lineamiento from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.padre_lineamiento=%s",
                    $ev['id'], 1, $rubro['id']);
            $sql7 = sprintf("select avg(gradacion_escalas.valor_escala) as promedio, lineamientos.padre_lineamiento from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.padre_lineamiento=%s",
                    $ev['id'], 2, $rubro['id']);
     
            $tmp = BD::$db->queryRow($sql6);
            $tmp2 = BD::$db->queryRow($sql7);
            $rubros[$key]['ei']['promedio'] = ($tmp['promedio'] == null ? 0 : $tmp['promedio']);
            $rubros[$key]['ee']['promedio'] = ($tmp2['promedio'] == null ? 0 : $tmp2['promedio']);
//            var_dump($key." ".$rubros[$key]['id']." ".$tmp['promedio']);
            
//            var_dump($ev_reciente);
        }
        $evaluaciones[$evk]['rubros'] = $rubros;
       }
       
       echo json_encode($evaluaciones);
    }
    
    public function test(){
        echo "EVALUACIONES DE UANL";
        $sql = sprintf("SELECT evaluacion.id, evaluacion.etiqueta FROM eval_instituciones 
            inner join eval_programas on eval_programas.cod_institucion = eval_instituciones.id
            inner join evaluacion on eval_programas.id = evaluacion.cod_evaluado
            where eval_instituciones.id = %s and evaluacion.tipo_evaluado=2 and evaluacion.visible_lmcc=1 order by evaluacion.finalizado desc", 1); 
        $evaluaciones = BD::$db->queryAll($sql);

       foreach($evaluaciones as $evk => $ev){       
        $sql5 = sprintf("select lineamientos.id, 
            lineamientos.num_orden from lineamientos 
            inner join lineamientos_detalle_conjuntos on lineamientos_detalle_conjuntos.cod_lineamiento = lineamientos.id
            inner join lineamientos_conjuntos on lineamientos_conjuntos.id = lineamientos_detalle_conjuntos.cod_conjunto
            inner join evaluacion on lineamientos_conjuntos.id = evaluacion.cod_conjunto            
            where lineamientos.padre_lineamiento = 0 and evaluacion.id = %s order by id asc", $ev['id']);
        
        $rubros = BD::$db->queryAll($sql5);
//        var_dump($items);
        foreach($rubros as $key=>$rubro){
            $sql6 = sprintf("select avg(gradacion_escalas.valor_escala) as promedio, lineamientos.padre_lineamiento from evaluacion 
                inner join momento_evaluacion on evaluacion.id = momento_evaluacion.cod_evaluacion
                inner join momento_resultado on momento_evaluacion.id = momento_resultado.cod_momento_evaluacion
                inner join momento_resultado_detalle on momento_resultado.id = momento_resultado_detalle.cod_momento_resultado
                inner join gradacion_escalas on gradacion_escalas.id = momento_resultado_detalle.cod_gradacion_escala
                inner join lineamientos on lineamientos.id = momento_resultado_detalle.cod_lineamiento
                where evaluacion.id = %s and momento_evaluacion.cod_momento=%s and lineamientos.padre_lineamiento=%s",
                    $ev['id'], 1, $rubro['id']);
     
            $tmp = BD::$db->queryRow($sql6);
            $rubros[$key]['promedio'] = ($tmp['promedio'] == null ? 0 : $tmp['promedio']);
//            var_dump($key." ".$rubros[$key]['id']." ".$tmp['promedio']);
            
//            var_dump($ev_reciente);
        }
        $evaluaciones[$evk]['rubros'] = $rubros;
       }
       
       echo json_encode($evaluaciones);
        
        
    }
    
    
    
    
}
