<?php
include("properties_conexion.php");
$bd=$_POST['bd'];
conectarPorBD($bd);
include ("properties_getgridcamptit.php");

	if(!isset($_POST['no_func'])){
		require('includes/class/polygon.geo.class.php');
		//NEW CLASS POLYGON
	
		function _pointINpolygon($arrPoly,$lat,$lng) 
		{
			$lng=($lng);
			$vertex = new vertex($lng,$lat);
			$polygon =& new geo_polygon();
			$_lat=0;$_lng=0;
			foreach($arrPoly as & $coord)
			{
				$_lng=($coord["long"]);
				$_lat=$coord["lat"];		
				$polygon->addv($_lng,$_lat);
			}	
			$isInside = ($polygon->isInside($vertex))? true:false;	
			
			return $isInside;
		}
	}

$beds=array();
$bath=array();
$pool=array();
$wf=array();

$el_comparado=$_POST["id"];
//$elcomparado=$_POST["parcel"];
//$url=$_POST["url"];
$curparcel=substr($el_comparado,0,6);
$proper=$_POST['prop'];
$factor_latlong=0.016;
$factor_latlong_2=0.025;

//Filtros
$filters="";

//Inicializacion
$distance=(float) -1;
$beds=(integer) -1;
$cond_beds=-1;
$baths=(integer) -1;
$cond_baths=-1;
$lsqft=(integer) -1;
$larea=(integer) -1;
$pool=(integer) -1;
$wf=(integer) -1;
$Garea_From=(integer) -1;
$Garea_To=(integer) -1;
$Larea_From=(integer) -1;
$Larea_To=(integer) -1;
$yb=(integer) -1;
$maplatlong=0;
$subd=-1;


//Logeado
if(isset($_POST['userid'])){
	if(isset($_POST['filter']) && $_POST['filter']!=''){
		$filters2 = explode('^',$_POST['filter']);
		$r=array();
		while(count($filters2)>0){
			$filteraux = array_pop($filters2);
			$filteraux = explode('=',$filteraux);
			$r[$filteraux[0]] = $filteraux[1];
		}
		
	}else{
		$query='select filter_rental_distance,filter_rental_beds,filter_rental_bedscon,filter_rental_baths,filter_rental_bathscon,
		filter_rental_garea,filter_rental_garea_from,filter_rental_garea_to,filter_rental_larea,filter_rental_larea_from,filter_rental_larea_to,
		filter_rental_pool,filter_rental_waterf,filter_rental_built,filter_rental_mapa, filter_rental_subd 
		FROM xima.xima_system_var WHERE userid='.$_POST['userid'];
		$result=mysql_query($query) or die($query.mysql_error());
		$r=mysql_fetch_array($result);
	}
	
	$distance=(float) $r['filter_rental_distance'];
	$beds=(integer) $r['filter_rental_beds'];
	$cond_beds=$r['filter_rental_bedscon'];
	$baths=(integer) $r['filter_rental_baths'];
	$cond_baths=$r['filter_rental_bathscon'];
	$lsqft=(integer) $r['filter_rental_garea'];
	$larea=(integer) $r['filter_rental_larea'];
	$pool=(integer) $r['filter_rental_pool'];
	$wf=(integer) $r['filter_rental_waterf'];
	$Garea_From=(integer) $r['filter_rental_garea_from'];
	$Garea_To=(integer) $r['filter_rental_garea_to'];
	$Larea_From=(integer) $r['filter_rental_larea_from'];
	$Larea_To=(integer) $r['filter_rental_larea_to'];
	$yb=(integer) $r['filter_rental_built'];
	$maplatlong=0;
	$maplatlong=$r['filter_rental_mapa'];
	$subd=(integer) $r['filter_rental_subd'];
}

$permission=array();
if(isset($_COOKIE['datos_usr']['USERID'])){
	$qp='SELECT * FROM xima.permission WHERE userid='.$_COOKIE['datos_usr']['USERID'];
	$result = mysql_query($qp) or die($qp.mysql_error());
	$permission = mysql_fetch_assoc($result);
}


		$query="Select
		psummary.lsqft,
		psummary.yrbuilt,
		psummary.bheated,
		psummary.sdname,
		latlong.latitude,
		latlong.longitude
		FROM psummary
		left join latlong on (psummary.parcelid=latlong.parcelid) 
		WHERE psummary.parcelid='$el_comparado'";
	//echo $query;

$resp=mysql_query($query) or die($query.mysql_error());
$r = mysql_fetch_array($resp);

//print_r($r);

$lat=$r['latitude'];
$lon=$r['longitude'];
$lsqft1=$r['lsqft'];
$larea1=$r['bheated'];
$com_year=$r['yrbuilt'];
$com_subd=$r["sdname"];
$latmax=$lat+$factor_latlong;
$latmin=$lat-$factor_latlong;
$lonmax=$lon+$factor_latlong;
$lonmin=$lon-$factor_latlong;
if($distance>1){
	$latmax=$lat+$factor_latlong_2;
	$latmin=$lat-$factor_latlong_2;
	$lonmax=$lon+$factor_latlong_2;
	$lonmin=$lon-$factor_latlong_2;
}

//echo $_calculo;

$filters="";

if($lsqft>0){
	$div=round($lsqft/100,2);

	$_lsqft=$lsqft1+($lsqft1*$div);
	$_lsqft1=$lsqft1-($lsqft1*$div);

	$filters.=" and (`rental`.lsqft>".$_lsqft1." and `rental`.lsqft<".$_lsqft.")";
}elseif($Garea_From>0 && $Garea_To>0){
	$filters.="  AND (`rental`.lsqft>=".$Garea_From." and `rental`.lsqft<=".$Garea_To." and `rental`.lsqft>0) ";
}

if($larea>0){
	$div=round($larea/100,2);

	$_larea=$larea1+($larea1*$div);
	$_larea1=$larea1-($larea1*$div);

	$filters.=" and (`mlsresidential`.larea>".$_larea1." and `mlsresidential`.larea<".$_larea.")";
}elseif($Larea_From>0 && $Larea_To>0){
	$filters.="  AND (`mlsresidential`.larea>=".$Larea_From." and `mlsresidential`.larea<=".$Larea_To." and `mlsresidential`.larea>0) ";
}

if (($yb>0) && ($com_year>0)){ 
	$filters.=" AND ((`rental`.yrbuilt>=($com_year-$yb)) and (`rental`.yrbuilt<=($com_year+$yb)))"; 
}

if($beds>0){
	$filters.=" and `rental`.beds $cond_beds $beds ";
}

if($baths>0){
	$filters.=" and `rental`.bath $cond_baths $baths ";
}

if($pool>=0){
		switch($pool){
		case 1: $filters.=" and `rental`.pool='Y' ";
		break;
		case 0: $filters.=" and `rental`.pool='N' ";
		break;
	}
}

if($wf>=0){
	switch($wf){
		case 1: $filters.=" and `rental`.waterf='Y' ";
		break;
		case 0: $filters.=" and `rental`.waterf='N' ";
		break;
	}
}

if($distance>0){
	$dist=$distance;
}else{
	$dist=0.5;
}

if ($subd > 0 && strlen(trim($com_subd))>0) {
	$filters.=" AND `psummary`.sdname = '".$com_subd."' ";
}

if($maplatlong!=0){
		$maplatlong=explode('/',$maplatlong);
		if(count($maplatlong)==2){
			$maplatlong1=explode(',',$maplatlong[0]);
			$maplatlong2=explode(',',$maplatlong[1]);
			if($maplatlong1[0]>$maplatlong2[0])
				$filters.=" AND (`latlong`.LATITUDE>=".$maplatlong2[0]." and `latlong`.LATITUDE<=".$maplatlong1[0];
			else
				$filters.=" AND (`latlong`.LATITUDE>=".$maplatlong1[0]." and `latlong`.LATITUDE<=".$maplatlong2[0];
			if($maplatlong1[1]>$maplatlong2[1])
				$filters.=" and `latlong`.LONGITUDE>=".$maplatlong2[1]." and `latlong`.LONGITUDE<=".$maplatlong1[1].")";
			else
				$filters.=" and `latlong`.LONGITUDE>=".$maplatlong1[1]." and `latlong`.LONGITUDE<=".$maplatlong2[1].")";
		}
		elseif(count($maplatlong)>2){
			$latmax=0;
			$longmax=-1000;
			$latmin=1000;
			$longmin=1000;
			$arrPoly=array();
			
			foreach($maplatlong as $k => $val){
				$aux=explode(',',$val);
				
				if($latmax<$aux[0]) $latmax=$aux[0];
				if($latmin>$aux[0]) $latmin=$aux[0];
				
				if($longmax<$aux[1]) $longmax=$aux[1];
				if($longmin>$aux[1]) $longmin=$aux[1];
				$arrPoly[]=array("lat"=>$aux[0],"long"=>$aux[1]);
			}
			
			$filters.=" AND (`latlong`.LATITUDE>=".$latmin." and `latlong`.LATITUDE<=".$latmax." and `latlong`.LONGITUDE>=".$longmin." and `latlong`.LONGITUDE<=".$longmax.")";	
		}
	}


//***********************************************************************************************	
$data_arr=array();//se guarda lo calculado
$array_parcelIds=array();//se guardan todos los parcelids para ser usados como filtro en los sub-siguientes selects
$MainData=array();//se aloja toda la data q sera vaciada en el grid

$ArSqlCT=array('idtc','campos','tabla','titulos','type','size','Desc','numformatted','decimals','align','px_size');//Search
$ArDfsCT=array('idtc','name','tabla','title','type','size','desc','numformatted','decimal','align','px_size');//Search
$ArIDCT = getArray('rental','comparables',false);

if($permission['realtor_esp']==1) $ArIDCT[0]=248;

$hdArray=getCamptit($ArSqlCT, $ArDfsCT, $ArIDCT);
$hdArray=str_replace(  "'",'"', $hdArray);	
$hdArray   = json_decode($hdArray);

$orderby='';
$limit='';
$jointable=array('`rental`','`properties_php`','`mlsresidential`','`latlong`','`marketvalue`','`diffvalue`','`psummary`');
$campos='p.parcelid as pid, concat("_",`rental`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong, p.address as pin_address, `rental`.lsqft as pin_lsqft, `mlsresidential`.larea as pin_larea, `rental`.beds as pin_bed, `rental`.bath as pin_bath, `rental`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip';

foreach($hdArray as $k => $val){
	if(array_search('`'.$val->tabla.'`',$jointable)===false)
		$jointable[]='`'.$val->tabla.'`';
	$campos.=', `'.$val->tabla.'`.'.$val->name;
	
	if(isset($_POST['sort']) && ($val->name==$_POST['sort'])){
		$orderby=' ORDER BY `'.$val->tabla.'`.'.$val->name.' '.$_POST['dir'];
	}
}
if(!isset($_POST['sort'])) $orderby=' ORDER BY Distance ASC';
if(isset($_POST['sort']) && ($_POST['sort']=='Distance' || $_POST['sort']=='status')) $orderby=' ORDER BY Distance '.$_POST['dir'];

if(isset($_POST['limit'])) $limit=' LIMIT '.$_POST['start'].', '.$_POST['limit'];

//============================================================================
$_calculo="truncate(sqrt((69.1* (`latlong`.latitude- $lat))*(69.1*(`latlong`.latitude-$lat))+(69.1*((`latlong`.longitude-($lon))*cos($lat/57.29577951)))*(69.1*((`latlong`.longitude-($lon))*cos($lat/57.29577951)))),2)";

$sql="SELECT ".$campos.", $_calculo as Distance FROM ";
foreach($jointable as $k => $val){
	if($k==0) $sql.="$val ";
	elseif($val=='`properties_php`') $sql.="LEFT JOIN $val p ON (".$jointable[0].".parcelid=p.parcelid) ";
	else $sql.="LEFT JOIN $val ON (".$jointable[0].".parcelid=$val.parcelid) ";
}

$sql.=" WHERE `rental`.parcelid<>'$el_comparado'
AND p.xcode='$proper' 
AND (`latlong`.LATITUDE>=".$latmin." and `latlong`.LATITUDE<=".$latmax." and `latlong`.LONGITUDE>=".$lonmin." and `latlong`.LONGITUDE<=".$lonmax.")
$filters";

if($_POST['array_no_taken']!='' && $_POST['array_no_taken']!="''")
	$sql.=" AND `rental`.parcelid NOT IN (".$_POST['array_no_taken'].")";

$sql.=$orderby;


if($_POST['array_taken']!='' && $_POST['array_taken']!="''"){
	$sql="SELECT ".$campos.", $_calculo as Distance FROM ";
	foreach($jointable as $k => $val){
		if($k==0) $sql.="$val ";
		elseif($val=='`properties_php`') $sql.="LEFT JOIN $val p ON (".$jointable[0].".parcelid=p.parcelid) ";
		else $sql.="LEFT JOIN $val ON (".$jointable[0].".parcelid=$val.parcelid) ";
	}
	
	$sql.=" WHERE `rental`.parcelid IN (".$_POST['array_taken'].")";
	if($_POST['array_no_taken']!='' && $_POST['array_no_taken']!="''")
		$sql.=" AND `rental`.parcelid NOT IN (".$_POST['array_no_taken'].")";
		
	$sql.=$orderby;
}
	
//echo $sql;//return;
$result = mysql_query($sql) or die($sql." ".mysql_error());


//===================================================================================

$lat=0;
$lon=0;
$num_rows=0;
$xmls="";
$_quitar = array("'", '"');

$array_parcelIds=array();//se guardan todos los parcelids para ser usados como filtro en los sub-siguientes selects
$array_datos=array();

while($row=mysql_fetch_array($result, MYSQL_ASSOC))
{
	if($row["pid"]!=$el_comparado && $row['Distance']<=$dist){	//Para arreglar el Bug de hace aparecer el comparado en el grid -- 08/02/2008
	
		$lat=$row["pin_xlat"];$lon=$row["pin_xlong"];
		if(is_null($lat)) $lat=0; if(is_null($lon)) $lon=0;

		$asignar=true;
		//guardamos el array de datos en nuestro array - requeirdo para [Maria Olivera]
		if($maplatlong!=0){
			if(count($maplatlong)>2){
				if($lat>0 && _pointINpolygon($arrPoly,$lat,$lon)){
					array_push($array_parcelIds,$row["pid"]);
				}else{
					$asignar=false;
				}
			}else{
				array_push($array_parcelIds,$row["pid"]);
			}
		}else{
			array_push($array_parcelIds,$row["pid"]);
		}
		//////////////////////////////////////
		
		if (strlen($lat)>0 && $asignar)	
			$array_datos[]=$row;	 
	}

}
mysql_free_result($result);

/*
//**************************************[Maria Olivera]************************************************
for($i=0;$i<count($array_parcelIds);$i++)//recorremos el array de parcelids
{
	$ySql="select mlsresidential.mlnumber from mlsresidential 
	where mlsresidential.parcelid='$array_parcelIds[$i]'";
	$result4 = mysql_query($ySql) or die (mysql_error());
	$row=mysql_fetch_array($result4, MYSQL_ASSOC);	
	
	if(is_null($row["mlnumber"]))		$mlnumber="0"; else $mlnumber=$row["mlnumber"];
	
	if ($i>0) $ml.=",";		
		$ml.= 
		"'".$array_parcelIds[$i]."':".
		"{\"mlnumber\":\"".$mlnumber."\"}";
	
}
//********************************[Maria  Oliveira] TABLA DIFFVALUE ************************************

for($i=0;$i<count($array_parcelIds);$i++)//recorremos el array de parcelids
{
$xSql="Select diffvalue.* FROM diffvalue where diffvalue.parcelID='$array_parcelIds[$i]'";
$result = mysql_query($xSql) or die (mysql_error());
$row=mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(is_null($row["ParcelID"]))		$parcelid=""; else $parcelid=$row["ParcelID"];
	if(is_null($row["Bath"]))			$bath=""; else $bath=$row["Bath"];
	if(is_null($row["Beds"]))			$beds=""; else $beds=$row["Beds"];
	if(is_null($row["ClosingDT"]))		$closingdt=""; else $closingdt=$row["ClosingDT"];
	if(is_null($row["Lsqft"]))			$lsqft=""; else $lsqft=$row["Lsqft"];
	if(is_null($row["Pool"]))			$pool=""; else $pool=$row["Pool"];
	if(is_null($row["SalePrice"]))		$saleprice=""; else $saleprice=$row["SalePrice"];
	if(is_null($row["TSqft"]))			$tsqft=""; else $tsqft=$row["TSqft"];
	if(is_null($row["Value"]))			$value=""; else $value=$row["Value"];
	if(is_null($row["waterf"]))			$waterf=""; else $waterf=$row["WaterF"];
	if(is_null($row["Zip"]))			$zip=""; else $zip=$row["Zip"];

	if ($i>0) $Diff.=",";		
		$Diff.= 
		"'".$array_parcelIds[$i]."':".
		"{\"parcelid\":\"".str_replace($_quitar,"",$parcelid). 
		"\",\"bath\":\"".str_replace($_quitar,"",$bath).
		"\",\"beds\":\"".str_replace($_quitar,"",$beds).
		"\",\"closingdt\":\"".str_replace($_quitar,"",$closingdt).
		"\",\"lsqft\":\"".str_replace($_quitar,"",$lsqft).
		"\",\"pool\":\"".str_replace($_quitar,"",$pool).
		"\",\"saleprice\":\"".str_replace($_quitar,"",$saleprice).
		"\",\"tsqft\":\"".str_replace($_quitar,"",$tsqft).
		"\",\"value\":\"".str_replace($_quitar,"",$value).
		"\",\"zip\":\"".str_replace($_quitar,"",$zip).
		"\",\"waterf\":\"".str_replace($_quitar,"",$waterf)."\"}";
	
	mysql_free_result($result);
}// end de For
//****************************************[Maria Oliveira] TABLA CAMPTIT*****************************

//mysql_select_db("xima");
$rSql="SELECT CAMPTIT.* FROM xima.CAMPTIT ";
$result = mysql_query($rSql) or die (mysql_error());
$i=0;
$array_campos=array();
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	if ($i>0) $Camptit.=",";		
		$Camptit.= 
		"{\"table\":\"".strtolower($row["Tabla"]). 
		"\",\"title\":\"".$row["Titulos"].  
		"\",\"field\":\"".strtolower($row["Campos"]).
		"\",\"desc\":\"".strtolower($row["Desc"]).  
		"\"}";
	$i++;
}
//--------------------------------------------------------------------------
//Variable de session para pasar el array de los parcel's del grid para la impresion de labels de Owner
$_SESSION["parcels"]=$array_parcelIds;		
//--------------------------------------------------------------------------

$ml="{".$ml."}";
//PARA DIFFVALUE
$Diff="{".$Diff."}";


echo $Filas."^".$Camptit."^".$Diff."^".$ml."^".$sql."^".$query;*/

$num_rows_all=count($array_datos);

if(isset($_POST['start'])) $array_datos_limit=array_slice($array_datos,$_POST['start'],$_POST['limit']);
else $array_datos_limit=$array_datos;
unset($array_datos);

$return= "{\"metaData\": {\"totalProperty\": \"total\",\"root\": \"records\",\"id\": \"id\",\"fields\": [";
	$return.='"pid","Distance","status","pin_xlat","pin_xlong","pin_address","pin_lsqft","pin_larea","pin_bed","pin_bath","pin_saleprice"';
	foreach($hdArray as $k => $val){
		$tipo=($val->type=='boolean' || $val->type=='pendes' || $val->type=='date') ? 'string' : $val->type;
		$tipo=$tipo=='real' ? 'float' : $tipo;
		$return.=",{\"name\": \"".$val->name."\",\"type\": \"".$tipo."\"}";
	}
$return.="]},\"success\": true,\"total\":".$num_rows_all.",\"records\": [";
	foreach($array_datos_limit as $k=>$val){
		if($k>0) $return.=",";
		$return.="{";
		$m=0;
		foreach($val as $l=>$val2){
			if($m>0) $return.=",";
			if($l=='status')
				$return.="\"$l\": \"".(($_POST['start'])+$k+1)."$val2\"";
			else
				$return.="\"$l\": \"$val2\"";
			$m++;
		}
		$return.="}";
	}
$return.="],\"columns\": [";
	$return.="{\"header\": \"Sta\",\"dataIndex\": \"status\",\"hidden\": false,\"width\": 30, renderer: gridgetcasita, sortable: true, tooltip: \"Status Property.\"},{\"header\": \"Dis.\",\"dataIndex\": \"Distance\",\"hidden\": false,\"width\": 50, sortable: true, tooltip: \"Distance.\"}";
	foreach($hdArray as $k => $val){
			if($val->type=='real')
				$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false,\"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\", xtype: \"numbercolumn\"}";
			elseif($val->name=='pendes')
				$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: gridgetsold,\"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
			else
				$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false,\"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
	}
$return.="]}";


if(isset($_POST['jsonapps'])){
    $return= "{\"success\": true,\"total\":".$num_rows_all.",\"records\": [";
	foreach($array_datos_limit as $k=>$val){
		if($k>0) $return.=",";
		$return.="{";
		$m=0;
		foreach($val as $l=>$val2){
			if($m>0) $return.=",";
			if($l=='status'){
                $return.="\"$l\": \"".(($_POST['start'])+$k+1)."$val2\"";
            }else
				$return.="\"$l\": \"$val2\"";
			$m++;
		}
		$return.="}";
	}
$return.="]}";
}

unset($array_datos_limit);
unset($hdArray);
echo $return;
?>