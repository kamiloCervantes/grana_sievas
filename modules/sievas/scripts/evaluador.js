/*
 * 
 * @type object
 * Se inicializa un objeto para contener las funciones de la página
 */
var niveles = {};

/*
 * Carga en la variable oTable el id de la etiqueta del datatable
 */
niveles.oTable = $('#data_tabla');

/*
 * Función que se ejecuta al cargar la página
 * @returns {undefined}
 */
niveles.init = function(){
    /*
     * Asignación de evento click de botón eliminar
     */
    niveles.oTable.on('click','.eliminar-generico',function(e){
        var id = $(this).data('id');
        niveles.eliminar(e,id);
    });
    /*
     * Asignación de evento click de botón eliminar
     */
    niveles.oTable.on('click','.editar-generico',function(e){
        var data = $(this).data();
        niveles.editar(e,data);
    });
    /*
     * asignación del evento guardar en el botón guardar
     */
    $('#add-generic').on('click', niveles.guardar);
    /*
     * Inicialización de datatable
     */
    niveles.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=academico&controlador=aca_niveles&accion=listar",
            "bProcessing": true,
            "fnServerParams": function(aoData){
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "aca_niveles"});
            },
            "aoColumns":[
                {"mData": 0,"sName":"nivel"},
                {"mData": 1,"sName":"desc_nivel"},
                {"mData": 2,"sName":"activo"},
                {
                    "mData":3,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="#" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    }
                }
            ]    
    });
}
/*
 * Función que permite eliminar un registro
 */
niveles.eliminar = function(e, id){
    bootbox.confirm({
    title: 'Eliminar Nivel',
    message: '¿Esta seguro que desea eliminarlo?',
    buttons: {
        'cancel': {
            label: 'Cancelar',
            className: 'btn-default pull-right'
        },
        'confirm': {
            label: 'Aceptar',
            className: 'btn-danger pull-right'
        }
    },
    callback: function(result) {
        if (result) {
            var data = id+' aca_niveles id';
            var jqxhr = $.post('index.php?mod=academico&controlador=aca_niveles&accion=eliminar',{
                 id : data
             });

             jqxhr.done(function(){
                niveles.oTable.fnReloadAjax();
             });
                    
        }
    }
});
}
/*
 * Función que permite guardar un registro
 */
niveles.guardar = function(e){
    e.preventDefault();
    $.get('modules/academico/plantillas/aca_niveles.html',function(html){        
            $html = $.parseHTML(html);
            $($html).attr('id', 'guardar-form');
            $('#activo',$html).checkbox('SI');             
                var modal = bootbox.dialog({
                message: $html,
                title: "Agregar Nivel",
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-success",
                        callback: function() {                                    
                                if($('#guardar-form').valid()){
                                    $('#guardar-form').on('submit', function(){
                                        var params = $('#guardar-form').serialize();
                                        var jqxhr = $.post('index.php?mod=academico&controlador=aca_niveles&accion=guardar', params);

                                        jqxhr.done(function(){
                                            niveles.oTable.fnReloadAjax();
                                        });

                                        jqxhr.fail(function(err){
                                           alert(err.responseText); 
                                        });
                                    });       
                                    $('#guardar-form').submit();
                                }       
                                else{                                 
                                    return false;        
                                }
                        }
                    },
                    danger: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                            console.log("error");
                        }
                    }
                }
            });

             modal.on('shown.bs.modal', function(){
                var validator = $('#guardar-form').validate({
                    debug : true,
                    rules : {
                        nivel: {
                            required: true,
                            maxlength: 40
                        }
                    }
                });  
            }); 
    });
};

niveles.editar = function(e,data){
    e.preventDefault();
         $.get('modules/academico/plantillas/aca_niveles.html',function(html){        
            
            var jqxhr = $.get('index.php?mod=academico&controlador=aca_niveles&accion=get_fkeys',{
                id : data.id,
                tabla : 'aca_niveles'
            });
            
            jqxhr.done(function(data2){
                data2 = JSON.parse(data2);
                $html = $.parseHTML(html);
                
                $($html).attr('id', 'editar-form');
                $('#nivel',$html).val(data2.principal.nivel);
                $('#activo',$html).checkbox(data2.principal.activo);
                $('#pkey', $html).attr({
                    name : 'pkey',
                    value : data.id
                });
                
                var modal = bootbox.dialog({
                message: $html,
                title: "Editar Nivel",
                buttons: {
                    success: {
                        label: "Actualizar",
                        className: "btn-success",
                        callback: function() {
                            if($('#editar-form').valid()){
                                $('#editar-form').on('submit', function(){
                                    var params = $('#editar-form').serialize();
                                    var jqxhr = $.post('index.php?mod=academico&controlador=aca_niveles&accion=editar', params);

                                    jqxhr.done(function(){
                                        niveles.oTable.fnReloadAjax();
                                    });

                                    jqxhr.fail(function(err){
                                       alert(err.responseText); 
                                    });
                                });
                                $('#editar-form').submit();
                            }
                            else{
                                return false;  
                            }
                        }
                    },
                    danger: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                            console.log("errror");
                        }
                    }

                }
            });

            modal.on('shown.bs.modal', function(){
                    var validator = $('#editar-form').validate({
                        debug : true,
                        rules : {
                             nivel: {
                                required: true,
                                maxlength: 40
                            }
                        }
                    });  
                });
            });
    })
}

$(niveles.init);