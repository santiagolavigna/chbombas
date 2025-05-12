<?php

//GLOBALS VARS: $session, $msg, $user

//HACE EL INCLUDE DE LOS LAYOUTS BASE Y PAGINA QUE CORRESPONDA,

//EN ESTE ARCHIVO DEBERIA IR EL CODIGO PHP QUE SEA COMUN A TODAS LAS PAGINAS
//Y YA NO HABRIA CODIGO REPETIDO, (SE SUPONE NO?)

  ob_start();
  //if aca para validar.....
  if($_REQUEST["p"] === "data"){
   require_once('includes/load_for_api.php');
   echo json_encode(producto_categoria());
  }else{
   require_once('includes/load.php');   
  

  //SETEAMOS LAS VARIABLES GLOBALES MAS USADAS
  $session=new Session();
  $msg=$session->msg();
  if($session->isUserLoggedIn(true)) $user = current_user();

  $thisPage="?p=".$_REQUEST["p"]; //CONTIENE LA URL COMPLETA ?p=module|page
  
  //en el request puede venir p=nombrePagina que cargaria de layout/ o del raiz /
  //o puede venir p=nombreModulo|nombrePagina y cargaria de modules/elModuloCorrespondiente/
  $REQ=explode("|", str_replace("?p=","",$thisPage) );//PAGINA
  if(sizeof($REQ)==2){
      $onlyModule=$REQ[0];
      $onlyPage=$REQ[1];
  } else $onlyPage=$REQ[0];

  //GUARDAMOS LA PAGINA ANTERIOR
  if($session->isUserLoggedIn(true)) {
      if(isset($_SESSION['PAGINA_ANTERIOR'])) $_SESSION['PAGINA_ANTERIOR_2']=$_SESSION['PAGINA_ANTERIOR'];
      if(isset($_SESSION['PAGINA_ACTUAL'])) $_SESSION['PAGINA_ANTERIOR']=$_SESSION['PAGINA_ACTUAL'];
      $_SESSION['PAGINA_ACTUAL']=$thisPage;
  }

  //INCLUIMOS EL HEAD
  include_once('layouts/_head.php');

  //AHORA HACEMOS EL INCLUDE DE LOS LAYOUTS QUE CORRESPONDA
  if($session->isUserLoggedIn(true)) {    
    include_once('layouts/_header.php');
    include_once('layouts/_sidebarGenerator.php');
    //include_once('layouts/_sidebar.php');
  }

  //ESTE SERIA EL MANEJADOR DE PAGINAS, LEE EL REQUEST Y SEGUN P CARGA LA PAGINA
  if($session->isUserLoggedIn(true)) {
      echo '<div class="page"> <div id="principalPage" class="container-fluid">';
      include_once('layouts/_pageLoader.php');
      echo '</div></div>';}
  else include_once('layouts/_pageLoader.php');  

  include_once('layouts/_footer.php');
}
?>
