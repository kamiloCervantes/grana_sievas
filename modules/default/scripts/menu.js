var menu = {};

menu.init = function(){
    $.ajax({
        url: "views/menus/menu.php",
        success: function(str){
                $("#navigation").html(str);
        }
    });    
}

$(menu.init);

