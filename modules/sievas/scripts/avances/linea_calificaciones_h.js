var linea_calificaciones = {};
var socket = io('http://sievas.herokuapp.com');

linea_calificaciones.init = function(){
    
    var ev_id = $('#ev_id').val();
    socket.on('actualizar_grafica_1', function (data) {
        console.log("data");
        console.log(data);
      if(ev_id > 0 && data.evaluacion == ev_id){
          linea_calificaciones.update();
      }
    });
    linea_calificaciones.update();
}

linea_calificaciones.update = function(){
    $.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_comparativa_items', function (csv) {
        
        var options = {
            chart: {
                type: 'line'
            },
            title: {
                text: ' '
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
                categories: csv.labels,
                labels: {
                    staggerLines: 5
                },
                gridLineWidth: 1
            },
            yAxis: {
                title: {
                    text: 'Nivel de calidad'
                },
                gridLineWidth: 1
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false,
                    marker: {
                        radius: 3
                    }
                    
                }
            },
            series: [{
                name: 'Interna',
                data: csv.einternaact
            }, {
                name: 'Externa',
                data: csv.eexternaact
            }]
        };
        
        Highcharts.chart('container', options);
        });
}




$(linea_calificaciones.init);


