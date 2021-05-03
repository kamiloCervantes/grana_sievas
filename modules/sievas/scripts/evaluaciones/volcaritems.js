var volcaritems = {};


volcaritems.init = function(){
   $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
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
   $('#evaluado').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    pais : $('#pais').val(),
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluado&id="+id;
                var jqxhr = $.get(url, {
                  
                });
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return '<b>'+d.programa+'</b><br/>'+d.adscrito;
        },
        formatSelection: function(d){
            return d.programa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
   });
   $('#evaluacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones_evaluado',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    evaluado : $('#evaluado').val(),
                    rol : 1,
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones_evaluado&id="+id;
                var jqxhr = $.get(url, {
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    evaluado : $('#evaluado').val(),
                    rol : 1,
                });
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.etiqueta;
        },
        formatSelection: function(d){
            return d.etiqueta;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
}

$(volcaritems.init);