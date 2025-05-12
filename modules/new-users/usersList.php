<?php
  $page_title = 'Lista de usuarios';
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_users = find_all_user();
?>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Usuarios</span>
       </strong>
         <a href="?p=new-users|abmUser" class="btn btn-info pull-right">Agregar usuario</a>
      </div>
     <div class="panel-body">
        <table class="userTable table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">ID</th>
            <th>Nombre </th>
            <th>Usuario</th>
            <th class="text-center" style="width: 15%;">Rol de usuario</th>
            <th class="text-center" style="width: 10%;">Estado</th>
            <th style="width: 20%;">Último login</th>
            <th class="text-center" style="width: 100px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr class="trUserLine" data-rowid="<?=$a_user['id']?>">
           <td class="text-center"><?=$a_user['id']?></td>
           <td><?= rj($a_user['name'])?></td>
           <td><?= rj($a_user['username'])?></td>
           <td class="text-center"><?= rj($a_user['group_name'])?></td>
           <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?= "Activo"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?= "Inactivo"; ?></span>
          <?php endif;?>
           </td>
           <td><?= read_date($a_user['last_login'])?></td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
