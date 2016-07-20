<?php
	//Includes database connection file
	require_once('properties_conexion.php');
	conectar('xima');
    $useridr		= isset($_POST['useridr']) ? $_POST['useridr'] : null;
    $email 			= isset($_POST['email']) ? $_POST['email'] : null;
	$email			= str_replace("%40", "@", $email);
    $password		= isset($_POST['password']) ? $_POST['password'] : null;
	$sql_comparado	= "insert into realtor_userconect (id_realtor_userconect,userid_realtor,email_userconect,pass_userconect) 
						values	(null,'{$useridr}','".mysql_real_escape_string($email)."','".mysql_real_escape_string($password)."')";
    $rs 			= mysql_query($sql_comparado) or die(mysql_error());
	//encode in JSON format 
	echo json_encode(array(
		"success" 	=> mysql_errno() == 0
	));
?>