console.log("MEGA BUTTONS LOAD OK!!");
//createButton crea botones basicos
//createTableButton inserta botones basicos en cada renglon de una tabla

function createButton($divSelector,$link,$type){
	$btns=[]; $type=$type.toLowerCase();
   	//	typeButton:		class	  title		icon
    $btns["edit"]=["btn-warning","Editar","pencil"];
    $btns["remove"]=["btn-danger","Eliminar","remove"];
    $btns["home"]=["btn-warning","Inicio","home"];

    	me=$($divSelector);
		//BOTON EDITAR y ELIMINAR	
		_a=$('<a class="btn btn-xs '+$btns[$type][0]+'" data-toggle="tooltip" title="'+$btns[$type][1]+'">');
                
                url = "?p="+$link;
                n = $link.search("baja");
                
                //Valido encontrar la palabra baja en $link
               if (n === -1) {
                   //si no existe, armo el href para editar
                    _a.attr("href", url);
                } else {
                    //si existe la palabra baja, envia un alert antes de eliminar el elemento
                    _a.attr("onClick", "if(confirm('¿Desea eliminar el elemento seleccionado?')){document.location.href='"+url+"'}else{console.log('No se elimino el elemento')}");
                }
                
		_i=$('<i class="glyphicon glyphicon-'+$btns[$type][2]+'"></i>');
		me.append( _a.append(_i) );
}

function createTableButton($table,$link,$type){
	//COLOCAR BOTONES EN TABLA
	//requiere que cada tr tenga el atributo data-rowid=id
	$($table+' tbody tr').each(function(i) {
		me=$(this)
		_td=me.find('.tdActions');

		//si no hay tdActions lo crea!
		if(_td.length<=0) {
			_td=$('<td class="text-center tdActions">');
			me.append( _td );
		}

		$tbLink=$link+"&id="+me.attr("data-rowid");
		createButton(me.find('.tdActions'),$tbLink,$type);
		_td.css("width",(_td.children().length+1)*35+"px");
		
	});

}