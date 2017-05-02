<?php
  /* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */
  class VSPParserQ3A
  {
      var $V93da65a9;
      var $V21d8a920;
      var $Vae2aeb93;
      var $Va2bbabfe;
      var $V6d2b5d2c;
      var $V42dfa3a4;
      var $Vc3ecd549;
      var $V9693e947;
      var $Vdafa753c;
      var $V50dffd37;
      var $Vd6d33a32;
      var $logdata;
      var $Va733fe6b;
      var $Vb3b1075a;
      function VSPParserQ3A($Ve0d85fdc, &$V4f00ff2f, &$V495c39bf)
      {
          define("C7e731e80", 1024);
          $this->Fcda1c5ae($Ve0d85fdc);
          $this->V21d8a920 = $V4f00ff2f;
          $this->Vae2aeb93 = $V495c39bf;
          $this->V6d2b5d2c = array();
          $this->V42dfa3a4 = array();
          $this->Va2bbabfe = array();
          $this->logdata = array();
          $this->Vdafa753c = false;
          $this->V42dfa3a4['weapon_name']['search'] = array("/MOD_/", "/_SPLASH/");
          $this->V42dfa3a4['weapon_name']['replace'] = array("", "");
          $this->V42dfa3a4['char_trans'] = array(
             "^<" => "^4", "^>" => "^6", "^&" => "^6",
             "\x01" => "(", "\x02" => "▀", "\x03" => ")", "\x04" => "█", "\x05" => " ", "\x06" => "█", "\x07" => "(", "\x08" => "▄", "\x09" => ")", "\x0b" => "_", "\x0b" => "■", "\x0c" => " ", "\x0d" => "►", "\x0e" => "·", "\x0f" => "·",
             "\x10" => "[", "\x11" => "]", "\x12" => "|¯", "\x13" => "¯", "\x14" => "¯|", "\x15" => "|", "\x16" => " ", "\x17" => "|", "\x18" => "|_", "\x19" => "_", "\x1a" => "_|", "\x1b" => "¯", "\x1c" => "·", "\x1d" => "(", "\x1e" => "-", "\x1f" => ")",
             "\x7f" => "<-",
             "\x80" => "(", "\x81" => "=", "\x82" => ")", "\x83" => "|", "\x84" => " ", "\x85" => "·", "\x86" => "▼", "\x87" => "▲", "\x88" => "◄", "\x89" => " ", "\x8a" => " ", "\x8b" => "■", "\x8c" => " ", "\x8d" => "►", "\x8e" => "·", "\x8f" => "·",
             "\x90" => "[", "\x91" => "]", "\x92" => "0", "\x93" => "1", "\x94" => "2", "\x95" => "3", "\x96" => "4", "\x97" => "5", "\x98" => "6", "\x99" => "7", "\x9a" => "8", "\x9b" => "9", "\x9c" => "·", "\x9d" => "(", "\x9e" => "-", "\x9f" => ")",
             "\xa0" => " ", "\xa1" => "!", "\xa2" => "\"", "\xa3" => "#", "\xa4" => "$", "\xa5" => "%", "\xa6" => "&", "\xa7" => "'", "\xa8" => "(", "\xa9" => ")", "\xaa" => "*", "\xab" => "+", "\xac" => ",", "\xad" => "-", "\xae" => ".", "\xaf" => "/",
             "\xb0" => "0", "\xb1" => "1", "\xb2" => "2", "\xb3" => "3", "\xb4" => "4", "\xb5" => "5", "\xb6" => "6", "\xb7" => "7", "\xb8" => "8", "\xb9" => "9", "\xba" => ":", "\xbb" => ";", "\xbc" => "<", "\xbd" => "=", "\xbe" => ">", "\xbf" => "?",
             "\xc0" => "@", "\xc1" => "A", "\xc2" => "B", "\xc3" => "C", "\xc4" => "D", "\xc5" => "E", "\xc6" => "F", "\xc7" => "G", "\xc8" => "H", "\xc9" => "I", "\xca" => "J", "\xcb" => "K", "\xcc" => "L", "\xcd" => "M", "\xce" => "N", "\xcf" => "O",
             "\xd0" => "P", "\xd1" => "Q", "\xd2" => "R", "\xd3" => "S", "\xd4" => "T", "\xd5" => "U", "\xd6" => "V", "\xd7" => "W", "\xd8" => "X", "\xd9" => "Y", "\xda" => "Z", "\xdb" => "[", "\xdc" => "\\", "\xdd" => "]", "\xde" => "^", "\xdf" => "_",
             "\xe0" => "'", "\xe1" => "A", "\xe2" => "B", "\xe3" => "C", "\xe4" => "D", "\xe5" => "E", "\xe6" => "F", "\xe7" => "G", "\xe8" => "H", "\xe9" => "I", "\xea" => "J", "\xeb" => "K", "\xec" => "L", "\xed" => "M", "\xee" => "N", "\xef" => "O",
             "\xf0" => "P", "\xf1" => "Q", "\xf2" => "R", "\xf3" => "S", "\xf4" => "T", "\xf5" => "U", "\xf6" => "V", "\xf7" => "W", "\xf8" => "X", "\xf9" => "Y", "\xfa" => "Z", "\xfb" => "{", "\xfc" => "|", "\xfd" => "}", "\xfe" => "\"", "\xff" => "->");
      }
      function Fcda1c5ae($Ve0d85fdc)
      {
          $this->V93da65a9['savestate'] = 0;
          $this->V93da65a9['gametype'] = "";
          $this->V93da65a9['backuppath'] = "";
          $this->V93da65a9['trackID'] = "playerName";
          //change: xp version for special chars
          $this->V93da65a9['xp_version'] = 200;
          //endchange
          if (is_array($Ve0d85fdc)) {
              foreach ($Ve0d85fdc as $Ve7cb9038 => $Va36fd2a1) {
                  $this->V93da65a9[$Ve7cb9038] = $Va36fd2a1;
              }
          }
          if ($this->V93da65a9['backuppath']) {
              $this->V93da65a9['backuppath'] = F9578dd1f($this->V93da65a9['backuppath']);
          }
          echo "[parser options]: ";
          print_r($this->V93da65a9);
      }
      function F713be45c() // supongo que inicia variables auxiliares para la fecha
      {
          unset($this->Va2bbabfe);
          $this->Va2bbabfe = array();
          unset($this->V6d2b5d2c);
          $this->V6d2b5d2c = array();
          $this->V9693e947['month'] = 12;
          $this->V9693e947['date'] = 28;
          $this->V9693e947['year'] = 1971;
          $this->V9693e947['hour'] = 23;
          $this->V9693e947['min'] = 59;
          $this->V9693e947['sec'] = 59;
      }
      function F5c0b129c() // escribe información del savestate - unused
      {
          $this->logdata["last_shutdown_end_position"] = ftell($this->V50dffd37);
          $V9a52fe40 = fseek($this->V50dffd37, -C7e731e80, SEEK_CUR);
          if ($V9a52fe40 == 0) {
              $this->logdata['last_shutdown_hash'] = md5(fread($this->V50dffd37, C7e731e80));
          } else {
              $V284073b9 = ftell($this->V50dffd37);
              fseek($this->V50dffd37, 0);
              $this->logdata['last_shutdown_hash'] = md5(fread($this->V50dffd37, $V284073b9));
          }
          $V3b2eb2c1 = fopen('./logdata/savestate_' . Fff47f8ac($this->Vd6d33a32) . '.inc.php', "wb");
          fwrite($V3b2eb2c1, "<?php /" . "* DO NOT EDIT THIS FILE! */\n");
          fwrite($V3b2eb2c1, "\$this->logdata['last_shutdown_hash']='{$this->logdata['last_shutdown_hash']}';\n");
          fwrite($V3b2eb2c1, "\$this->logdata['last_shutdown_end_position']={$this->logdata['last_shutdown_end_position']};\n");
          fwrite($V3b2eb2c1, "?>");
          fclose($V3b2eb2c1);
      }
      function Fb96636b2() // savestate check - unused
      {
          echo "Verifying savestate\n";
          $V8774de0e = fopen($this->Vd6d33a32, "rb");
          $V2843c763 = fseek($V8774de0e, $this->logdata['last_shutdown_end_position']);
          if ($V2843c763 == 0) {
              $V9a52fe40 = fseek($V8774de0e, -C7e731e80, SEEK_CUR);
              if ($V9a52fe40 == 0) {
                  $Vb9cc7f4b = fread($V8774de0e, C7e731e80);
              } else {
                  $V284073b9 = ftell($V8774de0e);
                  fseek($V8774de0e, 0);
                  $Vb9cc7f4b = fread($V8774de0e, $V284073b9);
              }
              if (strcmp(md5($Vb9cc7f4b), $this->logdata['last_shutdown_hash']) == 0) {
                  echo " - Hash matched, resuming parsing from previous saved location in log file\n";
                  fseek($this->V50dffd37, $this->logdata['last_shutdown_end_position']);
              } else {
                  echo " - Hash did not match, assuming new log file\n";
                  fseek($this->V50dffd37, 0);
              }
          } else {
              echo " - Seek to prior location failed, assuming new log file\n";
              fseek($this->V50dffd37, 0);
          }
          fclose($V8774de0e);
      }
      function F1417ca90($Vdbe56eaf)
      {
          $this->Vd6d33a32 = realpath($Vdbe56eaf);
          if (!file_exists($this->Vd6d33a32)) {
              F03c2b497("error: log file \"{$Vdbe56eaf}\" does not exist");
          }

          //change: excessiveplus 1.03 fix
          $this->original_log = $this->Vd6d33a32;
          if (!strcmp($this->V93da65a9['gametype'], 'xp') && $this->V93da65a9['xp_version'] == 103) {
              require("xp103_compat.inc.php");
              $this->Vd6d33a32 = repair_xp_logfile($this->Vd6d33a32, $this->V93da65a9['savestate'] == 1);
          }
          //endchange

          $this->F713be45c();
          if ($this->V93da65a9['savestate'] == 1) {
              //change: savestate centralized and now db-driven
              echo "savestate 1 processing enabled\n";
              global $V9c1ebee8; // db
              $sql = "SELECT `value` FROM {$GLOBALS['cfg']['db']['table_prefix']}savestate WHERE `logfile` = ".$V9c1ebee8->qstr($this->original_log);
              $V3a2d7564 = $V9c1ebee8->Execute($sql);
              if ($V3a2d7564 and !$V3a2d7564->EOF) {
                  eval($V3a2d7564->fields[0]);
              }
              //@include_once('./logdata/savestate_' . Fff47f8ac($this->Vd6d33a32) . '.inc.php');
              $this->V50dffd37 = fopen($this->Vd6d33a32, "rb");
              if (!empty($this->logdata)) {
                  //$this->Fb96636b2();
                  check_savestate($this);
              }
              //endchange
          } else {
              $this->V50dffd37 = fopen($this->Vd6d33a32, "rb");
          }
          if (!$this->V50dffd37) {
              Fb7d30ee1("error: {this->logfile} could not be opened");
              return;
          }
          $this->V42dfa3a4['logfile_size'] = filesize($this->Vd6d33a32);
          while (!feof($this->V50dffd37)) { // ciclo que parsea el archivo
              $this->Va733fe6b = ftell($this->V50dffd37); // obtiene posición del puntero
              $V6438c669 = fgets($this->V50dffd37, cBIG_STRING_LENGTH); // obtiene línea del archivo
              $V6438c669 = rtrim($V6438c669, "\r\n");
              $this->F20dd322a($V6438c669); // parsea la línea
          }
          fclose($this->V50dffd37);
          if (isset($this->original_log) && function_exists('remove_xp_tmp_logfile')) { //change: xp 1.03 fix
              remove_xp_tmp_logfile($this->Vd6d33a32);
          }
      }
      function F7212cda9($V341be97d)
      {
          $Vfc9b3a06 = preg_replace("/\\^[xX][\da-fA-F]{6}/", "", $V341be97d);
          $Vfc9b3a06 = preg_replace("/\\^[^\\^]/", "", $Vfc9b3a06);
          return $Vfc9b3a06;
      }
      function F3ffa4f4e($V341be97d)
      {
          //$V70dda5df = array("black", "red", "lime", "yellow", "blue", "aqua", "fuchsia", "white", "orange");
          //change: special chars
          if ($this->V93da65a9['xp_version'] <= 103) {
              // 1.03 special chars
              $V341be97d = preg_replace("/\+([\x01-\x7F])#/e", "chr(ord('\\1') + 127)", $V341be97d);
          } else {
              // 1.04 special chars
              $V341be97d = preg_replace("/#(#|[0-9a-f]{2})/ie", "'\\1' == '#' ? '#' : chr(hexdec('\\1'))", $V341be97d);
          }
          //endchange
          $V70dda5df = array("#555555", "#e90000", "#00dd24", "#f5d800", "#2e61c8", "#16b4a5", "#f408f1", "#efefef", "#ebbc1b");
          $tmp = array("\xde" => "^");
          $V341be97d = strtr($V341be97d, array_diff_assoc($this->V42dfa3a4['char_trans'], $tmp));
          if ($V341be97d[0] != "^")
              $V341be97d = "^7" . $V341be97d;
          $V341be97d = preg_replace('/\^(a[1-9]|[fFrRbBl])/', "", $V341be97d);
          $V341be97d = preg_replace('/\^s(\^x[a-fA-F0-9]{6}|\^[^\^])/', "\\1", $V341be97d);
          $V341be97d = preg_replace('/\^s/', "^7", $V341be97d);
          $V341be97d = preg_replace('/(\^(x[a-fA-F0-9]{6}|[^\^]))\^(x[a-fA-F0-9]{6}|[^\^])/', "\\1", $V341be97d);
          $V341be97d = preg_replace('/\^x([a-fA-F0-9]{6})/i', "`#\\1", $V341be97d);
          $V341be97d = preg_replace('/\^([^\^<])/e', "'`' . \$V70dda5df[ord('\\1') % 8]", $V341be97d);
          $V341be97d = strtr($V341be97d, $tmp);
          return $V341be97d;
      }
      function Fa3f5d48d($V341be97d) // parece eliminar efectos del nombre
      {
          if (!strcmp($this->V93da65a9['gametype'], "xp"))
              return $this->F3ffa4f4e($V341be97d);
          $V341be97d = strtr($V341be97d, $this->V42dfa3a4['char_trans']);
          $Vc6a8fe6b = 1;
          $V865c0c0b = 0;
          $V5b7f33be = 0;
          $Veedf5beb = strlen($V341be97d);
          if ($Veedf5beb < 1)
              return " ";
          if ($Vc6a8fe6b)
              $Vf8f0c0d8 = "`#FFFFFF";
          for ($V865c0c0b = 0; $V865c0c0b < $Veedf5beb - 1; $V865c0c0b++) {
              if ($V341be97d[$V865c0c0b] == "^" && $V341be97d[$V865c0c0b + 1] != "^") {
                  $V5b7f33be = ord($V341be97d[$V865c0c0b + 1]);
                  if ($Vc6a8fe6b) {
                      if ($V5b7f33be == 70 || $V5b7f33be == 102 || $V5b7f33be == 66 || $V5b7f33be == 98 || $V5b7f33be == 78) {
                          $V865c0c0b++;
                          continue;
                      }
                      if (($V5b7f33be == 88 || $V5b7f33be == 120) && strlen($V341be97d) - $V865c0c0b > 6) {
                          if (preg_match("/^[\da-fA-F]{6}/", substr($V341be97d, $V865c0c0b + 2, 6), $Vb74df323)) {
                              $Vf8f0c0d8 .= "`#";
                              $Vf8f0c0d8 .= substr($V341be97d, $V865c0c0b + 2, 6);
                              $V865c0c0b += 7;
                              continue;
                          }
                      }
                      switch ($V5b7f33be % 8) {
                          case 0:
                              $Vf8f0c0d8 .= "`#777777";
                              break;
                          case 1:
                              $Vf8f0c0d8 .= "`#FF0000";
                              break;
                          case 2:
                              $Vf8f0c0d8 .= "`#00FF00";
                              break;
                          case 3:
                              $Vf8f0c0d8 .= "`#FFFF00";
                              break;
                          case 4:
                              $Vf8f0c0d8 .= "`#4444FF";
                              break;
                          case 5:
                              $Vf8f0c0d8 .= "`#00FFFF";
                              break;
                          case 6:
                              $Vf8f0c0d8 .= "`#FF00FF";
                              break;
                          case 7:
                              $Vf8f0c0d8 .= "`#FFFFFF";
                              break;
                      }
                  }
                  $V865c0c0b++;
              } else {
                  $Vf8f0c0d8 .= $V341be97d[$V865c0c0b];
              }
          }
          if ($V865c0c0b < $Veedf5beb) {
              $Vf8f0c0d8 .= $V341be97d[$V865c0c0b];
          }
          return $Vf8f0c0d8;
      }
      function Fe846ce3f($Vb0aae2e8)
      {
          foreach ($this->Va2bbabfe as $V74aaef3b => $V9ba96d33) {
              if (!strcmp($this->Va2bbabfe[$V74aaef3b]['id'], $Vb0aae2e8)) {
                  return $V74aaef3b;
              }
          }
          return "";
      }
      function F381a6ffc($V3b043eba)
      {
          foreach ($this->Va2bbabfe as $V74aaef3b => $V9ba96d33) {
              if (!strcmp($this->Va2bbabfe[$V74aaef3b]['name'], $V3b043eba)) {
                  return $V74aaef3b;
              }
          }
          return "";
      }
      function Ffa84691e() // devuelve hora del servidor como timestamp
      {
          if (preg_match("/^(\d+)[\\:\\.](\d+)[\\:\\.](\d+)/", $this->Vc3ecd549, $Vb74df323)) {
              $V110decc3['hour'] = $Vb74df323[1];
              $V110decc3['min'] = $Vb74df323[2];
              $V110decc3['sec'] = $Vb74df323[3];
              return date("Y-m-d H:i:s", adodb_mktime($V110decc3['hour'], $V110decc3['min'], $V110decc3['sec'], $this->V9693e947['month'], $this->V9693e947['date'], $this->V9693e947['year']));
          } elseif (preg_match("/^(\d+):(\d+)/", $this->Vc3ecd549, $Vb74df323)) {
              $V110decc3['min'] = $Vb74df323[1];
              $V110decc3['sec'] = $Vb74df323[2];
              return date("Y-m-d H:i:s", adodb_mktime($this->V9693e947['hour'], $this->V9693e947['min'] + $V110decc3['min'], $this->V9693e947['sec'] + $V110decc3['sec'], $this->V9693e947['month'], $this->V9693e947['date'], $this->V9693e947['year']));
          } elseif (preg_match("/^(\d+).(\d+)/", $this->Vc3ecd549, $Vb74df323)) {
              $V110decc3['min'] = 0;
              $V110decc3['sec'] = $Vb74df323[1];
              return date("Y-m-d H:i:s", adodb_mktime($this->V9693e947['hour'], $this->V9693e947['min'] + $V110decc3['min'], $this->V9693e947['sec'] + $V110decc3['sec'], $this->V9693e947['month'], $this->V9693e947['date'], $this->V9693e947['year']));
          }
      }
      function F2fe073bc(&$V6438c669) // hora del servidor
      {
          if (!preg_match("/^ServerTime:\s+(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})\s+/", $V6438c669, $Vb74df323))
              return false;
          $this->V9693e947['year'] = $Vb74df323[1];
          $this->V9693e947['month'] = $Vb74df323[2];
          $this->V9693e947['date'] = $Vb74df323[3];
          $this->V9693e947['hour'] = $Vb74df323[4];
          $this->V9693e947['min'] = $Vb74df323[5];
          $this->V9693e947['sec'] = $Vb74df323[6];
          $this->Vae2aeb93->F6d04475a("_v_time_start", date("Y-m-d H:i:s", adodb_mktime($this->V9693e947['hour'], $this->V9693e947['min'], $this->V9693e947['sec'], $this->V9693e947['month'], $this->V9693e947['date'], $this->V9693e947['year'])));
          return true;
      }
      function F7939839b(&$V6438c669) // es inicio de juego
      {
          if (!preg_match("/^InitGame: (.*)/", $V6438c669, $Vb74df323))
              return false;
          if ($this->Vdafa753c) {
              Fb7d30ee1("corrupt game (no Shutdown after Init), ignored\n");
              Fb7d30ee1("{$this->Vc3ecd549} $V6438c669\n");
              $this->Vae2aeb93->Fc3b570a7();
              $this->Vae2aeb93->F242ca9da();
          }
          $this->Vdafa753c = true;
          $this->Vb3b1075a = $this->Va733fe6b;
          $this->F713be45c();
          $Vf81b2562 = $Vb74df323[1];
          while (preg_match("/^\\\(.+)\\\(.+)\\\/U", $Vf81b2562, $Va9ddcf51) || preg_match("/^\\\(.+)\\\(.+)/", $Vf81b2562, $Va9ddcf51)) { // parsea lista de variables del servidor
              $Vccedf499 = $Va9ddcf51[1];
              $Vcc14d460 = $Va9ddcf51[2];
              $Ve37f0136[$Vccedf499] = $Vcc14d460; // crea matriz de variables
              $Vf81b2562 = substr($Vf81b2562, strlen($Vccedf499) + strlen($Vcc14d460) + 2);
              if (!strcmp($Vccedf499, "gamestartup")) {
                  if (preg_match("/^(\d+)[-\/](\d+)[-\/](\d+) +(\d+)[:-](\d+)[:-](\d+)/", $Vcc14d460, $Vd6fd0924)) {
                      $this->V9693e947['month'] = $Vd6fd0924[1];
                      $this->V9693e947['date'] = $Vd6fd0924[2];
                      $this->V9693e947['year'] = $Vd6fd0924[3];
                      $this->V9693e947['hour'] = $Vd6fd0924[4];
                      $this->V9693e947['min'] = $Vd6fd0924[5];
                      $this->V9693e947['sec'] = $Vd6fd0924[6];
                  }
              }
          }
          $this->Vae2aeb93->Fd45b6912(); // muestra mensaje de inicio de análisis de juego
          foreach ($Ve37f0136 as $V2948dbaa => $V91dfc652) {
              $this->Vae2aeb93->F6d04475a($V2948dbaa, $V91dfc652);
          }
          $this->Vae2aeb93->F6d04475a("_v_time_start", $this->Ffa84691e());
          $this->Vae2aeb93->F6d04475a("_v_map", $Ve37f0136['mapname']);
          $this->Vae2aeb93->F6d04475a("_v_game", 'q3a');
          $this->Vae2aeb93->F6d04475a("_v_mod", $Ve37f0136['gamename']);
          $this->Vae2aeb93->F6d04475a("_v_game_type", $Ve37f0136['g_gametype']);
          $this->V42dfa3a4['mod'] = $Ve37f0136['gamename'];
          $this->V42dfa3a4['gametype'] = $Ve37f0136['g_gametype'];
          $this->V42dfa3a4['gameversion'] = isset($Ve37f0136['xp_version']) ? $Ve37f0136['xp_version'] : $Ve37f0136['gameversion'];
          return true;
      }
      function Ff90d84c5(&$V6438c669) // cambio de la información del player
      {
          if (!preg_match("/^ClientUserinfoChanged: (\d+) (.*)/", $V6438c669, $Vb74df323))
              return false;
          $V2bfe9d72 = $Vb74df323[1]; // id
          $V4fe74676 = $Vb74df323[2]; // variables
          while (preg_match("/^(.+)\\\(.*)\\\/U", $V4fe74676, $Va9ddcf51) || preg_match("/^(.+)\\\(.*)/", $V4fe74676, $Va9ddcf51)) { // parsea las variables
              $Vf986617c = $Va9ddcf51[1];
              $Vada01463 = $Va9ddcf51[2];
              $V4fe74676 = substr($V4fe74676, strlen($Vf986617c) + strlen($Vada01463) + 2);
              if (!strcmp($Vf986617c, "n")) { // nombre
                  $V1963b948 = $this->Fa3f5d48d($Vada01463); // elimina caracteres extraños del nombre
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['id']) && $this->V93da65a9['trackID'] == 'playerName' && strcmp($this->Va2bbabfe[$V2bfe9d72]['id'], $Vada01463) != 0) {
                      $this->Vae2aeb93->Fec5ab55c("sto", $this->Va2bbabfe[$V2bfe9d72]['id'], "alias", $V1963b948);
                      $this->Vae2aeb93->Fddcbd60f($this->Va2bbabfe[$V2bfe9d72]['id'], $V1963b948);
                      $this->Vae2aeb93->F95791962($this->Va2bbabfe[$V2bfe9d72]['id'], $Vada01463);
                      $this->Va2bbabfe[$V2bfe9d72]['id'] = $Vada01463;
                  } elseif (isset($this->Va2bbabfe[$V2bfe9d72]['id']) && isset($this->Va2bbabfe[$V2bfe9d72]['name']) && strcmp($this->Va2bbabfe[$V2bfe9d72]['name'], $V1963b948) != 0) {
                      $this->Vae2aeb93->Fec5ab55c("sto", $this->Va2bbabfe[$V2bfe9d72]['id'], "alias", $V1963b948);
                      $this->Vae2aeb93->Fddcbd60f($this->Va2bbabfe[$V2bfe9d72]['id'], $V1963b948);
                  } elseif ($this->V93da65a9['trackID'] == 'playerName') {
                      $this->Va2bbabfe[$V2bfe9d72]['id'] = $Vada01463;
                  } elseif ($this->V93da65a9['trackID'] == 'guid' && isset($this->Va2bbabfe[$V2bfe9d72]['guid'])) {
                      $this->Va2bbabfe[$V2bfe9d72]['id'] = $this->Va2bbabfe[$V2bfe9d72]['guid']; // track by guid
                  } elseif (preg_match("/^ip=(.+)/i", $this->V93da65a9['trackID'], $Vd6fd0924) && isset($this->Va2bbabfe[$V2bfe9d72]['ip']) && preg_match($Vd6fd0924[1], $this->Va2bbabfe[$V2bfe9d72]['ip'], $V793914c9)) {
                      $this->Va2bbabfe[$V2bfe9d72]['id'] = $V793914c9[1];
                  } else {
                      Fb7d30ee1("\$cfg['parser']['trackID'] is invalid, ignored\n");
                      Fb7d30ee1("Use \$cfg['parser']['trackID'] = 'playerName'; in your config\n");
                      Fb7d30ee1("{$this->Vc3ecd549} $V6438c669\n");
                      $this->Vae2aeb93->F242ca9da();
                      $this->Vdafa753c = false;
                      return true;
                  }
                  $this->Va2bbabfe[$V2bfe9d72]['name'] = $V1963b948; // asigna el nombre al player
              } elseif (!strcmp($Vf986617c, "t")) { // equipo
                  $this->Va2bbabfe[$V2bfe9d72]['team'] = $Vada01463; // asigna el equipo al player
                  if ($this->Va2bbabfe[$V2bfe9d72]['team'] != '3') { // no es espectador
                      //change: team control
                      if (!isset($this->Vae2aeb93->players_team)) {
                          $this->Vae2aeb93->players_team = array();
                          $this->has_acc_stats = array();
                      }
                      $this->Vae2aeb93->players_team[$V2bfe9d72] = array('team' => $this->Va2bbabfe[$V2bfe9d72]['team'], 'connected' => true);
                      $this->has_acc_stats[$V2bfe9d72] = false;
                      //endchange
                      $this->Vae2aeb93->F555c9055($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['team']);
                  }
              } elseif (!strcmp($Vf986617c, "model")) { // modelo
                  if ($this->Va2bbabfe[$V2bfe9d72]['team'] != '3') { // no es espectador
                      if (!isset($this->Va2bbabfe[$V2bfe9d72]['icon']) || strcmp($this->Va2bbabfe[$V2bfe9d72]['icon'], $Vada01463)) {
                          $this->Va2bbabfe[$V2bfe9d72]['icon'] = $Vada01463; // ícono del jugador
                      }
                  }
              }
          }
          return true;
      }
      function Ffab4963e(&$V6438c669) // si es inicio de cliente
      {
          if (!preg_match("/^ClientBegin: (\d+)/", $V6438c669, $Vb74df323))
              return false;
          $V2bfe9d72 =$Vb74df323[1]; // id
          if (isset($this->Va2bbabfe[$V2bfe9d72]['id'])) { // si el id existe
              if ($this->Va2bbabfe[$V2bfe9d72]['team'] != '3') { // no es espectador
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['name'])) { // si existe el nombre
                      $ip = @$this->Va2bbabfe[$V2bfe9d72]['ip'];
                      $tld = isset($this->Va2bbabfe[$V2bfe9d72]['rtld']) ? $this->Va2bbabfe[$V2bfe9d72]['rtld'] : @$this->Va2bbabfe[$V2bfe9d72]['tld'];
                      $this->Vae2aeb93->F6aae4907($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['name'], $ip, $tld); // inicializa datos del cliente
                  }
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['team'])) // si está en un team
                      $this->Vae2aeb93->F555c9055($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['team']);
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['role'])) // si existe el rol?
                      $this->Vae2aeb93->Fa3f3cadc($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['role']);
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['icon'])) // guarda ícono
                      $this->Vae2aeb93->Fec5ab55c("sto", $this->Va2bbabfe[$V2bfe9d72]['id'], "icon", $this->Va2bbabfe[$V2bfe9d72]['icon']);
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['ip'])) // si tiene ip
                      $this->Vae2aeb93->Fec5ab55c("sto", $this->Va2bbabfe[$V2bfe9d72]['id'], "ip", $this->Va2bbabfe[$V2bfe9d72]['ip']);
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['guid'])) // si tiene guid
                      $this->Vae2aeb93->Fec5ab55c("sto", $this->Va2bbabfe[$V2bfe9d72]['id'], "guid", $this->Va2bbabfe[$V2bfe9d72]['guid']);
                  if (isset($this->Va2bbabfe[$V2bfe9d72]['tld'])) // si tiene tld
                      $this->Vae2aeb93->Fec5ab55c("sto", $this->Va2bbabfe[$V2bfe9d72]['id'], "tld", isset($this->Va2bbabfe[$V2bfe9d72]['rtld']) ? $this->Va2bbabfe[$V2bfe9d72]['rtld'] : $this->Va2bbabfe[$V2bfe9d72]['tld']);
              }
          } else {
          }
          return true;
      }
      function F58a1721d(&$V6438c669) // si es notificación de frag
      {
          if (!preg_match("/^Kill: (\d+) (\d+) \d+: (.*) killed (.*) by (\w+)/", $V6438c669, $Vb74df323))
              return false;
          $V79ef036b = $Vb74df323[1];
          $V810f2cfd = $Vb74df323[2];
          $V2e7bf2ef = $Vb74df323[5];
          if ($V79ef036b > 128) { // suicidio
              $V79ef036b = $V810f2cfd;
          }
          $V2e7bf2ef = preg_replace($this->V42dfa3a4['weapon_name']['search'], $this->V42dfa3a4['weapon_name']['replace'], $V2e7bf2ef);
          if (isset($this->Va2bbabfe[$V79ef036b]['id']) && isset($this->Va2bbabfe[$V810f2cfd]['id'])) { // sólo si los 2 players existen
              //change: team control
              if (isset($this->Vae2aeb93->players_team)) {
                  $this->Vae2aeb93->Fd65f3244($V79ef036b, $V810f2cfd, $V2e7bf2ef, $this->Va2bbabfe);
              } else {
                  $this->Vae2aeb93->Fd65f3244($this->Va2bbabfe[$V79ef036b]['id'], $V810f2cfd, $V2e7bf2ef);
              }
              //endchange
          } else {
          }
          return true;
      }
      function Fa3c3a4ae(&$V6438c669) // si es obtención de item
      {
          if (!preg_match("/^Item: (\d+) (.*)/", $V6438c669, $Vb74df323))
              return false;
          $V2bfe9d72 = $Vb74df323[1];
          $V9d0307ba = $Vb74df323[2];
          $V9d0307ba = preg_replace("/ammo_/", "ammo|", $V9d0307ba, 1);
          $V9d0307ba = preg_replace("/weapon_/", "weapon|", $V9d0307ba, 1);
          $V9d0307ba = preg_replace("/item_/", "item|", $V9d0307ba, 1);
          if (isset($this->Va2bbabfe[$V2bfe9d72]['id'])) {
              $this->Vae2aeb93->F72d01d3f($V2bfe9d72, $V9d0307ba, 1, $this->Va2bbabfe); // dispara el evento de obtención de item
          }
          return true;
      }
      function F3237fc51(&$V6438c669) // si es chat del cliente
      {
          if (!preg_match("/^say: (.+): /U", $V6438c669, $Vb74df323))
              return false;
          $V9650cacb = $Vb74df323[1];
          $Vaa8af3eb = substr($V6438c669, strlen($Vb74df323[0]));
          $V2bfe9d72 = $this->F381a6ffc($this->Fa3f5d48d($V9650cacb));
          if (strlen($V2bfe9d72) > 0) {
              $Vaa8af3eb = $this->F7212cda9($Vaa8af3eb);
              $this->Vae2aeb93->Fec5ab55c("sto_glo", $this->Va2bbabfe[$V2bfe9d72]['id'], "chat", $Vaa8af3eb);
              $this->Vae2aeb93->F8405e6ea($this->Va2bbabfe[$V2bfe9d72]['id'], $Vaa8af3eb);
          }
          return true;
      }
      function F12520a4b(&$V6438c669) // si es conección de cliente
      {
          if (!preg_match("/^ClientConnect: (\d+)/", $V6438c669, $Vb74df323))
              return false;
          $V2bfe9d72 = $Vb74df323[1];
          if (isset($this->Va2bbabfe[$V2bfe9d72])) {
              unset($this->Va2bbabfe[$V2bfe9d72]);
          }
          return true;
      }
      function Fe460a20b(&$V6438c669) // si es desconexión de cliente
      {
          if (!preg_match("/^ClientDisconnect: (\d+)/", $V6438c669, $Vb74df323))
              return false;
          //change: team control
          if (isset($this->Vae2aeb93->players_team)) {
              $this->Vae2aeb93->players_team[$Vb74df323[1]]['connected'] = false;
          }
          //endchange
          return true;
      }
      function Fc5aace53(&$V6438c669) // si es finalización de juego
      {
          if (!preg_match("/^ShutdownGame:/", $V6438c669, $Vb74df323))
              return false;
          if ($this->V93da65a9['savestate'] == 1) {
              //change: savestate change
              //$this->F5c0b129c();
              save_savestate($this);
              //endchange
          }
          $this->Vae2aeb93->Fc3b570a7(); // actualiza los streaks de los jugadores
          //change: launch skills events
          $this->Vae2aeb93->launch_skill_events();
          //endchange
          $this->V21d8a920->F43781db5($this->Vae2aeb93->F26dd5333(), $this->Vae2aeb93->F068fac4f()); // actualiza los datos de los jugadores
          $this->Vae2aeb93->F242ca9da(); // limpieza de variables
          $this->Vdafa753c = false; // flag de juego en proceso
          return true;
      }
      function Fce212e15(&$V6438c669) // si es juego de calentamiento, ignorado
      {
          if (!preg_match("/^Warmup:/", $V6438c669, $Vb74df323))
              return false;
          Fb7d30ee1("warmup game, ignored\n");
          $this->Vae2aeb93->F242ca9da();
          $this->Vdafa753c = false;
          return true;
      }
      function F494eef52(&$V6438c669) // score del partido
      {
          if (!preg_match("/^red:(\d+) *blue:(\d+)/", $V6438c669, $Vb74df323))
              return false;
          //change: team|score event
          $tmp = $GLOBALS['skillset']['event']['team|score'];
          if (!(strcmp($this->V93da65a9['gametype'], "xp") == 0 && in_array($this->V42dfa3a4['gametype'], array('4', '5', '6', '7', '8', '9')))) {
              $GLOBALS['skillset']['event']['team|score'] = 0.0;
          }
          $this->Vae2aeb93->F89da123b("1", "team|score", $Vb74df323[1]); // les asigna el score al equipo rojo
          $this->Vae2aeb93->F89da123b("2", "team|score", $Vb74df323[2]); // les asigna el score al equipo azul
          $GLOBALS['skillset']['event']['team|score'] = $tmp;
          //endchange
          if (intval($Vb74df323[1]) > intval($Vb74df323[2])) { // gana el rojo
              $this->Vae2aeb93->F89da123b("1", "team|wins", 1);
              $this->Vae2aeb93->F89da123b("2", "team|loss", 1);
          } elseif (intval($Vb74df323[1]) < intval($Vb74df323[2])) { // gana el azul
              $this->Vae2aeb93->F89da123b("1", "team|loss", 1);
              $this->Vae2aeb93->F89da123b("2", "team|wins", 1);
          }
          return true;
      }
      function Fb19ef501(&$V6438c669) // puntuación del player al finalizar el juego
      {
          if (!preg_match("/^score: (-?\d+) +ping: (\d+) +client: (\d+)/", $V6438c669, $Vb74df323))
              return false;
          $Vca1cd3c3 = $Vb74df323[1]; // score
          $Vdf911f01 = $Vb74df323[2]; // ping
          $V2bfe9d72 = $Vb74df323[3]; // id
          if (isset($this->Va2bbabfe[$V2bfe9d72])) {
              //change: team control
              if (isset($this->Vae2aeb93->players_team)) {
                  $this->Vae2aeb93->F72d01d3f($V2bfe9d72, "score", $Vca1cd3c3, $this->Va2bbabfe);
              } else {
                  $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V2bfe9d72]['id'], "score", $Vca1cd3c3);
              }
              //endchange
              $this->Vae2aeb93->Fec5ab55c("avg", $this->Va2bbabfe[$V2bfe9d72]['id'], "ping", $Vdf911f01);
          }
          return true;
      }
      function Fccd6efdc(&$V6438c669)
      {
          if (preg_match("/^AWARD_FlagRecovery: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Flag_Return', 1);
              return true;
          } elseif (preg_match("/^AWARD_FlagSteal: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Flag_Pickup', 1);
              return true;
          } elseif (preg_match("/^AWARD_CarrierKill: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Kill_Carrier', 1);
              return true;
          } elseif (preg_match("/^AWARD_CarrierDangerProtect: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Defend_Hurt_Carrier', 1);
              return true;
          } elseif (preg_match("/^AWARD_CarrierProtection: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Defend_Carrier', 1);
              return true;
          } elseif (preg_match("/^AWARD_FlagDefense: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Defend_Flag', 1);
              return true;
          } elseif (preg_match("/^AWARD_FlagCarrierKillAssist: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Flag_Assist_Frag', 1);
              return true;
          } elseif (preg_match("/^AWARD_FlagCaptureAssist: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Flag_Assist_Return', 1);
              return true;
          } elseif (preg_match("/^AWARD_FlagCapture: (\d+)/", $V6438c669, $Vb74df323)) {
              $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], 'CTF|Flag_Capture', 1);
              return true;
          }
          return false;
      }
      function F09b9594a(&$V6438c669)
      {
          if (preg_match("/^processStatsGameTypesOSPClanArena_EndGame/", $V6438c669, $Vb74df323)) {
              if (isset($this->V6d2b5d2c['score'])) {
                  foreach ($this->V6d2b5d2c['score'] as $V74aaef3b => $Vca1cd3c3)
                      if (isset($this->Va2bbabfe[$V74aaef3b]))
                          $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V74aaef3b]['id'], "score", $Vca1cd3c3);
              }
              if (isset($this->V6d2b5d2c['team_score']['red']) && isset($this->V6d2b5d2c['team_score']['blue'])) {
                  $this->Vae2aeb93->F89da123b("1", "team|score", $this->V6d2b5d2c['team_score']['red']);
                  $this->Vae2aeb93->F89da123b("2", "team|score", $this->V6d2b5d2c['team_score']['blue']);
                  if (intval($this->V6d2b5d2c['team_score']['red']) > intval($this->V6d2b5d2c['team_score']['blue'])) {
                      $this->Vae2aeb93->F89da123b("1", "team|wins", 1);
                      $this->Vae2aeb93->F89da123b("2", "team|loss", 1);
                  } elseif (intval($this->V6d2b5d2c['team_score']['red']) < intval($this->V6d2b5d2c['team_score']['blue'])) {
                      $this->Vae2aeb93->F89da123b("1", "team|loss", 1);
                      $this->Vae2aeb93->F89da123b("2", "team|wins", 1);
                  }
              }
              return true;
          } elseif (preg_match("/^Warmup:/", $V6438c669, $Vb74df323)) {
              return true;
          } elseif (preg_match("/^red:(\d+) *blue:(\d+)/", $V6438c669, $Vb74df323)) {
              $this->V6d2b5d2c['team_score']['red'] = $Vb74df323[1];
              $this->V6d2b5d2c['team_score']['blue'] = $Vb74df323[2];
              return true;
          } elseif (preg_match("/^score: (-?\d+) +ping: (\d+) +client: (\d+)/", $V6438c669, $Vb74df323)) {
              $V2bfe9d72 = $Vb74df323[3];
              $this->V6d2b5d2c['score'][$V2bfe9d72] = $Vb74df323[1];
              $Vdf911f01 = $Vb74df323[2];
              if (isset($this->Va2bbabfe[$V2bfe9d72])) {
                  $this->Vae2aeb93->Fec5ab55c("avg", $this->Va2bbabfe[$V2bfe9d72]['id'], "ping", $Vdf911f01);
              }
              return true;
          } elseif (preg_match("/^Game_End:/", $V6438c669, $Vb74df323)) {
              $Va6d33173 = "processStatsGameTypesOSPClanArena_EndGame";
              $this->F09b9594a($Va6d33173);
              while (!feof($this->V50dffd37)) {
                  $V40023427 = ftell($this->V50dffd37);
                  $Vb16ebe0a = fgets($this->V50dffd37, cBIG_STRING_LENGTH);
                  $Vb16ebe0a = rtrim($Vb16ebe0a, "\r\n");
                  $this->F1ef9b71a($Vb16ebe0a);
                  if (preg_match("/^ShutdownGame:/", $Vb16ebe0a, $Va9ddcf51)) {
                      $V81c0aba7 = "ShutdownGame:";
                      $this->Fc5aace53($V81c0aba7);
                      return true;
                  } elseif (preg_match("/^InitGame:/", $Vb16ebe0a, $Va9ddcf51)) {
                      fseek($this->V50dffd37, $V40023427);
                      return true;
                  }
              }
              return true;
          } elseif (preg_match("/^ShutdownGame:/", $V6438c669, $Vb74df323)) {
              $Vceb79f03 = ftell($this->V50dffd37);
              while (!feof($this->V50dffd37)) {
                  $Vb16ebe0a = fgets($this->V50dffd37, cBIG_STRING_LENGTH);
                  $V13a33a95 = ftell($this->V50dffd37);
                  $Vb16ebe0a = rtrim($Vb16ebe0a, "\r\n");
                  $this->F1ef9b71a($Vb16ebe0a);
                  if (preg_match("/^InitGame:/", $Vb16ebe0a)) {
                      if (preg_match("/Score_Time\\\EndOfMatch/", $Vb16ebe0a) || preg_match("/Score_Time\\\Round 1\\//", $Vb16ebe0a) || preg_match("/g_gametype\\\[^5]/", $Vb16ebe0a)) {
                          $Va6d33173 = "processStatsGameTypesOSPClanArena_EndGame";
                          $this->F20dd322a($Va6d33173);
                          fseek($this->V50dffd37, $Vceb79f03);
                          return false;
                      } else {
                          fseek($this->V50dffd37, $V13a33a95);
                          return true;
                      }
                  }
              }
              return true;
          }
          return false;
      }
      function Fa5af98c8(&$V6438c669) // si son eventos de ctf o weapon_stats
      {
          if ((stristr($this->V42dfa3a4['mod'], "osp") || stristr($this->V42dfa3a4['gameversion'], "osp")) && $this->V42dfa3a4['gametype'] == '5') {
              if ($this->F09b9594a($V6438c669))
                  return true;
          }
          //change: less code and team control
          $events = array('Flag_Return', 'Flag_Pickup', 'Kill_Carrier', 'Defend_Hurt_Carrier', 'Hurt_Carrier_Defend',
                'Defend_Carrier', 'Defend_Base', 'Defend_Flag', 'Flag_Assist_Frag', 'Flag_Assist_Return', 'Flag_Capture');
          $n = count($events);
          foreach ($events as $event) {
              if (preg_match("/^{$event}: (\d+)/", $V6438c669, $Vb74df323)) {
                  if (isset($this->Vae2aeb93->players_team)) {
                      $this->Vae2aeb93->F72d01d3f($Vb74df323[1], "CTF|{$event}", 1, $this->Va2bbabfe);
                  } else {
                      $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$Vb74df323[1]]['id'], "CTF|{$event}", 1);
                  }
                  return true;
              }
          }
          //endchange
          if (preg_match("/^Weapon_Stats: (\d+) (.*)/", $V6438c669, $Vb74df323)) { // accuracy
              $V2bfe9d72 = $Vb74df323[1];
              $Ve143ea13 = $Vb74df323[2];
              //change: acc control
              if (isset($this->Vae2aeb93->players_team) && @$this->has_acc_stats[$V2bfe9d72]) {
                  return true;
              }
              //endchange
              while (preg_match("/^(.+):(\d+):(\d+)(:\d+:\d+)* /U", $Ve143ea13, $Va9ddcf51)) {
                  $V47239253 = $Va9ddcf51[1];
                  $V97cbf4b6 = '';
                  if (isset($Va9ddcf51[4])) {
                      $V9f892c18 = $Va9ddcf51[2];
                      $Vfce79135 = $Va9ddcf51[3];
                      $V97cbf4b6 = $Va9ddcf51[4];
                  } else {
                      if ($Va9ddcf51[2] > $Va9ddcf51[3]) {
                          $V9f892c18 = $Va9ddcf51[2];
                          $Vfce79135 = $Va9ddcf51[3];
                      } else {
                          $V9f892c18 = $Va9ddcf51[3];
                          $Vfce79135 = $Va9ddcf51[2];
                      }
                  }
                  $Ve143ea13 = substr($Ve143ea13, strlen($Va9ddcf51[0]));
                  if (!strcmp($V47239253, "Gauntlet") || !strcmp($V47239253, "G"))
                      $V47239253 = "GAUNTLET";
                  elseif (!strcmp($V47239253, "MachineGun") || !strcmp($V47239253, "Machinegun") || !strcmp($V47239253, "MG"))
                      $V47239253 = "MACHINEGUN";
                  elseif (!strcmp($V47239253, "Shotgun") || !strcmp($V47239253, "SG"))
                      $V47239253 = "SHOTGUN";
                  elseif (!strcmp($V47239253, "G.Launcher") || !strcmp($V47239253, "GL"))
                      $V47239253 = "GRENADE";
                  elseif (!strcmp($V47239253, "R.Launcher") || !strcmp($V47239253, "RL"))
                      $V47239253 = "ROCKET";
                  elseif (!strcmp($V47239253, "LightningGun") || !strcmp($V47239253, "Lightning") || !strcmp($V47239253, "LG"))
                      $V47239253 = "LIGHTNING";
                  elseif (!strcmp($V47239253, "Railgun") || !strcmp($V47239253, "RG"))
                      $V47239253 = "RAILGUN";
                  elseif (!strcmp($V47239253, "Plasmagun") || !strcmp($V47239253, "PG"))
                      $V47239253 = "PLASMA";
                  //change: acc grapple support
                  elseif (!strcmp($V47239253, "Hook"))
                      $V47239253 = "GRAPPLE";
                  //endchange
                  else
                      $V47239253 = preg_replace("/^MOD_/", "", $V47239253);
                  if ($V9f892c18 > 0) {
                      //change: team control
                      if (isset($this->Vae2aeb93->players_team)) {
                          $this->Vae2aeb93->F4135e567($V2bfe9d72, $V2bfe9d72, "accuracy|{$V47239253}_hits", $Vfce79135, $this->Va2bbabfe);
                          $this->Vae2aeb93->F4135e567($V2bfe9d72, $V2bfe9d72, "accuracy|{$V47239253}_shots", $V9f892c18, $this->Va2bbabfe);
                      } else {
                          $this->Vae2aeb93->F4135e567($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['id'], "accuracy|{$V47239253}_hits", $Vfce79135);
                          $this->Vae2aeb93->F4135e567($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['id'], "accuracy|{$V47239253}_shots", $V9f892c18);
                      }
                      //endchange
                  }
              }
              while (preg_match("/^(.+):(\d+) /U", $Ve143ea13, $Va9ddcf51) || preg_match("/^(.+):(\d+)/", $Ve143ea13, $Va9ddcf51)) {
                  $V980c0787 = $Va9ddcf51[1];
                  $V6cad3a31 = $Va9ddcf51[2];
                  //change: team control
                  if ((!strcmp($V980c0787, "Given") || !strcmp($V980c0787, "DG")) && $V6cad3a31 > 0) {
                      if (isset($this->Vae2aeb93->players_team)) {
                          $this->Vae2aeb93->F72d01d3f($V2bfe9d72, "damage given", $V6cad3a31, $this->Va2bbabfe);
                      } else {
                          $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V2bfe9d72]['id'], "damage given", $V6cad3a31);
                      }
                  }
                  if ((!strcmp($V980c0787, "Recvd") || !strcmp($V980c0787, "DR")) && $V6cad3a31 > 0) {
                      if (isset($this->Vae2aeb93->players_team)) {
                          $this->Vae2aeb93->F72d01d3f($V2bfe9d72, "damage taken", $V6cad3a31, $this->Va2bbabfe);
                      } else {
                          $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V2bfe9d72]['id'], "damage taken", $V6cad3a31);
                      }
                  }
                  if ((!strcmp($V980c0787, "TeamDmg") || !strcmp($V980c0787, "TD")) && $V6cad3a31 > 0) {
                      if (isset($this->Vae2aeb93->players_team)) {
                          $this->Vae2aeb93->F72d01d3f($V2bfe9d72, "damage to team", $V6cad3a31, $this->Va2bbabfe);
                      } else {
                          $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V2bfe9d72]['id'], "damage to team", $V6cad3a31);
                      }
                  }
                  //endchange
                  $Ve143ea13 = substr($Ve143ea13, strlen($Va9ddcf51[0]));
              }
              //change: acc control
              if (isset($this->Vae2aeb93->players_team)) {
                  $this->has_acc_stats[$V2bfe9d72] = true;
              }
              //endchange
              return true;
          }
          return false;
      }
      function Fd1ef0bee(&$V6438c669)
      {
          if (preg_match("/^Round starts/", $V6438c669, $Vb74df323)) {
              return true;
          } elseif (preg_match("/^Exit: Map voting complete/", $V6438c669, $Vb74df323)) {
              Fb7d30ee1("3wave portal game, ignored\n");
              $this->Vae2aeb93->F242ca9da();
              $this->Vdafa753c = false;
              return true;
          } elseif (preg_match("/^Client Connect Using IP Address: (\d+\\.\d+\\.\d+\\.\d+)(:\d+)*(\s+\((.+)\))?/", $V6438c669, $Vb74df323)) {
              $this->V6d2b5d2c['last_scanned_ip'] = $Vb74df323[1];
              if (isset($Vb74df323[4]))
                  $this->V6d2b5d2c['last_scanned_guid'] = $Vb74df323[4];
              return true;
          } elseif (preg_match("/^ClientConnect: (\d+)/", $V6438c669, $Vb74df323)) {
              $V2bfe9d72 = $Vb74df323[1];
              if (isset($this->Va2bbabfe[$V2bfe9d72])) {
                  unset($this->Va2bbabfe[$V2bfe9d72]);
              }
              if (isset($this->V6d2b5d2c['last_scanned_ip']))
                  $this->Va2bbabfe[$V2bfe9d72]['ip'] = $this->V6d2b5d2c['last_scanned_ip'];
              if (isset($this->V6d2b5d2c['last_scanned_guid']))
                  $this->Va2bbabfe[$V2bfe9d72]['guid'] = $this->V6d2b5d2c['last_scanned_guid'];
              return true;
          } elseif (preg_match("/^\^.Stats for (.*)/", $V6438c669, $Vb74df323)) {
              $V4fe04032 = $Vb74df323[1];
              $V2bfe9d72 = $this->F381a6ffc($this->Fa3f5d48d($V4fe04032));
              if (strlen($V2bfe9d72) < 1)
                  return true;
              $this->V6d2b5d2c['client_id_of_last_scanned_stats'] = $V2bfe9d72;
              return true;
          } elseif (isset($this->V6d2b5d2c['client_id_of_last_scanned_stats']) && preg_match("/^\^\d\[WP\](\w+)\s+\^\d\s+\d+\\.\d+ \((\d+)\/(\d+)\)/", $V6438c669, $Vb74df323)) {
              $V47239253 = $Vb74df323[1];
              $Vfce79135 = $Vb74df323[2];
              $V9f892c18 = $Vb74df323[3];
              if (!strcmp($V47239253, "MG"))
                  $V47239253 = "MACHINEGUN";
              elseif (!strcmp($V47239253, "SG"))
                  $V47239253 = "SHOTGUN";
              elseif (!strcmp($V47239253, "GL"))
                  $V47239253 = "GRENADE";
              elseif (!strcmp($V47239253, "RL"))
                  $V47239253 = "ROCKET";
              elseif (!strcmp($V47239253, "LG"))
                  $V47239253 = "LIGHTNING";
              elseif (!strcmp($V47239253, "RG"))
                  $V47239253 = "RAILGUN";
              elseif (!strcmp($V47239253, "PG"))
                  $V47239253 = "PLASMA";
              elseif (!strcmp($V47239253, "NG"))
                  $V47239253 = "NAILGUN";
              $V2bfe9d72 = $this->V6d2b5d2c['client_id_of_last_scanned_stats'];
              if ($V9f892c18 > 0) {
                  $this->Vae2aeb93->F4135e567($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['id'], "accuracy|{$V47239253}_hits", $Vfce79135);
                  $this->Vae2aeb93->F4135e567($this->Va2bbabfe[$V2bfe9d72]['id'], $this->Va2bbabfe[$V2bfe9d72]['id'], "accuracy|{$V47239253}_shots", $V9f892c18);
              }
              return true;
          } elseif (isset($this->V6d2b5d2c['client_id_of_last_scanned_stats']) && preg_match("/^\^\d(D[GT])\s+\^\d\s*(\d+)/", $V6438c669, $Vb74df323)) {
              $V980c0787 = $Vb74df323[1];
              $V6cad3a31 = $Vb74df323[2];
              $V2bfe9d72 = $this->V6d2b5d2c['client_id_of_last_scanned_stats'];
              if (!strcmp($V980c0787, "DG") && $V6cad3a31 > 0) {
                  $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V2bfe9d72]['id'], "damage given", $V6cad3a31);
              } elseif (!strcmp($V980c0787, "DT") && $V6cad3a31 > 0) {
                  $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V2bfe9d72]['id'], "damage taken", $V6cad3a31);
                  unset($this->V6d2b5d2c['client_id_of_last_scanned_stats']);
              }
              return true;
          }
          return false;
      }
      function Ff8e4093b(&$V6438c669)
      {
          if (preg_match("/^ClientDetails: (.*)/", $V6438c669, $Vb74df323)) {
              $Vaf858867 = $Vb74df323[1];
              while (preg_match("/^(.+)\\\(.*)\\\/U", $Vaf858867, $Va9ddcf51) || preg_match("/^(.+)\\\(.*)/", $Vaf858867, $Va9ddcf51)) {
                  $Vf986617c = $Va9ddcf51[1];
                  $Vada01463 = $Va9ddcf51[2];
                  $Vaf858867 = substr($Vaf858867, strlen($Vf986617c) + strlen($Vada01463) + 2);
                  if (!strcmp($Vf986617c, "ip"))
                      $this->V6d2b5d2c['last_scanned_ip'] = $Vada01463;
                  elseif (!strcmp($Vf986617c, "guid"))
                      $this->V6d2b5d2c['last_scanned_guid'] = $Vada01463;
              }
              return true;
          } elseif (preg_match("/^ClientConnect: (\d+)/", $V6438c669, $Vb74df323)) {
              $V2bfe9d72 = $Vb74df323[1];
              if (isset($this->Va2bbabfe[$V2bfe9d72])) {
                  unset($this->Va2bbabfe[$V2bfe9d72]);
              }
              if (isset($this->V6d2b5d2c['last_scanned_ip']))
                  $this->Va2bbabfe[$V2bfe9d72]['ip'] = $this->V6d2b5d2c['last_scanned_ip'];
              if (isset($this->V6d2b5d2c['last_scanned_guid']))
                  $this->Va2bbabfe[$V2bfe9d72]['guid'] = $this->V6d2b5d2c['last_scanned_guid'];
              return true;
          }
          return false;
      }
      function F07bd7443(&$V6438c669) // si es conección de cliente o es chat del cliente (no sé qué relación pueden tener)
      {
          if (preg_match("/^ClientConnect: (\d+) (.*)/", $V6438c669, $Vb74df323)) { // conexión de cliente
              $V2bfe9d72 = $Vb74df323[1]; // id
              $V4fe74676 = $Vb74df323[2]; // variables
              if (isset($this->Va2bbabfe[$V2bfe9d72])) { // si ya existe el cliente
                  unset($this->Va2bbabfe[$V2bfe9d72]);
              }
              while (preg_match("/^\\\(.+)\\\(.*)\\\/U", $V4fe74676, $Va9ddcf51) || preg_match("/^\\\(.+)\\\(.*)/", $V4fe74676, $Va9ddcf51)) { // parsea las variables
                  $Vf986617c = $Va9ddcf51[1];
                  $Vada01463 = $Va9ddcf51[2];
                  $V4fe74676 = substr($V4fe74676, strlen($Vf986617c) + strlen($Vada01463) + 2);
                  if (!strcmp($Vf986617c, "ip")) {
                      $this->Va2bbabfe[$V2bfe9d72]['ip'] =  preg_replace('/\:\d+$/', '', $Vada01463); // ip
                  } elseif (!strcmp($Vf986617c, "guid")) {
                      $this->Va2bbabfe[$V2bfe9d72]['guid'] = $Vada01463; // guid
                  } elseif (!strcmp($Vf986617c, "tld")) {
                      $this->Va2bbabfe[$V2bfe9d72]['tld'] = $Vada01463; // tld
                  } elseif (!strcmp($Vf986617c, "rtld")) {
                      $this->Va2bbabfe[$V2bfe9d72]['rtld'] = $Vada01463; // utld
                  }
              }
              return true;
          } elseif (preg_match("/^say: (.+): /U", $V6438c669, $Vb74df323)) { // si es chat
              $V9650cacb = $Vb74df323[1];
              $Vaa8af3eb = substr($V6438c669, strlen($Vb74df323[0]));
              $Vaa8af3eb = preg_replace("/^&.*\\.wav /i", "", $Vaa8af3eb);
              if (preg_match("/ (\d+)$/U", $Vaa8af3eb, $Va9ddcf51)) {
                  $V2bfe9d72 = $Va9ddcf51[1];
                  $Vaa8af3eb = substr($Vaa8af3eb, 0,  - (strlen($Va9ddcf51[1]) + 1));
              } else {
                  $V2bfe9d72 = $this->F381a6ffc($this->Fa3f5d48d($V9650cacb));
              }
              if (strlen($V2bfe9d72) > 0) {
                  //change: special chars
                  if ($this->V93da65a9['xp_version'] <= 103) {
                      // 1.03 special chars
                      $Vaa8af3eb = preg_replace("/\+([\x01-\x7F])#/e", "chr(ord('\\1') + 127)", $Vaa8af3eb);
                  } else {
                      // 1.04 special chars
                      $Vaa8af3eb = preg_replace("/#(#|[0-9a-f]{2})/ie", "'\\1' == '#' ? '#' : chr(hexdec('\\1'))", $Vaa8af3eb);
                  }
                  $Vaa8af3eb = strtr($Vaa8af3eb, $this->V42dfa3a4['char_trans']);
                  //endchange
                  $this->Vae2aeb93->F8405e6ea($this->Va2bbabfe[$V2bfe9d72]['id'], $this->F7212cda9($Vaa8af3eb));
              }
              return true;
          }
          return false;
      }
      function Fe7c49e90(&$V6438c669) // si descongela (oO, no sabía esto)
      {
          if (preg_match("/^Kill: (\d+) (\d+) \d+: (.*) killed (.*) by MOD_UNKNOWN/", $V6438c669, $Vb74df323) && 
              //change: thaw only for freeze
                  (strcmp($this->V93da65a9['gametype'], "xp") != 0 || $this->V42dfa3a4['gametype'] == '8')) {
              //endchange
              $V79ef036b = $Vb74df323[1];
              $V810f2cfd = $Vb74df323[2];
              if ($V79ef036b > 128) {
                  $V79ef036b = $V810f2cfd;
              }
              //change: team control
              if (isset($this->Va2bbabfe[$V79ef036b]['id']) && isset($this->Va2bbabfe[$V810f2cfd]['id'])) {
                  if (isset($this->Vae2aeb93->players_team)) {
                      $this->Vae2aeb93->F72d01d3f($V79ef036b, "THAW", 1, $this->Va2bbabfe); // lanza el evento THAW
                  } else {
                      $this->Vae2aeb93->F72d01d3f($this->Va2bbabfe[$V79ef036b]['id'], "THAW", 1); // lanza el evento THAW
                  }
              }
              //endchange
              return true;
          }
          return false;
      }
      function F98e2592b(&$V6438c669)
      {
          if (preg_match("/^Warmup:/", $V6438c669, $Vb74df323)) {
              return true;
          } elseif (preg_match("/^Item: \d+ ut_.*/", $V6438c669, $Vb74df323)) {
              $V6438c669 = preg_replace("/(Item: \d+ )ut_/", "\${1}", $V6438c669, 1);
              return false;
          } elseif (preg_match("/^Kill: \d+ \d+ \d+: .* killed .* by UT_MOD_/", $V6438c669, $Vb74df323)) {
              $V6438c669 = preg_replace("/(Kill: \d+ \d+ \d+: .* killed .* by )UT_MOD_/", "\${1}MOD_", $V6438c669, 1);
              return false;
          } elseif (preg_match("/^ClientUserinfo: (\d+) (.*)/", $V6438c669, $Vb74df323)) {
              $V2bfe9d72 = $Vb74df323[1];
              $V4fe74676 = $Vb74df323[2];
              while (preg_match("/^\\\(.+)\\\(.*)\\\/U", $V4fe74676, $Va9ddcf51) || preg_match("/^\\\(.+)\\\(.*)/", $V4fe74676, $Va9ddcf51)) {
                  $Vf986617c = $Va9ddcf51[1];
                  $Vada01463 = $Va9ddcf51[2];
                  $V4fe74676 = substr($V4fe74676, strlen($Vf986617c) + strlen($Vada01463) + 2);
                  if (!strcmp($Vf986617c, "ip")) {
                      $this->Va2bbabfe[$V2bfe9d72]['ip'] = $Vada01463;
                  } elseif (!strcmp($Vf986617c, "cl_guid")) {
                      $this->Va2bbabfe[$V2bfe9d72]['guid'] = $Vada01463;
                  } elseif (!strcmp($Vf986617c, "model")) {
                      if (!isset($this->Va2bbabfe[$V2bfe9d72]['icon']) || strcmp($this->Va2bbabfe[$V2bfe9d72]['icon'], $Vada01463)) {
                          $this->Va2bbabfe[$V2bfe9d72]['icon'] = $Vada01463;
                      }
                  }
              }
              return true;
          }
          return false;
      }
      function Fd624d004(&$V6438c669)
      {
          if (preg_match("/^ClientUserinfoChanged: (\d+) (.*)/", $V6438c669, $Vb74df323)) {
              $Va15379af = $Vb74df323[1];
              $V1dc90e6c = $V6438c669;
              $Vbb897a7c = $this->Va733fe6b;
              $V40023427 = ftell($this->V50dffd37);
              $Vb16ebe0a = fgets($this->V50dffd37, cBIG_STRING_LENGTH);
              $Vb16ebe0a = rtrim($Vb16ebe0a, "\r\n");
              $this->F1ef9b71a($Vb16ebe0a);
              if (preg_match("/^ClientConnect: (\d+).*(\\((\d+\\.\d+\\.\d+\\.\d+).*\\)$)/", $Vb16ebe0a, $Va9ddcf51)) {
                  if ($Va15379af == $Va9ddcf51[1]) {
                      $Va8db2c17 = $Va9ddcf51[1];
                      if (isset($this->Va2bbabfe[$Va8db2c17])) {
                          unset($this->Va2bbabfe[$Va8db2c17]);
                      }
                      if (isset($Va9ddcf51[3])) {
                          $this->Va2bbabfe[$Va8db2c17]['ip'] = $Va9ddcf51[3];
                      }
                      $this->Ff90d84c5($V1dc90e6c);
                      return true;
                  } else {
                      $this->Va733fe6b = $Vbb897a7c;
                      fseek($this->V50dffd37, $V40023427);
                      return false;
                  }
              } else {
                  $this->Va733fe6b = $Vbb897a7c;
                  fseek($this->V50dffd37, $V40023427);
                  return false;
              }
              return false;
          } elseif (preg_match("/^ClientConnect: (\d+).*(\\((\d+\\.\d+\\.\d+\\.\d+).*\\)$)/", $V6438c669, $Vb74df323)) {
              $V2bfe9d72 = $Vb74df323[1];
              if (isset($this->Va2bbabfe[$V2bfe9d72])) {
                  unset($this->Va2bbabfe[$V2bfe9d72]);
              }
              if (isset($Vb74df323[3])) {
                  $this->Va2bbabfe[$V2bfe9d72]['ip'] = $Vb74df323[3];
              }
              return true;
          } elseif (preg_match("/^Kill: \d+ \d+ \d+ \d+: .* killed .* by \w+/", $V6438c669, $Vb74df323)) {
              $V6438c669 = preg_replace("/^(Kill: \d+ \d+ \d+) \d+/", "\${1}", $V6438c669, 1);
              return false;
          } elseif (preg_match("/^say: (\d+) \d+: (.+):/U", $V6438c669, $Vb74df323)) {
              $Vaa8af3eb = substr($V6438c669, strlen($Vb74df323[0]));
              $V2bfe9d72 = $Vb74df323[1];
              if (isset($this->Va2bbabfe[$V2bfe9d72]['id'])) {
                  $this->Vae2aeb93->F8405e6ea($this->Va2bbabfe[$V2bfe9d72]['id'], $this->F7212cda9($Vaa8af3eb));
              }
              return true;
          }
          return false;
      }
      function F26a565c8(&$V6438c669) // si son eventos especiales de cada mod
      {
          if (!strcmp($this->V93da65a9['gametype'], "osp") || !strcmp($this->V93da65a9['gametype'], "cpma")) {
              if ($this->Fa5af98c8($V6438c669))
                  return true;
              elseif ($this->Fe7c49e90($V6438c669))
                  return true;
          } elseif (!strcmp($this->V93da65a9['gametype'], "threewave")) {
              if ($this->Fa5af98c8($V6438c669))
                  return true;
              elseif ($this->Fd1ef0bee($V6438c669))
                  return true;
              else
                  return false;
          } elseif (!strcmp($this->V93da65a9['gametype'], "battle")) {
              if ($this->Fa5af98c8($V6438c669))
                  return true;
              elseif ($this->Ff8e4093b($V6438c669))
                  return true;
              else
                  return false;
          } elseif (!strcmp($this->V93da65a9['gametype'], "freeze")) {
              if ($this->Fa5af98c8($V6438c669))
                  return true;
              elseif ($this->Fe7c49e90($V6438c669))
                  return true;
              else
                  return false;
          } elseif (!strcmp($this->V93da65a9['gametype'], "ut")) {
              return $this->F98e2592b($V6438c669);
          } elseif (!strcmp($this->V93da65a9['gametype'], "ra3")) {
              return $this->Fd624d004($V6438c669);
          } elseif (!strcmp($this->V93da65a9['gametype'], "lrctf")) {
              return $this->Fccd6efdc($V6438c669);
          } elseif (!strcmp($this->V93da65a9['gametype'], "xp")) { // excessiveplus
              if ($this->Fa5af98c8($V6438c669)) // si son eventos de ctf o weapon_stats
                  return true;
              elseif ($this->Fe7c49e90($V6438c669)) // si descongela (oO, no sabía esto)
                  return true;
              elseif ($this->F07bd7443($V6438c669)) // si es conección de cliente o es chat del cliente (no sé qué relación pueden tener)
                  return true;
              //change: clientguid
              elseif ($this->isClientGuid($V6438c669))
                return true;
              //endchange
              else
                  return false;
          }
          return false;
      }
      //change: cambia el guid
      function isClientGuid(&$linea) {
          if (!preg_match("/^ClientGuid: (\d+) (.*)/", $linea, $matches)) {
              return false;
          }
          $client_id = $matches[1];
          $guid = trim($matches[2]);
          if (isset($this->Va2bbabfe[$client_id])) { // si ya existe el cliente
              $this->Va2bbabfe[$client_id]['guid'] = $guid;
          }
          return true;
      }
      //endchange
      function F1ef9b71a(&$V6438c669) // obtiene la fecha de la línea
      {
          if (preg_match("/^\[(\d+[\\:\\.]\d+[\\:\\.]\d+)\] */", $V6438c669, $Va9ddcf51)) {
              $this->Vc3ecd549 = $Va9ddcf51[1];
              $V6438c669 = substr($V6438c669, strlen($Va9ddcf51[0]));
              return true;
          } elseif (preg_match("/^(\d+[\\:\\.]\d+[\\:\\.]\d+) */", $V6438c669, $Va9ddcf51)) {
              $this->Vc3ecd549 = $Va9ddcf51[1];
              $V6438c669 = substr($V6438c669, strlen($Va9ddcf51[0]));
              return true;
          } elseif (preg_match("/^ *(\d+[\\:\\.]\d+) */", $V6438c669, $Vb74df323)) {
              $this->Vc3ecd549 = $Vb74df323[1];
              $V6438c669 = substr($V6438c669, strlen($Vb74df323[0]));
              return true;
          }
          return false;
      }
      function Fa8539cfc(&$V6438c669)
      {
          return false;
      }
      function F20dd322a(&$V6438c669) // parsea la línea
      {
          $this->F1ef9b71a($V6438c669); // obtiene la fecha
          if ($this->F7939839b($V6438c669)) { // es inicio de juego
              echo sprintf("(%05.2f%%) ", 100.0 * ftell($this->V50dffd37) / $this->V42dfa3a4['logfile_size']); // muestra porcentaje de archivo procesado
          } elseif ($this->Vdafa753c) { // si hay juego en progreso
              //change: excessiveplus 1.03 fix
                /*if (!strcmp($this->V93da65a9['gametype'], 'xp')) {
                    $patterns = array(
                        '/^Kill: (\d+) (\d+)/',
                        '/^Item: (\d+)/',
                        '/^say: ?:.* (\d+)\n/',
                        '/^ClientDisconnect: (\d+)/',
                        '/^Weapon_Stats: (\d+)/',
                        '/^Kick: (\d+)/'
                    );
                    foreach ($patterns as $pattern) {
                        if (preg_match($pattern, $V6438c669, $matches)) {
                            array_shift($matches);
                            foreach ($matches as $playerId) {
                                if (isset($this->Va2bbabfe[$playerId]['id'])) {
                                    $id = $this->Va2bbabfe[$playerId]['id'];
                                    if (!isset($this->Vae2aeb93->V75125d17[$id])) {
                                        $dummy = "ClientBegin: ".$playerId;
                                        $this->Ffab4963e($dummy);
                                    }
                                }
                            }
                            break;
                        }
                    }
                }*/
              //endchange
              if ($this->F26a565c8($V6438c669)) { // si son eventos especiales de cada mod
              } elseif ($this->Ff90d84c5($V6438c669)) { // si es cambio de la información del jugador
              } elseif ($this->Fa3c3a4ae($V6438c669)) { // si es obtención de item
              } elseif ($this->F58a1721d($V6438c669)) { // si es notificación de frag
              } elseif ($this->F12520a4b($V6438c669)) { // si es conexión de cliente
              } elseif ($this->Fe460a20b($V6438c669)) { // si es desconexión de cliente
              } elseif ($this->Ffab4963e($V6438c669)) { // si es comienzo de cliente
              } elseif ($this->F3237fc51($V6438c669)) { // si es chat del cliente
              } elseif ($this->Fc5aace53($V6438c669)) { // si es finalización del juego
              } elseif ($this->Fb19ef501($V6438c669)) { // puntuación del cliente al finalizar el juego
              } elseif ($this->F494eef52($V6438c669)) { // cambio de puntuación entre los equipos
              } elseif ($this->F2fe073bc($V6438c669)) { // si es hora del servidor
              } elseif ($this->Fce212e15($V6438c669)) { // si es juego de calentamiento, ignorado
              } elseif ($this->Fa8539cfc($V6438c669)) { // si no es nada oO
              } else {
              }
          } else {
          }
      }
  }
?>
