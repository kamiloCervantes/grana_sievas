var evaluaciones_config = {};


evaluaciones_config.init = function(){
    $('#guardar-form').on('click', function(e){
        e.preventDefault();
        var tablero_control = $('#tablero_control').prop('checked') ? 1: 0;
        var ev_id = $('#ev_id').val();
        console.log(tablero_control);
        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=guardar_configuracion', {
            tablero_control : tablero_control,
            ev_id : ev_id
        });
        
        jqxhr.done(function(data){
            $('#alert_config .alert').removeClass('alert-success');
            $('#alert_config .alert').removeClass('alert-danger');
            $('#alert_config .alert').addClass('alert-success');
            $('#alert_config .alert p').html('Se ha guardado la configuración correctamente');
            $('#alert_config').removeClass('hide');           
        });
        
        jqxhr.fail(function(err){
            $('#alert_config .alert').removeClass('alert-success');
            $('#alert_config .alert').removeClass('alert-danger');
            $('#alert_config .alert').addClass('alert-danger');
            $('#alert_config .alert p').html(err.responseText);
            $('#alert_config').removeClass('hide'); 
        });
    });
}

$(evaluaciones_config.init);

