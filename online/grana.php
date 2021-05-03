<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >

<head>

<link rel="stylesheet" href="./css/style.css" type="text/css" media="screen,projection" />

<link rel="shortcut icon" href="images/grana.ico" type="image/x-icon" /> 
<title>ISTEC - GRANA</title>

<script type='text/javascript' src='./js/cufon-yui.js'></script>
<script type="text/javascript" src="./js/Futura_Bk_BT_400.font.js"></script>
<script type="text/javascript" src="./js/jquery.js"></script>

<meta charset="UTF-8">
</head>

<body>

<div id="container">
	<div class="main">
		<div class="content-border">

<div id="header">
    <div class="main-2">
        <div class="logoheader"><a href="./index.php" id="logo" title="GRANA"></a></div>
        <div id="navigation"></div>
    </div>
</div>

<div class="wrapper pages">

<ul id="filterNav" class="clearfix">

		<li><h3>GRANA</h3></li>
		<li class="no-border"><span class="description">Pertinencia social en la formación</span></li>
</ul>

</div>
	
        <!-- end header -->
    <div id="content">
            <div class="wrapper">
			
<div id="maincolbck" style="width: 100%;">
	<div id="maincolumn" style="width: 100%;">

<p>Es importante medir el nivel de calidad de cualquier programa educativo que ofrece una institución de educación
superior considerando los entornos ya imprescindibles de la internacionalización en todo su contexto.
GRANA es un vinculo facilitador para que las instituciones de educación superior propicien elevar los niveles
de calidad y pertinencia de los programas educativos que ofrecen y se vean reflejado en la competitividad y
capacidad académica de sus egresados. GRANA apoya la iniciativa de la Ingeniería para las Américas, que
surgió como inquietud de las situaciones internacionales que se han suscitado desde el inicio del siglo, y aporta
un instrumento de evaluación para que se propicie la movilidad profesional, la formación de redes académicas
de investigación y desarrollo, la homologación de planes de estudios, los niveles de inversión y por ende el
crecimiento económico de los países latinoamericanos. (Ingenierías para las Américas 2005). Además, sirve
como un gateway para la evaluación futura entre diferentes regiones, como por ejemplo, tanto profesionales
latinoamericanos como profesionales europeos.
<br/><br/>
Así también, GRANA al poseer un instrumento de evaluación en línea, los tiempos de evaluación y etapas de
revisión y las estancias del evaluador en una universidad serán menores porque el manual está ubicado en un lugar
accesible a todos los involucrados en el proceso de evaluación de un programa educativo. Además, se pueden
realizar modificaciones sin necesidad de perder información en el sistema y adecuar el instrumento para que se
actualice.
<br/><br/>
Por otro lado, los niveles de certificación permitirán estimular a las instituciones de educación superior y a sus
egresados que conllevan el reconocimiento internacional a la calidad de ambas entes y el logro de colocarse de
manera automática en el rankin mundial y en beneficio de la sociedad y de la región latinoamericana.
<br/><br/>
La función de GRANA se basa en 5 áreas para atender procesos de evaluación y certificación de programas
educativos de educación superior, es importante resaltar que el proceso de evaluación de un programa educativo
inicia con la evaluación interna -por parte del responsable(s) del programa educativo-, posteriormente lo revisan
pares académicos del programa como parte de la evaluación externa, luego la revisan los miembros de diferentes
sectores por disciplina y por último, pasa a un consejo ejecutivo para dictaminar el nivel de evaluación del
programa educativo.
<br/><br/>
<strong>El área de formación y capacitación de evaluadores internos y externos</strong>, se realiza a través de un curso
(presencial o en formato blended) en el ámbito de la calidad educativa donde se tratan los temas del origen de
los procesos de acreditación en el mundo en programas educativos de las instituciones de educación superior,
sus tendencias e impacto en la formación profesional; Tendencia Internacional de Evaluación de la Educación
Superior; Análisis de Organismos Acreditadores; Análisis y Discusión: Organismos Acreditadores; Indicadores de
Calidad GRANA; Sistema de evaluación en línea GRANA; Escalas de Calidad; Plan de Mejoramiento Continuo.
Esta área permite conocer los referentes y genética de indicadores de calidad cualitativa de GRANA.
<br/><br/>
<strong>El área de actualización permanente de indicadores de calidad</strong>, consiste en analizar las propuestas de mejora
de los involucrados en los procesos de capacitación, formación y evaluación, así como también de los resultados
obtenidos en los distintos congresos mundiales relacionados a la calidad en la educación superior para ser
integrados al instrumento de evaluación cualitativa de GRANA.
<br/><br/>
<strong>El área de procesos y procedimientos</strong>, consiste en atender las necesidades del capacitado durante su proceso
formativo y las distintas fases de los procesos de evaluación-certificación y planes de mejoramiento continuo.
<br/><br/>
<strong>El área de sistema de evaluación en línea</strong>, consiste en utilizar una plataforma virtual capaz de apoyar al
evaluador interno para integrar en línea la autoevaluación de un programa educativo con instrucciones precisas
tales como: tutorial de manejo del sistema, significado y referencias bibliográfica y científicas de cada ítem a
evaluar, escala de juicio de valor automático, y un indica metro que permite monitorear permanentemente el nivel
de calidad en tiempo real de la autoevaluación parcial o total.
<br/><br/>
<strong>El área de atención para el seguimiento de programas de mejoramiento continuo</strong>, consiste en monitorear
permanentemente los niveles de mejora que se van desarrollando en los programas educativos evaluados para
retroalimentar en la toma de decisiones de la institución en pro de mejora del Programa Educativo.</p>

</div>
	</div>
		</div>
			</div>

<div class="footer">
	<div id="copyright"></div>
</div>

		</div>
	</div>
</div>


<script type="text/javascript" charset="utf-8">

jQuery(document).ready( function () {
    jQuery.ajax({
        url: "./menu/menu.php",
        success: function(str){
			//$params = { 'actual' : 'index.php' };
            jQuery("#navigation").html(str);
        }
    });   

    jQuery.ajax({
        url: "./menu/footer.html",
        type: 'post',
        success: function(str){
            jQuery("#copyright").html(str);
        }    });
    
    /*jQuery.ajax({
        url: "./menu/menu2.html",
        type: 'post',
        success: function(str){
            jQuery("current-menu-item").html(str);
        }
    });*/
});
        
</script>

<script type="text/javascript" src="./js/custom.js"></script>
<script type="text/javascript">Cufon.now();</script>

</body>
</html>