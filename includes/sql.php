<?php
 // require_once('includes/load.php');
  
  /*--------------------------------------------------------------*/
  /****************** FUNCIONES DEL SISTEMA************************/
  /*--------------------------------------------------------------*/
  
  //get join entre dos tablas
  //requiere que en la bd, el campo foraneo se llame id_(nombre de la tabla padre)
  //ejemplo: get_join(productos,categorias)
  //producto contiene el campo id_categorias
  
  function get_join($table,$table2) {
   global $db;
   if(tableExists($table))
   {
     if(tableExists($table2)){
         
     return find_by_sql("SELECT * FROM ".$db->escape($table)." "
             . "INNER JOIN ".$db->escape($table2).""
             . " ON ".$db->escape($table).".id_".$db->escape($table2).""
             . " = ".$db->escape($table2).".id" );
     }
     
   }
}


 /*--------------------------------------------------------------*/
  /* Funcion para actualizar un campo en la bd (requiere como primer parametro
   * nombre de la tabla, segundo ID, tercero el nombre del campo en la BD que
   * sera actualizado y por ultimo el valor a actualizar.
   */
  /*--------------------------------------------------------------*/
  function update_campo($tabla,$id,$campo,$valor){
    global $db;
        $sql = "UPDATE `{$tabla}` SET `{$campo}` = '{$valor}' WHERE `{$tabla}`.`id` = {$id}";
        Utils::log($sql);
        $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

   function update_all_products($precio,$categoria){
    global $db;
    $sql ="";
    $value = (intval($precio) / 100);
    
        $sql = "UPDATE `productos` SET `precio_compra`=round(precio_compra*'{$value}'+
                                precio_compra,2),`precio_venta`=round(precio_venta*'{$value}'+
                                precio_venta,2)   where productos.id_categorias = '{$categoria}'";

        
        Utils::log($sql);
        $result = $db->query($sql);
    return($db->affected_rows() >= 1 ? true : false);

  }
  

  
  /*obtenemos sugerencia de productos en sales|salesList
   * from ajaxSales
   *
   */
  
   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT nombre FROM productos WHERE nombre like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }
  
   /*obtenemos el producto indicado sea por codigo o nombre
    * from ajaxSales
    * 
    */

  function find_all_product_info_by_title($name){
    global $db;
    $sql  = "SELECT * FROM productos ";
    $sql .= " WHERE nombre ='{$name}' or codigo ='{$name}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }
  
  
  
  /*insertamos factura y retornamos el id si todo salio bien, sino retorna vacio*/
  function insert_factura($cliente,$modo_pago,$tipo_factura,$vendedor){
    Utils::log("COMIENZA EL ALTA DE LA FACTURA PARA EL CLIENTE ".print_r($cliente,true).'/n');      
    $id_factura_insertada = "";  
    $fecha = make_date();  
      
    global $db;

    //SI NO ES CONSUMIDOR FINAL
    if($cliente!=-1){
      $sql = "INSERT INTO `facturas`(`id_cliente`, `fecha`, `id_modo_pago`, `cae`, `tipo`,`vendedor`) VALUES ('{$cliente}','{$fecha}','{$modo_pago}','-1','{$tipo_factura}','{$vendedor}')";
    }else{
      $sql = "INSERT INTO `facturas`(`fecha`, `id_modo_pago`, `cae`, `tipo`,`vendedor`) VALUES ('{$fecha}','{$modo_pago}','-1','{$tipo_factura}','{$vendedor}')";
    }
     $db->query($sql);
     if($db->affected_rows() === 1){
            //si se dio de alta la factura, obtenemos el ultimo id del registro
            $query = "SELECT MAX(id) AS id FROM `facturas` LIMIT 1";
            $last_id = $db->query($query)->fetch_object()->id; 
            $id_factura_insertada = $last_id;         
            }else{
                Utils::log_facturacion_err(print_r("fallo al dar de alta una factura para el cliente: ".$cliente,true).'/n');
                Utils::log_facturacion_err("CONSULTA: ".$sql,true.'/n');                
            }
      Utils::log_facturacion("INSERTANDO UNA NUEVA FACTURA",true.'/n'); 
      Utils::log_facturacion("CONSULTA: ".$sql,true.'/n');
      Utils::log_facturacion("ID factura: ".print_r($id_factura_insertada,true).'/n'); 
      Utils::log_facturacion("***************************************************".'/n');
     return $id_factura_insertada;       
  }
  
  
  
   /*insertamos detalle y retornamos true si todo sale bien, sino false*/
  function insert_detalles($id_factura,$array_productos,$descuento){
        
     $b = FALSE;  
     global $db;
     $array = json_decode($array_productos);
     
           //insertamos los detalles detalle
           $query = "INSERT INTO `detalles`"
                   . "(`id_facturas`, `id_productos`, `cantidad`, `precio_compra`, `precio_venta`, `total`, `descuento`, `descuento_especial`) "
                   . "VALUES ";
            
            $values = "";
            foreach ($array as $obj) {
                $TOTAL=$obj->precio_venta*$obj->cantidad;
                $values .= "('{$id_factura}','{$obj->id}','{$obj->cantidad}','{$obj->precio_compra}','{$obj->precio_venta}','{$TOTAL}','{$descuento}','{$obj->descuento_especial}')";
                $values .= ",";
            }
            
            $values = trim($values, ',');
            
            $query .= $values;
              Utils::log_facturacion("INSERTANDO UN NUEVO DETALLE",true.'/n');
              Utils::log_facturacion("CONSULTA: ".$query,true.'/n');
              
             $db->query($query);  
             
            //si el detalle fue insertado
            if($db->affected_rows() >= 1){  
                
                
                    //si se dio de alta el detalle, obtenemos el ultimo id del registro
                      $query = "SELECT MAX(id) AS id FROM `detalles` LIMIT 1";
                     $id_detalle = $db->query($query)->fetch_object()->id;
                     Utils::log_facturacion("ID detalle: ".print_r($id_detalle,true).'/n');               
                     Utils::log_facturacion("***************************************************".'/n');
                
                        //descontamos stock                    
                        if(update_product_qty($array)){
                            $b = true;
                            return $b;
                        }else{
                                                       
                            /*borrar insert y factura*/
                            delete_by_id('detalles', $id_detalle);
                            delete_by_id('facturas', $id_factura);
                        }     
                        
                        
                }else{
                    //si el detalle no fue insertado, borramos la factura asociada
                    delete_by_id('facturas', $id_factura);
                }     
        
        
    return $b;
  }
  
  
  /* update saldo cliente deudor */
  function update_saldo_deudor($id_cliente){
    global $db;
    $B=true;
        $id  = (int)$id_cliente;
        $sql = "update clientes set saldo = saldo - (SELECT total_factura.total FROM ( SELECT SUM((detalles.precio_venta * detalles.cantidad ) - ((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100)) - (SUM( (detalles.precio_venta * detalles.cantidad ) - (((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100))) * sum(DISTINCT detalles.descuento) /100) AS total FROM detalles INNER JOIN facturas on detalles.id_facturas = facturas.id INNER JOIN modo_pago on facturas.id_modo_pago = modo_pago.id INNER JOIN clientes c on c.id = facturas.id_cliente where facturas.id_cliente = '{$id_cliente}' group by detalles.id_facturas DESC LIMIT 1 ) as total_factura)
where clientes.id = '{$id_cliente}'";
          Utils::log_facturacion("ACTUALIZANDO SALDO DE CLIENTE",true.'/n'); 
          Utils::log_facturacion("CONSULTA: ".$sql,true.'/n');
          Utils::log_facturacion("ID Cliente: ".print_r($id,true).'/n'); 
          Utils::log_facturacion("***************************************************".'/n');
          $db->query($sql);
          
            if($db->affected_rows() === 0){
            $B = false;
            Utils::log_facturacion_err(print_r("fallo al actualizar saldo deudor: ".$id,true).'/n');
            Utils::log_facturacion_err("CONSULTA: ".$sql,true.'/n');
            Utils::log_facturacion_err("ID cliente: ".$id.'/n'); 

        }
    Utils::log_facturacion("***************************************************".'/n');
    return $B;
    
  }
  
  
    
  /* update saldo cliente acreedor */
  function update_saldo_acreedor($id_cliente,$saldo){
    global $db;
    $B = true;
        $id  = (int)$id_cliente;
        $sql = "UPDATE clientes SET saldo=saldo + '{$saldo}' WHERE id = '{$id}'";
          Utils::log_facturacion("ACTUALIZANDO SALDO DE CLIENTE",true.'/n'); 
          Utils::log_facturacion("CONSULTA: ".$sql,true.'/n');
          Utils::log_facturacion("ID Cliente: ".print_r($id,true).'/n'); 
          $db->query($sql);
        
         if($db->affected_rows() === 0){
            $B = false;
            Utils::log_facturacion_err(print_r("fallo al actualizar saldo acreedor: ".$id,true).'/n');
            Utils::log_facturacion_err("CONSULTA: ".$sql,true.'/n');
            Utils::log_facturacion_err("ID cliente: ".$id.'/n'); 

        }
    Utils::log_facturacion("***************************************************".'/n');
    return $B;
  }
  
  
  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($array){
    global $db;
    $B = true;
    
    foreach ($array as $obj) {
       $sql = "UPDATE productos SET stock=stock - '{$obj->cantidad}' WHERE id = '{$obj->id}'";
      Utils::log_facturacion("ACTUALIZANDO PRODUCTO",true.'/n'); 
      Utils::log_facturacion("CONSULTA: ".$sql,true.'/n');
      Utils::log_facturacion("ID PRODUCTO: ".print_r($obj->id,true).'/n'); 
      
       $db->query($sql);
        if($db->affected_rows() === 0){
            $B = false;
            Utils::log_facturacion_err(print_r("fallo al actualizar el producto: ".$obj->id,true).'/n');
            Utils::log_facturacion_err("CONSULTA: ".$sql,true.'/n');
            Utils::log_facturacion_err("ID PRODUCTO: ".print_r($obj->id,true).'/n'); 
            break;
        }
    }
    Utils::log_facturacion("***************************************************".'/n');
    return $B;
    
  }
  
  
  
  
   /*--------------------------------------------------------------*/
  /* Function for Update product quantity +++
  /*--------------------------------------------------------------*/
  function update_product_qty_suma($array){
    global $db;
    $B = true;
    
    foreach ($array as $obj) {
       $sql = "UPDATE productos SET stock=stock + '{$obj['cantidad']}' WHERE id = '{$obj['id_producto']}'";
      Utils::log_facturacion("ACTUALIZANDO PRODUCTO",true.'/n'); 
      Utils::log_facturacion("CONSULTA: ".$sql,true.'/n');
      Utils::log_facturacion("ID PRODUCTO: ".print_r($obj['id_producto'],true).'/n'); 
      
       $db->query($sql);
        if($db->affected_rows() === 0){
            $B = false;
            Utils::log_facturacion_err(print_r("fallo al actualizar el producto: ".$obj['id_producto'],true).'/n');
            Utils::log_facturacion_err("CONSULTA: ".$sql,true.'/n');
            Utils::log_facturacion_err("ID PRODUCTO: ".print_r($obj['id_producto'],true).'/n'); 
            break;
        }
    }
    Utils::log_facturacion("***************************************************".'/n');
    return $B;
    
  }
  
  
    //obtenemos el total de una factura
function get_total_by_id_factura($id){
  global $db;

    $sql     ="SELECT SUM(detalles.total) AS total FROM detalles "
            . "INNER JOIN facturas on detalles.id_facturas = facturas.id "
            . "WHERE facturas.id = '{$id}'";
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
  
}
  
  
  //*************************************************************
  //INICIO FUNCIONES PANEL DE CONTROL ***************************
  //*************************************************************
  
  
  /*--------------------------------------------------------------*/
 /* get caja diaria
/*--------------------------------------------------------------*/

 //obtenemos la caja
function get_caja_today($year,$month,$today){
  global $db;

    $sql     ="SELECT SUM(totals.total) as total from "
            . "( SELECT DISTINCT detalles.id_facturas, "
            . "SUM((detalles.precio_venta * detalles.cantidad ) - ((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100)) - (SUM( (detalles.precio_venta * detalles.cantidad ) - (((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100))) * sum(DISTINCT detalles.descuento) /100) AS total "
            . "FROM detalles INNER JOIN facturas on detalles.id_facturas = facturas.id "
            . "INNER JOIN modo_pago on facturas.id_modo_pago = modo_pago.id "
            . "WHERE DATE_FORMAT(facturas.fecha, '%Y-%m-%d' ) = '{$year}-{$month}-{$today}' AND modo_pago.id = '1' "
            . "group by detalles.id_facturas ) as totals";
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
  
}
//sumamos a la caja movimientos en efectivo si los hay
function get_movimientos_today($year,$month,$today){
  global $db;

    $sql     ="SELECT SUM(mc.monto) AS total "
            . "FROM movimiento_cliente mc "
            . "WHERE DATE_FORMAT(mc.fecha, '%Y-%m-%d' ) = '{$year}-{$month}-{$today}' AND mc.id_modo_pago = '1'";
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
}

//restamos a la caja egresos si los hay
function get_egresos_today($year,$month,$today){
  global $db;

    $sql     ="SELECT SUM(mc.monto) AS total "
            . "FROM egresos mc "
            . "WHERE DATE_FORMAT(mc.fecha, '%Y-%m-%d' ) = '{$year}-{$month}-{$today}'";
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
}


/*--------------------------------------------------------------*/
  /* ultimos productos añadidos
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.nombre,p.precio_compra,p.precio_venta,c.nombre AS categorie";
   $sql  .= " FROM productos p";
   $sql  .= " LEFT JOIN categorias c ON c.id = p.id_categorias";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
    Utils::log(print_r("find recent: ".$sql,true).'/n');
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* productos con mayor venta en el ultimo mes
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit,$year,$month){
   global $db;
   $sql  = "SELECT p.nombre, SUM(s.cantidad) AS totalSold, s.precio_venta - s.precio_compra AS totalQty";
   $sql .= " FROM detalles s";
   $sql .= " LEFT JOIN productos p ON p.id = s.id_productos ";
   $sql .= " LEFT JOIN facturas f ON s.id_facturas = f.id ";
   $sql  .= "WHERE DATE_FORMAT(f.fecha, '%Y-%m' ) = '{$year}-{$month}'";
   $sql .= " GROUP BY s.id_productos";
   $sql .= " ORDER BY SUM(s.cantidad) DESC LIMIT ".$db->escape((int)$limit);
    Utils::log(print_r("find high: ".$sql,true).'/n');
   return $db->query($sql);
 }
  
/*--------------------------------------------------------------*/
 /* ultimos 5 clientes con mayor deuda
 /*--------------------------------------------------------------*/
 
 function get_deudores(){
   global $db;

    $sql  ="SELECT * FROM clientes ";
    $sql .= " GROUP BY clientes.id";
    $sql .= " ORDER BY SUM(clientes.saldo) ASC LIMIT 5";
    $result = $db->query($sql);
     return($result);
 }
 
  
   //*********************************************************** 
  //FIN FUNCIONES PANEL DE CONTROL ***************************
  //************************************************************
 
 
 /************************
  * FUNCIONES FACTURAS
  */

  function get_facturas(){
     global $db;
     $sql = "SELECT detalles.id_facturas as id, f.fecha, mp.nombre as modopago, IFNULL(c.nombre,'Consumidor Final') as cliente, "
             . "SUM( (detalles.precio_venta * detalles.cantidad ) - ((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100)) - ((SUM( (detalles.precio_venta * detalles.cantidad ) - ((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100)) * detalles.descuento )/100) as subtotal,"
             . " f.vendedor "
             . "FROM `detalles` "
             . "LEFT JOIN productos p ON p.id = detalles.id_productos "
             . "LEFT JOIN facturas f on detalles.id_facturas = f.id "
             . "LEFT JOIN clientes c on c.id = f.id_cliente "
             . "LEFT JOIN modo_pago mp on f.id_modo_pago = mp.id "
             . "GROUP by detalles.id_facturas DESC";
     return(find_by_sql($sql));
 }
 
 
 function get_facturas_by_idcliente($id){
     global $db;
     $sql = "SELECT "
             . "detalles.id_facturas as id, f.fecha, mp.nombre as modopago, SUM( (detalles.precio_venta * detalles.cantidad ) - ((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100)) - ((SUM( (detalles.precio_venta * detalles.cantidad ) - ((detalles.precio_venta * detalles.cantidad )* detalles.descuento_especial /100)) * detalles.descuento )/100) as subtotal "
             . "FROM `detalles` "
             . "INNER JOIN productos p ON p.id = detalles.id_productos "
             . "INNER JOIN facturas f on detalles.id_facturas = f.id "
             . "INNER JOIN clientes c on c.id = f.id_cliente "
             . "INNER JOIN modo_pago mp on f.id_modo_pago = mp.id "
             . "WHERE c.id = '{$id}'"
             . "GROUP BY detalles.id_facturas";
     return(find_by_sql($sql));
 }

 
 function get_movimientos_by_idcliente($id){
     global $db;
     $sql = "SELECT "
             . "mc.id, mc.fecha, mp.nombre as modopago, mc.monto, mc.concepto, mc.detalles "
             . "FROM `movimiento_cliente` mc "
             . "INNER JOIN modo_pago mp on mc.id_modo_pago = mp.id "
             . "WHERE mc.id_cliente = '{$id}'";
     return(find_by_sql($sql));
 }
 
 
 function get_facturas_by_idfactura($id){
     global $db;
     $sql = "SELECT "
             . "detalles.id_facturas as id, c.id as cliente, f.fecha, mp.nombre as modopago,mp.id as id_modo_pago ,p.id as id_producto, p.nombre as producto, detalles.cantidad, detalles.precio_venta, ((detalles.precio_venta * detalles.cantidad) - (((detalles.precio_venta * detalles.cantidad) * detalles.descuento_especial)/100) )  as total, detalles.descuento, detalles.descuento_especial, IFNULL(c.nombre,'Consumidor Final') as nombre_cliente "
             . "FROM `detalles` "
             . "LEFT JOIN productos p ON p.id = detalles.id_productos "
             . "LEFT JOIN facturas f on detalles.id_facturas = f.id "
             . "LEFT JOIN clientes c on c.id = f.id_cliente "
             . "LEFT JOIN modo_pago mp on f.id_modo_pago = mp.id "
             . "WHERE f.id = '{$id}'";
             utils::log($sql);
     return(find_by_sql($sql));
 }
 
 
 function reporte($fecha_inicio,$fecha_final){
     global $db;
     
     $sql = "SELECT 
             detalles.id_facturas as id, c.id as cliente, f.fecha, 
             mp.nombre as modopago,mp.id as id_modo_pago ,
             p.id as id_producto, p.nombre as producto, 
             sum(detalles.cantidad) as cantidad,detalles.precio_compra, 
             detalles.precio_venta, SUM(detalles.total - (detalles.total*(detalles.descuento * 0.01))) as total, 
             c.nombre as nombre_cliente,
             ca.descripcion as proveedor,
             ((detalles.precio_venta - detalles.precio_compra)*sum(detalles.cantidad)) as ganancia
             FROM `detalles` 
             LEFT JOIN productos p ON p.id = detalles.id_productos 
             LEFT JOIN facturas f on detalles.id_facturas = f.id 
             LEFT JOIN clientes c on c.id = f.id_cliente 
             LEFT JOIN modo_pago mp on f.id_modo_pago = mp.id
             LEFT JOIN categorias ca on p.id_categorias = ca.id
             WHERE DATE_FORMAT(f.fecha, '%Y-%m-%d' ) BETWEEN '{$fecha_inicio}' AND '{$fecha_final}'
             GROUP BY p.id    
             ORDER BY f.fecha DESC ";
     utils::log($sql);
     return(find_by_sql($sql));
 }
 
 
 function insertarMovimiento($cliente,$modo_pago,$monto,$concepto,$detalles){
     global $db;
     $b = false;
     $fecha = make_date();
     
     $sql = "INSERT INTO `movimiento_cliente`(`id_cliente`, `fecha`, `id_modo_pago`, `monto`, `concepto`, `detalles`) "
             . "VALUES ('{$cliente}','{$fecha}','{$modo_pago}','{$monto}','{$concepto}','{$detalles}')";    
             
             Utils::log_facturacion("INSERTANDO MOVIMIENTO",true.'/n'); 
             Utils::log_facturacion("CONSULTA: ".$sql,true.'/n');
             Utils::log_facturacion("ID CLIENTE: ".print_r($cliente,true).'/n');  
             
        $result = $db->query($sql); 
   
      if($result){
          Utils::log_facturacion("***************************************************".'/n');    
          if(update_saldo_acreedor($cliente, $monto)){$b = true;}
      }else{
          Utils::log_facturacion_err("************ERROR AL INSERTAR EL MOVIMIENTO****************".'/n');
          Utils::log_facturacion_err("CONSULTA: ".$sql,true.'/n');
      }
    
      return $b;
 }
 
  /*
   * FIN FUNCION FACTURAS
   */
 
 
  /*--------------------------------------------------------------*/
  /****************** FUNCIONES BASE  *****************************/
  /*--------------------------------------------------------------*/
  
/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}


//obtengo las promociones
function get_promociones() {
   global $db;
     return find_by_sql("select pr.id_producto as id, pr.cantidad as cantidad, pr.descuento as descuento "
             . "from productos p inner join promocion pr on pr.id_producto = p.id "
             . "where p.stock >= pr.cantidad");   
}



function producto_categoria() {
   global $db;

     return find_by_sql("SELECT productos.id, productos.descripcion, IFNULL(media.file_name,'noimage.jpg') as nombre_foto ,productos.id_categorias as cat, productos.codigo,productos.nombre, productos.precio_compra,productos.precio_venta, productos.stock,productos.fecha_agregado, categorias.nombre as nombre_categoria, categorias.descripcion as proveedor "
             . "FROM `productos` inner join `categorias` on productos.id_categorias = categorias.id "
             . "left join `media` on productos.id_media = media.id",TRUE);
   
}


function promociones() {
   global $db;

     return find_by_sql("SELECT p.id,prod.codigo,prod.nombre,p.cantidad,p.descuento "
                      . "FROM `promocion` p inner join productos prod on prod.id = p.id_producto");
   
}

function find_egresos() {
   global $db;
  

     return find_by_sql("SELECT `id`, `descripcion`, `monto`, `fecha`, `vendedor` FROM `egresos` ORDER BY egresos.fecha DESC");

}

function find_egresos_montos(){
    return find_by_sql("SELECT `id`,  sum(`monto`) as monto, `fecha` FROM `egresos`GROUP BY egresos.fecha  ORDER BY egresos.fecha DESC  ");
}

function find_productos_categorias(){
return find_by_sql("SELECT productos.id,productos.codigo,productos.nombre,"
        . "productos.precio_compra,productos.precio_venta,productos.stock,"
        . "productos.id_categorias,productos.id_iva,productos.fecha_agregado,"
        . "categorias.descripcion FROM `productos` "
        . "INNER join categorias "
        . "on productos.id_categorias = categorias.id");
}

/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql,$data = false)
{

  global $db;
  $result = $db->query($sql);
    
  if($data){
     $result_set = $db->while_loop($result,$data);
  }else{
     $result_set = $db->while_loop($result);  
  }
  
 return $result_set;
}


/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}


/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_detalle_by_id_without_limit($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $consulta = "SELECT mp.id as id_modo_pago,f.id_cliente as id_cliente, detalles.id_productos as id_productos, detalles.cantidad as cantidad, detalles.total as total, detalles.id_facturas as id_factura ,detalles.id "
          . "FROM detalles "
          . "LEFT JOIN facturas f on f.id = detalles.id_facturas "
          . "LEFT JOIN modo_pago mp on mp.id = f.id_modo_pago "
                  . "WHERE detalles.id_facturas='{$db->escape($id)}'";
          return find_by_sql($consulta);
        
     }
     return null;
}

/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  
        $registro = "";
        //backup in log_deleteds
           if(tableExists($table)){  
              $sql = "SELECT * FROM ".$db->escape($table);
              $sql .= " WHERE id=". $db->escape($id);
              $sql .= " LIMIT 1";
              $registro = find_by_sql($sql);
           }
          
    if(tableExists($table)){
          $sql = "DELETE FROM ".$db->escape($table);
          $sql .= " WHERE id=". $db->escape($id);
          $sql .= " LIMIT 1";          
          $db->query($sql);
             Utils::log_deleteds("ELIMINANDO ".$table,true.'/n'); 
             Utils::log_deleteds("CONSULTA: ".$sql,true.'/n');
             Utils::log_deleteds("ID: ".print_r($id,true).'/n'); 

             if($db->affected_rows() === 1){
              Utils::log_deleteds("//////////////////REGISTRO ELIMINADO///////////////////////////////// ",true.'/n'); 
              Utils::log_deleteds(print_r($registro,true).'/n');
               Utils::log_deleteds("/////////////////////////////////////////////////////////////////// ",true.'/n'); 
              Utils::log_deleteds("***************************************************".'/n');    
              return true;
          }else{return false;}
    }else return false;
}





/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_detalle_by_id($table,$id)
{
  global $db;
  
        $registro = "";
        //backup in log_deleteds
           if(tableExists($table)){  
              $sql = "SELECT * FROM ".$db->escape($table);
              $sql .= " WHERE id_facturas=". $db->escape($id);
              $registro = find_by_sql($sql);
           }
          
    if(tableExists($table)){
          $sql = "DELETE FROM ".$db->escape($table);
          $sql .= " WHERE id_facturas=". $db->escape($id);        
          $db->query($sql);
             Utils::log_deleteds("ELIMINANDO ".$table,true.'/n'); 
             Utils::log_deleteds("CONSULTA: ".$sql,true.'/n');
             Utils::log_deleteds("ID: ".print_r($id,true).'/n'); 

             if($db->affected_rows() >= 1){
              Utils::log_deleteds("//////////////////REGISTRO ELIMINADO///////////////////////////////// ",true.'/n'); 
              Utils::log_deleteds(print_r($registro,true).'/n');
               Utils::log_deleteds("/////////////////////////////////////////////////////////////////// ",true.'/n'); 
              Utils::log_deleteds("***************************************************".'/n');    
              return true;
          }else{return false;}
    }else return false;
}







/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }

  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','Este nivel de usaurio esta inactivo!');
           redirect('?p=home',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('?p=home', false);
        endif;

     }     
     
function insertUpdateBBDD($TABLE,$DATA){
    //RECIBE EN DATA UN ARRAY ASOCIATIVO CON LOS CAMPOS A CARGAR EN BBDD
    //SI EXISTE EL CAMPO ID HACE UN UPDATE, SINO UN INSERT INTO..
    
       global $db;
       global $session;
       
       $action = "";
       if(!isset($DATA['id'])){
              $action = "agregado";
              $q = "INSERT INTO ".$TABLE." (";
              foreach ($DATA as $k=>$v){
                $q.=$k.',';
              }
              $q=substr($q,0,-1);
              $q .=") VALUES (";
              foreach ($DATA as $k=>$v){
                $q.='"'.rj($v).'",';
              }
              $q=substr($q,0,-1);
              $q .=");";
        }else{
              $action = "actualizado";
              $q = "UPDATE ".$TABLE." SET ";
              foreach ($DATA as $k=>$v){
                $q.=$k.'="'.$v.'", ';
              }
              $q=substr($q,0,-2);
              $q.=' WHERE id="'.rj($DATA['id']).'";';
        }
        $isOK=$db->query($q);
        Utils::log(print_r($q,true).'/n');
        
        if($isOK){
            $session->msg("s","Regristro "+ $action +" con exito");
        }
        
        return $isOK;
 }

?>
