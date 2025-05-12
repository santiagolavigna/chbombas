<?php
	function generateMenu($user_level){

		if($user_level!=='1'){
		$JSON = file_get_contents('layouts/menues_JSON_1.json');
                }else{
                $JSON = file_get_contents('layouts/menues_JSON.json');
                }
                
	    $ARRAY_MENUES=json_decode($JSON)->menues; 

	    $HTML='<ul>';
		foreach ($ARRAY_MENUES as $MENU) {
			$HTML.='<li>';		

				$HTML.=getLineMenuHTML($MENU);
				
				//si tiene subMenues
				if(isset($MENU->subMenus)) {
					$HTML.='<ul class="nav submenu">';
						foreach ($MENU->subMenus as $SUB) {
								$HTML.='<li>'.getLineMenuHTML($SUB).'</li>';
						}
					$HTML.='</ul>';
				}//fin si tiene submenues
			$HTML.='</li>';	//cierra el items	
		}
		$HTML.='</ul>';	//cierra la lista final	
		return $HTML;	
	}

	function getLineMenuHTML($M){	
		//print_r($MENU);
		//echo "<br><br>";	
		$H='';
		if(isset($M->subMenus)) $H.='<a href="#" class="submenu-toggle">';
		else $H.='<a href="'.$M->link.'">';
		if (isset($M->icon) AND ($M->icon!='')) {
			$H.='<i class="glyphicon '.$M->icon.'"></i>';
			$H.='<span>'.$M->name.'</span>';
		}else $H.=$M->name;
		$H.='</a>';
		return $H;		
	}
?>

<div id="sidebar" class="sidebar">
    <?=generateMenu($user['status'])?>
</div>