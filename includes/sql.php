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
  
  
  


  
  //*************************************************************
  //INICIO FUNCIONES PANEL DE CONTROL ***************************
  //*************************************************************
  


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

  
   //*********************************************************** 
  //FIN FUNCIONES PANEL DE CONTROL ***************************
  //************************************************************
 
  
 
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


function producto_categoria() {
  global $db;

    return find_by_sql("SELECT productos.id, productos.descripcion, IFNULL(media.file_name,'noimage.jpg') as nombre_foto ,productos.id_categorias as cat, productos.codigo,productos.nombre, productos.precio_compra,productos.precio_venta, productos.stock,productos.fecha_agregado, categorias.nombre as nombre_categoria, categorias.descripcion as proveedor, tipo.nombre as tipo "
            . "FROM `productos` inner join `categorias` on productos.id_categorias = categorias.id "
            . "left join `media` on productos.id_media = media.id inner join `tipo` on productos.id_tipo = tipo.id",TRUE);
  
}


function getBombas() {
   global $db;

     return find_by_sql("SELECT productos.id, productos.descripcion, IFNULL(media.file_name,'noimage.jpg') as nombre_foto ,productos.id_categorias as cat, productos.codigo,productos.nombre, productos.precio_compra,productos.precio_venta, productos.stock,productos.fecha_agregado, categorias.nombre as nombre_categoria, categorias.descripcion as proveedor "
             . "FROM `productos` inner join `categorias` on productos.id_categorias = categorias.id "
             . "left join `media` on productos.id_media = media.id inner join `tipo` on productos.id_tipo = tipo.id WHERE productos.id_tipo = 1",TRUE);
   
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
