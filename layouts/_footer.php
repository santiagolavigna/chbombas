   <script type="text/javascript" src="libs/js/spin.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="libs/js/functions.js"></script>
  <script type="text/javascript" src="libs/js/basicUtils.js"></script>
 
  <?php //si es un modulo, y existe un .js, lo carga.. 
	if (isset($onlyModule)){
		$url='modules/'.$onlyModule.'/'.$onlyModule.'.js';
		if (file_exists($url)) echo '<script type="text/javascript" src="'.$url.'"></script>';
	}
  ?>

  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
