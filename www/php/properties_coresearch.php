<?php  
include('properties_conexion.php');
	
if(isset($_POST['resultby'])){// GUARDAR PARAMETROS DE AGRUPACION DEL INVESTOR
	$query='UPDATE xima.xima_system_var SET resultby="'.(string)$_POST['resultby'].'",filter_buyer_owns='.(integer)$_POST['owns'].',search_filter_groupby="'.(string)$_POST['groupby'].'" WHERE userid='.$_COOKIE['datos_usr']['USERID'];
	//echo $query;
	conectar();
	mysql_query($query) or die($query.mysql_error());
	echo "{success:true}";	
		
}elseif(isset($_POST['latitude'])){
	$query='UPDATE xima.xima_system_var SET latGps="'.(float)$_POST['latitude'].'",lonGps='.(float)$_POST['longitude'].', search="My Location", distanceLocation="'.(float)$_POST['distancia'].'" WHERE userid='.$_COOKIE['datos_usr']['USERID'];
	//echo $query;
	conectar();
	mysql_query($query) or die($query.mysql_error());
	echo "{success:true}";	
}elseif($_POST['searchType']<>'advance')// GUARDAR PARAMETROS DE BUSQUEDA DEL SEARCH BASICO
{
	if(isset($_COOKIE['datos_usr']['USERID'])){
		conectar();
		$query='SELECT * FROM xima.xima_system_var WHERE userid='.$_COOKIE['datos_usr']['USERID'];
		$result=mysql_query($query) or die($query.mysql_error());
		if(mysql_num_rows($result)==0){
			$query='INSERT INTO xima.xima_system_var (userid) VALUES ('.$_COOKIE['datos_usr']['USERID'].')';
			mysql_query($query) or die($query.mysql_error());
		}
		
		
		//Verificación de los DREIAPRO
		$dreiapro = false;
		$query='SELECT * FROM xima.ximausrs WHERE userid='.$_COOKIE['datos_usr']['USERID'].' 
		AND (procode="DREIAPRO" OR executive=777 OR executive=3456 OR executive=3640)';
		$result=mysql_query($query) or die($query.mysql_error());
		if(mysql_num_rows($result)>0) $dreiapro=true;
		
		if($_POST['search']=='Address, City or Zip Code')
			$search='';
		else
			$search=(string)$_POST['search'];		 
			
		$orderby = "ORDER BY p.county,p.xcode asc  , if(length(p.address)=0 or p.address is null,1,0) ,p.address ASC";
		//Ordenamiento randon para los DREIAPRO
		if($dreiapro && $_POST['search_type']=='FS'){
			$orderRand = round(mt_rand() / mt_getrandmax() * 14.0);
			$orderDir = (mt_rand() / mt_getrandmax())<0.5 ? 'ASC' : 'DESC';
			switch($orderRand){
				case 0:
					$orderby = "ORDER BY p.county,p.xcode asc  , if(length(p.address)=0 or p.address is null,1,0) ,p.address ".$orderDir;
				break;
				case 1:
					$orderby = "ORDER BY p.address ".$orderDir;
				break;
				case 2:
					$orderby = "ORDER BY mlnumber ".$orderDir;
				break;
				case 3:
					$orderby = "ORDER BY p.parcelid ".$orderDir;
				break;
				case 4:
					$orderby = "ORDER BY mlsresidential.yrbuilt ".$orderDir;
				break;
				case 5:
					$orderby = "ORDER BY dom ".$orderDir;
				break;
				case 6:
					$orderby = "ORDER BY larea ".$orderDir;
				break;
				case 7:
					$orderby = "ORDER BY mlsresidential.beds ".$orderDir;
				break;
				case 8:
					$orderby = "ORDER BY mlsresidential.bath ".$orderDir;
				break;
				case 9:
					$orderby = "ORDER BY price ".$orderDir;
				break;
				case 10:
					$orderby = "ORDER BY marketvalue.marketvalue ".$orderDir;
				break;
				case 11:
					$orderby = "ORDER BY marketvalue.offertvalue ".$orderDir;
				break;
				case 12:
					$orderby = "ORDER BY marketvalue.marketpctg ".$orderDir;
				break;
				case 13:
					$orderby = "ORDER BY marketvalue.offertpctg ".$orderDir;
				break;
				case 14:
					$orderby = "ORDER BY marketvalue.debttv ".$orderDir;
				break;
			}
		}
		
		$query='UPDATE xima.xima_system_var 
				SET search_type="'.$_POST['search_type'].'",
					search="'.$search.'",
					search_filter_county='.$_POST['county'].',
					tsearch="'.(string)$_POST['tsearch'].'",
					resultby="'.(string)$_POST['resultby'].'",
					search_filter_proptype="'.(string)$_POST['proptype'].'",
					date_low="'.(string)$_POST['date_low'].'",
					date_hi="'.(string)$_POST['date_hi'].'",
					search_filter_price_low='.(float)$_POST['price_low'].',
					search_filter_price_hi='.(float)$_POST['price_hi'].',
					search_filter_bed='.(integer)$_POST['bed'].',
					search_filter_bath='.(integer)$_POST['bath'].',
					filter_buyer_owns='.(integer)$_POST['owns'].',
					search_filter_sqft='.(integer)$_POST['sqft'].',
					search_filter_pequity="'.$_POST['pequity'].'",
					search_filter_pendes="'.(string)$_POST['pendes'].'",
					search_filter_entrydate="'.(string)$_POST['entrydate'].'",
					search_filter_mapa="'.(string)$_POST['search_mapa'].'",
					search_filter_groupby="'.(string)$_POST['groupby'].'",
					search_filter_ownerocc=null,
					search_filter_probate=null,
					search_filter_lifeestate=null,					
					list_text_search="",
					orderby="'.$orderby.'",
					search_filter_ownerocc="'.(string)$_POST['occupied'].'" 
					WHERE userid='.$_COOKIE['datos_usr']['USERID'];
			//echo $query;
		
		mysql_query($query) or die($query.mysql_error());
	}else{	
		session_start();
		$_SESSION['query_search']['type_search']=$_POST['search_type'];
				
		if($_POST['search']=='Address, City or Zip Code'){
			$_SESSION['query_search']['search']='';
		}else{
			$_SESSION['query_search']['search']=(string)$_POST['search'];
		}
			
		$_SESSION['query_search']['search_filter_county']=(string)$_POST['county'];
		$_SESSION['query_search']['tsearch']=(string)$_POST['tsearch'];
		$_SESSION['query_search']['search_filter_proptype']=(string)$_POST['proptype'];
		$_SESSION['query_search']['search_filter_price_low']=(string)$_POST['price_low'];
		$_SESSION['query_search']['search_filter_price_hi']=(string)$_POST['price_hi'];
		$_SESSION['query_search']['search_filter_bed']=(string)$_POST['bed'];
		$_SESSION['query_search']['search_filter_bath']=(string)$_POST['bath'];
		$_SESSION['query_search']['search_filter_sqft']=(string)$_POST['sqft'];
		$_SESSION['query_search']['search_filter_pequity']=(string)$_POST['pequity'];
		$_SESSION['query_search']['search_filter_pendes']=(string)$_POST['pendes'];
		$_SESSION['query_search']['search_filter_entrydate']=(string)$_POST['entrydate'];
		$_SESSION['query_search']['search_filter_occupied']=(string)$_POST['occupied'];
		$_SESSION['query_search']['search_filter_mapa']=(string)$_POST['search_mapa'];
		
		$_SESSION['query_search']['list_text_search']=array();
		$_SESSION['query_search']['orderby']='ORDER BY p.county,p.xcode asc  , if(length(p.address)=0 or p.address is null,1,0) ,p.address ASC';
	}
}
else// GUARDAR PARAMETROS DE BUSQUEDA DEL SEARCH AVANZADO
{
	conectar();
	
	$query='SELECT * FROM xima.xima_system_var WHERE userid='.$_COOKIE['datos_usr']['USERID'];
	$result=mysql_query($query) or die($query.mysql_error());
	if(mysql_num_rows($result)==0){
		$query='INSERT INTO xima.xima_system_var (userid) VALUES ('.$_COOKIE['datos_usr']['USERID'].')';
		mysql_query($query) or die($query.mysql_error());
	}
	
	//Verificación de los DREIAPRO
	$dreiapro = false;
	$query='SELECT * FROM xima.ximausrs WHERE userid='.$_COOKIE['datos_usr']['USERID'].' 
	AND (procode="DREIAPRO" OR executive=777 OR executive=3456 OR executive=3640)';
	$result=mysql_query($query) or die($query.mysql_error());
	if(mysql_num_rows($result)>0) $dreiapro=true;
	
	$orderby='ORDER BY p.county,p.xcode asc  , if(length(p.address)=0 or p.address is null,1,0) ,p.address ASC';
	//Ordenamiento randon para los DREIAPRO
	if($dreiapro && $_POST['ocproperty']=='FS'){
		$orderRand = round(mt_rand() / mt_getrandmax() * 10.0);
		$orderDir = (mt_rand() / mt_getrandmax())<0.5 ? 'ASC' : 'DESC';
		switch($orderRand){
			case 0:
				$orderby = "ORDER BY p.county,p.xcode asc  , if(length(p.address)=0 or p.address is null,1,0) ,p.address ".$orderDir;
			break;
			case 1:
				$orderby = "ORDER BY p.address ".$orderDir;
			break;
			case 2:
				$orderby = "ORDER BY mlsresidential.yrbuilt ".$orderDir;
			break;
			case 3:
				$orderby = "ORDER BY larea ".$orderDir;
			break;
			case 4:
				$orderby = "ORDER BY mlsresidential.beds ".$orderDir;
			break;
			case 5:
				$orderby = "ORDER BY mlsresidential.bath ".$orderDir;
			break;
			case 6:
				$orderby = "ORDER BY price ".$orderDir;
			break;
			case 7:
				$orderby = "ORDER BY marketvalue.marketvalue ".$orderDir;
			break;
			case 8:
				$orderby = "ORDER BY marketvalue.offertvalue ".$orderDir;
			break;
			case 9:
				$orderby = "ORDER BY marketvalue.marketpctg ".$orderDir;
			break;
			case 10:
				$orderby = "ORDER BY marketvalue.offertpctg ".$orderDir;
			break;
		}
	}
	
	$query="UPDATE xima.xima_system_var SET
			search_type=null,search_filter_county=null,
			search_filter_proptype=null,
			search_filter_pendes=null,
			search_filter_mapa=null,
			orderby=null, 
			search_filter_ownerocc=null,
			search_filter_probate=null,
			search_filter_lifeestate=null,
			search_filter_mapa='-1',
			filter_search_adv=null 
			WHERE userid=".$_COOKIE['datos_usr']['USERID'];
	mysql_query($query) or die($query.mysql_error());

	//print_r($_POST);
	$propoutput=$_POST['ocproperty'];
	$county=$_POST['occounty']=='FL' ? 1 : $_POST['occounty'];
	$protype=$_POST['ocproptype'];
	$foreclosure=$_POST['ocforeclosure'];
	$state=$_POST['ocstate']=='FL' ? 1 : $_POST['ocstate'];
	$probate=$_POST['ocprobate'];
	$lifeestate=$_POST['oclifeestate'];
	$ownerocc=$_POST['ocownerocc'];
	$mapa=$_POST['mapa_search_latlongAdv'];	
	$mylocation=$_POST['mylocation'];
	unset($filtrosadv);
	reset($_POST);
	while (list($key, $value) = each($_POST)) 
	{
//if($_COOKIE['datos_usr']['USERID']==20)	echo "<br/><br/> antes --> ".$key."|".$value;
		$pos = strpos($key, '*other');
		if ($pos === false && substr($key,0,2)<>'oc' && $key<>'searchType' && $key<>'mapa_search_latlongAdv' && $key<>'mylocation')//si no son parametros de busqueda iniciales
		{
			$tabla=$key;
			$tabla=str_replace('cbh','',$tabla);
			$tabla=str_replace('tx','',$tabla);
			$arr=explode('*',trim($tabla));
			$tabla=$arr[0];
			$campo=$arr[1];
			$idct=$arr[2];
			$combofiltro=$value;
			
			list($key, $value) = each($_POST);
			$value=str_replace("'",'',$value);
			$valorfiltro=$value;
			
			if($combofiltro<>'Between')
			{
//if($_COOKIE['datos_usr']['USERID']==20)	echo "<br/> dif between if--> ".$key."|".$value."|".$valorfiltro;
				if(	(isset($valorfiltro) && strlen($valorfiltro)>0 ) || 
					($combofiltro=='Yes' || $combofiltro=='No' 	 || $combofiltro=='Foreclosed (REO)'  
					|| $combofiltro=='Short Sale'  || $combofiltro=='Other'   
					|| $combofiltro=='Empty' || $combofiltro=='Not Empty' || $combofiltro=='Never For Sale') 
				)
					$filtrosadv[]=array('idct'=>$idct, 'condition'=>$combofiltro, 'value'=>$valorfiltro, 'value2'=>'');
			}
			else
			{
//if($_COOKIE['datos_usr']['USERID']==20)	echo "<br/> dif between else1 --> ".$key."|".$valorfiltro;
				list($key, $value) = each($_POST);
				$valorfiltro2=$value;
//if($_COOKIE['datos_usr']['USERID']==20)	echo "<br/> dif between else2 --> ".$key."|".$value;
				if(isset($valorfiltro) && strlen($valorfiltro)>0 && isset($valorfiltro2) && strlen($valorfiltro2)>0 )
					$filtrosadv[]=array('idct'=>$idct, 'condition'=>$combofiltro, 'value'=>$valorfiltro, 'value2'=>$valorfiltro2);
			}
		}
	}
	
	$qjsofil='';
	if(isset($filtrosadv))
		$qjsofil=",filter_search_adv='".json_encode($filtrosadv)."'";
	
	
	$query="UPDATE xima.xima_system_var SET
			search_type='$propoutput',
			search_filter_county='$county',
			search_filter_proptype='$protype',
			search_filter_pendes='$foreclosure',
			search_filter_mapa='$mapa',
			orderby='$orderby', 
			search_filter_ownerocc='$ownerocc',
			search_filter_probate='$probate',
			search_filter_lifeestate='$lifeestate',
			searchByLocation='$mylocation'
			$qjsofil 
			WHERE userid=".$_COOKIE['datos_usr']['USERID'];//search_filter_mapa="'.(string)$_POST['search_mapa'].'",
	mysql_query($query) or die($query.mysql_error());

	echo "{success:true,errors: { reason: 'Update successfully' }}";
}
?>