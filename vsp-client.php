<?php /* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */
class VSPParserCLIENT
{
  var $killRegexPatterns;
  var $playerEnterPatterns;
  var $shutdownPatterns;
  var $gameStartPatterns;
  var $playerTeamEnterPatterns;
  var $ctfEventPatterns;
  var $renamePatterns;
  var $config;
  var $statsAggregator;
  var $statsProcessor;
  var $playerAliases;
  var $currentPlayerData;
  var $logInfo;
  var $rawTimestamp;
  var $baseTimeParts;
  var $gameInProgress;
  var $logFileHandle;
  var $logFilePath;
  var $logdata;
  var $currentFilePosition;
  var $gameStartFilePosition;

  // Constructor: initializes configuration, aggregator and processor.
  function __construct($configData, &$statsAggregator, &$statsProcessor)
  {
    $this->renamePatterns = ["#PLAYER#(?:\\^[^\\^])? renamed to #NAME#$"];
    $this->chatPatterns = ["#PLAYER#(?:\\^[^\\^])?: #CHAT#$"];
    $this->gameStartPatterns = ["Match has begun!"];
    $this->shutdownPatterns = [
      "^Timelimit hit\\.",
      "^Pointlimit hit\\.",
      "hit the capturelimit\\.$",
      "hit the fraglimit\\.$",
      "^----- CL_Shutdown -----",
    ];
    $this->playerEnterPatterns = ["#PLAYER#(?:\\^[^\\^])? entered the game"];
    $this->playerTeamEnterPatterns = [
      "#PLAYER#(?:\\^[^\\^])? entered the game \\(#TEAM#\\)",
    ];
    $this->ctfEventPatterns = [
      "#PLAYER#(?:\\^[^\\^])? RED's flag carrier defends against an agressive enemy" =>
        "CTF|Defend_Hurt_Carrier",
      "#PLAYER#(?:\\^[^\\^])? got the BLUE flag!" => "CTF|Flag_Pickup",
      "#PLAYER#(?:\\^[^\\^])? returned the RED flag!" => "CTF|Flag_Return",
      "#PLAYER#(?:\\^[^\\^])? fragged BLUE's flag carrier!" =>
        "CTF|Kill_Carrier",
      "#PLAYER#(?:\\^[^\\^])? gets an assist for returning the RED flag!" =>
        "CTF|Flag_Assist_Return",
      "#PLAYER#(?:\\^[^\\^])? gets an assist for fragging the RED flag carrier!" =>
        "CTF|Flag_Assist_Frag",
      "#PLAYER#(?:\\^[^\\^])? defends RED's flag carrier against an agressive enemy" =>
        "CTF|Defend_Hurt_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the RED flag carrier against an agressive enemy!" =>
        "CTF|Defend_Hurt_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the RED's flag carrier." =>
        "CTF|Defend_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the RED flag carrier!" =>
        "CTF|Defend_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the RED base" => "CTF|Defend_Base",
      "#PLAYER#(?:\\^[^\\^])? defends the RED flag" => "CTF|Defend_Flag",
      "#PLAYER#(?:\\^[^\\^])? captured the BLUE flag!" => "CTF|Flag_Capture",
      "#PLAYER#(?:\\^[^\\^])? BLUE's flag carrier defends against an agressive enemy" =>
        "CTF|Defend_Hurt_Carrier",
      "#PLAYER#(?:\\^[^\\^])? got the RED flag!" => "CTF|Flag_Pickup",
      "#PLAYER#(?:\\^[^\\^])? returned the BLUE flag!" => "CTF|Flag_Return",
      "#PLAYER#(?:\\^[^\\^])? fragged RED's flag carrier!" =>
        "CTF|Kill_Carrier",
      "#PLAYER#(?:\\^[^\\^])? gets an assist for returning the BLUE flag!" =>
        "CTF|Flag_Assist_Return",
      "#PLAYER#(?:\\^[^\\^])? gets an assist for fragging the BLUE flag carrier!" =>
        "CTF|Flag_Assist_Frag",
      "#PLAYER#(?:\\^[^\\^])? defends BLUE's flag carrier against an agressive enemy" =>
        "CTF|Defend_Hurt_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the BLUE flag carrier against an agressive enemy!" =>
        "CTF|Defend_Hurt_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the BLUE's flag carrier." =>
        "CTF|Defend_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the BLUE flag carrier!" =>
        "CTF|Defend_Carrier",
      "#PLAYER#(?:\\^[^\\^])? defends the BLUE base" => "CTF|Defend_Base",
      "#PLAYER#(?:\\^[^\\^])? defends the BLUE flag" => "CTF|Defend_Flag",
      "#PLAYER#(?:\\^[^\\^])? captured the RED flag!" => "CTF|Flag_Capture",
      "#PLAYER#(?:\\^[^\\^])? got an assist for returning the flag!" =>
        "CTF|Flag_Assist_Return",
      "#PLAYER#(?:\\^[^\\^])? got an assist for fragging the enemy flag carrier!" =>
        "CTF|Flag_Assist_Frag",
    ];
    $this->killRegexPatterns = [
      "#VICTIM#(?:\\^[^\\^])? was pummeled by #KILLER#(?:\\^[^\\^])?$" =>
        "GAUNTLET",
      "#VICTIM#(?:\\^[^\\^])? was machinegunned by #KILLER#(?:\\^[^\\^])?$" =>
        "MACHINEGUN",
      "#VICTIM#(?:\\^[^\\^])? was gunned down by #KILLER#(?:\\^[^\\^])?$" =>
        "SHOTGUN",
      "#VICTIM#(?:\\^[^\\^])? was shredded by #KILLER#(?:\\^[^\\^])?'s shrapnel" =>
        "GRENADE",
      "#VICTIM#(?:\\^[^\\^])? ate #KILLER#(?:\\^[^\\^])?'s grenade" =>
        "GRENADE",
      "#VICTIM#(?:\\^[^\\^])? ate #KILLER#(?:\\^[^\\^])?'s rocket" => "ROCKET",
      "#VICTIM#(?:\\^[^\\^])? almost dodged #KILLER#(?:\\^[^\\^])?'s rocket" =>
        "ROCKET",
      "#VICTIM#(?:\\^[^\\^])? was electrocuted by #KILLER#(?:\\^[^\\^])?$" =>
        "LIGHTNING",
      "#VICTIM#(?:\\^[^\\^])? was railed by #KILLER#(?:\\^[^\\^])?$" =>
        "RAILGUN",
      "#VICTIM#(?:\\^[^\\^])? was melted by #KILLER#(?:\\^[^\\^])?'s plasmagun" =>
        "PLASMA",
      "#VICTIM#(?:\\^[^\\^])? was blasted by #KILLER#(?:\\^[^\\^])?'s BFG" =>
        "BFG",
      "#VICTIM#(?:\\^[^\\^])? tried to invade #KILLER#(?:\\^[^\\^])?'s personal space" =>
        "TELEFRAG",
      "#VICTIM#(?:\\^[^\\^])? blew itself up\\." => "ROCKET",
      "#VICTIM#(?:\\^[^\\^])? blew herself up\\." => "ROCKET",
      "#VICTIM#(?:\\^[^\\^])? blew himself up\\." => "ROCKET",
      "#VICTIM#(?:\\^[^\\^])? tripped on his own grenade\\." => "GRENADE",
      "#VICTIM#(?:\\^[^\\^])? tripped on her own grenade\\." => "GRENADE",
      "#VICTIM#(?:\\^[^\\^])? tripped on its own grenade\\." => "GRENADE",
      "#VICTIM#(?:\\^[^\\^])? melted himself\\." => "PLASMA",
      "#VICTIM#(?:\\^[^\\^])? melted herself\\." => "PLASMA",
      "#VICTIM#(?:\\^[^\\^])? melted itself\\." => "PLASMA",
      "#VICTIM#(?:\\^[^\\^])? should have used a smaller gun\\." => "BFG",
      "#VICTIM#(?:\\^[^\\^])? killed himself\\." => "SUICIDE",
      "#VICTIM#(?:\\^[^\\^])? killed herself\\." => "SUICIDE",
      "#VICTIM#(?:\\^[^\\^])? killed itself\\." => "SUICIDE",
      "#VICTIM#(?:\\^[^\\^])? cratered\\." => "FALLING",
      "#VICTIM#(?:\\^[^\\^])? does a back flip into the lava\\." => "LAVA",
      "#VICTIM#(?:\\^[^\\^])? was squished\\." => "CRUSH",
      "#VICTIM#(?:\\^[^\\^])? sank like a rock\\." => "WATER",
      "#VICTIM#(?:\\^[^\\^])? melted\\." => "SLIME",
      "#VICTIM#(?:\\^[^\\^])? was in the wrong place\\." => "TRIGGER_HURT",
      "#VICTIM#(?:\\^[^\\^])? was disemboweled by #KILLER#(?:\\^[^\\^])?'s grappling hook" =>
        "GRAPPLE",
      "#VICTIM#(?:\\^[^\\^])? was impaled by #KILLER#(?:\\^[^\\^])?'s shower of nails" =>
        "NAILGUN",
      "#VICTIM#(?:\\^[^\\^])? was swimming too close to #KILLER#(?:\\^[^\\^])?$" =>
        "DISCHARGE",
      "#VICTIM#(?:\\^[^\\^])? pressed the wrong button\\." => "DISCHARGE",
    ];
    define("LOG_READ_SIZE", 1024);
    $this->initializeConfig($configData);
    $this->statsAggregator = $statsAggregator;
    $this->statsProcessor = $statsProcessor;
    $this->currentPlayerData = [];
    $this->logInfo = [];
    $this->playerAliases = [];
    $this->logdata = [];
    $this->gameInProgress = false;
  }

  // Initialize configuration from given data array.
  function initializeConfig($configData)
  {
    $this->config["savestate"] = 0;
    $this->config["gametype"] = "";
    $this->config["backuppath"] = "";
    $this->config["trackID"] = "playerName";
    if (is_array($configData)) {
      foreach ($configData as $key => $value) {
        $this->config[$key] = $value;
      }
    }
    if ($this->config["backuppath"]) {
      $this->config["backuppath"] = ensureTrailingSlash(
        $this->config["backuppath"]
      );
    }
    print_r($this->config);
  }

  // Reset player alias and session data, and initialize base time parts.
  function resetSessionData()
  {
    unset($this->playerAliases);
    $this->playerAliases = [];
    unset($this->currentPlayerData);
    $this->currentPlayerData = [];
    $this->baseTimeParts["month"] = 12;
    $this->baseTimeParts["date"] = 28;
    $this->baseTimeParts["year"] = 1971;
    $this->baseTimeParts["hour"] = 23;
    $this->baseTimeParts["min"] = 59;
    $this->baseTimeParts["sec"] = 59;
  }

  // Process and save shutdown state (hash and file position)
  function saveShutdownState()
  {
    $this->logdata["last_shutdown_end_position"] = ftell($this->logFileHandle);
    $seekResult = fseek($this->logFileHandle, -LOG_READ_SIZE, SEEK_CUR);
    if ($seekResult == 0) {
      $this->logdata["last_shutdown_hash"] = md5(
        fread($this->logFileHandle, LOG_READ_SIZE)
      );
    } else {
      $currentPosition = ftell($this->logFileHandle);
      fseek($this->logFileHandle, 0);
      $this->logdata["last_shutdown_hash"] = md5(
        fread($this->logFileHandle, $currentPosition)
      );
    }
    $savestateFile = fopen(
      "./logdata/savestate_" .
        sanitizeFilename($this->logFilePath) .
        ".inc.php",
      "wb"
    );
    fwrite($savestateFile, "<?php \n");
    fwrite(
      $savestateFile,
      "\$this->logdata['last_shutdown_hash']='{$this->logdata["last_shutdown_hash"]}';\n"
    );
    fwrite(
      $savestateFile,
      "\$this->logdata['last_shutdown_end_position']={$this->logdata["last_shutdown_end_position"]};\n"
    );
    fwrite($savestateFile, "?>");
    fclose($savestateFile);
  }

  // Verify the saved state by comparing the shutdown hash.
  function verifySavestate()
  {
    echo "Verifying savestate\n";
    $savestateFile = fopen($this->logFilePath, "rb");
    $seekResult = fseek(
      $savestateFile,
      $this->logdata["last_shutdown_end_position"]
    );
    if ($seekResult == 0) {
      $seekBackResult = fseek($savestateFile, -LOG_READ_SIZE, SEEK_CUR);
      if ($seekBackResult == 0) {
        $hashBlock = fread($savestateFile, LOG_READ_SIZE);
      } else {
        $currentPosition = ftell($savestateFile);
        fseek($savestateFile, 0);
        $hashBlock = fread($savestateFile, $currentPosition);
      }
      if (strcmp(md5($hashBlock), $this->logdata["last_shutdown_hash"]) == 0) {
        echo " - Hash matched, resuming parsing from previous saved location in log file\n";
        fseek(
          $this->logFileHandle,
          $this->logdata["last_shutdown_end_position"]
        );
      } else {
        echo " - Hash did not match, assuming new log file\n";
        fseek($this->logFileHandle, 0);
      }
    } else {
      echo " - Seek to prior location failed, assuming new log file\n";
      fseek($this->logFileHandle, 0);
    }
    fclose($savestateFile);
  }

  // Open and process the log file.
  function processLogFile($logFileName)
  {
    $this->logFilePath = realpath($logFileName);
    if (!file_exists($this->logFilePath)) {
      errorAndExit("error: log file \"{$logFileName}\" does not exist");
    }
    $this->resetSessionData();
    if ($this->config["savestate"] == 1) {
      echo "savestate 1 processing enabled\n";
      @include_once "./logdata/savestate_" .
        sanitizeFilename($this->logFilePath) .
        ".inc.php";
      $this->logFileHandle = fopen($this->logFilePath, "rb");
      if (!empty($this->logdata)) {
        $this->verifySavestate($this->logFilePath);
      }
    } else {
      $this->logFileHandle = fopen($this->logFilePath, "rb");
    }
    if (!$this->logFileHandle) {
      debugPrint("error: {this->logfile} could not be opened");
      return;
    }
    $this->logInfo["logfile_size"] = filesize($this->logFilePath);
    while (!feof($this->logFileHandle)) {
      $this->currentFilePosition = ftell($this->logFileHandle);
      $line = fgets($this->logFileHandle, cBIG_STRING_LENGTH);
      $line = rtrim($line, "\r\n");
      $this->processLogLine($line);
    }
    fclose($this->logFileHandle);
  }

  // Remove color codes from a string.
  function removeColorCodes($str)
  {
    $cleanStr = preg_replace("/\\^[xX][\da-fA-F]{6}/", "", $str);
    $cleanStr = preg_replace("/\\^[^\\^]/", "", $cleanStr);
    return $cleanStr;
  }

  // Convert color codes in a string to a new format.
  function convertColorCodes($str)
  {
    $enableColor = 1;
    $i = 0;
    $charCode = 0;
    $strLength = strlen($str);
    if ($strLength < 1) {
      return " ";
    }
    if ($enableColor) {
      $resultStr = "`#FFFFFF";
    }
    for ($i = 0; $i < $strLength - 1; $i++) {
      if ($str[$i] == "^" && $str[$i + 1] != "^") {
        $charCode = ord($str[$i + 1]);
        if ($enableColor) {
          if (
            $charCode == 70 ||
            $charCode == 102 ||
            $charCode == 66 ||
            $charCode == 98 ||
            $charCode == 78
          ) {
            $i++;
            continue;
          }
          if (($charCode == 88 || $charCode == 120) && strlen($str) - $i > 6) {
            if (
              preg_match(
                "/^[\da-fA-F]{6}/",
                substr($str, $i + 2, 6),
                $matchColor
              )
            ) {
              $resultStr .= "`#";
              $resultStr .= substr($str, $i + 2, 6);
              $i += 7;
              continue;
            }
          }
          switch ($charCode % 8) {
            case 0:
              $resultStr .= "`#777777";
              break;
            case 1:
              $resultStr .= "`#FF0000";
              break;
            case 2:
              $resultStr .= "`#00FF00";
              break;
            case 3:
              $resultStr .= "`#FFFF00";
              break;
            case 4:
              $resultStr .= "`#4444FF";
              break;
            case 5:
              $resultStr .= "`#00FFFF";
              break;
            case 6:
              $resultStr .= "`#FF00FF";
              break;
            case 7:
              $resultStr .= "`#FFFFFF";
              break;
          }
        }
        $i++;
      } else {
        $resultStr .= $str[$i];
      }
    }
    if ($i < $strLength) {
      $resultStr .= $str[$i];
    }
    return $resultStr;
  }

  // Generate a formatted timestamp based on the raw timestamp and base time parts.
  function generateTimestamp()
  {
    if (preg_match("/^(\d+):(\d+)/", $this->rawTimestamp, $matchTime)) {
      $timeOffset["min"] = $matchTime[1];
      $timeOffset["sec"] = $matchTime[2];
      return date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $this->baseTimeParts["hour"],
          $this->baseTimeParts["min"] + $timeOffset["min"],
          $this->baseTimeParts["sec"] + $timeOffset["sec"],
          $this->baseTimeParts["month"],
          $this->baseTimeParts["date"],
          $this->baseTimeParts["year"]
        )
      );
    } elseif (preg_match("/^(\d+).(\d+)/", $this->rawTimestamp, $matchTime)) {
      $timeOffset["min"] = 0;
      $timeOffset["sec"] = $matchTime[1];
      return date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $this->baseTimeParts["hour"],
          $this->baseTimeParts["min"] + $timeOffset["min"],
          $this->baseTimeParts["sec"] + $timeOffset["sec"],
          $this->baseTimeParts["month"],
          $this->baseTimeParts["date"],
          $this->baseTimeParts["year"]
        )
      );
    } elseif (
      preg_match("/^(\d+):(\d+):(\d+)/", $this->rawTimestamp, $matchTime)
    ) {
      $timeOffset["hour"] = $matchTime[1];
      $timeOffset["min"] = $matchTime[2];
      $timeOffset["sec"] = $matchTime[3];
      return date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $timeOffset["hour"],
          $timeOffset["min"],
          $timeOffset["sec"],
          $this->baseTimeParts["month"],
          $this->baseTimeParts["date"],
          $this->baseTimeParts["year"]
        )
      );
    }
  }

  // Process game initialization messages.
  function processGameInit(&$line)
  {
    foreach ($this->gameStartPatterns as $pattern) {
      $regex = "/" . $pattern . "/";
      if (preg_match($regex, $line, $match)) {
        if ($this->gameInProgress) {
          debugPrint("corrupt game (no Shutdown after Init), ignored\n");
          debugPrint("{$this->rawTimestamp} $line\n");
          $this->statsProcessor->updatePlayerStreaks();
          $this->statsProcessor->clearProcessorData();
        }
        $this->gameInProgress = true;
        $this->gameStartFilePosition = $this->currentFilePosition;
        $this->resetSessionData();
        $this->statsProcessor->startGameAnalysis();
        $this->statsProcessor->setGameData(
          "_v_time_start",
          date("Y-m-d H:i:s")
        );
        $this->statsProcessor->setGameData("_v_map", "?");
        $this->statsProcessor->setGameData("_v_game", "q3a");
        if (isset($this->logInfo["mod"])) {
          $this->statsProcessor->setGameData("_v_mod", $this->logInfo["mod"]);
        } else {
          $this->statsProcessor->setGameData("_v_mod", "?");
        }
        $this->statsProcessor->setGameData("_v_game_type", "?");
        return true;
      }
    }
    return false;
  }

  // Process accuracy and damage info from the log.
  function processAccuracyAndDamage(&$line)
  {
    while (!feof($this->logFileHandle)) {
      $this->currentFilePosition = ftell($this->logFileHandle);
      $line = fgets($this->logFileHandle, cBIG_STRING_LENGTH);
      $line = rtrim($line, "\r\n");
      if (
        preg_match(
          "/^Accuracy info for\\: (?:\\^[^\\^])?(.*?)(?:\\^[^\\^])?$/",
          $line,
          $matchPlayer
        )
      ) {
        $currentPlayer = $matchPlayer[1];
        continue;
      }
      $line = $this->removeColorCodes($line);
      if (
        preg_match(
          "/^(.*?) *\\: *(\d+\\.\d+) *(\d+)\\/(\d+) */",
          $line,
          $matchAccuracy
        )
      ) {
        $weaponName = $matchAccuracy[1];
        $hits = $matchAccuracy[3];
        $shots = $matchAccuracy[4];
        if (!strcmp($weaponName, "MachineGun")) {
          $weaponName = "MACHINEGUN";
        } elseif (!strcmp($weaponName, "Shotgun")) {
          $weaponName = "SHOTGUN";
        } elseif (!strcmp($weaponName, "G.Launcher")) {
          $weaponName = "GRENADE";
        } elseif (!strcmp($weaponName, "R.Launcher")) {
          $weaponName = "ROCKET";
        } elseif (!strcmp($weaponName, "LightningGun")) {
          $weaponName = "LIGHTNING";
        } elseif (!strcmp($weaponName, "Railgun")) {
          $weaponName = "RAILGUN";
        } elseif (!strcmp($weaponName, "Plasmagun")) {
          $weaponName = "PLASMA";
        } else {
          $weaponName = preg_replace("/^MOD_/", "", $weaponName);
        }
        $this->statsProcessor->updateAccuracyEvent(
          $currentPlayer,
          $currentPlayer,
          "accuracy|{$weaponName}_hits",
          $hits
        );
        $this->statsProcessor->updateAccuracyEvent(
          $currentPlayer,
          $currentPlayer,
          "accuracy|{$weaponName}_shots",
          $shots
        );
      } elseif (
        preg_match("/^Total damage given\\: (.*)$/", $line, $matchDamage)
      ) {
        $this->statsProcessor->updatePlayerEvent(
          $currentPlayer,
          "damage given",
          $matchDamage[1]
        );
      } elseif (
        preg_match("/^Total damage rcvd \\: (.*)$/", $line, $matchDamage)
      ) {
        $this->statsProcessor->updatePlayerEvent(
          $currentPlayer,
          "damage taken",
          $matchDamage[1]
        );
      } elseif (preg_match("/^Map\\: (.*)/", $line, $matchMap)) {
        $this->statsProcessor->setGameData("_v_map", $matchMap[1]);
        return true;
      } elseif (preg_match("/entered the game/", $line, $match)) {
        return true;
      }
    }
    return true;
  }

  // Process shutdown messages and finish the game.
  function processGameShutdown(&$line)
  {
    foreach ($this->shutdownPatterns as $pattern) {
      $regex = "/" . $pattern . "/";
      if (preg_match($regex, $line, $match)) {
        $this->processAccuracyAndDamage($line);
        if ($this->config["savestate"] == 1) {
          $this->saveShutdownState();
        }
        $this->statsProcessor->updatePlayerStreaks();
        $this->statsAggregator->storeGameData(
          $this->statsProcessor->getPlayerStats(),
          $this->statsProcessor->getGameData()
        );
        $this->statsProcessor->clearProcessorData();
        $this->gameInProgress = false;
        return true;
      }
    }
    return false;
  }

  // Retrieve an alias for a given player identifier.
  function lookupPlayerAlias($playerIdentifier)
  {
    foreach ($this->playerAliases as $aliasKey => $aliasData) {
      if (strstr($aliasKey, $playerIdentifier)) {
        return $aliasKey;
      }
    }
    return $playerIdentifier;
  }

  // Process player entering the game.
  function processPlayerEnter(&$line)
  {
    foreach ($this->playerEnterPatterns as $pattern) {
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#PLAYER#", "(.*?)", $regex);
      if (preg_match($regex, $line, $match)) {
        $this->playerAliases[$match[1]]["name"] = $this->convertColorCodes(
          $match[1]
        );
        $this->statsProcessor->initializePlayerData(
          $match[1],
          $this->convertColorCodes($match[1])
        );
        return false;
      }
    }
    return false;
  }

  // Process player team assignment messages.
  function processPlayerTeamAssignment(&$line)
  {
    $playerName = "";
    $teamName = "";
    $regex = "/" . $this->playerTeamEnterPatterns[0] . "/";
    $regex = str_replace("#PLAYER#", "(.*?)", $regex);
    $regex = str_replace("#TEAM#", ".+", $regex);
    if (preg_match($regex, $line, $match)) {
      $playerName = $match[1];
    }
    $regex = "/" . $this->playerTeamEnterPatterns[0] . "/";
    $regex = str_replace("#PLAYER#", ".*", $regex);
    $regex = str_replace("#TEAM#", "(.+?)", $regex);
    if (preg_match($regex, $line, $match)) {
      $teamName = $match[1];
    }
    if (strlen($playerName) > 0 && strlen($teamName) > 0) {
      if ($this->removeColorCodes($teamName) == "RED") {
        $teamName = "1";
      } elseif ($this->removeColorCodes($teamName) == "BLUE") {
        $teamName = "2";
      }
      $this->statsProcessor->updatePlayerTeam($playerName, $teamName);
      return true;
    }
    return false;
  }

  // Process kill events.
  function processKillEvent(&$line)
  {
    foreach ($this->killRegexPatterns as $pattern => $weapon) {
      $victim = "";
      $killer = "";
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#VICTIM#", "(.*?)", $regex);
      $regex = str_replace("#KILLER#", ".*", $regex);
      if (preg_match($regex, $line, $match)) {
        $victim = $match[1];
        if (strlen($victim) >= 29) {
          $victim = $this->lookupPlayerAlias($victim);
        }
      }
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#VICTIM#", ".*", $regex);
      $regex = str_replace("#KILLER#", "(.*?)", $regex);
      if (preg_match($regex, $line, $match)) {
        if (isset($match[1])) {
          $killer = $match[1];
          if (strlen($killer) >= 29) {
            $killer = $this->lookupPlayerAlias($killer);
          }
        }
      }
      if (strlen($victim) > 0 && strlen($killer) > 0) {
        $this->statsProcessor->processKillEvent($killer, $victim, $weapon);
        return true;
      } elseif (strlen($victim) > 0) {
        $this->statsProcessor->processKillEvent($victim, $victim, $weapon);
        return true;
      } else {
      }
    }
    return false;
  }

  // Process CTF events.
  function processCTFEvent(&$line)
  {
    foreach ($this->ctfEventPatterns as $pattern => $eventType) {
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#PLAYER#", "(.*?)", $regex);
      if (preg_match($regex, $line, $match)) {
        $this->statsProcessor->updatePlayerEvent($match[1], $eventType, 1);
        return true;
      }
    }
    return false;
  }

  // Process rename (alias) events.
  function processRenameEvent(&$line)
  {
    foreach ($this->renamePatterns as $pattern) {
      $playerName = "";
      $newName = "";
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#PLAYER#", "(.*?)", $regex);
      $regex = str_replace("#NAME#", ".+", $regex);
      if (preg_match($regex, $line, $match)) {
        $playerName = $match[1];
      }
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#PLAYER#", ".*", $regex);
      $regex = str_replace("#NAME#", "(.*)", $regex);
      if (preg_match($regex, $line, $match)) {
        $newName = $match[1];
      }
      if (strlen($playerName) > 0 && strlen($newName) > 0) {
        $formattedName = $this->convertColorCodes($newName);
        $this->statsProcessor->updatePlayerDataField(
          "sto",
          $playerName,
          "alias",
          $formattedName
        );
        $this->statsProcessor->updatePlayerName($playerName, $formattedName);
        $this->statsProcessor->resolvePlayerIDConflict($playerName, $newName);
        return true;
      }
    }
    return false;
  }

  // Process chat messages.
  function processChatMessage(&$line)
  {
    foreach ($this->chatPatterns as $pattern) {
      $playerName = "";
      $chatMessage = "";
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#PLAYER#", "(.*?)", $regex);
      $regex = str_replace("#CHAT#", ".+", $regex);
      if (preg_match($regex, $line, $match)) {
        $playerName = $match[1];
      }
      $regex = "/" . $pattern . "/";
      $regex = str_replace("#PLAYER#", ".*", $regex);
      $regex = str_replace("#CHAT#", "(.*?)", $regex);
      if (preg_match($regex, $line, $match)) {
        $chatMessage = $match[1];
      }
      if (strlen($playerName) > 0 && strlen($chatMessage) > 0) {
        $this->statsProcessor->updatePlayerQuote(
          $playerName,
          $this->removeColorCodes($chatMessage)
        );
        return true;
      }
    }
    return false;
  }

  // Process GUID assignment events.
  function processGUIDAssignment(&$line)
  {
    if (
      !preg_match(
        "/^\\^?\d+ *([\da-fA-F]*)\\((.*?)\\) .*? *\d+\\.\d+ \d+ (.*)$/",
        $line,
        $match
      )
    ) {
      return false;
    }
    $this->statsProcessor->updatePlayerDataField(
      "sto",
      $match[3],
      "guid",
      $match[1]
    );
    return true;
  }

  // Process OSP events (stub – not implemented).
  function processOSPEvent(&$line)
  {
    return false;
  }

  // Process Threewave events (stub – not implemented).
  function processThreewaveEvent(&$line)
  {
    return false;
  }

  // Process Freeze events (stub – not implemented).
  function processFreezeEvent(&$line)
  {
    return false;
  }

  // Process RA3 events (stub – not implemented).
  function processRA3Event(&$line)
  {
    return false;
  }

  // Process UT events (stub – not implemented).
  function processUTEvent(&$line)
  {
    return false;
  }

  // Dispatch event processing based on game type.
  function dispatchGameTypeEvent(&$line)
  {
    if (!strcmp($this->config["gametype"], "osp")) {
      return $this->processOSPEvent($line);
    } elseif (!strcmp($this->config["gametype"], "threewave")) {
      if ($this->processOSPEvent($line)) {
        return true;
      } elseif ($this->processThreewaveEvent($line)) {
        return true;
      } else {
        return false;
      }
    } elseif (!strcmp($this->config["gametype"], "freeze")) {
      if ($this->processOSPEvent($line)) {
        return true;
      } elseif ($this->processFreezeEvent($line)) {
        return true;
      } else {
        return false;
      }
    } elseif (!strcmp($this->config["gametype"], "ut")) {
      return $this->processUTEvent($line);
    } elseif (!strcmp($this->config["gametype"], "ra3")) {
      return $this->processRA3Event($line);
    }
    return false;
  }

  // Main log line processor – dispatches to various event handlers.
  function processLogLine(&$line)
  {
    if ($this->processGameInit($line)) {
      echo sprintf(
        "(%05.2f%%) ",
        (100.0 * ftell($this->logFileHandle)) / $this->logInfo["logfile_size"]
      );
    } elseif ($this->gameInProgress) {
      if ($this->dispatchGameTypeEvent($line)) {
      } elseif ($this->processPlayerEnter($line)) {
      } elseif ($this->processPlayerTeamAssignment($line)) {
      } elseif ($this->processKillEvent($line)) {
      } elseif ($this->processCTFEvent($line)) {
      } elseif ($this->processRenameEvent($line)) {
      } elseif ($this->processGameShutdown($line)) {
      } elseif ($this->processGUIDAssignment($line)) {
      } elseif ($this->processChatMessage($line)) {
      } else {
      }
    } else {
      if (preg_match("/^Current search path\\:/", $line, $match)) {
        $this->currentFilePosition = ftell($this->logFileHandle);
        $line = fgets($this->logFileHandle, cBIG_STRING_LENGTH);
        $line = rtrim($line, "\r\n");
        if (
          preg_match(
            "/[\\\\\/]([^\\\\\/]*)[\\\\\/][^\\\\\/]*$/",
            $line,
            $matchMod
          )
        ) {
          $this->logInfo["mod"] = $matchMod[1];
        }
      }
    }
  }
} ?>
