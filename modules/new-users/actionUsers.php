<?php
  // Checkin What level user has permission to view this page
  page_require_level(1);

  if(isset($_REQUEST['altaModif'])){
  //SI ES ALTA O MODIFICACION
      if(isset($_POST['password'])) 
        $_POST['password']=sha1($_POST['password']);

      if(!insertUpdateBBDD("users",$_POST))
        $session->msg('d','No se pudo realizar la accion correspondiente.');

  }if(isset($_REQUEST['baja'])){
  //SI ES BAJA
      $delete_id = delete_by_id('users',(int)$_REQUEST['id']);
      echo  '</br>delete_id: '.$delete_id;  
      echo  '</br>delete_id: '.(int)$_REQUEST['id'];   
      if($delete_id) $session->msg("s","Usuario eliminado");
      else $session->msg("d","Se produjo un error en la eliminación del usuario");
  
  }if(isset($_REQUEST['chPass'])){
  
    // Update user password
    $us = find_by_id('users',(int)$_POST['id']);
    print_r($_POST);   
    $_POST['Clave_Actual']=sha1($_POST['Clave_Actual']);
    if($_POST['Clave_Actual']===$us['password']){

        if($_POST['Clave_Nueva']===rj($_POST['Clave_Nueva'])){
            $sql = 'UPDATE users SET password="'.$_POST['Clave_Nueva'].'" WHERE id="'.sha1($_POST['id']).'"';
            if($db->query($sql)) $session->msg('s','Se actualizo la contraseña.');
            else $session->msg('d','ERROR al actualizar contraseña.');
        } else $session->msg('d','La clave no debe contener caracteres extraños.');
      
      echo "se cambia clave OK por ".$_POST['Clave_Nueva'];
    }else $session->msg('d','La clave actual es incorrecta.');
    redirect("?p=new-users|abmUser&id=".$_POST['id']);
  }

  redirect("?p=new-users|usersList");

 //end actions

 
?>