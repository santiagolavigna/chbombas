<?php
  $page_title = 'Panel de control';
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
 $c_categorie     = count_by_id('categorias');
 $c_product       = count_by_id('productos');
 $c_media         = count_by_id('media');

 
 $recent_products = find_recent_product_added('5');

?>

  <div class="row">
        <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-picture"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?= $c_media['total'] ?> </h2>
          <p class="text-muted">Fotos</p>
        </div>
       </div>
    </div>
 
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-list"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?= $c_categorie['total'] ?> </h2>
          <p class="text-muted">Marcas-modelo</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue">
          <i class="glyphicon glyphicon-asterisk"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?= $c_product['total'] ?> </h2>
          <p class="text-muted">Productos</p>
        </div>
       </div>
    </div> 
  
</div>
