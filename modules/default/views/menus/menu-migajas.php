<?php
$get = $_GET;
$controlador = $get['controlador'];
unset($get['controlador']);
$accion = $get['accion'];
unset($get['accion']);

$get_invertido = array_reverse($get);

$copia = $get;

$config = array(
    'sede' => array('controlador' => 'aca_sedes', 'descripcion' => 'Sedes'),
    'pe' => array('controlador' => 'aca_planestudio', 'descripcion' =>'Plan de Estudio'),
    'pea' => array('controlador' => 'aca_planestudio_asignaturas', 'descripcion' =>'Plan de Estudio Asignaturas'),
    'und' => array('controlador' => 'aca_unidades_aprendizaje', 'descripcion' =>'Unidades'),
    'tema' => array('controlador' => 'aca_temas', 'descripcion' => 'Temas'),
    'cont' => array('controlador' => 'aca_contenidos', 'descripcion' => 'Contenidos'),
    'compe' => array('controlador' => 'aca_competencias', 'descripcion' =>'Competencias')
);

$array_func = array(
    'sede' => 'func_sede',
    'pe' => 'func_pe',
    'pea' => 'func_pea',
    'und' => 'func_und',
    'tema' => 'func_tema',
    'cont' => 'func_cont',
    'compe' => 'func_compe'
);

function func_sede($id){
    return 'hola'.$id;
}

function func_pe($id,$db){
    $sql = sprintf('SELECT plan_estudio FROM aca_planestudio WHERE id = %s',$id);
    $db->setLimit(1);
    return $db->queryOne($sql);
}

function func_pea($id,$db){
    $sql = sprintf('SELECT aca_asignaturas.asignatura, aca_grados.grado FROM aca_planestudio_asignaturas INNER JOIN aca_asignaturas ON aca_planestudio_asignaturas.cod_asignatura = aca_asignaturas.id INNER JOIN aca_grados ON aca_planestudio_asignaturas.cod_grado = aca_grados.id WHERE aca_planestudio_asignaturas.id = %s',$id);
    $db->setLimit(1);
    $resp = $db->queryAll($sql);
    $resp = $resp[0];
    return $resp['asignatura'].' de '.$resp['grado'];
}

function func_und($id,$db){
    $sql = sprintf('SELECT unidad_aprendizaje FROM aca_unidades_aprendizaje WHERE id = %s',$id);
    $db->setLimit(1);
    $resp = $db->queryAll($sql);
    $resp = $resp[0];
    return $resp['unidad_aprendizaje'];
}

function func_tema($id,$db){
    $sql = sprintf('SELECT tema FROM aca_temas WHERE id = %s',$id);
    $db->setLimit(1);
    $resp = $db->queryAll($sql);
    $resp = $resp[0];
    return $resp['tema'];
}

function func_cont($id,$db){
    $sql = sprintf('SELECT contenido FROM aca_contenidos WHERE id = %s',$id);
    $db->setLimit(1);
    $resp = $db->queryAll($sql);
    $resp = $resp[0];
    return $resp['contenido'];
}

function func_compe($id,$db){
    $sql = sprintf('SELECT competencia FROM aca_competencias WHERE id = %s',$id);
    $db->setLimit(1);
    $resp = $db->queryAll($sql);
    $resp = $resp[0];
    return $resp['competencia'];
}

foreach ($get_invertido as $key => $val){
    $temp = array_pop($copia);
    $get_invertido[$key] = array(
        'url' => http_build_query($copia),
        'nombre' => ucwords(strtolower(call_user_func($array_func[$key],$val,$this->db))),
        'controlador' => $config[$key]['controlador']
    );
}

$get_invertido = array_reverse($get_invertido);

unset($get_invertido['sede']);

//print_r($get_invertido);
?>

<div class="row">
    <?php if (!empty($get_invertido)): ?>
    <ol class="breadcrumb" style="float: left;">
        <?php foreach ($get_invertido as $row): ?> 
            <li>
                <a href="index.php?controlador=<?php echo $row['controlador'] ?>&accion=<?php echo $accion ?>&<?php echo $row['url'] ?>"><?php echo $row['nombre'] ?></a>
            </li>
        <?php endforeach; ?>
    </ol>
    <?php endif; ?>
</div>

