(function($){
         $.fn.extend({
              checkbox: function(_si){      
                  return this.each(function(){  
                     var id = $(this).prop('id');
                     
                     $(this).attr('name', '');
                     
                     if(_si === 'SI')
                     $(this).attr('checked', true);
                     
                     if(_si === 'NO')
                     $(this).attr('checked', false);
                     
                     var input = $('<input/>', {
                        name : id,
                        id : 'input_'+id,
                        type: 'hidden'                        
                     });
                     
                     $(this).after(input);
                     $(this).next().val(_si);
                     
                     $(this).on('change', function(){
                         if($(this).is(':checked')){
                             $(this).next().val('SI');
                         }else{
                             $(this).next().val('NO');
                         }
                     });
                  });
              },
              
              multiple_check: function(name, data, data_checked, foreign_key, optionvalue, optionname, titulo, error_class){
                var html = '';
                html += '<h5><b>'+titulo+'</b></h5>';
                html += '<div class="'+error_class+'"></div>';
                
                $.each(data, function(index, value){
                    console.log(value);
                    html += '<div class="form-group">';
                    html += '<div class="col-sm-8">';
                    html += '<div class="checkbox">';
                    html += '<label>';
                    html += '<input type="checkbox" class="valor-requerido" name="'+name+'[]" value="'+value[optionvalue]+'">';
                    html += value[optionname];
                    html += '</label>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';                                
                });
                this.append(html);   
                if(data_checked !== null){
                    var check_group = $(this).find('input[type=checkbox]');
                    $.each(check_group, function(index,value){
                        $.each(data_checked, function(index2, value2){
                            if($(value).val() === value2[foreign_key]){
                                $(value).prop('checked', true);
                            }
                        });

                    });
                }
                return this;
            }
              
          });
          
          
          
 })(jQuery);
