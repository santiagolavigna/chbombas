window.onload = function ( ) {
//spinner
//$('#page-loader').fadeOut(50);

            
    //PONEMOS LOS BOTONES DE EDIT Y REMOVE EN LA TABLA
    $.getScript("libs/megaButtons/mb.js",function(){
      createTableButton("#table-productos","products|abmProducts","edit");
      createTableButton("#table-productos","products|actionProducts&baja","remove");    
    });
    
    

  //si estamos en pagina "products|productList", ejecutamos la funcion
	if(getCurrentPage()==="products|productsList"){
            
              //COLOCAMOS EL HOT SEARCH
            $.getScript("libs/hotSearch/hs.js",function(){
              hotSearch($('#table-productos'));
            });
                
    //recorremos cada fila para resaltar stock casi o faltante...
   /* $('#table-productos tbody tr').each(function(indice, elemento) {
        //STOCK
        var stock = $(this).find("[data-columnid='Stock']");
        var stock_valor = stock.html();
   
        //si el stock es > 15 seteamos color verde
        if (Number.isInteger(parseInt(stock_valor)) &&	stock_valor > 15){
            stock.empty();
            stock.append("<span class='label label-success'>"+stock_valor+"</span>");
        }                
        //si el stock es < 15 seteamos color rojo
        if (Number.isInteger(parseInt(stock_valor)) &&	stock_valor < 15){
            stock.empty();
            stock.append("<span class='label label-danger'>"+stock_valor+"</span>");
        }          
    });
*/
  //only for multiple_productsList  
  }else if((getCurrentPage()==="products|multiple_productsList")){
    
    //recorro cada elemento de la tabla y le seteo el checkbox y el precio
  /*  $('#table-productos tbody tr').each(function(indice, elemento) {
        var id = $(this).find('td[data-columnid="Id"]');
        var id_value = id.html();
        id.empty();
        $('<input />', { type: 'checkbox', name: 'check', id: id_value }).appendTo(id).click(function(){
            add_porcentaje($("input[name='%compra']").val(),'Precio_compra');
            add_porcentaje($("input[name='%venta']").val(),'Precio_venta');
        });
        
        
    });
    */

   
   $('select[name ="id_categorias"]').change(function(e){
       var cat = $('select[name ="id_categorias"]').children("option:selected").val().toLowerCase();
      //RECORRO LA TABLA, Y FILTRO POR CATEGORIA SELECCIONADA EN VARIABLE cat
                  $("#table-productos tbody tr").hide().filter(function() {
                     
                    return $(this).find("td[data-columnid='Cat']").text() === cat;
                  }).show();  
           
  	});
        
      
       
   
         
    //add %compra to multiple products selected (checkeds)
   $("input[name='%precio']").change(function(e){
       /* id_categoria = $('select[name ="id_categorias"]').children("option:selected").val() ;
        precio = $(this).val();
        add_porcentaje(precio,id_categoria,'Precio_compra','Precio_venta');
       */
    });
                
    
     $( "button[name='addPorcentajesAll']" ).click(function() {
         
      var cat_proveedor = $('select[name ="id_categorias"]').children("option:selected").text();
      
                if (confirm('¿QUIERES ACTUALIZAR LOS PRODUCTOS '+cat_proveedor+' ? la operación actualizara los precios')) {
                       console.log("actualizando productos");
                       

                              var precio = $("input[name='%precio']").val();
                              var id_categoria = $('select[name ="id_categorias"]').children("option:selected").val();
                             
                                   
                                   console.log(precio);
                                   console.log(id_categoria);
                                   console.log(cat_proveedor);
                                   
                                     getAjax_POST('_ajax.php?p=products|ajaxProducts&update_both',{precio:precio,categoria:id_categoria},function(data){                                   
                                       if(data['RESULT'])  console.log("Todos los productos "+cat_proveedor+" actualizados correctamente");                     
                                     });

                       
                    } else {
                       console.log("operacion cancelada");
                    }
     
              
                
           
                
      location.reload(true); 
    });
    
    
    
    

  }else if(getCurrentPage()==="products|abmProducts"){
      $('select[name="id_iva"]').attr('disabled', true);
  }


/*
//agregamos porcentaje en tabla, pasandole el porcentaje a sumar, y el id correspondiente al elemento a modificar
function add_porcentaje(data,idcat,compra,venta){
    if(data=="") data=0;
    $('#table-productos tr').each(function(indice, elemento,idcat){
        var nodo1 = $(this).find("td[data-columnid="+compra+"]");
        var nodo2 = $(this).find("td[data-columnid="+venta+"]");
        categoria = idcat ;
        
            if($('select[name ="id_categorias"]').children("option:selected").val() === categoria){
                var valorOriginal1 = nodo1.attr("data-valorOriginal"); 
                 var valorOriginal2 = nodo2.attr("data-valorOriginal");
                 console.log(valorOriginal1);
                if(valorOriginal1==null){
                    valorOriginal1= parseFloat((nodo1.html())).toFixed(2); 
                    nodo1.attr("data-valorOriginal",valorOriginal1);
                }
                 if(valorOriginal2==null){
                    valorOriginal2= parseFloat((nodo2.html())).toFixed(2); 
                    nodo2.attr("data-valorOriginal",valorOriginal2);
                }
                    var valorActual1 = valorOriginal1;
                    var r1 = (parseFloat(data) + parseFloat(valorActual1));
                    var porcentajeASumar1 = ((( parseFloat(data)* parseFloat(valorActual1) ) / 100)).toFixed(2);
                    var resultado1= (parseFloat(valorActual1) + parseFloat(porcentajeASumar1)).toFixed(2);

                    nodo1.html(resultado1);
                    nodo1.css("font-weight", "bolder");
                    
                    var valorActual2 = valorOriginal2;
                    var r2 = (parseFloat(data) + parseFloat(valorActual2));
                    var porcentajeASumar2 = ((( parseFloat(data)* parseFloat(valorActual2) ) / 100)).toFixed(2);
                    var resultado2 = (parseFloat(valorActual2) + parseFloat(porcentajeASumar2)).toFixed(2);

                    nodo2.html(resultado2);
                    nodo2.css("font-weight", "bolder");
                    
            }else{
                nodo1.html(nodo1.attr("data-valorOriginal"));
                nodo1.css("font-weight", "normal");
                
                nodo2.html(nodo2.attr("data-valorOriginal"));
                nodo2.css("font-weight", "normal");
            }
    });    
}*/

/* function add_check_all(){   
      head_id = $('#table-productos thead tr th:first-child').empty();
      $('<input />', { type: 'checkbox', name: 'check', id: 'check_all' }).appendTo(head_id).click(function(){
            if($(this).is(":checked")){
                        $('#table-productos tbody tr').each(function(indice, elemento){
                           if($(this).find("input[name='check']").is(":visible")){
                            $(this).find("input[name='check']").prop("checked","checked");
                            }
                        
                        });
                    }else{
                            $('#table-productos tbody tr').each(function(indice, elemento){
                                    if($(this).find("input[name='check']").is(":visible")){    
                                      $(this).find("input[name='check']").removeAttr("checked");
                                    }
                            });
                    }
        }); 
    }
*/
};