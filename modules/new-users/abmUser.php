<?php
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $groups = find_all('user_groups');
  $us=[];

  if(!isset($_REQUEST['id'])){
    $page_title = 'Nuevo usuario';
    $butonLabel = 'Crear usuario';
  }else {
    $us = find_by_id('users',(int)$_REQUEST['id']);
    if(!$us) redirect($thisPage);
    $page_title = 'Modificar usuario: '.rj($us['name']);
    $butonLabel = 'Actualizar usuario';
  }
?>

  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span><?=$page_title?></span>
          <a href="?p=new-users|usersList" class="btn btn-info pull-right">Volver</a>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="?p=new-users|actionUsers&altaModif">
            <?php if(isset($us['id'])):/*EN MODO EDIT AGREGA EL ID*/?>
              <input type="text" name="id" value="<?=rj($us['id'])?>" hidden>
            <?endif?>
            <?=hcSimpleInput("name",$us,"fg|lab:Nombre|req|ph:quien es el dueño de la cuenta?")?>
            <?=hcSimpleInput("username",$us,"fg|lab:Usuario|req|ph:nombre para ingresar al sistema")?>
            <?if(!isset($us['id'])):/*EN MODO ALTA MUESTRA EL CAMPO PASSWORD*/?>
              <?=hcSimpleInput("password",$us,"fg|lab:Contraseña|req|ph:clave de ingreso")?>
            <?php endif?>
            <div class="form-group">
              <label for="user_level">Rol de usuario</label>
              <?php $sel = validate_index($us,'user_level') ?>
              <?=hcSelect("user_level",$groups,"group_level","group_name",rj($sel))?>
            </div>
            <input type="text" name="status" value="1" hidden>
            <div class="form-group clearfix">
              <button type="submit" class="btn btn-primary"><?=$butonLabel?></button>
            </div>
        </form>
        </div>

        <? if(isset($us['id'])):?>
        <div class="row col-md-4 pull-right">
          <form action="?p=new-users|actionUsers&chPass" method="post" class="clearfix">
            <?=hcSimpleInput("id",$us,"hid")?>
            <?=hcSimpleInput("Clave Actual",$us,"fg|lab|req|pass")
            ?>
            <?=hcSimpleInput("Clave Nueva",$us,"fg|lab|req|pass")?>
          <div class="form-group clearfix">
              <button type="submit" name="update-pass" class="btn btn-danger pull-right">Cambiar Clave</button>
          </div>
          </form>
        </div>
        <? endif;?>

      </div>
    </div>
  </div>


