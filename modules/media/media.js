window.onload = function ( ) {
//spinner
//$('#page-loader').fadeOut(50);

            
    //PONEMOS LOS BOTONES DE EDIT Y REMOVE EN LA TABLA
    $.getScript("libs/megaButtons/mb.js",function(){
      createTableButton("#table-fotos","media|actionMedia&baja","remove");    
    });
    
    

  //si estamos en pagina "products|productList", ejecutamos la funcion
	if(getCurrentPage()==="media|mediaList"){
            
              //COLOCAMOS EL HOT SEARCH
            $.getScript("libs/hotSearch/hs.js",function(){
              hotSearch($('#table-fotos'));
            });
            }
}