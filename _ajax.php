<?php
	//se espera ajaxControl.php?m=users&otros-datos
  require_once('includes/load.php');  
  $session=new Session();
  page_require_level(1);

  $thisPage="?p=".$_REQUEST["p"]; //CONTIENE LA URL COMPLETA ?p=module|page

  //en el request puede venir p=nombrePagina que cargaria de layout/ o del raiz /
  //o puede venir p=nombreModulo|nombrePagina y cargaria de modules/elModuloCorrespondiente/
  $REQ=explode("|", str_replace("?p=","",$thisPage) );//PAGINA
  if(sizeof($REQ)==2){
      $onlyModule=$REQ[0];
      $onlyPage=$REQ[1];
  } else $onlyPage=$REQ[0];

  //SI SE RECIBE m=modulo, carga php de ajax desde el modulo indicado
  if (isset($onlyModule)){

      if (file_exists('modules'.DS.$onlyModule.DS.$onlyPage.'.php')) 
          include_once('modules'.DS.$onlyModule.DS.$onlyPage.'.php');
      else echo "No pudimos encontrar el AJAX  -".$onlyModule."|".$onlyPage."-";

  //SINO BUSCA LA PAGINA DENTRO DE  /layouts
  }else echo "Debes especificar un MODULO obligatoriamente.";  

?>