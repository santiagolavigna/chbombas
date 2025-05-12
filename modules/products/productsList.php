<?php
  $page_title = 'Lista de productos';
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);

$all_productos = producto_categoria();
?>

<div class="panel panel-default">

    <div class="panel-heading clearfix">
        <strong>
            <span class="glyphicon glyphicon-th"></span> <span>Productos</span>
        </strong>
        <a href="?p=products|abmProducts" class="btn btn-info pull-right">Agregar Producto</a>
    </div>

    <div class="panel-body">
        <?=hcTable("productos",$all_productos,"id::2px|nombre_foto:Foto|codigo|nombre_categoria:Marca|descripcion|tipo:clasificacion|fecha_agregado:Fecha registro:2px")?>
    </div>

    
</div>