<?php
/* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */
define("cVERSION", "0.45-xp-1.1.2");
define(
  "cTITLE",
  /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ " ----------------------------------------------------------------------------- " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                     vsp stats processor (c) 2004-2005                         " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                               version " .
    constant("cVERSION") .
    "                                    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                 vsp by myrddin (myrddin8 AT gmail DOT com)                    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ " ----------------------------------------------------------------------------- " .
    "\r\n" .
    "\r\n"
);
define(
  "cUSAGE",
  /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "  ---------------------------------------------------------------------------  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "  Usage: php vsp.php [options] [-p parserOptions] [logFilename]                " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    [options]                                                                  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    ---------                                                                  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    -c                 specify config file (must be in pub/configs/)           " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    -l                 specify logType (gamecode-gametype)                     " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         logType:-                                             " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           client           Client Logs (Any game)             " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a              Quake 3 Arena (and q3 engine games)" .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-battle       Quake 3 Arena BattleMod            " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-cpma         Quake 3 Arena CPMA (Promode)       " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-freeze       Quake 3 Arena (U)FreezeTag etc.    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-lrctf        Quake 3 Arena Lokis Revenge CTF    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-osp          Quake 3 Arena OSP                  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-ra3          Quake 3 Arena Rocket Arena 3       " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-threewave    Quake 3 Arena Threewave            " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-ut           Quake 3 Arena UrbanTerror          " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           q3a-xp           Quake 3 Arena Excessive Plus       " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    -n                                                                         " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         No confirmation/prompts (for unattended runs etc.)    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    -a                 specify action                                          " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         perform a specific predefined action                  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         *make sure this is the last option specified!*        " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         [logFilename] is not needed if this option is used    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         action:-                                              " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           clear_db         Clear the database in config       " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            ie. Reset Stats                    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           gen_awards       Generate only the awards           " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           clear_savestate  Clears the savestate information   " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            for the specified log. If no log   " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            file is specified, then all the    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            savestate information will be      " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            cleared. Currently only works with " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            the q3a gamecode                   " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           pop_ip2country   Deletes the information of the     " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            ip2country table and populates it  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            from the CSV file specified in the " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            configuration                      " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                           prune_old_games  Removes all the detailed           " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                            information of old games           " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    -p [parserOptions]                                                         " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "       savestate       1                                                       " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         Enable savestate processing                           " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         Remembers previously scanned logs and events.         " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         If this option is enabled, VSP will remember the      " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         location in the log file where the last stats was     " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         parsed from. So the next time VSP is run with the     " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         savestate 1 option against the same log file, it will " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         start parsing the stats from the previous saved       " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         location.                                             " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         If you want VSP to forget this save state, then you   " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         have to delete the corresponding save state file from " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         the logdata/ folder. The name is in the format        " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         savestate_[special_Form_Of_Logfile_Name]              " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         Deleting that file and running VSP again with         " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         savestate 1 option will reparse the whole log again   " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         from the beginning. Also note that each logfile will  " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         have a separate save state file under the logdata     " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         folder. Do not edit/modify the savestate files! If    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                         you dont want it, just delete it.                     " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "       check ReadME or first few lines of a particular parser php for other    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "       valid options for that particular parser                                " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    [logFilename] could be an FTP link/url. Set FTP username/password in config" .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    [logFilename] may be a logDirectory for some games. ex:- *HalfLife*        " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "                                                                               " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "    Usage: php vsp.php [options] [-p parserOptions] [logFilename]              " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "  Example: php vsp.php -l q3a -p savestate 1 \"c:/quake iii arena/games.log\"    " .
    "\r\n" .
    /*__POBS_EXCLUDE__*/ "  ---------------------------------------------------------------------------  " .
    "\r\n" .
    "\r\n"
);

class PlayerSkillProcessor
{
  var $gameData; // formerly $Vf273a653
  var $gameCounter = 0; // formerly $V56cacbad
  var $roundCounter = 0; // formerly $Vb77eef69
  var $teamCounts; // formerly $V282dbc1d
  var $playerStats; // formerly $V75125d17
  var $translationData; // formerly $V42dfa3a4

  function getWeaponSkillFactor($weaponEvent)
  {
    // devuelve el skill del arma
    if (isset($GLOBALS["skillset"]["weapon_factor"][$weaponEvent])) {
      return $GLOBALS["skillset"]["weapon_factor"][$weaponEvent];
    }
    return 0.0;
  }

  function getEventSkillFactor($eventName)
  {
    // devuelve el skill del evento
    if (isset($GLOBALS["skillset"]["event"][$eventName])) {
      return $GLOBALS["skillset"]["event"][$eventName];
    }
    return 0.0;
  }

  function getPlayerSkill($playerID)
  {
    // obtiene el skill del player
    global $db;
    $playerID = secureString($playerID); // Add this line
    $sql = "select skill from {$GLOBALS['cfg']['db']['table_prefix']}playerprofile where playerID=$playerID";
    $rs = $db->Execute($sql);
    if ($rs and !$rs->EOF) {
      //change: skill puede bajar de 1600
      //if ($rs->fields[0] >= 1600.00) // oO parece que aunque el skill pueda bajar de 1600, para las fórmulas se ocupa 1600 como el mínimo
      //endchange
      return (float) str_replace(",", ".", $rs->fields[0]);
    } //change: camel fix
    return $GLOBALS["skillset"]["defaults"]["value"];
  }

  function updatePlayerDataField($action, $playerID, $dataName, $value)
  {
    // hace más cosas con el player, no entiendo muy bien qué
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    if (!strcmp($action, "rep")) {
      $this->playerStats[$playerID]["data"][$dataName][0] = $action;
      $this->playerStats[$playerID]["data"][$dataName][1] = $value;
    } elseif (!strcmp($action, "inc")) {
      $this->playerStats[$playerID]["data"][$dataName][0] = $action;
      if (isset($this->playerStats[$playerID]["data"][$dataName][1])) {
        $this->playerStats[$playerID]["data"][$dataName][1] += $value;
      } else {
        $this->playerStats[$playerID]["data"][$dataName][1] = $value;
      }
    } elseif (!strcmp($action, "avg")) {
      // parece cambiar
      $this->playerStats[$playerID]["data"][$dataName][0] = $action;
      if (isset($this->playerStats[$playerID]["data"][$dataName][1])) {
        $this->playerStats[$playerID]["data"][$dataName][1] = round(
          ($value + $this->playerStats[$playerID]["data"][$dataName][1]) / 2.0,
          2
        );
      } else {
        $this->playerStats[$playerID]["data"][$dataName][1] = $value;
      }
    } elseif (!strcmp($action, "sto")) {
      // parece que hace modificaciones que luego serán guardadas en la base de datos
      $this->playerStats[$playerID]["data"][$dataName][0] = $action;
      $index = count($this->playerStats[$playerID]["data"][$dataName]);
      $this->playerStats[$playerID]["data"][$dataName][$index] = $value;
    } elseif (!strcmp($action, "sto_uni")) {
      $this->playerStats[$playerID]["data"][$dataName][0] = $action;
      if (!isset($this->playerStats[$playerID]["data"][$dataName][1])) {
        $this->playerStats[$playerID]["data"][$dataName][1] = $value;
      } else {
        $index = count($this->playerStats[$playerID]["data"][$dataName]);
        unset($this->playerStats[$playerID]["data"][$dataName][0]);
        if (
          array_search(
            $value,
            $this->playerStats[$playerID]["data"][$dataName]
          ) === false
        ) {
          $this->playerStats[$playerID]["data"][$dataName][$index] = $value;
        }
        $this->playerStats[$playerID]["data"][$dataName][0] = $action;
      }
    }
  }

  function resolvePlayerIDConflict($oldPlayerID, $newPlayerID)
  {
    if (!isset($this->playerStats[$oldPlayerID])) {
      return;
    }
    if (isset($this->playerStats[$newPlayerID])) {
      debugPrint("PlayerID Conflict Detected\n");
    }
    foreach ($this->playerStats as $pid => $pdata) {
      if (!isset($pdata["events"])) {
        continue;
      }
      foreach ($pdata["events"] as $round => $roundEvents) {
        foreach ($roundEvents as $team => $teamEvents) {
          foreach ($teamEvents as $role => $eventData) {
            foreach ($eventData as $eventKey => $eventValues) {
              if (!isset($eventValues["2D"])) {
                continue;
              }
              foreach ($eventValues["2D"] as $subKey => $subEvents) {
                foreach ($subEvents as $subRole => $subEventData) {
                  foreach ($subEventData as $conflictID => $value) {
                    if (!isset($conflictID)) {
                      // redundant check, kept as in original
                      continue;
                    }
                    if (strcmp($conflictID, $oldPlayerID) == 0) {
                      $this->playerStats[$pid]["events"][$round][$team][$role][
                        $eventKey
                      ]["2D"][$subKey][$subRole][$newPlayerID] = $value;
                      unset(
                        $this->playerStats[$pid]["events"][$round][$team][
                          $role
                        ][$eventKey]["2D"][$subKey][$subRole][$oldPlayerID]
                      );
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    $this->playerStats[$newPlayerID] = $this->playerStats[$oldPlayerID];
    unset($this->playerStats[$oldPlayerID]);
  }

  function updatePlayerName($playerID, $newName)
  {
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    $this->playerStats[$playerID]["profile"]["name"] = $newName;
  }

  function setPlayerIcon($playerID, $icon)
  {
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    $this->playerStats[$playerID]["vdata"]["icon"][0] = "";
    $this->playerStats[$playerID]["vdata"]["icon"][1] = "$icon";
  }

  function setPlayerRole($playerID, $role)
  {
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    $this->playerStats[$playerID]["vdata"]["role"][0] = "";
    $this->playerStats[$playerID]["vdata"]["role"][1] = "$role";
  }

  function updatePlayerTeam($playerID, $team)
  {
    // actualiza el equipo en el que el player está
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    $this->playerStats[$playerID]["vdata"]["team"][0] = "";
    $this->playerStats[$playerID]["vdata"]["team"][1] = "$team";
    if (!isset($this->teamCounts[$team])) {
      $this->teamCounts[$team] = "1";
    }
  }

  function ensureTeamCount($team)
  {
    if (!isset($this->teamCounts[$team])) {
      $this->teamCounts[$team] = "1";
    }
  }

  function incrementRoundCounter()
  {
    $this->roundCounter++;
  }

  function doNothing() {}

  function setGameData($key, $value)
  {
    // Asigna datos de juego; keys beginning with "_v_" are always stored.
    if (preg_match("/^_v_/", $key, $match)) {
      $this->gameData[$key] = $value;
    } elseif (
      isset($GLOBALS["cfg"]["data_filter"]["gamedata"][""]) &&
      preg_match($GLOBALS["cfg"]["data_filter"]["gamedata"][""], $key, $match)
    ) {
      return;
    } else {
      $this->gameData[$key] = $value;
    }
  }

  function initializePlayerData($playerID, $playerName, $ip = "", $tld = "")
  {
    // inicializa datos del jugador
    foreach ($GLOBALS["player_ban_list"] as $banPattern) {
      // saltar si el player está en la lista de bans
      if (preg_match($banPattern, $playerID, $match)) {
        return;
      }
    }
    if (isset($this->playerStats[$playerID])) {
      // si el cliente ya existe en el juego
      return;
    }
    $this->playerStats[$playerID]["v"]["original_id"] = $playerID;
    $this->playerStats[$playerID]["profile"]["name"] = "$playerName";
    //change:
    $this->playerStats[$playerID]["profile"]["ip"] = $ip;
    $this->playerStats[$playerID]["profile"]["tld"] = $tld;
    $this->playerStats[$playerID]["profile"]["org_skill"] = $this->playerStats[
      $playerID
    ]["profile"]["skill"] = $this->getPlayerSkill($playerID); // skill
    //endchange
    $this->playerStats[$playerID]["profile"]["kills"] = 0;
    $this->playerStats[$playerID]["profile"]["deaths"] = 0;
    $this->playerStats[$playerID]["profile"]["kill_streak"] = 0;
    $this->playerStats[$playerID]["profile"]["kill_streak_counter"] = 0;
    $this->playerStats[$playerID]["profile"]["death_streak"] = 0;
    $this->playerStats[$playerID]["profile"]["death_streak_counter"] = 0;
    $this->playerStats[$playerID]["data"] = [];
    $this->playerStats[$playerID]["vdata"]["team"][0] = "";
    $this->playerStats[$playerID]["vdata"]["team"][1] = "";
    $this->playerStats[$playerID]["vdata"]["role"][0] = "";
    $this->playerStats[$playerID]["vdata"]["role"][1] = "";
    $this->updatePlayerDataField("sto", $playerID, "alias", $playerName);
  }

  function updatePlayerQuote($playerID, $quote)
  {
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    if (preg_match("/\d/", $quote) || preg_match("/@/", $quote)) {
      return;
    }
    $this->playerStats[$playerID]["vdata"]["quote"][0] = "rep";
    if (!isset($this->playerStats[$playerID]["vdata"]["quote"][1])) {
      $this->playerStats[$playerID]["vdata"]["quote"][1] = "$quote";
    } elseif (strlen($this->playerStats[$playerID]["vdata"]["quote"][1]) < 5) {
      $this->playerStats[$playerID]["vdata"]["quote"][1] = "$quote";
    } elseif (strlen($quote) > 25) {
      $this->playerStats[$playerID]["vdata"]["quote"][1] = "$quote";
    } elseif (mt_rand(1, 10) <= 5) {
      $this->playerStats[$playerID]["vdata"]["quote"][1] = "$quote";
    }
  }

  function startGameAnalysis()
  {
    // muestra mensaje de inicio de análisis de juego
    $this->clearProcessorData(); // limpieza de variables

    $this->gameCounter++;
    echo "Analyzing game " . sprintf("%04d ", $this->gameCounter);
    flushOutputBuffers();
    $this->roundCounter = 0;
  }

  function updatePlayerStreaks()
  {
    // actualiza los streaks de los players
    if (isset($this->playerStats)) {
      foreach ($this->playerStats as $playerID => $pdata) {
        if (
          $this->playerStats[$playerID]["profile"]["death_streak_counter"] >
          $this->playerStats[$playerID]["profile"]["death_streak"]
        ) {
          $this->playerStats[$playerID]["profile"]["death_streak"] =
            $this->playerStats[$playerID]["profile"]["death_streak_counter"];
        }
        if (
          $this->playerStats[$playerID]["profile"]["kill_streak_counter"] >
          $this->playerStats[$playerID]["profile"]["kill_streak"]
        ) {
          $this->playerStats[$playerID]["profile"]["kill_streak"] =
            $this->playerStats[$playerID]["profile"]["kill_streak_counter"];
        }
      }
    }
  }

  function clearPlayerEvents()
  {
    foreach ($this->playerStats as $playerID => $pdata) {
      if (isset($this->playerStats[$playerID]["events"])) {
        unset($this->playerStats[$playerID]["events"]);
      }
      //change:
      $this->playerStats[$playerID]["profile"][
        "org_skill"
      ] = $this->playerStats[$playerID]["profile"][
        "skill"
      ] = $this->getPlayerSkill($playerID);
      //endchange
      $this->playerStats[$playerID]["profile"]["kills"] = 0;
      $this->playerStats[$playerID]["profile"]["deaths"] = 0;
      $this->playerStats[$playerID]["profile"]["kill_streak"] = 0;
      $this->playerStats[$playerID]["profile"]["kill_streak_counter"] = 0;
      $this->playerStats[$playerID]["profile"]["death_streak"] = 0;
      $this->playerStats[$playerID]["profile"]["death_streak_counter"] = 0;
      $this->playerStats[$playerID]["data"] = [];
    }
  }

  function clearProcessorData()
  {
    // limpieza de variables
    if (isset($this->playerStats)) {
      unset($this->playerStats);
    }
    if (isset($this->gameData)) {
      unset($this->gameData);
    }
    if (isset($this->teamCounts)) {
      unset($this->teamCounts);
    }
    if (isset($this->translationData)) {
      unset($this->translationData);
    }
  }

  function getPlayerStats()
  {
    // devuelve la información de los jugadores
    if (isset($this->playerStats)) {
      return $this->playerStats;
    }
    return false;
  }

  function getGameData()
  {
    // devuelve la información del juego
    if (isset($this->gameData)) {
      return $this->gameData;
    }
    return false;
  }

  //change: ELO based skill change
  function updateTeamEventSkill($team, $eventName, $value)
  {
    // team events launcher
    $eventPrefix = "";
    $eventFull = $eventName;
    if (preg_match("/^(.*)\\|(.+)/", $eventFull, $match)) {
      $eventPrefix = $match[1];
      $eventFull = $match[2];
    }
    if (
      isset($GLOBALS["cfg"]["data_filter"]["events"][$eventPrefix]) &&
      preg_match(
        $GLOBALS["cfg"]["data_filter"]["events"][$eventPrefix],
        $eventFull,
        $match
      )
    ) {
      return;
    }
    if (!$this->playerStats) {
      return;
    }

    $variance = $GLOBALS["skillset"]["defaults"]["variance"];
    $players = ["add" => [], "substract" => []];
    $skills = ["add" => 0.0, "substract" => 0.0];
    foreach ($this->playerStats as $pid => $pdata) {
      // itera sobre todos los players
      $role = $this->playerStats[$pid]["vdata"]["role"][1];
      $inTeam = false;
      if (
        isset($this->playerStats[$pid]["events"][$this->roundCounter][$team])
      ) {
        $inTeam = true;
        // agrega el evento
        if (
          !isset(
            $this->playerStats[$pid]["events"][$this->roundCounter][$team][
              $role
            ]["1D"][$eventName]
          )
        ) {
          $this->playerStats[$pid]["events"][$this->roundCounter][$team][$role][
            "1D"
          ][$eventName] = 0;
        }
        $this->playerStats[$pid]["events"][$this->roundCounter][$team][$role][
          "1D"
        ][$eventName] += $value;
        $players["add"][] = $pid;
        $skills["add"] += $this->playerStats[$pid]["profile"]["org_skill"];
      }
      if (
        !$inTeam ||
        (isset($this->playerStats[$pid]["events"][$this->roundCounter]) &&
          count($this->playerStats[$pid]["events"][$this->roundCounter]) > 1)
      ) {
        // agrega el player a los substract
        $players["substract"][] = $pid;
        $skills["substract"] +=
          $this->playerStats[$pid]["profile"]["org_skill"];
      }
    }
    $n = [
      "add" => count($players["add"]),
      "substract" => count($players["substract"]),
    ];
    $event_factor = $this->getEventSkillFactor($eventName);
    if ($event_factor && $n["add"] && $n["substract"]) {
      $max_n = max($n["add"], $n["substract"]);
      $av_skills = [
        "substract" => $skills["substract"] / $max_n,
        "add" => $skills["add"] / $max_n,
      ];
      $probAddWins =
        1 /
        (1 +
          exp(
            (($av_skills["substract"] - $av_skills["add"]) *
              ($event_factor > 0 ? 1 : -1)) /
              ($variance * $max_n)
          ));
      $factor =
        (1 - $probAddWins) *
        $value *
        $event_factor *
        min($n["add"], $n["substract"]);
      $prob_array = ["add" => [], "substract" => []];
      $prob_sum = ["add" => 0.0, "substract" => 0.0];
      foreach ($players as $type => $plist) {
        $enemy_avg =
          $type == "add" ? $av_skills["substract"] : $av_skills["add"];
        foreach ($plist as $index => $id) {
          $skill = $this->playerStats[$id]["profile"]["org_skill"];
          $prob_win =
            1 /
            (1 +
              exp(
                (($enemy_avg - $skill) * ($event_factor > 0 ? 1 : -1)) /
                  $variance
              ));
          $prob_array[$type][$index] =
            $type == "add" ? 1 - $prob_win : $prob_win;
          $prob_sum[$type] += $prob_array[$type][$index];
        }
      }
      foreach ($players as $type => $plist) {
        $negative = $type == "add" ? 1.0 : -1.0;
        foreach ($plist as $index => $id) {
          $player_factor = $prob_array[$type][$index] / $prob_sum[$type];
          $this->playerStats[$id]["profile"]["skill"] +=
            $negative * $factor * $player_factor;
        }
      }
    }
  }
  //endchange

  //change: ELO based skills change
  function updatePlayerEvent(
    $playerID,
    $eventName,
    $value,
    &$clients_info = false
  ) {
    // default events launcher
    //change: team control
    if ($clients_info) {
      $client_id = $playerID;
      $playerID = $clients_info[$client_id]["id"];
    }
    //endchange
    if (!isset($this->playerStats[$playerID])) {
      return;
    }
    $eventPrefix = "";
    $eventFull = $eventName;
    if (preg_match("/^(.*)\\|(.+)/", $eventFull, $match)) {
      $eventPrefix = $match[1];
      $eventFull = $match[2];
    }
    if (
      isset($GLOBALS["cfg"]["data_filter"]["events"][$eventPrefix]) &&
      preg_match(
        $GLOBALS["cfg"]["data_filter"]["events"][$eventPrefix],
        $eventFull,
        $match
      )
    ) {
      return;
    }
    //change: team control
    $team = $clients_info
      ? $this->players_team[$client_id]["team"]
      : $this->playerStats[$playerID]["vdata"]["team"][1]; // team
    //endchange
    $role = $this->playerStats[$playerID]["vdata"]["role"][1];
    if (
      !isset(
        $this->playerStats[$playerID]["events"][$this->roundCounter][$team][
          $role
        ]["1D"][$eventName]
      )
    ) {
      $this->playerStats[$playerID]["events"][$this->roundCounter][$team][
        $role
      ]["1D"][$eventName] = 0;
    }
    $this->playerStats[$playerID]["events"][$this->roundCounter][$team][$role][
      "1D"
    ][$eventName] += $value;

    $this->event_skills_update(
      $clients_info ? $client_id : $playerID,
      $eventName,
      $value,
      $clients_info
    );
  }
  //endchange

  function updateAccuracyEvent(
    $playerID1,
    $playerID2,
    $eventName,
    $value,
    &$clients_info = false
  ) {
    // accuracy events
    //change: client ids instead of ids
    if ($clients_info) {
      $first_id = $playerID1;
      $playerID1 = $clients_info[$first_id]["id"];
      $second_id = $playerID2;
      $playerID2 = $clients_info[$second_id]["id"];
    }
    //endchange
    if (
      !isset($this->playerStats[$playerID1]) ||
      !isset($this->playerStats[$playerID2])
    ) {
      return;
    }
    //change: team control
    $team1 = $clients_info
      ? $this->players_team[$first_id]["team"]
      : $this->playerStats[$playerID1]["vdata"]["team"][1];
    $role1 = $this->playerStats[$playerID1]["vdata"]["role"][1];
    $team2 = $clients_info
      ? $this->players_team[$second_id]["team"]
      : $this->playerStats[$playerID2]["vdata"]["team"][1];
    $quotedRole2 = $this->playerStats[$playerID2]["vdata"]["role"][1];
    //endchange
    if (
      isset(
        $this->playerStats[$playerID1]["events"][$this->roundCounter][$team1][
          $role1
        ]["2D"][$team2][$quotedRole2][$playerID2][$eventName]
      )
    ) {
      $this->playerStats[$playerID1]["events"][$this->roundCounter][$team1][
        $role1
      ]["2D"][$team2][$quotedRole2][$playerID2][$eventName] += $value;
    } else {
      $this->playerStats[$playerID1]["events"][$this->roundCounter][$team1][
        $role1
      ]["2D"][$team2][$quotedRole2][$playerID2][$eventName] = $value;
    }
    //change: launch event
    if ($clients_info) {
      $playerID1 = $first_id;
      $playerID2 = $second_id;
    }
    if ($playerID1 == $playerID2) {
      $this->event_skills_update(
        $playerID1,
        $eventName,
        $value,
        $clients_info,
        false
      );
    } else {
      //TODO: specific player accuracy
    }
    //endchange
  }

  function processKillEvent(
    $killer,
    $victim,
    $weaponEvent,
    &$clients_info = false
  ) {
    // kill events
    //change: skill configuration
    $variance = $GLOBALS["skillset"]["defaults"]["variance"];
    //endchange
    //change: team support
    if ($clients_info) {
      $killer_id = $killer;
      $killer = $clients_info[$killer_id]["id"];
      $victim_id = $victim;
      $victim = $clients_info[$victim_id]["id"];
    }
    //endchange
    if (
      !isset($this->playerStats[$killer]) ||
      !isset($this->playerStats[$victim])
    ) {
      return;
    }

    //change: team control
    $teamKiller = $clients_info
      ? $this->players_team[$killer_id]["team"]
      : $this->playerStats[$killer]["vdata"]["team"][1];
    $roleKiller = $this->playerStats[$killer]["vdata"]["role"][1];
    $teamVictim = $clients_info
      ? $this->players_team[$victim_id]["team"]
      : $this->playerStats[$victim]["vdata"]["team"][1];
    $roleVictim = $this->playerStats[$victim]["vdata"]["role"][1];
    //endchange

    if (
      $clients_info ? $killer_id != $victim_id : strcmp($killer, $victim) != 0
    ) {
      // si no es suicidio
      //change: team control
      if (
        count($this->teamCounts) > 1 &&
        strcmp($teamKiller, $teamVictim) == 0
      ) {
        // teamkill
        if (
          !isset(
            $this->playerStats[$killer]["events"][$this->roundCounter][
              $teamKiller
            ][$roleKiller]["2D"][$teamVictim][$roleVictim][$victim][
              "teamkill|$weaponEvent"
            ]
          )
        ) {
          $this->playerStats[$killer]["events"][$this->roundCounter][
            $teamKiller
          ][$roleKiller]["2D"][$teamVictim][$roleVictim][$victim][
            "teamkill|$weaponEvent"
          ] = 0;
        }
        $this->playerStats[$killer]["events"][$this->roundCounter][$teamKiller][
          $roleKiller
        ]["2D"][$teamVictim][$roleVictim][$victim]["teamkill|$weaponEvent"]++;
        //change: launch event
        $this->event_skills_update(
          $clients_info ? $killer_id : $killer,
          "teamkill|$weaponEvent",
          1,
          $clients_info
        );
        //endchange
      } else {
        //change: ELO system
        $event_factor = $this->getWeaponSkillFactor($weaponEvent);
        $killer_skill = $this->playerStats[$killer]["profile"]["skill"];
        $victim_skill = $this->playerStats[$victim]["profile"]["skill"];
        $prob_killer_wins =
          1 /
          (1 +
            exp(
              (($victim_skill - $killer_skill) * ($event_factor > 0 ? 1 : -1)) /
                $variance
            ));
        //$deltaSkill = $this->playerStats[$victim]['profile']['skill'] * $GLOBALS['skillset']['fraction']['value'];
        $deltaSkill = 1 - $prob_killer_wins;
        //endchange
        if (!isset($this->translationData["first killer"])) {
          // first killer
          //change: team control
          $this->updatePlayerEvent(
            $clients_info ? $killer_id : $killer,
            "first killer",
            1,
            $clients_info
          );
          $this->updatePlayerEvent(
            $clients_info ? $victim_id : $victim,
            "first victim",
            1,
            $clients_info
          );
          //endchange
          $this->translationData["first killer"] = 1;
        }
        if (
          !isset(
            $this->playerStats[$killer]["events"][$this->roundCounter][
              $teamKiller
            ][$roleKiller]["2D"][$teamVictim][$roleVictim][$victim][
              "kill|$weaponEvent"
            ]
          )
        ) {
          $this->playerStats[$killer]["events"][$this->roundCounter][
            $teamKiller
          ][$roleKiller]["2D"][$teamVictim][$roleVictim][$victim][
            "kill|$weaponEvent"
          ] = 0;
        }
        $this->playerStats[$killer]["events"][$this->roundCounter][$teamKiller][
          $roleKiller
        ]["2D"][$teamVictim][$roleVictim][$victim]["kill|$weaponEvent"]++;
        $this->playerStats[$killer]["profile"]["kills"]++;
        $this->playerStats[$killer]["profile"]["kill_streak_counter"]++;
        if (
          $this->playerStats[$killer]["profile"]["death_streak_counter"] >
          $this->playerStats[$killer]["profile"]["death_streak"]
        ) {
          $this->playerStats[$killer]["profile"]["death_streak"] =
            $this->playerStats[$killer]["profile"]["death_streak_counter"];
        }
        $this->playerStats[$killer]["profile"]["death_streak_counter"] = 0;
        $this->playerStats[$killer]["profile"]["skill"] +=
          $event_factor * $deltaSkill; // aumenta el skill del killer
        $this->playerStats[$victim]["profile"]["skill"] -=
          $event_factor * $deltaSkill; // disminuye el skill del fragged
      }
    } else {
      if (
        !isset(
          $this->playerStats[$killer]["events"][$this->roundCounter][
            $teamKiller
          ][$roleKiller]["2D"][$teamVictim][$roleVictim][$victim][
            "suicide|$weaponEvent"
          ]
        )
      ) {
        $this->playerStats[$killer]["events"][$this->roundCounter][$teamKiller][
          $roleKiller
        ]["2D"][$teamVictim][$roleVictim][$victim]["suicide|$weaponEvent"] = 0;
      }
      $this->playerStats[$killer]["events"][$this->roundCounter][$teamKiller][
        $roleKiller
      ]["2D"][$teamVictim][$roleVictim][$victim]["suicide|$weaponEvent"]++;
      //change: launch event
      $this->event_skills_update(
        $clients_info ? $killer_id : $killer,
        "suicide|$weaponEvent",
        1,
        $clients_info
      );
      //endchange
    }
    $this->playerStats[$victim]["profile"]["deaths"]++;
    $this->playerStats[$victim]["profile"]["death_streak_counter"]++;
    if (
      $this->playerStats[$victim]["profile"]["kill_streak_counter"] >
      $this->playerStats[$victim]["profile"]["kill_streak"]
    ) {
      $this->playerStats[$victim]["profile"]["kill_streak"] =
        $this->playerStats[$victim]["profile"]["kill_streak_counter"];
    }
    $this->playerStats[$victim]["profile"]["kill_streak_counter"] = 0;
  }

  //change: add event
  function event_skills_update(
    $playerID,
    $eventName,
    $value,
    &$clients_info = false,
    $team_penalty = true
  ) {
    ////change: team control
    if ($clients_info) {
      $client_id = $playerID;
      $playerID = $clients_info[$client_id]["id"];
    }
    //endchange
    $team = $clients_info
      ? $this->players_team[$client_id]["team"]
      : $this->playerStats[$playerID]["vdata"]["team"][1]; // team
    $event_factor = $this->getEventSkillFactor($eventName);
    if (!$event_factor) {
      return;
    }
    $player_skill = $this->playerStats[$playerID]["profile"]["skill"];
    $variance = $GLOBALS["skillset"]["defaults"]["variance"];
    $players = [];
    $skills = 0.0;
    $teamplayers = 0;
    if ($clients_info) {
      foreach ($this->players_team as $cl_id => $arr) {
        if ($arr["connected"] && isset($clients_info[$cl_id])) {
          if ($arr["team"] == $team) {
            // mismo equipo
            $teamplayers++;
          } else {
            // equipo contrario
            $id = $clients_info[$cl_id]["id"];
            if (!isset($this->playerStats[$id])) {
              continue;
            }
            $players[] = $id;
            $skills += $this->playerStats[$id]["profile"]["skill"];
          }
        }
      }
    } else {
      foreach ($this->playerStats as $pid => $pdata) {
        // itera sobre todos los players
        if ($this->playerStats[$pid]["vdata"]["team"][1] == $team) {
          // mismo equipo
          $teamplayers++;
        } else {
          // equipo contrario
          // agrega el player a la lista
          $players[] = $pid;
          $skills += $this->playerStats[$pid]["profile"]["skill"];
        }
      }
    }
    $n = count($players);
    if ($n && $teamplayers) {
      $av_skills = $skills / $n;
      $prob_win =
        1 /
        (1 +
          exp(
            (($av_skills - $player_skill) * ($event_factor > 0 ? 1 : -1)) /
              $variance
          ));
      $factor = (1 - $prob_win) * $value * $event_factor;
      if ($team_penalty) {
        $team_factor =
          $event_factor > 0 ? $n / $teamplayers : $teamplayers / $n;
        $factor *= $team_factor > 1 ? 1 : $team_factor;
      }
      $prob_array = [];
      $prob_sum = 0.0;
      foreach ($players as $id) {
        $skill = $this->playerStats[$id]["profile"]["skill"];
        $prob_array[$id] =
          1 /
          (1 +
            exp(
              (($player_skill - $skill) * ($event_factor > 0 ? 1 : -1)) /
                $variance
            ));
        $prob_sum += $prob_array[$id];
      }
      foreach ($players as $id) {
        $player_factor = $prob_array[$id] / $prob_sum;
        $this->playerStats[$id]["profile"]["skill"] -= $factor * $player_factor;
      }
      $this->playerStats[$playerID]["profile"]["skill"] += $factor; // cambia el skill
    }
  }
  //endchange

  //change: launch skill events
  function launch_skill_events()
  {
    if (isset($this->playerStats)) {
      foreach ($this->playerStats as $playerID => $pdata) {
        if (isset($this->playerStats[$playerID]["profile"]["org_skill"])) {
          $variation =
            $this->playerStats[$playerID]["profile"]["skill"] -
            $this->playerStats[$playerID]["profile"]["org_skill"];
          $this->updatePlayerEvent(
            $playerID,
            "skill|begins",
            round($this->playerStats[$playerID]["profile"]["org_skill"], 2)
          );
          $this->updatePlayerEvent(
            $playerID,
            "skill|" . ($variation > 0 ? "wins" : "loses"),
            round($variation, 2)
          );
          $this->updatePlayerEvent(
            $playerID,
            "skill|ends",
            round($this->playerStats[$playerID]["profile"]["skill"], 2)
          );
        }
      }
    }
  }
  //endchange
}
class GameDataProcessor
{
  var $games_parsed = 0;
  var $games_inserted = 0;
  var $playerStats;
  var $gameID;
  var $gameData;
  function GameDataProcessor() {}
  function purgeEmptyOneDEvents()
  {
    foreach ($this->playerStats as $playerID => $playerData) {
      foreach ($playerData as $key => $value) {
        if (!strcmp($key, "events")) {
          foreach ($value as $round => $roundEvents) {
            foreach ($roundEvents as $team => $teamEvents) {
              foreach ($teamEvents as $role => $eventGroup) {
                foreach ($eventGroup as $eventName => $eventData) {
                  $flag = 0;
                  if (
                    sizeof($eventGroup["1D"]) <= 1 &&
                    $this->playerStats[$playerID]["profile"]["deaths"] == 0
                  ) {
                    $flag = 1;
                    if (array_key_exists("2D", $eventGroup)) {
                      $flag = 0;
                    }
                  }
                  if ($flag == 1) {
                    unset(
                      $this->playerStats[$playerID]["events"][$round][$team][
                        $role
                      ]
                    );
                  }
                }
              }
            }
          }
        }
      }
    }
  }
  function purifyDatabaseEvents()
  {
    global $db;
    $sql = "select count(*) from {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata2d";
    $rs = $db->Execute($sql);
    if ($rs && $rs->fields[0] > 10000) {
      $tablesToPurge[
        "{$GLOBALS["cfg"]["db"]["table_prefix"]}" . "eventdata1d"
      ] = 1;
      $tablesToPurge[
        "{$GLOBALS["cfg"]["db"]["table_prefix"]}" . "eventdata2d"
      ] = 1;
      foreach ($tablesToPurge as $table => $dummy) {
        echo "purifyDb: checking for probable bad entries from $table\n";
        $sql = "select eventCategory, eventName, count(*) as c from $table group by eventCategory,eventName having c<3";

        $rs = $db->Execute($sql);
        if ($rs && !$rs->EOF) {
          echo "purifyDb: removing probable bad entries from $table\n";
          do {
            $delSql =
              "delete from $table where eventCategory=" .
              $db->qstr($rs->fields[0]) .
              " AND eventName=" .
              $db->qstr($rs->fields[1]);
            $res = $db->Execute($delSql);
            if ($res) {
              echo "purifyDb: removed: category-{$rs->fields[0]}, name-{$rs->fields[1]}\n";
            }
          } while ($rs->MoveNext() && !$rs->EOF);
        }
        echo "purifyDb: done\n";
      }
    }
  }
  function generateAwards()
  {
    // genera los awards
    $tp = $GLOBALS["cfg"]["db"]["table_prefix"];
    global $db;
    //change: add player_exclude_list support
    foreach ($GLOBALS["player_exclude_list"] as $key => $value) {
      $GLOBALS["player_exclude_list"][$key] = $db->qstr($value);
    }
    //endchange
    //change: exclude inactive players
    $last_update = false;
    if ($GLOBALS["cfg"]["display"]["days_inactivity"]) {
      // use timestamp from latest game
      $sql = "select max(timeStart) from {$GLOBALS["cfg"]["db"]["table_prefix"]}gameprofile";
      $rs = $db->execute($sql);
      if ($rs && !$rs->EOF) {
        $last_update = "'{$rs->fields[0]}'";
      } else {
        // use timestamp from latest update
        $sql = "select value from {$GLOBALS["cfg"]["db"]["table_prefix"]}gamedata
                    where name='last_update_time'";
        $rs = $db->execute($sql);
        if ($rs && !$rs->EOF) {
          $last_update = "'{$rs->fields[0]}'";
        } else {
          // use current timestamp
          $last_update = "CURRENT_TIMESTAMP";
        }
      }
    }
    //endchange
    include_once "pub/games/{$GLOBALS["cfg"]["game"]["name"]}/awardsets/{$GLOBALS["cfg"]["awardset"]}/{$GLOBALS["cfg"]["awardset"]}-awards.php";
    @include_once "pub/games/{$GLOBALS["cfg"]["game"]["name"]}/weaponsets/{$GLOBALS["cfg"]["weaponset"]}/{$GLOBALS["cfg"]["weaponset"]}-weapons.php";
    echo "\ngenerateAwards: Generating Awards...";
    flushOutputBuffers();
    if (!isset($GLOBALS["awardset"])) {
      echo "Award Definitions not found.\n";
      echo " " .
        "pub/games/{$GLOBALS["cfg"]["game"]["name"]}/awardsets/{$GLOBALS["cfg"]["awardset"]}/{$GLOBALS["cfg"]["awardset"]}-awards.php\n";
      return;
    }
    $awardset_expanded = [];
    $sql = "select distinct eventName from {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata2d where eventCategory='kill' order by eventName";
    $rs = $db->Execute($sql);
    foreach ($GLOBALS["awardset"] as $awardKey => $awardData) {
      if (strstr($awardKey, "_v_weapons")) {
        if ($rs) {
          $rs->MoveFirst();
          do {
            $expandedKey = preg_replace(
              "/_v_weapons/",
              $rs->fields[0],
              $awardKey
            );
            if (isset($GLOBALS["awardset"][$awardKey]["name"])) {
              if (isset($weaponset[$rs->fields[0]]["name"])) {
                $awardset_expanded[$expandedKey]["name"] = preg_replace(
                  "/_v_weapons/",
                  $weaponset[$rs->fields[0]]["name"],
                  $GLOBALS["awardset"][$awardKey]["name"]
                );
              } else {
                $awardset_expanded[$expandedKey]["name"] = preg_replace(
                  "/_v_weapons/",
                  ucfirst(strtolower(str_replace("_", " ", $rs->fields[0]))),
                  $GLOBALS["awardset"][$awardKey]["name"]
                );
              }
            }
            if (isset($GLOBALS["awardset"][$awardKey]["image"])) {
              $awardset_expanded[$expandedKey]["image"] = preg_replace(
                "/_v_weapons/",
                $rs->fields[0],
                $GLOBALS["awardset"][$awardKey]["image"]
              );
            }
            if (isset($GLOBALS["awardset"][$awardKey]["category"])) {
              $awardset_expanded[$expandedKey]["category"] = preg_replace(
                "/_v_weapons/",
                $rs->fields[0],
                $GLOBALS["awardset"][$awardKey]["category"]
              );
            }
            foreach (
              $GLOBALS["awardset"][$awardKey]["sql"]
              as $sqlKey => $sqlValue
            ) {
              $awardset_expanded[$expandedKey]["sql"][$sqlKey] = preg_replace(
                "/_v_weapons/",
                $rs->fields[0],
                $GLOBALS["awardset"][$awardKey]["sql"][$sqlKey]
              );
            }
          } while ($rs->MoveNext() && !$rs->EOF);
        }
      } else {
        $awardset_expanded[$awardKey] = $GLOBALS["awardset"][$awardKey];
      }
    }
    $sql = "DELETE from {$GLOBALS["cfg"]["db"]["table_prefix"]}awards where 1";
    $db->Execute($sql);
    foreach ($awardset_expanded as $awardKey => $awardData) {
      foreach ($awardset_expanded[$awardKey]["sql"] as $sqlKey => $sqlValue) {
        $sqlValue = preg_replace("/awardset/", "awardset_expanded", $sqlValue);
        eval("\$sqlValue=\"$sqlValue\";");
        $awardset_expanded[$awardKey]["sql_final"] = preg_replace(
          "/\s+/",
          " ",
          $sqlValue
        );
        $rs = $db->Execute($sqlValue);
        $awardset_expanded[$awardKey]["sql"][$sqlKey] = @$rs->fields;
        $awardset_expanded[$awardKey]["result"] =
          $awardset_expanded[$awardKey]["sql"][$sqlKey][0];
      }
      if (
        isset($awardset_expanded[$awardKey]["name"]) &&
        //change: do not insert accuracy with empty resultset
        $awardset_expanded[$awardKey]["result"] !== null
      ) {
        //endchange
        if (!isset($awardset_expanded[$awardKey]["category"])) {
          $awardset_expanded[$awardKey]["category"] = "";
        }
        $sql =
          "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}awards
 set `sql`='',name='',awardID=" .
          $db->qstr($awardKey) .
          "";
        $db->Execute($sql);
        $sql =
          "UPDATE {$GLOBALS["cfg"]["db"]["table_prefix"]}awards
 set name=" .
          $db->qstr($awardset_expanded[$awardKey]["name"]) .
          " ,category=" .
          $db->qstr($awardset_expanded[$awardKey]["category"]) .
          "
 ,image=" .
          $db->qstr($awardset_expanded[$awardKey]["image"]) .
          " ,playerID=" .
          $db->qstr($awardset_expanded[$awardKey]["result"]) .
          "
 ,`sql`=" .
          $db->qstr($awardset_expanded[$awardKey]["sql_final"]) .
          " where awardID=" .
          $db->qstr($awardKey) .
          "";
        $db->Execute($sql);
      }
    }
    echo "done\n";
    flushOutputBuffers();
  }

  //change: remove detailed information of old games
  function prune_old_games()
  {
    global $db; // db
    // check if we are limiting games
    if ($GLOBALS["cfg"]["games_limit"] < 0) {
      return;
    }
    // check if games are actually over the limit
    $sql = "SELECT COUNT(*) FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}gamedata
            WHERE name = '_v_time_start'";
    $rs = $db->Execute($sql);
    if ($rs->fields[0] <= $GLOBALS["cfg"]["games_limit"]) {
      return;
    }
    // get games
    print "\npruneOldGames: prunning old games...";
    flushOutputBuffers();
    $tables = ["gamedata", "playerdata", "eventdata1d", "eventdata2d"];
    while (true) {
      $gameIDs = [];
      $sql = "SELECT gameID FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}gamedata
                WHERE name = '_v_time_start'
                ORDER BY value DESC
                LIMIT {$GLOBALS["cfg"]["games_limit"]}, 500";
      $rs = $db->Execute($sql);
      if (!$rs || $rs->EOF) {
        break;
      }
      while ($rs && !$rs->EOF) {
        $gameIDs[] = $rs->fields[0];
        $rs->MoveNext();
      }
      // remove specific data from every game
      foreach ($gameIDs as $gameID) {
        foreach ($tables as $table) {
          $sql = "DELETE FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}$table WHERE gameID = $gameID";
          $db->Execute($sql);
        }
      }
    }
    // optimize databases
    print "optimizing tables...";
    foreach ($tables as $table) {
      $sql = "OPTIMIZE TABLE {$GLOBALS["cfg"]["db"]["table_prefix"]}$table";
      $db->Execute($sql);
    }
    print "done\n";
    flushOutputBuffers();
  }
  //endchange
  function storeGameData(&$players, &$gameOptions)
  {
    // almacena los cambios en la base de datos
    global $db; // db
    $this->games_parsed++;
    if (!$players) {
      // juego vacío (sin jugadores)
      print "game is empty?, ignored\n";
      flushOutputBuffers();
      return;
    }
    //change: queries optimization
    $queries = [
      "gamedata" => [
        "sql" => [
          "REPLACE INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}gamedata (gameID, name, value) VALUES ",
        ],
        "queries" => [],
      ],
      "gameprofile" => [
        "sql" => [
          "REPLACE INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}gameprofile (gameID, timeStart) VALUES ",
        ],
      ],
    ];
    //endchange
    $this->playerStats = $players; // jugadores
    $this->gameData = $gameOptions; // opciones del juego
    $this->gameData["_v_players"] = count($this->playerStats); // número de jugadores
    if (!isset($this->gameData["_v_players"])) {
      $this->gameData["_v_players"] = "?";
    }
    if (!isset($this->gameData["_v_map"])) {
      $this->gameData["_v_map"] = "?";
    }
    if (!isset($this->gameData["_v_mod"])) {
      $this->gameData["_v_mod"] = "?";
    }
    if (!isset($this->gameData["_v_game"])) {
      $this->gameData["_v_game"] = "?";
    }
    if (!isset($this->gameData["_v_game_type"])) {
      $this->gameData["_v_game_type"] = "?";
    }
    if (!isset($this->gameData["_v_time_start"])) {
      $this->gameData["_v_time_start"] = "1000-01-01 00:00:00";
    }
    //change: check for duplicated games
    do {
      preg_match("/^0\\.(\d+) (\d+)/", microtime(), $match);
      $match = $match[2] . $match[1]; // genera id del juego
    } while ($this->gameID == $match);
    //endchange
    $this->gameID = $match; // id del juego
    if ($this->gameData) {
      // inserta datos del juego
      foreach ($this->gameData as $key => $value) {
        $key = $db->qstr($key);
        $value = $db->qstr($value);
        $queries["gamedata"]["queries"][] = "($this->gameID, $key, $value)";
      }
    }
    $qtime = $db->qstr($this->gameData["_v_time_start"]);
    if ($GLOBALS["cfg"]["parser"]["check_unique_gameID"]) {
      $sql = "SELECT gameID FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}gameprofile
                WHERE timeStart = $qtime
                LIMIT 1";
      $rs = $db->Execute($sql);
      if ($rs->fields[0]) {
        print "duplicated game timestamp, ignored\n";
        flushOutputBuffers();
        return;
      }
    }
    $queries["gameprofile"]["queries"][] = "($this->gameID, $qtime)";
    print "updating database...";
    flushOutputBuffers();
    foreach ($this->playerStats as $playerID => $stats) {
      // actualiza los datos de los jugadores
      //if ($this->playerStats[$playerID]['profile']['skill'] < 1600.0)
      //$this->playerStats[$playerID]['profile']['skill']=1600.0;
      //change: camel fix
      $this->playerStats[$playerID]["profile"]["skill"] = number_format(
        $this->playerStats[$playerID]["profile"]["skill"],
        4,
        ".",
        ""
      );
      //endchange
      if ($GLOBALS["cfg"]["parser"]["use_original_playerID"]) {
        $stats["v"]["original_id"] = $stats["v"]["original_id"];
      } else {
        $stats["v"]["original_id"] = $playerID;
      }
      $stats["v"]["original_id"] = $db->qstr(
        substr($stats["v"]["original_id"], 0, 99)
      );
      $name = $db->qstr($this->playerStats[$playerID]["profile"]["name"]); // último nombre
      if (
        isset($GLOBALS["cfg"]["parser"]["use_most_used_playerName"]) &&
        $GLOBALS["cfg"]["parser"]["use_most_used_playerName"] == 1
      ) {
        // nombre más usado
        $sql = sprintf(
          "select dataValue, count(*) as num from {$GLOBALS["cfg"]["db"]["table_prefix"]}playerdata
 where dataName=%s and playerID={$stats["v"]["original_id"]} group by dataValue order by num desc
 ",
          $db->qstr("alias")
        );
        $rs = $db->SelectLimit($sql, 1, 0);
        if ($rs and !$rs->EOF) {
          $name = $db->qstr($rs->fields[0]);
        }
      }
      //change: get country code
      if ($GLOBALS["cfg"]["ip2country"]["countries_only"]) {
        $code = $this->playerStats[$playerID]["profile"]["tld"]; // último tld
        if (@$GLOBALS["cfg"]["parser"]["use_most_used_playerIP"]) {
          $sql = sprintf("select dataValue, count(*) as num from {$GLOBALS["cfg"]["db"]["table_prefix"]}playerdata
                        where dataName='tld' and playerID={$stats["v"]["original_id"]} group by dataValue order by num desc");
          $rs = $db->SelectLimit($sql, 1, 0);
          if ($rs && !$rs->EOF) {
            $code = $rs->fields[0];
          }
        }
        if ($code) {
          $countryCode = $db->qstr($code);
        }
      } else {
        $ip = $this->playerStats[$playerID]["profile"]["ip"]; // último ip
        if ($GLOBALS["cfg"]["parser"]["use_most_used_playerIP"]) {
          $sql = sprintf("select dataValue, count(*) as num from {$GLOBALS["cfg"]["db"]["table_prefix"]}playerdata
                        where dataName='ip' and playerID={$stats["v"]["original_id"]} group by dataValue order by num desc");
          $rs = $db->SelectLimit($sql, 1, 0);
          if ($rs && !$rs->EOF) {
            $ip = $rs->fields[0];
          }
        }
        $ip_number = sprintf("%u", ip2long($ip));
        $sql = "SELECT country_code2
                        FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}ip2country
                        WHERE $ip_number BETWEEN ip_from AND ip_to";
        $rs = $db->Execute($sql);
        if ($rs->fields[0]) {
          $countryCode = $db->qstr($rs->fields[0]);
        }
      }
      if (!@$countryCode || $countryCode == "''") {
        $countryCode = $db->qstr("XX");
      }
      //endchange
      //change: decimal skill
      if (!isset($queries["playerprofile"])) {
        $queries["playerprofile"] = [
          "sql" => [
            "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}playerprofile (playerID, playerName, countryCode,
                            skill, kills, deaths, games, kill_streak, death_streak, first_seen, last_seen) VALUES ",
            " ON DUPLICATE KEY UPDATE playerName = VALUES(playerName), countryCode = VALUES(countryCode), skill = VALUES(skill),
                            kills = kills + VALUES(kills), deaths = deaths + VALUES(deaths), games = games + VALUES(games),
                            kill_streak = IF(VALUES(kill_streak) > kill_streak, VALUES(kill_streak), kill_streak),
                            death_streak = IF(VALUES(death_streak) > death_streak, VALUES(death_streak), death_streak),
                            first_seen = IF(VALUES(first_seen) < first_seen, VALUES(first_seen), first_seen),
                            last_seen = IF(VALUES(last_seen) > last_seen, VALUES(last_seen), last_seen)",
          ],
          "queries" => [],
        ];
      }
      $queries["playerprofile"][
        "queries"
      ][] = "({$stats["v"]["original_id"]}, $name, $countryCode,
                    {$this->playerStats[$playerID]["profile"]["skill"]}, {$this->playerStats[$playerID]["profile"]["kills"]},
                    {$this->playerStats[$playerID]["profile"]["deaths"]}, 1, {$this->playerStats[$playerID]["profile"]["kill_streak"]},
                    {$this->playerStats[$playerID]["profile"]["death_streak"]}, '{$this->gameData["_v_time_start"]}',
                    '{$this->gameData["_v_time_start"]}')";
      $dataIndexes = [];
      foreach ($stats as $key => $dataGroup) {
        // itera sobre la información de los jugadores
        if (!strcmp($key, "data") || !strcmp($key, "vdata")) {
          // si es data o vdata
          foreach ($dataGroup as $dataName => $dataArray) {
            $dataValue = $dataArray[1];
            $dataName = $db->qstr($dataName);
            $action = $dataArray[0];
            if (
              !strcmp($action, "rep") ||
              !strcmp($action, "inc") ||
              !strcmp($action, "avg")
            ) {
              $dataArray[1] = $db->qstr($dataArray[1]);
              if (!isset($queries["playerdata$action"])) {
                $aux =
                  $action == "rep"
                    ? "VALUES(dataValue)"
                    : ($action == "inc"
                      ? "dataValue+VALUES(dataValue)"
                      : "round((dataValue+VALUES(dataValue))/2.0,2.0)");
                $queries["playerdata$action"] = [
                  "sql" => [
                    "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}playerdata (playerID, gameID, dataName, dataNo, dataValue) VALUES ",
                    " ON DUPLICATE KEY UPDATE dataValue = $aux",
                  ],
                  "queries" => [],
                ];
              }
              $queries["playerdata$action"][
                "queries"
              ][] = "({$stats["v"]["original_id"]}, 0, $dataName, 0, {$dataArray[1]})";
            } elseif (!strcmp($action, "sto")) {
              // guarda datos del jugador
              unset($dataArray[0]);
              foreach ($dataArray as $index => $value) {
                if (!isset($dataIndexes[$dataName])) {
                  $dataIndexes[$dataName] = 0;
                }
                $dataNo = $dataIndexes[$dataName]++;
                if (!isset($queries["playerdata"])) {
                  $queries["playerdata"] = [
                    "sql" => [
                      "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}playerdata (playerID, gameID, dataName, dataNo, dataValue) VALUES ",
                    ],
                    "queries" => [],
                  ];
                }
                if (!isset($queries["playerdata_total"])) {
                  $queries["playerdata_total"] = [
                    "sql" => [
                      "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}playerdata_total (playerID, dataName, dataValue, dataCount) VALUES ",
                      " ON DUPLICATE KEY UPDATE dataCount = dataCount + 1",
                    ],
                    "queries" => [],
                  ];
                }
                $value = $db->qstr($value);
                $queries["playerdata"][
                  "queries"
                ][] = "({$stats["v"]["original_id"]}, $this->gameID, $dataName, $dataNo, $value)";
                $queries["playerdata_total"][
                  "queries"
                ][] = "({$stats["v"]["original_id"]}, $dataName, $value, 1)";
              }
            }
          }
        } elseif (!strcmp($key, "events")) {
          // eventos
          foreach ($dataGroup as $round => $roundEvents) {
            // events
            foreach ($roundEvents as $team => $teamEvents) {
              // rounds (?_)
              $quotedTeam = $db->qstr($team);
              foreach ($teamEvents as $role => $eventGroup) {
                // teams
                $quotedRole = $db->qstr($role);
                foreach ($eventGroup as $eventName => $eventData) {
                  // roles
                  if (!strcmp($eventName, "1D")) {
                    // afectan solamente a un player
                    foreach ($eventData as $eventName2 => $eventValue) {
                      $eventCategory = "";
                      if (preg_match("/^(.*)\\|(.+)/", $eventName2, $matches)) {
                        $eventCategory = $matches[1];
                        $eventName2 = $matches[2];
                      }
                      $eventName2 = $db->qstr($eventName2);
                      //change: camel fix
                      $eventValue = $db->qstr(
                        is_float($eventValue)
                          ? number_format($eventValue, 2, ".", "")
                          : $eventValue
                      );
                      //endchange
                      $eventCategory = $db->qstr($eventCategory);
                      if (!isset($queries["eventdata1d"])) {
                        $queries["eventdata1d"] = [
                          "sql" => [
                            "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata1d (playerID, gameID, round, team, role, eventName, eventCategory, eventValue) VALUES ",
                          ],
                          "queries" => [],
                        ];
                      }
                      $queries["eventdata1d"][
                        "queries"
                      ][] = "({$stats["v"]["original_id"]}, $this->gameID, $round, $quotedTeam, $quotedRole, $eventName2, $eventCategory, $eventValue)";
                      // special treatment for skill events
                      if ($eventCategory == "'skill'") {
                        if (
                          $eventName2 == "'begins'" ||
                          $eventName2 == "'ends'"
                        ) {
                          if (!isset($queries["eventdata1d_minskill"])) {
                            $queries["eventdata1d_minskill"] = [
                              "sql" => [
                                "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata1d_total (playerID, eventCategory, eventName, eventValue) VALUES ",
                                " ON DUPLICATE KEY UPDATE eventValue = IF(0+eventValue < 0+VALUES(eventValue), eventValue, VALUES(eventValue))",
                              ],
                              "queries" => [],
                            ];
                            $queries["eventdata1d_maxskill"] = [
                              "sql" => [
                                "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata1d_total (playerID, eventCategory, eventName, eventValue) VALUES ",
                                " ON DUPLICATE KEY UPDATE eventValue = IF(0+eventValue > 0+VALUES(eventValue), eventValue, VALUES(eventValue))",
                              ],
                              "queries" => [],
                            ];
                          }
                          $queries["eventdata1d_minskill"][
                            "queries"
                          ][] = "({$stats["v"]["original_id"]}, 'skill', 'min', $eventValue)";
                          $queries["eventdata1d_maxskill"][
                            "queries"
                          ][] = "({$stats["v"]["original_id"]}, 'skill', 'max', $eventValue)";
                        }
                        continue;
                      }
                      // other events
                      if (!isset($queries["eventdata1d_total"])) {
                        $queries["eventdata1d_total"] = [
                          "sql" => [
                            "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata1d_total (playerID, eventCategory, eventName, eventValue) VALUES ",
                            " ON DUPLICATE KEY UPDATE eventValue = eventValue + VALUES(eventValue)",
                          ],
                          "queries" => [],
                        ];
                      }
                      $queries["eventdata1d_total"][
                        "queries"
                      ][] = "({$stats["v"]["original_id"]}, $eventCategory, $eventName2, $eventValue)";
                    }
                  } elseif (!strcmp($eventName, "2D")) {
                    // eventos en los que intervienen 2 jugadores - los acc stats está acá supongo que por compatibilidad con otros juegos, lástima que q3 no tiene :(
                    foreach ($eventData as $eventName2 => $eventValue) {
                      // team 2 (?)
                      $quotedEventName2 = $db->qstr($eventName2);
                      foreach ($eventValue as $role2 => $opponents) {
                        // role 2 (?)
                        $quotedRole2 = $db->qstr($role2);
                        foreach ($opponents as $opponentName => $opponentEvent) {
                          //  player 2 id
                          if (
                            $GLOBALS["cfg"]["parser"]["use_original_playerID"]
                          ) {
                            $player2ID =
                              $this->playerStats[$opponentName]["v"][
                                "original_id"
                              ];
                          } else {
                            $player2ID = $opponentName;
                          }
                          $player2ID = $db->qstr(substr($player2ID, 0, 99));
                          foreach ($opponentEvent as $eventName3 => $eventValue) {
                            $eventCategory = "";
                            if (
                              preg_match(
                                "/^(.*)\\|(.+)/",
                                $eventName3,
                                $matches
                              )
                            ) {
                              $eventCategory = $matches[1];
                              $eventName3 = $matches[2];
                            }
                            $eventName3 = $db->qstr($eventName3);
                            $eventValue = $db->qstr($eventValue);
                            $eventCategory = $db->qstr($eventCategory);
                            if (!isset($queries["eventdata2d"])) {
                              $queries["eventdata2d"] = [
                                "sql" => [
                                  "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata2d
                                                                        (playerID, gameID, round, team, role, eventName, eventCategory, eventValue, player2ID, team2, role2) VALUES ",
                                ],
                                "queries" => [],
                              ];
                            }
                            if (!isset($queries["eventdata2d_total"])) {
                              $queries["eventdata2d_total"] = [
                                "sql" => [
                                  "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}eventdata2d_total (playerID, player2ID, eventCategory, eventName, eventValue) VALUES ",
                                  " ON DUPLICATE KEY UPDATE eventValue = eventValue + VALUES(eventValue)",
                                ],
                                "queries" => [],
                              ];
                            }
                            $queries["eventdata2d"][
                              "queries"
                            ][] = "({$stats["v"]["original_id"]}, $this->gameID, $round, $quotedTeam, $quotedRole, $eventName3, $eventCategory, $eventValue, $player2ID, $quotedEventName2, $quotedRole2)";
                            $queries["eventdata2d_total"][
                              "queries"
                            ][] = "({$stats["v"]["original_id"]}, $player2ID, $eventCategory, $eventName3, $eventValue)";
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
    $extraData["last update time"] = date("Y-m-d H:i:s"); // última actualización
    $extraData["vsp version"] = constant("cVERSION"); // última versión de vsp ejecutada
    foreach ($extraData as $extraKey => $extraValue) {
      $name = $db->qstr($extraKey);
      $value = $db->qstr($extraValue);
      $queries["gamedata"]["queries"][] = "(0, $name, $value)";
    }
    //change: batched queries
    foreach ($queries as $query) {
      $sql = $query["sql"][0] . implode(", ", $query["queries"]);
      if (isset($query["sql"][1])) {
        $sql .= $query["sql"][1];
      }
      $db->Execute($sql);
      $err = $db->ErrorMsg();
      if ($err) {
        die("\n\nError: $err\nQuery: $sql");
      }
    }
    unset($queries);
    //endchange
    print "done\n";
    $this->games_inserted++;
    if (
      $GLOBALS["cfg"]["games_limit"] >= 0 &&
      $this->games_inserted % 500 == 0
    ) {
      $this->prune_old_games();
    }
    flushOutputBuffers();
  }
}

function printTitle()
{
  print cTITLE;
}

function printUsage()
{
  print cUSAGE;
}

function debugPrint($message)
{
  // Currently hardcoded to always print the message.
  $printFlag = 1;
  if ($printFlag == 1) {
    print "$message";
  }
}

function errorAndExit($errorMessage)
{
  print "\n$errorMessage\n";
  exitProgram();
}

function usageErrorExit($errorMessage)
{
  printUsage();
  print "$errorMessage\n";
  exitProgram();
}

function getFtpFileList(&$ftpConnection, $remotePath)
{
  // Retrieves a raw file listing via FTP, parses it, and returns only regular files.
  $rawList = ftp_rawlist($ftpConnection, $remotePath);
  $parsedList = parseFileListing($rawList);
  $fileList = [];
  foreach ($parsedList as $fileInfo) {
    if ($fileInfo["type"] == 0) {
      $fileList[count($fileList)] = $fileInfo;
    }
  }
  return $fileList;
}

function downloadFtpLogs($ftpUrl)
{
  // Parse the FTP URL.
  $parsedUrl = parse_url($ftpUrl);
  echo "Attempting to connect to FTP server {$parsedUrl["host"]}:{$parsedUrl["port"]}...\n";

  // Disallow credentials in the URL for security reasons.
  if (isset($parsedUrl["user"]) || isset($parsedUrl["pass"])) {
    echo " - Specify the ftp username and password in the config and not in the VSP command line (Security reasons?)\n";
    exitProgram();
  }
  flushOutputBuffers();

  // Connect to the FTP server.
  if (
    !($ftpConnection = ftp_connect($parsedUrl["host"], $parsedUrl["port"], 30))
  ) {
    echo " - Error: Failed to connect to ftp server. Verify FTP hostname/port.\n";
    echo " Also, your php host may not have ftp access via php enabled or may\n";
    echo " have blocked the php process from connecting to an external server\n";
    exitProgram();
  }

  // Login using credentials from the configuration.
  if (
    !ftp_login(
      $ftpConnection,
      $GLOBALS["cfg"]["ftp"]["username"],
      $GLOBALS["cfg"]["ftp"]["password"]
    )
  ) {
    echo " - Error: Failed to login to ftp server. Verify FTP username/password in config\n";
    exitProgram();
  }
  echo " - Connection/Login successful.\n";

  // Set FTP passive mode if configured.
  if (isset($GLOBALS["cfg"]["ftp"]["pasv"]) && $GLOBALS["cfg"]["ftp"]["pasv"]) {
    if (ftp_pasv($ftpConnection, true)) {
      echo " - FTP passive mode enabled\n";
    } else {
      echo " - failed to enable FTP passive mode\n";
    }
  } else {
    echo " - not using FTP passive mode (disabled in config)\n";
  }

  // Ensure the local logs directory exists.
  if (!ensureDirectoryExists($GLOBALS["cfg"]["ftp"]["logs_path"])) {
    echo " - Error: Failed to create local directory \"" .
      $GLOBALS["cfg"]["ftp"]["logs_path"] .
      "\" for FTP log download.\n";
    echo " Check pathname/permissions.\n";
    exitProgram();
  }

  // Check if the remote path is a directory (ends with a slash) or a file.
  if (preg_match("/[\\/\\\\]$/", $parsedUrl["path"])) {
    echo " - Preparing to download all files from remote directory \"" .
      $parsedUrl["path"] .
      "\"\n";
    $fileList = getFtpFileList($ftpConnection, $parsedUrl["path"]);
    preg_match("/([^\\/\\\\]+[\\/\\\\])$/", $parsedUrl["path"], $matches);
    $remoteBasePath = $parsedUrl["path"];
    $localDirectory = ensureTrailingSlash(
      $GLOBALS["cfg"]["ftp"]["logs_path"] . $matches[1]
    );
    ensureDirectoryExists($localDirectory);
    $localTargetPath = $localDirectory;
  } else {
    echo " - Preparing to download the remote file \"" .
      $parsedUrl["path"] .
      "\"\n";
    preg_match("/([^\\/\\\\]+)$/", $parsedUrl["path"], $matches);
    $fileList[0]["name"] = $matches[1];
    $fileList[0]["size"] = ftp_size($ftpConnection, $parsedUrl["path"]);
    $remoteBasePath = substr(
      $parsedUrl["path"],
      0,
      strlen($parsedUrl["path"]) - strlen($fileList[0]["name"])
    );
    $localDirectory = ensureTrailingSlash($GLOBALS["cfg"]["ftp"]["logs_path"]);
    $localTargetPath = $localDirectory . $fileList[0]["name"];
  }

  // Verify that the remote file size is valid.
  if (!ctype_digit("" . $fileList[0]["size"]) || $fileList[0]["size"] < 0) {
    echo " - Error: cannot find Remote file \"" .
      $fileList[0]["name"] .
      "\" at ftp://{$parsedUrl["host"]}:{$parsedUrl["port"]}" .
      $remoteBasePath .
      "\n";
    exitProgram();
  }

  // For each file in the list, attempt to download it.
  foreach ($fileList as $fileInfo) {
    $localFilePath = $localDirectory . $fileInfo["name"];
    $localFileSize = file_exists($localFilePath)
      ? filesize($localFilePath) - 1
      : 0;
    $remoteFilePath = $remoteBasePath . $fileInfo["name"];
    $remoteFileSize = $fileInfo["size"];
    echo " - Attempting to download \"$remoteFilePath\" from FTP server to \"$localFilePath\"...\n";
    flushOutputBuffers();
    if (
      isset($GLOBALS["cfg"]["ftp"]["overwrite"]) &&
      $GLOBALS["cfg"]["ftp"]["overwrite"]
    ) {
      echo " - overwrite mode\n";
      if (
        !ftp_get($ftpConnection, $localFilePath, $remoteFilePath, FTP_BINARY)
      ) {
        echo " Error: Failed to get ftp log from \"$remoteFilePath\" to \"$localFilePath\".\n";
        if (!$GLOBALS["cfg"]["ftp"]["pasv"]) {
          echo " Try enabling FTP passive mode in config.\n";
        }
        echo " Try making the ftplogs/ and logdata/ folder writable by all (chmod 777).\n";
        exitProgram();
      }
      echo " Downloaded remote file successfully\n";
      flushOutputBuffers();
    } else {
      if ($remoteFileSize == $localFileSize + 1) {
        echo " Remote file is the same size as Local file. Skipped Download.\n";
      } elseif ($remoteFileSize > $localFileSize + 1) {
        if (
          !ftp_get(
            $ftpConnection,
            $localFilePath,
            $remoteFilePath,
            FTP_BINARY,
            $localFileSize
          )
        ) {
          echo " Error: Failed to get ftp log from \"$remoteFilePath\" to \"$localFilePath\".\n";
          if (!$GLOBALS["cfg"]["ftp"]["pasv"]) {
            echo " Try enabling FTP passive mode in config.\n";
          }
          echo " Try making the ftplogs/ and logdata/ folder writable by all (chmod 777).\n";
          exitProgram();
        }
        echo " Downloaded/Resumed remote file successfully\n";
      } else {
        echo " Remote file is smaller than Local file. Skipped Download.\n";
      }
      flushOutputBuffers();
    }
  }
  echo $localTargetPath . "\n";
  return $localTargetPath;
}

function processCommandLineArgs()
{
  global $cliArgs;
  if (cIS_SHELL) {
    if (!isset($_SERVER["argc"])) {
      echo "Error: args not registered.\n";
      echo " register_argc_argv may need to be set to On in shell mode\n";
      echo " Please edit your php.ini and set variable register_argc_argv to On\n";
      exitProgram();
    }
    $cliArgs["argv"] = $_SERVER["argv"];
    $cliArgs["argc"] = $_SERVER["argc"];
  } else {
    $cmdLine = $_POST["CMD_LINE_ARGS"];
    $cliArgs = parseCommandLineArgs("vsp.php " . $cmdLine);
  }
  global $options;
  $options["parser-options"] = [];
  $options["prompt"] = 1;
  if ($cliArgs["argc"] > 1) {
    for ($i = 1; $i < $cliArgs["argc"] - 1; $i++) {
      if (strcmp($cliArgs["argv"][$i], "-a") == 0) {
        $i++;
        $options["action"] = $cliArgs["argv"][$i];
        //change: add new actions
        if (
          !in_array($options["action"], [
            "clear_db",
            "gen_awards",
            "clear_savestate",
            "pop_ip2country",
            "prune_old_games",
          ])
        ) {
          usageErrorExit("error: invalid action");
        }
        //endchange
        break;
      }
      if (strcmp($cliArgs["argv"][$i], "-n") == 0) {
        $options["prompt"] = 0;
        continue;
      }
      if ($i + 1 > $cliArgs["argc"] - 2) {
        usageErrorExit(
          "error: no value specified for option " . $cliArgs["argv"][$i]
        );
      }
      if (strcmp($cliArgs["argv"][$i], "-p") == 0) {
        $i++;
        for ($j = $i; $j < $cliArgs["argc"] - 1; $j += 2) {
          $options["parser-options"][$cliArgs["argv"][$j]] =
            $cliArgs["argv"][$j + 1];
        }
        break;
      } elseif (strcmp($cliArgs["argv"][$i], "-c") == 0) {
        $i++;
        $options["config"] = $cliArgs["argv"][$i];
      } elseif (strcmp($cliArgs["argv"][$i], "-l") == 0) {
        $i++;
        $options["log-gamecode"] = $cliArgs["argv"][$i];
        $options["log-gametype"] = "";
        if (preg_match("/(.*)-(.*)/", $options["log-gamecode"], $matches)) {
          $options["log-gamecode"] = $matches[1];
          $options["log-gametype"] = $matches[2];
          $options["parser-options"]["gametype"] = $options["log-gametype"];
        }
      } else {
        usageErrorExit("error: invalid option " . $cliArgs["argv"][$i]);
      }
    }
  } else {
    usageErrorExit("error: logfile not specified");
  }
  $options["logfile"] = $cliArgs["argv"][$cliArgs["argc"] - 1];
  if (!isset($options["action"])) {
    if (!isset($options["logfile"])) {
      usageErrorExit("error: logFile not specified");
    }
    if (!isset($options["log-gamecode"])) {
      usageErrorExit("error: logType not specified");
    }
  }
  $configPath = "pub/configs/";
  if (
    !isset($options["config"]) ||
    preg_match("/\\.\\./", $options["config"]) ||
    !is_file($configPath . $options["config"])
  ) {
    $options["config"] = $configPath . "cfg-default.php";
  } else {
    $options["config"] = $configPath . $options["config"];
  }
  echo "max_execution_time is " . ini_get("max_execution_time") . "\n\n";
  echo "[command-line options]: ";
  print_r($options);
  if (
    isset($options["parser-options"]["savestate"]) &&
    $options["parser-options"]["savestate"]
  ) {
    $testFile = "writetest_" . md5(uniqid(rand(), true));
    $fp = fopen("./logdata/" . $testFile, "wb");
    if (!$fp || !fwrite($fp, "* WRITE TEST *\n")) {
      echo "Error: savestate 1 processing requires logdata/ directory to be writable.\n";
      echo " Enable write permissions for logdata/ directory (chmod 777)\n";
      exitProgram();
    }
    fclose($fp);
    unlink("logdata/$testFile");
  }
}

function configureAndProcessGameLogs()
{
  global $options, $cliArgs;
  global $options; // $options from processCommandLineArgs
  require_once $options["config"];
  if (preg_match("/^ftp:\\/\\//i", $options["logfile"])) {
    $options["logfile"] = downloadFtpLogs($options["logfile"]);
  }
  $options["parser-options"]["trackID"] = $GLOBALS["cfg"]["parser"]["trackID"];
  if (isset($GLOBALS["cfg"]["db"]["adodb_path"])) {
    $GLOBALS["cfg"]["db"]["adodb_path"] = ensureTrailingSlash(
      $GLOBALS["cfg"]["db"]["adodb_path"]
    );
  } else {
    $GLOBALS["cfg"]["db"]["adodb_path"] =
      ensureTrailingSlash(APP_ROOT_DIR) . "pub/lib/adodb/";
  }
  require_once "{$GLOBALS["cfg"]["db"]["adodb_path"]}adodb.inc.php";
  include_once "{$GLOBALS["cfg"]["db"]["adodb_path"]}tohtml.inc.php";
  require_once "sql/{$GLOBALS["cfg"]["db"]["adodb_driver"]}.inc.php";
  include_once "pub/include/playerBanList-{$GLOBALS["cfg"]["player_ban_list"]}.inc.php";
  //change: add player_exclude_list support
  include_once "pub/include/playerExcludeList-{$GLOBALS["cfg"]["player_exclude_list"]}.inc.php";
  //endchange
  foreach ($GLOBALS["player_ban_list"] as $key => $value) {
    $GLOBALS["player_ban_list"][$key] = "/^" . preg_quote($value) . "$/";
  }
  $GLOBALS["db"] = &ADONewConnection($GLOBALS["cfg"]["db"]["adodb_driver"]);
  global $db;
  if (
    !$db->Connect(
      $GLOBALS["cfg"]["db"]["hostname"],
      $GLOBALS["cfg"]["db"]["username"],
      $GLOBALS["cfg"]["db"]["password"],
      $GLOBALS["cfg"]["db"]["dbname"]
    )
  ) {
    echo "Attempting to create/connect to database {$GLOBALS["cfg"]["db"]["dbname"]}\n";
    $GLOBALS["db"] = null;
    $GLOBALS["db"] = &ADONewConnection($GLOBALS["cfg"]["db"]["adodb_driver"]);
    global $db;
    $db->Connect(
      $GLOBALS["cfg"]["db"]["hostname"],
      $GLOBALS["cfg"]["db"]["username"],
      $GLOBALS["cfg"]["db"]["password"]
    );
    $db->Execute($sql_create[0]);
    if (
      !$db->Connect(
        $GLOBALS["cfg"]["db"]["hostname"],
        $GLOBALS["cfg"]["db"]["username"],
        $GLOBALS["cfg"]["db"]["password"],
        $GLOBALS["cfg"]["db"]["dbname"]
      )
    ) {
      echo " - failed to create/connect to database {$GLOBALS["cfg"]["db"]["dbname"]}\n";
      exitProgram();
    }
    echo " - database created\n";
  }
  //change: add new options
  if (isset($options["action"])) {
    switch ($options["action"]) {
      case "clear_db":
        if (cIS_SHELL && $options["prompt"]) {
          echo "Are you sure you want to clear the database {$GLOBALS["cfg"]["db"]["dbname"]} @ {$GLOBALS["cfg"]["db"]["hostname"]}? (y/n)\n";
          flushOutputBuffers();
          $confirm = readStdinLine();
        } else {
          $confirm = "y";
        }
        if ($confirm == "y" || $confirm == "Y") {
          foreach ($sql_destroy as $key => $sql) {
            $db->Execute($sql);
          }
          print "{$GLOBALS["cfg"]["db"]["table_prefix"]}* tables in {$GLOBALS["cfg"]["db"]["dbname"]} @ {$GLOBALS["cfg"]["db"]["hostname"]} has been cleared\n";
        }
        finalizeProgram();
        break;
      case "gen_awards":
        $processor = new GameDataProcessor();
        $processor->generateAwards();
        finalizeProgram();
        break;
      case "clear_savestate":
        if ($options["logfile"] == "clear_savestate") {
          $options["logfile"] = "";
        } else {
          $realpath_log = realpath($options["logfile"]);
        }
        if (cIS_SHELL && $options["prompt"]) {
          if ($options["logfile"]) {
            echo "Are you sure you want to clear the savestate information for the log file {$realpath_log}? (y/n)\n";
          } else {
            echo "Are you sure you want to clear the savestate information for ALL the log files? (y/n)\n";
          }
          flushOutputBuffers();
          $confirm = readStdinLine();
        } else {
          $confirm = "y";
        }
        if ($confirm == "y" || $confirm == "Y") {
          $logfile = $db->qstr($realpath_log);
          $sql = "DELETE FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}savestate";
          if ($options["logfile"]) {
            $sql .= " WHERE `logfile` = {$logfile}";
          }
          $db->Execute($sql);
          echo "Savestate information for log file {$realpath_log} cleared\n";
        }
        finalizeProgram();
        break;
      case "pop_ip2country":
        populateIp2countryTable();
        finalizeProgram();
        break;
      case "prune_old_games":
        $processor = new GameDataProcessor();
        $processor->prune_old_games();
        finalizeProgram();
        break;
    }
  }
  //endchange
  foreach ($sql_create as $key => $sql) {
    if ($key == 0) {
      continue;
    }
    $db->Execute($sql);
  }
  //change: automatic insert ip2country table
  $sql = "SELECT COUNT(*) FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}ip2country";
  $rs = $db->Execute($sql);
  if (!$rs || !$rs->fields[0]) {
    populateIp2countryTable();
  }
  //endchange
  $db->SetFetchMode(ADODB_FETCH_NUM);
  if (!is_dir("pub/games/{$GLOBALS["cfg"]["game"]["name"]}")) {
    echo "Error: The variable \$cfg['game']['name'] is not set properly in config file.\n";
    echo " Edit your config file ({$options["config"]})\n";
    echo " Read the comments beside that variable and set that variable properly.\n";
    exitProgram();
  }
  if (!file_exists("vsp-{$options["log-gamecode"]}.php")) {
    usageErrorExit("error: unrecognized logType");
  }
  require_once "vsp-{$options["log-gamecode"]}.php";
  include_once "pub/games/{$GLOBALS["cfg"]["game"]["name"]}/skillsets/{$GLOBALS["cfg"]["skillset"]}/{$GLOBALS["cfg"]["skillset"]}-skill.php";
  if (!isset($GLOBALS["skillset"])) {
    echo "Skill Definitions not found.\n";
    echo " " .
      "pub/games/{$GLOBALS["cfg"]["game"]["name"]}/skillsets/{$GLOBALS["cfg"]["skillset"]}/{$GLOBALS["cfg"]["skillset"]}-skill.php" .
      "\n";
  }
  $processor = new GameDataProcessor();
  $skillProcessor = new PlayerSkillProcessor();
  $upperLogCode = strtoupper($options["log-gamecode"]);
  eval(
    "\$parser = new VSPParser$upperLogCode(\$options['parser-options'],\$processor,\$skillProcessor);"
  );

  $parser->processLogFile($options["logfile"]); // parsea el log
  $processor->prune_old_games();
  $processor->generateAwards(); // generate awards
  echo "\ngames: parsed: " .
    $processor->games_parsed .
    "\tinserted: " .
    $processor->games_inserted .
    "\t ignored: " .
    ($processor->games_parsed - $processor->games_inserted) .
    "\n";
}

function populateIp2countryTable()
{
  global $db;
  echo "Populating ip to country table...";
  flushOutputBuffers();
  $sql = "DELETE FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}ip2country";
  $db->Execute($sql);
  $flag = false;
  $filename = realpath("sql/{$GLOBALS["cfg"]["ip2country"]["source"]}");
  if (!file_exists($filename)) {
    $filename = $GLOBALS["cfg"]["ip2country"]["source"];
  }
  $countries = [];
  if ($file = fopen($filename, "rb")) {
    while ($line = fgetcsv($file)) {
      $from_index = $GLOBALS["cfg"]["ip2country"]["columns"]["ip_from"];
      $to_index = $GLOBALS["cfg"]["ip2country"]["columns"]["ip_to"];
      $code_index = $GLOBALS["cfg"]["ip2country"]["columns"]["country_code2"];
      $name_index = $GLOBALS["cfg"]["ip2country"]["columns"]["country_name"];
      if (
        !isset(
          $line[$from_index],
          $line[$to_index],
          $line[$code_index],
          $line[$name_index]
        ) ||
        !is_numeric($line[$from_index]) ||
        !is_numeric($line[$to_index]) ||
        strlen($line[$code_index]) != 2 ||
        !$line[$name_index]
      ) {
        continue;
      }
      $flag = true;
      $country_code = $db->qstr($line[$code_index]);
      $country_name = $db->qstr($line[$name_index]);
      if (
        $GLOBALS["cfg"]["ip2country"]["countries_only"] &&
        array_key_exists($country_code, $countries)
      ) {
        continue;
      }
      $countries[$country_code] = true;
      $sql = "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}ip2country (ip_from, ip_to, country_code2, country_name)
                  VALUES ({$line[$from_index]}, {$line[$to_index]}, $country_code, $country_name)";
      $db->Execute($sql);
    }
  }
  if (!$flag) {
    echo "\n - error at populating ip to country table.\n";
    exitProgram();
  }
  // put some dummy locations
  if (!array_key_exists("XX", $countries)) {
    $sql = "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}ip2country (ip_from, ip_to, country_code2, country_name)
              VALUES (4294967295, 4294967295, 'XX', 'UNKNOWN LOCATION')";
    $db->Execute($sql);
  }
  if (!array_key_exists("ZZ", $countries)) {
    $sql = "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}ip2country (ip_from, ip_to, country_code2, country_name)
              VALUES (4294967294, 4294967294, 'ZZ', 'UNKNOWN LOCATION')";
    $db->Execute($sql);
  }
  echo "done\n";
  flushOutputBuffers();
}

//change: database-driven savestate
function save_savestate(&$parser)
{
  $parser->logdata["last_shutdown_end_position"] = ftell($parser->logFileHandle);
  $seekResult = fseek($parser->logFileHandle, -LOG_READ_SIZE, SEEK_CUR);
  if ($seekResult == 0) {
    $parser->logdata["last_shutdown_hash"] = md5(
      fread($parser->logFileHandle, LOG_READ_SIZE)
    );
  } else {
    $currentPos = ftell($parser->logFileHandle);
    fseek($parser->logFileHandle, 0);
    $parser->logdata["last_shutdown_hash"] = md5(
      fread($parser->logFileHandle, $currentPos)
    );
  }
  // $fp = fopen('./logdata/savestate_' . sanitizeFilename($parser->logFilePath) . '.inc.php', "wb");
  global $db; // db
  $logfile = $db->qstr(
    isset($parser->original_log) ? $parser->original_log : $parser->logFilePath
  );
  $sql = "INSERT INTO {$GLOBALS["cfg"]["db"]["table_prefix"]}savestate SET `logfile` = {$logfile}";
  $rs = $db->Execute($sql);
  $value = $db->qstr(
    "\$this->logdata['last_shutdown_hash']='{$parser->logdata["last_shutdown_hash"]}';\n" .
      "\$this->logdata['last_shutdown_end_position']={$parser->logdata["last_shutdown_end_position"]};"
  );
  $sql = "UPDATE {$GLOBALS["cfg"]["db"]["table_prefix"]}savestate SET `value` = {$value}";
  $rs = $db->Execute($sql);
}

function check_savestate(&$parser)
{
  echo "Verifying savestate\n";
  $fp = fopen($parser->logFilePath, "rb");
  $seekResult = fseek($fp, $parser->logdata["last_shutdown_end_position"]);
  if ($seekResult == 0) {
    $seekResult2 = fseek($fp, -LOG_READ_SIZE, SEEK_CUR);
    if ($seekResult2 == 0) {
      $fileData = fread($fp, LOG_READ_SIZE);
    } else {
      $currentPos = ftell($fp);
      fseek($fp, 0);
      $fileData = fread($fp, $currentPos);
    }
    if (strcmp(md5($fileData), $parser->logdata["last_shutdown_hash"]) == 0) {
      echo " - Hash matched, resuming parsing from previous saved location in log file\n";
      fseek($parser->logFileHandle, $parser->logdata["last_shutdown_end_position"]);
    } else {
      echo " - Hash did not match, assuming new log file\n";
      fseek($parser->logFileHandle, 0);
    }
  } else {
    echo " - Seek to prior location failed, assuming new log file\n";
    fseek($parser->logFileHandle, 0);
  }
  fclose($fp);
}
//endchange

function checkWebAccess()
{
  require_once "./password.inc.php";
  if (strlen($vsp["password"]) < 6) {
    echo "<HTML><BODY><PRE>Web Access to vsp.php is currently disabled.\nIf you want to enable web access to vsp.php,\nlook in password.inc.php under your vsp folder using a text editor(notepad).\nRead the ReadME.txt file for additional information.";
    exitProgram();
  }
  if (!isset($_POST["password"])) { ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <HTML> <HEAD> <TITLE>vsp stats processor</TITLE> </HEAD>
    <BODY> <center> <PRE> <?php printTitle(); ?>
    </PRE>
    <form action="vsp.php?mode=web" method="post">
      <TABLE BORDER="0" CELLSPACING="5" CELLPADDING="0">
        <TR> <TD>&nbsp;</TD> <TD>[options] [-p parserOptions] [logFilename]</TD> </TR>
        <TR> <TD VALIGN="TOP">php vsp.php</TD>
          <TD><input size="50" type="text" name="CMD_LINE_ARGS" /><BR>example: -l q3a-osp -p savestate 1 "games.log"</TD>
        </TR>
      </TABLE>
      <BR><BR> password = <input size=10 type=password name="password" /><BR><BR>
      <input type="submit" value="Submit ( Process Stats )" />
      <BR><BR>
    </form>
    <PRE> <?php printUsage(); ?>
    </PRE> </center> </BODY></HTML>
    <?php exit();}
  $userPass = $_POST["password"];
  if (md5($userPass) != md5($vsp["password"])) {
    echo "<HTML><BODY><PRE>Invalid password.\nFor the correct password, Look in password.inc.php under your vsp folder using a text editor(notepad).";
    exitProgram();
  }
}

function initializeEnvironment()
{
  flushOutputBuffers();
  $GLOBALS["startTime"] = gettimeofday();
  set_time_limit(0);

  define("APP_ROOT_DIR", dirname(realpath(__FILE__)));
  if (
    (isset($_GET["mode"]) && $_GET["mode"] == "web") ||
    isset($_SERVER["QUERY_STRING"]) ||
    isset($_SERVER["HTTP_HOST"]) ||
    isset($_SERVER["SERVER_PROTOCOL"]) ||
    isset($_SERVER["SERVER_SOFTWARE"]) ||
    isset($_SERVER["SERVER_NAME"])
  ) {
    define("cIS_SHELL", 0);
  } else {
    define("cIS_SHELL", 1);
  }
  define("cBIG_STRING_LENGTH", "1024");
  if (cIS_SHELL) {
    ini_set("html_errors", "0");
    chdir(APP_ROOT_DIR);
  } else {
    ini_set("html_errors", "1");
    checkWebAccess();
    echo "<HTML><BODY><PRE>";
  }
  printTitle();
}

function exitProgram()
{
  if (!cIS_SHELL) {
    echo "</PRE></BODY></HTML>";
  }
  exit();
}

function finalizeProgram()
{
  // final del programa
  printTitle();
  $elapsed = getElapsedTime($GLOBALS["startTime"]);
  $minutes = floor($elapsed / 60);
  $seconds = $elapsed % 60;
  echo "processed in {$minutes}m {$seconds}s ({$elapsed}s)\n";
  if (!cIS_SHELL) {
    echo "</PRE></BODY></HTML>";
  }
  exit();
}

require_once "vutil.php";
initializeEnvironment();
processCommandLineArgs();
configureAndProcessGameLogs();
finalizeProgram(); // Note: In the original, these functions are called in sequence: initializeEnvironment(), processCommandLineArgs(), configureAndProcessGameLogs(), finalizeProgram().

?>
