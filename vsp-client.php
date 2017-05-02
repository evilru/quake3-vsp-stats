<?php /* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */
class VSPParserCLIENT { var $V2e9590a3; var $Va10baeb6; var $V1c7b7557; var $Vdba7d3a2; var $V8ba4afff;
var $Va509f5c2; var $Vbbf5edcf; var $V93da65a9; var $V21d8a920; var $Vae2aeb93; var $Va2bbabfe; var $V6d2b5d2c;
 var $V42dfa3a4; var $Vc3ecd549; var $V9693e947; var $Vdafa753c; var $V50dffd37; var $Vd6d33a32;
var $logdata; var $Va733fe6b; var $Vb3b1075a; function VSPParserCLIENT($Ve0d85fdc,&$V4f00ff2f,&$V495c39bf)
 { $this->Vbbf5edcf=array( "#PLAYER#(?:\\^[^\\^])? renamed to #NAME#$" ); $this->def_chat=array(
 "#PLAYER#(?:\\^[^\\^])?: #CHAT#$" ); $this->Vdba7d3a2=array( "Match has begun!" ); $this->V1c7b7557=array(
 "^Timelimit hit\\." ,"^Pointlimit hit\\." ,"hit the capturelimit\\.$" ,"hit the fraglimit\\.$" ,"^----- CL_Shutdown -----"
 ); $this->Va10baeb6=array( "#PLAYER#(?:\\^[^\\^])? entered the game" ); $this->V8ba4afff=array(
 "#PLAYER#(?:\\^[^\\^])? entered the game \\(#TEAM#\\)" ); $this->Va509f5c2=array( "#PLAYER#(?:\\^[^\\^])? RED's flag carrier defends against an agressive enemy" => "CTF|Defend_Hurt_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? got the BLUE flag!" => "CTF|Flag_Pickup" ,"#PLAYER#(?:\\^[^\\^])? returned the RED flag!" => "CTF|Flag_Return"
 ,"#PLAYER#(?:\\^[^\\^])? fragged BLUE's flag carrier!" => "CTF|Kill_Carrier" ,"#PLAYER#(?:\\^[^\\^])? gets an assist for returning the RED flag!" => "CTF|Flag_Assist_Return"
 ,"#PLAYER#(?:\\^[^\\^])? gets an assist for fragging the RED flag carrier!" => "CTF|Flag_Assist_Frag"
 ,"#PLAYER#(?:\\^[^\\^])? defends RED's flag carrier against an agressive enemy" => "CTF|Defend_Hurt_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? defends the RED flag carrier against an agressive enemy!" => "CTF|Defend_Hurt_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? defends the RED's flag carrier." => "CTF|Defend_Carrier" ,"#PLAYER#(?:\\^[^\\^])? defends the RED flag carrier!" => "CTF|Defend_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? defends the RED base" => "CTF|Defend_Base" ,"#PLAYER#(?:\\^[^\\^])? defends the RED flag" => "CTF|Defend_Flag"
 ,"#PLAYER#(?:\\^[^\\^])? captured the BLUE flag!" => "CTF|Flag_Capture" ,"#PLAYER#(?:\\^[^\\^])? BLUE's flag carrier defends against an agressive enemy" => "CTF|Defend_Hurt_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? got the RED flag!" => "CTF|Flag_Pickup" ,"#PLAYER#(?:\\^[^\\^])? returned the BLUE flag!" => "CTF|Flag_Return"
 ,"#PLAYER#(?:\\^[^\\^])? fragged RED's flag carrier!" => "CTF|Kill_Carrier" ,"#PLAYER#(?:\\^[^\\^])? gets an assist for returning the BLUE flag!" => "CTF|Flag_Assist_Return"
 ,"#PLAYER#(?:\\^[^\\^])? gets an assist for fragging the BLUE flag carrier!" => "CTF|Flag_Assist_Frag"
 ,"#PLAYER#(?:\\^[^\\^])? defends BLUE's flag carrier against an agressive enemy" => "CTF|Defend_Hurt_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? defends the BLUE flag carrier against an agressive enemy!" => "CTF|Defend_Hurt_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? defends the BLUE's flag carrier." => "CTF|Defend_Carrier" ,"#PLAYER#(?:\\^[^\\^])? defends the BLUE flag carrier!" => "CTF|Defend_Carrier"
 ,"#PLAYER#(?:\\^[^\\^])? defends the BLUE base" => "CTF|Defend_Base" ,"#PLAYER#(?:\\^[^\\^])? defends the BLUE flag" => "CTF|Defend_Flag"
 ,"#PLAYER#(?:\\^[^\\^])? captured the RED flag!" => "CTF|Flag_Capture" ,"#PLAYER#(?:\\^[^\\^])? got an assist for returning the flag!" => "CTF|Flag_Assist_Return"
 ,"#PLAYER#(?:\\^[^\\^])? got an assist for fragging the enemy flag carrier!" => "CTF|Flag_Assist_Frag"
 ); $this->V2e9590a3=array( "#VICTIM#(?:\\^[^\\^])? was pummeled by #KILLER#(?:\\^[^\\^])?$" => "GAUNTLET"
 ,"#VICTIM#(?:\\^[^\\^])? was machinegunned by #KILLER#(?:\\^[^\\^])?$" => "MACHINEGUN" ,"#VICTIM#(?:\\^[^\\^])? was gunned down by #KILLER#(?:\\^[^\\^])?$" => "SHOTGUN"
 ,"#VICTIM#(?:\\^[^\\^])? was shredded by #KILLER#(?:\\^[^\\^])?'s shrapnel" => "GRENADE" ,"#VICTIM#(?:\\^[^\\^])? ate #KILLER#(?:\\^[^\\^])?'s grenade" => "GRENADE"
 ,"#VICTIM#(?:\\^[^\\^])? ate #KILLER#(?:\\^[^\\^])?'s rocket" => "ROCKET" ,"#VICTIM#(?:\\^[^\\^])? almost dodged #KILLER#(?:\\^[^\\^])?'s rocket" => "ROCKET"
 ,"#VICTIM#(?:\\^[^\\^])? was electrocuted by #KILLER#(?:\\^[^\\^])?$" => "LIGHTNING" ,"#VICTIM#(?:\\^[^\\^])? was railed by #KILLER#(?:\\^[^\\^])?$" => "RAILGUN"
 ,"#VICTIM#(?:\\^[^\\^])? was melted by #KILLER#(?:\\^[^\\^])?'s plasmagun" => "PLASMA" ,"#VICTIM#(?:\\^[^\\^])? was blasted by #KILLER#(?:\\^[^\\^])?'s BFG" => "BFG"
 ,"#VICTIM#(?:\\^[^\\^])? tried to invade #KILLER#(?:\\^[^\\^])?'s personal space" => "TELEFRAG" ,"#VICTIM#(?:\\^[^\\^])? blew itself up\\." => "ROCKET"
 ,"#VICTIM#(?:\\^[^\\^])? blew herself up\\." => "ROCKET" ,"#VICTIM#(?:\\^[^\\^])? blew himself up\\." => "ROCKET"
 ,"#VICTIM#(?:\\^[^\\^])? tripped on his own grenade\\." => "GRENADE" ,"#VICTIM#(?:\\^[^\\^])? tripped on her own grenade\\." => "GRENADE"
 ,"#VICTIM#(?:\\^[^\\^])? tripped on its own grenade\\." => "GRENADE" ,"#VICTIM#(?:\\^[^\\^])? melted himself\\." => "PLASMA"
 ,"#VICTIM#(?:\\^[^\\^])? melted herself\\." => "PLASMA" ,"#VICTIM#(?:\\^[^\\^])? melted itself\\." => "PLASMA"
 ,"#VICTIM#(?:\\^[^\\^])? should have used a smaller gun\\." => "BFG" ,"#VICTIM#(?:\\^[^\\^])? killed himself\\." => "SUICIDE"
 ,"#VICTIM#(?:\\^[^\\^])? killed herself\\." => "SUICIDE" ,"#VICTIM#(?:\\^[^\\^])? killed itself\\." => "SUICIDE"
 ,"#VICTIM#(?:\\^[^\\^])? cratered\\." => "FALLING" ,"#VICTIM#(?:\\^[^\\^])? does a back flip into the lava\\." => "LAVA"
 ,"#VICTIM#(?:\\^[^\\^])? was squished\\." => "CRUSH" ,"#VICTIM#(?:\\^[^\\^])? sank like a rock\\." => "WATER"
 ,"#VICTIM#(?:\\^[^\\^])? melted\\." => "SLIME" ,"#VICTIM#(?:\\^[^\\^])? was in the wrong place\\." => "TRIGGER_HURT" 
 ,"#VICTIM#(?:\\^[^\\^])? was disemboweled by #KILLER#(?:\\^[^\\^])?'s grappling hook" => "GRAPPLE"
 ,"#VICTIM#(?:\\^[^\\^])? was impaled by #KILLER#(?:\\^[^\\^])?'s shower of nails" => "NAILGUN" ,"#VICTIM#(?:\\^[^\\^])? was swimming too close to #KILLER#(?:\\^[^\\^])?$" => "DISCHARGE"
 ,"#VICTIM#(?:\\^[^\\^])? pressed the wrong button\\." => "DISCHARGE" ); define("C7e731e80",1024);
$this->Fcda1c5ae($Ve0d85fdc); $this->V21d8a920= $V4f00ff2f; $this->Vae2aeb93= $V495c39bf; $this->V6d2b5d2c= array();
 $this->V42dfa3a4= array(); $this->Va2bbabfe= array(); $this->logdata=array(); $this->Vdafa753c= false;
 } function Fcda1c5ae($Ve0d85fdc) { $this->V93da65a9['savestate']=0; $this->V93da65a9['gametype']="";
$this->V93da65a9['backuppath']=""; $this->V93da65a9['trackID']="playerName"; if (is_array($Ve0d85fdc))
 { foreach($Ve0d85fdc as $Ve7cb9038 => $Va36fd2a1) { $this->V93da65a9[$Ve7cb9038] = $Va36fd2a1; }
} if ($this->V93da65a9['backuppath']) { $this->V93da65a9['backuppath']=F9578dd1f($this->V93da65a9['backuppath']);
} print_r($this->V93da65a9); } function F713be45c() { unset($this->Va2bbabfe); $this->Va2bbabfe= array();
unset($this->V6d2b5d2c); $this->V6d2b5d2c= array(); $this->V9693e947['month']=12; $this->V9693e947['date']=28;
$this->V9693e947['year']=1971; $this->V9693e947['hour']=23; $this->V9693e947['min']=59; $this->V9693e947['sec']=59;
} function F5c0b129c() { $this->logdata["last_shutdown_end_position"]=ftell($this->V50dffd37); 
 $V9a52fe40=fseek ($this->V50dffd37, -C7e731e80, SEEK_CUR); if ($V9a52fe40==0) { $this->logdata['last_shutdown_hash']=md5(fread($this->V50dffd37, C7e731e80));
} else { $V284073b9=ftell($this->V50dffd37); fseek ($this->V50dffd37, 0); $this->logdata['last_shutdown_hash']=md5(fread($this->V50dffd37, $V284073b9)); 
 } $V3b2eb2c1 = fopen('./logdata/savestate_'.Fff47f8ac($this->Vd6d33a32).'.inc.php',"wb"); fwrite($V3b2eb2c1,"<?php \n");
fwrite($V3b2eb2c1,"\$this->logdata['last_shutdown_hash']='{$this->logdata['last_shutdown_hash']}';\n");
fwrite($V3b2eb2c1,"\$this->logdata['last_shutdown_end_position']={$this->logdata['last_shutdown_end_position']};\n");
fwrite($V3b2eb2c1,"?>"); fclose($V3b2eb2c1); } function Fb96636b2() { echo "Verifying savestate\n";
$V8774de0e=fopen($this->Vd6d33a32,"rb"); $V2843c763=fseek($V8774de0e,$this->logdata['last_shutdown_end_position']);
 if ($V2843c763==0) { $V9a52fe40=fseek ($V8774de0e, -C7e731e80, SEEK_CUR); if ($V9a52fe40==0) { 
 $Vb9cc7f4b=fread($V8774de0e, C7e731e80); } else { $V284073b9=ftell($V8774de0e); fseek ($V8774de0e, 0);
$Vb9cc7f4b=fread($V8774de0e, $V284073b9); } if (strcmp(md5($Vb9cc7f4b),$this->logdata['last_shutdown_hash'])==0)
 { echo " - Hash matched, resuming parsing from previous saved location in log file\n"; fseek($this->V50dffd37,$this->logdata['last_shutdown_end_position']);
} else { echo " - Hash did not match, assuming new log file\n"; fseek($this->V50dffd37,0); } } else
 { echo " - Seek to prior location failed, assuming new log file\n"; fseek($this->V50dffd37,0); }
 fclose($V8774de0e); } function F1417ca90($Vdbe56eaf) { $this->Vd6d33a32=realpath($Vdbe56eaf); 
 if (!file_exists($this->Vd6d33a32)) { F03c2b497("error: log file \"{$Vdbe56eaf}\" does not exist");
} $this->F713be45c(); if ($this->V93da65a9['savestate']==1) { echo "savestate 1 processing enabled\n";
@include_once('./logdata/savestate_'.Fff47f8ac($this->Vd6d33a32).'.inc.php'); $this->V50dffd37= fopen($this->Vd6d33a32,"rb");
 if (!empty($this->logdata)) { $this->Fb96636b2($this->Vd6d33a32); } } else { $this->V50dffd37= fopen($this->Vd6d33a32,"rb");
} if (!$this->V50dffd37) { Fb7d30ee1("error: {this->logfile} could not be opened"); return; } $this->V42dfa3a4['logfile_size']=filesize($this->Vd6d33a32); 
 while(!feof($this->V50dffd37)) { $this->Va733fe6b=ftell($this->V50dffd37); $V6438c669 = fgets($this->V50dffd37, cBIG_STRING_LENGTH); 
 $V6438c669=rtrim($V6438c669,"\r\n"); $this->F20dd322a($V6438c669); } fclose($this->V50dffd37); 
 } function F7212cda9($V341be97d) { $Vfc9b3a06=preg_replace("/\\^[xX][\da-fA-F]{6}/","",$V341be97d);
$Vfc9b3a06=preg_replace("/\\^[^\\^]/","",$Vfc9b3a06); return $Vfc9b3a06; } function Fa3f5d48d($V341be97d)
 { $Vc6a8fe6b=1; $V865c0c0b=0; $V5b7f33be=0; $Veedf5beb=strlen($V341be97d); if ($Veedf5beb<1) return " ";
if ($Vc6a8fe6b) $Vf8f0c0d8="`#FFFFFF"; for ($V865c0c0b=0;$V865c0c0b< $Veedf5beb-1;$V865c0c0b++) {
 if ($V341be97d[$V865c0c0b]=="^" && $V341be97d[$V865c0c0b+1]!="^") { $V5b7f33be = ord($V341be97d[$V865c0c0b+1]);
if ($Vc6a8fe6b) { if ($V5b7f33be == 70 || $V5b7f33be == 102 || $V5b7f33be == 66 || $V5b7f33be == 98 || $V5b7f33be == 78)
 { $V865c0c0b++; continue; } if (($V5b7f33be == 88 || $V5b7f33be == 120) && strlen($V341be97d)-$V865c0c0b>6)
 { if (preg_match("/^[\da-fA-F]{6}/",substr($V341be97d, $V865c0c0b+2,6),$Vb74df323)) { $Vf8f0c0d8 .= "`#";
$Vf8f0c0d8 .= substr($V341be97d, $V865c0c0b+2,6); $V865c0c0b += 7; continue; } } switch($V5b7f33be%8)
 { case 0: $Vf8f0c0d8 .= "`#777777"; break; case 1: $Vf8f0c0d8 .= "`#FF0000"; break; case 2: $Vf8f0c0d8 .= "`#00FF00";
break; case 3: $Vf8f0c0d8 .= "`#FFFF00"; break; case 4: $Vf8f0c0d8 .= "`#4444FF"; break; case 5: $Vf8f0c0d8 .= "`#00FFFF";
break; case 6: $Vf8f0c0d8 .= "`#FF00FF"; break; case 7: $Vf8f0c0d8 .= "`#FFFFFF"; break; } } $V865c0c0b++;
} else { $Vf8f0c0d8 .=$V341be97d[$V865c0c0b]; } } if ($V865c0c0b<$Veedf5beb) { $Vf8f0c0d8 .= $V341be97d[$V865c0c0b];
} return $Vf8f0c0d8; } function Ffa84691e() { if (preg_match("/^(\d+):(\d+)/", $this->Vc3ecd549, $Vb74df323))
 { $V110decc3['min']=$Vb74df323[1]; $V110decc3['sec']=$Vb74df323[2]; return date ("Y-m-d H:i:s", adodb_mktime ($this->V9693e947['hour'],$this->V9693e947['min']+$V110decc3['min'],$this->V9693e947['sec']+$V110decc3['sec'],$this->V9693e947['month'],$this->V9693e947['date'],$this->V9693e947['year']));
} else if (preg_match("/^(\d+).(\d+)/", $this->Vc3ecd549, $Vb74df323)) { $V110decc3['min']=0; $V110decc3['sec']=$Vb74df323[1]; 
 return date ("Y-m-d H:i:s", adodb_mktime ($this->V9693e947['hour'],$this->V9693e947['min']+$V110decc3['min'],$this->V9693e947['sec']+$V110decc3['sec'],$this->V9693e947['month'],$this->V9693e947['date'],$this->V9693e947['year']));
} else if (preg_match("/^(\d+):(\d+):(\d+)/", $this->Vc3ecd549, $Vb74df323)) { $V110decc3['hour']=$Vb74df323[1];
$V110decc3['min'] =$Vb74df323[2]; $V110decc3['sec'] =$Vb74df323[3]; return date ("Y-m-d H:i:s", adodb_mktime ($V110decc3['hour'],$V110decc3['min'],$V110decc3['sec'],$this->V9693e947['month'],$this->V9693e947['date'],$this->V9693e947['year']));
} } function F7939839b(&$V6438c669) { foreach ($this->Vdba7d3a2 as $V8792851f) { $Vd405fc11 = "/".($V8792851f)."/";
 if (preg_match($Vd405fc11, $V6438c669, $Vb74df323)) { if ($this->Vdafa753c) { Fb7d30ee1("corrupt game (no Shutdown after Init), ignored\n");
Fb7d30ee1("{$this->Vc3ecd549} $V6438c669\n"); $this->Vae2aeb93->Fc3b570a7(); $this->Vae2aeb93->F242ca9da();
} $this->Vdafa753c= true; $this->Vb3b1075a=$this->Va733fe6b; $this->F713be45c(); $this->Vae2aeb93->Fd45b6912(); 
 $this->Vae2aeb93->F6d04475a("_v_time_start",date('Y-m-d H:i:s')); $this->Vae2aeb93->F6d04475a("_v_map","?");
$this->Vae2aeb93->F6d04475a("_v_game",'q3a'); if (isset($this->V42dfa3a4['mod'])) $this->Vae2aeb93->F6d04475a("_v_mod",$this->V42dfa3a4['mod']);
else $this->Vae2aeb93->F6d04475a("_v_mod","?"); $this->Vae2aeb93->F6d04475a("_v_game_type","?");
 return true; } } return false; } function Fdd0441e2(&$V6438c669) { while(!feof($this->V50dffd37))
 { $this->Va733fe6b=ftell($this->V50dffd37); $V6438c669 = fgets($this->V50dffd37, cBIG_STRING_LENGTH);
$V6438c669=rtrim($V6438c669,"\r\n"); if (preg_match("/^Accuracy info for\\: (?:\\^[^\\^])?(.*?)(?:\\^[^\\^])?$/", $V6438c669, $Vb74df323))
 { $V912af0df=$Vb74df323[1]; continue; } $V6438c669=$this->F7212cda9($V6438c669); if (preg_match("/^(.*?) *\\: *(\d+\\.\d+) *(\d+)\\/(\d+) */",$V6438c669,$Va9ddcf51))
 { $V47239253=$Va9ddcf51[1]; $Vfce79135=$Va9ddcf51[3]; $V9f892c18=$Va9ddcf51[4]; if (!strcmp($V47239253,"MachineGun"))
 $V47239253="MACHINEGUN"; else if (!strcmp($V47239253,"Shotgun")) $V47239253="SHOTGUN"; else if (!strcmp($V47239253,"G.Launcher"))
 $V47239253="GRENADE"; else if (!strcmp($V47239253,"R.Launcher")) $V47239253="ROCKET"; else if (!strcmp($V47239253,"LightningGun"))
 $V47239253="LIGHTNING"; else if (!strcmp($V47239253,"Railgun")) $V47239253="RAILGUN"; else if (!strcmp($V47239253,"Plasmagun"))
 $V47239253="PLASMA"; else $V47239253=preg_replace("/^MOD_/","",$V47239253); $this->Vae2aeb93->F4135e567($V912af0df,$V912af0df,"accuracy|{$V47239253}_hits",$Vfce79135);
$this->Vae2aeb93->F4135e567($V912af0df,$V912af0df,"accuracy|{$V47239253}_shots",$V9f892c18); } else if (preg_match("/^Total damage given\\: (.*)$/",$V6438c669,$Va9ddcf51))
 { $this->Vae2aeb93->F72d01d3f($V912af0df,"damage given",$Va9ddcf51[1]); } else if (preg_match("/^Total damage rcvd \\: (.*)$/",$V6438c669,$Va9ddcf51))
 { $this->Vae2aeb93->F72d01d3f($V912af0df,"damage taken",$Va9ddcf51[1]); } else if (preg_match("/^Map\\: (.*)/",$V6438c669,$Va9ddcf51))
 { $this->Vae2aeb93->F6d04475a("_v_map",$Va9ddcf51[1]); return true; } else if (preg_match("/entered the game/",$V6438c669,$Va9ddcf51))
 { return true; } } return true; } function Fc5aace53(&$V6438c669) { foreach ($this->V1c7b7557 as $V8792851f)
 { $Vd405fc11 = "/".($V8792851f)."/"; if (preg_match($Vd405fc11, $V6438c669, $Vb74df323)) { $this->Fdd0441e2($V6438c669);
 if ($this->V93da65a9['savestate']==1) { $this->F5c0b129c(); } $this->Vae2aeb93->Fc3b570a7(); $this->V21d8a920->F43781db5($this->Vae2aeb93->F26dd5333(),$this->Vae2aeb93->F068fac4f());
$this->Vae2aeb93->F242ca9da(); $this->Vdafa753c= false; return true; } } return false; } function F4b57e26a($Vc165b9b5)
 { foreach ($this->Va2bbabfe as $Vd915074e => $V163b0d74) { if (strstr($Vd915074e,$Vc165b9b5)) return $Vd915074e;
} return $Vc165b9b5; } function Fa00ebe94(&$V6438c669) { foreach ($this->Va10baeb6 as $V8792851f)
 { $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#PLAYER#","(.*?)",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $this->Va2bbabfe[$Vb74df323[1]]['name']=$this->Fa3f5d48d($Vb74df323[1]); $this->Vae2aeb93->F6aae4907($Vb74df323[1],$this->Fa3f5d48d($Vb74df323[1]));
return false; } } return false; } function F390143ba(&$V6438c669) { foreach ($this->V8ba4afff as $V8792851f)
 { $V912af0df=""; $Vf894427c=""; $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#PLAYER#","(.*?)",$Vd405fc11);
$Vd405fc11 = str_replace("#TEAM#",".+",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $V912af0df=$Vb74df323[1]; } $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#PLAYER#",".*",$Vd405fc11);
$Vd405fc11 = str_replace("#TEAM#","(.+?)",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $Vf894427c=$Vb74df323[1]; } if (strlen($V912af0df)>0 && strlen($Vf894427c)>0) { if ($this->F7212cda9($Vf894427c)=="RED")
 $Vf894427c="1"; else if($this->F7212cda9($Vf894427c)=="BLUE") $Vf894427c="2"; $this->Vae2aeb93->F555c9055($V912af0df,$Vf894427c);
return true; } } return false; } function F58a1721d(&$V6438c669) { foreach ($this->V2e9590a3 as $V8792851f => $V80cfa351)
 { $V96d4976b=""; $Vb36d3314=""; $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#VICTIM#","(.*?)",$Vd405fc11);
$Vd405fc11 = str_replace("#KILLER#",".*",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $V96d4976b=$Vb74df323[1]; if (strlen($V96d4976b)>=29) { $V96d4976b=$this->F4b57e26a($V96d4976b);
} } $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#VICTIM#",".*",$Vd405fc11); $Vd405fc11 = str_replace("#KILLER#","(.*?)",$Vd405fc11); 
 if (preg_match($Vd405fc11, $V6438c669, $Vb74df323)) { if (isset($Vb74df323[1])) { $Vb36d3314=$Vb74df323[1];
if (strlen($Vb36d3314)>=29) { $Vb36d3314=$this->F4b57e26a($Vb36d3314); } } } if (strlen($V96d4976b)>0 && strlen($Vb36d3314)>0)
 { $this->Vae2aeb93->Fd65f3244($Vb36d3314,$V96d4976b,$V80cfa351); return true; } else if (strlen($V96d4976b)>0)
 { $this->Vae2aeb93->Fd65f3244($V96d4976b,$V96d4976b,$V80cfa351); return true; } else { } } return false;
} function F53e6621b(&$V6438c669) { foreach ($this->Va509f5c2 as $V8792851f => $V6b0755dd) { $Vd405fc11 = "/".($V8792851f)."/";
$Vd405fc11 = str_replace("#PLAYER#","(.*?)",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $this->Vae2aeb93->F72d01d3f($Vb74df323[1],$V6b0755dd,1); return true; } } return false; } function F92efcba9(&$V6438c669)
 { foreach ($this->Vbbf5edcf as $V8792851f) { $V912af0df=""; $Vb068931c=""; $Vd405fc11 = "/".($V8792851f)."/";
$Vd405fc11 = str_replace("#PLAYER#","(.*?)",$Vd405fc11); $Vd405fc11 = str_replace("#NAME#",".+",$Vd405fc11);
 if (preg_match($Vd405fc11, $V6438c669, $Vb74df323)) { $V912af0df=$Vb74df323[1]; } $Vd405fc11 = "/".($V8792851f)."/";
$Vd405fc11 = str_replace("#PLAYER#",".*",$Vd405fc11); $Vd405fc11 = str_replace("#NAME#","(.*)",$Vd405fc11);
 if (preg_match($Vd405fc11, $V6438c669, $Vb74df323)) { $Vb068931c=$Vb74df323[1]; } if (strlen($V912af0df)>0 && strlen($Vb068931c)>0)
 { $V1963b948=$this->Fa3f5d48d($Vb068931c); $this->Vae2aeb93->Fec5ab55c("sto",$V912af0df,"alias",$V1963b948);
$this->Vae2aeb93->Fddcbd60f($V912af0df,$V1963b948); $this->Vae2aeb93->F95791962($V912af0df,$Vb068931c); 
 return true; } } return false; } function F7888de3f(&$V6438c669) { foreach ($this->def_chat as $V8792851f)
 { $V912af0df=""; $Vaa8af3eb=""; $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#PLAYER#","(.*?)",$Vd405fc11);
$Vd405fc11 = str_replace("#CHAT#",".+",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $V912af0df=$Vb74df323[1]; } $Vd405fc11 = "/".($V8792851f)."/"; $Vd405fc11 = str_replace("#PLAYER#",".*",$Vd405fc11);
$Vd405fc11 = str_replace("#CHAT#","(.*?)",$Vd405fc11); if (preg_match($Vd405fc11, $V6438c669, $Vb74df323))
 { $Vaa8af3eb=$Vb74df323[1]; } if (strlen($V912af0df)>0 && strlen($Vaa8af3eb)>0) { $this->Vae2aeb93->F8405e6ea($V912af0df,$this->F7212cda9($Vaa8af3eb));
return true; } } return false; } function F60053465(&$V6438c669) { if (!preg_match("/^\\^?\d+ *([\da-fA-F]*)\\((.*?)\\) .*? *\d+\\.\d+ \d+ (.*)$/", $V6438c669, $Vb74df323))
 return false; $this->Vae2aeb93->Fec5ab55c("sto",$Vb74df323[3],"guid",$Vb74df323[1]); return true;
} function Fa5af98c8(&$V6438c669) { return false; } function Fd1ef0bee(&$V6438c669) { return false;
} function Fe7c49e90(&$V6438c669) { return false; } function Fd624d004(&$V6438c669) { return false;
} function F26a565c8(&$V6438c669) { if (!strcmp($this->V93da65a9['gametype'],"osp")) { return $this->Fa5af98c8($V6438c669);
} else if (!strcmp($this->V93da65a9['gametype'],"threewave")) { if ($this->Fa5af98c8($V6438c669))
 return true; else if ($this->Fd1ef0bee($V6438c669)) return true; else return false; } else if (!strcmp($this->V93da65a9['gametype'],"freeze"))
 { if ($this->Fa5af98c8($V6438c669)) return true; else if ($this->Fe7c49e90($V6438c669)) return true;
else return false; } else if (!strcmp($this->V93da65a9['gametype'],"ut")) { return $this->F98e2592b($V6438c669);
} else if (!strcmp($this->V93da65a9['gametype'],"ra3")) { return $this->Fd624d004($V6438c669); } return false;
} function F20dd322a(&$V6438c669) { if ($this->F7939839b($V6438c669)) { echo sprintf("(%05.2f%%) ",100.0 * ftell($this->V50dffd37)/$this->V42dfa3a4['logfile_size']);
} else if ($this->Vdafa753c) { if ($this->F26a565c8($V6438c669)) { } else if ($this->Fa00ebe94($V6438c669))
 { } else if ($this->F390143ba($V6438c669)) { } else if ($this->F58a1721d($V6438c669)) { } else if ($this->F53e6621b($V6438c669))
 { } else if ($this->F92efcba9($V6438c669)) { } else if ($this->Fc5aace53($V6438c669)) { } else if ($this->F60053465($V6438c669))
 { } else if ($this->F7888de3f($V6438c669)) { } else { } } else { if (preg_match("/^Current search path\\:/", $V6438c669, $Vb74df323))
 { $this->Va733fe6b=ftell($this->V50dffd37); $V6438c669 = fgets($this->V50dffd37, cBIG_STRING_LENGTH);
$V6438c669=rtrim($V6438c669,"\r\n"); if (preg_match("/[\\\\\/]([^\\\\\/]*)[\\\\\/][^\\\\\/]*$/", $V6438c669, $Va9ddcf51))
 { $this->V42dfa3a4['mod']=$Va9ddcf51[1]; } } } } } ?>