
<style>
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #F5A9BC;
    }


    .table ul.lista{
        padding: 0;
        margin-left: 11px;
    }

    .table ul.lista li{
        list-style: square;
    }

	td{
        font-size: 12px;
		padding: 0;
    }

    th{
        font-size: 12px;
		text-align:center;
		color: #fff;
    }

    thead{
		background: #800000;
		font-size: 14px;
		vertical-align:middle;
		font-weight:bold;
		color:#FFFFFF;
		text-align:center;
    }

    .popover{
        max-width: 750px;
    }

    .no-border thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: none;
    }
    
    .profesores td:nth-child(2){
        text-align: center;
        color: red;
    }
</style>


<h4 class="sub-header">
	Gestor de tablas estadísticas | RUBRO <?php echo $rubro['num_orden'].'. '.$rubro['nom_lineamiento']; ?>
</h4>
<hr/>
<div class="controls">
    <a href="#" class="btn btn-primary" id="agregar-data" data-tipo="<?php echo $rubro['num_orden']?>"><i class="glyphicon glyphicon-plus-sign"></i> Agregar datos a tabla</a>
    <a href="index.php?mod=sievas&controlador=evaluar&accion=generar_tabla_estadistica&id=<?php echo $rubro['id']?>" class="btn btn-default" id="reporte-data" ><i class="glyphicon glyphicon-download"></i> Descargar tabla</a>
    <a href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas" class="btn btn-default">Volver a tablas estadísticas</a>
</div>
<br/>
<input type="hidden" id="subtabla" value="<?php echo $subtabla ?>">
<div class="add-form"></div>
<div class="tabla_datos">
    <input type="hidden" value="<?php echo $evaluacion ?>" id="ev_id" >
<?php if(count($tabla_datos) > 0){
    foreach($tabla_datos as $key=>$tab){
        switch($rubro['num_orden']){
            case 1: ?>
                <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
                    <div class="panel-body">
                      <table class="table">
                          <tr>
                              <td colspan="2" style="text-align: right">
                                  <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                              </td>
                          </tr>
                          <tr>
                              <td>Año</td>
                              <td class="anio"><?php echo $tab['anio'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de egresados </td>
                              <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
                          </tr>
                          <tr>
                              <td>Egresados con reconocimiento nacional o internacional </td>
                              <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
                          </tr>
                          <tr>
                              <td>Egresados ejerciendo actividades en su ambito formativo en el extranjero</td>
                              <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
                          </tr>
                          <tr>
                              <td>Egresados ejerciendo actividades en su ambito formativo en el pais</td>
                              <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de instituciones que opinan de la formacion siendo empleadoras o con vínculos</td>
                              <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de instituciones que opinan favorablemente a la formación</td>
                              <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de egresados que son encuestados en referencia a su formación</td>
                              <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de egresados que opinan favorablemente a su formación</td>
                              <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de instituciones de la disciplina o colegios especializados que opinan de la formación</td>
                              <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de instituciones de la disciplina o colegios especializados que opinan favorablemente a la formación</td>
                              <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de profesores que opinan de la formación</td>
                              <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de profesores que opinan a favor de la formación</td>
                              <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes que opinan de su formación</td>
                              <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes que opinan a favor de su formación</td>
                              <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
                          </tr>
                      </table>

                      </div>
                    </div>
             <?php break;
            case 2:   ?>
                <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
                    <div class="panel-body">
                      <table class="table">
                          <tr>
                              <td colspan="2" style="text-align: right">
                                  <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>" data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#"  data-id="<?php echo $tab['id'] ?>" data-tipo="<?php echo $rubro['num_orden']?>" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                              </td>
                          </tr>
                          <tr>
                              <td>Año</td>
                              <td class="anio"><?php echo $tab['anio'] ?></td>
                          </tr>
                          <tr>
                              <td>Profesores que participan en el PE</td>
                              <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
                          </tr>
                          <tr>
                              <td>Numero de profesores que realizan investigación</td>
                              <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
                          </tr>
                          <tr>
                              <td>Artículos publicados </td>
                              <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
                          </tr>
                          <tr>
                              <td>Capítulos de libros </td>
                              <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
                          </tr>
                          <tr>
                              <td>Desarrollos tecnológicos</td>
                              <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
                          </tr>
                          <tr>
                              <td>Certificaciones por consejos externos</td>
                              <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
                          </tr>
                          <tr>
                              <td>Distinciones</td>
                              <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
                          </tr>
                          <tr>
                              <td>Divulgación y difusión</td>
                              <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
                          </tr>
                          <tr>
                              <td>Docencia</td>
                              <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
                          </tr>
                          <tr>
                              <td>Estancias de investigacion</td>
                              <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
                          </tr>
                          <tr>
                              <td>Redes de investigación</td>
                              <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
                          </tr>
                          <tr>
                              <td>Libros</td>
                              <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
                          </tr>
                          <tr>
                              <td>Grados académicos</td>
                              <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
                          </tr>
                          <tr>
                              <td>Participación en congresos</td>
                              <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
                          </tr>
                          <tr>
                              <td>Patentes</td>
                              <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
                          </tr>
                          <tr>
                              <td>Proyectos de investigación</td>
                              <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
                          </tr>
                          <tr>
                              <td>Reportes técnicos</td>
                              <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
                          </tr>
                          <tr>
                              <td>Reseñas</td>
                              <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
                          </tr>
                          <tr>
                              <td>Tesis dirigidas</td>
                              <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
                          </tr>
                          <tr>
                              <td>Idiomas que domina</td>
                              <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
                          </tr>
                      </table>

                      </div>
                    </div>
             <?php break;
            case 3:   ?>
            <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
                    <div class="panel-body">
                      <table class="table">
                          <tr>
                              <td colspan="2" style="text-align: right">
                                  <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>" data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                              </td>
                          </tr>
                          <tr>
                              <td>Año</td>
                              <td class="anio"><?php echo $tab['anio'] ?></td>
                          </tr>
                          <tr>
                              <td>Numero de aspirantes</td>
                              <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de aspirantes cuya residencia sea localizada a menos de 50 kms de la Universidad  </td>
                              <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de aspirantes cuya residencia sea fuera del estado o departamento </td>
                              <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de aspirantes extranjeros</td>
                              <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes admitidos</td>
                              <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes admitidos cuya residencia sea localizada a menos de 50 kms de la Universidad</td>
                              <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes admitidos  cuya residencia sea localizada a mas de 50 kms de la Universidad </td>
                              <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes admitidos cuya residencia sea fuera del estado o departamento</td>
                              <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes admitidos extranjeros</td>
                              <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes becados</td>
                              <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de estudiantes admitidos de zonas marginadas o susceptibles</td>
                              <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
                          </tr>
                          <tr>
                              <td>Promedio de calificaciones según el rango o escala de medición</td>
                              <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de egresados</td>
                              <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de titulados con egreso no mayor a 6 meses</td>
                              <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de egresados titulados que ejercen su profesión, insertados en el mercado laboral </td>
                              <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
                          </tr>
                      </table>

                      </div>
                    </div>
             <?php break;
            case 4:  ?>
                <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
                    <div class="panel-body">
                      <table class="table" style="width: 100%">
                          <tr>
                              <td colspan="2" style="text-align: right">
                                  <a href="#" class="btn btn-primary btn-xs editar-tabla-datos"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                              </td>
                          </tr>
                          <tr>
                              <td>Año</td>
                              <td class="anio" style="text-align: right"><?php echo $tab['profesores'][0]['anio'] ?></td>
                          </tr>
                      </table>
                      <?php foreach($tab['profesores'] as $tpr){ ?>

                      <table class="table profesores" style="width: 100%">
                          <tr>
                              <td>Nombre Del Profesor que participa en el PE</td>
                              <td class="campo_a"><?php echo $tpr['campo_a'] ?></td>
                          </tr>
                          <tr>
                              <td>Grado Académico Actual y área del conocimiento en que fue obtenido</td>
                              <td class="campo_b"><?php echo $tpr['campo_b'] ?></td>
                          </tr>
                          <tr>
                              <td>Categoría Académica o Laboral, indicando horas de trabajo académico por semana </td>
                              <td class="campo_c"><?php echo $tpr['campo_c'] ?></td>
                          </tr>
                          <tr>
                              <td>Nombre de Asignaturas o materias que imparte en el PE</td>
                              <td class="campo_d"><?php echo $tpr['campo_d'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de proyectos de Investigación que desarrollan y línea de generación y aplicación del conocimiento señalando el nombre de los principales</td>
                              <td class="campo_e"><?php echo $tpr['campo_e'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de la Actividad de Vinculación y nombre de las principales</td>
                              <td class="campo_f"><?php echo $tpr['campo_f'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de la Actividad de Difusión y nombre de las principales</td>
                              <td class="campo_g"><?php echo $tpr['campo_g'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de Actividades de Internacionalización y nombre de las principales</td>
                              <td class="campo_h"><?php echo $tpr['campo_h'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de Estudiantes Tutorados por profesor</td>
                              <td class="campo_i"><?php echo $tpr['campo_i'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de Actividades de Superación Académica y nombre de las principales</td>
                              <td class="campo_j"><?php echo $tpr['campo_j'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de Actividades Colegiadas ó Redes  (Locales, Nacionales e Internacionales) y nombre de las principales</td>
                              <td class="campo_k"><?php echo $tpr['campo_k'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de Estudiantes Dirigidos en Actividades de Prácticas Profesionales y Estancias académicas</td>
                              <td class="campo_l"><?php echo $tpr['campo_l'] ?></td>
                          </tr>
                          <tr>
                              <td>Número de Actividades Extracurriculares y nombre de las principales</td>
                              <td class="campo_m"><?php echo $tpr['campo_m'] ?></td>
                          </tr>
                      </table>
                      <?php } ?>
                      </div>
                    </div>
             <?php break;
            case 5:   ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Eje 1</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Eje 2</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Eje 3</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Eje 4</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Eje 5</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de Créditos por Asignatura</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Carga horaria semanal por ciclo</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Horas Teoría</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Horas Práctica</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Horas Curriculares</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Horas Extracurriculares</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Asignatura vinculada al entorno internacional (Si ó No)</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Uso de Nuevas Tecnologías de la Información y Comunicación para cada Asignatura(Si ó No)</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Asignatura relacionada al Medio Ambiente
(Si ó No)</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Asignatura relacionada a la equidad de género
(Si ó No)</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Materias vinculadas a proyectos de investigación que realizan profesores del PE</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>Número de Ciclos escolares del PE</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>Número de asignaturas  promedio en el que esta inscrito el Estudiante por ciclo escolar</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td>Número de horas obligatorias en prácticas profesionales relacionados al Plan de Estudios</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>Número de materias en el que esta inscrito el Estudiante en el primer ciclo escolar</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores invitados de universidades extranjeras que imparten asignaturas en el PE</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con actividad externa preponderante que imparte al menos una asignatura en el PE</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Asignaturas vinculadas a programas de extensión en el que participan Estudiantes</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Asignaturas vinculadas a Redes Académicas</td>
                  <td class="campo_x"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td>Existe sobre la media nacional posicionamiento aceptable del PE frente a otros similares en el país</td>
                  <td class="campo_y"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>Cuenta con evidencia de su posicionamiento sobre otros PE similares</td>
                  <td class="campo_z"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>Se relaciona el contenido tematico de la asignatura con el perfil de egreso
(Si ó No)</td>
                  <td class="campo_aa"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Derechos humanos</td>
                  <td class="campo_ab"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a la Equidad de Género</td>
                  <td class="campo_ac"><?php echo $tab['campo_ac'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas al Desarrollo sustentable</td>
                  <td class="campo_ad"><?php echo $tab['campo_ad'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a la Superación integral-social comunitario</td>
                  <td class="campo_ae"><?php echo $tab['campo_ae'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Valores Etícos</td>
                  <td class="campo_af"><?php echo $tab['campo_af'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Valores Sociales</td>
                  <td class="campo_ag"><?php echo $tab['campo_ag'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los  Formación Política Ideológica</td>
                  <td class="campo_ah"><?php echo $tab['campo_ah'] ?></td>
              </tr>
              <tr>
                  <td>Uso de Nuevas Tecnologías para el desarrollo social (Mencionar)</td>
                  <td class="campo_ai"><?php echo $tab['campo_ai'] ?></td>
              </tr>
              <tr>
                  <td>Número de programas institucionales en los que participan Estudiantes</td>
                  <td class="campo_aj"><?php echo $tab['campo_aj'] ?></td>
              </tr>
              <tr>
                  <td>Número de programas institucionales en los que participan Profesores</td>
                  <td class="campo_ak"><?php echo $tab['campo_ak'] ?></td>
              </tr>
              <tr>
                  <td>Otros</td>
                  <td class="campo_al"><?php echo $tab['campo_al'] ?></td>
              </tr>
          </table>
          </div>
        </div>
             <?php break;
            case 6:   ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos"  data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de reuniones colegiadas para evaluación y seguimiento de metodologías de aprendizaje </td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de reuniones interdiciplenarias de  profesores que participan en el PE </td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores del PE que participan en al menos un evento de actualización disciplinar</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Total de profesores que participan en el PE como docentes </td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores evaluados por pares academicos en su actividad docente</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores evaluados por estudiantes al final de cada ciclo escolar</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con resultados de excelencia o sobresalientes en la evaluación de estudaintes por ciclo escolar</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con niveles bajos en los  resultados de la evaluación por estudiantes  por ciclo escolar</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de programas o cursos de la institución para la superación academica del profesor </td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con niveles bajos en los  resultados de la evaluación por estudiantes  por ciclo escolar</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
          </table>
          </div>
        </div>
             <?php break;
            case 7:   ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
               <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de aulas asignadas al PE</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Dimensiones de las aulas que atienden al PE</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de superficie (mts) construidos por aula</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Total de superficie (mts) en áreas verdes</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Total de superficie (mts) en áreas deportivas</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Total de sanitarios  </td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Total de instalaciones destinadas a servicio de alimentos</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) del Centro de Laboratorio de Computo y/o Tecnologías para el aprendizaje</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de software especializado que atiende al PE</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Total de licencias de los software especializados que atienden al PE</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Cantidad estimada de equipo especializado de laboratorio</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Número de Laboratorios</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Laboratorios</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Número de Talleres</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Talleres</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de Auditorios (Salas de exposición, Eventos Culturales y Eventos Académicos)</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Auditorios</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>Número de Salas de estudio para Profesores</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por sala</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>Número de salas de estudio para Estudiantes</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por sala</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>Número de cubículos para Profesores</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por cubículo</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Total de computadoras que atienden al PE</td>
                  <td class="campo_x"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Número de Volumenes en biblioteca</td>
                  <td class="campo_y"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Númeo de Titulos en biblioteca</td>
                  <td class="campo_z"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td>Número de Colecciones en biblioteca</td>
                  <td class="campo_aa"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>Número de Suscripciones a revistas Científicas en biblioteca</td>
                  <td class="campo_ab"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>Número de Tesis existentes en biblioteca realizadas por Egresados del PE</td>
                  <td class="campo_ac"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>Estadisticas anuales de visitas a la biblioteca por parte de Estudiantes del PE</td>
                  <td class="campo_ad"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>Estadisticas anuales de visitas a la biblioteca por parte de Profesores del PE</td>
                  <td class="campo_ae"><?php echo $tab['campo_ac'] ?></td>
              </tr>
          </table>
          </div>
        </div>
             <?php break;
            case 8:   ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Promedio anual de colocación al sector productivo de egresados del pe, desde la bolsa de trabajo de la institución</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Gobierno</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Empresa</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Instituciones educativas y de investigación</td>
                  <td class="campo_x"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Organizaciones no gubernamentales</td>
                  <td class="campo_y"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Gobierno</td>
                  <td class="campo_z"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Empresa</td>
                  <td class="campo_aa"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Instituciones educativas y de investigación</td>
                  <td class="campo_ab"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Organizaciones no gubernamentales</td>
                  <td class="campo_ac"><?php echo $tab['campo_ac'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Gobierno</td>
                  <td class="campo_ad"><?php echo $tab['campo_ad'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Empresa</td>
                  <td class="campo_ae"><?php echo $tab['campo_ae'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Instituciones educativas y de investigación</td>
                  <td class="campo_af"><?php echo $tab['campo_af'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Organizaciones no gubernamentales</td>
                  <td class="campo_ag"><?php echo $tab['campo_ag'] ?></td>
              </tr>
              <tr>
                  <td>Numero de actividades de difusión del PE por los distintos medios de comunicación</td>
                  <td class="campo_ah"><?php echo $tab['campo_ah'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos culturales, deportivos, artísticos, académicos: congresos, seminarios, cursos, etc. Nacionales e internacionales del PE </td>
                  <td class="campo_ai"><?php echo $tab['campo_ai'] ?></td>
              </tr>
          </table>
          </div>
        </div>
             <?php break;
            case 9:   ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de convenios  de cooperación internacional vigentes</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de asignaturas del plan de estudios del PE  con contenido de internacionalización</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de asignaturas del PE con dinámica de trabajo en redes académicas</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que participan en estancias académicas en otras universidades</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes de origen extranjero realizando estancia  de intercambio en el PE</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores del pe que participan en redes académicas</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores de origen extranjero realizando estancia de intercambio en el PE</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de proyectos de investigación interinstitucional vinculados al PE</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Numero de productos como resultado de proyectos de investigación interinstitucional vinculados al PE (libros, artículos, patentes, memorias, reportes, etc.)</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de proyectos de extensión interinstitucionales vinculados al PE</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales organizados por el PE   o la institución por año</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales en el que participan estudiantes del PE</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales en el que participan profesores del PE</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos virtuales internacionales del PE (video-conferencias, cursos virtuales, etc.)</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Numero de tutores, directores de tesis y directores de prácticas profesionales a extranjeros que apoyan al PE</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores del PE que realizan tutorías, dirección de tesis, dirección de prácticas profesionales a estudiantes de otras universidades</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
          </table>
          </div>
        </div>
             <?php break;
            case 10:   ?>
                <?php switch($subtabla){
                    case 1:?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Sitios Virtuales de Acceso a la Información</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad  que regula la actividad laboral  y académica de los Profesores</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la actividad académica de los Estudiantes</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula al Plan de Estudios</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad  o políticas que regulan la Formación Integral</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la planeación, programación, presupuestación y ejercicio de los Recursos Financieros</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula el uso, crecimiento, mantenimiento  de Instalaciones, equipamiento y  tecnologías </td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la Investigación y Extensión</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la actividad del Personal Administrativo</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la Internacionalización</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
          </table>
          </div>
        </div>
          <?php     break;
                    case 2: ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Normatividad</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Profesorado</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Estudiantes</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Plan de estudios</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_x"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Formación integral</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_y"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_z"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_aa"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_ab"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ac"><?php echo $tab['campo_ac'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_ad"><?php echo $tab['campo_ad'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Recursos Financieros</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_ae"><?php echo $tab['campo_ae'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_af"><?php echo $tab['campo_af'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_ag"><?php echo $tab['campo_ag'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_ah"><?php echo $tab['campo_ah'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ai"><?php echo $tab['campo_ai'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_aj"><?php echo $tab['campo_aj'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Instalaciones</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_ak"><?php echo $tab['campo_ak'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_al"><?php echo $tab['campo_al'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_am"><?php echo $tab['campo_am'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_an"><?php echo $tab['campo_an'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ao"><?php echo $tab['campo_ao'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_ap"><?php echo $tab['campo_ap'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Investigación y Extensión</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_aq"><?php echo $tab['campo_aq'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_ar"><?php echo $tab['campo_ar'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_as"><?php echo $tab['campo_as'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_at"><?php echo $tab['campo_at'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_au"><?php echo $tab['campo_au'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_av"><?php echo $tab['campo_av'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Personal Administrativo</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_aw"><?php echo $tab['campo_aw'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_ax"><?php echo $tab['campo_ax'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_ay"><?php echo $tab['campo_ay'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_az"><?php echo $tab['campo_az'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ba"><?php echo $tab['campo_ba'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bb"><?php echo $tab['campo_bb'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Internacionalización</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_bc"><?php echo $tab['campo_bc'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_bd"><?php echo $tab['campo_bd'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_be"><?php echo $tab['campo_be'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_bf"><?php echo $tab['campo_bf'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_bg"><?php echo $tab['campo_bg'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bh"><?php echo $tab['campo_bh'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r10_2_2_2_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Normatividad</td>
                  <td class="campo_bi"><?php echo $tab['campo_bi'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Profesorado</td>
                  <td class="campo_bj"><?php echo $tab['campo_bj'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Estudiantes</td>
                  <td class="campo_bk"><?php echo $tab['campo_bk'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Plan de Estudios</td>
                  <td class="campo_bl"><?php echo $tab['campo_bl'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Formación Integral</td>
                  <td class="campo_bm"><?php echo $tab['campo_bm'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Recursos Financieros</td>
                  <td class="campo_bn"><?php echo $tab['campo_bn'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Instalaciones</td>
                  <td class="campo_bo"><?php echo $tab['campo_bo'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Investigación y Extensión</td>
                  <td class="campo_bp"><?php echo $tab['campo_bp'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bq"><?php echo $tab['campo_bq'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Internacionalización</td>
                  <td class="campo_br"><?php echo $tab['campo_br'] ?></td>
              </tr>
          </table>
          </div>
        </div>
           <?php    break;
                    case 3:  ?>
    <div class="panel panel-default item-tabla item-tabla-<?php echo $tab['id'] ?>">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos" data-id="<?php echo $tab['id'] ?>"  data-tipo="<?php echo $rubro['num_orden']?>">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Numero de metas trazadas  del plan anual de desarrollo del PE por año</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Numero de metas cumplidas  del plan anual de desarrollo del pe por año</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Total de  áreas o procesos administrativos que atienden  al PE en sus distintos rubros</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Total de  áreas o procesos administrativos reconocidos por su calidad que atiende al PE en sus distintos rubros</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
          </table>
          </div>
        </div>
          <?php     break;
                    } ?>
     <?php break; }  ?>

<?php }
} else { ?>
    <div class="panel panel-default no-data">
    <div class="panel-body">
      No hay datos en la tabla
    </div>
  </div>
<?php }  ?>
</div>


<div class="panel panel-default no-data-tpl hide">
    <div class="panel-body">
      No hay datos en la tabla
    </div>
  </div>

<div class="tablas_estadisticas_tpl">
<?php 
switch(Auth::info_usuario('tipo_evaluado')){
    case 10: ?>
     <div class="tabla_estadistica_r1_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas asociadas al plan de
desarrollo institucional </td>
                  <td class="campo_a"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas asociadas al plan de
desarrollo de la dependencia</td>
                  <td class="campo_b"></td>
              </tr>
              <tr>
                  <td>Número de implementaciones</td>
                  <td class="campo_c"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas en Prácticas</td>
                  <td class="campo_d"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas en Experiencias</td>
                  <td class="campo_e"></td>
              </tr>
              <tr>
                  <td>Número de impactos logrados</td>
                  <td class="campo_f"></td>
              </tr>
              <tr>
                  <td>Numero de sesiones</td>
                  <td class="campo_g"></td>
              </tr>
              <tr>
                  <td>Número de reuniones con entes</td>
                  <td class="campo_h"></td>
              </tr>
              <tr>
                  <td>Número de comunidades atendidas de manera regular</td>
                  <td class="campo_i"></td>
              </tr>
              <tr>
                  <td>Número de reconocimientos</td>
                  <td class="campo_j"></td>
              </tr>
            
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r2_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Número de servicios realizados en procesamiento y difusión de la información científica</td>
                  <td class="campo_a"></td>
              </tr>
              <tr>
                  <td>Número de servicios realizados en acceso a artículos científicos publicados</td>
                  <td class="campo_b"></td>
              </tr>
              <tr>
                  <td>Metas cumplidas en estrategias de acceso a la Web libre científica</td>
                  <td class="campo_c"></td>
              </tr>
              <tr>
                  <td>Evaluaciones realizadas </td>
                  <td class="campo_d"></td>
              </tr>
              <tr>
                  <td>Número de proyectos</td>
                  <td class="campo_e"></td>
              </tr>
              <tr>
                  <td>Número de redes atendidas</td>
                  <td class="campo_f"></td>
              </tr>
              <tr>
                  <td>Número de software utilizados</td>
                  <td class="campo_g"></td>
              </tr>
              <tr>
                  <td>Número de plataformas digitales apoyando las tareas
de investigación</td>
                  <td class="campo_h"></td>
              </tr>
              <tr>
                  <td>Número de páginas web</td>
                  <td class="campo_i"></td>
              </tr>
              <tr>
                  <td>Número de programas y diseños de algoritmos en la
investigación</td>
                  <td class="campo_j"></td>
              </tr>
            
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r3_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Número de plataformas digitales y sistemas integrales
de información en apoyo a las gestiones académico-
administrativas</td>
                  <td class="campo_a"></td>
              </tr>
             
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r4_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Número de plataformas digitales y sistemas integrales
de información en apoyo a las gestiones académico-
administrativas</td>
                  <td class="campo_a"></td>
              </tr>
             
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r5_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de reuniones con dependencias</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la comparativa</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de las evaluaciones
y actualizaciones</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia dela organización y
operación</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la vinculación</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia o documentos que
evidencien las plataformas y las bases de datos</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de TI relacionadas a planes de estudios en vinculación con redes temáticas </td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la vinculación de las TI en comunidades internacionales de aprendizaje</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la evaluación y seguimiento del impacto del uso de las TI en planes y programas de estudio</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r6_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Realidad virtual </td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Internet de las cosas </td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Algoritmos</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Big data</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en E-book</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Redes formales</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                 <td>Número de apoyos o acciones registrados en Impresoras 3D</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Inteligencia artificial</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Almacenamiento en la nube</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en 5G (Generación 5)</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r7_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de personas que atienden la EUE</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de software en operación</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Metros cuadrados construidos de la EUE</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Cantidad de equipos de cómputo</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de registros de propiedad intelectual</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de equipos adquiridos</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Numero de metas logradas</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de metros cuadrados en crecimiento prospectivo</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de programas en función</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
             
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r8_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Actividades de extensión por profesores y
estudiantes</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Estudiantes vinculados a los sectores sociales
y/o científicos</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Profesores vinculados a los sectores sociales y/o
científicos</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Servicio social vinculado con prácticas
profesionales de la formación a los sectores de
la sociedad</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Actividades científicas tecnológicas que
organiza la institución vinculadas a la sociedad
en el que participan los profesores y estudiantes
de la formación; como eventos, ferias,
concursos, exposiciones</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Resultados de convenios, proyectos y servicios
específicos con los sectores sociales en apoyo a
la formación</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Artículos de difusión científica publicados en el
que participan profesores y estudiantes</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Programa de difusión especifico de la
formación: becas, bolsas de empleo y
convocatorias</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Concursos de conocimientos de la formación en
el que participan profesores y estudiantes</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Programas de extensión vinculadas a la
formación</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
             
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r9_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de Distinciones logradas</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de Patentes vinculadas</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de desarrollos tecnológicos vinculados</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de artículos vinculados</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de reportes vinculados</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de tesis vinculadas</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de eventos y grupos apoyados</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de actividades vinculadas en divulgación y
difusión</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de resultados de la investigación vinculados</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de proyectos vinculados</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              
          </table>
          </div>
        </div>
    </div>

 <div class="tabla_estadistica_r10_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de certificaciones obtenidas</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de acciones atendidas</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Cantidad de recursos financieros aplicados</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de metas logradas con el ejercicio
presupuestal</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de metas logradas asociadas a la gestión
de recursos</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Cantidad de recursos extraordinarios logrados</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Cantidad financiera a ejercer por año</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Contrataciones de personal por año</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
             
          </table>
          </div>
        </div>
    </div>


  <div class="tabla_estadistica_r1_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas asociadas al plan de
desarrollo institucional </td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas asociadas al plan de
desarrollo de la dependencia</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de implementaciones</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de metas logradas en Prácticas</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas en Experiencias</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de impactos logrados</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Numero de sesiones</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de reuniones con entes</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de comunidades atendidas de manera regular</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de reconocimientos</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
             
              </tr>
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r2_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
             <tr>
                  <td>Número de servicios realizados en procesamiento y difusión de la información científica</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de servicios realizados en acceso a artículos científicos publicados</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Metas cumplidas en estrategias de acceso a la Web libre científica</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Evaluaciones realizadas </td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de proyectos</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de redes atendidas</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de software utilizados</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de plataformas digitales apoyando las tareas
de investigación</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de páginas web</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de programas y diseños de algoritmos en la
investigación</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
            
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r3_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
             <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de plataformas digitales y sistemas integrales
de información en apoyo a las gestiones académico-
administrativas</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

 <div class="tabla_estadistica_r4_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
             <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de plataformas digitales y sistemas integrales
de información en apoyo a las gestiones académico-
administrativas</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r5_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de reuniones con dependencias</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la comparativa</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de actas como evidencia de las evaluaciones
y actualizaciones</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia dela organización y
operación</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la vinculación</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de actas como evidencia o documentos que
evidencien las plataformas y las bases de datos</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de actas como evidencia de TI relacionadas a planes de estudios en vinculación con redes temáticas </td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de actas como evidencia de la vinculación de las TI en comunidades internacionales de aprendizaje</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de actas como evidencia de la evaluación y seguimiento del impacto del uso de las TI en planes y programas de estudio</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
             
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r6_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Realidad virtual </td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Internet de las cosas </td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Algoritmos</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Big data</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en E-book</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de apoyos o acciones registrados en Redes formales</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de apoyos o acciones registrados en Impresoras 3D</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de apoyos o acciones registrados en Inteligencia artificial</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de apoyos o acciones registrados en Almacenamiento en la nube</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de apoyos o acciones registrados en 5G (Generación 5)</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r7_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de personas que atienden la EUE</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de software en operación</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Metros cuadrados construidos de la EUE</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Cantidad de equipos de cómputo</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de registros de propiedad intelectual</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de equipos adquiridos</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de metas logradas</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de metros cuadrados en crecimiento prospectivo</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de programas en función</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
            
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r8_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de servicios de apoyo realizados en Actividades de extensión por profesores y
estudiantes</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Estudiantes vinculados a los sectores sociales
y/o científicos</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                    <td>Número de servicios de apoyo realizados en Profesores vinculados a los sectores sociales y/o
científicos</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de servicios de apoyo realizados en Servicio social vinculado con prácticas
profesionales de la formación a los sectores de
la sociedad</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Actividades científicas tecnológicas que
organiza la institución vinculadas a la sociedad
en el que participan los profesores y estudiantes
de la formación; como eventos, ferias,
concursos, exposiciones</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Número de servicios de apoyo realizados en Resultados de convenios, proyectos y servicios
específicos con los sectores sociales en apoyo a
la formación</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de servicios de apoyo realizados en Artículos de difusión científica publicados en el
que participan profesores y estudiantes</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Programa de difusión especifico de la
formación: becas, bolsas de empleo y
convocatorias</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Concursos de conocimientos de la formación en
el que participan profesores y estudiantes</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de servicios de apoyo realizados en Programas de extensión vinculadas a la
formación</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
             
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r9_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
             <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Distinciones logradas</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Patentes vinculadas</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de desarrollos tecnológicos vinculados</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de artículos vinculados</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de reportes vinculados</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de tesis vinculadas</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de eventos y grupos apoyados</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de actividades vinculadas en divulgación y
difusión</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de resultados de la investigación vinculados</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de proyectos vinculados</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
             
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r10_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
             <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de certificaciones obtenidas</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de acciones atendidas</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                 <td>Cantidad de recursos financieros aplicados</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Número de metas logradas con el ejercicio
presupuestal</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de metas logradas asociadas a la gestión
de recursos</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Cantidad de recursos extraordinarios logrados</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Cantidad financiera a ejercer por año</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                   <td>Contrataciones de personal por año</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              
             
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
</div>



    <?php  break; ?>
    <?php default: ?>
     <div class="tabla_estadistica_r1_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Número de egresados </td>
                  <td class="campo_a"></td>
              </tr>
              <tr>
                  <td>Egresados con reconocimiento nacional o internacional </td>
                  <td class="campo_b"></td>
              </tr>
              <tr>
                  <td>Egresados ejerciendo actividades en su ambito formativo en el extranjero</td>
                  <td class="campo_c"></td>
              </tr>
              <tr>
                  <td>Egresados ejerciendo actividades en su ambito formativo en el pais</td>
                  <td class="campo_d"></td>
              </tr>
              <tr>
                  <td>Número de instituciones que opinan de la formacion siendo empleadoras o con vínculos</td>
                  <td class="campo_e"></td>
              </tr>
              <tr>
                  <td>Número de instituciones que opinan favorablemente a la formación</td>
                  <td class="campo_f"></td>
              </tr>
              <tr>
                  <td>Número de egresados que son encuestados en referencia a su formación</td>
                  <td class="campo_g"></td>
              </tr>
              <tr>
                  <td>Número de egresados que opinan favorablemente a su formación</td>
                  <td class="campo_h"></td>
              </tr>
              <tr>
                  <td>Número de instituciones de la disciplina o colegios especializados que opinan de la formación</td>
                  <td class="campo_i"></td>
              </tr>
              <tr>
                  <td>Número de instituciones de la disciplina o colegios especializados que opinan favorablemente a la formación</td>
                  <td class="campo_j"></td>
              </tr>
              <tr>
                  <td>Número de profesores que opinan de la formación</td>
                  <td class="campo_k"></td>
              </tr>
              <tr>
                  <td>Número de profesores que opinan a favor de la formación</td>
                  <td class="campo_l"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que opinan de su formación</td>
                  <td class="campo_m"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que opinan a favor de su formación</td>
                  <td class="campo_n"></td>
              </tr>
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r2_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Profesores que participan en el PE</td>
                  <td class="campo_a"></td>
              </tr>
              <tr>
                  <td>Numero de profesores que realizan investigación</td>
                  <td class="campo_b"></td>
              </tr>
              <tr>
                  <td>Artículos publicados </td>
                  <td class="campo_c"></td>
              </tr>
              <tr>
                  <td>Capítulos de libros </td>
                  <td class="campo_d"></td>
              </tr>
              <tr>
                  <td>Desarrollos tecnológicos</td>
                  <td class="campo_e"></td>
              </tr>
              <tr>
                  <td>Certificaciones por consejos externos</td>
                  <td class="campo_f"></td>
              </tr>
              <tr>
                  <td>Distinciones</td>
                  <td class="campo_g"></td>
              </tr>
              <tr>
                  <td>Divulgación y difusión</td>
                  <td class="campo_h"></td>
              </tr>
              <tr>
                  <td>Docencia</td>
                  <td class="campo_i"></td>
              </tr>
              <tr>
                  <td>Estancias de investigacion</td>
                  <td class="campo_j"></td>
              </tr>
              <tr>
                  <td>Redes de investigación</td>
                  <td class="campo_k"></td>
              </tr>
              <tr>
                  <td>Libros</td>
                  <td class="campo_l"></td>
              </tr>
              <tr>
                  <td>Grados académicos</td>
                  <td class="campo_m"></td>
              </tr>
              <tr>
                  <td>Participación en congresos</td>
                  <td class="campo_n"></td>
              </tr>
              <tr>
                  <td>Patentes</td>
                  <td class="campo_o"></td>
              </tr>
              <tr>
                  <td>Proyectos de investigación</td>
                  <td class="campo_p"></td>
              </tr>
              <tr>
                  <td>Reportes técnicos</td>
                  <td class="campo_q"></td>
              </tr>
              <tr>
                  <td>Reseñas</td>
                  <td class="campo_r"></td>
              </tr>
              <tr>
                  <td>Tesis dirigidas</td>
                  <td class="campo_s"></td>
              </tr>
              <tr>
                  <td>Idiomas que domina</td>
                  <td class="campo_t"></td>
              </tr>
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r3_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"></td>
              </tr>
              <tr>
                  <td>Numero de aspirantes</td>
                  <td class="campo_a"></td>
              </tr>
              <tr>
                  <td>Número de aspirantes cuya residencia sea localizada a menos de 50 kms de la Universidad  </td>
                  <td class="campo_b"></td>
              </tr>
              <tr>
                  <td>Número de aspirantes cuya residencia sea fuera del estado o departamento </td>
                  <td class="campo_c"></td>
              </tr>
              <tr>
                  <td>Número de aspirantes extranjeros</td>
                  <td class="campo_d"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos</td>
                  <td class="campo_e"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos cuya residencia sea localizada a menos de 50 kms de la Universidad</td>
                  <td class="campo_f"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos  cuya residencia sea localizada a mas de 50 kms de la Universidad </td>
                  <td class="campo_g"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos cuya residencia sea fuera del estado o departamento</td>
                  <td class="campo_h"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos extranjeros</td>
                  <td class="campo_i"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes becados</td>
                  <td class="campo_j"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos de zonas marginadas o susceptibles</td>
                  <td class="campo_k"></td>
              </tr>
              <tr>
                  <td>Promedio de calificaciones según el rango o escala de medición</td>
                  <td class="campo_l"></td>
              </tr>
              <tr>
                  <td>Número de egresados</td>
                  <td class="campo_m"></td>
              </tr>
              <tr>
                  <td>Número de titulados con egreso no mayor a 6 meses</td>
                  <td class="campo_n"></td>
              </tr>
              <tr>
                  <td>Número de egresados titulados que ejercen su profesión, insertados en el mercado laboral </td>
                  <td class="campo_o"></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r4_tpl hide">
    <div class="tabla_estadistica_r4_tpl_panel_profesor">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio" style="text-align: right"></td>
              </tr>

          </table>
          
          </div>
        </div>
        </div>
        <div class="tabla_estadistica_r4_tpl_item_profesor">
          <table>
          <tr>
              <td><b>Nombre Del Profesor que participa en el PE</b></td>
              <td class="campo_a"></td>
          </tr>
          <tr>
              <td>Grado Académico Actual y área del conocimiento en que fue obtenido</td>
              <td class="campo_b"></td>
          </tr>
          <tr>
              <td>Categoría Académica o Laboral, indicando horas de trabajo académico por semana </td>
              <td class="campo_c"></td>
          </tr>
          <tr>
              <td>Nombre de Asignaturas o materias que imparte en el PE</td>
              <td class="campo_d"></td>
          </tr>
          <tr>
              <td>Número de proyectos de Investigación que desarrollan y línea de generación y aplicación del conocimiento señalando el nombre de los principales</td>
              <td class="campo_e"></td>
          </tr>
          <tr>
              <td>Número de la Actividad de Vinculación y nombre de las principales</td>
              <td class="campo_f"></td>
          </tr>
          <tr>
              <td>Número de la Actividad de Difusión y nombre de las principales</td>
              <td class="campo_g"></td>
          </tr>
          <tr>
              <td>Número de Actividades de Internacionalización y nombre de las principales</td>
              <td class="campo_h"></td>
          </tr>
          <tr>
              <td>Número de Estudiantes Tutorados por profesor</td>
              <td class="campo_i"></td>
          </tr>
          <tr>
              <td>Número de Actividades de Superación Académica y nombre de las principales</td>
              <td class="campo_j"></td>
          </tr>
          <tr>
              <td>Número de Actividades Colegiadas ó Redes  (Locales, Nacionales e Internacionales) y nombre de las principales</td>
              <td class="campo_k"></td>
          </tr>
          <tr>
              <td>Número de Estudiantes Dirigidos en Actividades de Prácticas Profesionales y Estancias académicas</td>
              <td class="campo_l"></td>
          </tr>
          <tr>
              <td>Número de Actividades Extracurriculares y nombre de las principales</td>
              <td class="campo_m"></td>
          </tr>
          </table>
        </div>
    </div>

  <div class="tabla_estadistica_r5_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Eje 1</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Eje 2</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Eje 3</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Eje 4</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Eje 5</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de Créditos por Asignatura</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Carga horaria semanal por ciclo</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Horas Teoría</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Horas Práctica</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Horas Curriculares</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Horas Extracurriculares</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Asignatura vinculada al entorno internacional (Si ó No)</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Uso de Nuevas Tecnologías de la Información y Comunicación para cada Asignatura(Si ó No)</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Asignatura relacionada al Medio Ambiente
(Si ó No)</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Asignatura relacionada a la equidad de género
(Si ó No)</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Materias vinculadas a proyectos de investigación que realizan profesores del PE</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>Número de Ciclos escolares del PE</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>Número de asignaturas  promedio en el que esta inscrito el Estudiante por ciclo escolar</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td>Número de horas obligatorias en prácticas profesionales relacionados al Plan de Estudios</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>Número de materias en el que esta inscrito el Estudiante en el primer ciclo escolar</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores invitados de universidades extranjeras que imparten asignaturas en el PE</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con actividad externa preponderante que imparte al menos una asignatura en el PE</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Asignaturas vinculadas a programas de extensión en el que participan Estudiantes</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Asignaturas vinculadas a Redes Académicas</td>
                  <td class="campo_x"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td>Existe sobre la media nacional posicionamiento aceptable del PE frente a otros similares en el país</td>
                  <td class="campo_y"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>Cuenta con evidencia de su posicionamiento sobre otros PE similares</td>
                  <td class="campo_z"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>Se relaciona el contenido tematico de la asignatura con el perfil de egreso
(Si ó No)</td>
                  <td class="campo_aa"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Derechos humanos</td>
                  <td class="campo_ab"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a la Equidad de Género</td>
                  <td class="campo_ac"><?php echo $tab['campo_ac'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas al Desarrollo sustentable</td>
                  <td class="campo_ad"><?php echo $tab['campo_ad'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a la Superación integral-social comunitario</td>
                  <td class="campo_ae"><?php echo $tab['campo_ae'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Valores Etícos</td>
                  <td class="campo_af"><?php echo $tab['campo_af'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Valores Sociales</td>
                  <td class="campo_ag"><?php echo $tab['campo_ag'] ?></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los  Formación Política Ideológica</td>
                  <td class="campo_ah"><?php echo $tab['campo_ah'] ?></td>
              </tr>
              <tr>
                  <td>Uso de Nuevas Tecnologías para el desarrollo social (Mencionar)</td>
                  <td class="campo_ai"><?php echo $tab['campo_ai'] ?></td>
              </tr>
              <tr>
                  <td>Número de programas institucionales en los que participan Estudiantes</td>
                  <td class="campo_aj"><?php echo $tab['campo_aj'] ?></td>
              </tr>
              <tr>
                  <td>Número de programas institucionales en los que participan Profesores</td>
                  <td class="campo_ak"><?php echo $tab['campo_ak'] ?></td>
              </tr>
              <tr>
                  <td>Otros</td>
                  <td class="campo_al"><?php echo $tab['campo_al'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r6_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de reuniones colegiadas para evaluación y seguimiento de metodologías de aprendizaje </td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de reuniones interdiciplenarias de  profesores que participan en el PE </td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores del PE que participan en al menos un evento de actualización disciplinar</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Total de profesores que participan en el PE como docentes </td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores evaluados por pares academicos en su actividad docente</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores evaluados por estudiantes al final de cada ciclo escolar</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con resultados de excelencia o sobresalientes en la evaluación de estudaintes por ciclo escolar</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores con niveles bajos en los  resultados de la evaluación por estudiantes  por ciclo escolar</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r7_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de aulas asignadas al PE</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Dimensiones de las aulas que atienden al PE</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de superficie (mts) construidos por aula</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Total de superficie (mts) en áreas verdes</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Total de superficie (mts) en áreas deportivas</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Total de sanitarios  </td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Total de instalaciones destinadas a servicio de alimentos</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) del Centro de Laboratorio de Computo y/o Tecnologías para el aprendizaje</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de software especializado que atiende al PE</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Total de licencias de los software especializados que atienden al PE</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Cantidad estimada de equipo especializado de laboratorio</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Número de Laboratorios</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Laboratorios</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Número de Talleres</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Talleres</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de Auditorios (Salas de exposición, Eventos Culturales y Eventos Académicos)</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Auditorios</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>Número de Salas de estudio para Profesores</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por sala</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>Número de salas de estudio para Estudiantes</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por sala</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>Número de cubículos para Profesores</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por cubículo</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Total de computadoras que atienden al PE</td>
                  <td class="campo_x"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Número de Volumenes en biblioteca</td>
                  <td class="campo_y"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Númeo de Titulos en biblioteca</td>
                  <td class="campo_z"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td>Número de Colecciones en biblioteca</td>
                  <td class="campo_aa"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>Número de Suscripciones a revistas Científicas en biblioteca</td>
                  <td class="campo_ab"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>Número de Tesis existentes en biblioteca realizadas por Egresados del PE</td>
                  <td class="campo_ac"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>Estadisticas anuales de visitas a la biblioteca por parte de Estudiantes del PE</td>
                  <td class="campo_ad"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>Estadisticas anuales de visitas a la biblioteca por parte de Profesores del PE</td>
                  <td class="campo_ae"><?php echo $tab['campo_ac'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r8_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Promedio anual de colocación al sector productivo de egresados del pe, desde la bolsa de trabajo de la institución</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Gobierno</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Empresa</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Instituciones educativas y de investigación</td>
                  <td class="campo_x"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Organizaciones no gubernamentales</td>
                  <td class="campo_y"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Gobierno</td>
                  <td class="campo_z"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Empresa</td>
                  <td class="campo_aa"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Instituciones educativas y de investigación</td>
                  <td class="campo_ab"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Organizaciones no gubernamentales</td>
                  <td class="campo_ac"><?php echo $tab['campo_ac'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Gobierno</td>
                  <td class="campo_ad"><?php echo $tab['campo_ad'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Empresa</td>
                  <td class="campo_ae"><?php echo $tab['campo_ae'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Instituciones educativas y de investigación</td>
                  <td class="campo_af"><?php echo $tab['campo_af'] ?></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Organizaciones no gubernamentales</td>
                  <td class="campo_ag"><?php echo $tab['campo_ag'] ?></td>
              </tr>
              <tr>
                  <td>Numero de actividades de difusión del PE por los distintos medios de comunicación</td>
                  <td class="campo_ah"><?php echo $tab['campo_ah'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos culturales, deportivos, artísticos, académicos: congresos, seminarios, cursos, etc. Nacionales e internacionales del PE </td>
                  <td class="campo_ai"><?php echo $tab['campo_ai'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r9_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td>Número de convenios  de cooperación internacional vigentes</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de asignaturas del plan de estudios del PE  con contenido de internacionalización</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de asignaturas del PE con dinámica de trabajo en redes académicas</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que participan en estancias académicas en otras universidades</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes de origen extranjero realizando estancia  de intercambio en el PE</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores del pe que participan en redes académicas</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores de origen extranjero realizando estancia de intercambio en el PE</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de proyectos de investigación interinstitucional vinculados al PE</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Numero de productos como resultado de proyectos de investigación interinstitucional vinculados al PE (libros, artículos, patentes, memorias, reportes, etc.)</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de proyectos de extensión interinstitucionales vinculados al PE</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales organizados por el PE   o la institución por año</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales en el que participan estudiantes del PE</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales en el que participan profesores del PE</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos virtuales internacionales del PE (video-conferencias, cursos virtuales, etc.)</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Numero de tutores, directores de tesis y directores de prácticas profesionales a extranjeros que apoyan al PE</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de profesores del PE que realizan tutorías, dirección de tesis, dirección de prácticas profesionales a estudiantes de otras universidades</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r10_1_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Sitios Virtuales de Acceso a la Información</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad  que regula la actividad laboral  y académica de los Profesores</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la actividad académica de los Estudiantes</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula al Plan de Estudios</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad  o políticas que regulan la Formación Integral</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la planeación, programación, presupuestación y ejercicio de los Recursos Financieros</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula el uso, crecimiento, mantenimiento  de Instalaciones, equipamiento y  tecnologías </td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la Investigación y Extensión</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la actividad del Personal Administrativo</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la Internacionalización</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r10_2_1_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Sitios Virtuales de Acceso a la Información</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Numero de aspirantes</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de aspirantes cuya residencia sea localizada a menos de 50 kms de la Universidad  </td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de aspirantes cuya residencia sea fuera del estado o departamento </td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de aspirantes extranjeros</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos cuya residencia sea localizada a menos de 50 kms de la Universidad</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos  cuya residencia sea localizada a mas de 50 kms de la Universidad </td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos cuya residencia sea fuera del estado o departamento</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos extranjeros</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes becados</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos de zonas marginadas o susceptibles</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td>Promedio de calificaciones según el rango o escala de medición</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Número de egresados</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Número de titulados con egreso no mayor a 6 meses</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de egresados titulados que ejercen su profesión, insertados en el mercado laboral </td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r10_2_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
               <tr>
                  <td>Año</td>
                  <td class="anio"><?php echo $tab['anio'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Normatividad</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_f"><?php echo $tab['campo_f'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Profesorado</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_g"><?php echo $tab['campo_g'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_h"><?php echo $tab['campo_h'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_i"><?php echo $tab['campo_i'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_j"><?php echo $tab['campo_j'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_k"><?php echo $tab['campo_k'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_l"><?php echo $tab['campo_l'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Estudiantes</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_m"><?php echo $tab['campo_m'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_n"><?php echo $tab['campo_n'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_o"><?php echo $tab['campo_o'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_p"><?php echo $tab['campo_p'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_q"><?php echo $tab['campo_q'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_r"><?php echo $tab['campo_r'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Plan de estudios</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_s"><?php echo $tab['campo_s'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_t"><?php echo $tab['campo_t'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_u"><?php echo $tab['campo_u'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_w"><?php echo $tab['campo_w'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_v"><?php echo $tab['campo_v'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_x"><?php echo $tab['campo_x'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Formación integral</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_y"><?php echo $tab['campo_y'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_z"><?php echo $tab['campo_z'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_aa"><?php echo $tab['campo_aa'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_ab"><?php echo $tab['campo_ab'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ac"><?php echo $tab['campo_ac'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_ad"><?php echo $tab['campo_ad'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Recursos Financieros</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_ae"><?php echo $tab['campo_ae'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_af"><?php echo $tab['campo_af'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_ag"><?php echo $tab['campo_ag'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_ah"><?php echo $tab['campo_ah'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ai"><?php echo $tab['campo_ai'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_aj"><?php echo $tab['campo_aj'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Instalaciones</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_ak"><?php echo $tab['campo_ak'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_al"><?php echo $tab['campo_al'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_am"><?php echo $tab['campo_am'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_an"><?php echo $tab['campo_an'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ao"><?php echo $tab['campo_ao'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_ap"><?php echo $tab['campo_ap'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Investigación y Extensión</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_aq"><?php echo $tab['campo_aq'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_ar"><?php echo $tab['campo_ar'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_as"><?php echo $tab['campo_as'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_at"><?php echo $tab['campo_at'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_au"><?php echo $tab['campo_au'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_av"><?php echo $tab['campo_av'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Personal Administrativo</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_aw"><?php echo $tab['campo_aw'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_ax"><?php echo $tab['campo_ax'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_ay"><?php echo $tab['campo_ay'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_az"><?php echo $tab['campo_az'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ba"><?php echo $tab['campo_ba'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bb"><?php echo $tab['campo_bb'] ?></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Internacionalización</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_bc"><?php echo $tab['campo_bc'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_bd"><?php echo $tab['campo_bd'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_be"><?php echo $tab['campo_be'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_bf"><?php echo $tab['campo_bf'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_bg"><?php echo $tab['campo_bg'] ?></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bh"><?php echo $tab['campo_bh'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r10_2_2_2_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Normatividad</td>
                  <td class="campo_bi"><?php echo $tab['campo_bi'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Profesorado</td>
                  <td class="campo_bj"><?php echo $tab['campo_bj'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Estudiantes</td>
                  <td class="campo_bk"><?php echo $tab['campo_bk'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Plan de Estudios</td>
                  <td class="campo_bl"><?php echo $tab['campo_bl'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Formación Integral</td>
                  <td class="campo_bm"><?php echo $tab['campo_bm'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Recursos Financieros</td>
                  <td class="campo_bn"><?php echo $tab['campo_bn'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Instalaciones</td>
                  <td class="campo_bo"><?php echo $tab['campo_bo'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Investigación y Extensión</td>
                  <td class="campo_bp"><?php echo $tab['campo_bp'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bq"><?php echo $tab['campo_bq'] ?></td>
              </tr>
              <tr>
                  <td>Breve Descripción de la estratégia utilizada para la asignación de recursos financieros - Internacionalización</td>
                  <td class="campo_br"><?php echo $tab['campo_br'] ?></td>
              </tr>

          </table>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r10_3_tpl hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td colspan="2" style="text-align: right">
                      <a href="#" class="btn btn-primary btn-xs editar-tabla-datos">Editar</a> <a href="#" class="btn btn-danger btn-xs eliminar-tabla-datos">Eliminar</a>
                  </td>
              </tr>
              <tr>
                  <td>Año</td>
                  <td class="campo_a"><?php echo $tab['campo_a'] ?></td>
              </tr>
              <tr>
                  <td>Numero de metas trazadas  del plan anual de desarrollo del PE por año</td>
                  <td class="campo_b"><?php echo $tab['campo_b'] ?></td>
              </tr>
              <tr>
                  <td>Numero de metas cumplidas  del plan anual de desarrollo del pe por año</td>
                  <td class="campo_c"><?php echo $tab['campo_c'] ?></td>
              </tr>
              <tr>
                  <td>Total de  áreas o procesos administrativos que atienden  al PE en sus distintos rubros</td>
                  <td class="campo_d"><?php echo $tab['campo_d'] ?></td>
              </tr>
              <tr>
                  <td>Total de  áreas o procesos administrativos reconocidos por su calidad que atiende al PE en sus distintos rubros</td>
                  <td class="campo_e"><?php echo $tab['campo_e'] ?></td>
              </tr>
          </table>
          </div>
        </div>
    </div>


  <div class="tabla_estadistica_r1_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de egresados </td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Egresados con reconocimiento nacional o internacional </td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Egresados ejerciendo actividades en su ambito formativo en el extranjero</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Egresados ejerciendo actividades en su ambito formativo en el pais</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de instituciones que opinan de la formacion siendo empleadoras o con vínculos</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de instituciones que opinan favorablemente a la formación</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de egresados que son encuestados en referencia a su formación</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de egresados que opinan favorablemente a su formación</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de instituciones de la disciplina o colegios especializados que opinan de la formación</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de instituciones de la disciplina o colegios especializados que opinan favorablemente a la formación</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores que opinan de la formación</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores que opinan a favor de la formación</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que opinan de su formación</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que opinan a favor de su formación</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r2_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
             <tr>
                  <td>Profesores que participan en el PE</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de profesores que realizan investigación</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Artículos publicados </td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Capítulos de libros </td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Desarrollos tecnológicos</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Certificaciones por consejos externos</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Distinciones</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Divulgación y difusión</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Docencia</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Estancias de investigacion</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Redes de investigación</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Libros</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Grados académicos</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Participación en congresos</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Patentes</td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Proyectos de investigación</td>
                  <td class="campo_p"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Reportes técnicos</td>
                  <td class="campo_q"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Reseñas</td>
                  <td class="campo_r"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Tesis dirigidas</td>
                  <td class="campo_s"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Idiomas que domina</td>
                  <td class="campo_t"><input type="text" class="form-control"></td>
              </tr>
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
  <div class="tabla_estadistica_r3_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table no-border" style="text-align: right;">
             <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de aspirantes</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de aspirantes cuya residencia sea localizada a menos de 50 kms de la Universidad  </td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de aspirantes cuya residencia sea fuera del estado o departamento </td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de aspirantes extranjeros</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos cuya residencia sea localizada a menos de 50 kms de la Universidad</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos  cuya residencia sea localizada a mas de 50 kms de la Universidad </td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos cuya residencia sea fuera del estado o departamento</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos extranjeros</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes becados</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes admitidos de zonas marginadas o susceptibles</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Promedio de calificaciones según el rango o escala de medición</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de egresados</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de titulados con egreso no mayor a 6 meses</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de egresados titulados que ejercen su profesión, insertados en el mercado laboral </td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r4_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
          </table>
        </div>
    </div>

    <div class="form-profesores">
        <div class="panel panel-default panel-profesor">
            <div class="panel-body">
              <table class="table">
                  <tr>
                      <td>Nombre Del Profesor que participa en el PE</td>
                      <td class="campo_a"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Grado Académico Actual y área del conocimiento en que fue obtenido</td>
                      <td class="campo_b"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Categoría Académica o Laboral, indicando horas de trabajo académico por semana </td>
                      <td class="campo_c"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Nombre de Asignaturas o materias que imparte en el PE</td>
                      <td class="campo_d"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de proyectos de Investigación que desarrollan y línea de generación y aplicación del conocimiento señalando el nombre de los principales</td>
                      <td class="campo_e"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de la Actividad de Vinculación y nombre de las principales</td>
                      <td class="campo_f"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de la Actividad de Difusión y nombre de las principales</td>
                      <td class="campo_g"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades de Internacionalización y nombre de las principales</td>
                      <td class="campo_h"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Estudiantes Tutorados por profesor</td>
                      <td class="campo_i"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades de Superación Académica y nombre de las principales</td>
                      <td class="campo_j"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades Colegiadas ó Redes  (Locales, Nacionales e Internacionales) y nombre de las principales</td>
                      <td class="campo_k"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Estudiantes Dirigidos en Actividades de Prácticas Profesionales y Estancias académicas</td>
                      <td class="campo_l"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades Extracurriculares y nombre de las principales</td>
                      <td class="campo_m"><input type="text" class="form-control"></td>
                  </tr>
              </table>

              </div>
            </div>

        </div>

        <div class="panel panel-default">
          <div class="panel-body">
                <div class="form-controls pull-right">
                    <a href="#" class="btn btn-default agregar-docente">Agregar docente</a>
                    <a href="#" class="btn btn-success guardar-data-profesor" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                    <a href="#" class="btn btn-default cancelar">Cancelar</a>
                </div>
          </div>
        </div>

      <div class="hide" id="panel-profesor-tpl">
          <div class="panel panel-default panel-profesor">
            <div class="panel-body">
              <table class="table">
                  <tr>
                      <td>Nombre Del Profesor que participa en el PE</td>
                      <td class="campo_a"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Grado Académico Actual y área del conocimiento en que fue obtenido</td>
                      <td class="campo_b"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Categoría Académica o Laboral, indicando horas de trabajo académico por semana </td>
                      <td class="campo_c"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Nombre de Asignaturas o materias que imparte en el PE</td>
                      <td class="campo_d"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de proyectos de Investigación que desarrollan y línea de generación y aplicación del conocimiento señalando el nombre de los principales</td>
                      <td class="campo_e"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de la Actividad de Vinculación y nombre de las principales</td>
                      <td class="campo_f"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de la Actividad de Difusión y nombre de las principales</td>
                      <td class="campo_g"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades de Internacionalización y nombre de las principales</td>
                      <td class="campo_h"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Estudiantes Tutorados por profesor</td>
                      <td class="campo_i"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades de Superación Académica y nombre de las principales</td>
                      <td class="campo_j"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades Colegiadas ó Redes  (Locales, Nacionales e Internacionales) y nombre de las principales</td>
                      <td class="campo_k"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Estudiantes Dirigidos en Actividades de Prácticas Profesionales y Estancias académicas</td>
                      <td class="campo_l"><input type="text" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Número de Actividades Extracurriculares y nombre de las principales</td>
                      <td class="campo_m"><input type="text" class="form-control"></td>
                  </tr>
              </table>

              </div>
            </div>
      </div>
    </div>

  <div class="tabla_estadistica_r5_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Eje 1</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Eje 2</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Eje 3</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Eje 4</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Eje 5</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Créditos por Asignatura</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Carga horaria semanal por ciclo</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Horas Teoría</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Horas Práctica</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Horas Curriculares</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Horas Extracurriculares</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Asignatura vinculada al entorno internacional (Si ó No)</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Uso de Nuevas Tecnologías de la Información y Comunicación para cada Asignatura(Si ó No)</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Asignatura relacionada al Medio Ambiente
(Si ó No)</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Asignatura relacionada a la equidad de género
(Si ó No)</td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Materias vinculadas a proyectos de investigación que realizan profesores del PE</td>
                  <td class="campo_p"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Ciclos escolares del PE</td>
                  <td class="campo_q"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de asignaturas  promedio en el que esta inscrito el Estudiante por ciclo escolar</td>
                  <td class="campo_r"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de horas obligatorias en prácticas profesionales relacionados al Plan de Estudios</td>
                  <td class="campo_s"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de materias en el que esta inscrito el Estudiante en el primer ciclo escolar</td>
                  <td class="campo_t"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores invitados de universidades extranjeras que imparten asignaturas en el PE</td>
                  <td class="campo_u"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores con actividad externa preponderante que imparte al menos una asignatura en el PE</td>
                  <td class="campo_v"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Asignaturas vinculadas a programas de extensión en el que participan Estudiantes</td>
                  <td class="campo_w"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Asignaturas vinculadas a Redes Académicas</td>
                  <td class="campo_x"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Existe sobre la media nacional posicionamiento aceptable del PE frente a otros similares en el país</td>
                  <td class="campo_y"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Cuenta con evidencia de su posicionamiento sobre otros PE similares</td>
                  <td class="campo_z"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Se relaciona el contenido tematico de la asignatura con el perfil de egreso
(Si ó No)</td>
                  <td class="campo_aa"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Derechos humanos</td>
                  <td class="campo_ab"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a la Equidad de Género</td>
                  <td class="campo_ac"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas al Desarrollo sustentable</td>
                  <td class="campo_ad"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a la Superación integral-social comunitario</td>
                  <td class="campo_ae"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Valores Etícos</td>
                  <td class="campo_af"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los Valores Sociales</td>
                  <td class="campo_ag"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Actividades vinculadas a los  Formación Política Ideológica</td>
                  <td class="campo_ah"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Uso de Nuevas Tecnologías para el desarrollo social (Mencionar)</td>
                  <td class="campo_ai"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de programas institucionales en los que participan Estudiantes</td>
                  <td class="campo_aj"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de programas institucionales en los que participan Profesores</td>
                  <td class="campo_ak"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Otros</td>
                  <td class="campo_al"><input type="text" class="form-control"></td>
              </tr>
          </table>
            <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r6_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de reuniones colegiadas para evaluación y seguimiento de metodologías de aprendizaje </td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de reuniones interdiciplenarias de  profesores que participan en el PE </td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores del PE que participan en al menos un evento de actualización disciplinar</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de profesores que participan en el PE como docentes </td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores evaluados por pares academicos en su actividad docente</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores evaluados por estudiantes al final de cada ciclo escolar</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores con resultados de excelencia o sobresalientes en la evaluación de estudaintes por ciclo escolar</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores con niveles bajos en los  resultados de la evaluación por estudiantes  por ciclo escolar</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r7_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de aulas asignadas al PE</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Dimensiones de las aulas que atienden al PE</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de superficie (mts) construidos por aula</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de superficie (mts) en áreas verdes</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de superficie (mts) en áreas deportivas</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de sanitarios  </td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de instalaciones destinadas a servicio de alimentos</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) del Centro de Laboratorio de Computo y/o Tecnologías para el aprendizaje</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de software especializado que atiende al PE</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de licencias de los software especializados que atienden al PE</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Cantidad estimada de equipo especializado de laboratorio</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Laboratorios</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Laboratorios</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Talleres</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Talleres</td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Auditorios (Salas de exposición, Eventos Culturales y Eventos Académicos)</td>
                  <td class="campo_p"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) de los Auditorios</td>
                  <td class="campo_q"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Salas de estudio para Profesores</td>
                  <td class="campo_r"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por sala</td>
                  <td class="campo_s"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de salas de estudio para Estudiantes</td>
                  <td class="campo_t"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por sala</td>
                  <td class="campo_u"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de cubículos para Profesores</td>
                  <td class="campo_v"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Superficie (mts) por cubículo</td>
                  <td class="campo_w"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de computadoras que atienden al PE</td>
                  <td class="campo_x"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Volumenes en biblioteca</td>
                  <td class="campo_y"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Númeo de Titulos en biblioteca</td>
                  <td class="campo_z"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Colecciones en biblioteca</td>
                  <td class="campo_aa"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Suscripciones a revistas Científicas en biblioteca</td>
                  <td class="campo_ab"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de Tesis existentes en biblioteca realizadas por Egresados del PE</td>
                  <td class="campo_ac"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Estadisticas anuales de visitas a la biblioteca por parte de Estudiantes del PE</td>
                  <td class="campo_ad"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Estadisticas anuales de visitas a la biblioteca por parte de Profesores del PE</td>
                  <td class="campo_ae"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r8_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE CONVENIOS POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PROFESORES QUE PARTICIPAN EN ACTIVIDADES DE VINCULACION POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN PRACTICAS PROFESIONALES POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_p"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Gobierno</td>
                  <td class="campo_q"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Empresa</td>
                  <td class="campo_r"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Instituciones educativas y de investigación</td>
                  <td class="campo_s"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE ESTUDIANTES QUE REALIZAN SERVICIO SOCIAL POR SECTOR SOCIAL - Organizaciones no gubernamentales</td>
                  <td class="campo_t"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Promedio anual de colocación al sector productivo de egresados del pe, desde la bolsa de trabajo de la institución</td>
                  <td class="campo_u"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Gobierno</td>
                  <td class="campo_v"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Empresa</td>
                  <td class="campo_w"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Instituciones educativas y de investigación</td>
                  <td class="campo_x"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS POR SECTOR SOCIAL QUE PARTICIPAN EN LA ACTUALIZACION DEL PLAN DE ESTUDIOS  - Organizaciones no gubernamentales</td>
                  <td class="campo_y"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Gobierno</td>
                  <td class="campo_z"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Empresa</td>
                  <td class="campo_aa"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Instituciones educativas y de investigación</td>
                  <td class="campo_ab"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE PERSONAS DE LOS  SECTORES  SOCIALES QUE PARTICIPAN EN PROYECTOS DE  INVESTIGACION VINCULADA AL PE  - Organizaciones no gubernamentales</td>
                  <td class="campo_ac"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Gobierno</td>
                  <td class="campo_ad"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Empresa</td>
                  <td class="campo_ae"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Instituciones educativas y de investigación</td>
                  <td class="campo_af"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>NUMERO DE SERVICIOS PROFESIONALES EN EL QUE PARTICIPAN PROFESORES Y ESTUDIANTES DEL PE POR SECTOR  - Organizaciones no gubernamentales</td>
                  <td class="campo_ag"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de actividades de difusión del PE por los distintos medios de comunicación</td>
                  <td class="campo_ah"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de eventos culturales, deportivos, artísticos, académicos: congresos, seminarios, cursos, etc. Nacionales e internacionales del PE </td>
                  <td class="campo_ai"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r9_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
             <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de convenios  de cooperación internacional vigentes</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de asignaturas del plan de estudios del PE  con contenido de internacionalización</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de asignaturas del PE con dinámica de trabajo en redes académicas</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes que participan en estancias académicas en otras universidades</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de estudiantes de origen extranjero realizando estancia  de intercambio en el PE</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores del pe que participan en redes académicas</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores de origen extranjero realizando estancia de intercambio en el PE</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de proyectos de investigación interinstitucional vinculados al PE</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de productos como resultado de proyectos de investigación interinstitucional vinculados al PE (libros, artículos, patentes, memorias, reportes, etc.)</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de proyectos de extensión interinstitucionales vinculados al PE</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales organizados por el PE   o la institución por año</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales en el que participan estudiantes del PE</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos internacionales en el que participan profesores del PE</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de eventos académicos virtuales internacionales del PE (video-conferencias, cursos virtuales, etc.)</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de tutores, directores de tesis y directores de prácticas profesionales a extranjeros que apoyan al PE</td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de profesores del PE que realizan tutorías, dirección de tesis, dirección de prácticas profesionales a estudiantes de otras universidades</td>
                  <td class="campo_p"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>

  <div class="tabla_estadistica_r10_1_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">

              <tr>
                  <td>Sitios Virtuales de Acceso a la Información</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad  que regula la actividad laboral  y académica de los Profesores</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la actividad académica de los Estudiantes</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula al Plan de Estudios</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad  o políticas que regulan la Formación Integral</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la planeación, programación, presupuestación y ejercicio de los Recursos Financieros</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula el uso, crecimiento, mantenimiento  de Instalaciones, equipamiento y  tecnologías </td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la Investigación y Extensión</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la actividad del Personal Administrativo</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Nombre de la Normatividad que regula la Internacionalización</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>


 <div class="tabla_estadistica_r10_2_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
              <tr>
                  <td>¿Existe un plan anual estratégico de planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinados al PE?</td>
                  <td>
                      <select class="form-control pregunta_inicial">
                          <option value="0">Seleccione una opción...</option>
                          <option value="1">Si</option>
                          <option value="2">No</option>
                      </select>
                  </td>
              </tr>
          </table>
          </div>
        </div>
    </div>

     <div class="tabla_estadistica_r10_2_1_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
            <tr>
                  <td>Año</td>
                  <td class="anio"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2"><b>Normatividad</b></td>
              </tr>
              
            
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_f"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Profesorado</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_g"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_h"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_i"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_j"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_k"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_l"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Estudiantes</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_m"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_n"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_o"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_p"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_q"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_r"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Plan de estudios</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_s"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_t"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_u"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_w"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_v"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_x"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Formación integral</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_y"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_z"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_aa"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_ab"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ac"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_ad"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Recursos Financieros</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_ae"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_af"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_ag"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_ah"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ai"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_aj"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Instalaciones</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_ak"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_al"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_am"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_an"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ao"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_ap"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2" style="text-align: right">
                      <b>Investigación y Extensión</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_aq"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_ar"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_as"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_at"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_au"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_av"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Personal Administrativo</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_aw"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_ax"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_ay"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_az"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_ba"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bb"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td colspan="2">
                      <b>Internacionalización</b>
                  </td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Profesores</td>
                  <td class="campo_bc"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Estudiantes</td>
                  <td class="campo_bd"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes en la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros destinado al PE por rubro - Personal Administrativo</td>
                  <td class="campo_be"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Profesores</td>
                  <td class="campo_bf"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Estudiantes</td>
                  <td class="campo_bg"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Número de participantes satisfechos en la asignación de recursos de acuerdo a la planeación, programación, presupuestación, ejecución y evaluación de recursos financieros - Personal Administrativo</td>
                  <td class="campo_bh"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default atras">Atrás</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
</div>



  <div class="tabla_estadistica_r10_3_tpl_form hide">
    <div class="panel panel-default">
        <div class="panel-body">
          <table class="table">
            <tr>
                  <td>Año</td>
                  <td class="campo_a"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de metas trazadas  del plan anual de desarrollo del PE por año</td>
                  <td class="campo_b"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Numero de metas cumplidas  del plan anual de desarrollo del pe por año</td>
                  <td class="campo_c"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de  áreas o procesos administrativos que atienden  al PE en sus distintos rubros</td>
                  <td class="campo_d"><input type="text" class="form-control"></td>
              </tr>
              <tr>
                  <td>Total de  áreas o procesos administrativos reconocidos por su calidad que atiende al PE en sus distintos rubros</td>
                  <td class="campo_e"><input type="text" class="form-control"></td>
              </tr>
          </table>
             <div class="form-controls pull-right">
                <a href="#" class="btn btn-success guardar-data" data-tipo="<?php echo $rubro['num_orden']?>" data-rubro="<?php echo $rubro['id']?>">Guardar</a>
                <a href="#" class="btn btn-default cancelar">Cancelar</a>
            </div>
          </div>
        </div>
    </div>
    <?php  break; ?>
 <?php } ?>
</div>
