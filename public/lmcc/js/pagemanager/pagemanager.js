/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var pagemanager = {};


pagemanager.init = function(){
    var url = window.location.hash;
    if(url == ''){
        url = '#inicio';
    }
    pagemanager.navigate(url);
    
    $('a.spa_link').on('click', function(){
        var url = $(this).prop('href').substring($(this).prop('href').indexOf('#'));
        if(url == ''){
            url = '#inicio';
        }
        pagemanager.navigate(url);
    });
}

pagemanager.navigate = function(url){
    $('.page').addClass('hide');
    $('.page').each(function(index, value){
       if($(value).data('page') == url){
           $(value).removeClass('hide');
       } 
    });
}


$(pagemanager.init);