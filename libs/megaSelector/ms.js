console.log("MEGA SELECTOR LOAD OK!!");

/*megaSelector recibe: 1-array con datos de la BBDD generalmente obtenidos con AJAX
 2-string con los campos a mostrar ordenados y separados con "|"
 3-un formulario jQuery, es decir algo asi $("#idForm") 
 con esto permite seleccionar los datos de una lista y autocompleta los inputs 
 del form recibido que tengan el mismo name que el data..*/

function insertMegaSelector($data,$camposShow,$formulario,$onSelectFunction=null){
	$camposShow=$camposShow.split("|");
	$grup=$('<div class="megaSelector megaSelectorHiden">');
	$inp=$('<input id="megaSelectorSearchInput" class="list-group-item" type="text" placeholder="buscar..">');
	$sel=$('<ul id=mySelector class="list-group">');

	$btn=$('<a class="btn btn-ms btn-warning pull-right"><i class="glyphicon glyphicon-search"></i></a>');
	$btn.click(function(){
		$grup.toggleClass("megaSelectorHiden");
		$formulario.toggleClass("megaSelectorHiden");
		$inp.focus();
	});

	$data.forEach(function( element, index ) {
		$str="";
		$camposShow.forEach(function( c ) {$str+=element[c]+" ";});
		$sel.append($('<li class="list-group-item" data-idelement="'+element["id"]+'">'+$str+'</li>').click(
			function(){
				//$inp.val($(this).html());
				$grup.toggleClass("megaSelectorHiden");
				$formulario.toggleClass("megaSelectorHiden");
				megaSelectorSelect($formulario,element);
				if($onSelectFunction!=null) $onSelectFunction(element);
				$(this).val("");
				filtrarMegaList($sel,"");
			}
		));
	});

	$inp.on("keyup", function(e) {
    	var value = $(this).val().toLowerCase();
	    filtrarMegaList($sel,value);
	    if(e.key.toLowerCase()=="enter"){
	    	$grup.toggleClass("megaSelectorHiden");
			$formulario.toggleClass("megaSelectorHiden");
			$firstId=$sel.find('li:not([style*="display: none"])').first().attr("data-idelement");			
			$firstElement=$data.find(function(e){return e['id']==$firstId});
			if($firstElement){
				megaSelectorSelect($formulario,$firstElement);
				if($onSelectFunction!=null) $onSelectFunction($firstElement);
			}
			$(this).val("");
			filtrarMegaList($sel,"");
	    
	    }else if(e.key.toLowerCase()=="arrowup"){
	    	//VER COMO PONER QUE SE PUEDA ELEGIR CON LOS CURSORES
	    	$sel.find('li:first').addClass('megaSelectionArrow');
	    	$liActual=$sel.find('li [class|="megaSelectionArrow"]');
	    	console.log($liActual);
	    	$liActual.next().addClass('megaSelectionArrow');
	    }
	    console.log(e.key);
  	});

	$grup.append($inp);	
	$grup.append($sel);
	$formulario.prepend($btn);
	$formulario.after($grup);
}

function insertMegaSelectorBuscador($data,$camposShow,$inputSearch,$onSelectFunction=null){
	$camposShow=$camposShow.split("|");
	//$grup=$('<div class="megaSelector megaSelectorHiden">');
	//$inp=$('<input id="megaSelectorSearchInput" class="list-group-item" type="text" placeholder="buscar..">');
	$sel=$('<ul class="list-group megaSelector megaSelectorHiden" hidden>');
	$data.forEach(function( element, index ) {
		$str="";
		$camposShow.forEach(function( c ) {$str+=element[c]+" ";});
		$sel.append($('<li class="list-group-item megaSelectorLi" data-idelement="'+element["id"]+'">'+$str+'</li>')
			.click( function(){
				$sel=$(this).parent();
				$sel.addClass("megaSelectorHiden");
				$inputSearch.val($(this).text());				
				if($onSelectFunction!=null) {
					$onSelectFunction(element);
					}
				}
			));		
	});

	$inputSearch.click(function(){$(this).parent().find(".megaSelector").toggleClass("megaSelectorHiden");})

	$inputSearch.on("keyup", function(e) {
    	var value = $(this).val().toLowerCase();
    	$sel=$(this).parent().find(".megaSelector");
	    filtrarMegaList($sel,value);
	    $sel.removeClass("megaSelectorHiden");
	    //al apretar enter
	    if(e.key.toLowerCase()=="enter"){
	    	$sel.addClass("megaSelectorHiden");
			$firstId=$sel.find('li:not([style*="display: none"])').first().attr("data-idelement");			
			$firstElement=$data.find(function(e){return e['id']==$firstId});
			if($firstElement){
				if($onSelectFunction!=null) $onSelectFunction($firstElement);
			}
	    
	    }else if(e.key.toLowerCase()=="arrowup"){
	    	//VER COMO PONER QUE SE PUEDA ELEGIR CON LOS CURSORES
	    	$sel.find('li:first').addClass('megaSelectionArrow');
	    	$liActual=$sel.find('li [class|="megaSelectionArrow"]');
	    	console.log($liActual);
	    	$liActual.next().addClass('megaSelectionArrow');
	    }
	    //console.log(e.key);
  	});

	$inputSearch.after($sel);
	setTimeout(function(){ 
    	$(".megaSelector").removeAttr("hidden");
    }, 500); 
}

function megaSelectorSelect($form,data){
	$form.find('input[name]').each(function(){
		$(this).val(data[$(this).attr("name")]);
	});
	$form.find("input").first().focus();	
}

function filtrarMegaList(list, value){
	list.find("li").filter(function() {
	   $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	 });
}

//IMPORTAMOS EL CSS PARA EL megaSelector
$("head").append("<link>");
css = $("head").children(":last");
css.attr({
rel: "stylesheet",
type: "text/css",
href: "libs/megaSelector/ms.css"
});