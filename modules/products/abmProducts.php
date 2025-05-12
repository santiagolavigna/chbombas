<?php
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $us = [];
  if(!isset($_REQUEST['id'])){
    $page_title = 'Nuevo producto';
    $butonLabel = 'Agregar producto';
    
  }else {
    $us = find_by_id('productos',(int)$_REQUEST['id']);
    if(!$us) redirect($thisPage);
    $page_title = 'Modificar producto: '.rj($us['nombre']);
    $butonLabel = 'Actualizar producto';
  }

?>
<div class="panel panel-default">

    <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span><span><?=$page_title?></span></strong>
        <a href="<?=$_SESSION['PAGINA_ANTERIOR']?>" class="btn btn-info pull-right">Volver</a>
    </div>

    <div class="panel-body">
        <form id="formProducts" method="post" action="?p=products|actionProducts&altaModif">

            <?php if(isset($us['id'])) echo hcSimpleInput("id",$us,"hid")?>

            <div class="col-md-6">
                <?=hcSimpleInput("codigo",$us,"fg|lab|req|ph:Codigo del producto...")?>
                 <div class="form-group">
                      <label for="user-level">Marca </label>
                      <?php $cod = validate_index($us,'id_categorias') ?>
                      <?= hcSelect("id_categorias", find_all('categorias'),"id","nombre",$cod)?>                  
                    </div>
                
                     <?=hcSimpleInput("descripcion",$us,"fg|lab|req|ph:descripcion...")?>
                   
                
    
            </div>    
                
                
            <div class="col-md-6">    
                 <div class="form-group">
                      <label for="user-level">Foto</label>
                      <?php $cod1 = validate_index($us,'id_media') ?>
                      <?= hcSelect("id_media", find_all('media'),"id","file_name",$cod1)?>                 
                    </div>
                
                
                               <?=hcSimpleInput("fecha_agregado",$us,"fg|lab|date-now|readonly")?>
            </div>

            <div class="form-group clearfix">
              <button type="submit" class="btn btn-primary"><?=$butonLabel?></button>
            </div>
            
        </form>
    </div>
</div>

