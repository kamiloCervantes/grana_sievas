
var roles_acceso = {};

roles_acceso.oTable = $('#data_tabla');

roles_acceso.init = function(){
    roles_acceso.oTable.on('click','.eliminar-generico',function(e){
        var id = $(this).data('id');
        roles_acceso.eliminar(e,id);
    });
    roles_acceso.oTable.on('click','.editar-generico',function(e){
        var data = $(this).data();
        roles_acceso.editar(e,data);
    });
    
    $('#add-generic').on('click', roles_acceso.guardar);
    
    roles_acceso.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=roles_acceso&accion=listar",
            "bProcessing": true,
            "fnServerParams": function(aoData){
                aoData.push({"name": "sIndexColumn", "value": "nom_pagina"});
                aoData.push({"name": "sTable", "value": "roles_acceso"});
            },
            "aoColumns":[
                {"mData": 0,"sName":"idrol"},
                {"mData": 1,"sName":"nom_pagina"},
                {"mData": 2,"sName":"permisos"},
                {
                    "mData":3,
                    "sName":"nom_pagina",
                    "mRender":function(id){
                        return '<a href="#" class="editar-generico" data-id="'+id+'">'
							 + '<i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico"'
							 + ' data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    }
                }
            ]    
    });
}

roles_acceso.eliminar = function(e, id){
    bootbox.confirm({
    title: 'Eliminar Rol',
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
            var data = id+' roles id';
            var jqxhr = $.post('index.php?mod=sievas&controlador=roles_acceso&accion=eliminar',{
                 id : data
             });

             jqxhr.done(function(){
                roles_acceso.oTable.fnReloadAjax();
             });
                    
        }
    }
});
}

roles_acceso.guardar = function(e){
    e.preventDefault();
    $.get('modules/sievas/plantillas/roles/roles_acceso.html',function(html){        
            $html = $.parseHTML(html);
            $($html).attr('id', 'guardar-form');
            $('#activo', $html).checkbox('SI');                
                var modal = bootbox.dialog({
                message: $html,
                title: "Agregar Rol",
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-success",
                        callback: function() {                                    
                                if($('#guardar-form').valid()){
                                    $('#guardar-form').on('submit', function(){
                                        var params = $('#guardar-form').serialize();
                                        var jqxhr = $.post('index.php?mod=sievas&controlador=roles_acceso&accion=guardar', params);

                                        jqxhr.done(function(){
                                            roles_acceso.oTable.fnReloadAjax();
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
                         idrol: {
                            required: true,
                            maxlength: 70
                        },
                        desc_rol: {
                            required: true,
                            maxlength: 20
                        }
                    }
                });  
            }); 
    });
}

roles_acceso.editar = function(e,data){
    e.preventDefault();
         $.get('modules/sievas/plantillas/roles/roles_acceso.html',function(html){        
            
            var jqxhr = $.get('index.php?mod=sievas&controlador=roles_acceso&accion=get_fkeys',{
                id : data.id,
                tabla : 'roles'
            });
            
            jqxhr.done(function(data2){
                data2 = JSON.parse(data2);
                $html = $.parseHTML(html);
                
                $($html).attr('id', 'editar-form');
                $('#idrol', $html).val(data2.principal.idrol);
                $('#desc_rol', $html).val(data2.principal.desc_rol);
                $('#activo', $html).checkbox(data2.principal.activo);
                $('#pkey', $html).attr({
                    name : 'pkey',
                    value : data.id
                });
                
                var modal = bootbox.dialog({
                message: $html,
                title: "Editar Rol",
                buttons: {
                    success: {
                        label: "Actualizar",
                        className: "btn-success",
                        callback: function() {
                            if($('#editar-form').valid()){
                                $('#editar-form').on('submit', function(){
                                    var params = $('#editar-form').serialize();
                                    var jqxhr = $.post('index.php?mod=sievas&controlador=roles_acceso&accion=editar', params);

                                    jqxhr.done(function(){
                                        roles_acceso.oTable.fnReloadAjax();
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
                             idrol: {
                                required: true,
                                maxlength: 70
                            },
                            desc_rol: {
                                required: true,
                                maxlength: 20
                            }
                        }
                    });  
                });
            });
    })
}

$(roles_acceso.init);

