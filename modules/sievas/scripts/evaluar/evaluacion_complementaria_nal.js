var evaluacion_complementaria_nal = {};


evaluacion_complementaria_nal.init = function(){
    
    $('#guardar-documento').on('click', function(e){
        e.preventDefault();
        var documento = Dropzone.forElement('#predictamen-dropzone').files[0];
        var nombre_doc = $("#nombre_documento").val();
        var fecha_doc  = $("#fecha_documento").val();
        var tipo_doc = $("#tipo_documento").val();
        var valid = true;
        
        if(typeof documento == 'undefined'){
            valid = false;
        }
        else{
            if(documento.type != 'application/pdf' || !(documento.type.toString().indexOf('pdf') > 0)){
                console.log(documento.type);
                valid = false;
            }
        }
        
        if(!(nombre_doc.length > 0) || !(fecha_doc.length > 0) || !(tipo_doc > 0)){
            valid = false;
        }
        
        if(valid){
            var data = new FormData();
            data.append('documento', documento);
            data.append('nombre_doc', nombre_doc);
            data.append('fecha_doc', fecha_doc);
            data.append('tipo_doc', tipo_doc);
            
            
            $.ajax({
                url: 'index.php?mod=sievas&controlador=evaluar&accion=guardar_documento_nal',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(response){
                   $('#documentos tbody').append("<tr><td>"+nombre_doc+"</td>"
                           +"<td>"+fecha_doc+"</td>"
                           +"<td>"+$("#tipo_documento option[value="+tipo_doc+"]").text()+"</td>"
                           +"<td><a href='#' data-id='"+response.id+"'><i class='glyphicon glyphicon-trash'></i></a></td>"
                           +"</tr>");
                    $("#nombre_documento").val('');
                    $("#fecha_documento").val('');
                    $("#tipo_documento").val('');
                   Dropzone.forElement('#predictamen-dropzone').removeAllFiles(true);
                }
            });
        }
        else{
            console.log("err");
        }
        
    });
    
    $('.fecha').datepicker();
    Dropzone.options.predictamenDropzone = {
        init: function(){
            this.on('success', function(file, data, xhr){
                console.log("ok");
//                console.log(data);
//                data = JSON.parse(data);
//                console.log($(this.element).parent());
//                $(this.element).parent().find('.dropzone').addClass('hide');
//                $(this.element).parent().find('.dictamen_link a').prop('href', data.url);
//                $(this.element).parent().find('.dictamen_link a').html(data.nombre);
//                $(this.element).parent().find('.dictamen_link a').after(' <a href="#" class="modificar_dictamen">[Modificar]</a>');
//                $(this.element).parent().find('.dictamen_link').removeClass('hide');
//                this.removeAllFiles(true);
            });
            this.on('error', function(file, err, e){
                console.log(e);
            });
        }
    };
}



$(evaluacion_complementaria_nal.init);