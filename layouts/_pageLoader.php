<?php

  echo '<div id=msgBlock class="row"> <div class="col-md-12">'.display_msg($session->msg()).'</div></div>';
    //SI SE RECIBE m=modulo, carga php desde el modulo indicado
    if (isset($onlyModule)){

      if (file_exists('modules'.DS.$onlyModule.DS.$onlyPage.'.php')) 
          include_once('modules'.DS.$onlyModule.DS.$onlyPage.'.php');
      else print "En el MODULO *".$onlyModule."* no pudo localizarse -".$onlyPage."-";

    //SINO BUSCA LA PAGINA DENTRO DE  /layouts
    }else if (file_exists('layouts'.DS.$onlyPage.'.php')){
      include_once('layouts'.DS.$onlyPage.'.php');
   
    //SINO BUSCA LA PAGINA EN EL DIRECTORIO RAIZ /
    }else if (file_exists($onlyPage.'.php')){
      include_once($onlyPage.'.php');

    }else{//SINO INDICA QUE NO SE ENCONTRO LA PAGINA
      if(!$session->isUserLoggedIn(true)) redirect('?p=login|login',false);
      if($onlyPage!="") $session->msg('a',"No pudimos encontrar la pagina -".$onlyPage."-");
      redirect("?p=home",false);
    }

?>