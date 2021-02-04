var tablas_estadisticas_graficas = {};


tablas_estadisticas_graficas.init = function(){
    RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=numero_egresados', tablas_estadisticas_graficas.graficar_numero_egresados);
    setInterval(function(){
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=numero_egresados', tablas_estadisticas_graficas.graficar_numero_egresados);
    }, 2000);
    RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=egr_001', tablas_estadisticas_graficas.graficar_egr_001);
    setInterval(function(){
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=egr_001', tablas_estadisticas_graficas.graficar_egr_001);
    }, 2000);
    RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=egr_002', tablas_estadisticas_graficas.graficar_egr_002);
    setInterval(function(){
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=egr_002', tablas_estadisticas_graficas.graficar_egr_002);
    }, 2000);
    RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=egr_003', tablas_estadisticas_graficas.graficar_egr_003);
    setInterval(function(){
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafica&param=egr_003', tablas_estadisticas_graficas.graficar_egr_003);
    }, 2000);
}

//callback
tablas_estadisticas_graficas.graficar_numero_egresados = function(data){
    RGraph.reset(document.getElementById('cvs'));
    console.log(data);
    var campos = data.campos;
    var tooltips = data.campos;
    var max = 0;
    $.each(data.valores, function(i,v){
        tooltips[i] = tooltips[i]+':'+v+' egresados';
        if(v > max){
            max = v;
            
        }
    });
    console.log(max);
    
    
    
      var line = new RGraph.Line({
            id: 'cvs',
            data:  data.valores,
            options: {
                gutterTop: 50,
                colors: ['#ff8e00'],
                tickmarks: 'filledcircle',
                keyInteractive: true,
                textAccessible: true,
                ticksize: 5,
                shadow: true,
                spline: false,
                hmargin: 15,
                linewidth: 3,
                ymin: 0,
                ymax: max,
                ylabels: false,
                ylabelsCount: 7,
                backgroundGridAutofitNumhlines: 7,
                backgroundGridVlines: false,
                axisColor: 'gray',
                textSize: 10,
                textAccessible: true,
                tickmarks: 'dot',
                ticksize: 10,
                numyticks: 10,
                title: '',
                key: ['Número de egresados'],
                keyPosition: 'gutter',
//                labels : campos,
                highlightStyle: 'halo',
                tooltips: tooltips
            }
        }).draw();
        
       
}
tablas_estadisticas_graficas.graficar_egr_001 = function(data){
    RGraph.reset(document.getElementById('cvs1'));
//    console.log(data);
    var campos = data.campos;
    var tooltips = data.campos;
    var max = 0;
    $.each(data.valores, function(i,v){
        tooltips[i] = tooltips[i]+':'+v+' egresados';
        if(v > max){
            max = v;
            
        }
    });
//    console.log(max);
    
    
    
      var line = new RGraph.Line({
            id: 'cvs1',
            data:  data.valores,
            options: {
                gutterTop: 50,
                colors: ['#ff8e00'],
                tickmarks: 'filledcircle',
                keyInteractive: true,
                textAccessible: true,
                ticksize: 5,
                shadow: true,
                spline: false,
                hmargin: 15,
                linewidth: 3,
                ymin: 0,
                ymax: max,
                ylabels: false,
                ylabelsCount: 7,
                backgroundGridAutofitNumhlines: 7,
                backgroundGridVlines: false,
                axisColor: 'gray',
                textSize: 10,
                textAccessible: true,
                tickmarks: 'dot',
                ticksize: 10,
                numyticks: 10,
                title: '',
                key: ['Egresados con reconocimiento nacional o internacional'],
                keyPosition: 'gutter',
//                labels : campos,
                highlightStyle: 'halo',
                tooltips: tooltips
            }
        }).draw();
        
//       .set('labels.ingraph', [['Hoolio', 'red', 'yellow', -1, 60],['Hoolio', 'red', 'yellow', -1, 60],['Hoolio', 'red', 'yellow', -1, 60]])
}
tablas_estadisticas_graficas.graficar_egr_002 = function(data){
    RGraph.reset(document.getElementById('cvs2'));
//    console.log(data);
    var campos = data.campos;
    var tooltips = data.campos;
    var max = 0;
    $.each(data.valores, function(i,v){
        tooltips[i] = tooltips[i]+':'+v+' egresados';
        if(v > max){
            max = v;
            
        }
    });
//    console.log(max);
    
    
    
      var line = new RGraph.Line({
            id: 'cvs2',
            data:  data.valores,
            options: {
                gutterTop: 50,
                colors: ['#ff8e00'],
                tickmarks: 'filledcircle',
                keyInteractive: true,
                textAccessible: true,
                ticksize: 5,
                shadow: true,
                spline: false,
                hmargin: 15,
                linewidth: 3,
                ymin: 0,
                ymax: max,
                ylabels: false,
                ylabelsCount: 7,
                backgroundGridAutofitNumhlines: 7,
                backgroundGridVlines: false,
                axisColor: 'gray',
                textSize: 10,
                textAccessible: true,
                tickmarks: 'dot',
                ticksize: 10,
                numyticks: 10,
                title: '',
                key: ['Egresados ejerciendo actividades en su ambito formativo en el extranjero'],
                keyPosition: 'gutter',
//                labels : campos,
                highlightStyle: 'halo',
                tooltips: tooltips
            }
        }).draw();
        
       
}
tablas_estadisticas_graficas.graficar_egr_003 = function(data){
    RGraph.reset(document.getElementById('cvs3'));
//    console.log(data);
    var campos = data.campos;
    var tooltips = data.campos;
    var max = 0;
    $.each(data.valores, function(i,v){
        tooltips[i] = tooltips[i]+':'+v+' egresados';
        if(v > max){
            max = v;
            
        }
    });
//    console.log(max);
    
    
    
      var line = new RGraph.Line({
            id: 'cvs3',
            data:  data.valores,
            options: {
                gutterTop: 50,
                colors: ['#ff8e00'],
                tickmarks: 'filledcircle',
                keyInteractive: true,
                textAccessible: true,
                ticksize: 5,
                shadow: true,
                spline: false,
                hmargin: 15,
                linewidth: 3,
                ymin: 0,
                ymax: max,
                ylabels: false,
                ylabelsCount: 7,
                backgroundGridAutofitNumhlines: 7,
                backgroundGridVlines: false,
                axisColor: 'gray',
                textSize: 10,
                textAccessible: true,
                tickmarks: 'dot',
                ticksize: 10,
                numyticks: 10,
                title: '',
                key: ['Egresados ejerciendo actividades en su ambito formativo en el pais'],
                keyPosition: 'gutter',
//                labels : campos,
                highlightStyle: 'halo',
                tooltips: tooltips
            }
        }).draw();
        
       
}


$(tablas_estadisticas_graficas.init);


