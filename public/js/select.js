$.fn.cargarSelect = function(_selectedvalue, _data_collection, _optionvalue, _optiontext ){   
    //for(d in _data_collection){
    var este    =$(this);
    este.empty();
    $.each(_data_collection,function(i,val){
        //$(this).append('<option value="'+_data_collection[d][_optionvalue]+'">'+_data_collection[d][_optiontext]+'</option>');
        este.append('<option value="'+val[_optionvalue]+'">'+val[_optiontext]+'</option>');
    });
    if(_selectedvalue !== null)
    $(this).find('option[value='+_selectedvalue+']').prop('selected', 'selected');
};
$.fn.generarSelect = function(_select_id, _selectedvalue, _data_collection, _optionvalue, _optiontext ){
    var select = '<select id="'+_select_id+'" class="form-control" name="'+_select_id+'">';
    for(d in _data_collection){
        select += '<option value="'+_data_collection[d][_optionvalue]+'">'+_data_collection[d][_optiontext]+'</option>';
    }
    if(_selectedvalue !== null)
    $(select).find('option[value='+_selectedvalue+']').prop('selected', 'selected');
    select += '</select>';
    console.log(select);
    return select;
};