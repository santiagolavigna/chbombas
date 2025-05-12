console.log("HOT SEARCH LOAD OK!!");

//recibe un table jquery y le añade un filtro de busqueda en caliente,
//que filtra los resultados cada vez que se presiona una tecla.
function hotSearch($table){
	$search=$('<input id="mySearchBar" type="text" class="form-control search-control" name ="search" placeholder="Buscar...">');
	$search.on("keyup", function() {
            
    	var value = $(this).val().toLowerCase();

	    $table.find("tbody tr").filter(function() {
	      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
	    });

  	});
        
      
        
	$table.before($search);
}

function hotSearchSelect($table){
	$search=$('<input id="mySearchBar" type="text" class="form-control search-control" name ="search" placeholder="Buscar...">');
	$search.on("keyup", function() {
            
    	var value = $(this).val().toLowerCase();

	    $table.find("tbody tr td[data-columnid='Cat']").filter(function() {
	      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
	    });

  	});
        
      
        
	$table.before($search);
}