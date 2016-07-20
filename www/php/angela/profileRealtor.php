<?php
	require_once("properties_conexion.php");
	$userid			= isset($_COOKIE['useridr']) ? $_COOKIE['useridr'] : $_GET['useridr'];
	$db				= conectar('xima');
	$sql			= "select 
						profimg,companyname as officename,profname as agent,profemail as agentemail,profphone as agentph,
						proffax as agentfax,officephone as officephone,profaddress as address,userid
					from profile
					WHERE userid='{$userid}';";
    $rs				= mysql_query($sql) or die($sql.' - '.mysql_error());
	
	//loop that creates array with the database rows
	if($data 	= mysql_fetch_assoc($rs)) {
		$profile = $data;
	}
	//encode in JSON format
	echo json_encode(array(
		"success" => mysql_errno() == 0,
		"profile" => $profile
	));
?>