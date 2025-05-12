<?php  
	$page_title = 'Home Page'; 
	page_require_level(1);
?>
<div class="row">  
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
        <h1 class="welcome">Bienvenido: <?=remove_junk(ucfirst($user['name']))?></h1>
      </div>
    </div>
 </div>
</div>
