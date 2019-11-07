<?php
date_default_timezone_set('Asia/Krasnoyarsk');
if (file_exists('/var/www/html/php/count.ini')){
    $cfg = parse_ini_file('/var/www/html/php/count.ini');
    $oldCount=$cfg['oldCount'];
} else {
    file_put_contents('/var/www/html/php/count.ini',"oldCount=0");
    $oldCount=0;
}
$log=file('/var/www/html/php/server.log');
$count=count($log);
$dif=$count-$oldCount;
$hour =  date("G");
$dur = '(с '.($hour-1).' до  '.$hour.')';

$rank = parse_ini_file('/var/www/html/php/rank.ini');
arsort($rank);
$iter=0;
// get top 3 user
foreach ($rank as $vName => $req) {
	if ($iter<3){
		switch ($iter) {
			case 0:
				$first="\nДа здравствует глав ХАЦКЕР сервера ".$vName." у него ".$req." сообщений! (⌐■_■)\n\n";
				break;
			case 1:
				$second="\nRESPECT ЗАВСЕГДАТАЮ ".$vName." с его ".$req." сообщенями! ლ(´ڡ`ლ)\n";
				break;
			case 2:
				$third="\nПоклон АКТИВНОМУ гостю ".$vName." с его ".$req." сообщенями! Поднажми ε=ε=ε=ε=┌(;￣▽￣)┘\n";
				break 2;	
		}
		$iter++;
	}# code...
}

$top_message=$third.$second.$first;

if ($dif==0){
    $message='За последний час '.$dur.'  никто не написал. Мне так одиноко (ಥ﹏ಥ)'."\n";       
} elseif ($dif<10){
	$message='За последний час '.$dur.'  мне написали всего '.$dif.' раз. Нужно больше сообщений (ง ͠° ͟ل͜ ͡°)ง'."\n";	
} elseif ($dif<50){
	$message='За последний час '.$dur.'  мне написали '.$dif.' раз. Неплохо, но я знаю вы можете больше (●´ω｀●)'."\n";
} elseif ($dif>100){
	$message='За последний час '.$dur.'  Вы молодцы написали мне аж '.$dif.' раз. Люблю вас ( ́ ◕◞ε◟◕`)'."\n";
}
file_put_contents('/var/www/html/php/server.log',"\n".$message,FILE_APPEND);
file_put_contents('/var/www/html/php/server.log',$top_message,FILE_APPEND);
file_put_contents('/var/www/html/php/count.ini',"oldCount=".$count);


?>