<?php
  $page_title = 'Lista de productos';
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
$all_categorias = producto_categoria();
?>
  
<div class="panel panel-default">    
       
    <div class="panel-heading clearfix">
        <strong>
            <span class="glyphicon glyphicon-th"></span> <span>Todos los productos</span>
        </strong>
    </div>

    <div class="panel-body">
        
        <div class="form-group col-md-6">  
          
              <strong class="col-md-12">Categoria & proveedor</strong>
            <div class="form-group col-md-6">          
               <?= hcSelectDoble("id_categorias", find_all('categorias'),"id","nombre","descripcion")?>              
        
                 
            </div>
                 <strong class="col-md-12">Ingresar aumento</strong>
            <div class="form-group col-md-6">          
                <input type="number" step="0.1" class="form-control" name="%precio" value="" placeholder="Precio en %">            
            </div>
              
               <div class="form-group col-md-12">
            <button name="addPorcentajesAll" class="col-md-12 btn btn-danger btn-sm">% Establecer Aumento en Porcentajes %</button>  
            </div>  
      
          
        </div>  
    <!--//spinner
                <div id="page-loader"><span class="preloader-interior container-fluid"></span></div> -->
        <?=hcTable("productos",$all_categorias,"id|cat|codigo|nombre|nombre_categoria|proveedor|precio_compra|precio_venta|stock|fecha_agregado:Fecha registro")?>
    </div> 
</div>