/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var main = {};

main.init_flot_chart = function(){
//    $('#prom_acreditacion').select2({
//        minimumInputText: 1,
//        placeholder: "Seleccione...",
//        ajax:{
//            url:'index.php?mod=sievas&controlador=lmcc&accion=get_options_promedios',
//            dataType: 'json',
//            data: function(term, page){
//                return {
//                    q: term,
//                    page_limit: 10
//                }
//            },
//            results: function(data, page){
//                return {
//                    results: data
//                }
//            }
//        },
//
//        initSelection: function(element, callback) {
//        // the input tag has a value attribute preloaded that points to a preselected movie's id
//        // this function resolves that id attribute to an object that select2 can render
//        // using its formatResult renderer - that way the movie name is shown preselected
//        var id=$(element).val();   
//            if (id!=="") {
//                var url = "index.php?mod=sievas&controlador=evaluar&accion=get_options_promedios_id&id="+id;
//                var jqxhr = $.get(url);
//                jqxhr.done(function(data){
//                    callback(data[0]);
//                });
//            }
//        },
//
//        formatResult: function(d){
//            return d.opcion;
//        },
//
//        formatSelection: function(d){
//            return d.opcion;
//        },
//
//        dropdownCssClass: "bigdrop",
//        escapeMarkup: function (m) { return m; }
//
//    });
    
    $('#tipo_evaluado').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_niveles_autoevaluacion',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10
                }
            },
            results: function(data, page){
                return {
                    results: data
                }
            }
        },
         initSelection: function(element, callback) {
        // the input tag has a value attribute preloaded that points to a preselected movie's id
        // this function resolves that id attribute to an object that select2 can render
        // using its formatResult renderer - that way the movie name is shown preselected
        var id=$(element).val();            
            if (id!=="") {
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_nivel_autoevaluacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel;
            
        },
        formatSelection: function(d){
            return d.nivel;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    var ins = $('#institucion_id').val();
    var jqxhr = $.get('index.php?mod=sievas&controlador=lmcc&accion=get_rubros_gral&i='+ins);
    
    jqxhr.done(function(data){
        data = JSON.parse(data);
        var low = [];
        var med = [];
        var high = [];
        var gradacion = $.map(data, function(val, idx){
           low.push(3);
           med.push(6);
           high.push(10);
           return parseFloat(val.gradacion); 
        });
        var categorias = $.map(data, function(val, idx){
           return 'Rubro '+val.num; 
        });
        console.log(gradacion);
        Highcharts.chart('grafico1', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Reporte de calificaciones generales por rubros'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: categorias
            },
            yAxis: {
                title: {
                    text: 'Calificación'
                },
                max : 10,
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
                name: 'Rubros general',
                data: gradacion
            }, ]
        });
        
        Highcharts.chart('grafindicametro1', {

            chart: {
                polar: true,
                type: 'area'
            },
//             plotOptions: {
//            column: {
//                colorByPoint: true
//            }
//            },
//            colors: [
//                'red',
//                'yellow',
//                'green',
//                'orange'],

            title: {
                text: 'Grafindicámetro general',
                x: -80
            },

            pane: {
                size: '80%'
            },

            xAxis: {
                categories: categorias,
                tickmarkPlacement: 'on',
                lineWidth: 0
            },

            yAxis: {
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 0,
                max: 10
            },

//            tooltip: {
//                shared: true,
//                pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
//            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: 'Evaluacion general',
                color: 'orange',                
                data: gradacion,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            },{
                name: 'Nivel bajo',
                data: low,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel medio',
                data: med,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel alto',
                data: high,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }]

        });
       
    });
    
    var jqxhr = $.get('index.php?mod=sievas&controlador=lmcc&accion=get_rubros_ev_reciente&i='+ins);
    
    jqxhr.done(function(data){
        data = JSON.parse(data);
        var low = [];
        var med = [];
        var high = [];
        var gradacion = $.map(data.rubros, function(val, idx){
           low.push(3);
           med.push(6);
           high.push(10);
           return parseFloat(val.promedio); 
        });
        var categorias = $.map(data.rubros, function(val, idx){
           return 'Rubro '+val.num_orden; 
        });
        console.log(gradacion);
        Highcharts.chart('grafico2', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Reporte de evaluacion más reciente'
            },
            subtitle: {
                text: data.etiqueta
            },
            xAxis: {
                categories: categorias
            },
            yAxis: {
                title: {
                    text: 'Calificación'
                },
                max : 10,
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
                name: 'Rubros general',
                data: gradacion
            }, ]
        });
        
        Highcharts.chart('grafindicametro2', {

            chart: {
                polar: true,
                type: 'area'
            },
//             plotOptions: {
//            column: {
//                colorByPoint: true
//            }
//            },
//            colors: [
//                'red',
//                'yellow',
//                'green',
//                'orange'],

            title: {
                text: 'Grafindicámetro general evaluación más reciente',
            },
            subtitle: {
                text: data.etiqueta
            },
            pane: {
                size: '80%'
            },

            xAxis: {
                categories: categorias,
                tickmarkPlacement: 'on',
                lineWidth: 0
            },

            yAxis: {
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 0,
                max: 10
            },

//            tooltip: {
//                shared: true,
//                pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
//            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: 'Evaluacion más reciente',
                color: 'orange',                
                data: gradacion,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            },{
                name: 'Nivel bajo',
                data: low,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel medio',
                data: med,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel alto',
                data: high,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }]

        });
       
    });
    
    var jqxhr = $.get('index.php?mod=sievas&controlador=lmcc&accion=get_grafindicametros_ev&i='+ins);
    
    jqxhr.done(function(data){
        data = JSON.parse(data);
//        console.log(data);
        
        $.each(data, function(index,value){
            var low = [];
            var med = [];
            var high = [];
             var gradacion_ei = $.map(value.rubros, function(val, idx){
                low.push(3);
                med.push(6);
                high.push(10);
                return val.ei.promedio > 0 ? parseFloat(val.ei.promedio) : 0; 
             });
             var gradacion_ee = $.map(value.rubros, function(val, idx){
//                low.push(3);
//                med.push(6);
//                high.push(10);
                return val.ee.promedio > 0 ? parseFloat(val.ee.promedio): 0; 
             });
             console.log(gradacion_ei);
             var categorias = $.map(value.rubros, function(val, idx){
                return 'Rubro '+val.num_orden; 
             });
             console.log(categorias);
            
            Highcharts.chart('linea_esp_'+index, {
                chart: {
                    type: 'line'
                },
                 title: {
                    text: 'Estadística comparativa evaluación',
                    },
                subtitle: {
                    text: value.etiqueta
                },
                xAxis: {
                    categories: categorias
                },
                yAxis: {
                    title: {
                        text: 'Calificación'
                    },
                    max : 10,
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
                    name: 'Evaluación interna',
                    data: gradacion_ei
                }, {
                    name: 'Evaluación externa',
                    data: gradacion_ee
                }]
            });
            
            Highcharts.chart('grafindicametro_esp_'+index, {

            chart: {
                polar: true,
                type: 'area'
            },
            title: {
                text: 'Grafindicámetro evaluación interna',
            },
            subtitle: {
                text: value.etiqueta
            },
            pane: {
                size: '80%'
            },

            xAxis: {
                categories: categorias,
                tickmarkPlacement: 'on',
                lineWidth: 0
            },

            yAxis: {
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 0,
                max: 10
            },

//            tooltip: {
//                shared: true,
//                pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
//            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: 'Evaluacion general',
                color: 'orange',                
                data: gradacion_ei,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            },{
                name: 'Nivel bajo',
                data: low,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel medio',
                data: med,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel alto',
                data: high,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }]

        });
            Highcharts.chart('grafindicametro_ext_'+index, {

            chart: {
                polar: true,
                type: 'area'
            },
            title: {
                text: 'Grafindicámetro evaluación externa',
            },
            subtitle: {
                text: value.etiqueta
            },
            pane: {
                size: '80%'
            },

            xAxis: {
                categories: categorias,
                tickmarkPlacement: 'on',
                lineWidth: 0
            },

            yAxis: {
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 0,
                max: 10
            },

//            tooltip: {
//                shared: true,
//                pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
//            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: 'Evaluacion general',
                color: 'orange',                
                data: gradacion_ee,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            },{
                name: 'Nivel bajo',
                data: low,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel medio',
                data: med,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }, {
                name: 'Nivel alto',
                data: high,
                fillOpacity: '0.2',
                pointPlacement: 'on'
            }]

        });
        });
        
        
        $('#carousel-grafindicametros').carousel();
        $('#carousel-estadisticas').carousel();
        
        $('#carousel-grafindicametros .left').click(function() {
            console.log("hola");
            $('#carousel-grafindicametros').carousel('prev');
          });

          $('#carousel-grafindicametros .right').click(function() {
               console.log("hola");
            $('#carousel-grafindicametros').carousel('next');
          });
        $('#carousel-estadisticas .left').click(function() {
            console.log("hola");
            $('#carousel-estadisticas').carousel('prev');
          });

          $('#carousel-estadisticas .right').click(function() {
               console.log("hola");
            $('#carousel-estadisticas').carousel('next');
          });
       
    });
  
}

main.init = function(){
    $('.cambiarunidad').on('click', function(e){
        e.preventDefault();
        console.log("hola");
    });
    
    $('.spa_link').on('click', function(){
        main.init_flot_chart();
    });
    
    
    $('.carousel').carousel({
        interval: 1000 * 30
      });
      var socket = io('http://sievas.herokuapp.com');
      socket.on('actualizar_lmcc', function(){
          main.init_flot_chart();
//        window.location.reload();
      });
    main.init_flot_chart();
}

$(main.init);