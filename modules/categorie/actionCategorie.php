<?php
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  if(isset($_REQUEST['altaModif'])){
  //SI ES ALTA O MODIFICACION
      if(!insertUpdateBBDD("categorias",$_POST))
        $session->msg('d','No se pudo realizar la accion correspondiente.');
      else{
        $session->msg('s','Operación realizada correctamente.');  
      }

  }
  
  if(isset($_REQUEST['baja'])){
  //SI ES BAJA
      $delete_id = delete_by_id('categorias',(int)$_REQUEST['id']);
      echo  '</br>delete_id: '.$delete_id;  
      echo  '</br>delete_id: '.(int)$_REQUEST['id'];   
      if($delete_id) $session->msg("s","Categoria eliminada");
      else $session->msg("d","Se produjo un error en la eliminación de la categoria");
  } 
  
  redirect("?p=categorie|categorieList");
 //end actions