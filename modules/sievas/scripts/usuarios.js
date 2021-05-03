var usuarios = {};

usuarios.oTable = $('#data_tabla');

usuarios.init = function(){
    $.ajax({
        url: "views/menus/menu.php",
        success: function(str){
                $("#navigation").html(str);		
        }
    });
    
    usuarios.oTable.on('click','.eliminar-generico',function(e){
        var id = $(this).data('id');
        usuarios.eliminar(e,id);
    });
    
    usuarios.oTable.on('click','.editar-generico',function(e){
        var data = $(this).data();
        usuarios.editar(e,data);
    });
    
    $('#add-generic').on('click', usuarios.guardar);
    
    usuarios.oTable.dataTable({
        "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
        "bProcessing": true,
        "sAjaxSource": 'index.php?mod=academico&controlador=usuarios&accion=listar',
        "aoColumns":[
            {"mData": 1},
            {"mData": 2},
            {"mData": 5},
            {"mData": 6},
            {
                "mData":1,
                //"sName":"id",
                "mRender":function(id){
                    return '<a href="#" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                }
            }
        ]
    });
}

usuarios.eliminar = function(e, id){
    bootbox.confirm({
    title: 'Eliminar Area',
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
            var data = id+' aca_areas id';
            var jqxhr = $.post('index.php?mod=academico&controlador=aca_areas&accion=eliminar',{
                 id : data
             });

             jqxhr.done(function(){
                usuarios.oTable.fnReloadAjax();
             });
                    
        }
    }
});
}

usuarios.guardar = function(e){
    e.preventDefault();
    $.get('modules/academico/plantillas/aca_areas.html',function(html){        
            $html = $.parseHTML(html);
            $($html).attr('id', 'guardar-form');
            $('#activo', $html).checkbox('SI');                
                var modal = bootbox.dialog({
                message: $html,
                title: "Agregar Area",
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-success",
                        callback: function() {                                    
                                if($('#guardar-form').valid()){
                                    $('#guardar-form').on('submit', function(){
                                        var params = $('#guardar-form').serialize();
                                        var jqxhr = $.post('index.php?mod=academico&controlador=aca_areas&accion=guardar', params);

                                        jqxhr.done(function(){
                                            usuarios.oTable.fnReloadAjax();
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
                         usuarios: {
                            required: true,
                            maxlength: 70
                        },
                        abreviatura: {
                            required: true,
                            maxlength: 20
                        }
                    }
                });  
            }); 
    });
}

usuarios.editar = function(e,data){
    e.preventDefault();
         $.get('modules/academico/plantillas/usuarios.html',function(html){        
            
            var jqxhr = $.get('index.php?mod=academico&controlador=aca_areas&accion=get_fkeys',{
                id : data.id,
                tabla : 'aca_areas'
            });
            
            jqxhr.done(function(data2){
                data2 = JSON.parse(data2);
                $html = $.parseHTML(html);
                
                $($html).attr('id', 'editar-form');
                $('#area', $html).val(data2.principal.area);
                $('#abreviatura', $html).val(data2.principal.abreviatura);
                $('#activo', $html).checkbox(data2.principal.activo);
                $('#pkey', $html).attr({
                    name : 'pkey',
                    value : data.id
                });
                
                var modal = bootbox.dialog({
                message: $html,
                title: "Editar Usuario",
                buttons: {
                    success: {
                        label: "Actualizar",
                        className: "btn-success",
                        callback: function() {
                            if($('#editar-form').valid()){
                                $('#editar-form').on('submit', function(){
                                    var params = $('#editar-form').serialize();
                                    var jqxhr = $.post('index.php?mod=academico&controlador=usuarios&accion=editar', params);

                                    jqxhr.done(function(){
                                        usuarios.oTable.fnReloadAjax();
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
                             area: {
                                required: true,
                                maxlength: 70
                            },
                            abreviatura: {
                                required: true,
                                maxlength: 20
                            }
                        }
                    });  
                });
            });
    })
}

$(usuarios.init);