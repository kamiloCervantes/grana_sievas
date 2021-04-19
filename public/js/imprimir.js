var imprimir = {};

imprimir.init = function(){
    $('#imprimir').on('click', function(e){
        e.preventDefault();
       window.print(); 
    });
}

$(imprimir.init);