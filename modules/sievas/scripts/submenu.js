var menusub = {};

menusub.init = function(){
    $.ajax({
        url: "../../menu/submenu.php",
        success: function(str){
                $("#navigation").html(str);		
        }
    });
      
}

$(menusub.init);

