<style>
    td {
        padding: 5px;
    }
    
    .controls a{
        color: #000;
        font-size: 12px;
        padding: 10px;
    }

    .comp_header{
        text-align: center;
    }

    .comp_header img{
        width: 200px;
    }

    h3.title{
        font-weight: bold;
    }

    p.grana-title{
        color: #a60000;
        font-weight:bold;
        font-size: 20px;
    }
</style>
<div class="controls" style="background: #ccc; margin-top: 50px; padding: 5px">
    <form id="generate_word" method="post" action="index.php?mod=sievas&controlador=avances&accion=word_gen">
        <input type="hidden" id="word_data" name="word_data">
    </form>
    <a href="#" class="guardarword">Guardar como Word</a>
    <a href="index.php?mod=sievas&controlador=evaluar&accion=evaluacion_complementaria">Cancelar</a>
</div>
<div class="word_version" style="border: 1px solid #ccc; background: #fff; padding: 60px;">
<div class="comp_header" style="text-align:center">
    <img src="public/img/logo2.png" style="width: 200px">
    <p class="grana-title" style="color: #a60000;font-weight:bold;font-size: 20px;">Generation of Resources for Accreditation in Nations of the America</p>
</div>
<br/>
<h3 class="title" style="font-weight: bold;">Evaluación complementaria del <?php echo Auth::info_usuario('evaluado_nombre') != null ? Auth::info_usuario('evaluado_nombre') : ''; ?></h3>
<br/>
<?php echo $ev_comp['evaluacion'] != null ? $ev_comp['evaluacion'] : ''; ?>

    
</div>