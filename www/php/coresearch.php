<?php 

include('properties_conexion.php');
conectar();
if($_GET['resultType']=='advance') include('properties_getgridcamptit.php');

function quitarCaracteres2($cad)
{
	$cad=strtoupper($cad);
	
	$cad=str_replace('\\','',$cad);
	//$cad=str_replace('-','',$cad);
	$cad=str_replace('\r\n','',$cad);
	$cad=str_replace("^","",$cad);
	$cad=str_replace(","," ",$cad);
	$cad=str_replace("'","",$cad);
	$cad=str_replace("#","",$cad);
	$cad=str_replace(".","",$cad);
	$cad=str_replace('"','',$cad);
	$cad=str_replace('/','',$cad);
	$cad=str_replace('     ',' ',$cad);
	$cad=str_replace('    ',' ',$cad);
	$cad=str_replace('   ',' ',$cad);
	$cad=str_replace('  ',' ',$cad);
	//echo $cad.'<br>';
	return $cad;
}

function getCaracteres($cad){
	$cad=strtoupper($cad);
	//echo $cad.'=';
	$cad=str_replace('CIRCLE','CIR',$cad);
	$cad=str_replace('CIRCL','CIR ',$cad);
	$cad=str_replace('CORT','CT',$cad);
	$cad=str_replace('PLACE','PL',$cad);
	$cad=str_replace('STREET','ST',$cad);
	$cad=str_replace('TERRACE','TER',$cad);
	$cad=str_replace('LINE','LN',$cad);
	$cad=str_replace('DRIVE','DR',$cad);
	$cad=str_replace('BOULEVARD','BLVD',$cad);
	$cad=str_replace('PARKWAY','PKWY',$cad);
	$cad=str_replace('ROAD','RD',$cad);
	$cad=str_replace('RUN','RN',$cad);
	$cad=str_replace('TRAIL','TRL',$cad);
	$cad=str_replace('TRACE','TRCE',$cad);
	
	if($cad=='AVENUE')$cad='AVE';
 	if($cad=='AV')$cad='AVE';
	if($cad=='EAST')$cad='E';
 	if($cad=='EA')$cad='E';
	if($cad=='SOUTH')$cad='S';
	if($cad=='SO')$cad='S';
	if($cad=='NORTH')$cad='N';
 	if($cad=='NO')$cad='N';
	if($cad=='WEST')$cad='W';
 	if($cad=='WE')$cad='W';
 
	//echo $cad.'<br>';
	return $cad;
}

function findCaracteres($cad){
	$cad=trim(strtoupper($cad));
	$busq=$cad;
	//if($_COOKIE['datos_usr']['USERID']==73) echo $cad.' = ';
	$cad=str_replace('CIRCLE','CIR',$cad);
	$cad=str_replace('CIRCL','CIR ',$cad);
	$cad=str_replace('CORT','CT',$cad);
	$cad=str_replace('PLACE','PL',$cad);
	$cad=str_replace('STREET','ST',$cad);
	$cad=str_replace('TERRACE','TER',$cad);
	$cad=str_replace('LINE','LN',$cad);
	$cad=str_replace('DRIVE','DR',$cad);
	$cad=str_replace('BOULEVARD','BLVD',$cad);
	$cad=str_replace('PARKWAY','PKWY',$cad);
	$cad=str_replace('ROAD','RD',$cad);
	$cad=str_replace('AVENUE','AVE',$cad);
	$cad=str_replace('AV','AVE',$cad);
	$cad=str_replace('RUN','RN',$cad);
	$cad=str_replace('TRAIL','TRL',$cad);
	$cad=str_replace('TRACE','TRCE',$cad);
	$cad=str_replace('EAST','E',$cad);
	$cad=str_replace('EA','E',$cad);
	$cad=str_replace('SOUTH','S',$cad);
	$cad=str_replace('SO','S',$cad);
	$cad=str_replace('NORTH','N',$cad);
	$cad=str_replace('NO','N',$cad);
	$cad=str_replace('WEST','W',$cad);
	$cad=str_replace('WE','W',$cad);
	//if($_COOKIE['datos_usr']['USERID']==73) echo $cad.'<br>';
	return $busq==$cad ? false : true;
}

function getOrdinal($number)
{
	// get first digit
	$digit = abs($number) % 10;
 	$ext = 'TH';
	$ext = ((abs($number) %100 < 21 && abs($number) %100 > 4) ? 'TH' : (($digit < 4) ? ($digit < 3) ? ($digit < 2) ? ($digit < 1) ? 'TH' : 'ST' : 'ND' : 'RD' : 'TH'));
 	return $number.$ext;
}

	if(isset($_COOKIE['datos_usr']['USERID'])){
		//echo 'Tabla.';
		$query='SELECT search,search_type,list_text_search,
		orderby,search_filter_proptype,search_filter_price_low,search_filter_price_hi,
		search_filter_bed,search_filter_bath,search_filter_sqft,search_filter_pequity,
		search_filter_pendes,search_filter_entrydate,search_filter_county,search_filter_mapa,
		tsearch,filter_search_adv,search_filter_ownerocc,search_filter_probate,search_filter_lifeestate,
		filter_buyer_owns,search_filter_groupby,resultby,latGps, lonGps, searchByLocation, distanceLocation
		FROM xima.xima_system_var
		WHERE userid='.$_COOKIE['datos_usr']['USERID'];
		
		$result=mysql_query($query) or die($query.mysql_error());
		$r=mysql_fetch_array($result);
		$texto=quitarCaracteres2(trim($r['search']));
		$type_search=$r['search_type'];
		if($texto=='MY LOCATION'){
			$searchByLocation='Y';
			$texto='';
		}else{
			$searchByLocation='N';
		}
		$arrtexto= explode(' ',$texto);
		$orderby=$r['orderby'];
		$proptype=$r['search_filter_proptype'];
		$price_low=$r['search_filter_price_low'];
		$price_hi=$r['search_filter_price_hi'];
		$bed=$r['search_filter_bed'];
		$bath=$r['search_filter_bath'];
		$sqft=$r['search_filter_sqft'];
		$pequity=$r['search_filter_pequity'];
		$pendes=$r['search_filter_pendes'];
		$entrydate=$r['search_filter_entrydate'];
		$county=$r['search_filter_county'];
		$mapa=$r['search_filter_mapa'];
		$owns=$r['filter_buyer_owns'];
		$resultby=$r['resultby'];
		$group_by=$r['search_filter_groupby'];
		$tsearch=$r['tsearch'];
		$_list_text_search=empty($r['list_text_search'])? NULL : json_decode($r['list_text_search']);
		$_list_text_search=NULL;
		$filter_search_adv=str_replace('[','',$r['filter_search_adv']);
		$filter_search_adv=str_replace(']','',$filter_search_adv);
		$ownerocc=$r['search_filter_ownerocc'];
		$probateocc=$r['search_filter_probate'];
		$lifeestateocc=$r['search_filter_lifeestate'];
		$latGps=$r['latGps'];
		$lonGps=$r['lonGps'];
		$searchGps=$r['searchByLocation'];
		$distanceGps=$r['distanceLocation'];
	}else{
		session_start();
		$texto=quitarCaracteres2(trim($_SESSION['query_search']['search']));
		//echo $texto;
		$type_search=$_SESSION['query_search']['type_search'];
		$arrtexto= explode(' ',$texto);
		$orderby=$_SESSION['query_search']['orderby'];
		$proptype=$_SESSION['query_search']['search_filter_proptype'];
		$price_low=$_SESSION['query_search']['search_filter_price_low'];
		$price_hi=$_SESSION['query_search']['search_filter_price_hi'];
		$bed=$_SESSION['query_search']['search_filter_bed'];
		$bath=$_SESSION['query_search']['search_filter_bath'];
		$sqft=$_SESSION['query_search']['search_filter_sqft'];
		$pequity=$_SESSION['query_search']['search_filter_pequity'];
		$pendes=$_SESSION['query_search']['search_filter_pendes'];
		$entrydate=$_SESSION['query_search']['search_filter_entrydate'];
		$county=$_SESSION['query_search']['search_filter_county'];
		$mapa=$_SESSION['query_search']['search_filter_mapa'];
		$tsearch=$_SESSION['query_search']['tsearch'];
		$ownerocc=$_SESSION['query_search']['search_filter_occupied'];
		$probateocc=$_SESSION['query_search']['search_filter_probate'];
		$lifeestateocc=$_SESSION['query_search']['search_filter_lifeestate'];
		$_list_text_search=empty($_SESSION['query_search']['list_text_search']) || is_null($_SESSION['query_search']['list_text_search'])? NULL : $_SESSION['query_search']['list_text_search'];
		$_list_text_search=NULL;
		//print_r($_list_text_search);
	}
	
	
	$retnum="";
	if($_GET['resultType']=='basic'){
		if(isset($max_page_pagin_r))
			$limit_cant_reg=$max_page_pagin_r;
		else
			$limit_cant_reg=10;
	}else{ 
		$limit_cant_reg=isset($_POST['limit']) ? $_POST['limit'] : 50;
		if(isset($_POST['start']) && $_POST['start']>0){
				$_GET['page']=($_POST['start']/$limit_cant_reg);
		}
	}
	$num_limit_page= isset($_GET['page'])? $_GET['page']:0;
	$limit=' LIMIT '.($num_limit_page*$limit_cant_reg).', '.$limit_cant_reg;
	$campos_adi=', p.address, p.city, p.zip, p.unit, p.url, p.county';
	if(isset($_COOKIE['datos_usr']['USERID']))
		$campos_adi.=', IF(fu.parcelid is not null AND fu.type!="DEL", fu.type, "0") as followup';
	
	if(count($arrtexto)==1 && $arrtexto[0]=='') $arrtexto=array();
	
	if($ownerocc=='*')$ownerocc='';
	if($probateocc=='*')$probateocc='';
	if($lifeestateocc=='*')$lifeestateocc='';
	
	$enc=0;
	$list_reg=array();
	$list_parcelid=array();
	$list_text_search=array();
	
	
	$filterwhere='';
	$leftjoinfilter='';
	$tabla_search='`properties_search`';
	$tabla_php='`properties_php`';
	$bd_search='`mantenimiento`';
	$jointable=array();
	$wherejoin='';
	
	$mostrarSoloPropertyFound=false;
	//print_r($_COOKIE['datos_usr']);
	//echo $type_search.'  '.$mostrarSoloPropertyFound;
	if($county!=-1){
		$bd_search='`'.conectarPorIdCounty($county).'`';
	}
	$countytext=str_replace('`','',strtoupper(substr($bd_search,3)));
	
	if($_GET['systemsearch']=='basic')//campos de busqueda el search basico
	{
		////////////////////////////////////filtros where/////////////////////////////////////
		if($proptype!='')
			$filterwhere.=" AND p.xcode = '$proptype'";
		if($pendes!=-1){
			$filterwhere.=" AND p.pendes = '$pendes'"; $mostrarSoloPropertyFound=true;
		}
		if($county!=-1 && $mapa!='-1'){
			$latlong=explode('/',str_replace("\'","",$mapa));
			$arrLats = array();
			$arrLons = array();
			$arrPoly = array();
			
			foreach($latlong as $k => $val){
				$par=explode(",",$val);
				$arrLats []=$par[0];
				$arrLons[]=$par[1];
				$arrPoly[]=array("lat"=>$par[0],"long"=>$par[1]);
			}
			
			$maxLat = max($arrLats);
			$minLat = min($arrLats);
			$maxLon = max($arrLons);
			$minLon = min($arrLons);
			
			$filterwhere.=" AND (p.latitude > $minLat AND p.latitude < $maxLat AND p.longitude > $minLon AND p.longitude < $maxLon)";	
		}
		if($searchByLocation=='Y'){
			if($_COOKIE['datos_usr']['USERID']==1719){
				$latGps=30.3589982986;
				$lonGps=-82.2548751831;
			}
			$factor=($distanceGps*0.015)/0.5;
			$latMin=$latGps-($factor);
			$latMax=$latGps+($factor);
			$lonMin=$lonGps-($factor);
			$lonMax=$lonGps+($factor); 
			$filterwhere.=" AND (p.latitude > $latMin AND p.latitude < $latMax AND p.longitude > $lonMin AND p.longitude < $lonMax)";
			
		}
		if($ownerocc!=-1 && strlen($ownerocc)>0 && $ownerocc!='') $filterwhere.=" AND `marketvalue`.ownocc = '$ownerocc'";
		if($probateocc!=-1 && strlen($probateocc)>0 && $probateocc!='') $filterwhere.=" AND `marketvalue`.probate = '$probateocc'";
		if($lifeestateocc!=-1 && strlen($lifeestateocc)>0 && $lifeestateocc!='') $filterwhere.=" AND `marketvalue`.lifeestate = '$lifeestateocc'";
		
		//echo ' / '.$mostrarSoloPropertyFound;
		
		if($type_search=='FS'){

			if(strlen($price_low)>0 && $price_low!='min'){
				if(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
					if($price_low > $price_hi){
						$price_aux = $price_hi;
						$price_hi = $price_low;
						$price_low = $price_aux;
					}
					$filterwhere.=" AND (`mlsresidential`.lprice >= $price_low AND `mlsresidential`.lprice <= $price_hi)";
				}else
					$filterwhere.=" AND `mlsresidential`.lprice >= $price_low ";
					
			}elseif(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
				$filterwhere.=" AND `mlsresidential`.lprice <= $price_hi ";
			}	
			
			if($pequity!=-1 && ($proptype=='' || $proptype=='01' || $proptype=='04')){
				//echo 'Entro';
				$filterwhere.=" AND `marketvalue`.marketpctg $pequity";
				$mostrarSoloPropertyFound=true;
			}
			if($bed!=-1)
				$filterwhere.=" AND `mlsresidential`.beds >= $bed";
			if($bath!=-1)
				$filterwhere.=" AND `mlsresidential`.bath >= $bath";
			if($sqft!=-1)
				$filterwhere.=" AND if(`mlsresidential`.lsqft > 0,`mlsresidential`.lsqft,`mlsresidential`.apxtotsqft) >= $sqft";
			
			
			if($county==-1){
				$tabla_search='`properties_search_l`';
				$tabla_php='`properties_php_l`';
			}else{
				$jointable[]='`mlsresidential`';
				$wherejoin=' AND `mlsresidential`.status=\'A\' ';
				if($_GET['resultType']=='basic')
					$campos_result= ', `mlsresidential`.beds, `mlsresidential`.bath, if(`mlsresidential`.lsqft > 0,`mlsresidential`.lsqft,`mlsresidential`.apxtotsqft) sqft, `mlsresidential`.lsqft garea, `mlsresidential`.larea, `mlsresidential`.tsqft, p.xcoded, `mlsresidential`.xcode, `mlsresidential`.lprice price, if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima,
if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner, if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, `mlsresidential`.status, `marketvalue`.marketpctg equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, `marketvalue`.debttv, `mlsresidential`.mlnumber, `mlsresidential`.pool, `mlsresidential`.waterf, `mlsresidential`.yrbuilt, `mlsresidential`.dom, `marketvalue`.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ';
				else
					$campos_result=', s.parcelid as pid, concat("_",`mlsresidential`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, p.xcoded, p.xcode, if(`mlsresidential`.lsqft > 0,`mlsresidential`.lsqft,`mlsresidential`.apxtotsqft) sqft, `mlsresidential`.dom, p.address as pin_address, `mlsresidential`.lsqft as pin_lsqft, `mlsresidential`.apxtotsqft as pin_larea, `mlsresidential`.beds as pin_bed, `mlsresidential`.bath as pin_bath, `mlsresidential`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom, if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,\'Y\',\'N\') tieneImg, concat(imagenes.url,imagenes.url2,if(imagenes.letra=\'Y\',imagenes.mlnumber,substring(imagenes.mlnumber,2)),\'.\',imagenes.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima';
				$jointable[]='`marketvalue`';
				$jointable[]='`imagenes`';
				$jointable[]='`byowner_img`';
				$jointable[]='`diffvalue`';
			}
			
		}elseif($type_search=='PR'){
			if($county!=-1){
				
				if(strlen($price_low)>0 && $price_low!='min'){
					if(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
						if($price_low > $price_hi){
							$price_aux = $price_hi;
							$price_hi = $price_low;
							$price_low = $price_aux;
						}
						$filterwhere.=" AND (price >= $price_low AND price <= $price_hi)";
					}else
						$filterwhere.=" AND price >= $price_low ";
						
				}elseif(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
					$filterwhere.=" AND price <= $price_hi ";
				}
				
				if($pequity!=-1 && ($proptype=='' || $proptype=='01' || $proptype=='04')){
					$filterwhere.=" AND p.equity $pequity";
					$mostrarSoloPropertyFound=true;
				}
				
				if($bed!=-1)
					$filterwhere.=" AND p.beds >= $bed";
				if($bath!=-1)
					$filterwhere.=" AND p.bath >= $bath";
				if($sqft!=-1)
					$filterwhere.=" AND p.sqft >= $sqft";
				
				$jointable[]='`psummary`';
				$wherejoin=' ';
				if($_GET['resultType']=='basic')
					$campos_result= ', p.beds, p.bath, p.sqft, p.xcoded, p.xcode, p.price, if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima, if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner,if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, p.status, p.equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, `marketvalue`.debttv, `psummary`.yrbuilt, `psummary`.pool, `psummary`.waterf, `psummary`.lsqft garea, `psummary`.bheated larea, `psummary`.tsqft, `mlsresidential`.mlnumber, `mlsresidential`.dom, `marketvalue`.sold ';
				else
					$campos_result=', s.parcelid as pid, concat("_",p.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, p.address as pin_address, `psummary`.lsqft as pin_lsqft, `psummary`.bheated as pin_larea, p.beds as pin_bed, p.bath as pin_bath, p.price as pin_saleprice, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima';
				$jointable[]='`mlsresidential`';
				$jointable[]='`marketvalue`';
				$jointable[]='`imagenes`';
				$jointable[]='`byowner_img`';
				
			}else{
				$filterwhere='';
			}
			
			$tabla_search='`properties_search`';
			$tabla_php='`properties_php`';
			
		}elseif($type_search=='FO'){
			$mostrarSoloPropertyFound=true;
			
			if($pequity!=-1 && ($proptype=='' || $proptype=='01' || $proptype=='04'))
				$filterwhere.=" AND p.equity $pequity";
			
			if(strlen($entrydate)>0 && $entrydate!='yyyymmdd')
				$filterwhere.=" AND `pendes`.entrydate >= '$entrydate'";
			
			if($bed!=-1)
				$filterwhere.=" AND p.beds >= $bed";
			if($bath!=-1)
				$filterwhere.=" AND p.bath >= $bath";
			if($sqft!=-1)
				$filterwhere.=" AND p.sqft >= $sqft";
			
			$jointable[]='`pendes`';
			$wherejoin=' AND (`pendes`.pof=\'P\' OR `pendes`.pof=\'F\') ';
			if($_GET['resultType']=='basic')
				$campos_result= ', p.beds, p.bath, p.sqft, p.xcoded, p.xcode, p.price, if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima, if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner,if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, p.status, p.equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, `marketvalue`.debttv, `psummary`.yrbuilt, `psummary`.pool, `psummary`.waterf, `psummary`.lsqft garea, `psummary`.bheated larea, `psummary`.tsqft, `mlsresidential`.mlnumber, `mlsresidential`.dom, `marketvalue`.sold ';
			else
				$campos_result=', s.parcelid as pid, concat("_",p.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, p.address as pin_address, `psummary`.lsqft as pin_lsqft, `psummary`.bheated as pin_larea, p.beds as pin_bed, p.bath as pin_bath, p.price as pin_saleprice, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima';
			$jointable[]='`psummary`';
			$jointable[]='`mlsresidential`';
			$jointable[]='`marketvalue`';
			$jointable[]='`imagenes`';
			$jointable[]='`byowner_img`';
			
			$tabla_search='`properties_search`';
			$tabla_php='`properties_php`';
			
		}elseif($type_search=='FR'){

			if(strlen($price_low)>0 && $price_low!='min'){
				if(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
					if($price_low > $price_hi){
						$price_aux = $price_hi;
						$price_hi = $price_low;
						$price_low = $price_aux;
					}
					$filterwhere.=" AND (`rental`.lprice >= $price_low AND `rental`.lprice <= $price_hi)";
				}else
					$filterwhere.=" AND `rental`.lprice >= $price_low ";
					
			}elseif(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
				$filterwhere.=" AND `rental`.lprice <= $price_hi ";
			}	
				
			if($bed!=-1)
				$filterwhere.=" AND `rental`.beds >= $bed";
			if($bath!=-1)
				$filterwhere.=" AND `rental`.bath >= $bath";
			if($sqft!=-1)
				$filterwhere.=" AND if(`rental`.lsqft > 0,`rental`.lsqft,`rental`.apxtotsqft) >= $sqft";
				
			if($county==-1){
				$tabla_search='`properties_search_r`';
				$tabla_php='`properties_php_r`';
			}else{
				$jointable[]='`rental`';
				$wherejoin=' AND `rental`.status=\'A\' ';
				if($_GET['resultType']=='basic')
					$campos_result= ', `rental`.beds, `rental`.bath, if(`rental`.lsqft > 0,`rental`.lsqft,`rental`.apxtotsqft) sqft, `mlsresidential`.lsqft garea, `mlsresidential`.apxtotsqft larea, `mlsresidential`.tsqft, p.xcoded, p.xcode, `rental`.lprice price, if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima, if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner,if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, `rental`.status, `marketvalue`.marketpctg equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, `marketvalue`.debttv, `rental`.mlnumber, `rental`.pool, `rental`.waterf, `mlsresidential`.yrbuilt, `mlsresidential`.dom, `marketvalue`.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ';
				else
					$campos_result=', s.parcelid as pid, concat("_",`rental`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, p.xcoded, p.xcode, if(`rental`.lsqft > 0,`rental`.lsqft,`rental`.apxtotsqft) sqft, `mlsresidential`.dom, p.address as pin_address, `mlsresidential`.lsqft as pin_lsqft, `mlsresidential`.apxtotsqft as pin_larea, `rental`.beds as pin_bed, `rental`.bath as pin_bath, `rental`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom, if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,\'Y\',\'N\') tieneImg, concat(imagenes.url,imagenes.url2,if(imagenes.letra=\'Y\',imagenes.mlnumber,substring(imagenes.mlnumber,2)),\'.\',imagenes.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima';
				$jointable[]='`mlsresidential`';
				$jointable[]='`marketvalue`';
				$jointable[]='`imagenes`';
				$jointable[]='`byowner_img`';
				$jointable[]='`diffvalue`';
			}
				
		}elseif($type_search=='BO'){

			if(strlen($price_low)>0 && $price_low!='min'){
				if(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
					if($price_low > $price_hi){
						$price_aux = $price_hi;
						$price_hi = $price_low;
						$price_low = $price_aux;
					}
					$filterwhere.=" AND (`byowner_s`.lprice >= $price_low AND `byowner_s`.lprice <= $price_hi)";
				}else
					$filterwhere.=" AND `byowner_s`.lprice >= $price_low ";
					
			}elseif(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
				$filterwhere.=" AND `byowner_s`.lprice <= $price_hi ";
			}
				
			if($pequity!=-1 && ($proptype=='' || $proptype=='01' || $proptype=='04')){
				//echo 'Entro';
				$filterwhere.=" AND `marketvalue`.marketpctg $pequity";
				$mostrarSoloPropertyFound=true;
			}
			if($bed!=-1)
				$filterwhere.=" AND `byowner_s`.beds >= $bed";
			if($bath!=-1)
				$filterwhere.=" AND `byowner_s`.bath >= $bath";
			if($sqft!=-1)
				$filterwhere.=" AND if(`byowner_s`.lsqft > 0,`byowner_s`.lsqft,`byowner_s`.apxtotsqft) >= $sqft";
			
			
			if($county==-1){
				$tabla_search='`properties_search_l`';
				$tabla_php='`properties_php_l`';
			}else{
				$jointable[]='`byowner_s`';
				$wherejoin=' AND `byowner_s`.parcelid is not null AND `byowner_s`.status="A" ';
				if($_GET['resultType']=='basic')
					$campos_result= ', `byowner_s`.beds, `byowner_s`.bath, if(`byowner_s`.lsqft > 0,`byowner_s`.lsqft,`byowner_s`.apxtotsqft) sqft, `byowner_s`.lsqft garea, `byowner_s`.larea, `byowner_s`.tsqft, p.xcoded, `byowner_s`.xcode, `byowner_s`.lprice price, if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima,if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner, if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, `byowner_s`.status, `marketvalue`.marketpctg equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, `marketvalue`.debttv, `byowner_s`.mlnumber, `byowner_s`.pool, `byowner_s`.waterf, `byowner_s`.yrbuilt, `byowner_s`.dom, `marketvalue`.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ';
				else
					$campos_result=', s.parcelid as pid, concat("_",`byowner_s`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, p.address as pin_address, `byowner_s`.lsqft as pin_lsqft, `byowner_s`.apxtotsqft as pin_larea, `byowner_s`.beds as pin_bed, `byowner_s`.bath as pin_bath, `byowner_s`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima';
				$jointable[]='`marketvalue`';
				$jointable[]='`imagenes`';
				$jointable[]='`byowner_img`';
				$jointable[]='`diffvalue`';
			}
			
		}elseif($type_search=='BOR'){
	
			if(strlen($price_low)>0 && $price_low!='min'){
				if(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
					if($price_low > $price_hi){
						$price_aux = $price_hi;
						$price_hi = $price_low;
						$price_low = $price_aux;
					}
					$filterwhere.=" AND (`byowner_r`.lprice >= $price_low AND `byowner_r`.lprice <= $price_hi)";
				}else
					$filterwhere.=" AND `byowner_r`.lprice >= $price_low ";
					
			}elseif(strlen($price_hi)>0 && $price_hi!='max' && $price_hi>0){
				$filterwhere.=" AND `byowner_r`.lprice <= $price_hi ";
			}
				
			if($bed!=-1)
				$filterwhere.=" AND `byowner_r`.beds >= $bed";
			if($bath!=-1)
				$filterwhere.=" AND `byowner_r`.bath >= $bath";
			if($sqft!=-1)
				$filterwhere.=" AND if(`byowner_r`.lsqft > 0,`byowner_r`.lsqft,`byowner_r`.apxtotsqft) >= $sqft";
				
			if($county==-1){
				$tabla_search='`properties_search_r`';
				$tabla_php='`properties_php_r`';
			}else{
				$jointable[]='`byowner_r`';
				$wherejoin=' AND `byowner_r`.parcelid is not null AND `byowner_r`.status="A" ';
				if($_GET['resultType']=='basic')//`byowner_r`.tsqft,
					$campos_result= ', `byowner_r`.beds, `byowner_r`.bath, if(`byowner_r`.lsqft > 0,`byowner_r`.lsqft,`byowner_r`.apxtotsqft) sqft, `byowner_r`.lsqft garea, `byowner_r`.apxtotsqft larea,  p.xcoded, p.xcode, `byowner_r`.lprice price, if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima, if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner,if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, `byowner_r`.status, `marketvalue`.marketpctg equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, `marketvalue`.debttv, `byowner_r`.mlnumber, `byowner_r`.pool, `byowner_r`.waterf, `byowner_r`.yrbuilt, `byowner_r`.dom, `marketvalue`.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ';
				else
					$campos_result=', s.parcelid as pid, concat("_",`byowner_r`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, p.address as pin_address, `byowner_r`.lsqft as pin_lsqft, `byowner_r`.apxtotsqft as pin_larea, `byowner_r`.beds as pin_bed, `byowner_r`.bath as pin_bath, `byowner_r`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima';
				$jointable[]='`marketvalue`';
				$jointable[]='`imagenes`';
				$jointable[]='`byowner_img`';
				$jointable[]='`diffvalue`';
			}
				
		}
	}
	else if($_GET['systemsearch']=='advance' )//campos de busqueda el search avanzado, EN EL SELECT 	
	{

		//buscando la tabla principal para el query 
		//echo "filters<pre>";print_r($arrfiltersadv);echo "</pre>";
		$maintableadv=str_replace('`','',$tabla_php);
		if($type_search=='PR'){	$jointable[]='psummary';	}
		if($type_search=='FO'){	$jointable[]='pendes';	 }
		if($type_search=='MO'){	$jointable[]='mortgage';	}
		if($type_search=='FS'){	$jointable[]='mlsresidential';	}
		if($type_search=='FR'){	$jointable[]='rental';	}

		//if(strlen($orderby)==0 && $orderby=='')	$orderby=" ORDER BY $maintableadv.address ASC ";
			
		$selectdistinct='';
		$filtersadvaux='';
		$wherejoin=" WHERE 1=1 ";
		
		if($county!=-1 && $mapa!='-1'){
			$latlong=explode('/',str_replace("\'","",$mapa));
			$arrLats = array();
			$arrLons = array();
			$arrPoly = array();
			
			foreach($latlong as $k => $val){
				$par=explode(",",$val);
				$arrLats []=$par[0];
				$arrLons[]=$par[1];
				$arrPoly[]=array("lat"=>$par[0],"long"=>$par[1]);
			}
			
			$maxLat = max($arrLats);
			$minLat = min($arrLats);
			$maxLon = max($arrLons);
			$minLon = min($arrLons);
			
			$wherejoin.=" AND (p.latitude > $minLat AND p.latitude < $maxLat AND p.longitude > $minLon AND p.longitude < $maxLon)";	
		}
		if($searchGps=='Y'){
			$searchByLocation='Y';
			if($_COOKIE['datos_usr']['USERID']==1719){
				$latGps=30.3589982986;
				$lonGps=-82.2548751831;
			}
			$factor=($distanceGps*0.015)/0.5;
			$latMin=$latGps-($factor);
			$latMax=$latGps+($factor);
			$lonMin=$lonGps-($factor);
			$lonMax=$lonGps+($factor); 
			$wherejoin.=" AND (p.latitude > $latMin AND p.latitude < $latMax AND p.longitude > $lonMin AND p.longitude < $lonMax)";
		}else{
			$searchByLocation='N';
		}
		$jointable[]='marketvalue';
		//$jointable[]='latlong';
		$jointable[]='imagenes';
			
		if($proptype=='*')$proptype='';
		if($ownerocc=='*')$ownerocc='';
		if($probateocc=='*')$probateocc='';
		if($lifeestateocc=='*')$lifeestateocc='';
		if($pendes=='-1')$pendes='';
		//$state=$_POST['ocstate']; hay que traerlo de algun lado cuando pasemos a estados

		//echo "filters<pre>";print_r($arrfiltersadv);echo "</pre>";
		
		switch ($type_search)
		{
			case 'FS':
				//quitado por smith 29/10/2010 se bajo al foreach donde se contruyen los filtros
				//de manera que si se coloca algun filtro en la seccion de FOR SALE siempre le concatene 
				//`mlsresidential`.status='A'
				$wherejoin.=" AND `mlsresidential`.status='A' ";

				if($_GET['resultType']=='basic')
				{
					$campos_result= "mlsresidential.beds,mlsresidential.bath,if(mlsresidential.lsqft > 0,mlsresidential.lsqft,mlsresidential.apxtotsqft) sqft,
									mlsresidential.lsqft garea,mlsresidential.larea,mlsresidential.tsqft,p.xcoded,mlsresidential.xcode,mlsresidential.lprice price,
									if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg,imagenes.nphotos,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima,
									if(mlsresidential.xcode='01','PS_SF','PS_CONDOS')module,marketvalue.marketvalue,marketvalue.pendes,p.latitude,p.longitude,
									mlsresidential.status,marketvalue.marketpctg equity,marketvalue.offertvalue,marketvalue.marketpctg,marketvalue.offertpctg,
									marketvalue.debttv,mlsresidential.mlnumber,mlsresidential.pool,mlsresidential.waterf,mlsresidential.yrbuilt,mlsresidential.dom,marketvalue.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ";
					$jointable[]='mlsresidential';
					$jointable[]='psummary';
					$jointable[]='diffvalue';
				}
				else
				{
					$campos_result="mlsresidential.parcelid as pid,concat('_',mlsresidential.status,'-',marketvalue.pendes,if(`marketvalue`.sold='S',concat('-',`marketvalue`.sold),'')) as status,p.latitude as pin_xlat,
									p.longitude as pin_xlong,p.latitude,p.longitude,mlsresidential.address as pin_address,mlsresidential.lsqft as pin_lsqft,
									mlsresidential.apxtotsqft as pin_larea,mlsresidential.beds as pin_bed,mlsresidential.bath as pin_bath,mlsresidential.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom, if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg, concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,'.',`imagenes`.tipo)) imagenxima";
					$jointable[]='mlsresidential';
					$jointable[]='diffvalue';
				}
				$campos_result.=", mlsresidential.address, mlsresidential.city, p.zip, p.unit, p.url, p.county";
			break;
			case 'PR':
				$wherejoin.=" ";

				if($_GET['resultType']=='basic')
				{
					$campos_result="psummary.beds,psummary.bath,if(psummary.lsqft > 0,psummary.lsqft,psummary.bheated) sqft,p.xcoded,p.xcode,
									p.price,if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg,imagenes.nphotos,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima,
									if(p.xcode='01','PS_SF','PS_CONDOS') module,marketvalue.marketvalue,marketvalue.pendes,p.latitude,p.longitude,
									psummary.astatus status,marketvalue.marketpctg equity,marketvalue.offertvalue,marketvalue.marketpctg,marketvalue.offertpctg,
									marketvalue.debttv,psummary.yrbuilt,psummary.pool,psummary.waterf,psummary.lsqft garea,psummary.bheated larea,psummary.tsqft,
									mlsresidential.mlnumber,mlsresidential.dom,marketvalue.sold ";
					$jointable[]='psummary';
					$jointable[]='mlsresidential';
				}
				else
				{
					$campos_result="psummary.parcelid as pid,concat('_',psummary.astatus,'-',marketvalue.pendes,if(`marketvalue`.sold='S',concat('-',`marketvalue`.sold),'')) as status, p.latitude as pin_xlat, 
									p.longitude as pin_xlong,p.latitude,p.longitude, psummary.address as pin_address,psummary.lsqft as pin_lsqft,psummary.bheated as pin_larea,
									psummary.beds as pin_bed,psummary.bath as pin_bath,psummary.saleprice as pin_saleprice,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima";
					$jointable[]='psummary';
				}
				$campos_result.=", psummary.address, psummary.city, p.zip, p.unit,p.url,  p.county";
			break;
			case 'FO':
				$wherejoin.=" AND (`pendes`.pof='P' || `pendes`.pof='F') ";

				if($_GET['resultType']=='basic')
				{
					$campos_result="psummary.beds,psummary.bath,if(psummary.lsqft > 0,psummary.lsqft,psummary.bheated) sqft,p.xcoded,p.xcode,
									p.price,if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg,imagenes.nphotos,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima,
									if(p.xcode='01','PS_SF','PS_CONDOS') module,marketvalue.marketvalue,marketvalue.pendes,p.latitude,p.longitude,
									psummary.astatus status,marketvalue.marketpctg equity,marketvalue.offertvalue,marketvalue.marketpctg,marketvalue.offertpctg,
									marketvalue.debttv,psummary.yrbuilt,psummary.pool,psummary.waterf,psummary.lsqft garea,psummary.bheated larea,psummary.tsqft,
									mlsresidential.mlnumber,mlsresidential.dom,marketvalue.sold ";
					$jointable[]='mlsresidential';
					$jointable[]='psummary';
				}
				else
				{
					$campos_result="pendes.parcelid as pid, concat('_',psummary.astatus,'-',marketvalue.pendes,if(`marketvalue`.sold='S',concat('-',`marketvalue`.sold),''))  as status, p.latitude as pin_xlat,
									p.longitude as pin_xlong,p.latitude,p.longitude, psummary.address as pin_address, psummary.lsqft as pin_lsqft, psummary.bheated as pin_larea, 
									psummary.beds as pin_bed, psummary.bath as pin_bath, psummary.saleprice as pin_saleprice,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima";
					$jointable[]='mlsresidential';
					$jointable[]='psummary';
				}
				$campos_result.=", psummary.address, psummary.city,  p.zip, p.unit,p.url,  p.county";
			break;
			case 'MO':
				$wherejoin.=" ";
				//$filtersadvaux='';

				if($_GET['resultType']=='basic')
				{
					$campos_result="psummary.beds,psummary.bath,if(psummary.lsqft > 0,psummary.lsqft,psummary.bheated) sqft,p.xcoded,p.xcode,
									p.price,if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg,imagenes.nphotos,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima,
									if(p.xcode='01','PS_SF','PS_CONDOS') module,marketvalue.marketvalue,marketvalue.pendes,p.latitude,p.longitude,
									psummary.astatus status,marketvalue.marketpctg equity,marketvalue.offertvalue,marketvalue.marketpctg,marketvalue.offertpctg,
									marketvalue.debttv,psummary.yrbuilt,psummary.pool,psummary.waterf,psummary.lsqft garea,psummary.bheated larea,psummary.tsqft,
									mlsresidential.mlnumber,mlsresidential.dom,marketvalue.sold ";
					$jointable[]='mlsresidential';
					$jointable[]='psummary';
				}
				else
				{
					$campos_result="psummary.parcelid as pid,concat('_',psummary.astatus,'-',marketvalue.pendes,if(`marketvalue`.sold='S',concat('-',`marketvalue`.sold),'')) as status, p.latitude as pin_xlat, 
									p.longitude as pin_xlong,p.latitude,p.longitude, psummary.address as pin_address,psummary.lsqft as pin_lsqft,psummary.bheated as pin_larea,
									psummary.beds as pin_bed,psummary.bath as pin_bath,psummary.saleprice as pin_saleprice,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima";
					$jointable[]='psummary';
				}
				$campos_result.=", psummary.address, psummary.city,  p.zip, p.unit,p.url,  p.county";
				$orderby=" ORDER BY psummary.address ASC ";
				$selectdistinct=' DISTINCT ';
			break;
			case 'FR':
				$wherejoin.=" AND rental.status='A' ";

				if($_GET['resultType']=='basic')
				{
					$campos_result="rental.beds,rental.bath,if(rental.lsqft > 0,rental.lsqft,rental.apxtotsqft) sqft,mlsresidential.lsqft garea,
									mlsresidential.apxtotsqft larea,mlsresidential.tsqft,p.xcoded,p.xcode,rental.lprice price,
									if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg,imagenes.nphotos,
									concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen,
									if(imagenes.urlxima is null or length(imagenes.urlxima)<5,NULL,concat(imagenes.urlxima,imagenes.parcelid,'.',imagenes.tipo)) imagenxima,
									if(p.xcode='01','PS_SF','PS_CONDOS') module,marketvalue.marketvalue,marketvalue.pendes,p.latitude,
									p.longitude,rental.status,marketvalue.marketpctg equity,marketvalue.offertvalue,marketvalue.marketpctg,
									marketvalue.offertpctg,marketvalue.debttv,rental.mlnumber,rental.pool,rental.waterf,mlsresidential.yrbuilt,mlsresidential.dom,marketvalue.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ";
					$jointable[]='mlsresidential';
					$jointable[]='psummary';
					$jointable[]='diffvalue';
				}
				else
				{
					$campos_result="rental.parcelid as pid, concat('_',rental.status,'-',marketvalue.pendes,if(`marketvalue`.sold='S',concat('-',`marketvalue`.sold),'')) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, 
									mlsresidential.address as pin_address, mlsresidential.lsqft as pin_lsqft, mlsresidential.apxtotsqft as pin_larea, rental.beds as pin_bed, 
									rental.bath as pin_bath, rental.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom, if(length(imagenes.url)>0 OR length(imagenes.urlxima)>5,'Y','N') tieneImg, concat(imagenes.url,imagenes.url2,if(imagenes.letra='Y',imagenes.mlnumber,substring(imagenes.mlnumber,2)),'.',imagenes.tipo) imagen";
					$jointable[]='mlsresidential';
					$jointable[]='diffvalue';
				}
				$campos_result.=", mlsresidential.address, mlsresidential.city,  p.zip, p.unit,p.url,  p.county";
			break;
			case 'BO':
				$wherejoin.=' AND `byowner_s`.parcelid is not null AND `byowner_s`.status="A" ';

				if($_GET['resultType']=='basic')
				{
					$campos_result= ' `byowner_s`.beds, `byowner_s`.bath, if(`byowner_s`.lsqft > 0,`byowner_s`.lsqft,`byowner_s`.apxtotsqft) sqft, `byowner_s`.lsqft garea, 
									 `byowner_s`.larea, `byowner_s`.tsqft, p.xcoded, `byowner_s`.xcode, `byowner_s`.lprice price, 
									 if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,`imagenes`.nphotos,
									 concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, 
									 if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima,
									 if(`byowner_img`.urlxima is null or length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",
									 `byowner_img`.tipo)) imagenowner,if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude,
									 `byowner_s`.status, `marketvalue`.marketpctg equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, 
									 `marketvalue`.debttv, `byowner_s`.mlnumber, `byowner_s`.pool, `byowner_s`.waterf, `byowner_s`.yrbuilt, `byowner_s`.dom,marketvalue.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ';
					$jointable[]='byowner_s';
					$jointable[]='psummary';
					$jointable[]='`byowner_img`';
					$jointable[]='diffvalue';
				}
				else
				{
					$campos_result=' byowner_s.parcelid as pid, concat("_",`byowner_s`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, 
									p.address as pin_address, `byowner_s`.lsqft as pin_lsqft, `byowner_s`.apxtotsqft as pin_larea, `byowner_s`.beds as pin_bed, 
									`byowner_s`.bath as pin_bath, `byowner_s`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom';
					$jointable[]='byowner_s';
					$jointable[]='`byowner_img`';
					$jointable[]='diffvalue';
				}
				$campos_result.=", byowner_s.address, byowner_s.city,  p.zip, p.unit,p.url,  p.county";
			break;
			case 'BOR':
				$wherejoin.=" AND byowner_r.status='A' ";

				if($_GET['resultType']=='basic')
				{//`byowner_r`.tsqft,
					$campos_result= '`byowner_r`.beds, `byowner_r`.bath, if(`byowner_r`.lsqft > 0,`byowner_r`.lsqft,`byowner_r`.apxtotsqft) sqft, `byowner_r`.lsqft garea,
									`byowner_r`.apxtotsqft larea,  p.xcoded, p.xcode, `byowner_r`.lprice price, 
									if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5 OR length(`byowner_img`.urlxima)>5,"Y","N") tieneImg,
									`imagenes`.nphotos, concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,
									substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,
									concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima, if(`byowner_img`.urlxima is null or 
									length(`byowner_img`.urlxima)<5,NULL,concat(`byowner_img`.urlxima,`byowner_img`.parcelid,".",`byowner_img`.tipo)) imagenowner,
									if(p.xcode="01","PS_SF","PS_CONDOS") module, p.marketvalue, p.pendes, p.latitude, p.longitude, `byowner_r`.status, 
									`marketvalue`.marketpctg equity, `marketvalue`.offertvalue, `marketvalue`.marketpctg, `marketvalue`.offertpctg, 
									`marketvalue`.debttv, `byowner_r`.mlnumber, `byowner_r`.pool, `byowner_r`.waterf, `byowner_r`.yrbuilt, `byowner_r`.dom,marketvalue.sold, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom ';
					$jointable[]='byowner_r';
					$jointable[]='psummary';
					$jointable[]='`byowner_img`';
					$jointable[]='diffvalue';
				}
				else
				{
					$campos_result='byowner_r.parcelid as pid, concat("_",`byowner_r`.status,"-",p.pendes,if(`marketvalue`.sold="S",concat("-",`marketvalue`.sold),"")) as status, p.latitude as pin_xlat, p.longitude as pin_xlong,p.latitude,p.longitude, 
									p.address as pin_address, `byowner_r`.lsqft as pin_lsqft, `byowner_r`.apxtotsqft as pin_larea, `byowner_r`.beds as pin_bed,
									`byowner_r`.bath as pin_bath, `byowner_r`.lprice as pin_saleprice, `diffvalue`.bath as diff_bath, `diffvalue`.beds as diff_beds, `diffvalue`.lsqft as diff_lsqft, `diffvalue`.larea as diff_larea, `diffvalue`.zip as diff_zip, `diffvalue`.dom as diff_dom';
					$jointable[]='byowner_r';
					$jointable[]='`byowner_img`';
					$jointable[]='diffvalue';
				}
				$campos_result.=", byowner_r.address, byowner_r.city,  p.zip, p.unit,p.url,  p.county";

			break;
		}		
	}
	
	
	if (isset($_GET['groupbylevel']))
	{    
		if($_GET['groupbylevel']==1){
			$campos_adi='';
			$orderby=' ORDER BY count DESC';
		 
			if ($resultby=='ownername' || $resultby=='owneraddress'){
				if(array_search('`psummary`',$jointable)===false)
						$jointable[]='`psummary`';
			
				if ($group_by=='CO'){ 					  
					$filterwhere.=" AND (`psummary`.owner LIKE '%INC%' or `psummary`.owner LIKE '%LCC%' or `psummary`.owner LIKE '%LLC%' or `psummary`.owner LIKE '%ASSN%'  or `psummary`.owner LIKE '%CORP%') AND (`psummary`.owner NOT LIKE '%BANK%' and `psummary`.owner NOT LIKE '%FINANCIAL%' and `psummary`.owner NOT LIKE '%TRUST%' and `psummary`.owner NOT LIKE '%TRS%' and `psummary`.owner NOT LIKE '%MORTGAGE%' and `psummary`.owner NOT LIKE '%MORTG%' and `psummary`.owner NOT LIKE '%HOME LOAN%')";}
				
				if ($group_by=='TR'){
					$filterwhere.=" and (`psummary`.owner LIKE '%TRUST%' or `psummary`.owner LIKE '%TRS%') AND (`psummary`.owner NOT LIKE '%INC%' and `psummary`.owner NOT LIKE '%LCC%' and `psummary`.owner NOT LIKE '%ASSN%' and `psummary`.owner NOT LIKE '%LLC%' and `psummary`.owner NOT LIKE '%BANK%' and `psummary`.owner NOT LIKE '%MORTGAGE%' and `psummary`.owner NOT LIKE '%MORTG%' and `psummary`.owner NOT LIKE '%HOME LOAN%')";}	
				
				if ($group_by=='FB'){ 
					$filterwhere.=" and (`psummary`.owner LIKE '%BANK%' or `psummary`.owner LIKE '%FINANCIAL%' or `psummary`.owner LIKE '%MORTGAGE%' or `psummary`.owner LIKE '%HOME LOAN%')AND (`psummary`.owner NOT LIKE '%INC%' and `psummary`.owner NOT LIKE '%LCC%' and `psummary`.owner NOT LIKE '%ASSN%' and `psummary`.owner NOT LIKE '%LLC%' and `psummary`.owner NOT LIKE '%TRUST%' and `psummary`.owner NOT LIKE '%TRS%')";}
				
				if ($group_by=='IN'){ 
					$filterwhere.=" AND `psummary`.owner NOT LIKE '%INC%' AND `psummary`.owner NOT LIKE '%LCC%' and `psummary`.owner NOT LIKE '%LLC%' AND `psummary`.owner NOT LIKE '%ASSN%' AND `psummary`.owner NOT LIKE '%BANK%' AND `psummary`.owner NOT LIKE '%TRUST%' AND `psummary`.owner NOT LIKE '%MORTGAGE%' AND `psummary`.owner NOT LIKE '%MORTG%' AND `psummary`.owner NOT LIKE '%HOME LOAN%' AND `psummary`.owner NOT LIKE '%FINANCIAL%' AND `psummary`.owner NOT LIKE '% TR %' AND `psummary`.owner NOT LIKE '%LTD%' AND `psummary`.owner NOT LIKE '%New MLS%' AND `psummary`.owner NOT LIKE '%City%'";}
			
				if ($resultby=='ownername'){
					$groupby=' group by `psummary`.owner having count(`psummary`.owner) >= '.$owns.' and length(`psummary`.owner)>5 and `psummary`.owner <> \'</font>\'';
					$campos_result=' as pid,count(*) as count, `psummary`.owner as groupselect, `psummary`.owner_a, `psummary`.owner_c, `psummary`.owner_z ';	
				}else{
					$groupby=' group by `psummary`.owner_a,`psummary`.owner_c,`psummary`.owner_z having count(`psummary`.owner_a) >= '.$owns.' and length(`psummary`.owner_a)>5';
					$campos_result=' as pid,count(*) as count, CONCAT_WS(\', \',`psummary`.owner_a,`psummary`.owner_c,`psummary`.owner_z) as groupselect, `psummary`.owner ';	
				}
			}elseif($resultby=='agentname'){
				if(array_search('`mlsresidential`',$jointable)===false)
					$jointable[]='`mlsresidential`';
				
				$groupby=' group by `mlsresidential`.agent,`mlsresidential`.agentph having count(`mlsresidential`.agent) >= '.$owns.' and length(`mlsresidential`.agent)>5';
				$campos_result=' as pid,count(*) as count, CONCAT_WS(\', \',`mlsresidential`.agent,`mlsresidential`.agentph) as groupselect ';	

			}elseif($resultby=='lender'){
				if(array_search('`mortgage`',$jointable)===false)
					$jointable[]='`mortgage`';
				
				$groupby=' group by `mortgage`.mtg_lender having count(`mortgage`.mtg_lender) >= '.$owns.' and length(`mortgage`.mtg_lender)>5';
				$campos_result=' as pid,count(*) as count, `mortgage`.mtg_lender as groupselect ';	
				
			}elseif($resultby=='plaintiff'){
				if(array_search('`pendes`',$jointable)===false)
					$jointable[]='`pendes`';
				
				$groupby=' group by `pendes`.plaintiff1 having count(`pendes`.plaintiff1) >= '.$owns.' and length(`pendes`.plaintiff1)>5';
				$campos_result=' as pid,count(*) as count, `pendes`.plaintiff1 as groupselect ';	
							
			}
		}elseif($_GET['groupbylevel']==2){
			if ($resultby=='ownername' || $resultby=='owneraddress'){
				if(array_search('`psummary`',$jointable)===false)
					$jointable[]='`psummary`';
			
				$groupby=' ';
				
				if ($resultby=='ownername')
					$filterwhere.=" AND `psummary`.owner='".$_POST['groupselect']."' ";
				else
					$filterwhere.=" AND CONCAT_WS(', ',`psummary`.owner_a,`psummary`.owner_c,`psummary`.owner_z)='".$_POST['groupselect']."' ";
			}elseif($resultby=='agentname'){
				if(array_search('`mlsresidential`',$jointable)===false)
					$jointable[]='`mlsresidential`';
			
				$groupby=' ';
				
				$filterwhere.=" AND CONCAT_WS(', ',`mlsresidential`.agent,`mlsresidential`.agentph)='".$_POST['groupselect']."' ";

			}elseif($resultby=='lender'){
				if(array_search('`mortgage`',$jointable)===false)
					$jointable[]='`mortgage`';
			
				$groupby=' ';
				
				$filterwhere.=" AND `mortgage`.mtg_lender='".$_POST['groupselect']."' ";
			}elseif($resultby=='plaintiff'){
				if(array_search('`pendes`',$jointable)===false)
					$jointable[]='`pendes`';
			
				$groupby=' ';
				
				$filterwhere.=" AND `pendes`.plaintiff1='".$_POST['groupselect']."' ";
			}
		}
	}

	if($_GET['resultType']=='advance'){//ADICIONANDO LOS CAMPOS SI EL RESULT ES AVANZADO
	
		$ArSqlCT = array('idtc','campos','tabla','titulos','type','size','Desc','numformatted','decimals','align','px_size');//Search
		$ArDfsCT = array('idtc','name','tabla','title','type','size','desc','numformatted','decimal','align','px_size');//Searc
		
		$ArIDCT  = getArray($type_search,'result');
		if($_POST['ResultTemplate']!=-1)
			$ArIDCT  = getArray($_POST['ResultTemplate'],'template');
		
		if (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==1){
			if($resultby=='ownername') $ArIDCT  = array(20,21,22,25,24,23);
			if($resultby=='owneraddress') $ArIDCT  = array(21,22,25,24,23.20);
			if($resultby=='agentname') $ArIDCT  = array(62,66,67,604); 
			if($resultby=='lender') $ArIDCT  = array(323);
			if($resultby=='plaintiff') $ArIDCT  = array(502);
		}
		
		$campos_grid_defa = getCamptit($ArSqlCT, $ArDfsCT, $ArIDCT);
		$campos_grid_defa = str_replace(  "'",'"', $campos_grid_defa);	
		$campos_grid_defa = json_decode($campos_grid_defa);
		//print_r($campos_grid_defa);
		foreach($campos_grid_defa as $k => $val){
			if(array_search('`'.$val->tabla.'`',$jointable)===false)
				$jointable[]='`'.$val->tabla.'`';
			$campos_result.=', `'.$val->tabla.'`.'.$val->name;
			
			if(isset($_POST['sort']) && $val->name==$_POST['sort']){
				$orderby=' ORDER BY `'.$val->tabla.'`.'.$val->name.' '.$_POST['dir'];
				if( $val->name=='pendes')// $_COOKIE['datos_usr']['USERID']==20 &&
					$orderby=' ORDER BY concat(marketvalue.pendes," ",marketvalue.sold) '.$_POST['dir'];
				
				mysql_query("UPDATE xima.xima_system_var SET orderby='$orderby' WHERE userid=".$_COOKIE['datos_usr']['USERID']) or die(mysql_error());
			}elseif(isset($_POST['sort']) && $_POST['sort']=='count'){
				$orderby=' ORDER BY count '.$_POST['dir'];	
			}
		}
		if(isset($_COOKIE['datos_usr']['USERID'])){
			$campos_result.=', IF(fu.parcelid is not null AND fu.type!="DEL", fu.type, "0") as followup ';	
			if(isset($_POST['sort']) && $_POST['sort']=='followup'){
				$orderby=' ORDER BY followup '.$_POST['dir'];
				mysql_query("UPDATE xima.xima_system_var SET orderby='$orderby' WHERE userid=".$_COOKIE['datos_usr']['USERID']) or die(mysql_error());
			}
		}
		
	}
	
	if($_GET['systemsearch']=='basic')//filtros y query del search basico
	{
		if(is_null($_list_text_search)){
			//echo ' Buscando.';
			///BUSCANDO A TODAS LAS PALABRAS
			for($i=0;$i<count($arrtexto);$i++){
				$list_text_search[]=$arrtexto[$i];
				if($tsearch=='location'){
					if(is_numeric($arrtexto[$i])){
						$retnum.=" AND ((s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."') ";
						
						if($i>0){
							$retnum.=" OR (s.campo1='".getOrdinal($arrtexto[$i])."' OR s.campo2='".getOrdinal($arrtexto[$i])."' OR s.campo3='".getOrdinal($arrtexto[$i])."' OR s.campo4='".getOrdinal($arrtexto[$i])."' OR s.campo5='".getOrdinal($arrtexto[$i])."' OR s.campo6='".getOrdinal($arrtexto[$i])."' OR s.campo7='".getOrdinal($arrtexto[$i])."' OR s.campo8='".getOrdinal($arrtexto[$i])."' OR s.campo9='".getOrdinal($arrtexto[$i])."' OR s.campo10='".getOrdinal($arrtexto[$i])."' OR s.campo11='".getOrdinal($arrtexto[$i])."' OR s.campo12='".getOrdinal($arrtexto[$i])."' OR s.campo13='".getOrdinal($arrtexto[$i])."' OR s.campo14='".getOrdinal($arrtexto[$i])."' OR s.campo15='".getOrdinal($arrtexto[$i])."')";
						}
						$retnum.=") ";
							
						
					}else{
						if(findCaracteres($arrtexto[$i])){
							$retnum.=" AND (s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."' OR s.campo1='".getCaracteres($arrtexto[$i])."' OR s.campo2='".getCaracteres($arrtexto[$i])."' OR s.campo3='".getCaracteres($arrtexto[$i])."' OR s.campo4='".getCaracteres($arrtexto[$i])."' OR s.campo5='".getCaracteres($arrtexto[$i])."' OR s.campo6='".getCaracteres($arrtexto[$i])."' OR s.campo7='".getCaracteres($arrtexto[$i])."' OR s.campo8='".getCaracteres($arrtexto[$i])."' OR s.campo9='".getCaracteres($arrtexto[$i])."' OR s.campo10='".getCaracteres($arrtexto[$i])."' OR s.campo11='".getCaracteres($arrtexto[$i])."' OR s.campo12='".getCaracteres($arrtexto[$i])."' OR s.campo13='".getCaracteres($arrtexto[$i])."' OR s.campo14='".getCaracteres($arrtexto[$i])."' OR s.campo15='".getCaracteres($arrtexto[$i])."') ";
						}else{
							$retnum.=" AND (s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."') ";
						}
					}
				}elseif($tsearch=='parcelid'){
					$retnum.=" AND s.parcelid='".$arrtexto[$i]."'";
				}elseif($tsearch=='mlnumber'){
					if($type_search=='BOR') $retnum.=" AND byowner_r.mlnumber='".$arrtexto[$i]."'";
					elseif($type_search=='BO') $retnum.=" AND byowner_s.mlnumber='".$arrtexto[$i]."'";
					else $retnum.=" AND mlsresidential.mlnumber='".$arrtexto[$i]."'";
				}elseif($tsearch=='case'){
					$retnum.=" AND REPLACE(pendes.case_numbe,'-','')='".$arrtexto[$i]."'";
				}
			}
			$qwhere=$retnum;
					
			$query="SELECT count(DISTINCT s.parcelid) 
			FROM $bd_search.$tabla_search s ";
			
			if(strlen($filterwhere)>0)
				$query.="INNER JOIN $bd_search.$tabla_php p ON (s.parcelid=p.parcelid) ";
			
			if(count($jointable)>0){
				foreach($jointable as $k => $val)
					$query.="LEFT JOIN $bd_search.$val ON (s.parcelid=$val.parcelid) ";
			}
						
			$query.="WHERE 1=1 ".$wherejoin.$qwhere.$filterwhere.$groupby.' ';
			//echo  $query;
			$rest=mysql_query($query) or die($query.mysql_error());
			$r=mysql_fetch_array($rest);
			$num_rows_all=$r[0];
			
			if (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==1){			
				$num_rows_all=mysql_num_rows($rest);
			}
			
			if($num_rows_all>0){
				$query="SELECT DISTINCT s.parcelid $campos_adi $campos_result 
				FROM $bd_search.$tabla_search s ";
				$query.="INNER JOIN $bd_search.$tabla_php p ON (s.parcelid=p.parcelid) ";
				if(count($jointable)>0){
					foreach($jointable as $k => $val)
						$query.="LEFT JOIN $bd_search.$val ON (s.parcelid=$val.parcelid) ";
				}
				if(isset($_COOKIE['datos_usr']['USERID']))
					$query.="LEFT JOIN xima.followup fu ON (s.parcelid=fu.parcelid and userid=".$_COOKIE['datos_usr']['USERID'].") ";
				$query.="WHERE 1=1 ".$wherejoin.$qwhere.$filterwhere.$groupby.' '.$orderby;
				if(count($arrPoly)<=2) $query.=$limit;
				//echo $query;
				
				$rest=mysql_query($query) or die($query.mysql_error());
				while($r=mysql_fetch_array($rest,MYSQL_ASSOC)){
					$list_parcelid[]=$r['parcelid'];
					$list_reg[]=$r;
				}		
			}elseif(count($arrtexto)>1){
				$encontrado=false;
				$j=1;
				while(!$encontrado){
					$list_text_search=array();
					$retnum='';
	
					if($j==count($arrtexto)-1){$encontrado=true;}
					if(!$encontrado){
						for($i=0;$i<count($arrtexto)-$j;$i++){
							$list_text_search[]=$arrtexto[$i];
							if($tsearch=='location'){
								if(is_numeric($arrtexto[$i])){
									$retnum.=" AND ((s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."') ";
						
									if($i>0){
										$retnum.=" OR (s.campo1='".getOrdinal($arrtexto[$i])."' OR s.campo2='".getOrdinal($arrtexto[$i])."' OR s.campo3='".getOrdinal($arrtexto[$i])."' OR s.campo4='".getOrdinal($arrtexto[$i])."' OR s.campo5='".getOrdinal($arrtexto[$i])."' OR s.campo6='".getOrdinal($arrtexto[$i])."' OR s.campo7='".getOrdinal($arrtexto[$i])."' OR s.campo8='".getOrdinal($arrtexto[$i])."' OR s.campo9='".getOrdinal($arrtexto[$i])."' OR s.campo10='".getOrdinal($arrtexto[$i])."' OR s.campo11='".getOrdinal($arrtexto[$i])."' OR s.campo12='".getOrdinal($arrtexto[$i])."' OR s.campo13='".getOrdinal($arrtexto[$i])."' OR s.campo14='".getOrdinal($arrtexto[$i])."' OR s.campo15='".getOrdinal($arrtexto[$i])."')";
									}
									$retnum.=") ";
								}else{
									if(findCaracteres($arrtexto[$i])){
										$retnum.=" AND (s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."' OR s.campo1='".getCaracteres($arrtexto[$i])."' OR s.campo2='".getCaracteres($arrtexto[$i])."' OR s.campo3='".getCaracteres($arrtexto[$i])."' OR s.campo4='".getCaracteres($arrtexto[$i])."' OR s.campo5='".getCaracteres($arrtexto[$i])."' OR s.campo6='".getCaracteres($arrtexto[$i])."' OR s.campo7='".getCaracteres($arrtexto[$i])."' OR s.campo8='".getCaracteres($arrtexto[$i])."' OR s.campo9='".getCaracteres($arrtexto[$i])."' OR s.campo10='".getCaracteres($arrtexto[$i])."' OR s.campo11='".getCaracteres($arrtexto[$i])."' OR s.campo12='".getCaracteres($arrtexto[$i])."' OR s.campo13='".getCaracteres($arrtexto[$i])."' OR s.campo14='".getCaracteres($arrtexto[$i])."' OR s.campo15='".getCaracteres($arrtexto[$i])."') ";
									}else{
										$retnum.=" AND (s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."') ";
									}
								}
							}elseif($tsearch=='parcelid'){
								$retnum.=" AND s.parcelid='".$arrtexto[$i]."'";
							}elseif($tsearch=='mlnumber'){
								if($type_search=='BOR') $retnum.=" AND byowner_r.mlnumber='".$arrtexto[$i]."'";
								elseif($type_search=='BO') $retnum.=" AND byowner_s.mlnumber='".$arrtexto[$i]."'";
								else $retnum.=" AND mlsresidential.mlnumber='".$arrtexto[$i]."'";
							}elseif($tsearch=='case'){
								$retnum.=" AND REPLACE(pendes.case_numbe,'-','')='".$arrtexto[$i]."'";
							}
						}
						
						$qwhere=$retnum;
						
						$query="SELECT count(DISTINCT s.parcelid) 
						FROM $bd_search.$tabla_search s ";
						
						if(strlen($filterwhere)>0)
							$query.="INNER JOIN $bd_search.$tabla_php p ON (s.parcelid=p.parcelid) ";
						if(count($jointable)>0){
							foreach($jointable as $k => $val)
								$query.="LEFT JOIN $bd_search.$val ON (s.parcelid=$val.parcelid) ";
						}
						
						$query.="WHERE 1=1 ".$wherejoin.$qwhere.$filterwhere.$groupby.' ';
						$rest=mysql_query($query) or die($query.mysql_error());
						$r=mysql_fetch_array($rest);
						$num_rows_all=$r[0];
						if (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==1){					
			         		$num_rows_all=mysql_num_rows($rest);
			          	}
						
						if($num_rows_all>0){
							$query="SELECT DISTINCT s.parcelid $campos_adi $campos_result 
							FROM $bd_search.$tabla_search s ";
							$query.="INNER JOIN $bd_search.$tabla_php p ON (s.parcelid=p.parcelid) ";
							if(count($jointable)>0){
								foreach($jointable as $k => $val)
									$query.="LEFT JOIN $bd_search.$val ON (s.parcelid=$val.parcelid) ";
							}
							if(isset($_COOKIE['datos_usr']['USERID']))	
								$query.="LEFT JOIN xima.followup fu ON (s.parcelid=fu.parcelid and userid=".$_COOKIE['datos_usr']['USERID'].") ";
							$query.="WHERE 1=1 ".$wherejoin.$qwhere.$filterwhere.' '.$orderby;
							if(count($arrPoly)<=2) $query.=$limit;
							//echo $query;
							$rest=mysql_query($query) or die($query.mysql_error());
							while($r=mysql_fetch_array($rest,MYSQL_ASSOC)){
								$list_parcelid[]=$r['parcelid'];
								$list_reg[]=$r;
							}
							
							$encontrado=true;
						}
					}
					$j++;
				}
			}
			
			if(isset($_COOKIE['datos_usr']['USERID'])){
				$_query='UPDATE xima.xima_system_var SET num_rows_all='.$num_rows_all.',list_text_search="'.str_replace('"','\"',json_encode($list_text_search)).'" WHERE userid='.$_COOKIE['datos_usr']['USERID'];
				mysql_query($_query) or die($_query.mysql_error());
			}else{
				$_SESSION['query_search']['num_rows_all']=$num_rows_all;
				$_SESSION['query_search']['list_text_search']=$list_text_search;
			}
		}else{
			for($i=0;$i<count($arrtexto);$i++){
				if($tsearch=='location'){
					if(is_numeric($arrtexto[$i])){
						$retnum.=" AND ((s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."') ";
						
						if($i>0){
							$retnum.=" OR (s.campo1='".getOrdinal($arrtexto[$i])."' OR s.campo2='".getOrdinal($arrtexto[$i])."' OR s.campo3='".getOrdinal($arrtexto[$i])."' OR s.campo4='".getOrdinal($arrtexto[$i])."' OR s.campo5='".getOrdinal($arrtexto[$i])."' OR s.campo6='".getOrdinal($arrtexto[$i])."' OR s.campo7='".getOrdinal($arrtexto[$i])."' OR s.campo8='".getOrdinal($arrtexto[$i])."' OR s.campo9='".getOrdinal($arrtexto[$i])."' OR s.campo10='".getOrdinal($arrtexto[$i])."' OR s.campo11='".getOrdinal($arrtexto[$i])."' OR s.campo12='".getOrdinal($arrtexto[$i])."' OR s.campo13='".getOrdinal($arrtexto[$i])."' OR s.campo14='".getOrdinal($arrtexto[$i])."' OR s.campo15='".getOrdinal($arrtexto[$i])."')";
						}
						$retnum.=") ";
					}else{
						if(findCaracteres($arrtexto[$i])){
							$retnum.=" AND (s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."' OR s.campo1='".getCaracteres($arrtexto[$i])."' OR s.campo2='".getCaracteres($arrtexto[$i])."' OR s.campo3='".getCaracteres($arrtexto[$i])."' OR s.campo4='".getCaracteres($arrtexto[$i])."' OR s.campo5='".getCaracteres($arrtexto[$i])."' OR s.campo6='".getCaracteres($arrtexto[$i])."' OR s.campo7='".getCaracteres($arrtexto[$i])."' OR s.campo8='".getCaracteres($arrtexto[$i])."' OR s.campo9='".getCaracteres($arrtexto[$i])."' OR s.campo10='".getCaracteres($arrtexto[$i])."' OR s.campo11='".getCaracteres($arrtexto[$i])."' OR s.campo12='".getCaracteres($arrtexto[$i])."' OR s.campo13='".getCaracteres($arrtexto[$i])."' OR s.campo14='".getCaracteres($arrtexto[$i])."' OR s.campo15='".getCaracteres($arrtexto[$i])."') ";
						}else{
							$retnum.=" AND (s.campo1='".$arrtexto[$i]."' OR s.campo2='".$arrtexto[$i]."' OR s.campo3='".$arrtexto[$i]."' OR s.campo4='".$arrtexto[$i]."' OR s.campo5='".$arrtexto[$i]."' OR s.campo6='".$arrtexto[$i]."' OR s.campo7='".$arrtexto[$i]."' OR s.campo8='".$arrtexto[$i]."' OR s.campo9='".$arrtexto[$i]."' OR s.campo10='".$arrtexto[$i]."' OR s.campo11='".$arrtexto[$i]."' OR s.campo12='".$arrtexto[$i]."' OR s.campo13='".$arrtexto[$i]."' OR s.campo14='".$arrtexto[$i]."' OR s.campo15='".$arrtexto[$i]."') ";
						}
					}
				}elseif($tsearch=='parcelid'){
					$retnum.=" AND s.parcelid='".$arrtexto[$i]."'";
				}elseif($tsearch=='mlnumber'){
					if($type_search=='BOR') $retnum.=" AND byowner_r.mlnumber='".$arrtexto[$i]."'";
					elseif($type_search=='BO') $retnum.=" AND byowner_s.mlnumber='".$arrtexto[$i]."'";
					else $retnum.=" AND mlsresidential.mlnumber='".$arrtexto[$i]."'";
				}elseif($tsearch=='case'){
					$retnum.=" AND REPLACE(pendes.case_numbe,'-','')='".$arrtexto[$i]."'";
				}
			}
			$qwhere=$retnum;
					
			$query="SELECT DISTINCT s.parcelid $campos_adi $campos_result 
			FROM $bd_search.$tabla_search s ";
			$query.="INNER JOIN $bd_search.$tabla_php p ON (s.parcelid=p.parcelid) ";
			if(count($jointable)>0){
				foreach($jointable as $k => $val)
					$query.="LEFT JOIN $bd_search.$val ON (s.parcelid=$val.parcelid) ";
			}
			if(isset($_COOKIE['datos_usr']['USERID']))
				$query.="LEFT JOIN xima.followup fu ON (s.parcelid=fu.parcelid and userid=".$_COOKIE['datos_usr']['USERID'].") ";
			$query.="WHERE 1=1 ".$wherejoin.$qwhere.$filterwhere.$groupby.' '.$orderby;
			if(count($arrPoly)<=2) $query.=$limit;
			//echo $query;
			if(isset($_COOKIE['datos_usr']['USERID'])){
				$_query='SELECT num_rows_all FROM xima.xima_system_var WHERE userid='.$_COOKIE['datos_usr']['USERID'];
				$result=mysql_query($_query) or die($_query.mysql_error());
				$r=mysql_fetch_array($result);
				$num_rows_all=$r[0];
			}else
				$num_rows_all=$_SESSION['query_search']['num_rows_all'];
			
			$rest=mysql_query($query) or die($query.mysql_query());
			while($r=mysql_fetch_array($rest,MYSQL_ASSOC)){
				$list_parcelid[]=$r['parcelid'];
				$list_reg[]=$r;
			}
		}
	}
	else if($_GET['systemsearch']=='advance')//filtros y query del search avanzado	
	{
		
		//Para el caso del FORECLOSURE status se toma el marketvalue.pendes
		if($pendes<>'' && strlen($pendes)>0)
			$filtersadvaux.='{"idct":"611","condition":"EXACT","value":"'.$pendes.'"},';
		
		//En funcion del tipo de propiedad seleccionado distinto a ALL se usa el psummary o el mlsresidentiasl para el xcode
		if($proptype<>'' && strlen($proptype)>0)
			$filtersadvaux.='{"idct":"612","condition":"EXACT","value":"'.$proptype.'"},';//xcode mlsresidential	
		
		//Para el caso del owner occupied  se toma el marketvalue.ownocc 
		if($ownerocc<>'' && strlen($ownerocc)>0)
			$filtersadvaux.='{"idct":"597","condition":"EXACT","value":"'.$ownerocc.'"},';

		//Para el caso del owner occupied  se toma el marketvalue.probate 
		if($probateocc<>'' && strlen($probateocc)>0)
			$filtersadvaux.='{"idct":"698","condition":"EXACT","value":"'.$probateocc.'"},';

		//Para el caso del lifeestateocc  se toma el marketvalue.lifeestateocc 
		if($lifeestateocc<>'' && strlen($lifeestateocc)>0)
			$filtersadvaux.='{"idct":"699","condition":"EXACT","value":"'.$lifeestateocc.'"},';

		//buscando los filtros que van en el where del query
		if($filter_search_adv=='')$filtersadvaux=substr($filtersadvaux,0,strlen($filtersadvaux)-1);
		$filter_search_adv='['.$filtersadvaux.$filter_search_adv.']';
		$arrfiltersadv=empty($filter_search_adv)? NULL : json_decode($filter_search_adv);
		//echo "<pre>";print_r($arrfiltersadv);echo "</pre>";
		if(isset($arrfiltersadv))
		{
			$filtro='';
			//$cant=count($arrfiltersadv);
			//for($ifor=0;$ifor<$cant;$ifor++) 
			$ifor=0;
			$expiredno=0;
			foreach($arrfiltersadv as $k => $valfilter)
			{
				$sql2="SELECT c.`Tabla`, c.`Campos` FROM xima.camptit c WHERE c.`IDTC`=".$valfilter->idct;
				$res2=mysql_query($sql2) or die($sql2.mysql_error());
				$row=mysql_fetch_array($res2);
				
				$tabla=$row['Tabla'];
				$campo=$row['Campos'];
				$condicional=$valfilter->condition;
				$value=$valfilter->value;
				$value2=$valfilter->value2;
				if(array_search($tabla,$jointable)===false)//sino esta en los join lo inserto
					$jointable[]=$tabla; 
			
				switch(strtoupper($condicional))
				{
					//STRING
					case "START WITH":
						$filtro=$tabla.".".$campo." LIKE '".$value."%'"; 
					break;
					case "EXACT":
						$filtro=$tabla.".".$campo."='".$value."'"; 
					break;
					case "CONTAINS":
					//Agregado por Luis R Castro 18/09/2015
						$arrval=explode(',',$value);
						$long=count($arrval);
						$filtro=' (';
						for($i=0;$i<$long;$i++){
							if($i>0 && $i<$long) $filtro.=" OR ";
							$filtro.="INSTR(".$tabla.".".$campo.",'".trim($arrval[$i])."')";
						}
						$filtro.=') ';
					/////////////////////////////////////////
					
					break;
					case "NOT START WITH":
						$filtro=$tabla.".".$campo." NOT LIKE '".$value."%'"; 
					break;
					case "NOT EXACT":
						$filtro=$tabla.".".$campo."<>'".$value."'";
					break;
					case "NOT CONTAINS":
						//Agregado por Luis R Castro 18/09/2015
						$arrval=explode(',',$value);
						$long=count($arrval);
						$filtro='';
						for($i=0;$i<$long;$i++){
							if($i>0 && $i<$long) $filtro.=" AND ";
							$filtro.="INSTR(".$tabla.".".$campo.",'".trim($arrval[$i])."')=0";
						}
					/////////////////////////////////////////
				
						//$filtro="INSTR(".$tabla.".".$campo.",'".$value."')=0";
					break;
					case "EMPTY":
						$filtro="($tabla.$campo='' || $tabla.$campo  is null )";
					break;
					case "NOT EMPTY":
						$filtro="!($tabla.$campo='' || $tabla.$campo  is null )";
					break;
								
					//NUMBERS Y DATE
					case "EQUAL":
						$filtro=$tabla.".".$campo."=".$value; 
					break;
					case "GREATER THAN":
						$filtro=$tabla.".".$campo.">".$value; 
					break;
					case "LESS THAN":
						$filtro=$tabla.".".$campo."<".$value; 
					break;
					case "EQUAL OR LESS":
						$filtro=$tabla.".".$campo."<=".$value; 
					break;
					case "EQUAL OR GREATER":
						$filtro=$tabla.".".$campo.">=".$value; 
					break;
					case "BETWEEN":							
						$filtro="(".$tabla.".".$campo." BETWEEN ".$value." AND ".$value2.")"; 
					break;
								
					//BOOLEAN
					case "YES":
						if($campo=='astatus')$filtro=$tabla.".".$campo."='A'";
						else if($campo=='sold')$filtro=$tabla.".".$campo."='S'";
						elseif($campo=='expired'){ $filtro="(".$tabla.".status = 'X')"; }
						else $filtro=$tabla.".".$campo."='Y'";
					break;
					case "NO":
						if($campo=='astatus')$filtro=$tabla.".".$campo."<>'A'";
						elseif($campo=='financial')$filtro=$tabla.".".$campo." LIKE 'No%'"; 
						elseif($campo=='expired'){ $filtro="(".$tabla.".status <> 'X')"; $expiredno=1;}
						else $filtro=$tabla.".".$campo."='N'";
					break;
					
					//FINANCIAL
					case "FORECLOSED (REO)":
						$filtro=$tabla.".".$campo." LIKE 'Foreclosed%'"; 
					break;
					case "SHORT SALE":
						$filtro=$tabla.".".$campo." LIKE 'Short Sale%'"; 
					break;
					case "OTHER":
						$filtro=$tabla.".".$campo." LIKE 'Other%'"; 
					break;
					
					//FORSALE
					case "NEVER FOR SALE":
						$filtro="( SELECT count(*) FROM $bd_search.mlsresidential maux WHERE p.parcelid=maux.parcelid )=0";
						
					break;
					
				}
				//puesto por smith 29/10/2010 de manera que si se coloca algun filtro en la seccion de FOR SALE siempre le concatene 
				//`mlsresidential`.status='A'
				if(strtolower(str_replace('`','',$tabla))=='mlsresidential')$wherejoin.=" AND `mlsresidential`.status='A' ";	
				if(strtolower(str_replace('`','',$tabla))=='expired' )		$wherejoin.=" AND `expired`.status='X' ";	
			
				$wherejoin.=" AND ".$filtro;
				$ifor++;
			}
			if($expiredno==1)$wherejoin=str_replace(" AND `expired`.status='X' ",'',$wherejoin);		
		}//if(isset($arrfiltersadv))si hay filtros
		
		//buscando las tablas que van en el join del query
		unset($arraux);
		foreach ($jointable as $val) 
		{
			$val=str_replace('`','',$val);
			if((!isset($arraux) || array_search($val,$arraux)===false) && $val<>$maintableadv && $val<>'p')		
				$arraux[]=$val;
		}
		unset($jointable);
		$jointable=$arraux;
		$leftjoin='';
		$ifr=0;
		foreach ($jointable as $val) //construyendo los join
		{
			if($ifr==0) $leftjoin.=" INNER JOIN $bd_search.$val ON (p.parcelid=$val.parcelid) ";
			else $leftjoin.=" LEFT JOIN $bd_search.$val ON (p.parcelid=$val.parcelid) ";
			$ifr++;
		}
		if(isset($_COOKIE['datos_usr']['USERID']))
			$leftjoin.="LEFT JOIN xima.followup fu ON (p.parcelid=fu.parcelid and userid=".$_COOKIE['datos_usr']['USERID'].") ";
		//$orderby=str_replace('p.',$maintableadv.'.',$orderby);
		if (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==1)
			$campos_result=" p.parcelid ".$campos_result;
		else
			$campos_result=" p.parcelid, ".$campos_result;
		
		if(isset($_COOKIE['datos_usr']['USERID']))
			$campos_result.=', IF(fu.parcelid is not null AND fu.type!="DEL", fu.type, "0") as followup ';	
		$maintableadv=$bd_search.".".$maintableadv;
		/*if($type_search=='MO')
			$maintableadv=" (SELECT m1.* FROM $bd_search.mortgage m1
							Where  m1.mtg_amount=(select max(m3.mtg_amount) from $bd_search.mortgage m3 where m3.parcelid=m1.parcelid )
							group by m1.parcelid) as mortgage ";*/
		$query="SELECT DISTINCT $campos_result FROM $maintableadv p $leftjoin $wherejoin ";
		if(isset($_GET['groupbylevel'])) $query.="$filterwhere $groupby ";
		$query2 = $query;
		$rest2=mysql_query($query2) or die($query2.mysql_error());
		$num_rows_all=mysql_num_rows($rest2);
		
		$query.="$orderby ";// $limit";
		if(count($arrPoly)<=2) $query.=$limit;
		$rest=mysql_query($query) or die($query.mysql_error());

		while($r=mysql_fetch_array($rest,MYSQL_ASSOC)){
			$list_parcelid[]=$r['parcelid'];
			$list_reg[]=$r;
		}
		//$orderby='ORDER BY p.county,p.address ASC';//para el combo de order by
	}
	
	if(count($arrPoly)>2){
		require('includes/class/polygon.geo.class.php');
		function _createPolygon($arrPoly){
			$polygon =& new geo_polygon();
			$_lat=0;$_lng=0;
			foreach($arrPoly as & $coord)
			{
				$_lng=($coord["long"]);
				$_lat=$coord["lat"];		
				$polygon->addv($_lng,$_lat);
			}
			
			return $polygon;
		}
		function _pointINpolygon($polygon,$lat,$lng) 
		{
			$lng=($lng);
			$vertex = new vertex($lng,$lat);
				
			$isInside = ($polygon->isInside($vertex))? true:false;	
			
			return $isInside;
		}
		
		$list_regAux = array();
		$polygon = _createPolygon($arrPoly);
		
		foreach($list_reg as $k=>$val){
			if(_pointINpolygon($polygon,$val['latitude'],$val['longitude'])){
				$list_regAux[]=$val;
			}
		}
		
		unset($polygon);
		
		$list_reg=array_slice($list_regAux,($num_limit_page*$limit_cant_reg),$limit_cant_reg);
		
		if($num_rows_all != count($list_regAux)){
			$num_rows_all = count($list_regAux);
			
			if(isset($_COOKIE['datos_usr']['USERID'])){
				$_query='UPDATE xima.xima_system_var SET num_rows_all='.$num_rows_all.' WHERE userid='.$_COOKIE['datos_usr']['USERID'];
				mysql_query($_query) or die($_query.mysql_error());
			}else{
				$_SESSION['query_search']['num_rows_all']=$num_rows_all;
			}
		}
	}
	
	if($num_rows_all<=0) $mostrarSoloPropertyFound=true;
		
	//if(in_array($_COOKIE['datos_usr']['USERID'],array(73,2482))) echo $query;//|| $_COOKIE['datos_usr']['USERID']==3152
	//if(in_array($_COOKIE['datos_usr']['USERID'],array(20))) echo $query;//|| $_COOKIE['datos_usr']['USERID']==3152

	if($_GET['resultType']=='advance'){
		
		if(isset($_POST['MarketingModule'])){

			$return= "{\"success\": true,\"page\": ".$num_limit_page.",\"total\":".$num_rows_all.",\"records\": [";
			
			foreach($list_reg as $k=>$val){
				if($k>0) $return.=",";
				$return.="{";
				$m=0;
				foreach($val as $l=>$val2){
					if($m>0) $return.=",";
					if($l=='status')
						$return.="\"$l\": \"".(($num_limit_page*$limit_cant_reg)+$k+1).mysql_real_escape_string($val2)."\"";
					else
						$return.="\"$l\": \"".mysql_real_escape_string($val2)."\"";
					$m++;
				}
				$return.="}";
			}
			$return.="]}";
		}else{
			
			$return= "{\"metaData\": {\"totalProperty\": \"total\",\"root\": \"records\",\"id\": \"id\",\"fields\": [";
			if (!isset($_GET['groupbylevel']) || (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==2))	
				$return.='"pid","status","pin_xlat","pin_xlong","pin_address","pin_lsqft","pin_larea","pin_bed","pin_bath","pin_saleprice","pin_sold","county","diff_bath","diff_beds","diff_lsqft","diff_larea","diff_zip","diff_dom","followup","tieneImg","imagen","imagenxima"';
			else
				$return.='"pid","count","groupselect"';
				
			foreach($campos_grid_defa as $k => $val){
				$tipo=($val->type=='boolean' || $val->type=='pendes' || $val->type=='date') ? 'string' : $val->type;
				$tipo=$tipo=='real' ? 'float' : $tipo;
				$return.=",{\"name\": \"".$val->name."\",\"type\": \"".$tipo."\"}";
			}
			
			$return.="]},\"success\": true,\"page\": ".$num_limit_page.",\"total\":".$num_rows_all.",\"records\": [";
	
			foreach($list_reg as $k=>$val){
				if($k>0) $return.=",";
				$return.="{";
				$m=0;
				foreach($val as $l=>$val2){
					if($m>0) $return.=",";
					if($l=='status')
						$return.="\"$l\": \"".(($num_limit_page*$limit_cant_reg)+$k+1).mysql_real_escape_string($val2)."\"";
					else
						$return.="\"$l\": \"".mysql_real_escape_string($val2)."\"";
					$m++;
				}
				$return.="}";
			}
			$return.="],\"columns\": [";
			
			if (!isset($_GET['groupbylevel']) || (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==2)){
				$return.="{\"header\": \"Sta\",\"dataIndex\": \"status\",\"hidden\": false,\"width\": 30, renderer: gridgetcasita, sortable: true, tooltip: \"Status Property.\"}";
				$return.=",{\"header\": \"F\",\"dataIndex\": \"followup\",\"hidden\": false,\"width\": 30, renderer: gridgetfollowup, sortable: true, tooltip: \"Follow Up Status.\"}";
			}else
				$return.="{\"header\": \"Quantity\",\"dataIndex\": \"count\",\"hidden\": false,\"width\": 50, sortable: true, tooltip: \"Quantity of that group.\"}";
				
			foreach($campos_grid_defa as $k => $val){
				if($val->type=='real')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false,\"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\", xtype: \"numbercolumn\"}";
				elseif($val->name=='pendes')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: gridgetsold, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='larea')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: griddifflarea, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='beds')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: griddiffbeds, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='bath')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: griddiffbath, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='lsqft')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: griddifflsqft, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='zip')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: griddiffzip, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='dom')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, renderer: griddiffdom, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
				elseif($val->name=='mlnumber')
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false, \"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\" ,editor: new Ext.form.TextField() }";
				else
					$return.=",{\"header\": \"".$val->title."\",\"dataIndex\": \"".$val->name."\",\"hidden\": false,\"width\": ".$val->px_size.", sortable: true, tooltip: \"".$val->desc."\"}";
			}
				//Sugerencia 12501 12502 12504 pagregada por Luis R Castro 05/06/2015
			if (!isset($_GET['groupbylevel']) || (isset($_GET['groupbylevel']) && $_GET['groupbylevel']==2)){
	
	$return.=",{\"header\": \"   \",\"dataIndex\": \"parcelid\",\"hidden\": false,\"width\": 30, renderer: gridsetFollow, sortable: true, tooltip: \"Click for Follow Property.\"}";
							}
			/////////////////////////////////////////////////////////////////////////////////////
			$return.="]}";
		}

		unset($list_reg);
		unset($list_parcelid);
		echo $return;
	}
?>