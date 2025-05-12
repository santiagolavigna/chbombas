<?php
  $page_title = 'Lista de imagenes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php $media_files = find_all('media');?>
<?php
  if(isset($_POST['submit'])) {
  $photo = new Media();
  Utils::log("1");
  $photo->upload($_FILES['file_upload']);
  Utils::log("2");
    if($photo->process_media()){
        Utils::log("3");
        $session->msg('s','Imagen subida al servidor.');
        redirect('?p=media|mediaList');
    } else{
      $session->msg('d',join($photo->errors));
      redirect('?p=media|mediaList');
    }

  }

?>

   <div class="row">
        <div class="col-md-6">
          <?php echo display_msg($msg); ?>
        </div>

      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
            <span class="glyphicon glyphicon-camera"></span>
            <span>Lista de imagenes</span>
            <div class="pull-right">
              <form class="form-inline" action="?p=media|mediaList" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-btn">
                    <input type="file" name="file_upload" multiple="multiple" class="btn btn-primary btn-file"/>
                 </span>
                  
                 <button type="submit" name="submit" class="btn btn-default">Subir</button>
               </div>
              </div>
             </form>
            </div>
          </div>
          <div class="panel-body">
            <table class="table" id="table-fotos">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th class="text-center">Imagen</th>
                  <th class="text-center">Descripción</th>
                  <th class="text-center" style="width: 20%;">Tipo</th>
                  <th class="text-center" style="width: 50px;">Acciones</th>
                </tr>
              </thead>
                <tbody>
                <?php foreach ($media_files as $media_file): ?>
                <tr class="list-inline" data-rowid="<?php echo $media_file['id'];?>">
                 <td data-columnid="id" class="text-center"><?php echo $media_file['id'];?></td>
                  <td class="text-center">
                      <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
                  </td>
                <td class="text-center">
                  <?php echo $media_file['file_name'];?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type'];?>
                </td>
                <td class="text-center">
                </td>
               </tr>
              <?php endforeach;?>
            </tbody>
          </div>
        </div>
      </div>
</div>