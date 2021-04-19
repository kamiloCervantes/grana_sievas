var linea_calificaciones = {};


linea_calificaciones.init = function(){
     RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_comparativa_items', linea_calificaciones.graficar_datos);
//     linea_calificaciones.graficar_datos_locales();
      setInterval(function(){
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_comparativa_items', linea_calificaciones.graficar_datos);
    }, 2000);
}

linea_calificaciones.graficar_datos = function(data){
    RGraph.reset(document.getElementById('cvs'));
    var tmp = [
        data.einternaact,
        data.eexternaact,
        data.einternaant,
        data.eexternaant
    ];
//    tmp.push(data.einternaact);
//    tmp.push(data.eexternaact);
//    tmp.push(data.einternaant);
//    tmp.push(data.eexternaant);
    console.log(tmp);
    //maxima y
//    console.log($('#cvs').data('eiac').split(','));
      new RGraph.Line({
            id: 'cvs',
            data:  tmp,
            options: {
                gutterTop: 50,
                colors: ['#ff8e00','#41db00','rgba(200,50,100,0.4)','#ff0000'],
                tickmarks: 'filledcircle',
                keyInteractive: true,
                textAccessible: true,
                ticksize: 3,
                shadow: false,
                spline: true,
                hmargin: 15,
                linewidth: 1,
                ymin: 0,
                ymax: 10,
                ylabelsCount: 7,
                backgroundGridAutofitNumhlines: 7,
                backgroundGridVlines: false,
                axisColor: 'gray',
                textSize: 18,
                textAccessible: true,
                tickmarks: 'dot',
                ticksize: 10,
                numyticks: 10,
                keyPositionX: 50,
                gutterLeft: 5,
                gutterRight: 35,
                gutterBottom: 35,
                title: '',
//                key: ['Evaluación interna actual', 'Evaluación externa actual', 'Evaluación interna anterior', 'Evaluación externa anterior'],
                tooltips: data.tooltips,
                highlightStyle: 'halo'
            }
        }).draw();
}

linea_calificaciones.graficar_datos_locales = function(){    
    //data
    var data = [];
    
    data.push($('#cvs').data('eiac').split(',').map(function(val,idx){
        return parseInt(val);
    }));
    data.push($('#cvs').data('eeac').split(',').map(function(val,idx){
        return parseInt(val);
    }));
    data.push($('#cvs').data('eian').split(',').map(function(val,idx){
        return parseInt(val);
    }));
    data.push($('#cvs').data('eean').split(',').map(function(val,idx){
        return parseInt(val);
    }));
    
    console.log(data);
    
    var tooltips = [];
    
    tooltips.push($('#cvs').data('tooltips').split('&'));
    tooltips.push($('#cvs').data('tooltips').split('&'));
    tooltips.push($('#cvs').data('tooltips').split('&'));
    tooltips.push($('#cvs').data('tooltips').split('&'));
//    console.log(tooltips);
     var colors = [];
    
    //labels
    var labels = [];
    labels.push($('#cvs').data('labels').split('&'));
    labels.push($('#cvs').data('labels').split('&'));
    labels.push($('#cvs').data('labels').split('&'));
    labels.push($('#cvs').data('labels').split('&'));
    
    //maxima y
//    console.log($('#cvs').data('eiac').split(','));
      new RGraph.Line({
            id: 'cvs',
            data:  data,
            options: {
                gutterTop: 50,
                colors: ['#ff8e00','#41db00','rgba(200,50,100,0.4)','#ff0000'],
                tickmarks: 'filledcircle',
                keyInteractive: true,
                textAccessible: true,
                ticksize: 3,
                shadow: false,
                spline: true,
                hmargin: 15,
                linewidth: 1,
                ymin: 0,
                ymax: 10,
                ylabelsCount: 7,
                backgroundGridAutofitNumhlines: 7,
                backgroundGridVlines: false,
                axisColor: 'gray',
                textSize: 18,
                textAccessible: true,
                tickmarks: 'dot',
                ticksize: 10,
                numyticks: 10,
                title: '',
                key: ['Evaluación interna actual', 'Evaluación externa actual', 'Evaluación interna anterior', 'Evaluación externa anterior'],
                keyPosition: 'gutter',
                tooltips: $('#cvs').data('tooltips').split('&'),
                highlightStyle: 'halo'
            }
        }).draw();
}


$(linea_calificaciones.init);


