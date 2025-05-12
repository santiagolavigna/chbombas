<?php
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $us = [];
  if(!isset($_REQUEST['id'])){
    $page_title = 'Nueva marca';
    $butonLabel = 'Crear marca';
    
  }else {
    $us = find_by_id('categorias',(int)$_REQUEST['id']);
    if(!$us) redirect($thisPage);
    $page_title = 'Modificar marca: '.rj($us['nombre']);
    $butonLabel = 'Actualizar marca';
  }

?>
<div class="panel panel-default">

    <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span><span><?=$page_title?></span></strong>
        <a href="?p=categorie|categorieList" class="btn btn-info pull-right">Volver</a>
    </div>

    <div class="panel-body">
        <form id="formClients" method="post" action="?p=categorie|actionCategorie&altaModif">

            <?php if(isset($us['id'])) echo hcSimpleInput("id",$us,"hid")?>

            <div class="col-md-6">
                <?=hcSimpleInput("nombre",$us,"fg|lab:Marca|req|ph:Nombre de la marca")?>
            </div>

            <div class="form-group clearfix">
              <button type="submit" class="btn btn-primary"><?=$butonLabel?></button>
            </div>
            
        </form>
    </div>
</div>
