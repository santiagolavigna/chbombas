<?php
  $RESULT=array();

  $RESULT['isOK']=true;
  $RESULT['msg']='Algun mensaje para notificar';
  $RESULT['sendData']=$_POST;
  
  
   if(isset($_REQUEST["update_both"])){
   
      if((isset($RESULT['sendData']['precio'])) && (isset($RESULT['sendData']['categoria']))){
       $b = update_all_products($RESULT['sendData']['precio'], $RESULT['sendData']['categoria']);
      }
  
      
      if($b){ 
       $RESULT['RESULT']=true;
      }else{
       $RESULT['RESULT']=false;
      }
  }
  
                          
  

  echo json_encode($RESULT);
?>