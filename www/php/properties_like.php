<?php
	//Includes database connection file
	require_once('properties_conexion.php');

    $useridr	= isset($_POST['useridr']) ? $_POST['useridr'] : null;
    $pid 		= isset($_POST['pid']) ? $_POST['pid'] : null;
    $email 		= isset($_POST['email']) ? $_POST['email'] : null;
    $county		= isset($_POST['county']) ? $_POST['county'] : null;
	$email		= str_replace("%40", "@", $email);   
    
    if(!is_numeric($county)){
        $countyData = conectarPorNameCounty($county);
        $countyData = explode('^',$countyData);
        $county = $countyData[1];
    }
    
  	conectar('xima');
	
	$sql_select	= "	select 
						pid, status 
					from realtor_like 
					where 
						email_userconect='".mysql_real_escape_string($email)."' 
						and userid_realtor={$useridr} 
						and county={$county} 
						and pid='{$pid}'";
    
    if(!isset($_POST['type'])){    
        $rs_select	= mysql_query($sql_select) or die(mysql_error().$sql_select);
	    $nro		= mysql_num_rows($rs_select);
    
        if($nro == 0){
            $sql_new	= "insert into realtor_like (id_realtor_like,email_userconect,userid_realtor,pid,county,status) 
            values	(null,'".mysql_real_escape_string($email)."',{$useridr},'{$pid}','{$county}','L')";
            $status = 'L';                            
        }else {
            $row 		= mysql_fetch_assoc($rs_select);
            if($row['status'] == 'L') $status='D';
            else $status='L';
            
            $sql_new	= "update realtor_like set status='{$status}' 
                            where 
                        email_userconect='".mysql_real_escape_string($email)."' 
                        and userid_realtor={$useridr} 
                        and county={$county} 
                        and pid='{$pid}'";
        }
        $rs 		= mysql_query($sql_new) or die(mysql_error().$sql_new);
        //encode in JSON format
        echo json_encode(array(
            "success" 	=> mysql_errno() == 0,
            "status" => $status
        ));
    }else{
        if($_POST['type']=='is_like'){
            $rs_select	= mysql_query($sql_select) or die(mysql_error().$sql_select);
	        $nro		= mysql_num_rows($rs_select);
        
            echo json_encode(array(
                "success" 	=> $nro != 0
            ));
        }
        elseif($_POST['type']=='result'){
            $query="select 
						pid, county, status 
					from realtor_like 
					where 
						email_userconect='".mysql_real_escape_string($email)."' 
						and userid_realtor={$useridr} AND status='L'";
            $result = mysql_query($query) or die(mysql_error().$query);
            
            $likes = array();
            $pids = array();
            $counties = array();
            while($r = mysql_fetch_assoc($result)){
                $likes[$r['pid']] = $r;
                $pids[] = $r['pid'];
                $counties[] = $r['county'];
            }
            $counties = array_unique($counties);
            
            for($i=0; $i<count($counties); $i++){
                conectarPorIdCounty($counties[$i]);
                
                $query='SELECT DISTINCT s.parcelid as pid, s.parcelid , p.address, p.city, p.zip, p.unit, p.county, 
                `mlsresidential`.beds, `mlsresidential`.bath, 
                if(`mlsresidential`.lsqft > 0,`mlsresidential`.lsqft,`mlsresidential`.apxtotsqft) sqft, 
                p.xcoded, `mlsresidential`.lprice, 
                if(length(`imagenes`.url)>0 OR length(`imagenes`.urlxima)>5,"Y","N") tieneImg,
                `imagenes`.nphotos, 
             concat(`imagenes`.url,`imagenes`.url2,if(`imagenes`.letra="Y",`imagenes`.mlnumber,substring(`imagenes`.mlnumber,2)),".",`imagenes`.tipo) imagen, 
             if(`imagenes`.urlxima is null or length(`imagenes`.urlxima)<5,NULL,concat(`imagenes`.urlxima,`imagenes`.parcelid,".",`imagenes`.tipo)) imagenxima, 
             concat("1_",`mlsresidential`.status,"-",p.pendes) as status
             
              FROM `properties_search` s 
              INNER JOIN `properties_php` p ON (s.parcelid=p.parcelid) 
              LEFT JOIN `mlsresidential` ON (s.parcelid=`mlsresidential`.parcelid) 
              LEFT JOIN `marketvalue` ON (s.parcelid=`marketvalue`.parcelid) 
              LEFT JOIN `imagenes` ON (s.parcelid=`imagenes`.parcelid) 
              WHERE s.parcelid IN ("'.implode('","',$pids).'") 
              ORDER BY p.county,p.xcode asc , if(length(p.address)=0 or p.address is null,1,0) ,p.address ASC';
              
                $result = mysql_query($query) or die(mysql_error().$query);
                
                while($r = mysql_fetch_assoc($result)){
                    $likes[$r['pid']] = $r;
                }
            }
            
            echo json_encode(array(
                "success" 	=> true,
                "total"     => count($likes),
                "likes"     => $likes
            ));
        }
    }
?>