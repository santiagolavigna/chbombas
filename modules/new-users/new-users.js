window.onload = function ( ) {
	//si estamos en pagina "new-users|users", ejecutamos la funcion
	if(getCurrentPage()=="new-users|usersList") {

		//PONEMOS LOS BOTONES DE EDIT Y REMOVE EN LA TABLA
		$.getScript("libs/megaButtons/mb.js",function(){
			createTableButton(".userTable ","new-users|abmUser","edit");
			createTableButton(".userTable ","new-users|actionUsers&baja","remove");
		});

		//COLOCAMOS EL HOT SEARCH
		$.getScript("libs/hotSearch/hs.js",function(){
			hotSearch($('#table-clientes'));
		});
    	
    	console.log("modulo <users.js> fue cargado OK!");
	}

}