<?php
  $page_title = 'Marca de productos';
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_tipos = find_all("tipo");
?>

  <div class="row">
     <div class="col-md-12">
     </div>
  </div>
   <div class="row">
    <div class="col-md-7">
    <div id="tipoList" class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Lista de clasificacion</span>
       </strong>
      </div>
        <div class="panel-body">
         <?=hcTable("Clasificaciones",$all_tipos,"id|nombre:Clasificacion")?>
       </div>
    </div>
    </div>
   </div>
  </div>