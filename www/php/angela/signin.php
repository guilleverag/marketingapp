<?php
	//Includes database connection file
	require_once('properties_conexion.php');
	conectar('xima');
    $useridr	= isset($_POST['useridr']) ? $_POST['useridr'] : null;
    $pid 		= isset($_POST['pid']) ? $_POST['pid'] : null;
    $email 		= isset($_POST['email']) ? $_POST['email'] : null;
    $password	= isset($_POST['password']) ? $_POST['password'] : null;
	$sql		= "SELECT email_userconect FROM realtor_userconect WHERE email_userconect='{$email}' and pass_userconect='{$password}'";
	$rs 		= mysql_query($sql) or die(mysql_error());
	$nro		= mysql_num_rows($rs);
	//encode in JSON format
	echo json_encode(array(
		"success" 	=> mysql_errno() == 0,
		"nro" 		=> $nro
	));
?>