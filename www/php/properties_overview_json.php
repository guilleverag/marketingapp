<?php
include("properties_conexion.php");

$county=$_POST['county'];
$pid=$_POST['pid'];

if(is_numeric($county)){
    $db=conectarPorIdCounty($county);
}else{
    $db=conectarPorNameCounty($county);
    $county=explode('^',$db);
    $db=$county[0];
    $county=$county[1];
}


    
    function url_exists($url)
	{
		$url_info = parse_url($url);
		
		if (! isset($url_info['host']))
			return false;
		
		$port = (isset($url_info['post'])?$url_info['port']:80);
		
		if (! $hwnd = @fsockopen($url_info['host'], $port, $errno, $errstr)) 
			return false;
		
		$uri = @$url_info['path'] . '?' . @$url_info['query'];
		
		$http = "HEAD $uri HTTP/1.1\r\n";
		$http .= "Host: {$url_info['host']}\r\n";
		$http .= "Connection: close\r\n\r\n";
		
		@fwrite($hwnd, $http);
		
		$response = fgets($hwnd);
		$response_match = "/^HTTP\/1\.1 ([0-9]+) (.*)$/";
		
		fclose($hwnd);
		
		if (preg_match($response_match, $response, $matches)) {
			//print_r($matches);
			if ($matches[1] == 404)
				return false;
			else if ($matches[1] == 200)
				return true;
			else
				return false;
			 
		} else {
			return false;
		}
	}
    
	$sql_comparado="Select
	p.state, p.address,p.city,p.zip,p.xcode,p.xcoded, m.remark, m.agent, m.officeemail,
    m.dom, ps.yrbuilt, 
	p.xcode,p.beds,p.bath,p.sqft,p.price,ps.waterf,ps.pool,p.unit, m.status,
	l.latitude,l.longitude,ma.marketvalue
	FROM properties_php p
	LEFT JOIN psummary ps ON (p.parcelid=ps.parcelid)
	LEFT JOIN mlsresidential m ON (p.parcelid=m.parcelid)
	LEFT JOIN marketvalue ma ON (p.parcelid=ma.parcelid)
	LEFT JOIN latlong l ON (p.parcelid=l.parcelid)
	Where p.parcelid='$pid';";
	$res = mysql_query($sql_comparado) or die(mysql_error());
	$property= mysql_fetch_assoc($res);
    
    $property['bd']=$db;

	//$sql="select mlsresidential.parcelid from mlsresidential where mlsresidential.Parcelid='$pid'";
	//$sql1="select pendes.parcelid from pendes where pendes.parcelid='$pid' and pendes.totaliens >0";
	//$sql2="SELECT mortgage.parcelid FROM mortgage WHERE mortgage.PARCELID='$pid'";
	//$sql3="select parcelid from mlsresidential where Parcelid='$pid' AND (length(replace(remark1,' ',''))>0 || length(replace(remark2,' ',''))>0 || length(replace(remark3,' ',''))>0 || length(replace(remark4,' ',''))>0 || length(replace(remark5,' ',''))>0)";
	$sql4="select mlsresidential.parcelid from mlsresidential where mlsresidential.Parcelid='$pid' and mlsresidential.status='A'";
	$sql5="Select psummary.parcelid from psummary where psummary.parcelid='$pid'";
	$sql6="Select parcelid from rental where parcelid='$pid' and status='A'";
	//$sql8="SELECT parcelid FROM sales where parcelid='$pid'";
	//$sql9="SELECT parcelid FROM psummary where parcelid='$pid' AND (phonename<>'' || psummary.phonenumber1<>'' || psummary.phonenumber2<>'')";
	//$sql10="select pendes.parcelid from pendes where pendes.parcelid='$pid' and (pof='F' or pof='P')";
	$sql11="select i.parcelid from imagenes i where i.parcelid='$pid'";
	//$sql12="select parcelid from byowner_s where parcelid='$pid' AND status='A'";
	//$sql13="select parcelid from byowner_r where parcelid='$pid' AND status='A'";
	//$sql14="select parcelid from byowner_img where parcelid='$pid'";
	//$sql15="select parcelid from probates where parcelid='$pid'";
	//$sql16="select parcelid from expired where parcelid='$pid' and status='X'";
    //$sql17="select parcelid from tax where parcelid='$pid'";

	//$result = mysql_query($sql) or die ($sql.mysql_error());
	//$result1 = mysql_query($sql1) or die ($sql1.mysql_error());
	//$result2 = mysql_query($sql2) or die ($sql2.mysql_error());
	//$result3 = mysql_query($sql3) or die ($sql3.mysql_error());
	$result4 = mysql_query($sql4) or die ($sql4.mysql_error());
	$result5 = mysql_query($sql5) or die ($sql5.mysql_error());
	$result6 = mysql_query($sql6) or die ($sql6.mysql_error());
	//$result8 = mysql_query($sql8) or die ($sql8.mysql_error());
	//$result9 = mysql_query($sql9) or die ($sql9.mysql_error());
	//$result10 = mysql_query($sql10) or die ($sql10.mysql_error());
	$result11 = mysql_query($sql11) or die ($sql11.mysql_error());
	//$result12 = mysql_query($sql12) or die ($sql12.mysql_error());
	//$result13 = mysql_query($sql13) or die ($sql13.mysql_error());
	//$result14 = mysql_query($sql14) or die ($sql14.mysql_error());
	//$result15 = mysql_query($sql15) or die ($sql15.mysql_error());
	//$result16 = mysql_query($sql16) or die ($sql16.mysql_error());
    //$result17 = mysql_query($sql17) or die ($sql17.mysql_error());


	//$agent= mysql_num_rows($result);
	//$liens= mysql_num_rows($result1);
	//$mortgage= mysql_num_rows($result2);
	//$remark= mysql_num_rows($result3);
	$listing= mysql_num_rows($result4);
	//$probates = mysql_num_rows($result15);
	//$expired = mysql_num_rows($result16);
    //$taxed = mysql_num_rows($result17);
    
    /*if($db!='flbroward' && $db!='flbroward1' && $db!='fldade' && $db!='fldade1' && $db!='flpalmbeach' && $db!='flpalmbeach1'
     && $db!='fldixie' && $db!='fldixie1' && $db!='flgadsden' && $db!='flgadsden1' && $db!='flcolumbia' && $db!='flcolumbia1' && $db!='fllake' && $db!='fllake1' && $db!='flstlucie' && $db!='flstlucie1' && $db!='flduval' && $db!='flduval1' && $db!='flhighlands' && $db!='flhighlands1' && $db!='flpasco' && $db!='flpasco1'){
        if($_COOKIE['datos_usr']['USERID'] != 73 && $_COOKIE['datos_usr']['USERID'] != 20 && $_COOKIE['datos_usr']['USERID'] != 5 && $_COOKIE['datos_usr']['USERID'] != 4) $taxed = 0;
    }*/
    
	//if(strtoupper(substr($db,0,2))=='TX') $listing=0;
	$psummary=$owner= mysql_num_rows($result5);
	$rental= mysql_num_rows($result6);
	//$sales = mysql_num_rows($result8);
	//$phones = mysql_num_rows($result9);
	//$pendes = mysql_num_rows($result10);
	$imagenes = mysql_num_rows($result11);
    
    
    $psummaryData = $listingData = $imagenesData = array();
    
    if($psummary > 0){
        $sql_comparado="Select 
		p.sdname,p.folio,p.ccoded,p.saledate,p.saleprice,p.ac,p.stories,
        p.tsqft,p.lsqft,p.bheated,p.pool,p.waterf,p.units,
        p.buildingv,p.sfp,p.landv,p.taxablev,p.legal
		FROM psummary p LEFT JOIN mlsresidential m ON (p.parcelid=m.parcelid)
		Where p.parcelid='$pid';";	
		$res = mysql_query($sql_comparado) or die(mysql_error());
		$psummaryData = mysql_fetch_assoc($res);
        
        
        /*if($sales > 0){
            $sql_comparado="SELECT r.parcelid, r.closingdt as date, 'non recorded sale' as type, r.saleprice as price, '' as book, '' as page, '' as grantor
            FROM rtmaster r 
            WHERE r.status='CS' AND 
            r.closingdt NOT IN (SELECT date FROM sales WHERE parcelid=r.parcelid AND price >= 19000) AND r.parcelid='$pid'
            UNION
            SELECT  
            parcelid,date,type,price,book,page,grantor
            FROM sales WHERE parcelid='$pid' 
            ORDER BY date DESC";
            $res = mysql_query($sql_comparado) or die(mysql_error());
            
            while($myrow2= mysql_fetch_assoc($res)){
                $salesData[] = $myrow2;
            }
        }*/
        
        $property['listingType'] = 'By Owner';
    }
    
    if($listing > 0){
        $sql_comparado="Select 
		mlnumber,parcelid,status,lprice,entrydate,ldate,constype,apxtotsqft,
        remark1,financial
		FROM mlsresidential m 
		Where m.parcelid='$pid';";
        $res = mysql_query($sql_comparado) or die(mysql_error());
		$listingData = mysql_fetch_assoc($res);
        
        $property['listingType'] = 'For Sale';
    }elseif($rental > 0){    
        $sql_comparado="Select
		m.mlnumber,m.parcelid,m.status,m.lprice,m.entrydate,m.constype,m.apxtotsqft,m.remark1
		FROM rental m
		INNER JOIN psummary p ON (m.parcelid=p.parcelid)
		Where m.parcelid='$pid';";	
		$res = mysql_query($sql_comparado) or die(mysql_error());
		$listingData= mysql_fetch_array($res);
        
        $property['listingType'] = 'For Rent';
    }
    
    if($imagenes > 0){
        $sql_comparado="select * 
        from imagenes 
        where parcelid='$pid' AND nphotos > 0";
        $res = mysql_query($sql_comparado) or die(mysql_error());
            
        $myrow2= mysql_fetch_assoc($res);
                
        if($myrow2["letra"]=='Y')
            $mlnumber=$myrow2["mlnumber"];
        else
            $mlnumber=substr($myrow2["mlnumber"],1);
        
        
        if(strlen($myrow2["urlxima"])>5 && url_exists($myrow2["urlxima"].$myrow2["parcelid"].".".$myrow2['tipo'])){
            $imagenesData[] = $myrow2["urlxima"].$myrow2["parcelid"].".".$myrow2['tipo'];
            
            if($myrow2['nphotos']>1){	
                for($i=$myrow2['inicount']; $i<=$myrow2['nphotos']; $i++){
                    if(url_exists($myrow2["urlxima"].$myrow2["parcelid"].$myrow2['sep'].$i.".".$myrow2['tipo']))
                        $imagenesData[] = $myrow2["urlxima"].$myrow2["parcelid"].$myrow2['sep'].$i.".".$myrow2['tipo'];
                }
            }
        }else{
           if(url_exists($myrow2['url'].$myrow2['url2'].$mlnumber.".".$myrow2['tipo'])){
                $imagenesData[] = $myrow2['url'].$myrow2['url2'].$mlnumber.".".$myrow2['tipo'];
                if($myrow2['nphotos']>1){	
                    for($i=$myrow2['inicount']; $i<=$myrow2['nphotos']; $i++){
                        if(url_exists($myrow2["url"].$myrow2['url3'].$mlnumber.$myrow2['sep'].$i.".".$myrow2['tipo']))
                            $imagenesData[] = $myrow2["url"].$myrow2['url3'].$mlnumber.$myrow2['sep'].$i.".".$myrow2['tipo'];
                    }
                }
            } 
        }         
    }
    
    echo json_encode(array('property'=>$property, 'psummary'=>$psummaryData, /*'sales'=>$salesData, */'listing'=>$listingData, 'images'=>$imagenesData));
?>
