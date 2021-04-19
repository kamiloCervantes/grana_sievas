var tablas_estadisticas_graficas_pr = {};


tablas_estadisticas_graficas_pr.draw = function(){
    var indicadores = $('#i').val();
    var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=indicadores_data&i='+indicadores);
    
    jqxhr.done(function(data){
        data = JSON.parse(data);
        console.log(data);
        
        $.each(data, function(index,value){
            var campos = $.map(value.campos, function(val,idx){
                return parseFloat(val);
            }); 
            Highcharts.chart('cvs'+value.indicador, {
                chart: {
                    type: 'line'
                },
                title: {
                    text: $('<p/>').html(value.nombre_indicador).html()
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: value.anios
                },
                yAxis: {
                    title: {
                        text: 'Valor'
                    },
                    min : 0
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series: [{
                    name: 'Datos indicador',
                    data: campos
                } ]
            });
        });
        
    });
}

tablas_estadisticas_graficas_pr.init = function(){
    var socket = io('http://sievas.herokuapp.com');
    socket.on('actualizar_grafica_1', function(){
        tablas_estadisticas_graficas_pr.draw();
    });
    tablas_estadisticas_graficas_pr.draw();
}

$(tablas_estadisticas_graficas_pr.init);