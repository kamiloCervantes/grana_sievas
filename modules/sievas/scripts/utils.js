var utils = {};
utils.inArray = function(searchFor, property) {
    var retVal = -1;
    var self = this;
    for(var index=0; index < self.length; index++){
        var item = self[index];
        if (item.hasOwnProperty(property)) {
            if (String(item[property]).toLowerCase() === String(searchFor).toLowerCase()) {
            //if (item[property] === searchFor) {
                retVal = index;
                return retVal;
            }
        }
    };
    return retVal;
};
Array.prototype.inArray = utils.inArray;
/** Sistema de mensajes **/
var mensajeCargando={
    dialogo:null,
    mostrar:function(titulo){
        html   ='<div class="progress progress-striped active">';
        html   +='  <div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">';
        html   +='    <span class="sr-only">100% Complete</span>';
        html   +='  </div>';
        html   +='</div>';
        mensajeCargando.dialogo = bootbox.dialog({
            message: html,
            title: titulo,
            closeButton: false            
        });
    },
    ocultar:function(){
        mensajeCargando.dialogo.modal('hide');
    }
};
/**
 * Nuevo metodo cargar select
 */
$.fn.cargarSelectData = function(_selectedvalue, _data_collection, _optionvalue, _optiontext,_datos_adicionales){   
    //for(d in _data_collection){
    var este    =$(this);
    este.empty();
    $.each(_data_collection,function(i,val){
        //$(this).append('<option value="'+_data_collection[d][_optionvalue]+'">'+_data_collection[d][_optiontext]+'</option>');
        var datos='';
        if(_datos_adicionales!=null){
            $.each(_datos_adicionales,function(pos_da,val_da){
                datos += ' data-'+val_da+'="'+val[val_da]+'"';
            });
        }
        este.append('<option value="'+val[_optionvalue]+'"'+datos+'>'+val[_optiontext]+'</option>');
    });
    if(_selectedvalue !== null)
    $(this).find('option[value='+_selectedvalue+']').prop('selected', 'selected');
};
/**
 * @method mostrar_error_base
 * @param {string} error
 * @param {string} titulo
 * @param {function} accion
 */
utils.mostrar_error_base=function(error,titulo,accion){
    var infoError   =$.parseJSON(error.responseText);
    var mensajeError=error.responseText;
    if(typeof infoError==='object'){
        mensajeError    =infoError.status;
    }
    titulo  =   'Eval acad&eacute;mico - '+titulo;
    bootbox.dialog({
        title:titulo,
        message:mensajeError+'<br><br><i>Si el problema persite consulte con el administrador.</i>',
        buttons: {
            success: {
                label: "OK",
                className: "btn-danger",
                callback: accion
            }
        }
    });
};
/**
 * 
 * @param {type} titulo
 * @param {type} mensaje
 * @param {type} accion
 */
utils.mostrar_mensaje_base=function(titulo,mensaje,accion){
    bootbox.dialog({
        title:titulo,
        message:mensaje,
        buttons: {
            success: {
                label: "OK",
                className: "btn-success",
                callback: accion
            }
        }
    });
};