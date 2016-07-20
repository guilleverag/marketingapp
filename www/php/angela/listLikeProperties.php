<?php
	//Includes database connection file
	require_once('properties_conexion.php');
	conectar('xima');
    //print_r($_COOKIE);
    //print_r($_POST);
	$useridr	= isset($_COOKIE['useridr']) ? $_COOKIE['useridr'] : $_POST['useridr'];
	$email		= isset($_COOKIE['email']) ? $_COOKIE['email'] : $_POST['email'];
	$county		= isset($_COOKIE['county']) ? $_COOKIE['county'] : $_POST['county'];
	$email		= str_replace("%40", "@", $email);
	
	$sql		= "	select pid 
					from 
						realtor_like 
					where 
						email_userconect='".mysql_real_escape_string($email)."' and userid_realtor={$useridr} and status='L' and county={$county};";
    $rs			= mysql_query($sql);
	//loop that creates array with the database rows
	$properties = array();
	while($data = mysql_fetch_assoc($rs)) {
		$properties[] = $data;
	}
	//encode in JSON format
	echo json_encode(array(
		"success" 		=> mysql_errno() == 0,
		"properties" 	=> $properties,
        "sql" 	=> $sql
	));
?>