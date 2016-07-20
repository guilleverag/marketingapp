<?php
	//Includes database connection file
	require_once('properties_conexion.php');
	conectar('xima');
    $email	= isset($_POST['email']) ? $_POST['email'] : null;
	$sql	= "SELECT email_userconect FROM realtor_userconect WHERE email_userconect='{$email}'";
	$res	= mysql_query($sql) or die(mysql_error());
	$nro	= mysql_num_rows($res);
	//echo $sql;
	//encode in JSON format
	echo 'JSONP.process('.json_encode(array(
		"success" 	=> mysql_errno() == 0,
		"nro" 		=> $nro
	)).',"email")';
?>