window.onload = function ( ) {
	//si estamos en pagina "categorie|categorieList", ejecutamos la funcion
	if(getCurrentPage()=="categorie|categorieList"){

                //PONEMOS LOS BOTONES DE EDIT Y REMOVE EN LA TABLA
		$.getScript("libs/megaButtons/mb.js",function(){
			createTableButton("#table-Categorias","categorie|abmCategorie","edit");
			createTableButton("#table-Categorias","categorie|actionCategorie&baja","remove");
			
			//$("#principalPage").prepend('<div id="homeButton">');
			//createButton("#homeButton","home","home");
			
		});
		
	}

	console.log("modulo <categorie.js> fue cargado OK!");
    
}

