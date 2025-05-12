<?php 
//HTML Constructor
//las funciones iniciadas con "hc" son HTML Constructors, es decir
//son funciones que sirven para generar mas facilmente codigo html para
//formularios, tablas, listas etc..
function hcSelect($NAME_SELECT,$ARREGLO,$CAMPO_VALOR,$CAMPO_ETIQUETA,$VALUE_SELECTED=""){
  //RETORNA EL HTML DE UN SELECT ESTANDAR CON LOS PARAMETROS INDICADOS
    $RES='<select class="form-control" name="'.$NAME_SELECT.'">';
  
  
    foreach ($ARREGLO as $elem ){
      $RES.='<option ';
      if($VALUE_SELECTED==$elem[$CAMPO_VALOR]) $RES.='selected ';
      $RES.='value="'.$elem[$CAMPO_VALOR].'"> ';
      $RES.=rj($elem[$CAMPO_ETIQUETA]).'</option>';
    }
  $RES.='</select>';
  return $RES;
}



function hcSelectDoble($NAME_SELECT,$ARREGLO,$CAMPO_VALOR,$CAMPO_ETIQUETA,$CAMPO_ETIQUETA2,$VALUE_SELECTED=""){
  //RETORNA EL HTML DE UN SELECT ESTANDAR CON LOS PARAMETROS INDICADOS
    $RES='<select class="form-control" name="'.$NAME_SELECT.'">';
  
  
    foreach ($ARREGLO as $elem ){
      $RES.='<option ';
      if($VALUE_SELECTED==$elem[$CAMPO_VALOR]) $RES.='selected ';
      $RES.='value="'.$elem[$CAMPO_VALOR].'"> ';
      $RES.=rj($elem[$CAMPO_ETIQUETA]).' ';
      $RES.=rj($elem[$CAMPO_ETIQUETA2]).'</option>';
    }
  $RES.='</select>';
  return $RES;
}


function hcSimpleInput($name,$data,$options){
//$options= "r|fg|lab:Titulo|ph:placeholder|pass|disabled"
  $o=array();
  foreach (explode("|",$options) as $k) {
    $exp=explode(":",$k);
    if(isset($exp[1])) $o[$exp[0]]=$exp[1];
    else $o[$exp[0]]="";
  }
  $v = validate_index($data,$name);
        
	$HTML="";
	  if (isset($o["fg"])){
       $HTML.='<div class="form-group';
       if ($o["fg"]!="") $HTML.=' '.$o["fg"];
       $HTML.='">';
    }
    if (isset($o["lab"])) {
       if($o["lab"]!="") $label=$o["lab"];
       else $label=ucfirst($name);
       $HTML.='<label for="'.$name.'">'.$label.'</label>';
    }
    $HTML.='<input';
    if (isset($o["num"])) $HTML.=' type="number" step="0.01"';
    else if (isset($o["pass"])) $HTML.=' type="password"';
    else if (isset($o["date"])) $HTML.=' type="date"';
    else if (isset($o["date-now"])) $HTML.=' type="date"';
    else $HTML.=' type="text"';
    $HTML.=' class="form-control" autocomplete="off" name="'.$name.'"';
    if (isset($o["date-now"])) $v=date('Y-m-d');
    $HTML.=' value="'.$v.'"';    
    if (isset($o["ph"])) {
       if($o["ph"]!="") $ph=$o["ph"];
       else $ph=ucfirst($name);
       $HTML.=' placeholder="'.$ph.'"';    
    }
    if (isset($o["req"])) $HTML.=' required';
    
    if (isset($o["disabled"])) $HTML.=' disabled';
    if (isset($o["readonly"])) $HTML.=' readonly';
    
    $HTML.=' >';
    if (isset($o["fg"])) $HTML.='</div>';
    if (isset($o["hid"])) //si es hid pisa lo anterior
    	$HTML='<input name="'.$name.'" value="'.$v.'" hidden>';
    return $HTML;
}
function hcTable($nameTable, $dataTable, $heads=null){
  //RECIBE ARRAY CON DATOS DE BBDD Y CREA UNA TABLA ESTANDAR
  //se puede personalizar campos a mostrar con $customData
  if(!isset($dataTable)||!isset($dataTable[0])) 
    return "<span>No hay datos en la tabla..</span>";	
  
  /*
   cada cabecera se separa de otra con "|"
   cada cabecera puede tener hasta tres parametros: 
        nombreEnBBDD:tituloDeLaColumna:ancho
  un $heads esperado seria:
    "id:CODIGO:15px|nombre::50%|tel:Telefono|cuit:C.U.I.T.:20em"*/
  //armamos las cabeceras
  $hd=array();
  $hdW=array();
  if(!isset($heads)){ 
    foreach($dataTable[0] as $k=>$v){
      if(!is_numeric($k)) {
        $hd[$k]=ucfirst($k);
        $hdW[$k]="";
      }
    }
  }else{
    foreach(explode("|",$heads) as $k){
      $exp=explode(":",$k);
      if(isset($exp[1])) {        
        if($exp[1]==="") $hd[$exp[0]]=ucfirst($exp[0]);
        else $hd[$exp[0]]=$exp[1];
      }else $hd[$exp[0]]=ucfirst($exp[0]);
      
      if(isset($exp[2])) $hdW[$exp[0]]=$exp[2];
      else $hdW[$exp[0]]="";
    }
  }
	
  //armamos la tabla
  $HTML='<table id="table-'.$nameTable.'" class="table table-bordered table-striped">';
  $HTML.='<thead><tr>';
      //CREAMOS EL THEAD
      foreach($hd as $k=>$v){
         	if($hdW[$k]!="") $HTML.='<th class="text-center" style="width: '.$hdW[$k].';">';
          else $HTML.='<th class="text-center">';
          $HTML.=$v.'</th>';
        //<th class="text-center" style="width: 50px;">ID</th>
      }
  $HTML.='</tr></thead>';
  $HTML.='<tbody>';
  foreach($dataTable as $row){
    $HTML.='<tr data-rowid="'.$row['id'].'">';
    foreach($hd as $k=>$v){
        if ($hd[$k] === 'Foto') {
                $HTML.='<td data-columnid='.$hd[$k].' class="text-center"><img class="img-avatar img-circle" src="uploads/products/'.$row[$k].'"></td>';
      
        }else{
        	$HTML.='<td data-columnid='.$hd[$k].' class="text-center">'.$row[$k].'</td>';
        }
    }
    $HTML.='</tr>';
  }
  $HTML.='</tbody>';
  $HTML.='</table>';
  return $HTML;
}
?>