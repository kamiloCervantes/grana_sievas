
var evaluacion_complementaria_exportar = {};


evaluacion_complementaria_exportar.init = function(){
    $('.guardarword').on('click', function(e){
        e.preventDefault();
        console.log("hola");
        $('.word_version').wordExport();
    });
}

$(evaluacion_complementaria_exportar.init);