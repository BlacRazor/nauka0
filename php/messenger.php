<?php
date_default_timezone_set('Asia/Krasnoyarsk');
error_reporting(0);
include './ObsceneCensorRus.php';
include './ReflectionTypeHint.php';
include './UTF8.php';
include './Censor.php';

switch ($_REQUEST['action']) {
    case 'send':
        {            
            $rank = parse_ini_file('rank.ini');

            $date = date("j M");
            $time = date("h:i:s");
            // Get data from body http query
            $replace = array(" ",1,2,3,4,5,6,7,8,9,0,"_","-".".",",",":",";","!","?","#","@","%","&","*","+","=","/","№",'"',"~","\\");
            sleep(2);
            $request_body = file_get_contents('php://input');
            $data = json_decode($request_body);
                       
            $name    = strip_tags($data->name);
            $cName   = str_ireplace("=","",$name);
            $message = strip_tags($data->message);            
            
            $line1   = $name." ".$message;
            $line2=str_ireplace($replace,"",$name.$message);
            
            $censor1 = Text_Censure::parse($line1);
            $censor2 = Text_Censure::parse($line2);
            
            $censor3 = ObsceneCensorRus::isAllowed($line1);
            $censor4 = ObsceneCensorRus::isAllowed($line2);
            if ($censor1){
                $censor1=false;
            } else $censor1=true;
            if ($censor2){
                $censor2=false;
            } else $censor2=true;

	    preg_match("/(хуй){1,}/", $line2, $matches);
	    if (isset($matches[0])){
	        print_r($matches);
	        $censor5=0;	
	    } else {
		$censor5=1;	
	    }
	    
	    preg_match("/(\)\(уй){1,}/", $line2, $matches);
	    if (isset($matches[0])){
	        print_r($matches);
	        $censor6=0;	
	    } else {
	        $censor6=1;	
	    }
	    
	    preg_match("/пизда/", $line2, $matches);
	    if (isset($matches[0])){
	        print_r($matches);
	        $censor7=0;	
	    } else {
	        $censor7=1;
	    }

            if (($censor1)and($censor2)and($censor3)and($censor4)and($censor5)and($censor6)and($censor7)){
                // Мата нет
                echo "1";
                $log=$date.' в '.$time.' я получил собщение "'.$message.'" от ['.$name.']. Спасибо :) '."\n";
                if (isset($rank[$cName])){
                    $rank[$cName]++;
                } else{
                    $rank[$cName]=1;
                }
                file_put_contents('rank.ini','');
                foreach ($rank as $rName => $req) {
                    file_put_contents('rank.ini',$rName.'='.$req."\n",FILE_APPEND);
                }
                file_put_contents('server.log',$log,FILE_APPEND);
            } else{
                // Мат есть
                echo "0";
            }
        }
        break;
}

?>