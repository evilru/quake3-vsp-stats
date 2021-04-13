<?php /* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */ 
function Fe6fec173($V98bf7d8c) { foreach ($V98bf7d8c as $V8c7dd922) { if(ereg("([-dl][rwxst-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)", $V8c7dd922, $V66373a9c)) 
 { $V599dcce2 = (int) strpos("-dl", $V66373a9c[1][0]); $Vd50e12d5['line'] = $V66373a9c[0]; $Vd50e12d5['type'] = $V599dcce2;
$Vd50e12d5['rights'] = $V66373a9c[1]; $Vd50e12d5['number'] = $V66373a9c[2]; $Vd50e12d5['user'] = $V66373a9c[3];
$Vd50e12d5['group'] = $V66373a9c[4]; $Vd50e12d5['size'] = $V66373a9c[5]; $Vd50e12d5['date'] = date("m-d",strtotime($V66373a9c[6]));
$Vd50e12d5['time'] = $V66373a9c[7]; $Vd50e12d5['name'] = $V66373a9c[9]; $V4a7870ea[] = $Vd50e12d5; }
} return $V4a7870ea; } function F126ba7b1($V341be97d) { while (preg_match('/^\s*"(.+)"/U',$V341be97d,$Vb74df323) || preg_match("/^\s*([^\s]+)\s*/",$V341be97d,$Vb74df323))
 { $V341be97d=str_replace($Vb74df323[0],"",$V341be97d); $V70e78261['argv'][]=$Vb74df323[1]; } $V70e78261['argc']=count($V70e78261['argv']);
return $V70e78261; } function Fa10803e1() { while (ob_get_level() > 0) ob_end_flush(); flush(); } 
function F9578dd1f($Vd6fe1d0b) { return rtrim($Vd6fe1d0b,"\\/").'/'; } function F37e824f4($Vc1b291cb, $V925904ef)
{ $Vc1b291cb = rtrim ( $Vc1b291cb , "/"); $V925904ef = rtrim ( $V925904ef , "/"); @mkdir($V925904ef,0777);
$V47c80780=F1bc4fd92($Vc1b291cb); foreach ($V47c80780 as $V4d9d6c17) { if($V4d9d6c17) { $V3d296788 = $Vc1b291cb . "/" .$V4d9d6c17;
 if (strcmp ($V3d296788, $V925904ef) != 0) { $Vf2c1de0b = $V925904ef . "/" . $V4d9d6c17; if(is_dir($V3d296788))
 F37e824f4($V3d296788,$Vf2c1de0b); else copy($V3d296788,$Vf2c1de0b); } } } } function F1bc4fd92($V6148bbf9)
{ if ($Ve1260894 = opendir($V6148bbf9)) { while (false !== ($V8c7dd922 = readdir($Ve1260894))) {
 if ($V8c7dd922 != "." && $V8c7dd922 != ".." ) { if(!isset($V45b96339)) $V45b96339="$V8c7dd922";
else $V45b96339="$V8c7dd922\n$V45b96339"; } } closedir($Ve1260894); } @$V47c80780=explode("\n",$V45b96339);
return $V47c80780; } function Fd63c38c9($V2fa47f7c = 255) { $V82a9e4d2 = fopen("php://stdin", "r");
$Va43c1b0a = fgets($V82a9e4d2, $V2fa47f7c); $Va43c1b0a = rtrim($Va43c1b0a); fclose($V82a9e4d2); return $Va43c1b0a;
} function F331c0468($Va93d0e39) { $Va93d0e39=str_replace('\\','/',$Va93d0e39); if(!file_exists($Va93d0e39)) 
 { $V0cc175b9=""; foreach(explode("/",$Va93d0e39) AS $V8ce4b16b) { $V0cc175b9.=$V8ce4b16b."/";
if(!file_exists($V0cc175b9)) $V260ca9dd=mkdir($V0cc175b9, 0775); } return $V260ca9dd; } return true;
} function Fff47f8ac($V341be97d) { return str_replace(array('\\','<','>','/','=',':','*','?','"',' ','|'), "_", $V341be97d);
} function F3a57ff01(&$Vc4d98dbd) { $V305d2889=gettimeofday(); $V843b9f46 = (float)($V305d2889['sec'] - $Vc4d98dbd['sec']) + ((float)($V305d2889['usec'] - $Vc4d98dbd['usec'])/1000000);
return $V843b9f46; } ?>