<?php
  $page_title = 'Marca de productos';
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_categories = find_all("categorias");
?>

  <div class="row">
     <div class="col-md-12">
     </div>
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Agregar Marca</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="?p=categorie|actionCategorie&altaModif">
            <div class="form-group">
                <input type="text" class="form-control" name="nombre" placeholder="Nombre de la marca" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Marca</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div id="categoriaList" class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Lista de Marcas</span>
       </strong>
      </div>
        <div class="panel-body">
         <?=hcTable("Categorias",$all_categories,"id|nombre:MARCA")?>
       </div>
    </div>
    </div>
   </div>
  </div>