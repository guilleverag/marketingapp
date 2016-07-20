<?php
set_include_path(get_include_path() . PATH_SEPARATOR .'C:\\inetpub\\wwwroot'. PATH_SEPARATOR);
include('server_var.php');
//pro = 0; xima3
//pro = 1; xima
//pro = 2; Proyecto_xima
$pro=1;

function conectarPorNameCounty($name){
	global $server,$user,$pass,$pro;
	$conexion = mysql_connect($server, $user, $pass) or die("Could not connect: " . mysql_error());
	mysql_select_db('xima');
	mysql_query('set time_zone="US/Eastern";');
	
	$query = "SELECT if(l.bd=1,concat(c.bd,'1'),c.bd) bd, l.idcounty from xima.lsprogram l INNER JOIN xima.lscounty c ON (l.idcounty=c.idcounty) where l.idprogram=$pro and (replace(c.county,' ','')='$name' OR c.county='$name')";

	$result = mysql_query($query) or die($query.mysql_error());
	$r=mysql_fetch_array($result);
	
	$bd=$r['bd'];
	
	mysql_select_db($bd);
	return $bd.'^'.$r['idcounty'];
}
// Agregado por Luis R Castro 06/05/2015 a traves de la Sugerencia 12285
function CountyPorId($idcounty){
		$q="SELECT County from xima.lscounty where idcounty=".$idcounty."";
		$res = mysql_query($q) or die(mysql_error());
		while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
			$coun=$row["County"];
			return $coun;
			}
		
	}
////////////////////////////////////////////////7
function conectarPorIdCounty($idcounty){
	global $server,$user,$pass,$pro;
	$conexion = mysql_connect($server, $user, $pass) or die("Could not connect: " . mysql_error());
	mysql_select_db('xima');
	mysql_query('set time_zone="US/Eastern";');
	
	if(is_numeric($idcounty))
		$query = "SELECT if(l.bd=1,concat(c.bd,'1'),c.bd) bd from xima.lsprogram l INNER JOIN xima.lscounty c ON (l.idcounty=c.idcounty) where l.idprogram=$pro and l.idcounty=$idcounty";
	else{
		$query = "SELECT if(l.bd=1,concat(c.bd,'1'),c.bd) bd, l.idcounty from xima.lsprogram l INNER JOIN xima.lscounty c ON (l.idcounty=c.idcounty) where l.idprogram=$pro and replace(c.county,' ','')='$idcounty'";
	}
	
	$result = mysql_query($query) or die($query.mysql_error());
	$r=mysql_fetch_array($result);
	
	$bd=$r['bd'];
	
	mysql_select_db($bd);
	return $bd;
}

function conectarPorBD($bd){
	global $server,$user,$pass;
	$conexion = mysql_connect($server, $user, $pass) or die("Could not connect: " . mysql_error());

	mysql_select_db($bd);
	mysql_query('set time_zone="US/Eastern";');
	return $bd;
}

if(!function_exists("conectar")){
	function conectar(){
		global $server,$user,$pass;
		$conexion = mysql_connect($server, $user, $pass) or die("Could not connect: " . mysql_error());

		mysql_select_db('xima');
		mysql_query('set time_zone="US/Eastern";');
		return $conexion;
	}
}
?>