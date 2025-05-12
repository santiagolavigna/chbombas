<?php
function getTablaAfip($tabla){
	$T=array();
	if($tabla=="tipoResponsable"){
		$id=1; $T[$id]=array("id"=>$id,"sigla"=>"RI","label"=>"Resp. Inscripto"); 
		$id=6; $T[$id]=array("id"=>$id,"sigla"=>"M","label"=>"Monotributista");
		$id=5; $T[$id]=array("id"=>$id,"sigla"=>"CF","label"=>"Consumidor Final");
		$id=4; $T[$id]=array("id"=>$id,"sigla"=>"E","label"=>"Excento");
		$id=3; $T[$id]=array("id"=>$id,"sigla"=>"NA","label"=>"No Alcanzado");
	}else if($tabla=="alicuotas"){
		$id=3; $T[$id]=array("id"=>$id,"alicuota"=>"0.00");
		$id=4; $T[$id]=array("id"=>$id,"alicuota"=>"10.50");
		$id=5; $T[$id]=array("id"=>$id,"alicuota"=>"21.00");
		$id=6; $T[$id]=array("id"=>$id,"alicuota"=>"27.00");
		$id=8; $T[$id]=array("id"=>$id,"alicuota"=>"5.00");
		$id=9; $T[$id]=array("id"=>$id,"alicuota"=>"2.50");
	}else if($tabla=="tipoComprobante"){
		$id=1; $T[$id]=array("id"=>$id,"tipo"=>"FACTURA A");
		//$id=2; $T[$id]=array("id"=>$id,"tipo"=>"NOTA DE DEBITO A");
		$id=6; $T[$id]=array("id"=>$id,"tipo"=>"FACTURA B");
		//$id=7; $T[$id]=array("id"=>$id,"tipo"=>"NOTA DE DEBITO B");
		$id=11; $T[$id]=array("id"=>$id,"tipo"=>"FACTURA C");
		//$id=12; $T[$id]=array("id"=>$id,"tipo"=>"NOTA DE DEBITO C");
	}else if($tabla=="modoDePago"){
		$id=1; $T[$id]=array("id"=>$id,"tipo"=>"Efectivo", "sigla"=>"$$$");
		$id=2; $T[$id]=array("id"=>$id,"tipo"=>"Cheque", "sigla"=>"CH");
		$id=3; $T[$id]=array("id"=>$id,"tipo"=>"Cuenta Corriente", "sigla"=>"Cta");
	}
  return $T;

}
?>