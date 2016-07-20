<?php
	require_once("properties_conexion.php");
    $db = conectar('xima');
	
    if(isset($_POST['type'])){
        switch($_POST['type']){
            case 'login':
                $useridr	= isset($_POST['useridr']) ? $_POST['useridr'] : null;
                $email 		= isset($_POST['email']) ? $_POST['email'] : null;
                $email		= str_replace("%40", "@", $email);
                $password	= isset($_POST['password']) ? $_POST['password'] : null;
                $success    = false;
                $resgister = false;
                
                $sql		= "SELECT email_userconect FROM xima.realtor_userconect WHERE userid_realtor=$useridr AND email_userconect='{$email}' and pass_userconect='{$password}'";
                $rs 		= mysql_query($sql) or die(mysql_error());
                $nro		= mysql_num_rows($rs);
                
                if($nro > 0){
                    $data 	= mysql_fetch_assoc($rs);
                    $users = $data['email_userconect'];
                    
                    $success = true;
                }else{ //Register
                    $sql	    = "insert into xima.realtor_userconect (id_realtor_userconect,userid_realtor,email_userconect,pass_userconect) 
                                values	(null,'{$useridr}','".mysql_real_escape_string($email)."','".mysql_real_escape_string($password)."')";
                    $rs 		= mysql_query($sql) or die(mysql_error());
                    
                    $users = $email;
                    $resgister = true;
                    $success = true;
                }
                
                //encode in JSON format
                echo json_encode(array(
                    "success"   => $nro > 0,
                    "users"     => $users,
                    "register"  => $resgister
                ));
            break;
        }
    }
    
    
?>