function GetURLParameter(sParam){
    
    var sPageURL = window.location.search.substring(1);
    
    var sURLVariables = sPageURL.split('&');
    
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}

function indexurl(){
    var fullurl = $(location).attr('pathname');    
    var aux = fullurl.split('/');
    var index = aux[aux.length-1]
    var indexurl = index+$(location).attr('search');    
    return indexurl;
}