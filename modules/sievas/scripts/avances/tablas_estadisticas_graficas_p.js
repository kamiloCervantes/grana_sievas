var tablas_estadisticas_graficas_p = {};


tablas_estadisticas_graficas_p.init = function(){
    $('#filtro').select2({
        placeholder: "Seleccione una opción...",
        minimumInputText: 1        
    });
    
    $('#filtro').on('change', function(){
        $('tbody tr').removeClass('hide');
        $('tbody tr').each(function(index,value){
            if($('#filtro').select2('val') > 0 && $(value).data('tabla') != $('#filtro').select2('val')){
                $(value).toggleClass('hide');
            }
        });
    });
    
    $('#restablecer').on('click', function(e){
        e.preventDefault();
        $('tbody tr').removeClass('hide');
        $('#filtro').select2('val', '0');
    });
    
    $('#graficar').on('click', function(e){
         e.preventDefault();
         var indicadores = $.map($('input[name="indicadores"]:checked'), function(val,idx){
            return $(val).val();
        });
        indicadores = indicadores.join('|');
        console.log(indicadores);
         window.location = 'index.php?mod=sievas&controlador=avances&accion=tabla_graficas_estadisticas&i='+indicadores;
    });
}

$(tablas_estadisticas_graficas_p.init);