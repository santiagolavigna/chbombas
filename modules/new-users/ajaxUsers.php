<?php
  $RESULT=array();

  $RESULT['isOK']=true;
  $RESULT['msg']='Algun mensaje para notificar';
  $RESULT['sendData']=$_POST;

  if(isset($_REQUEST["autos"])){
  	$coches = array();
  	$coches[0]=array("id"=>"0","color"=>"rojo","marca"=>"fiat","modelo"=>"palio");
  	$coches[1]=array("id"=>"1","color"=>"azul","marca"=>"ford","modelo"=>"ka");
  	$coches[2]=array("id"=>"2","color"=>"naranja","marca"=>"honda","modelo"=>"civic");
  	$coches[3]=array("id"=>"3","color"=>"gris","marca"=>"chevrolet","modelo"=>"corsa");
  	$coches[4]=array("id"=>"4","color"=>"negro","marca"=>"fiat","modelo"=>"duna");
  	$RESULT['RESULT']=$coches;
  }

  echo json_encode($RESULT);
?>