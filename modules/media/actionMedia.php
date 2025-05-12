<?php
  // Checkin What level user has permission to view this page
  page_require_level(1);

  if(isset($_REQUEST['altaModif'])){
  //SI ES ALTA O MODIFICACION
      if(!insertUpdateBBDD("media",$_POST))
        $session->msg('d','No se pudo realizar la accion correspondiente.');
      else{
          $session->msg('s','Operación realizada correctamente.'); 
      }

  }if(isset($_REQUEST['baja'])){
  //SI ES BAJA
     $find_media = find_by_id('media',(int)$_REQUEST['id']);
     $photo = new Media();
    if($photo->media_destroy($find_media['id'],$find_media['file_name'])){
      $session->msg("s","Se ha eliminado la foto.");
      redirect($_SESSION['PAGINA_ANTERIOR_2']);
        } else {
      $session->msg("d","Se ha producido un error en la eliminación de fotografías.");
      redirect($_SESSION['PAGINA_ANTERIOR_2']);
    }
  }

  redirect($_SESSION['PAGINA_ANTERIOR_2']);

 //end actions

 
?>