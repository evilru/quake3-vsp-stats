<?php
  /* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */
  define("cVERSION", "0.45-xp-1.1.2");
  define("cTITLE", /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/" ----------------------------------------------------------------------------- " . "\r\n".
  /*__POBS_EXCLUDE__*/"                     vsp stats processor (c) 2004-2005                         " . "\r\n".
  /*__POBS_EXCLUDE__*/"                               version " . constant("cVERSION") . "                                    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                 vsp by myrddin (myrddin8 AT gmail DOT com)                    " . "\r\n".
  /*__POBS_EXCLUDE__*/" ----------------------------------------------------------------------------- " . "\r\n" . "\r\n");
  define("cUSAGE", /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"  ---------------------------------------------------------------------------  " . "\r\n".
  /*__POBS_EXCLUDE__*/"  Usage: php vsp.php [options] [-p parserOptions] [logFilename]                " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    [options]                                                                  " . "\r\n".
  /*__POBS_EXCLUDE__*/"    ---------                                                                  " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    -c                 specify config file (must be in pub/configs/)           " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    -l                 specify logType (gamecode-gametype)                     " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         logType:-                                             " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           client           Client Logs (Any game)             " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a              Quake 3 Arena (and q3 engine games)" . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-battle       Quake 3 Arena BattleMod            " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-cpma         Quake 3 Arena CPMA (Promode)       " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-freeze       Quake 3 Arena (U)FreezeTag etc.    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-lrctf        Quake 3 Arena Lokis Revenge CTF    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-osp          Quake 3 Arena OSP                  " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-ra3          Quake 3 Arena Rocket Arena 3       " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-threewave    Quake 3 Arena Threewave            " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-ut           Quake 3 Arena UrbanTerror          " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           q3a-xp           Quake 3 Arena Excessive Plus       " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    -n                                                                         " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         No confirmation/prompts (for unattended runs etc.)    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    -a                 specify action                                          " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         perform a specific predefined action                  " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         *make sure this is the last option specified!*        " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         [logFilename] is not needed if this option is used    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         action:-                                              " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           clear_db         Clear the database in config       " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            ie. Reset Stats                    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           gen_awards       Generate only the awards           " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           clear_savestate  Clears the savestate information   " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            for the specified log. If no log   " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            file is specified, then all the    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            savestate information will be      " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            cleared. Currently only works with " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            the q3a gamecode                   " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           pop_ip2country   Deletes the information of the     " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            ip2country table and populates it  " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            from the CSV file specified in the " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            configuration                      " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"                           prune_old_games  Removes all the detailed           " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                            information of old games           " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    -p [parserOptions]                                                         " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"       savestate       1                                                       " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         Enable savestate processing                           " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         Remembers previously scanned logs and events.         " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         If this option is enabled, VSP will remember the      " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         location in the log file where the last stats was     " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         parsed from. So the next time VSP is run with the     " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         savestate 1 option against the same log file, it will " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         start parsing the stats from the previous saved       " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         location.                                             " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         If you want VSP to forget this save state, then you   " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         have to delete the corresponding save state file from " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         the logdata/ folder. The name is in the format        " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         savestate_[special_Form_Of_Logfile_Name]              " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         Deleting that file and running VSP again with         " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         savestate 1 option will reparse the whole log again   " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         from the beginning. Also note that each logfile will  " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         have a separate save state file under the logdata     " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         folder. Do not edit/modify the savestate files! If    " . "\r\n".
  /*__POBS_EXCLUDE__*/"                         you dont want it, just delete it.                     " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"       check ReadME or first few lines of a particular parser php for other    " . "\r\n".
  /*__POBS_EXCLUDE__*/"       valid options for that particular parser                                " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    [logFilename] could be an FTP link/url. Set FTP username/password in config" . "\r\n".
  /*__POBS_EXCLUDE__*/"    [logFilename] may be a logDirectory for some games. ex:- *HalfLife*        " . "\r\n".
  /*__POBS_EXCLUDE__*/"                                                                               " . "\r\n".
  /*__POBS_EXCLUDE__*/"    Usage: php vsp.php [options] [-p parserOptions] [logFilename]              " . "\r\n".
  /*__POBS_EXCLUDE__*/"  Example: php vsp.php -l q3a -p savestate 1 \"c:/quake iii arena/games.log\"    " . "\r\n".
  /*__POBS_EXCLUDE__*/"  ---------------------------------------------------------------------------  " . "\r\n" . "\r\n");

  class F02ac4643
  {
      var $Vf273a653;
      var $V56cacbad = 0;
      var $Vb77eef69 = 0;
      var $V282dbc1d;
      var $V75125d17;
      var $V42dfa3a4;
      function F47fe6c4c($V2e7bf2ef) // devuelve el skill del arma
      {
          if (isset($GLOBALS['skillset']['weapon_factor'][$V2e7bf2ef])) {
              return $GLOBALS['skillset']['weapon_factor'][$V2e7bf2ef];
          }
          return 0.0;
      }
      function F4af5007c($V2da2c443) // devuelve el skill del evento
      {
          if (isset($GLOBALS['skillset']['event'][$V2da2c443])) {
              return $GLOBALS['skillset']['event'][$V2da2c443];
          }
          return 0.0;
      }
      function F428ddac6($V116ad936) // obtiene el skill del player
      {
          global $V9c1ebee8; // db
          $V116ad936 = $V9c1ebee8->qstr($V116ad936);
          $Vac5c74b6 = "select skill from {$GLOBALS['cfg']['db']['table_prefix']}playerprofile where playerID=$V116ad936
 ";
          $V3a2d7564 = $V9c1ebee8->Execute($Vac5c74b6);
          if ($V3a2d7564 and !$V3a2d7564->EOF)
              //change: skill puede bajar de 1600
              //if ($V3a2d7564->fields[0] >= 1600.00) // oO parece que aunque el skill pueda bajar de 1600, para las fórmulas se ocupa 1600 como el mínimo
              //endchange
                return (float) str_replace(',', '.', $V3a2d7564->fields[0]); //change: camel fix
          return $GLOBALS['skillset']['defaults']['value'];
      }
      function Fec5ab55c($V418c5509, $Vafe72417, $V38bb9770, $V7b824acf) // hace más cosas con el player, no entiendo muy bien qué
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          if (!strcmp($V418c5509, "rep")) {
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][0] = $V418c5509;
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1] = $V7b824acf;
          } elseif (!strcmp($V418c5509, "inc")) {
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][0] = $V418c5509;
              if (isset($this->V75125d17[$Vafe72417]['data'][$V38bb9770][1]))
                  $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1] += $V7b824acf;
              else
                  $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1] = $V7b824acf;
          } elseif (!strcmp($V418c5509, "avg")) { // parece cambiar
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][0] = $V418c5509;
              if (isset($this->V75125d17[$Vafe72417]['data'][$V38bb9770][1]))
                  $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1] = round(($V7b824acf + $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1]) / 2.0, 2);
              else
                  $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1] = $V7b824acf;
          } elseif (!strcmp($V418c5509, "sto")) { // parece que hace modificaciones que luego serán guardadas en la base de datos
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][0] = $V418c5509;
              $Vb67d07b7 = count($this->V75125d17[$Vafe72417]['data'][$V38bb9770]);
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][$Vb67d07b7] = $V7b824acf;
          } elseif (!strcmp($V418c5509, "sto_uni")) {
              $this->V75125d17[$Vafe72417]['data'][$V38bb9770][0] = $V418c5509;
              if (!isset($this->V75125d17[$Vafe72417]['data'][$V38bb9770][1])) {
                  $this->V75125d17[$Vafe72417]['data'][$V38bb9770][1] = $V7b824acf;
              } else {
                  $Vb67d07b7 = count($this->V75125d17[$Vafe72417]['data'][$V38bb9770]);

                  unset($this->V75125d17[$Vafe72417]['data'][$V38bb9770][0]);
                  if (array_search($V7b824acf, $this->V75125d17[$Vafe72417]['data'][$V38bb9770]) === false)
                      $this->V75125d17[$Vafe72417]['data'][$V38bb9770][$Vb67d07b7] = $V7b824acf;
                  $this->V75125d17[$Vafe72417]['data'][$V38bb9770][0] = $V418c5509;
              }
          }
      }
      function F95791962($Vafe72417, $V11bc833e)
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          if (isset($this->V75125d17[$V11bc833e])) {
              Fb7d30ee1("PlayerID Conflict Detected\n");
          }
          foreach ($this->V75125d17 as $Vd915074e => $V245742dd) {
              if (!isset($V245742dd['events']))
                  continue;
              foreach ($V245742dd['events'] as $Vf4345940 => $Vc00710ee) {
                  foreach ($Vc00710ee as $Vccefc8b4 => $V7ccb0a11) {
                      foreach ($V7ccb0a11 as $V23488d50 => $Vdb6db230) {
                          if (!isset($Vdb6db230['2D']))
                              continue;
                          foreach ($Vdb6db230['2D'] as $V5fe26767 => $V80cbfddc) {
                              foreach ($V80cbfddc as $V64d90431 => $V68a881e6) {
                                  foreach ($V68a881e6 as $Vd85cbecd => $V27ccee9d) {
                                      if (strcmp($Vd85cbecd, $Vafe72417) == 0) {
                                          $this->V75125d17[$Vd915074e]['events'][$Vf4345940][$Vccefc8b4][$V23488d50]['2D'][$V5fe26767][$V64d90431][$V11bc833e] = $V27ccee9d;
                                          unset($this->V75125d17[$Vd915074e]['events'][$Vf4345940][$Vccefc8b4][$V23488d50]['2D'][$V5fe26767][$V64d90431][$Vd85cbecd]);
                                      }
                                  }
                              }
                          }
                      }
                  }
              }
          }
          $this->V75125d17[$V11bc833e] = $this->V75125d17[$Vafe72417];
          unset($this->V75125d17[$Vafe72417]);
      }
      function Fddcbd60f($Vafe72417, $V44ae8273)
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          $this->V75125d17[$Vafe72417]['profile']['name'] = $V44ae8273;
      }
      function F0a0dc2ec($Vafe72417, $Vbaec6461)
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          $this->V75125d17[$Vafe72417]['vdata']['icon'][0] = "";

          $this->V75125d17[$Vafe72417]['vdata']['icon'][1] = "$Vbaec6461";
      }
      function Fa3f3cadc($Vafe72417, $V29a7e964)
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          $this->V75125d17[$Vafe72417]['vdata']['role'][0] = "";

          $this->V75125d17[$Vafe72417]['vdata']['role'][1] = "$V29a7e964";
      }
      function F555c9055($Vafe72417, $Vf894427c) // actualiza el equipo en el que el player está
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          $this->V75125d17[$Vafe72417]['vdata']['team'][0] = "";

          $this->V75125d17[$Vafe72417]['vdata']['team'][1] = "$Vf894427c";
          if (!isset($this->V282dbc1d[$Vf894427c]))
              $this->V282dbc1d[$Vf894427c] = '1';
      }
      function F52d4d302($Vf894427c)
      {
          if (!isset($this->V282dbc1d[$Vf894427c]))
              $this->V282dbc1d[$Vf894427c] = '1';
      }
      function F7161116f()
      {
          $this->Vb77eef69++;
      }
      function F15999c20()
      {
      }
      function F6d04475a($V2cbf43a2, $Vcb99dc4d)
      {
          if (preg_match("/^_v_/", $V2cbf43a2, $Vb74df323))
              $this->Vf273a653[$V2cbf43a2] = $Vcb99dc4d;
          elseif (isset($GLOBALS['cfg']['data_filter']['gamedata']['']) && preg_match($GLOBALS['cfg']['data_filter']['gamedata'][''], $V2cbf43a2, $Vb74df323))
              return;
          else
              $this->Vf273a653[$V2cbf43a2] = $Vcb99dc4d;
      }
      function F6aae4907($Vafe72417, $V3b043eba, $ip = '', $tld = '') // inicializa datos del jugador
      {
          foreach ($GLOBALS['player_ban_list'] as $V7fa3b767 => $V9539adc5) { // saltar si el player está en la lista de bans
              if (preg_match($V9539adc5, $Vafe72417, $Vb74df323)) {
                  return;
              }
          }
          if (isset($this->V75125d17[$Vafe72417])) { // si el cliente ya existe en el juego
              return;
          }
          $this->V75125d17[$Vafe72417]['v']['original_id'] = $Vafe72417;
          $this->V75125d17[$Vafe72417]['profile']['name'] = "$V3b043eba";
          //change:
          $this->V75125d17[$Vafe72417]['profile']['ip'] = $ip;
          $this->V75125d17[$Vafe72417]['profile']['tld'] = $tld;
          $this->V75125d17[$Vafe72417]['profile']['org_skill'] =
              $this->V75125d17[$Vafe72417]['profile']['skill'] = $this->F428ddac6($Vafe72417); // skill
          //endchange
          $this->V75125d17[$Vafe72417]['profile']['kills'] = 0;
          $this->V75125d17[$Vafe72417]['profile']['deaths'] = 0;
          $this->V75125d17[$Vafe72417]['profile']['kill_streak'] = 0;
          $this->V75125d17[$Vafe72417]['profile']['kill_streak_counter'] = 0;
          $this->V75125d17[$Vafe72417]['profile']['death_streak'] = 0;
          $this->V75125d17[$Vafe72417]['profile']['death_streak_counter'] = 0;
          $this->V75125d17[$Vafe72417]['data'] = array();
          $this->V75125d17[$Vafe72417]['vdata']['team'][0] = "";

          $this->V75125d17[$Vafe72417]['vdata']['team'][1] = "";
          $this->V75125d17[$Vafe72417]['vdata']['role'][0] = "";

          $this->V75125d17[$Vafe72417]['vdata']['role'][1] = "";
          $this->Fec5ab55c("sto", $Vafe72417, "alias", $V3b043eba);
      }
      function F8405e6ea($Vafe72417, $V7a674c32)
      {
          if (!isset($this->V75125d17[$Vafe72417])) {
              return;
          }
          if (preg_match("/\d/", $V7a674c32) || preg_match("/@/", $V7a674c32)) {
              return;
          }
          $this->V75125d17[$Vafe72417]['vdata']['quote'][0] = "rep";
          if (!isset($this->V75125d17[$Vafe72417]['vdata']['quote'][1]))
              $this->V75125d17[$Vafe72417]['vdata']['quote'][1] = "$V7a674c32";
          elseif (strlen($this->V75125d17[$Vafe72417]['vdata']['quote'][1]) < 5)
              $this->V75125d17[$Vafe72417]['vdata']['quote'][1] = "$V7a674c32";
          elseif (strlen($V7a674c32) > 25)
              $this->V75125d17[$Vafe72417]['vdata']['quote'][1] = "$V7a674c32";
          elseif (mt_rand(1, 10) <= 5)
              $this->V75125d17[$Vafe72417]['vdata']['quote'][1] = "$V7a674c32";
      }
      function Fd45b6912() // muestra mensaje de inicio de análisis de juego
      {
          $this->F242ca9da(); // limpieza de variables

          $this->V56cacbad++;
          echo "Analyzing game " . sprintf("%04d ", $this->V56cacbad);
          Fa10803e1();
          $this->Vb77eef69 = 0;
      }
      function Fc3b570a7() // actualiza los streaks de los players
      {
          if (isset($this->V75125d17)) {
              foreach ($this->V75125d17 as $Vafe72417 => $V910d9037) {
                  if ($this->V75125d17[$Vafe72417]['profile']['death_streak_counter'] > $this->V75125d17[$Vafe72417]['profile']['death_streak'])
                      $this->V75125d17[$Vafe72417]['profile']['death_streak'] = $this->V75125d17[$Vafe72417]['profile']['death_streak_counter'];
                  if ($this->V75125d17[$Vafe72417]['profile']['kill_streak_counter'] > $this->V75125d17[$Vafe72417]['profile']['kill_streak'])
                      $this->V75125d17[$Vafe72417]['profile']['kill_streak'] = $this->V75125d17[$Vafe72417]['profile']['kill_streak_counter'];
              }
          }
      }
      function Fb03ee647()
      {
          foreach ($this->V75125d17 as $Vafe72417 => $V910d9037) {
              if (isset($this->V75125d17[$Vafe72417]['events'])) {
                  unset($this->V75125d17[$Vafe72417]['events']);
              }
              //change:
              $this->V75125d17[$Vafe72417]['profile']['org_skill'] =
                  $this->V75125d17[$Vafe72417]['profile']['skill'] = $this->F428ddac6($Vafe72417);
              //endchange
              $this->V75125d17[$Vafe72417]['profile']['kills'] = 0;
              $this->V75125d17[$Vafe72417]['profile']['deaths'] = 0;
              $this->V75125d17[$Vafe72417]['profile']['kill_streak'] = 0;
              $this->V75125d17[$Vafe72417]['profile']['kill_streak_counter'] = 0;
              $this->V75125d17[$Vafe72417]['profile']['death_streak'] = 0;
              $this->V75125d17[$Vafe72417]['profile']['death_streak_counter'] = 0;
              $this->V75125d17[$Vafe72417]['data'] = array();
          }
      }
      function F242ca9da() // limpieza de variables
      {
          if (isset($this->V75125d17))
              unset($this->V75125d17);
          if (isset($this->Vf273a653))
              unset($this->Vf273a653);
          if (isset($this->V282dbc1d))
              unset($this->V282dbc1d);
          if (isset($this->V42dfa3a4))
              unset($this->V42dfa3a4);
      }
      function F26dd5333() // devuelve la información de los jugadores
      {
          if (isset($this->V75125d17))
              return($this->V75125d17);
          return false;
      }
      function F068fac4f() // devuelve la información del juego
      {
          if (isset($this->Vf273a653))
              return($this->Vf273a653);
          return false;
      }
      //change: ELO based skill change
      function F89da123b($Vf894427c, $V2da2c443, $V6ae4aaa3) // team events launcher
      {
          $V10dad7cb = '';
          $Vf1a19314 = $V2da2c443;
          if (preg_match("/^(.*)\\|(.+)/", $Vf1a19314, $Vb74df323)) {
              $V10dad7cb = $Vb74df323[1];
              $Vf1a19314 = $Vb74df323[2];
          }
          if (isset($GLOBALS['cfg']['data_filter']['events'][$V10dad7cb]) && preg_match($GLOBALS['cfg']['data_filter']['events'][$V10dad7cb], $Vf1a19314, $Vb74df323))
              return;
          if (!$this->V75125d17)
              return;

          $variance = $GLOBALS['skillset']['defaults']['variance'];
          $players = array('add' => array(), 'substract' => array());
          $skills = array('add' => 0.0, 'substract' => 0.0);
          foreach ($this->V75125d17 as $Vd915074e => $V163b0d74) { // itera sobre todos los players
              $V29a7e964 = $this->V75125d17[$Vd915074e]['vdata']['role'][1];
              $band = false;
              if ($band = isset($this->V75125d17[$Vd915074e]['events'][$this->Vb77eef69][$Vf894427c])) { // es del equipo
                  // agrega el evento
                  if (!isset($this->V75125d17[$Vd915074e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['1D'][$V2da2c443]))
                      $this->V75125d17[$Vd915074e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['1D'][$V2da2c443] = 0;
                  $this->V75125d17[$Vd915074e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['1D'][$V2da2c443] += $V6ae4aaa3;
                  // agrega el player a los add
                  $players['add'][] = $Vd915074e;
                  $skills['add'] += $this->V75125d17[$Vd915074e]['profile']['org_skill'];
              }
              if (!$band || (isset($this->V75125d17[$Vd915074e]['events'][$this->Vb77eef69]) && count($this->V75125d17[$Vd915074e]['events'][$this->Vb77eef69]) > 1)) {
                  // agrega el player a los substract
                  $players['substract'][] = $Vd915074e;
                  $skills['substract'] += $this->V75125d17[$Vd915074e]['profile']['org_skill'];
              }
          }
          $n = array('add' => count($players['add']), 'substract' => count($players['substract']));
          $event_factor = $this->F4af5007c($V2da2c443);
          if ($event_factor && $n['add'] && $n['substract']) {
              $max_n = max($n['add'], $n['substract']);
              $av_skills = array('substract' => $skills['substract'] / $max_n, 'add' => $skills['add'] / $max_n);
              $prob_add_wins = 1 / (1 + exp(($av_skills['substract'] - $av_skills['add']) * ($event_factor > 0 ? 1 : -1) / ($variance * $max_n)));
              $factor = (1 - $prob_add_wins) * $V6ae4aaa3 * $event_factor * min($n['add'], $n['substract']);
              $prob_array = array('add' => array(), 'substract' => array());
              $prob_sum = array('add' => 0.0, 'substract' => 0.0);
              foreach ($players as $type => $player_list) {
                  $enemy_av_skill = $type == 'add' ? $av_skills['substract'] : $av_skills['add'];
                  foreach ($player_list as $index => $id) {
                      $skill = $this->V75125d17[$id]['profile']['org_skill'];
                      $prob_win = 1 / (1 + exp(($enemy_av_skill - $skill) * ($event_factor > 0 ? 1 : -1) / $variance));
                      $prob_array[$type][$index] = $type == 'add' ? 1 - $prob_win : $prob_win;
                      $prob_sum[$type] += $prob_array[$type][$index];
                  }
              }
              foreach ($players as $type => $player_list) {
                  $negative = $type == 'add' ? 1.0 : -1.0;
                  foreach ($player_list as $index => $id) {
                      $player_team_factor = $prob_array[$type][$index] / $prob_sum[$type];
                      $this->V75125d17[$id]['profile']['skill'] += $negative * $factor * $player_team_factor;
                  }
              }
          }
      }
      //endchange
      //change: ELO based skills change
      function F72d01d3f($Vafe72417, $V2da2c443, $V6ae4aaa3, &$clients_info = false) // default events launcher
      {
          //change: team control
          if ($clients_info) {
              $client_id = $Vafe72417;
              $Vafe72417 = $clients_info[$client_id]['id'];
          }
          //endchange
          if (!isset($this->V75125d17[$Vafe72417]))
              return;
          $V10dad7cb = '';
          $Vf1a19314 = $V2da2c443;
          if (preg_match("/^(.*)\\|(.+)/", $Vf1a19314, $Vb74df323)) {
              $V10dad7cb = $Vb74df323[1];
              $Vf1a19314 = $Vb74df323[2];
          }
          if (isset($GLOBALS['cfg']['data_filter']['events'][$V10dad7cb]) && preg_match($GLOBALS['cfg']['data_filter']['events'][$V10dad7cb], $Vf1a19314, $Vb74df323))
              return;
          //change: team control
          $Vf894427c = $clients_info ? $this->players_team[$client_id]['team'] : $this->V75125d17[$Vafe72417]['vdata']['team'][1]; // team
          //endchange
          $V29a7e964 = $this->V75125d17[$Vafe72417]['vdata']['role'][1];
          if (!isset($this->V75125d17[$Vafe72417]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['1D'][$V2da2c443]))
              $this->V75125d17[$Vafe72417]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['1D'][$V2da2c443] = 0;
          $this->V75125d17[$Vafe72417]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['1D'][$V2da2c443] += $V6ae4aaa3;

          $this->event_skills_update($clients_info ? $client_id : $Vafe72417, $V2da2c443, $V6ae4aaa3, $clients_info);
      }
      //endchange
      function F4135e567($Vafe72417, $V863818d8, $V2da2c443, $V6ae4aaa3, &$clients_info = false) // accuracy events
      {
          //change: client ids instead of ids
          if ($clients_info) {
              $first_id = $Vafe72417;
              $Vafe72417 = $clients_info[$first_id]['id'];
              $second_id = $V863818d8;
              $V863818d8 = $clients_info[$second_id]['id'];
          }
          //endchange
          if (!isset($this->V75125d17[$Vafe72417]) || !isset($this->V75125d17[$V863818d8])) {
              return;
          }
          //change: team control
          $Vf894427c = $clients_info ? $this->players_team[$first_id]['team'] : $this->V75125d17[$Vafe72417]['vdata']['team'][1];
          $V29a7e964 = $this->V75125d17[$Vafe72417]['vdata']['role'][1];
          $V60962ab1 = $clients_info ? $this->players_team[$second_id]['team'] : $this->V75125d17[$V863818d8]['vdata']['team'][1];
          $V84ccdc56 = $this->V75125d17[$V863818d8]['vdata']['role'][1];
          //endchange
          if (isset($this->V75125d17[$Vafe72417]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V863818d8][$V2da2c443])) {
              $this->V75125d17[$Vafe72417]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V863818d8][$V2da2c443] += $V6ae4aaa3;
          } else {
              $this->V75125d17[$Vafe72417]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V863818d8][$V2da2c443] = $V6ae4aaa3;
          }
          //$this->V75125d17[$Vafe72417]['profile']['skill'] += $this->F4af5007c($V2da2c443);
          //change: launch event
          if ($clients_info) {
              $Vafe72417 = $first_id;
              $V863818d8 = $second_id;
          }
          if ($Vafe72417 == $V863818d8) {
              $this->event_skills_update($Vafe72417, $V2da2c443, $V6ae4aaa3, $clients_info, false);
          } else {
              //TODO: specific player accuracy
          }
          //endchange

      }
      function Fd65f3244($V4b8cff0e, $V6426a622, $V2e7bf2ef, &$clients_info = false)  // kill events
      {
          //change: skill configuration
          $variance = $GLOBALS['skillset']['defaults']['variance'];
          //endchange
          //change: team support
          if ($clients_info) {
              $killer_id = $V4b8cff0e;
              $V4b8cff0e = $clients_info[$killer_id]['id'];
              $victim_id = $V6426a622;
              $V6426a622 = $clients_info[$victim_id]['id'];
          }
          //endchange
          if (!isset($this->V75125d17[$V4b8cff0e]) || !isset($this->V75125d17[$V6426a622])) {
              return;
          }
          
          //change: team control
          $Vf894427c = $clients_info ? $this->players_team[$killer_id]['team'] : $this->V75125d17[$V4b8cff0e]['vdata']['team'][1];
          $V29a7e964 = $this->V75125d17[$V4b8cff0e]['vdata']['role'][1];
          $V60962ab1 = $clients_info ? $this->players_team[$victim_id]['team'] : $this->V75125d17[$V6426a622]['vdata']['team'][1];
          $V84ccdc56 = $this->V75125d17[$V6426a622]['vdata']['role'][1];
          //endchange

          if ($clients_info ? $killer_id != $victim_id : strcmp($V4b8cff0e, $V6426a622) != 0) { // si no es suicidio
              //change: team control
              if ((count($this->V282dbc1d) > 1 && strcmp($Vf894427c, $V60962ab1) == 0)) { // teamkill
                  if (!isset($this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["teamkill|$V2e7bf2ef"]))
                      $this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["teamkill|$V2e7bf2ef"] = 0;
                  $this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["teamkill|$V2e7bf2ef"]++;
                  //$Ve97b3886 = $this->V75125d17[$V4b8cff0e]['profile']['skill'] * $GLOBALS['skillset']['fraction']['value'];
                  //$this->V75125d17[$V4b8cff0e]['profile']['skill']-=$Ve97b3886;
                  //change: launch event
                  $this->event_skills_update($clients_info ? $killer_id : $V4b8cff0e, "teamkill|$V2e7bf2ef", 1, $clients_info);
                  //endchange
              } else {
                  //change: ELO system
                  $event_factor = $this->F47fe6c4c($V2e7bf2ef);
                  $killer_skill = $this->V75125d17[$V4b8cff0e]['profile']['skill'];
                  $victim_skill = $this->V75125d17[$V6426a622]['profile']['skill'];
                  $prob_killer_wins = 1 / (1 + exp(($victim_skill - $killer_skill) * ($event_factor > 0 ? 1 : -1) / $variance));
                  //$Ve7bf558f = $this->V75125d17[$V6426a622]['profile']['skill'] * $GLOBALS['skillset']['fraction']['value'] ;
                  $Ve7bf558f = 1 - $prob_killer_wins;
                  //endchange
                  if (!isset($this->V42dfa3a4['first killer'])) { // first killer
                      //change: team control
                      $this->F72d01d3f($clients_info ? $killer_id : $V4b8cff0e, "first killer", 1, $clients_info);
                      $this->F72d01d3f($clients_info ? $victim_id : $V6426a622, "first victim", 1, $clients_info);
                      //endchange
                      $this->V42dfa3a4['first killer'] = 1;
                  }
                  if (!isset($this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["kill|$V2e7bf2ef"]))
                      $this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["kill|$V2e7bf2ef"] = 0;
                  $this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["kill|$V2e7bf2ef"]++;
                  $this->V75125d17[$V4b8cff0e]['profile']['kills']++;
                  $this->V75125d17[$V4b8cff0e]['profile']['kill_streak_counter']++;
                  if ($this->V75125d17[$V4b8cff0e]['profile']['death_streak_counter'] > $this->V75125d17[$V4b8cff0e]['profile']['death_streak'])
                      $this->V75125d17[$V4b8cff0e]['profile']['death_streak'] = $this->V75125d17[$V4b8cff0e]['profile']['death_streak_counter'];
                  $this->V75125d17[$V4b8cff0e]['profile']['death_streak_counter'] = 0;
                  $this->V75125d17[$V4b8cff0e]['profile']['skill'] += $event_factor * $Ve7bf558f; // aumenta el skill del killer
                  $this->V75125d17[$V6426a622]['profile']['skill'] -= $event_factor * $Ve7bf558f; // disminuye el skill del fragged
              }
          } else {
              if (!isset($this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["suicide|$V2e7bf2ef"]))
                  $this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["suicide|$V2e7bf2ef"] = 0;
              $this->V75125d17[$V4b8cff0e]['events'][$this->Vb77eef69][$Vf894427c][$V29a7e964]['2D'][$V60962ab1][$V84ccdc56][$V6426a622]["suicide|$V2e7bf2ef"]++;

              //$this->V75125d17[$V6426a622]['profile']['skill'] -= $Ve7bf558f;
              //change: launch event
              $this->event_skills_update($clients_info ? $killer_id : $V4b8cff0e, "suicide|$V2e7bf2ef", 1, $clients_info);
              //endchange
          }
          $this->V75125d17[$V6426a622]['profile']['deaths']++;
          $this->V75125d17[$V6426a622]['profile']['death_streak_counter']++;
          if ($this->V75125d17[$V6426a622]['profile']['kill_streak_counter'] > $this->V75125d17[$V6426a622]['profile']['kill_streak'])
              $this->V75125d17[$V6426a622]['profile']['kill_streak'] = $this->V75125d17[$V6426a622]['profile']['kill_streak_counter'];
          $this->V75125d17[$V6426a622]['profile']['kill_streak_counter'] = 0;
      }
      //change: add event
      function event_skills_update($Vafe72417, $V2da2c443, $V6ae4aaa3, &$clients_info = false, $team_penalty = true) {
          ////change: team control
          if ($clients_info) {
              $client_id = $Vafe72417;
              $Vafe72417 = $clients_info[$client_id]['id'];
          }
          //endchange
          $Vf894427c = $clients_info ? $this->players_team[$client_id]['team'] : $this->V75125d17[$Vafe72417]['vdata']['team'][1]; // team
          $event_factor = $this->F4af5007c($V2da2c443);
          if (!$event_factor) {
              return;
          }
          $player_skill = $this->V75125d17[$Vafe72417]['profile']['skill'];
          $variance = $GLOBALS['skillset']['defaults']['variance'];
          $players = array();
          $skills = 0.0;
          $teamplayers = 0;
          if ($clients_info) {
              foreach ($this->players_team as $cl_id => $arr) {
                  if ($arr['connected'] && isset($clients_info[$cl_id])) {
                      if ($arr['team'] == $Vf894427c) { // mismo equipo
                          $teamplayers++;
                      } else { // equipo contrario
                          $id = $clients_info[$cl_id]['id'];
                          if (!isset($this->V75125d17[$id])) continue;
                          $players[] = $id;
                          $skills += $this->V75125d17[$id]['profile']['skill'];
                      }
                  }
              }
          } else {
              foreach ($this->V75125d17 as $Vd915074e => $V163b0d74) { // itera sobre todos los players
                  if ($this->V75125d17[$Vd915074e]['vdata']['team'][1] == $Vf894427c) { // mismo equipo
                      $teamplayers++;
                  } else {  // equipo contrario
                      // agrega el player a la lista
                      $players[] = $Vd915074e;
                      $skills += $this->V75125d17[$Vd915074e]['profile']['skill'];
                  }
              }
          }
          $n = count($players);
          if ($n && $teamplayers) {
              $av_skills = $skills / $n;
              $prob_wins = 1 / (1 + exp(($av_skills - $player_skill) * ($event_factor > 0 ? 1 : -1) / $variance));
              $factor = (1 - $prob_wins) * $V6ae4aaa3 * $event_factor;
              if ($team_penalty) {
                  $team_factor = $event_factor > 0 ? $n / $teamplayers : $teamplayers / $n;
                  $factor *= $team_factor > 1 ? 1 : $team_factor;
              }
              $prob_array = array();
              $prob_sum = 0.0;
              foreach ($players as $id) {
                  $skill = $this->V75125d17[$id]['profile']['skill'];
                  $prob_array[$id] = 1 / (1 + exp(($player_skill - $skill) * ($event_factor > 0 ? 1 : -1) / $variance));
                  $prob_sum += $prob_array[$id];
              }
              foreach ($players as $id) {
                  $player_team_factor = $prob_array[$id] / $prob_sum;
                  $this->V75125d17[$id]['profile']['skill'] -= $factor * $player_team_factor;
              }
              $this->V75125d17[$Vafe72417]['profile']['skill'] += $factor; // cambia el skill
          }
      }
      //endchange
      //change: launch skill events
      function launch_skill_events() {
          if (isset($this->V75125d17)) {
              foreach ($this->V75125d17 as $Vafe72417 => $V910d9037) {
                  if (isset($this->V75125d17[$Vafe72417]['profile']['org_skill'])) {
                      $variation = $this->V75125d17[$Vafe72417]['profile']['skill'] - $this->V75125d17[$Vafe72417]['profile']['org_skill'];
                      $this->F72d01d3f($Vafe72417, 'skill|begins', round($this->V75125d17[$Vafe72417]['profile']['org_skill'], 2));
                      $this->F72d01d3f($Vafe72417, 'skill|'.($variation > 0 ? 'wins' : 'loses'), round($variation, 2));
                      $this->F72d01d3f($Vafe72417, 'skill|ends', round($this->V75125d17[$Vafe72417]['profile']['skill'], 2));
                  }
              }
          }
      }
      //endchange
  }
  class F622a322a
  {
      var $games_parsed = 0;
      var $games_inserted = 0;
      var $V75125d17;
      var $V7da699e4;
      var $Vf273a653;
      function F622a322a()
      {
      }
      function F61ee4b91()
      {
          foreach ($this->V75125d17 as $Vafe72417 => $V910d9037) {
              foreach ($V910d9037 as $V17f71d96 => $Vf57346d3) {
                  if (!strcmp($V17f71d96, 'events')) {
                      foreach ($Vf57346d3 as $V9bbd993d => $V61b837be) {
                          foreach ($V61b837be as $Vf894427c => $Vf60229f3) {
                              foreach ($Vf60229f3 as $V29a7e964 => $V91812ae6) {
                                  foreach ($V91812ae6 as $V1cd03614 => $V9290beca) {
                                      $V0ba4439e = 0;
                                      if (sizeof($V91812ae6['1D']) <= 1 && $this->V75125d17[$Vafe72417]['profile']['deaths'] == 0) {
                                          $V0ba4439e = 1;
                                          if (array_key_exists('2D', $V91812ae6)) {
                                              $V0ba4439e = 0;
                                          }
                                      }
                                      if ($V0ba4439e == 1) {
                                          unset($this->V75125d17[$Vafe72417]['events'][$V9bbd993d][$Vf894427c][$V29a7e964]);
                                      }
                                  }
                              }
                          }
                      }
                  }
              }
          }
      }
      function Fefea820f()
      {
          global $V9c1ebee8;
          $Vac5c74b6 = "select count(*) from {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d";
          $V3a2d7564 = $V9c1ebee8->Execute($Vac5c74b6);
          if ($V3a2d7564 && $V3a2d7564->fields[0] > 10000) {
              $V9ab2ec7e["{$GLOBALS['cfg']['db']['table_prefix']}" . 'eventdata1d'] = 1;
              $V9ab2ec7e["{$GLOBALS['cfg']['db']['table_prefix']}" . 'eventdata2d'] = 1;
              foreach ($V9ab2ec7e as $V4b27b6e5 => $V3a6d0284) {
                  echo "purifyDb: checking for probable bad entries from $V4b27b6e5\n";
                  $Vac5c74b6 = "select eventCategory, eventName, count(*) as c from $V4b27b6e5 group by eventCategory,eventName having c<3";

                  $V3a2d7564 = $V9c1ebee8->Execute($Vac5c74b6);
                  if ($V3a2d7564 && !$V3a2d7564->EOF) {
                      echo "purifyDb: removing probable bad entries from $V4b27b6e5\n";
                      do {
                          $V63d2929c = "delete from $V4b27b6e5 where eventCategory=" . $V9c1ebee8->qstr($V3a2d7564->fields[0]) . " AND eventName=" . $V9c1ebee8->qstr($V3a2d7564->fields[1]);
                          $V4fc2f671 = $V9c1ebee8->Execute($V63d2929c);
                          if ($V4fc2f671)
                              echo "purifyDb: removed: category-{$V3a2d7564->fields[0]}, name-{$V3a2d7564->fields[1]}\n";
                      } while ($V3a2d7564->MoveNext() && !$V3a2d7564->EOF);
                  }
                  echo "purifyDb: done\n";
              }
          }
      }
      function F215f9169() // genera los awards
      {
          $tp = $GLOBALS['cfg']['db']['table_prefix'];
          global $V9c1ebee8;
          //change: add player_exclude_list support
          foreach ($GLOBALS['player_exclude_list'] as $key => $value) {
              $GLOBALS['player_exclude_list'][$key] = $V9c1ebee8->qstr($value);
          }
          //endchange
          //change: exclude inactive players
          $last_update = false;
          if ( $GLOBALS['cfg']['display']['days_inactivity'] ) {
              // use timestamp from latest game
              $sql = "select max(timeStart) from {$GLOBALS['cfg']['db']['table_prefix']}gameprofile";
              $rs = $V9c1ebee8->execute( $sql );
              if ( $rs && !$rs->EOF ) {
                  $last_update = "'{$rs->fields[0]}'";
              } else {
                  // use timestamp from latest update
                  $sql = "select value from {$GLOBALS['cfg']['db']['table_prefix']}gamedata
                    where name='last_update_time'";
                  $rs = $V9c1ebee8->execute( $sql );
                  if ($rs && !$rs->EOF) {
                    $last_update = "'{$rs->fields[0]}'";
                  } else {
                      // use current timestamp
                    $last_update = 'CURRENT_TIMESTAMP';
                  }
              }
          }
          //endchange
          include_once("pub/games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$GLOBALS['cfg']['awardset']}-awards.php");
          @include_once("pub/games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$GLOBALS['cfg']['weaponset']}-weapons.php");
          echo "\ngenerateAwards: Generating Awards...";
          Fa10803e1();
          if (!isset($GLOBALS['awardset'])) {
              echo "Award Definitions not found.\n";
              echo " " . "pub/games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$GLOBALS['cfg']['awardset']}-awards.php\n";
              return;
          }
          $awardset_expanded = array();
          $Vac5c74b6 = "select distinct eventName from {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d where eventCategory='kill' order by eventName";

          $V3a2d7564 = $V9c1ebee8->Execute($Vac5c74b6);
          foreach ($GLOBALS['awardset'] as $Vee670b78 => $V853346d3) {
              if (strstr($Vee670b78, '_v_weapons')) {
                  if ($V3a2d7564) {
                      $V3a2d7564->MoveFirst();
                      do {
                          $V15b259db = preg_replace("/_v_weapons/", $V3a2d7564->fields[0], $Vee670b78);

                          if (isset($GLOBALS['awardset'][$Vee670b78]['name'])) {
                              if (isset($weaponset[$V3a2d7564->fields[0]]['name']))
                                  $awardset_expanded[$V15b259db]['name'] = preg_replace("/_v_weapons/", $weaponset[$V3a2d7564->fields[0]]['name'], $GLOBALS['awardset'][$Vee670b78]['name']);
                              else
                                  $awardset_expanded[$V15b259db]['name'] = preg_replace("/_v_weapons/", ucfirst(strtolower(str_replace("_", " ", $V3a2d7564->fields[0]))), $GLOBALS['awardset'][$Vee670b78]['name']);
                          }
                          if (isset($GLOBALS['awardset'][$Vee670b78]['image']))
                              $awardset_expanded[$V15b259db]['image'] = preg_replace("/_v_weapons/", $V3a2d7564->fields[0], $GLOBALS['awardset'][$Vee670b78]['image']);
                          if (isset($GLOBALS['awardset'][$Vee670b78]['category']))
                              $awardset_expanded[$V15b259db]['category'] = preg_replace("/_v_weapons/", $V3a2d7564->fields[0], $GLOBALS['awardset'][$Vee670b78]['category']);
                          foreach ($GLOBALS['awardset'][$Vee670b78]['sql'] as $Va76c847d => $V111b0e36) {
                              $awardset_expanded[$V15b259db]['sql'][$Va76c847d] = preg_replace("/_v_weapons/", $V3a2d7564->fields[0], $GLOBALS['awardset'][$Vee670b78]['sql'][$Va76c847d]);
                          }
                      } while ($V3a2d7564->MoveNext() && !$V3a2d7564->EOF);
                  }
              } else {
                  $awardset_expanded[$Vee670b78] = $GLOBALS['awardset'][$Vee670b78];
              }
          }
          $Vac5c74b6 = "DELETE from {$GLOBALS['cfg']['db']['table_prefix']}awards where 1";
          $V9c1ebee8->Execute($Vac5c74b6);
          foreach ($awardset_expanded as $Vee670b78 => $V853346d3) {
              foreach ($awardset_expanded[$Vee670b78]['sql'] as $Vf5c8a086 => $Vac5c74b6) {
                  $Vac5c74b6 = preg_replace("/awardset/", "awardset_expanded", $Vac5c74b6);
                  eval("\$Vac5c74b6=\"$Vac5c74b6\";");
                  $awardset_expanded[$Vee670b78]['sql_final'] = preg_replace("/\s+/", " ", $Vac5c74b6);
                  $V3a2d7564 = $V9c1ebee8->Execute($Vac5c74b6);

                  $awardset_expanded[$Vee670b78]['sql'][$Vf5c8a086] = @$V3a2d7564->fields;
                  $awardset_expanded[$Vee670b78]['result'] = $awardset_expanded[$Vee670b78]['sql'][$Vf5c8a086][0];
              }
              if (isset($awardset_expanded[$Vee670b78]['name']) &&
                      //change: do not insert accuracy with empty resultset
                      $awardset_expanded[$Vee670b78]['result'] !== null) {
                      //endchange
                  if (!isset($awardset_expanded[$Vee670b78]['category']))
                      $awardset_expanded[$Vee670b78]['category'] = '';
                  $Vac5c74b6 = "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}awards
 set `sql`='',name='',awardID=" . $V9c1ebee8->qstr($Vee670b78) . "";
                  $V9c1ebee8->Execute($Vac5c74b6);
                  $Vac5c74b6 = "UPDATE {$GLOBALS['cfg']['db']['table_prefix']}awards
 set name=" . $V9c1ebee8->qstr($awardset_expanded[$Vee670b78]['name']) . " ,category=" . $V9c1ebee8->qstr($awardset_expanded[$Vee670b78]['category']) . "
 ,image=" . $V9c1ebee8->qstr($awardset_expanded[$Vee670b78]['image']) . " ,playerID=" . $V9c1ebee8->qstr($awardset_expanded[$Vee670b78]['result']) . "
 ,`sql`=" . $V9c1ebee8->qstr($awardset_expanded[$Vee670b78]['sql_final']) . " where awardID=" . $V9c1ebee8->qstr($Vee670b78) . "";

                  $V9c1ebee8->Execute($Vac5c74b6);
              }
          }
          echo "done\n";
          Fa10803e1();
      }
//change: remove detailed information of old games
      function prune_old_games() {
          global $V9c1ebee8; // db
          // check if we are limiting games
          if ($GLOBALS['cfg']['games_limit'] < 0) {
              return;
          }
          // check if games are actually over the limit
          $sql = "SELECT COUNT(*) FROM {$GLOBALS['cfg']['db']['table_prefix']}gamedata
            WHERE name = '_v_time_start'";
          $rs = $V9c1ebee8->Execute($sql);
          if ($rs->fields[0] <= $GLOBALS['cfg']['games_limit']) {
              return;
          }
          // get games
          print "\npruneOldGames: prunning old games...";
          Fa10803e1();
          $tables = array('gamedata', 'playerdata', 'eventdata1d', 'eventdata2d');
          while (true) {
              $gameIDs = array();
              $sql = "SELECT gameID FROM {$GLOBALS['cfg']['db']['table_prefix']}gamedata
                WHERE name = '_v_time_start'
                ORDER BY value DESC
                LIMIT {$GLOBALS['cfg']['games_limit']}, 500";
              $rs = $V9c1ebee8->Execute($sql);
              if (!$rs || $rs->EOF) break;
              while ($rs && !$rs->EOF) {
                  $gameIDs[] = $rs->fields[0];
                  $rs->MoveNext();
              }
              // remove specific data from every game
              foreach ($gameIDs as $gameID) {
                  foreach ($tables as $table) {
                    $sql = "DELETE FROM {$GLOBALS['cfg']['db']['table_prefix']}$table WHERE gameID = $gameID";
                    $V9c1ebee8->Execute($sql);
                  }
              }
          }
          // optimize databases
          print "optimizing tables...";
          foreach ($tables as $table) {
              $sql = "OPTIMIZE TABLE {$GLOBALS['cfg']['db']['table_prefix']}$table";
              $V9c1ebee8->Execute($sql);
          }
          print "done\n";
          Fa10803e1();
      }
//endchange
      function F43781db5(&$V7a55b3e1, &$Vcc64f241) // almacena los cambios en la base de datos
      {
          global $V9c1ebee8; // db
          $this->games_parsed++;
          if (!$V7a55b3e1) { // juego vacío (sin jugadores)
              print "game is empty?, ignored\n";
              Fa10803e1();
              return;
          }
//change: queries optimization
          $queries = array(
              'gamedata' => array(
                  'sql' => array("REPLACE INTO {$GLOBALS['cfg']['db']['table_prefix']}gamedata (gameID, name, value) VALUES "),
                  'queries' => array()
              ), 'gameprofile' => array(
                  'sql' => array("REPLACE INTO {$GLOBALS['cfg']['db']['table_prefix']}gameprofile (gameID, timeStart) VALUES ")
              )
          );
//endchange
          $this->V75125d17 = $V7a55b3e1; // jugadores
          $this->Vf273a653 = $Vcc64f241; // opciones del juego
          $this->Vf273a653['_v_players'] = count($this->V75125d17); // número de jugadores
          if (!isset($this->Vf273a653['_v_players']))
              $this->Vf273a653['_v_players'] = "?";
          if (!isset($this->Vf273a653['_v_map']))
              $this->Vf273a653['_v_map'] = "?";
          if (!isset($this->Vf273a653['_v_mod']))
              $this->Vf273a653['_v_mod'] = "?";
          if (!isset($this->Vf273a653['_v_game']))
              $this->Vf273a653['_v_game'] = "?";
          if (!isset($this->Vf273a653['_v_game_type']))
              $this->Vf273a653['_v_game_type'] = "?";
          if (!isset($this->Vf273a653['_v_time_start']))
              $this->Vf273a653['_v_time_start'] = "1000-01-01 00:00:00";
//change: check for duplicated games
          do {
              preg_match("/^0\\.(\d+) (\d+)/", microtime(), $Vc7e009b7);
              $Vc7e009b7 = $Vc7e009b7[2] . $Vc7e009b7[1]; // genera id del juego
          } while ($this->V7da699e4 == $Vc7e009b7);
//endchange
          $this->V7da699e4 = $Vc7e009b7; // id del juego
          if ($this->Vf273a653) { // inserta datos del juego
              foreach ($this->Vf273a653 as $V2cbf43a2 => $Vcb99dc4d) {
                  $V2cbf43a2 = $V9c1ebee8->qstr($V2cbf43a2);
                  $Vcb99dc4d = $V9c1ebee8->qstr($Vcb99dc4d);
                  $queries['gamedata']['queries'][] = "($this->V7da699e4, $V2cbf43a2, $Vcb99dc4d)";
              }
          }
          $qtime = $V9c1ebee8->qstr($this->Vf273a653['_v_time_start']);
          if ($GLOBALS['cfg']['parser']['check_unique_gameID']) {
              $sql = "SELECT gameID FROM {$GLOBALS['cfg']['db']['table_prefix']}gameprofile
                WHERE timeStart = $qtime
                LIMIT 1";
              $rs = $V9c1ebee8->Execute($sql);
              if ($rs->fields[0]) {
                  print "duplicated game timestamp, ignored\n";
                  Fa10803e1();
                  return;
              }
          }
          $queries['gameprofile']['queries'][] = "($this->V7da699e4, $qtime)";
          print "updating database...";
          Fa10803e1();
          foreach ($this->V75125d17 as $Vafe72417 => $V910d9037) { // actualiza los datos de los jugadores
              //if ($this->V75125d17[$Vafe72417]['profile']['skill'] < 1600.0)
              //$this->V75125d17[$Vafe72417]['profile']['skill']=1600.0;
              //change: camel fix
              $this->V75125d17[$Vafe72417]['profile']['skill'] = number_format($this->V75125d17[$Vafe72417]['profile']['skill'], 4, '.', '');
              //endchange
              if ($GLOBALS['cfg']['parser']['use_original_playerID']) {
                  $V910d9037['v']['original_id'] = $V910d9037['v']['original_id'];
              } else {
                  $V910d9037['v']['original_id'] = $Vafe72417;
              }
              $V910d9037['v']['original_id'] = $V9c1ebee8->qstr(substr($V910d9037['v']['original_id'], 0, 99));

              $name = $V9c1ebee8->qstr($this->V75125d17[$Vafe72417]['profile']['name']); // último nombre
              if (isset($GLOBALS['cfg']['parser']['use_most_used_playerName']) && $GLOBALS['cfg']['parser']['use_most_used_playerName'] == 1) { // nombre más usado
                  $Vac5c74b6 = sprintf("select dataValue, count(*) as num from {$GLOBALS['cfg']['db']['table_prefix']}playerdata
 where dataName=%s and playerID={$V910d9037['v']['original_id']} group by dataValue order by num desc
 ", $V9c1ebee8->qstr('alias')); // obtiene el nombre más usado
                  $V3a2d7564 = $V9c1ebee8->SelectLimit($Vac5c74b6, 1, 0);
                  if ($V3a2d7564 and !$V3a2d7564->EOF) {
                      $name = $V9c1ebee8->qstr($V3a2d7564->fields[0]);
                  }
              }
              //change: get country code
              if ($GLOBALS['cfg']['ip2country']['countries_only']) {
                  $code = $this->V75125d17[$Vafe72417]['profile']['tld']; // último tld
                  if (@$GLOBALS['cfg']['parser']['use_most_used_playerIP']) { // tld más usado
                      $Vac5c74b6 = sprintf("select dataValue, count(*) as num from {$GLOBALS['cfg']['db']['table_prefix']}playerdata
                        where dataName='tld' and playerID={$V910d9037['v']['original_id']} group by dataValue order by num desc");
                      $V3a2d7564 = $V9c1ebee8->SelectLimit($Vac5c74b6, 1, 0);
                      if ($V3a2d7564 && !$V3a2d7564->EOF) {
                          $code = $V3a2d7564->fields[0];
                      }
                  }
                  if ($code) {
                      $countryCode = $V9c1ebee8->qstr($code);
                  }
              } else {
                  $ip = $this->V75125d17[$Vafe72417]['profile']['ip']; // último ip
                  if ($GLOBALS['cfg']['parser']['use_most_used_playerIP']) { // ip más usado
                      $Vac5c74b6 = sprintf("select dataValue, count(*) as num from {$GLOBALS['cfg']['db']['table_prefix']}playerdata
                        where dataName='ip' and playerID={$V910d9037['v']['original_id']} group by dataValue order by num desc");
                      $V3a2d7564 = $V9c1ebee8->SelectLimit($Vac5c74b6, 1, 0);
                      if ($V3a2d7564 && !$V3a2d7564->EOF) {
                          $ip = $V3a2d7564->fields[0];
                      }
                  }
                  $ip_number = sprintf("%u", ip2long($ip));
                  $sql = "SELECT country_code2
                        FROM {$GLOBALS['cfg']['db']['table_prefix']}ip2country
                        WHERE $ip_number BETWEEN ip_from AND ip_to";
                  $rs = $V9c1ebee8->Execute($sql);
                  if ($rs->fields[0]) {
                      $countryCode = $V9c1ebee8->qstr($rs->fields[0]);
                  }
              }
              if (!@$countryCode || $countryCode == "''") $countryCode = $V9c1ebee8->qstr('XX');
              //endchange
              //change: decimal skill
              if (!isset($queries['playerprofile'])) {
                  $queries['playerprofile'] = array(
                      'sql' => array(
                          "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}playerprofile (playerID, playerName, countryCode, 
                            skill, kills, deaths, games, kill_streak, death_streak, first_seen, last_seen) VALUES ",
                          " ON DUPLICATE KEY UPDATE playerName = VALUES(playerName), countryCode = VALUES(countryCode), skill = VALUES(skill), 
                            kills = kills + VALUES(kills), deaths = deaths + VALUES(deaths), games = games + VALUES(games), 
                            kill_streak = IF(VALUES(kill_streak) > kill_streak, VALUES(kill_streak), kill_streak), 
                            death_streak = IF(VALUES(death_streak) > death_streak, VALUES(death_streak), death_streak),
                            first_seen = IF(VALUES(first_seen) < first_seen, VALUES(first_seen), first_seen),
                            last_seen = IF(VALUES(last_seen) > last_seen, VALUES(last_seen), last_seen)"
                      ), 'queries' => array()
                  );
              }
              $queries['playerprofile']['queries'][] = "({$V910d9037['v']['original_id']}, $name, $countryCode,
                    {$this->V75125d17[$Vafe72417]['profile']['skill']}, {$this->V75125d17[$Vafe72417]['profile']['kills']},
                    {$this->V75125d17[$Vafe72417]['profile']['deaths']}, 1, {$this->V75125d17[$Vafe72417]['profile']['kill_streak']},
                    {$this->V75125d17[$Vafe72417]['profile']['death_streak']}, '{$this->Vf273a653['_v_time_start']}',
                    '{$this->Vf273a653['_v_time_start']}')";
              $dataIndexes = array();
              foreach ($V910d9037 as $V17f71d96 => $Vf57346d3) { // itera sobre la información de los jugadores
                  if (!strcmp($V17f71d96, 'data') || !strcmp($V17f71d96, 'vdata')) { // si es data o vdata
                      foreach ($Vf57346d3 as $V38bb9770 => $V260a7bf2) {
                          $V7b824acf = $V260a7bf2[1];
                          $V38bb9770 = $V9c1ebee8->qstr($V38bb9770);
                          $V418c5509 = $V260a7bf2[0];
                          if (!strcmp($V418c5509, "rep") || !strcmp($V418c5509, "inc") || !strcmp($V418c5509, "avg")) {
                              $V260a7bf2[1] = $V9c1ebee8->qstr($V260a7bf2[1]);
                              if (!isset($queries["playerdata$V418c5509"])) {
                                  $aux = $V418c5509 == 'rep' ? "VALUES(dataValue)" : ($V418c5509 == 'inc' ? "dataValue+VALUES(dataValue)" : "round((dataValue+VALUES(dataValue))/2.0,2.0)");
                                  $queries["playerdata$V418c5509"] = array(
                                      'sql' => array(
                                          "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}playerdata (playerID, gameID, dataName, dataNo, dataValue) VALUES ",
                                          " ON DUPLICATE KEY UPDATE dataValue = $aux"
                                      ), 'queries' => array()
                                  );
                              }
                              $queries["playerdata$V418c5509"]['queries'][] = "({$V910d9037['v']['original_id']}, 0, $V38bb9770, 0, {$V260a7bf2[1]})";
                          } elseif (!strcmp($V418c5509, "sto")) { // guarda datos del jugador
                              unset($V260a7bf2[0]);
                              foreach ($V260a7bf2 as $V1b612377 => $V7b824acf) {
                                  if (!isset($dataIndexes[$V38bb9770])) {
                                      $dataIndexes[$V38bb9770] = 0;
                                  }
                                  $dataNo = $dataIndexes[$V38bb9770]++;
                                  if (!isset($queries['playerdata'])) {
                                      $queries['playerdata'] = array(
                                          'sql' => array("INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}playerdata (playerID, gameID, dataName, dataNo, dataValue) VALUES "),
                                          'queries' => array()
                                      );
                                  }
                                  if (!isset($queries['playerdata_total'])) {
                                      $queries['playerdata_total'] = array(
                                          'sql' => array(
                                              "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total (playerID, dataName, dataValue, dataCount) VALUES ",
                                              " ON DUPLICATE KEY UPDATE dataCount = dataCount + 1"
                                          ), 'queries' => array()
                                      );
                                  }
                                  $V7b824acf = $V9c1ebee8->qstr($V7b824acf);
                                  $queries['playerdata']['queries'][] = "({$V910d9037['v']['original_id']}, $this->V7da699e4, $V38bb9770, $dataNo, $V7b824acf)";
                                  $queries['playerdata_total']['queries'][] = "({$V910d9037['v']['original_id']}, $V38bb9770, $V7b824acf, 1)";
                              }
                          }
                      }
                  } elseif (!strcmp($V17f71d96, 'events')) { // eventos
                      foreach ($Vf57346d3 as $V9bbd993d => $V61b837be) { // events
                          foreach ($V61b837be as $Vf894427c => $Vf60229f3) { // rounds (?)
                              $V61110698 = $V9c1ebee8->qstr($Vf894427c);
                              foreach ($Vf60229f3 as $V29a7e964 => $V91812ae6) { // teams
                                  $V6f8b602a = $V9c1ebee8->qstr($V29a7e964);
                                  foreach ($V91812ae6 as $V1cd03614 => $V9290beca) { // roles
                                      if (!strcmp($V1cd03614, '1D')) { // afectan solamente a un player
                                          foreach ($V9290beca as $Vf1a19314 => $V1c6ef5e9) {
                                              $V10dad7cb = "";
                                              if (preg_match("/^(.*)\\|(.+)/", $Vf1a19314, $Vb74df323)) {
                                                  $V10dad7cb = $Vb74df323[1];
                                                  $Vf1a19314 = $Vb74df323[2];
                                              }
                                              $Vf1a19314 = $V9c1ebee8->qstr($Vf1a19314);
                                              //change: camel fix
                                              $V1c6ef5e9 = $V9c1ebee8->qstr(is_float($V1c6ef5e9) ? number_format($V1c6ef5e9, 2, '.', '') : $V1c6ef5e9);
                                              //endchange
                                              $V10dad7cb = $V9c1ebee8->qstr($V10dad7cb);
                                              if (!isset($queries['eventdata1d'])) {
                                                  $queries['eventdata1d'] = array(
                                                      'sql' => array("INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d (playerID, gameID, round, team, role, eventName, eventCategory, eventValue) VALUES "),
                                                      'queries' => array()
                                                  );
                                              }
                                              $queries['eventdata1d']['queries'][] = "({$V910d9037['v']['original_id']}, $this->V7da699e4, $V9bbd993d, $V61110698, $V6f8b602a, $Vf1a19314, $V10dad7cb, $V1c6ef5e9)";
                                              // special treatment for skill events
                                              if ($V10dad7cb == "'skill'") {
                                                  if ($Vf1a19314 == "'begins'" || $Vf1a19314 == "'ends'") {
                                                      if (!isset($queries['eventdata1d_minskill'])) {
                                                          $queries['eventdata1d_minskill'] = array(
                                                              'sql' => array(
                                                                  "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total (playerID, eventCategory, eventName, eventValue) VALUES ",
                                                                  " ON DUPLICATE KEY UPDATE eventValue = IF(0+eventValue < 0+VALUES(eventValue), eventValue, VALUES(eventValue))"
                                                              ), 'queries' => array()
                                                          );
                                                          $queries['eventdata1d_maxskill'] = array(
                                                              'sql' => array(
                                                                  "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total (playerID, eventCategory, eventName, eventValue) VALUES ",
                                                                  " ON DUPLICATE KEY UPDATE eventValue = IF(0+eventValue > 0+VALUES(eventValue), eventValue, VALUES(eventValue))"
                                                              ), 'queries' => array()
                                                          );
                                                      }
                                                      $queries['eventdata1d_minskill']['queries'][] = "({$V910d9037['v']['original_id']}, 'skill', 'min', $V1c6ef5e9)";
                                                      $queries['eventdata1d_maxskill']['queries'][] = "({$V910d9037['v']['original_id']}, 'skill', 'max', $V1c6ef5e9)";
                                                  }
                                                  continue;
                                              }
                                              // other events
                                              if (!isset($queries['eventdata1d_total'])) {
                                                  $queries['eventdata1d_total'] = array(
                                                      'sql' => array(
                                                          "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total (playerID, eventCategory, eventName, eventValue) VALUES ",
                                                          " ON DUPLICATE KEY UPDATE eventValue = eventValue + VALUES(eventValue)"
                                                      ), 'queries' => array()
                                                  );
                                              }
                                              $queries['eventdata1d_total']['queries'][] = "({$V910d9037['v']['original_id']}, $V10dad7cb, $Vf1a19314, $V1c6ef5e9)";
                                          }
                                      } elseif (!strcmp($V1cd03614, '2D')) { // eventos en los que intervienen 2 jugadores - los acc stats está acá supongo que por compatibilidad con otros juegos, lástima que q3 no tiene :(
                                          foreach ($V9290beca as $V60962ab1 => $V6b3b7a9f) { // team 2 (?)
                                              $Vbc6c5186 = $V9c1ebee8->qstr($V60962ab1);
                                              foreach ($V6b3b7a9f as $V84ccdc56 => $Vda5a5b5d) { // role 2 (?)
                                                  $V4647c709 = $V9c1ebee8->qstr($V84ccdc56);
                                                  foreach ($Vda5a5b5d as $V863818d8 => $Vcf7bfd32) { //  player 2 id
                                                      if ($GLOBALS['cfg']['parser']['use_original_playerID']) {
                                                          $Vdbead972 = $this->V75125d17[$V863818d8]['v']['original_id'];
                                                      } else {
                                                          $Vdbead972 = $V863818d8;
                                                      }
                                                      $Vdbead972 = $V9c1ebee8->qstr(substr($Vdbead972, 0, 99));
                                                      foreach ($Vcf7bfd32 as $Vd4e58592 => $V2029376b) {
                                                          $V701adf24 = "";
                                                          if (preg_match("/^(.*)\\|(.+)/", $Vd4e58592, $Vb74df323)) {
                                                              $V701adf24 = $Vb74df323[1];
                                                              $Vd4e58592 = $Vb74df323[2];
                                                          }
                                                          $Vd4e58592 = $V9c1ebee8->qstr($Vd4e58592);
                                                          $V2029376b = $V9c1ebee8->qstr($V2029376b);
                                                          $V701adf24 = $V9c1ebee8->qstr($V701adf24);
                                                          if (!isset($queries['eventdata2d'])) {
                                                              $queries['eventdata2d'] = array(
                                                                  'sql' => array("INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d
                                                                        (playerID, gameID, round, team, role, eventName, eventCategory, eventValue, player2ID, team2, role2) VALUES "),
                                                                  'queries' => array()
                                                              );
                                                          }
                                                          if (!isset($queries['eventdata2d_total'])) {
                                                              $queries['eventdata2d_total'] = array(
                                                                  'sql' => array(
                                                                      "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d_total (playerID, player2ID, eventCategory, eventName, eventValue) VALUES ",
                                                                      " ON DUPLICATE KEY UPDATE eventValue = eventValue + VALUES(eventValue)"
                                                                  ), 'queries' => array()
                                                              );
                                                          }
                                                          $queries['eventdata2d']['queries'][] = "({$V910d9037['v']['original_id']}, $this->V7da699e4, $V9bbd993d, $V61110698, $V6f8b602a, $Vd4e58592, $V701adf24, $V2029376b, $Vdbead972, $Vbc6c5186, $V4647c709)";
                                                          $queries['eventdata2d_total']['queries'][] = "({$V910d9037['v']['original_id']}, $Vdbead972, $V701adf24, $Vd4e58592, $V2029376b)";
                                                      }
                                                  }
                                              }
                                          }
                                      }
                                  }
                              }
                          }
                      }
                  }
              }
          }
          $Vd744430a["last update time"] = date('Y-m-d H:i:s'); // última actualización
          $Vd744430a["vsp version"] = constant("cVERSION"); // última versión de vsp ejecutada
          foreach ($Vd744430a as $V13c571a8 => $Va2f69a5a) {
              $name = $V9c1ebee8->qstr($V13c571a8);
              $value = $V9c1ebee8->qstr($Va2f69a5a);
              $queries['gamedata']['queries'][] = "(0, $name, $value)";
          }
          //change: batched queries
          foreach ($queries as $query) {
              $sql = $query['sql'][0].implode(', ', $query['queries']);
              if (isset($query['sql'][1])) {
                  $sql .= $query['sql'][1];
              }
              $V9c1ebee8->Execute($sql);
              $err = $V9c1ebee8->ErrorMsg();
              if ($err) {
                die("\n\nError: $err\nQuery: $sql");
              }
          }
          unset($queries);
          //endchange
          print "done\n";
          $this->games_inserted++;
          if ($GLOBALS['cfg']['games_limit'] >= 0 && $this->games_inserted % 500 == 0) {
              $this->prune_old_games();
          }
          Fa10803e1();
      }
  }
  function F4ca894df()
  {
      print cTITLE;
  }
  function F4d7a92f8()
  {
      print cUSAGE;
  }
  function Fb7d30ee1($V341be97d)
  {
      $Vad42f669 = 1;
      if ($Vad42f669 == 1) {
          print "$V341be97d";
      }
  }
  function F03c2b497($V6e2baaf3)
  {
      print "\n$V6e2baaf3\n";
      F56fd05e9();
  }
  function Facf3bf61($Vd17549fa)
  {
      F4d7a92f8();
      print "$Vd17549fa\n";
      F56fd05e9();
  }
  function F30765b08(&$V5a1af13e, $V73600783)
  {
      $Vd5efc4b7 = ftp_rawlist($V5a1af13e, $V73600783);
      $V23227229 = Fe6fec173($Vd5efc4b7);
      $V0ad17471 = array();
      foreach ($V23227229 as $V8c7dd922) {
          if ($V8c7dd922['type'] == 0) {
              $V0ad17471[(count($V0ad17471))] = $V8c7dd922;
          }
      }
      return $V0ad17471;
  }
  function Fd2c39001($V6c62e2ab)
  {
      $Va4b43381 = parse_url($V6c62e2ab);
      echo "Attempting to connect to FTP server {$Va4b43381['host']}:{$Va4b43381['port']}...\n";

      if (isset($Va4b43381['user']) || isset($Va4b43381['pass'])) {
          echo " - Specify the ftp username and password in the config and not in the VSP command line (Security reasons?)\n";
          F56fd05e9();
      }
      Fa10803e1();
      if (!$V5a1af13e = ftp_connect($Va4b43381['host'], $Va4b43381['port'], 30)) {
          echo " - Error: Failed to connect to ftp server. Verify FTP hostname/port.\n";
          echo " Also, your php host may not have ftp access via php enabled or may\n";
          echo " have blocked the php process from connecting to an external server\n";
          F56fd05e9();
      }
      if (!ftp_login($V5a1af13e, $GLOBALS['cfg']['ftp']['username'], $GLOBALS['cfg']['ftp']['password'])) {
          echo " - Error: Failed to login to ftp server. Verify FTP username/password in config\n";
          F56fd05e9();
      }
      echo " - Connection/Login successful.\n";
      if (isset($GLOBALS['cfg']['ftp']['pasv']) && $GLOBALS['cfg']['ftp']['pasv']) {
          if (ftp_pasv($V5a1af13e, true))
              echo " - FTP passive mode enabled\n";
          else
              echo " - failed to enable FTP passive mode\n";
      } else {
          echo " - not using FTP passive mode (disabled in config)\n";
      }
      if (!F331c0468($GLOBALS['cfg']['ftp']['logs_path'])) {
          echo " - Error: Failed to create local directory \"" . $GLOBALS['cfg']['ftp']['logs_path'] . "\" for FTP log download.\n";
          echo " Check pathname/permissions.\n";
          F56fd05e9();
      }
      if (preg_match("/[\\/\\\\]$/", $Va4b43381['path'])) {
          echo " - Preparing to download all files from remote directory \"" . $Va4b43381['path'] . "\"\n";
          $V0ad17471 = F30765b08($V5a1af13e, $Va4b43381['path']);
          preg_match("/([^\\/\\\\]+[\\/\\\\])$/", $Va4b43381['path'], $Vb74df323);
          $V24faf2f1 = $Va4b43381['path'];
          $Ved05e3b3 = F9578dd1f($GLOBALS['cfg']['ftp']['logs_path'] . $Vb74df323[1]);
          F331c0468($Ved05e3b3);
          $Vc0d41efd = $Ved05e3b3;
      } else {
          echo " - Preparing to download the remote file \"" . $Va4b43381['path'] . "\"\n";
          preg_match("/([^\\/\\\\]+)$/", $Va4b43381['path'], $Vb74df323);
          $V0ad17471[0]['name'] = $Vb74df323[1];
          $V0ad17471[0]['size'] = ftp_size($V5a1af13e, $Va4b43381['path']);
          $V24faf2f1 = substr($Va4b43381['path'], 0, strlen($Va4b43381['path']) - strlen($V0ad17471[0]['name']));
          $Ved05e3b3 = F9578dd1f($GLOBALS['cfg']['ftp']['logs_path']);
          $Vc0d41efd = $Ved05e3b3 . $V0ad17471[0]['name'];
      }
      if (!ctype_digit('' . $V0ad17471[0]['size']) || $V0ad17471[0]['size'] < 0) {
          echo " - Error: cannot find Remote file \"" . $V0ad17471[0]['name'] . "\" at ftp://{$Va4b43381['host']}:{$Va4b43381['port']}" . $V24faf2f1 . "\n";
          F56fd05e9();
      }
      foreach ($V0ad17471 as $V8c7dd922) {
          $Vb026bf91 = $Ved05e3b3 . $V8c7dd922['name'];
          $V85b21706 = file_exists($Vb026bf91) ? filesize($Vb026bf91) - 1 : 0;
          $Vef5e6c91 = $V24faf2f1 . $V8c7dd922['name'];
          $V011089a7 = $V8c7dd922['size'];
          echo " - Attempting to download \"$Vef5e6c91\" from FTP server to \"$Vb026bf91\"...\n";
          Fa10803e1();
          if (isset($GLOBALS['cfg']['ftp']['overwrite']) && $GLOBALS['cfg']['ftp']['overwrite']) {
              echo " - overwrite mode\n";
              if (!ftp_get($V5a1af13e, $Vb026bf91, $Vef5e6c91, FTP_BINARY)) {
                  echo " Error: Failed to get ftp log from \"$Vef5e6c91\" to \"$Vb026bf91\".\n";
                  if (!$GLOBALS['cfg']['ftp']['pasv'])
                      echo " Try enabling FTP passive mode in config.\n";
                  echo " Try making the ftplogs/ and logdata/ folder writable by all (chmod 777).\n";
                  F56fd05e9();
              }
              echo " Downloaded remote file successfully\n";
              Fa10803e1();
          } else {
              if ($V011089a7 == $V85b21706 + 1) {
                  echo " Remote file is the same size as Local file. Skipped Download.\n";
              } elseif ($V011089a7 > $V85b21706 + 1) {
                  if (!ftp_get($V5a1af13e, $Vb026bf91, $Vef5e6c91, FTP_BINARY, $V85b21706)) {
                      echo " Error: Failed to get ftp log from \"$Vef5e6c91\" to \"$Vb026bf91\".\n";
                      if (!$GLOBALS['cfg']['ftp']['pasv'])
                          echo " Try enabling FTP passive mode in config.\n";
                      echo " Try making the ftplogs/ and logdata/ folder writable by all (chmod 777).\n";
                      F56fd05e9();
                  }
                  echo " Downloaded/Resumed remote file successfully\n";
              } else {
                  echo " Remote file is smaller than Local file. Skipped Download.\n";
              }
              Fa10803e1();
          }
      }
      echo $Vc0d41efd . "\n";
      return $Vc0d41efd;
  }
  function F92261ca6()
  {
      global $V51d3ee44;
      if (cIS_SHELL) {
          if (!isset($_SERVER['argc'])) {
              echo "Error: args not registered.\n";
              echo " register_argc_argv may need to be set to On in shell mode\n";
              echo " Please edit your php.ini and set variable register_argc_argv to On\n";
              F56fd05e9();
          }
          $V51d3ee44['argv'] = $_SERVER['argv'];

          $V51d3ee44['argc'] = $_SERVER['argc'];
      } else {
          $V4f96c5a0 = $_POST['V70e78261'];
          if (get_magic_quotes_gpc())
              $V4f96c5a0 = stripslashes($V4f96c5a0);
          $V51d3ee44 = F126ba7b1("vsp.php " . $V4f96c5a0);
      }
      global $V0f14082c;

      $V0f14082c['parser-options'] = [];
      $V0f14082c['prompt'] = 1;
      if ($V51d3ee44['argc'] > 1) {
          for ($V865c0c0b = 1; $V865c0c0b < $V51d3ee44['argc'] - 1; $V865c0c0b++) {
              if (strcmp($V51d3ee44['argv'][$V865c0c0b], "-a") == 0) {
                  $V865c0c0b++;
                  $V0f14082c['action'] = $V51d3ee44['argv'][$V865c0c0b];
                  //change: add new actions
                  if (!in_array($V0f14082c['action'], array('clear_db', 'gen_awards', 'clear_savestate', 'pop_ip2country', 'prune_old_games')))
                      Facf3bf61("error: invalid action");
                  //endchange
                  break;
              }
              if (strcmp($V51d3ee44['argv'][$V865c0c0b], "-n") == 0) {
                  $V0f14082c['prompt'] = 0;
                  continue;
              }
              if ($V865c0c0b + 1 > $V51d3ee44['argc'] - 2) {
                  Facf3bf61("error: no value specified for option " . $V51d3ee44['argv'][$V865c0c0b]);
              }
              if (strcmp($V51d3ee44['argv'][$V865c0c0b], "-p") == 0) {
                  $V865c0c0b++;
                  for ($V363b122c = $V865c0c0b; $V363b122c < $V51d3ee44['argc'] - 1; $V363b122c = $V363b122c + 2) {
                      $V0f14082c['parser-options'][$V51d3ee44['argv'][$V363b122c]] = $V51d3ee44['argv'][$V363b122c + 1];
                  }
                  break;
              } elseif (strcmp($V51d3ee44['argv'][$V865c0c0b], "-c") == 0) {
                  $V865c0c0b++;
                  $V0f14082c['config'] = $V51d3ee44['argv'][$V865c0c0b];
              } elseif (strcmp($V51d3ee44['argv'][$V865c0c0b], "-l") == 0) {
                  $V865c0c0b++;
                  $V0f14082c['log-gamecode'] = $V51d3ee44['argv'][$V865c0c0b];
                  $V0f14082c['log-gametype'] = '';
                  if (preg_match("/(.*)-(.*)/", $V0f14082c['log-gamecode'], $Vb74df323)) {
                      $V0f14082c['log-gamecode'] = $Vb74df323[1];
                      $V0f14082c['log-gametype'] = $Vb74df323[2];
                      $V0f14082c['parser-options']['gametype'] = $V0f14082c['log-gametype'];
                  }
              } else {
                  Facf3bf61("error: invalid option " . $V51d3ee44['argv'][$V865c0c0b]);
              }
          }
      } else {
          Facf3bf61("error: logfile not specified");
      }
      $V0f14082c['logfile'] = $V51d3ee44['argv'][$V51d3ee44['argc'] - 1];
      if (!isset($V0f14082c['action'])) {
          if (!isset($V0f14082c['logfile'])) {
              Facf3bf61("error: logFile not specified");
          }
          if (!isset($V0f14082c['log-gamecode'])) {
              Facf3bf61("error: logType not specified");
          }
      }
      $V55d5b418 = "pub/configs/";
      if (!isset($V0f14082c['config']) || preg_match("/\\.\\./", $V0f14082c['config']) || !is_file($V55d5b418 . $V0f14082c['config'])) {
          $V0f14082c['config'] = $V55d5b418 . "cfg-default.php";
      } else {
          $V0f14082c['config'] = $V55d5b418 . $V0f14082c['config'];
      }
      echo "max_execution_time is " . ini_get("max_execution_time") . "\n\n";
      echo "[command-line options]: ";
      print_r($V0f14082c);
      if (isset($V0f14082c['parser-options']['savestate']) && $V0f14082c['parser-options']['savestate']) {
          $Vb3521e13 = "writetest_" . md5(uniqid(rand(), true));
          $V2880b5ba = fopen('./logdata/' . $Vb3521e13, "wb");
          if (!$V2880b5ba || !fwrite($V2880b5ba, "* WRITE TEST *\n")) {
              echo "Error: savestate 1 processing requires logdata/ directory to be writable.\n";
              echo " Enable write permissions for logdata/ directory (chmod 777)\n";
              F56fd05e9();
          }
          fclose($V2880b5ba);
          unlink("logdata/$Vb3521e13");
      }
  }
  function F68c076b3()
  {
      global $V0f14082c;
      global $V51d3ee44;
      require_once($V0f14082c['config']);
      if (preg_match("/^ftp:\\/\\//i", $V0f14082c['logfile']))
          $V0f14082c['logfile'] = Fd2c39001($V0f14082c['logfile']);
      $V0f14082c['parser-options']['trackID'] = $GLOBALS['cfg']['parser']['trackID'];
      if (isset($GLOBALS['cfg']['db']['adodb_path']))
          $GLOBALS['cfg']['db']['adodb_path'] = F9578dd1f($GLOBALS['cfg']['db']['adodb_path']);
      else
          $GLOBALS['cfg']['db']['adodb_path'] = F9578dd1f(Ce5c65ec5) . 'pub/lib/adodb/';
      require_once("{$GLOBALS['cfg']['db']['adodb_path']}" . 'adodb.inc.php');
      include_once("{$GLOBALS['cfg']['db']['adodb_path']}" . 'tohtml.inc.php');
      require_once("sql/{$GLOBALS['cfg']['db']['adodb_driver']}.inc.php");
      include_once("pub/include/playerBanList-{$GLOBALS['cfg']['player_ban_list']}.inc.php");
      //change: add player_exclude_list support
      include_once("pub/include/playerExcludeList-{$GLOBALS['cfg']['player_exclude_list']}.inc.php");
      //endchange
      foreach ($GLOBALS['player_ban_list'] as $V7fa3b767 => $V36190f8a) {
          $GLOBALS['player_ban_list'][$V7fa3b767] = "/^" . preg_quote($V36190f8a) . "$/";
      }
      $GLOBALS['V9c1ebee8'] = &ADONewConnection($GLOBALS['cfg']['db']['adodb_driver']);
      global $V9c1ebee8;

      if (!$V9c1ebee8->Connect($GLOBALS['cfg']['db']['hostname'], $GLOBALS['cfg']['db']['username'], $GLOBALS['cfg']['db']['password'], $GLOBALS['cfg']['db']['dbname'])) {
          echo "Attempting to create/connect to database {$GLOBALS['cfg']['db']['dbname']}\n";
          $GLOBALS['V9c1ebee8'] = null;
          $GLOBALS['V9c1ebee8'] = &ADONewConnection($GLOBALS['cfg']['db']['adodb_driver']);
          global $V9c1ebee8;

          $V9c1ebee8->Connect($GLOBALS['cfg']['db']['hostname'], $GLOBALS['cfg']['db']['username'], $GLOBALS['cfg']['db']['password']);

          $V9c1ebee8->Execute($sql_create[0]);
          if (!$V9c1ebee8->Connect($GLOBALS['cfg']['db']['hostname'], $GLOBALS['cfg']['db']['username'], $GLOBALS['cfg']['db']['password'], $GLOBALS['cfg']['db']['dbname'])) {
              echo " - failed to create/connect to database {$GLOBALS['cfg']['db']['dbname']}\n";
              F56fd05e9();
          }
          echo " - database created\n";
      }
      //change: add new options
      if (isset($V0f14082c['action'])) {
          switch ($V0f14082c['action']) {
              case "clear_db":
                  if (cIS_SHELL && $V0f14082c['prompt']) {
                      echo "Are you sure you want to clear the database {$GLOBALS['cfg']['db']['dbname']} @ {$GLOBALS['cfg']['db']['hostname']}? (y/n)\n";
                      Fa10803e1();
                      $Vd0cf705f = Fd63c38c9();
                  } else {
                      $Vd0cf705f = 'y';
                  }
                  if ($Vd0cf705f == 'y' || $Vd0cf705f == 'Y') {
                      foreach ($sql_destroy as $V7fa3b767 => $Vac5c74b6) {
                          $V9c1ebee8->Execute($Vac5c74b6);
                      }
                      print "{$GLOBALS['cfg']['db']['table_prefix']}* tables in {$GLOBALS['cfg']['db']['dbname']} @ {$GLOBALS['cfg']['db']['hostname']} has been cleared\n";
                  }
                  Fa3e3aec1();
                  break;
              case "gen_awards":
                  $V21d8a920 = new F622a322a();
                  $V21d8a920->F215f9169();
                  Fa3e3aec1();
                  break;
              case "clear_savestate":
                  if ($V0f14082c['logfile'] == "clear_savestate") {
                      $V0f14082c['logfile'] = "";
                  } else {
                      $realpath_log = realpath($V0f14082c['logfile']);
                  }
                  if (cIS_SHELL && $V0f14082c['prompt']) {
                      if ($V0f14082c['logfile']) {
                          echo "Are you sure you want to clear the savestate information for the log file {$realpath_log}? (y/n)\n";
                      } else {
                          echo "Are you sure you want to clear the savestate information for ALL the log files? (y/n)\n";
                      }
                      Fa10803e1();
                      $Vd0cf705f = Fd63c38c9();
                  } else {
                      $Vd0cf705f = 'y';
                  }
                  if ($Vd0cf705f == 'y' || $Vd0cf705f == 'Y') {
                      $logfile = $V9c1ebee8->qstr($realpath_log);
                      $sql = "DELETE FROM {$GLOBALS['cfg']['db']['table_prefix']}savestate";
                      if ($V0f14082c['logfile']) {
                          $sql .= " WHERE `logfile` = {$logfile}";
                      }
                      $V9c1ebee8->Execute($sql);
                      echo "Savestate information for log file {$realpath_log} cleared\n";
                  }
                  Fa3e3aec1();
                  break;
              case 'pop_ip2country':
                  populateIp2countryTable();
                  Fa3e3aec1();
                  break;
              case 'prune_old_games':
                  $V21d8a920 = new F622a322a();
                  $V21d8a920->prune_old_games();
                  Fa3e3aec1();
                  break;
          }
      }
      //endchange
      foreach ($sql_create as $V7fa3b767 => $Vac5c74b6) {
          if ($V7fa3b767 == 0)
              continue;
          $V9c1ebee8->Execute($Vac5c74b6);
      }
      //change: automatic insert ip2country table
      $sql = "SELECT COUNT(*) FROM {$GLOBALS['cfg']['db']['table_prefix']}ip2country";
      $rs = $V9c1ebee8->Execute($sql);
      if (!$rs || !$rs->fields[0]) {
          populateIp2countryTable();
      }
      //endchange
      $V9c1ebee8->SetFetchMode(ADODB_FETCH_NUM);
      if (!is_dir("pub/games/{$GLOBALS['cfg']['game']['name']}")) {
          echo "Error: The variable \$cfg['game']['name'] is not set properly in config file.\n";
          echo " Edit your config file ({$V0f14082c['config']})\n";
          echo " Read the comments beside that variable and set that variable properly.\n";
          F56fd05e9();
      }
      if (!file_exists("vsp-{$V0f14082c['log-gamecode']}.php"))
          Facf3bf61("error: unrecognized logType");
      require_once("vsp-{$V0f14082c['log-gamecode']}.php");
      include_once("pub/games/{$GLOBALS['cfg']['game']['name']}/skillsets/{$GLOBALS['cfg']['skillset']}/{$GLOBALS['cfg']['skillset']}-skill.php");
      if (!isset($GLOBALS['skillset'])) {
          echo "Skill Definitions not found.\n";
          echo " " . "pub/games/{$GLOBALS['cfg']['game']['name']}/skillsets/{$GLOBALS['cfg']['skillset']}/{$GLOBALS['cfg']['skillset']}-skill.php" . "\n";
      }
      $V21d8a920 = new F622a322a();
      $Vae2aeb93 = new F02ac4643();
      $V8db265ff = strtoupper($V0f14082c['log-gamecode']);
      eval("\$V3643b863 = new VSPParser$V8db265ff(\$V0f14082c['parser-options'],\$V21d8a920,\$Vae2aeb93);");

      $V3643b863->F1417ca90($V0f14082c['logfile']); // parsea el log
      $V21d8a920->prune_old_games();
      $V21d8a920->F215f9169(); // generate awards
      echo "\ngames: parsed: ".$V21d8a920->games_parsed."\tinserted: ".$V21d8a920->games_inserted."\t ignored: ".
              ($V21d8a920->games_parsed - $V21d8a920->games_inserted)."\n";
  }
  //change: insert ip2country table
  function populateIp2countryTable() {
      global $V9c1ebee8;
      echo "Populating ip to country table...";
      Fa10803e1();
      $sql = "DELETE FROM {$GLOBALS['cfg']['db']['table_prefix']}ip2country";
      $V9c1ebee8->Execute($sql);
      $band = false;
      $filename = realpath("sql/{$GLOBALS['cfg']['ip2country']['source']}");
      if (!file_exists($filename)) {
          $filename = $GLOBALS['cfg']['ip2country']['source'];
      }
      $countries = array();
      if ($file = fopen($filename, 'rb')) {
          while ($line = fgetcsv($file)) {
              $from_index = $GLOBALS['cfg']['ip2country']['columns']['ip_from'];
              $to_index = $GLOBALS['cfg']['ip2country']['columns']['ip_to'];
              $code_index = $GLOBALS['cfg']['ip2country']['columns']['country_code2'];
              $name_index = $GLOBALS['cfg']['ip2country']['columns']['country_name'];
              if (!isset($line[$from_index], $line[$to_index], $line[$code_index], $line[$name_index]) ||
                      !is_numeric($line[$from_index]) || !is_numeric($line[$to_index]) ||
                      strlen($line[$code_index]) != 2 || !$line[$name_index]) {
                  continue;
              }
              $band = true;
              $country_code = $V9c1ebee8->qstr($line[$code_index]);
              $country_name = $V9c1ebee8->qstr($line[$name_index]);
              if ($GLOBALS['cfg']['ip2country']['countries_only'] && array_key_exists($country_code, $countries)) {
                  continue;
              }
              $countries[$country_code] = true;
              $sql = "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}ip2country (ip_from, ip_to, country_code2, country_name)
                  VALUES ({$line[$from_index]}, {$line[$to_index]}, $country_code, $country_name)";
              $V9c1ebee8->Execute($sql);
          }
      }
      if (!$band) {
          echo "\n - error at populating ip to country table.\n";
          F56fd05e9();
      }
      // put some dummy locations
      if (!array_key_exists('XX', $countries)) {
          $sql = "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}ip2country (ip_from, ip_to, country_code2, country_name)
              VALUES (4294967295, 4294967295, 'XX', 'UNKNOWN LOCATION')";
          $V9c1ebee8->Execute($sql);
      }
      if (!array_key_exists('ZZ', $countries)) {
          $sql = "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}ip2country (ip_from, ip_to, country_code2, country_name)
              VALUES (4294967294, 4294967294, 'ZZ', 'UNKNOWN LOCATION')";
          $V9c1ebee8->Execute($sql);
      }
      echo "done\n";
      Fa10803e1();
  }
  //endchange
  //change: database-driven savestate
  function save_savestate(&$parser)
  {
      $parser->logdata["last_shutdown_end_position"] = ftell($parser->V50dffd37);
      $V9a52fe40 = fseek($parser->V50dffd37, -C7e731e80, SEEK_CUR);
      if ($V9a52fe40 == 0) {
          $parser->logdata['last_shutdown_hash'] = md5(fread($parser->V50dffd37, C7e731e80));
      } else {
          $V284073b9 = ftell($parser->V50dffd37);
          fseek($parser->V50dffd37, 0);
          $parser->logdata['last_shutdown_hash'] = md5(fread($parser->V50dffd37, $V284073b9));
      }
      //$V3b2eb2c1 = fopen('./logdata/savestate_' . Fff47f8ac($parser->Vd6d33a32) . '.inc.php', "wb");
      global $V9c1ebee8; // db
      $logfile = $V9c1ebee8->qstr(isset($parser->original_log) ? $parser->original_log : $parser->Vd6d33a32);
      $sql = "INSERT INTO {$GLOBALS['cfg']['db']['table_prefix']}savestate SET `logfile` = {$logfile}";
      $V3a2d7564 = $V9c1ebee8->Execute($sql);
      $value = $V9c1ebee8->qstr("\$this->logdata['last_shutdown_hash']='{$parser->logdata['last_shutdown_hash']}';\n".
            "\$this->logdata['last_shutdown_end_position']={$parser->logdata['last_shutdown_end_position']};");
      $sql = "UPDATE {$GLOBALS['cfg']['db']['table_prefix']}savestate SET `value` = {$value}";
      $V3a2d7564 = $V9c1ebee8->Execute($sql);
  }
  function check_savestate(&$parser)
  {
      echo "Verifying savestate\n";
      $V8774de0e = fopen($parser->Vd6d33a32, "rb");
      $V2843c763 = fseek($V8774de0e, $parser->logdata['last_shutdown_end_position']);
      if ($V2843c763 == 0) {
          $V9a52fe40 = fseek($V8774de0e, -C7e731e80, SEEK_CUR);
          if ($V9a52fe40 == 0) {
              $Vb9cc7f4b = fread($V8774de0e, C7e731e80);
          } else {
              $V284073b9 = ftell($V8774de0e);
              fseek($V8774de0e, 0);
              $Vb9cc7f4b = fread($V8774de0e, $V284073b9);
          }
          if (strcmp(md5($Vb9cc7f4b), $parser->logdata['last_shutdown_hash']) == 0) {
              echo " - Hash matched, resuming parsing from previous saved location in log file\n";
              fseek($parser->V50dffd37, $parser->logdata['last_shutdown_end_position']);
          } else {
              echo " - Hash did not match, assuming new log file\n";
              fseek($parser->V50dffd37, 0);
          }
      } else {
          echo " - Seek to prior location failed, assuming new log file\n";
          fseek($parser->V50dffd37, 0);
      }
      fclose($V8774de0e);
  }
  //endchange
  function F181dcd21()
  {
      require_once("./password.inc.php");
      if (strlen($vsp['password']) < 6) {
          echo "<HTML><BODY><PRE>Web Access to vsp.php is currently disabled.\nIf you want to enable web access to vsp.php,\nlook in password.inc.php under your vsp folder using a text editor(notepad).\nRead the ReadME.txt file for additional information.";
          F56fd05e9();
      }
      if (!isset($_POST['password'])) {
?> <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
 <HTML> <HEAD> <TITLE>vsp stats processor</TITLE> </HEAD> <BODY> <center> <PRE> <?php
          F4ca894df();
?>
 </PRE> <form action="vsp.php?mode=web" method="post"> <TABLE BORDER="0" CELLSPACING="5" CELLPADDING="0">
 <TR> <TD>&nbsp;</TD> <TD>[options] [-p parserOptions] [logFilename]</TD> </TR> <TR> <TD VALIGN="TOP">php vsp.php</TD>
 <TD><input size="50" type="text" name="V70e78261" /><BR>example: -l q3a-osp -p savestate 1 "games.log"</TD>
 </TR> </TABLE> <BR><BR> password = <input size=10 type=password name="password" /><BR><BR><input type="submit" value="Submit ( Process Stats )" />
 <BR><BR> </form> <PRE> <?php
          F4d7a92f8();
?> </PRE> </center> </BODY></HTML> <?php
          exit();
      }
      $V42c71341 = $_POST['password'];
      if (get_magic_quotes_gpc())
          $V42c71341 = stripslashes($V42c71341);
      if (md5($V42c71341) != md5($vsp['password'])) {
          echo "<HTML><BODY><PRE>Invalid password.\nFor the correct password, Look in password.inc.php under your vsp folder using a text editor(notepad).";
          F56fd05e9();
      }
  }
  function F5974bf41()
  {
      Fa10803e1();
      $GLOBALS['Vc4d98dbd'] = gettimeofday();
      set_time_limit(0);

      define("Ce5c65ec5", dirname(realpath(__FILE__)));
      if ((isset($_GET['mode']) && $_GET['mode'] == 'web') || isset($_SERVER['QUERY_STRING']) || isset($_SERVER['HTTP_HOST']) || isset($_SERVER['SERVER_PROTOCOL']) || isset($_SERVER['SERVER_SOFTWARE']) || isset($_SERVER['SERVER_NAME']))
          define("cIS_SHELL", 0);
      else
          define("cIS_SHELL", 1);
      define("cBIG_STRING_LENGTH", "1024");
      if (cIS_SHELL) {
          ini_set("html_errors", "0");
          chdir(Ce5c65ec5);
      } else {
          ini_set("html_errors", "1");
          F181dcd21();
          echo "<HTML><BODY><PRE>";
      }
      F4ca894df();
  }
  function F56fd05e9()
  {
      if (!cIS_SHELL)
          echo "</PRE></BODY></HTML>";
      exit();
  }
  function Fa3e3aec1() // final del programa
  {
      F4ca894df();
      $Vb1f08b98 = F3a57ff01($GLOBALS['Vc4d98dbd']);
      $Vcd0f6503 = floor($Vb1f08b98 / 60);
      $V89cef217 = $Vb1f08b98 % 60;
      echo "processed in {$Vcd0f6503}m {$V89cef217}s ({$Vb1f08b98}s)\n";
      if (!cIS_SHELL)
          echo "</PRE></BODY></HTML>";
      exit();
  }
  require_once('vutil.php');
  F5974bf41();
  F92261ca6();
  F68c076b3();
  Fa3e3aec1();
?>
