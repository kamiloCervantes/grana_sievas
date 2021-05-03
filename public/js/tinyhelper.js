/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var summernote_helper = {};


summernote_helper.init = function(){  
    $.each($('.summernote_modal'), function(index, value){
        if(typeof $(value).data('content') != 'undefined' && $(value).data('content').length > 0){
            try{
                 var content = $(this).data('content').replace(/%/g, "%25");
                 $(value).html(decodeURIComponent(content));
            }
            catch(e){
                console.log(e);
            }
        }
        else{             
            $(value).trigger('reset.summernote_helper');            
        } 
    });
    
    $(document).on('reset.summernote_helper', '.summernote_modal', function(){
         if($(this).data('type') == 'link'){
             
         }
         else{
             $(this).html('<input type="text" class="form-control">');
         }
        
    });
    
    $(document).on('render.summernote_helper','.summernote_modal', function(){
        if($(this).data('content') !== null && typeof $(this).data('content') != 'undefined' && $(this).data('content').length > 0 ){
             var content = $(this).data('content').replace(/%/g, "%25");
            $(this).html(decodeURIComponent(content));
        }
        else{
            $(this).trigger('reset.summernote_helper');
        }
       
    });
    
    $(document).on('click','.summernote_modal', function(e){
        console.log(e);
        var self = this;
        var content = '<p><br></p>';
        if($(this).find('input[type=text]').length === 0 )
            content = $(this).html();

        var mensaje = '';
        mensaje += '<div class="summernote"><textarea class="editor"></textarea></div>';        
        $mensaje = $.parseHTML(mensaje);  
        
//        $('textarea.editor', $mensaje).summernote({
//            height: 300,
//            lang: 'es-ES',
//            toolbar: [
//            ['style', ['bold', 'italic', 'underline', 'clear']],
//            ['fontsize', ['fontsize']],
//            ['fontname', ['fontname']],
//            ['color', ['color']],
//            ['para', ['ul', 'ol', 'paragraph']],
//            ['height', ['height']],
//          ],
//          focus: true
//        });
        var title = ($(this).data('title') !== 'undefined' && $(this).data('title').length > 0) ? $(this).data('title') : 'Editar contenido';
        if($(self).data('type') == 'link'){
           if($(self).data('replacetitle') > 0){
           var title = ($(this).data('title') !== 'undefined' && $(this).data('title').length > 0) ? $(this).data('title').replace( $(this).data('title').substring($(this).data('title').indexOf('['),$(this).data('title').indexOf(']')+1), $('#'+$(this).data('title').substring($(this).data('title').indexOf('[')+1,$(this).data('title').indexOf(']'))).html() ) : 'Editar contenido';
       }
//           $('textarea.editor', $mensaje).code(decodeURIComponent($(self).data('content')));
//           tinymce.activeEditor.setContent($(self).data('content'));
        }
        else{
//            $('textarea.editor', $mensaje).code(content);
            
        }
        
       
        var editable = $(this).data('editable');
        if(typeof editable === 'undefined' || editable > 0){
            var modal = bootbox.dialog({
            title:title,
            message:$mensaje,
            className: 'wide-modal',
            buttons: {
                success: {
                    label: "Aceptar",
                    className: "btn-success",
                    callback: function(e){  
                        var old_data = $('textarea.editor').code();
                        var data = tinymce.activeEditor.getContent();
                        console.log(data);
                        if( data !== '' && data !== '<p><br></p>' && data !== '<br>'){
                            data = data.replace(/%/g, "%25");
                            console.log(data);
                            if($(self).data('type') == 'link'){
                                $(self).data('content', decodeURIComponent(data));
                            }
                            else{
                                $(self).html(decodeURIComponent(data));
//                                $(self).data('content', $.trim(encodeURIComponent($('textarea.editor').code())));
                                $(self).data('content', decodeURIComponent(data));
                            }
                           
                            
                        }
                        else{
                            if($(self).data('type') == 'link'){
                                $(self).data('content', '');
                            }
                            else{
                                $(self).html('<input type="text" class="form-control">');
                                $(self).data('content', '');
                            }
                            
                        }    
                        //data serialization
                        var data = [];
                        $.each($(e.target).parent().parent().find('select'), function(index,value){
                            console.log(value);
                            data[$(value).attr('name')] = $(value).val();
                        });
                        console.log(data);
                        
                        if(typeof $(self).data('success') != 'undefined'){
                                $(document).trigger($(self).data('success'), [data]);
                            }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function(){
                        
                    }
                },
            }
        });
        
        modal.init(function(){
            console.log("modal init");
            console.log($(self).data('content'));
            tinymce.init({
                selector: 'textarea.editor',
                plugins: 'paste',           
                language: 'es_MX',
                branding: false,
                paste_as_text: true,
                height : 300,
                init_instance_callback: function(editor){
                    if(typeof $(self).data('content') == 'undefined'){
                        editor.setContent('');
                    }
                    else{
                        console.log($(self).data('content'));
                        var content = $(self).data('content').replace(/%/g, "%25");
                        console.log(content);
                        editor.setContent(decodeURIComponent(content));
                    }
                    
                }
              });
//              console.log();
//            tinymce.activeEditor.setContent("test");
            
        });
        if(typeof $(self).data('onmodal') != 'undefined'){
            $(document).trigger($(self).data('onmodal'), [modal]);
        }
        
        }
        
    });
}

summernote_helper.formatInput = function(sInput){
    // 1. remove line breaks / Mso classes
      var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
      var output = sInput.replace(stringStripper, ' ');

      // 2. strip Word generated HTML comments
      var commentSripper = new RegExp('<!--(.*?)-->', 'g');
      output = output.replace(commentSripper, '');

      // 3. remove tags leave content if any
      var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>', 'gi');
      output = output.replace(tagStripper, '');

      // 4. Remove everything in between and including tags '<style(.)style(.)>'
      var badTags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];

      for (var i = 0; i < badTags.length; i++) {
        tagStripper = new RegExp('<' + badTags[i] + '.*?' + badTags[i] + '(.*?)>', 'gi');
        output = output.replace(tagStripper, '');
      }

      // 5. remove attributes ' style="..."'
      var badAttributes = ['style', 'start'];
      for (var ii = 0; ii < badAttributes.length; ii++) {
        var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"', 'gi');
        output = output.replace(attributeStripper, '');
      }
      
//      output = summernote_helper.cleanWordPaste(output);

      return output;
}

summernote_helper.cleanWordPaste = function( in_word_text ) {
 var tmp = document.createElement("DIV");
 tmp.innerHTML = in_word_text;
 var newString = tmp.textContent||tmp.innerText;
 // this next piece converts line breaks into break tags
 // and removes the seemingly endless crap code
 newString  = newString.replace(/\n\n/g, "<br />").replace(/.*<!--.*-->/g,"");
 // this next piece removes any break tags (up to 10) at beginning
 for ( i=0; i<10; i++ ) {
  if ( newString.substr(0,6)=="<br />" ) { 
   newString = newString.replace("<br />", ""); 
  }
 }
 return newString;
}

$(summernote_helper.init);
