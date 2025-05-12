function delete_function(id,locationhref){
    var href = locationhref.concat(id);
    window.location=href;
}

function delete_button(objeto){
    var padre=objeto.parentNode.parentNode;
    padre.remove();
}


function suggetionClients1() {
     $('#sug_input_client_turnos').keyup(function(e) {
         var formDataClient = {
             'client_name' : $('input[name=Cliente]').val()
         };
         

         if(formDataClient['client_name'].length >= 1){
           // process the form
           $.ajax({
               type        : 'POST',
               url         : 'ajax.php',
               data        : formDataClient,
               dataType    : 'json',
               encode      : true
           })
               .done(function(data) {
                               
                
                   $('#result').html(data).fadeIn();
                   $('#result li').click(function() {
                   
                   $('#sug-mascotas option').remove();
                   $('#sug-mascotas').removeAttr("disabled");  
                   
                   
                   //tengo el nombre seleccionado en el input cliente
                    var nombre_apellido = $(this).text() ;
                                                        
                    $('#sug_input_client_turnos').val(nombre_apellido); 
                     
                    
                    $('#result').fadeOut(500);
                             
                           
                            //get masctoas from this client
                             var formDataClient123 = {
                                 //traer el id
                                'client_id' : $(this).attr("id")
                            };
                       
                            $('#setclient').val(formDataClient123.client_id);
                       
                             if(formDataClient123['client_id'].length >= 1){
                                 $.ajax({ 
                                    type        : 'POST',
                                    url         : 'ajax.php',
                                    data        : formDataClient123,
                                    dataType    : 'json',
                                    encode      : true,
                                    complete    : function(data) {
                                     
                                        var datos = data.responseJSON ;
                            
                                        var mascotas = datos.split("*");
                                        
                                        var o = new Option("seleccionar mascota", "999999");
                                         
                                            $(o).html("seleccionar mascota");
                                            $(o).attr("id","999999");
                                            $("#sug-mascotas").append(o);
                                 
                                       for (var i = 0; (i < ((mascotas.length / 3)-1)) ; i++) { 
                       
                                            var m = i*3 ;
                                            var o = new Option(mascotas[m+1]+" "+mascotas[m+2], mascotas[m]);
                                         
                                            $(o).html(mascotas[m+1]+" "+mascotas[m+2]);
                                            $(o).attr("id",mascotas[m]);
                                            $("#sug-mascotas").append(o);
                                      
                                       }
                                        
                                       
                                        
                                         $("#sug-mascotas").change(function(e){
                                             $('#setmascota').val($(this).val());
                                         });
                                         
                                 }
                                });
                                 
                             }
                            
                            
                    
                   });

                   $("#sug_input_client_turnos").blur(function(){
                     $("#result").fadeOut(500);
                   });
                   
                      

               });
               
         } else {
     
           $("#result").hide();

         };

         e.preventDefault();
     });
 }  

/*
  $('#sug-form').submit(function(e) {
      var formData = {
          'prod_name' : $('input[name=title]').val()
      };
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'ajax.php',
            data        : formData,
            dataType    : 'json',
            encode      : true
        })
        
            .done(function(data) {
                var node = $('#product_info:last-child');
                node.append(data).show();
                total();
                $('.datePicker').datepicker('update', new Date());
                
            }).fail(function() {
                $('#product_info').html(data).show();
            });
      $('#sug_input').val("");      
      e.preventDefault();
  });
*/
  
  
  function total(){
    $('#product_info input[name="quantity"]').change(function(e)  {
     var arr = [] ;  
      
    $('#product_info tr').each(function(indice, elemento) {
      
        $name = $(this).text();
     
         var formDataStock = {
             'id' : $(this).find('input[name=s_id]').val(),
             'qty' : $(this).find('input[name=quantity]').val()
         };
         
         arr.push(formDataStock);
           
            });
            
           $('#product_info tr').each(function(indice, elemento) {
           
            $price = +$(this).find('input[name=price]').val() || 0;
            $qty = +$(this).find('input[name=quantity]').val() || 0;
            $total = $qty * $price;
            $(this).find('input[name=total]').val($total.toFixed(2));
            
            arr.forEach(function(element) {
                
                 $.ajax({
                    type        : 'POST',
                    url         : 'ajax.php',
                    data        : element,
                    dataType    : 'json',
                    encode      : true,
                    complete: function (data) {
                        
                         var hijo = $("#product_info tr td#"+element.id);
                         var padre = hijo.parent();
                         var className = padre.find('input[name=quantity]').attr('class');

                        if (data.responseJSON === "True"){                            
                            if(className === "qtylow"){                                 
                                padre.find('input[name=quantity]').removeClass("qtylow").addClass("form-control");  
                            }
                          
                        }else{
                            if(className === "form-control"){                                
                                padre.find('input[name=quantity]').removeClass("form-control").addClass("qtylow"); 
                            }
                        }
                    }
                });
                
              });
          });
   
   });
  }
  


 function calculo_subtotal(){
 $('#ganancia').html('0'); 
  $('#totalsumado').html('0');
  
     
  $('#ventas1 tr').each(function(indice, elemento) {
      if($(this).find('td[name=subtotal1]').html() === "0"){
         
         
         //envio el id a buscar de venta
         var idventa = {
             'find_id_venta' : $(this).find('td[name=subtotal1]').attr('id')
         };
               
          $.ajax({
                    type        : 'POST',
                    url         : 'ajax.php',
                    data        : idventa,
                    dataType    : 'json',
                    async       : false,
                    encode      : true,
                    complete: function (data) {
                        
                    var totalObtenido = parseFloat(data.responseJSON[0][3]).toFixed(2) ;   
                     
                    if (data.responseJSON[0][0] == 0){
                        //seteo el subtotal
                        
                      $('#ventas1 tr').find('td[id='+idventa.find_id_venta+']').html(data.responseJSON[0][3]).css('font-weight','bolder');
                     
                     
                     
                       //obtengo el html y le sumo la ganancia
                        var valor = parseFloat($('#ganancia').html()) + (data.responseJSON[0][3] - data.responseJSON[0][2]);
                        $('#ganancia').html(valor.toFixed(2));
                    
                  
                    }else{
                        
                        //calculo porcentaje
                        $porcentaje = data.responseJSON[0][0] ;
                        
                        var porcentaje_sumar = ((( parseFloat($porcentaje) * parseFloat(data.responseJSON[0][3]) ) / 100)).toFixed(2);
                           
                       
                        var total = parseFloat(data.responseJSON[0][3]) + parseFloat(porcentaje_sumar);
                       
                       $('#ventas1 tr').find('td[id='+idventa.find_id_venta+']').html(total.toFixed(2)).css('font-weight','bolder');
                       
            
                        var valor = parseFloat($('#ganancia').html()) + ((data.responseJSON[0][3] - data.responseJSON[0][2]) +  parseFloat(porcentaje_sumar));
                        $('#ganancia').html(valor.toFixed(2));
                        
                        
                       //ganancia = ganancia + ((data.responseJSON[0][3] - data.responseJSON[0][2]) +  parseFloat(porcentaje_sumar));
                       // $('#ganancia').html( ((data.responseJSON[0][3] - data.responseJSON[0][2]) +  parseFloat(porcentaje_sumar)));
                    }
                   
                
               
                    } 
                   
                })
            }
            
           //venta manual directa
         if($(this).find('td[name=subtotal1]').html() === ""){
                
            var idventa1 = {
                'find_venta_directa' : $(this).find('td[name=subtotal1]').attr('id')
            };
            
             $.ajax({
                    type        : 'POST',
                    url         : 'ajax.php',
                    data        : idventa1,
                    dataType    : 'json',
                    async       : false,
                    encode      : true,
                    complete: function (data1) {
                        
                       var totalObtenido1 = parseFloat(data1.responseJSON[0][5]).toFixed(2) ;  
                        
                      $('#ventas1 tr').find('td[id='+idventa.find_id_venta+']').html(data1.responseJSON[0][5]).css('font-weight','bolder');
                      
                      
                      var i = $('#ganancia').html() ;
                      
                      //valor de ganancia
                      var val = (parseFloat(data1.responseJSON[0][5]).toFixed(2));
                      
                       i = parseFloat(parseFloat(i) + parseFloat(val)) ;
                      
                       $('#ganancia').html(i.toFixed(2));
               
                    }
                    
                    
                    
          });
            

         }
            
            
  });
  
  
  
  
 }
  

function format_data_clients(){
    
   var id = $('#tabla_clientes tr').find('td[name=id]').attr('id');
   var contador = 0;
   
   $('#tabla_clientes tr').each(function(indice, elemento) {
       if(contador > 0 && (id === ($(this).find('td[name=id]').attr('id')))){
           
          $(this).find('td[name=nombre]').html("///"); 
           $(this).find('td[name=apellido]').html("///"); 
            $(this).find('td[name=telefono]').html("///"); 
             $(this).find('div').remove(); 
       }else{
           if(id !== ($(this).find('td[name=id]').attr('id'))){
               id = ($(this).find('td[name=id]').attr('id'));
               contador = 0;
          }
          if(id === ($(this).find('td[name=id]').attr('id'))){
          contador++;
         }
          
       }
       
   })
}



function suggetion() {

     $('#sug_input').keyup(function(e) {

         var formData = {
             'product_name' : $('input[name=title]').val()
         };

         if(formData['product_name'].length >= 1){

           // process the form
           $.ajax({
               type        : 'POST',
               url         : 'ajax.php',
               data        : formData,
               dataType    : 'json',
               encode      : true
           })
               .done(function(data) {
                   $('#result').html(data).fadeIn();
                   $('#result li').click(function() {

                     $('#sug_input').val($(this).text());
                     $('#result').fadeOut(500);

                   });

                   $("#sug_input").blur(function(){
                     $("#result").fadeOut(500);
                   });

               });

         } else {

           $("#result").hide();

         };
         e.preventDefault();
     });

 }
 /*
  $('#sug-form').submit(function(e) {
      var formData = {
          'prod_name' : $('input[name=title]').val()
      };
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'ajax.php',
            data        : formData,
            dataType    : 'json',
            encode      : true
        })
        
            .done(function(data) {
                var node = $('#product_info:last-child');
                node.append(data).show();
                total();
                $('.datePicker').datepicker('update', new Date());
                
            }).fail(function() {
                $('#product_info').html(data).show();
            });
      $('#sug_input').val("");      
      e.preventDefault();
  });

*/

function calculo_caja(){
    
    //sumamos los subtotales al #totalsumado (caja) y restamos #egr

  $('#ventas1 tr').each(function(indice, elemento) {
      
   if($(this).find('td[name=subtotal1]').html() !== undefined){
       
             //obtengo los datos en negrita
             var i = parseFloat($(this).find('td[name=subtotal1]').html()).toFixed(2) ;
             //sumo a la CAJA #totalsumado
              var asd = (parseFloat($('#totalsumado').html()) + parseFloat(i)).toFixed(2);
              $('#totalsumado').html(asd);
            }
            
  });
      //ahora restamos los egresos de la variable #egr
          var egresos = parseFloat($('#egr').html()).toFixed(2);
          
           if(!isNaN(egresos)){
             var asd1 = (parseFloat($('#totalsumado').html()) - parseFloat(egresos)).toFixed(2);
              $('#totalsumado').html(asd1);
          }
}
  
  

  $(document).ready(function() {
      
    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $('.submenu-toggle').click(function () {
       $(this).parent().children('ul.submenu').toggle(200);
    });
    //suggetion for find clients of mascotas
    suggetion();
    suggetionClients1();
    // Callculate total ammont
    format_data_clients();
    total();
    calculo_subtotal();
    calculo_caja();

    $('.datepicker')
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
  });
  
function numeroAleatorio() {
  return Math.round(Math.random() * (10000 - 1) + 1);
}


//add stock to multiple products selected
$("th input[name='input_stock']").change(function(e){
    var value = parseInt($(this).val());
    
         $('#table_products tr').each(function(indice, elemento){
         
            if($(this).find("input[name='check']").is(":checked")){
                var valorActual = parseInt($(this).find("#stockqty").html());
                var resultado = valorActual + value ;
                
                $(this).find("#stockqty").html(resultado);
            }
         
         });
    
});

//add buy price percent to multiple products selected
$("th input[name='input_price']").change(function(e){
    var value =  parseFloat(($(this).val())).toFixed(2);
    
         $('#table_products tr').each(function(indice, elemento){
         
            if($(this).find("input[name='check']").is(":checked")){
                var valorActual = parseFloat(($(this).find("#buyprice").html())).toFixed(2);
                var r = (parseFloat(value) + parseFloat(valorActual)).toFixed(2) ;
                var porcentajeASumar = ((( parseFloat(value) * parseFloat(valorActual) ) / 100)).toFixed(2);
                var resultado = (parseFloat(valorActual) + parseFloat(porcentajeASumar)).toFixed(2);

                $(this).find("#buyprice").html(resultado);
            }
         
         });
    
});
 
 //add sale price percent to multiple products selected
$("th input[name='input_price_sale']").change(function(e){
    var value =  parseFloat(($(this).val())).toFixed(2);
    
         $('#table_products tr').each(function(indice, elemento){
         
            if($(this).find("input[name='check']").is(":checked")){
                
                var valorActual = parseFloat(($(this).find("#saleprice").html())).toFixed(2);
                var r = (parseFloat(value) + parseFloat(valorActual)) ;
                var porcentajeASumar = ((( parseFloat(value)* parseFloat(valorActual) ) / 100)).toFixed(2);
                var resultado = (parseFloat(valorActual) + parseFloat(porcentajeASumar)).toFixed(2);

                $(this).find("#saleprice").html(resultado);
            }
         
         });
    
});
 
 //checking all products in edit multiple
$("th input[name='check_all']").click(function(){

    if($(this).is(":checked")){
        $('#table_products tr').each(function(indice, elemento){
        $(this).find("input[name='check']").prop("checked","checked");
        });
    }else{
            $('#table_products tr').each(function(indice, elemento){
            $(this).find("input[name='check']").removeAttr("checked");
            });
    }
    
});

//edit multiple products reset button
$( "button[name='reset_changes']" ).click(function() {
    location.reload(true);
});




$("[name='add_sale1']").on('click', function(evt){
    evt.preventDefault();
    evt.stopPropagation();
    var b = true;
    
    $('#product_info tr').each(function(indice, elemento) {
            $id = $(this).find('input[name=s_id]').val();
            $name = $(this).text();
            $precio = $(this).find('input[name=price]').val();
            $qty = $(this).find('input[name=quantity]').val();
            $total = $(this).find('input[name=total]').val();        
            $date = $(this).find('input[name=date]').val();
           var hijo = $("#product_info tr td#"+$id);
           var padre = hijo.parent();
           var className = padre.find('input[name=quantity]').attr('class');
        if (className === "qtylow"){
            alert("No hay stock para "+$name);
            //$(this).remove();
            b = false;
        }
        if ($qty.length < 1 || $total.length < 1 ){
            alert("Hay campos vacios");
            b= false; 
        }    
        
    });
        
    if(b){
     add_sale(this); 
    }
   
});

function getRandomArbitrary(min, max) {
  return Math.random() * (max - min) + min;
}

function add_sale(objeto){
    var array = [] ;  
          
    var id_venta = getRandomArbitrary(5,99999);
      
    $('#product_info tr').each(function(indice, elemento) {
        
              var formDataSale = {
             'idp' : $(this).find('input[name=s_id]').val(),
             'name' : $(this).text(),
             'price' : $(this).find('input[name=price]').val(),
             'qty' : $(this).find('input[name=quantity]').val(),
             'total' : $(this).find('input[name=total]').val(),
             'date' : $(this).find('input[name=date]').val(),
             'tarjeta' : parseInt(($('#tarjeta').val())),
             'id_venta' : id_venta
             
              };
              
             array.push(formDataSale);
            });
       
       
                array.forEach(function(element) {
                   $.ajax({         
                           type        : 'POST',
                           url         : 'ajax.php',
                           data        : element,
                           dataType    : 'json',
                           encode      : true,
                           complete: function (data) {
                              if(data.responseJSON === "True"){
                                  //alert(element.name + " agregado");
                               }else{
                                   alert("Error al agregar " +element.name );
                               }
                           }
                    }).then(function(data){
                        //console.log("ready");
                    });
                });
               alert("Venta agregada");  
               var total = 0.00;
               $('#product_info tr').each(function(indice, elemento) {
               var valor = $(this).find('input[name=total]').val() || 0;   
              
               total = parseFloat(total) + parseFloat(valor); 
            }); 
            
            var porcentaje_sumar = 0;
            if($('#tarjeta').val() === ""){
                //console.log("no hay porcentaje a sumar");
                var n_input = 0;
            }else{
                 var n_input =  parseFloat(($('#tarjeta').val())).toFixed(2);
                //n_input tiene el valor de porcentaje a sumar
                
                var porcentaje_sumar = ((( parseFloat(n_input) * parseFloat(total) ) / 100)).toFixed(2);
            }
           
                total = parseFloat(total) + parseFloat(porcentaje_sumar);
                
           
                
                ($('#totalmax').val((total.toFixed(2))));
            
            $('#add_sale1').attr("disabled", "disabled");
            
            $('#product_info tr').each(function(indice, elemento) {
                
            $(this).find('#delete_button').attr("disabled", "disabled");
            });
            
            $('#sug_input').attr("disabled","disabled");
           //$(location).attr('href',"add_sale.php");

}

function buscarPorClaveValor($tabla,$clave,$valor){
    B=null;
    $tabla.forEach(function(el){
        if(el[$clave]==$valor){
          B=el;
        }
    });
    return B;
}