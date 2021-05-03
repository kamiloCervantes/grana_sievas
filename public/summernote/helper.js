/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var summernote_helper = {};


summernote_helper.init = function(){  
    $.each($('.summernote_modal'), function(index, value){
        console.log("hola");
        if(typeof $(value).data('content') != 'undefined' && $(value).data('content').length > 0 ){
            try{
                 $(value).html(decodeURIComponent($(value).data('content')));
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
        $(this).html('<input type="text" class="form-control">');
    });
    
    $(document).on('render.summernote_helper','.summernote_modal', function(){
        if($(this).data('content') !== null && typeof $(this).data('content') != 'undefined' && $(this).data('content').length > 0){
             $(this).html($.parseHTML(decodeURIComponent($(this).data('content'))));
        }
        else{
            $(this).trigger('reset.summernote_helper');
        }
       
    });
    
    $(document).on('click focus','.summernote_modal', function(e){
        var self = this;
        var content = '<p><br></p>';
        if($(this).find('input[type=text]').length === 0)
            content = $(this).html();

        var mensaje = '';
        mensaje += '<div class="summernote"><textarea class="editor"></textarea></div>';        
        $mensaje = $.parseHTML(mensaje);        
        $('textarea.editor', $mensaje).summernote({
            height: 300,
            lang: 'es-ES',
            toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
          ],
          focus: true
        });
        var title = ($(this).data('title') !== 'undefined' && $(this).data('title').length > 0) ? $(this).data('title') : 'Editar contenido';
        $('textarea.editor', $mensaje).code(content);
       
        var editable = $(this).data('editable');
        if(typeof editable === 'undefined' || editable > 0){
            bootbox.dialog({
            title:title,
            message:$mensaje,
            className: 'wide-modal',
            buttons: {
                success: {
                    label: "Aceptar",
                    className: "btn-success",
                    callback: function(){  
                        if($('textarea.editor').code() !== '' && $('textarea.editor').code() !== '<p><br></p>' && $('textarea.editor').code() !== '<br>'){
                            $(self).html($('textarea.editor').code());
                            $(self).data('content', $.trim(encodeURIComponent($('textarea.editor').code())));
//                            $(self).data('content', $.trim(encodeURIComponent(summernote_helper.formatInput($('textarea.editor').code()))));
                        }
                        else{
                            $(self).html('<input type="text" class="form-control">');
                            $(self).data('content', '');
                        }    
                        
                        
                        
                        if(typeof $(self).data('success') != 'undefined'){
                                $(document).trigger($(self).data('success'), data);
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
      
      output = summernote_helper.cleanWordPaste(output);

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
