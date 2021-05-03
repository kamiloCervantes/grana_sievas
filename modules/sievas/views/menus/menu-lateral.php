
<?php 
    switch(Auth::info_usuario('rol')){
    case "1": ?>
    <div id="arbol-menu" style="border-radius: 3px;padding: 10px; font-size:12px">
        <ul>
            <li>Configuración de institucion
                <ul>
                    <li><a href="index.php?mod=academico&controlador=aca_areas">Áreas</a></li>  
                    <li><a href="index.php?mod=academico&controlador=aca_asignaturas">Asignaturas</a></li>
                    <li><a href="index.php?mod=academico&controlador=aca_cursos">Cursos</a></li>
                    <li><a href="index.php?mod=academico&controlador=aca_grados">Grados</a></li>
                    <li><a href="index.php?mod=academico&controlador=gen_unidades">Unidades físicas de sede</a></li>
                </ul>
            </li>
              
        </ul>
 </div>    
<?php  break;
}?>
