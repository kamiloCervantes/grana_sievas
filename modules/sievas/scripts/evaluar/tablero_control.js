var tablero_control = {};

tablero_control.init = function(){
    $('.slider').bootstrapSlider({
	formatter: function(value) {
		return 'Current value: ' + value;
	}
    }); 
    
    $('#control td input').on('change', function(e){
        console.log(e);
        var proceso = $(this).data('proceso');
        var etapa = $(this).data('etapa');
        var avance = $(this).val();
        
        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_avance_tablero_control', {
            proceso : proceso,
            etapa : etapa,
            avance : avance
        });
    });
}

$(tablero_control.init);

