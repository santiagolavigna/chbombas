console.log("PRINT BUTTON LOAD OK!!");
//crea un boton para imprimir la pagina o div seleccionado

function insertPrintButton($div){
    //lista con los elementos no imprimibles
    $noPrintList="a|.tdActions|#mySearchBar";

	$buttonPrint=$('<a class="btn btn-xs btn-success pull-right"><i class="glyphicon glyphicon-print"> PRINT</a>');
    $buttonPrint.click(function(){
    	$("body").addClass("printMode");
    	$div.addClass("imprimible");
        $noPrintList.split("|").forEach(function (noPr) {
            $(noPr).addClass("noPrint");
        });
        noPrintBrothers($div);

    	window.print();

        $("body").removeClass("printMode");
        $div.removeClass("imprimible");
        $noPrintList.split("|").forEach(function (noPr) {
            $(noPr).removeClass("noPrint");
        });
        removeNoPrintBrothers($div);
    });
    $div.append($buttonPrint);	
}


function insertPrintButtonSale($div){
    //lista con los elementos no imprimibles
    $noPrintList="a|.tdActions|#mySearchBar|#add_venta|#new_venta";
    
    
    $buttonPrint=$('<a class="btn btn-xs btn-success pull-right"><i class="glyphicon glyphicon-print"> PRINT</a>');
    $buttonPrint.click(function(){
    	$("body").addClass("printMode");
    	$div.addClass("imprimible");
        $noPrintList.split("|").forEach(function (noPr) {
            $(noPr).addClass("noPrint");
        });
        noPrintBrothers($div);

    	window.print();

        $("body").removeClass("printMode");
        $div.removeClass("imprimible");
        $noPrintList.split("|").forEach(function (noPr) {
            $(noPr).removeClass("noPrint");
        });
        removeNoPrintBrothers($div);
    });
    $div.append($buttonPrint);	
}

function noPrintBrothers($div){
    //noPrint hermanos ni tios ni bistios
    $s=$div    
    while($s.length>0){
        $s.siblings().addClass("noPrint");
        $s=$s.parent();
    }
}

function removeNoPrintBrothers($div){
    //noPrint hermanos ni tios ni bistios
    $s=$div    
    while($s.length>0){
        $s.siblings().removeClass("noPrint");
        $s=$s.parent();
    }
}

//IMPORTAMOS EL CSS PARA EL megaSelector
$("head").append("<link>");
css = $("head").children(":last");
css.attr({
rel: "stylesheet",
type: "text/css",
href: "libs/printButton/pb.css"
});
