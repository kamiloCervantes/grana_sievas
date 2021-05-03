var historico_tablas = {};


historico_tablas.init = function(){
     historico_tablas.graficar_numero_egresados(1,47);
}

historico_tablas.graficar_numero_egresados = function(rubro, evaluacion){
    
    var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=get_data_grafica_egresados', {
        rubro : rubro,
        evaluacion : evaluacion
    });
    
    jqxhr.done(function(data){
           //colors
    var datos = [];
    $.each(data, function(index,value){
        datos.push(value.valores);
    });
    var colors = [];
    
    //labels
    var labels = [];
    
    //maxima y
    console.log(datos);
    
     new RGraph.Line({
            id: 'cvs',
            data: datos,
            options: {
            gutterTop: 50,
            gutterLeft: 85,
            colors: [
                '#666'
            ],
            shadow: false,
            spline: true,
            linewidth: 5,
            ymax: 50,
            scaleDecimals: 1,
            ylabelsCount: 3,
            backgroundGridAutofitNumhlines: 9,
            backgroundGridAutofitNumvlines: 9,
            axisColor: 'gray',
            textSize: 18,
            textAccessible: true,
            numyticks: 6,
            numxticks: 3,
            title: 'Histórico del número de egresados',
            tickmarks: null,
            backgroundHbars: [
                [0,5,'rgba(240,240,240,0.25)'],
                [5,5,'rgba(210,210,210,0.25)'],
                [10,5,'rgba(150,150,150,.25)']
            ],
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

            unitsPost: 'mph',
            BackgroundBarcolor1: 'white',
            BackgroundBarcolor2: 'white',
            BackgroundGridColor: 'rgba(238,238,238,1)',
            filled: true,
            fillstyle: ['rgba(240,240,240,0)','rgba(240,240,240,0)','rgba(240,240,240,0)'],
            hmargin: 5,
        }
        }).trace2();
    });
    
    //data
//    var data = [
//        [8,7,6,4,9,5,6,7,9],
//        [4,3,5,8,6,4,2,4,9],
//        [8,4,9,5,3,5,1,2,5]
//    ];
    
 
}


$(historico_tablas.init);


