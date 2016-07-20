<?php
	$bpoaction=(isset($_POST['type']) && $_POST['type']=='nobpo') ? false : true;

	if(!$bpoaction) include("properties_conexion.php");
	$el_comparado=$_POST["id"];
	$proper=$_POST["prop"];
	$bd=$_POST['bd'];
	conectarPorBD($bd);
	
	$priceFactor = 5000;
	if($bd=='flbroward' || $bd=='fldade' || $bd=='flpalmbeach') $priceFactor = 19000;

	$_status=explode(",",$_POST["status"]);	
	if(strlen($proper)==1)$proper='0'.$proper;
	
	$permission=array();
	if(isset($_COOKIE['datos_usr']['USERID'])){
		$qp='SELECT * FROM xima.permission WHERE userid='.$_COOKIE['datos_usr']['USERID'];
		$result = mysql_query($qp) or die($qp.mysql_error());
		$permission = mysql_fetch_assoc($result);
	}

	$php_string='properties_compType_'.$proper.'.php';

	include($php_string);
	
	if($bpoaction){
		if ($Filas=="") echo "ERROR^Sorry there are not Comparables for this record^".$_POST["php_grid"]."^$Camptit"; 
		else echo $Filas."^".$Camptit."^".$Diff;
	}elseif(isset($_POST['jsonapps'])){
        $num_rows_all=count($array_datos);

		if(isset($_POST['start'])) $array_datos_limit=array_slice($array_datos,$_POST['start'],$_POST['limit']);
		else $array_datos_limit=$array_datos;
		unset($array_datos);
        
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
        
        unset($array_datos_limit);
		unset($hdArray);

		echo $return;
    }else{
		$num_rows_all=count($array_datos);

		if(isset($_POST['start'])) $array_datos_limit=array_slice($array_datos,$_POST['start'],$_POST['limit']);
		else $array_datos_limit=$array_datos;
		unset($array_datos);
		
		$return= "{\"metaData\": {\"totalProperty\": \"total\",\"root\": \"records\",\"id\": \"id\",\"fields\": [";
			$return.='"pid","Distance","status","pin_xlat","pin_xlong","pin_address","pin_lsqft","pin_larea","pin_bed","pin_bath","pin_saleprice","diff_bath","diff_beds","diff_lsqft","diff_larea","diff_zip"';
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
			$return.="{\"header\": \"Sta\",\"dataIndex\": \"status\",\"hidden\": false,\"width\": 30, \"renderer\": gridgetcasita, \"sortable\": true, \"tooltip\": \"Status Property.\"},{\"header\": \"Dis.\",\"dataIndex\": \"Distance\",\"hidden\": false,\"width\": 50, \"sortable\": true, \"tooltip\": \"Distance.\"}";
			foreach($hdArray as $k => $val){
					if($val->type=='real')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false,\"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\", \"xtype\": \"numbercolumn\"}";
					elseif($val->name=='pendes')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"renderer\": gridgetsold,\"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
					elseif($val->name=='larea')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
					elseif($val->name=='beds')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
					elseif($val->name=='bath')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
					elseif($val->name=='lsqft')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
					elseif($val->name=='zip')
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
					else
						$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false,\"width\": ".$val->px_size.", \"sortable\": true, \"tooltip\": \"".$val->desc."\"}";
			}
		$return.="]}";
		unset($array_datos_limit);
		unset($hdArray);

		echo $return;
	}
?> 
