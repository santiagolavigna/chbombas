<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "";?>
    </title>
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main1.css" />

    <?php //si es un modulo, y existe un .css, lo carga.. 
    if (isset($onlyModule)){
      $url='modules/'.$onlyModule.'/'.$onlyModule.'.css';
      if (file_exists($url)) echo '<link rel="stylesheet" href="'.$url.'"/>';
    }
    ?>

  </head>
  <body>