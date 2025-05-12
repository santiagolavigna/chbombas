function getAjax_POST(myUrl,datosPost,callback,failCallBack=null){
    $.ajax({	data:datosPost,//array con datos {k1:v1, k2:v2}
        		type: "POST", dataType: "JSON", url: myUrl, async:true })
    .done( callback ) //.done(function( data, textStatus, jqXHR ) )
    .fail( function(jqXHR,textStatus,errorThrown){console.log( "falla:"+textStatus+" error:"+errorThrown);console.log(jqXHR);});
}

function getAjax_POST_SYNC(myUrl,datosPost,callback,failCallBack=null){
    $.ajax({	data:datosPost,//array con datos {k1:v1, k2:v2}
        		type: "POST", dataType: "JSON", url: myUrl, async:false })
    .done( callback ) //.done(function( data, textStatus, jqXHR ) )
    .fail( function(jqXHR,textStatus,errorThrown){console.log( "falla:"+textStatus+" error:"+errorThrown);console.log(jqXHR);});
}

function getCurrentPage(){
	var pathname = window.location.href.split("?p=")[1];
	return pathname;
}

function getScriptSYNC($url){
	$.ajax({url:$url,async:false,dataType:"script"});
}

console.log("modulo <basicUtils.js> fue cargado OK!");




/*
EJEMPLO DE USO

//SE LLAMA AJAX A LA URL PASANDOLE EL DATA
getAjax_POST("url",{clave1:valor1, clave2,valor2},myCallback);

//SE EJECUTA LA FUNCION CALLBACK CDO RESPONDE
function myCallback(data){
};	  
*/