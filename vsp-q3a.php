<?php
/* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */
class VSPParserQ3A
{
  var $config;
  var $statsAggregator;
  var $statsProcessor;
  var $playerInfo;
  var $miscStats;
  var $translationData;
  var $rawTimestamp;
  var $baseTime;
  var $gameInProgress;
  var $logFileHandle;
  var $logFilePath;
  var $logdata;
  var $currentFilePosition;
  var $gameStartFilePosition;

  // Constructor: initialize configuration, aggregator and processor.
  function __construct($configData, &$statsAggregator, &$statsProcessor)
  {
    define("LOG_READ_SIZE", 1024);
    $this->initializeConfig($configData);
    $this->statsAggregator = $statsAggregator;
    $this->statsProcessor = $statsProcessor;
    $this->miscStats = [];
    $this->translationData = [];
    $this->playerInfo = [];
    $this->logdata = [];
    $this->gameInProgress = false;
    // Initialize translation arrays for weapon name cleanup and character translations
    $this->translationData["weapon_name"]["search"] = ["/MOD_/", "/_SPLASH/"];
    $this->translationData["weapon_name"]["replace"] = ["", ""];
    $this->translationData["char_trans"] = [
      "^<" => "^4",
      "^>" => "^6",
      "^&" => "^6",
      "\x01" => "(",
      "\x02" => "▀",
      "\x03" => ")",
      "\x04" => "█",
      "\x05" => " ",
      "\x06" => "█",
      "\x07" => "(",
      "\x08" => "▄",
      "\x09" => ")",
      "\x0b" => "_",
      "\x0b" => "■",
      "\x0c" => " ",
      "\x0d" => "►",
      "\x0e" => "·",
      "\x0f" => "·",
      "\x10" => "[",
      "\x11" => "]",
      "\x12" => "|¯",
      "\x13" => "¯",
      "\x14" => "¯|",
      "\x15" => "|",
      "\x16" => " ",
      "\x17" => "|",
      "\x18" => "|_",
      "\x19" => "_",
      "\x1a" => "_|",
      "\x1b" => "¯",
      "\x1c" => "·",
      "\x1d" => "(",
      "\x1e" => "-",
      "\x1f" => ")",
      "\x7f" => "<-",
      "\x80" => "(",
      "\x81" => "=",
      "\x82" => ")",
      "\x83" => "|",
      "\x84" => " ",
      "\x85" => "·",
      "\x86" => "▼",
      "\x87" => "▲",
      "\x88" => "◄",
      "\x89" => " ",
      "\x8a" => " ",
      "\x8b" => "■",
      "\x8c" => " ",
      "\x8d" => "►",
      "\x8e" => "·",
      "\x8f" => "·",
      "\x90" => "[",
      "\x91" => "]",
      "\x92" => "0",
      "\x93" => "1",
      "\x94" => "2",
      "\x95" => "3",
      "\x96" => "4",
      "\x97" => "5",
      "\x98" => "6",
      "\x99" => "7",
      "\x9a" => "8",
      "\x9b" => "9",
      "\x9c" => "·",
      "\x9d" => "(",
      "\x9e" => "-",
      "\x9f" => ")",
      "\xa0" => " ",
      "\xa1" => "!",
      "\xa2" => "\"",
      "\xa3" => "#",
      "\xa4" => "$",
      "\xa5" => "%",
      "\xa6" => "&",
      "\xa7" => "'",
      "\xa8" => "(",
      "\xa9" => ")",
      "\xaa" => "*",
      "\xab" => "+",
      "\xac" => ",",
      "\xad" => "-",
      "\xae" => ".",
      "\xaf" => "/",
      "\xb0" => "0",
      "\xb1" => "1",
      "\xb2" => "2",
      "\xb3" => "3",
      "\xb4" => "4",
      "\xb5" => "5",
      "\xb6" => "6",
      "\xb7" => "7",
      "\xb8" => "8",
      "\xb9" => "9",
      "\xba" => ":",
      "\xbb" => ";",
      "\xbc" => "<",
      "\xbd" => "=",
      "\xbe" => ">",
      "\xbf" => "?",
      "\xc0" => "@",
      "\xc1" => "A",
      "\xc2" => "B",
      "\xc3" => "C",
      "\xc4" => "D",
      "\xc5" => "E",
      "\xc6" => "F",
      "\xc7" => "G",
      "\xc8" => "H",
      "\xc9" => "I",
      "\xca" => "J",
      "\xcb" => "K",
      "\xcc" => "L",
      "\xcd" => "M",
      "\xce" => "N",
      "\xcf" => "O",
      "\xd0" => "P",
      "\xd1" => "Q",
      "\xd2" => "R",
      "\xd3" => "S",
      "\xd4" => "T",
      "\xd5" => "U",
      "\xd6" => "V",
      "\xd7" => "W",
      "\xd8" => "X",
      "\xd9" => "Y",
      "\xda" => "Z",
      "\xdb" => "[",
      "\xdc" => "\\",
      "\xdd" => "]",
      "\xde" => "^",
      "\xdf" => "_",
      "\xe0" => "'",
      "\xe1" => "A",
      "\xe2" => "B",
      "\xe3" => "C",
      "\xe4" => "D",
      "\xe5" => "E",
      "\xe6" => "F",
      "\xe7" => "G",
      "\xe8" => "H",
      "\xe9" => "I",
      "\xea" => "J",
      "\xeb" => "K",
      "\xec" => "L",
      "\xed" => "M",
      "\xee" => "N",
      "\xef" => "O",
      "\xf0" => "P",
      "\xf1" => "Q",
      "\xf2" => "R",
      "\xf3" => "S",
      "\xf4" => "T",
      "\xf5" => "U",
      "\xf6" => "V",
      "\xf7" => "W",
      "\xf8" => "X",
      "\xf9" => "Y",
      "\xfa" => "Z",
      "\xfb" => "{",
      "\xfc" => "|",
      "\xfd" => "}",
      "\xfe" => "\"",
      "\xff" => "->",
    ];
  }

  // Initialize configuration from given data array.
  function initializeConfig($configData)
  {
    $this->config["savestate"] = 0;
    $this->config["gametype"] = "";
    $this->config["backuppath"] = "";
    $this->config["trackID"] = "playerName";
    //change: xp version for special chars
    $this->config["xp_version"] = 200;
    //endchange
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
    echo "[parser options]: ";
    print_r($this->config);
  }

  // Reset auxiliary variables (e.g. date/time parts) for a new game session.
  function resetSessionData()
  {
    // supongo que inicia variables auxiliares para la fecha
    unset($this->playerInfo);
    $this->playerInfo = [];
    unset($this->miscStats);
    $this->miscStats = [];
    $this->baseTime["month"] = 12;
    $this->baseTime["date"] = 28;
    $this->baseTime["year"] = 1971;
    $this->baseTime["hour"] = 23;
    $this->baseTime["min"] = 59;
    $this->baseTime["sec"] = 59;
  }

  // Write shutdown savestate information – unused.
  function saveShutdownState()
  {
    // escribe información del savestate - unused
    $this->logdata["last_shutdown_end_position"] = ftell($this->logFileHandle);
    $seekResult = fseek($this->logFileHandle, -LOG_READ_SIZE, SEEK_CUR);
    if ($seekResult == 0) {
      $this->logdata["last_shutdown_hash"] = md5(
        fread($this->logFileHandle, LOG_READ_SIZE)
      );
    } else {
      $currentPos = ftell($this->logFileHandle);
      fseek($this->logFileHandle, 0);
      $this->logdata["last_shutdown_hash"] = md5(
        fread($this->logFileHandle, $currentPos)
      );
    }
    $savestateFile = fopen(
      "./logdata/savestate_" .
        sanitizeFilename($this->logFilePath) .
        ".inc.php",
      "wb"
    );
    fwrite($savestateFile, "<?php /" . "* DO NOT EDIT THIS FILE! */\n");
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

  // Verify savestate (unused).
  function verifySavestate()
  {
    // savestate check - unused
    echo "Verifying savestate\n";
    $fileHandle = fopen($this->logFilePath, "rb");
    $seekResult = fseek(
      $fileHandle,
      $this->logdata["last_shutdown_end_position"]
    );
    if ($seekResult == 0) {
      $seekBack = fseek($fileHandle, -LOG_READ_SIZE, SEEK_CUR);
      if ($seekBack == 0) {
        $hashBlock = fread($fileHandle, LOG_READ_SIZE);
      } else {
        $curPos = ftell($fileHandle);
        fseek($fileHandle, 0);
        $hashBlock = fread($fileHandle, $curPos);
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
    fclose($fileHandle);
  }

  // Open and process the log file.
  function processLogFile($logFileName)
  {
    $this->logFilePath = realpath($logFileName);
    if (!file_exists($this->logFilePath)) {
      errorAndExit("error: log file \"{$logFileName}\" does not exist");
    }

    //change: excessiveplus 1.03 fix
    $this->original_log = $this->logFilePath;
    if (
      !strcmp($this->config["gametype"], "xp") &&
      $this->config["xp_version"] == 103
    ) {
      require "xp103_compat.inc.php";
      $this->logFilePath = repair_xp_logfile(
        $this->logFilePath,
        $this->config["savestate"] == 1
      );
    }
    //endchange

    $this->resetSessionData();
    if ($this->config["savestate"] == 1) {
      //change: savestate centralized and now db-driven
      echo "savestate 1 processing enabled\n";
      global $db; // db
      $sql =
        "SELECT `value` FROM {$GLOBALS["cfg"]["db"]["table_prefix"]}savestate WHERE `logfile` = " .
        $db->qstr($this->original_log);
      $rs = $db->Execute($sql);
      if ($rs and !$rs->EOF) {
        eval($rs->fields[0]);
      }
      //@include_once('./logdata/savestate_' . sanitizeFilename($this->logFilePath) . '.inc.php');
      $this->logFileHandle = fopen($this->logFilePath, "rb");
      if (!empty($this->logdata)) {
        //$this->verifySavestate();
        check_savestate($this);
      }
      //endchange
    } else {
      $this->logFileHandle = fopen($this->logFilePath, "rb");
    }
    if (!$this->logFileHandle) {
      debugPrint("error: {this->logfile} could not be opened");
      return;
    }
    $this->translationData["logfile_size"] = filesize($this->logFilePath);
    while (!feof($this->logFileHandle)) {
      // ciclo que parsea el archivo
      $this->currentFilePosition = ftell($this->logFileHandle); // obtiene posición del puntero
      $line = fgets($this->logFileHandle, cBIG_STRING_LENGTH); // obtiene línea del archivo
      $line = rtrim($line, "\r\n");
      $this->processLogLine($line); // parsea la línea
    }
    fclose($this->logFileHandle);
    if (
      isset($this->original_log) &&
      function_exists("remove_xp_tmp_logfile")
    ) {
      //change: xp 1.03 fix
      remove_xp_tmp_logfile($this->logFilePath);
    }
  }

  // Remove color codes from a string.
  function removeColorCodes($str)
  {
    $cleanStr = preg_replace("/\\^[xX][\da-fA-F]{6}/", "", $str);
    $cleanStr = preg_replace("/\\^[^\\^]/", "", $cleanStr);
    return $cleanStr;
  }

  // Convert XP-specific color codes.
  function convertXPColorCodes($str)
  {
    //$colors = array("black", "red", "lime", "yellow", "blue", "aqua", "fuchsia", "white", "orange");
    //change: special chars
    if ($this->config["xp_version"] <= 103) {
      // 1.03 special chars
      $str = preg_replace("/\+([\x01-\x7F])#/e", "chr(ord('\\1') + 127)", $str);
    } else {
      // 1.04 special chars
      $str = preg_replace(
        "/#(#|[0-9a-f]{2})/ie",
        "'\\1' == '#' ? '#' : chr(hexdec('\\1'))",
        $str
      );
    }
    //endchange
    $defaultColors = [
      "#555555",
      "#e90000",
      "#00dd24",
      "#f5d800",
      "#2e61c8",
      "#16b4a5",
      "#f408f1",
      "#efefef",
      "#ebbc1b",
    ];
    $tmp = ["\xde" => "^"];
    $str = strtr(
      $str,
      array_diff_assoc($this->translationData["char_trans"], $tmp)
    );
    if ($str[0] != "^") {
      $str = "^7" . $str;
    }
    $str = preg_replace("/\^(a[1-9]|[fFrRbBl])/", "", $str);
    $str = preg_replace("/\^s(\^x[a-fA-F0-9]{6}|\^[^\^])/", "\\1", $str);
    $str = preg_replace("/\^s/", "^7", $str);
    $str = preg_replace(
      "/(\^(x[a-fA-F0-9]{6}|[^\^]))\^(x[a-fA-F0-9]{6}|[^\^])/",
      "\\1",
      $str
    );
    $str = preg_replace("/\^x([a-fA-F0-9]{6})/i", "`#\\1", $str);
    $str = preg_replace(
      "/\^([^\^<])/e",
      "'`' . \$defaultColors[ord('\\1') % 8]",
      $str
    );
    $str = strtr($str, $tmp);
    return $str;
  }

  // Convert color codes (delegates to XP conversion if gametype is xp).
  function convertColorCodes($str)
  {
    // parece eliminar efectos del nombre
    if (!strcmp($this->config["gametype"], "xp")) {
      return $this->convertXPColorCodes($str);
    }
    $str = strtr($str, $this->translationData["char_trans"]);
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

  // Lookup a player by matching the given id against stored playerInfo.
  function lookupPlayerById($playerId)
  {
    foreach ($this->playerInfo as $key => $pInfo) {
      if (!strcmp($this->playerInfo[$key]["id"], $playerId)) {
        return $key;
      }
    }
    return "";
  }

  // Lookup a player by matching the given name.
  function lookupPlayerByName($name)
  {
    foreach ($this->playerInfo as $key => $pInfo) {
      if (!strcmp($this->playerInfo[$key]["name"], $name)) {
        return $key;
      }
    }
    return "";
  }

  // Generate a formatted timestamp based on the raw timestamp and base time parts.
  function generateTimestamp()
  {
    // devuelve hora del servidor como timestamp
    if (
      preg_match(
        "/^(\d+)[\\:\\.](\d+)[\\:\\.](\d+)/",
        $this->rawTimestamp,
        $match
      )
    ) {
      $timeParts["hour"] = $match[1];
      $timeParts["min"] = $match[2];
      $timeParts["sec"] = $match[3];
      return date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $timeParts["hour"],
          $timeParts["min"],
          $timeParts["sec"],
          $this->baseTime["month"],
          $this->baseTime["date"],
          $this->baseTime["year"]
        )
      );
    } elseif (preg_match("/^(\d+):(\d+)/", $this->rawTimestamp, $match)) {
      $timeParts["min"] = $match[1];
      $timeParts["sec"] = $match[2];
      return date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $this->baseTime["hour"],
          $this->baseTime["min"] + $timeParts["min"],
          $this->baseTime["sec"] + $timeParts["sec"],
          $this->baseTime["month"],
          $this->baseTime["date"],
          $this->baseTime["year"]
        )
      );
    } elseif (preg_match("/^(\d+).(\d+)/", $this->rawTimestamp, $match)) {
      $timeParts["min"] = 0;
      $timeParts["sec"] = $match[1];
      return date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $this->baseTime["hour"],
          $this->baseTime["min"] + $timeParts["min"],
          $this->baseTime["sec"] + $timeParts["sec"],
          $this->baseTime["month"],
          $this->baseTime["date"],
          $this->baseTime["year"]
        )
      );
    }
  }

  // Process server time line.
  function processServerTime(&$line)
  {
    // hora del servidor
    if (
      !preg_match(
        "/^ServerTime:\s+(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})\s+/",
        $line,
        $match
      )
    ) {
      return false;
    }
    $this->baseTime["year"] = $match[1];
    $this->baseTime["month"] = $match[2];
    $this->baseTime["date"] = $match[3];
    $this->baseTime["hour"] = $match[4];
    $this->baseTime["min"] = $match[5];
    $this->baseTime["sec"] = $match[6];
    $this->statsProcessor->setGameData(
      "_v_time_start",
      date(
        "Y-m-d H:i:s",
        adodb_mktime(
          $this->baseTime["hour"],
          $this->baseTime["min"],
          $this->baseTime["sec"],
          $this->baseTime["month"],
          $this->baseTime["date"],
          $this->baseTime["year"]
        )
      )
    );
    return true;
  }

  // Process game initialization.
  function processGameInit(&$line)
  {
    // es inicio de juego
    if (!preg_match("/^InitGame: (.*)/", $line, $match)) {
      return false;
    }
    if ($this->gameInProgress) {
      debugPrint("corrupt game (no Shutdown after Init), ignored\n");
      debugPrint("{$this->rawTimestamp} $line\n");
      $this->statsProcessor->updatePlayerStreaks();
      $this->statsProcessor->clearProcessorData();
    }
    $this->gameInProgress = true;
    $this->gameStartFilePosition = $this->currentFilePosition;
    $this->resetSessionData();
    $serverVars = $match[1];
    while (
      preg_match("/^\\\(.+)\\\(.+)\\\/U", $serverVars, $varMatch) ||
      preg_match("/^\\\(.+)\\\(.+)/", $serverVars, $varMatch)
    ) {
      // parsea lista de variables del servidor
      $varName = $varMatch[1];
      $varValue = $varMatch[2];
      $serverVars = substr(
        $serverVars,
        strlen($varName) + strlen($varValue) + 2
      );
      if (!strcmp($varName, "gamestartup")) {
        if (
          preg_match(
            "/^(\d+)[-\/](\d+)[-\/](\d+) +(\d+)[:-](\d+)[:-](\d+)/",
            $varValue,
            $timeMatch
          )
        ) {
          $this->baseTime["month"] = $timeMatch[1];
          $this->baseTime["date"] = $timeMatch[2];
          $this->baseTime["year"] = $timeMatch[3];
          $this->baseTime["hour"] = $timeMatch[4];
          $this->baseTime["min"] = $timeMatch[5];
          $this->baseTime["sec"] = $timeMatch[6];
        }
      }
      $serverVarsArray[$varName] = $varValue; // crea matriz de variables
    }
    $this->statsProcessor->startGameAnalysis(); // muestra mensaje de inicio de análisis de juego
    foreach ($serverVarsArray as $key => $value) {
      $this->statsProcessor->setGameData($key, $value);
    }
    $this->statsProcessor->setGameData(
      "_v_time_start",
      $this->generateTimestamp()
    );
    $this->statsProcessor->setGameData("_v_map", $serverVarsArray["mapname"]);
    $this->statsProcessor->setGameData("_v_game", "q3a");
    $this->statsProcessor->setGameData("_v_mod", $serverVarsArray["gamename"]);
    $this->statsProcessor->setGameData(
      "_v_game_type",
      $serverVarsArray["g_gametype"]
    );
    $this->translationData["mod"] = $serverVarsArray["gamename"];
    $this->translationData["gametype"] = $serverVarsArray["g_gametype"];
    $this->translationData["gameversion"] = isset(
      $serverVarsArray["xp_version"]
    )
      ? $serverVarsArray["xp_version"]
      : $serverVarsArray["gameversion"];
    return true;
  }

  // Process client userinfo change.
  function processClientUserinfoChanged(&$line)
  {
    // cambio de la información del player
    if (!preg_match("/^ClientUserinfoChanged: (\d+) (.*)/", $line, $match)) {
      return false;
    }
    $clientId = $match[1]; // id
    $vars = $match[2]; // variables
    while (
      preg_match("/^(.+)\\\(.*)\\\/U", $vars, $varMatch) ||
      preg_match("/^(.+)\\\(.*)/", $vars, $varMatch)
    ) {
      // parsea las variables
      $varName = $varMatch[1];
      $varValue = $varMatch[2];
      $vars = substr($vars, strlen($varName) + strlen($varValue) + 2);
      if (!strcmp($varName, "n")) {
        // nombre
        $newName = $this->convertColorCodes($varValue); // elimina caracteres extraños del nombre
        if (
          isset($this->playerInfo[$clientId]["id"]) &&
          $this->config["trackID"] == "playerName" &&
          strcmp($this->playerInfo[$clientId]["id"], $varValue) != 0
        ) {
          $this->statsProcessor->updatePlayerDataField(
            "sto",
            $this->playerInfo[$clientId]["id"],
            "alias",
            $newName
          );
          $this->statsProcessor->updatePlayerName(
            $this->playerInfo[$clientId]["id"],
            $newName
          );
          $this->statsProcessor->resolvePlayerIDConflict(
            $this->playerInfo[$clientId]["id"],
            $varValue
          );
          $this->playerInfo[$clientId]["id"] = $varValue;
        } elseif (
          isset($this->playerInfo[$clientId]["id"]) &&
          isset($this->playerInfo[$clientId]["name"]) &&
          strcmp($this->playerInfo[$clientId]["name"], $newName) != 0
        ) {
          $this->statsProcessor->updatePlayerDataField(
            "sto",
            $this->playerInfo[$clientId]["id"],
            "alias",
            $newName
          );
          $this->statsProcessor->updatePlayerName(
            $this->playerInfo[$clientId]["id"],
            $newName
          );
        } elseif ($this->config["trackID"] == "playerName") {
          $this->playerInfo[$clientId]["id"] = $varValue;
        } elseif (
          $this->config["trackID"] == "guid" &&
          isset($this->playerInfo[$clientId]["guid"])
        ) {
          $this->playerInfo[$clientId]["id"] =
            $this->playerInfo[$clientId]["guid"]; // track by guid
        } elseif (
          preg_match("/^ip=(.+)/i", $this->config["trackID"], $tmpMatch) &&
          isset($this->playerInfo[$clientId]["ip"]) &&
          preg_match($tmpMatch[1], $this->playerInfo[$clientId]["ip"], $ipMatch)
        ) {
          $this->playerInfo[$clientId]["id"] = $ipMatch[1];
        } else {
          debugPrint("\$cfg['parser']['trackID'] is invalid, ignored\n");
          debugPrint(
            "Use \$cfg['parser']['trackID'] = 'playerName'; in your config\n"
          );
          debugPrint("{$this->rawTimestamp} $line\n");
          $this->statsProcessor->clearProcessorData();
          $this->gameInProgress = false;
          return true;
        }
        $this->playerInfo[$clientId]["name"] = $newName; // asigna el nombre al player
      } elseif (!strcmp($varName, "t")) {
        // equipo
        $this->playerInfo[$clientId]["team"] = $varValue; // asigna el equipo al player
        if ($this->playerInfo[$clientId]["team"] != "3") {
          // no es espectador
          //change: team control
          if (!isset($this->statsProcessor->players_team)) {
            $this->statsProcessor->players_team = [];
            $this->has_acc_stats = [];
          }
          $this->statsProcessor->players_team[$clientId] = [
            "team" => $this->playerInfo[$clientId]["team"],
            "connected" => true,
          ];
          $this->has_acc_stats[$clientId] = false;
          //endchange
          $this->statsProcessor->updatePlayerTeam(
            $this->playerInfo[$clientId]["id"],
            $this->playerInfo[$clientId]["team"]
          );
        }
      } elseif (!strcmp($varName, "model")) {
        // modelo
        if ($this->playerInfo[$clientId]["team"] != "3") {
          // no es espectador
          if (
            !isset($this->playerInfo[$clientId]["icon"]) ||
            strcmp($this->playerInfo[$clientId]["icon"], $varValue)
          ) {
            $this->playerInfo[$clientId]["icon"] = $varValue; // ícono del jugador
          }
        }
      }
    }
    return true;
  }

  // Process client begin event.
  function processClientBegin(&$line)
  {
    // si es inicio de cliente
    if (!preg_match("/^ClientBegin: (\d+)/", $line, $match)) {
      return false;
    }
    $clientId = $match[1]; // id
    if (isset($this->playerInfo[$clientId]["id"])) {
      // si el id existe
      if ($this->playerInfo[$clientId]["team"] != "3") {
        // no es espectador
        if (isset($this->playerInfo[$clientId]["name"])) {
          // si existe el nombre
          $ip = @$this->playerInfo[$clientId]["ip"];
          $tld = isset($this->playerInfo[$clientId]["rtld"])
            ? $this->playerInfo[$clientId]["rtld"]
            : @$this->playerInfo[$clientId]["tld"];
          $this->statsProcessor->initializePlayerData(
            $this->playerInfo[$clientId]["id"],
            $this->playerInfo[$clientId]["name"],
            $ip,
            $tld
          ); // inicializa datos del cliente
        }
        if (isset($this->playerInfo[$clientId]["team"])) {
          // si está en un team
          $this->statsProcessor->updatePlayerTeam(
            $this->playerInfo[$clientId]["id"],
            $this->playerInfo[$clientId]["team"]
          );
        }
        if (isset($this->playerInfo[$clientId]["role"])) {
          // si existe el rol?
          $this->statsProcessor->setPlayerRole(
            $this->playerInfo[$clientId]["id"],
            $this->playerInfo[$clientId]["role"]
          );
        }
        if (isset($this->playerInfo[$clientId]["icon"])) {
          // guarda ícono
          $this->statsProcessor->updatePlayerDataField(
            "sto",
            $this->playerInfo[$clientId]["id"],
            "icon",
            $this->playerInfo[$clientId]["icon"]
          );
        }
        if (isset($this->playerInfo[$clientId]["ip"])) {
          // si tiene ip
          $this->statsProcessor->updatePlayerDataField(
            "sto",
            $this->playerInfo[$clientId]["id"],
            "ip",
            $this->playerInfo[$clientId]["ip"]
          );
        }
        if (isset($this->playerInfo[$clientId]["guid"])) {
          // si tiene guid
          $this->statsProcessor->updatePlayerDataField(
            "sto",
            $this->playerInfo[$clientId]["id"],
            "guid",
            $this->playerInfo[$clientId]["guid"]
          );
        }
        if (isset($this->playerInfo[$clientId]["tld"])) {
          // si tiene tld
          $this->statsProcessor->updatePlayerDataField(
            "sto",
            $this->playerInfo[$clientId]["id"],
            "tld",
            isset($this->playerInfo[$clientId]["rtld"])
              ? $this->playerInfo[$clientId]["rtld"]
              : $this->playerInfo[$clientId]["tld"]
          );
        }
      }
    }
    return true;
  }

  // Process kill event.
  function processKillEvent(&$line)
  {
    // si es notificación de frag
    if (
      !preg_match(
        "/^Kill: (\d+) (\d+) \d+: (.*) killed (.*) by (\w+)/",
        $line,
        $match
      )
    ) {
      return false;
    }
    $attacker = $match[1];
    $victim = $match[2];
    $weapon = $match[5];
    if ($attacker > 128) {
      // suicidio
      $attacker = $victim;
    }
    $weapon = preg_replace(
      $this->translationData["weapon_name"]["search"],
      $this->translationData["weapon_name"]["replace"],
      $weapon
    );
    if (
      isset($this->playerInfo[$attacker]["id"]) &&
      isset($this->playerInfo[$victim]["id"])
    ) {
      // sólo si los 2 players existen
      //change: team control
      if (isset($this->statsProcessor->players_team)) {
        $this->statsProcessor->processKillEvent(
          $attacker,
          $victim,
          $weapon,
          $this->playerInfo
        );
      } else {
        $this->statsProcessor->processKillEvent(
          $this->playerInfo[$attacker]["id"],
          $victim,
          $weapon
        );
      }
      //endchange
    }
    return true;
  }

  // Process item pickup event.
  function processItemPickup(&$line)
  {
    // si es obtención de item
    if (!preg_match("/^Item: (\d+) (.*)/", $line, $match)) {
      return false;
    }
    $clientId = $match[1];
    $item = $match[2];
    $item = preg_replace("/ammo_/", "ammo|", $item, 1);
    $item = preg_replace("/weapon_/", "weapon|", $item, 1);
    $item = preg_replace("/item_/", "item|", $item, 1);
    if (isset($this->playerInfo[$clientId]["id"])) {
      $this->statsProcessor->updatePlayerEvent(
        $clientId,
        $item,
        1,
        $this->playerInfo
      ); // dispara el evento de obtención de item
    }
    return true;
  }

  // Process client chat message.
  function processClientChat(&$line)
  {
    // si es chat del cliente
    if (!preg_match("/^say: (.+): /U", $line, $match)) {
      return false;
    }
    $namePart = $match[1];
    $chatMsg = substr($line, strlen($match[0]));
    $clientKey = $this->lookupPlayerByName($this->convertColorCodes($namePart));
    if (strlen($clientKey) > 0) {
      $chatMsg = $this->removeColorCodes($chatMsg);
      $this->statsProcessor->updatePlayerDataField(
        "sto_glo",
        $this->playerInfo[$clientKey]["id"],
        "chat",
        $chatMsg
      );
      $this->statsProcessor->updatePlayerQuote(
        $this->playerInfo[$clientKey]["id"],
        $chatMsg
      );
    }
    return true;
  }

  // Process client connect event.
  function processClientConnect(&$line)
  {
    // si es conección de cliente
    if (!preg_match("/^ClientConnect: (\d+)/", $line, $match)) {
      return false;
    }
    $clientId = $match[1];
    if (isset($this->playerInfo[$clientId])) {
      unset($this->playerInfo[$clientId]);
    }
    return true;
  }

  // Process client disconnect event.
  function processClientDisconnect(&$line)
  {
    // si es desconexión de cliente
    if (!preg_match("/^ClientDisconnect: (\d+)/", $line, $match)) {
      return false;
    }
    //change: team control
    if (isset($this->statsProcessor->players_team)) {
      $this->statsProcessor->players_team[$match[1]]["connected"] = false;
    }
    //endchange
    return true;
  }

  // Process game shutdown.
  function processGameShutdown(&$line)
  {
    // si es finalización de juego
    if (!preg_match("/^ShutdownGame:/", $line, $match)) {
      return false;
    }
    if ($this->config["savestate"] == 1) {
      //change: savestate change
      //$this->saveShutdownState();
      save_savestate($this);
      //endchange
    }
    $this->statsProcessor->updatePlayerStreaks(); // actualiza los streaks de los jugadores
    //change: launch skills events
    $this->statsProcessor->launch_skill_events();
    //endchange
    $this->statsAggregator->storeGameData(
      $this->statsProcessor->getPlayerStats(),
      $this->statsProcessor->getGameData()
    ); // actualiza los datos de los jugadores
    $this->statsProcessor->clearProcessorData(); // limpieza de variables
    $this->gameInProgress = false; // flag de juego en proceso
    return true;
  }

  // Process warmup (ignored) event.
  function processWarmup(&$line)
  {
    // si es juego de calentamiento, ignorado
    if (!preg_match("/^Warmup:/", $line, $match)) {
      return false;
    }
    debugPrint("warmup game, ignored\n");
    $this->statsProcessor->clearProcessorData();
    $this->gameInProgress = false;
    return true;
  }

  // Process team score and game end events.
  function processTeamScoreLine(&$line)
  {
    // score del partido
    if (!preg_match("/^red:(\d+) *blue:(\d+)/", $line, $match)) {
      return false;
    }
    //change: team|score event
    $tmp = $GLOBALS["skillset"]["event"]["team|score"];
    if (
      !(
        strcmp($this->config["gametype"], "xp") == 0 &&
        in_array($this->translationData["gametype"], [
          "4",
          "5",
          "6",
          "7",
          "8",
          "9",
        ])
      )
    ) {
      $GLOBALS["skillset"]["event"]["team|score"] = 0.0;
    }
    $this->statsProcessor->updateTeamEventSkill("1", "team|score", $match[1]); // les asigna el score al equipo rojo
    $this->statsProcessor->updateTeamEventSkill("2", "team|score", $match[2]); // les asigna el score al equipo azul
    $GLOBALS["skillset"]["event"]["team|score"] = $tmp;
    //endchange
    if (intval($match[1]) > intval($match[2])) {
      // gana el rojo
      $this->statsProcessor->updateTeamEventSkill("1", "team|wins", 1);
      $this->statsProcessor->updateTeamEventSkill("2", "team|loss", 1);
    } elseif (intval($match[1]) < intval($match[2])) {
      // gana el azul
      $this->statsProcessor->updateTeamEventSkill("1", "team|loss", 1);
      $this->statsProcessor->updateTeamEventSkill("2", "team|wins", 1);
    }
    return true;
  }

  // Process player score at game end.
  function processPlayerScore(&$line)
  {
    // puntuación del player al finalizar el juego
    if (
      !preg_match(
        "/^score: (-?\d+) +ping: (\d+) +client: (\d+)/",
        $line,
        $match
      )
    ) {
      return false;
    }
    $score = $match[1]; // score
    $ping = $match[2]; // ping
    $clientId = $match[3]; // id
    if (isset($this->playerInfo[$clientId])) {
      //change: team control
      if (isset($this->statsProcessor->players_team)) {
        $this->statsProcessor->updatePlayerEvent(
          $clientId,
          "score",
          $score,
          $this->playerInfo
        );
      } else {
        $this->statsProcessor->updatePlayerEvent(
          $this->playerInfo[$clientId]["id"],
          "score",
          $score
        );
      }
      //endchange
      $this->statsProcessor->updatePlayerDataField(
        "avg",
        $this->playerInfo[$clientId]["id"],
        "ping",
        $ping
      );
    }
    return true;
  }

  // Process CTF awards.
  function processCTFAwards(&$line)
  {
    if (preg_match("/^AWARD_FlagRecovery: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Flag_Return",
        1
      );
      return true;
    } elseif (preg_match("/^AWARD_FlagSteal: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Flag_Pickup",
        1
      );
      return true;
    } elseif (preg_match("/^AWARD_CarrierKill: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Kill_Carrier",
        1
      );
      return true;
    } elseif (
      preg_match("/^AWARD_CarrierDangerProtect: (\d+)/", $line, $match)
    ) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Defend_Hurt_Carrier",
        1
      );
      return true;
    } elseif (preg_match("/^AWARD_CarrierProtection: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Defend_Carrier",
        1
      );
      return true;
    } elseif (preg_match("/^AWARD_FlagDefense: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Defend_Flag",
        1
      );
      return true;
    } elseif (
      preg_match("/^AWARD_FlagCarrierKillAssist: (\d+)/", $line, $match)
    ) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Flag_Assist_Frag",
        1
      );
      return true;
    } elseif (preg_match("/^AWARD_FlagCaptureAssist: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Flag_Assist_Return",
        1
      );
      return true;
    } elseif (preg_match("/^AWARD_FlagCapture: (\d+)/", $line, $match)) {
      $this->statsProcessor->updatePlayerEvent(
        $this->playerInfo[$match[1]]["id"],
        "CTF|Flag_Capture",
        1
      );
      return true;
    }
    return false;
  }

  // Process team scores and finalize game stats.
  function processTeamScoreAndGameEnd(&$line)
  {
    if (
      preg_match("/^processStatsGameTypesOSPClanArena_EndGame/", $line, $match)
    ) {
      if (isset($this->miscStats["score"])) {
        foreach ($this->miscStats["score"] as $clientKey => $score) {
          if (isset($this->playerInfo[$clientKey])) {
            $this->statsProcessor->updatePlayerEvent(
              $this->playerInfo[$clientKey]["id"],
              "score",
              $score
            );
          }
        }
      }
      if (
        isset($this->miscStats["team_score"]["red"]) &&
        isset($this->miscStats["team_score"]["blue"])
      ) {
        $this->statsProcessor->updateTeamEventSkill(
          "1",
          "team|score",
          $this->miscStats["team_score"]["red"]
        );
        $this->statsProcessor->updateTeamEventSkill(
          "2",
          "team|score",
          $this->miscStats["team_score"]["blue"]
        );
        if (
          intval($this->miscStats["team_score"]["red"]) >
          intval($this->miscStats["team_score"]["blue"])
        ) {
          $this->statsProcessor->updateTeamEventSkill("1", "team|wins", 1);
          $this->statsProcessor->updateTeamEventSkill("2", "team|loss", 1);
        } elseif (
          intval($this->miscStats["team_score"]["red"]) <
          intval($this->miscStats["team_score"]["blue"])
        ) {
          $this->statsProcessor->updateTeamEventSkill("1", "team|loss", 1);
          $this->statsProcessor->updateTeamEventSkill("2", "team|wins", 1);
        }
      }
      return true;
    } elseif (preg_match("/^Warmup:/", $line, $match)) {
      return true;
    } elseif (preg_match("/^red:(\d+) *blue:(\d+)/", $line, $match)) {
      $this->miscStats["team_score"]["red"] = $match[1];
      $this->miscStats["team_score"]["blue"] = $match[2];
      return true;
    } elseif (
      preg_match("/^score: (-?\d+) +ping: (\d+) +client: (\d+)/", $line, $match)
    ) {
      $clientKey = $match[3];
      $this->miscStats["score"][$clientKey] = $match[1];
      $ping = $match[2];
      if (isset($this->playerInfo[$clientKey])) {
        $this->statsProcessor->updatePlayerDataField(
          "avg",
          $this->playerInfo[$clientKey]["id"],
          "ping",
          $ping
        );
      }
      return true;
    } elseif (preg_match("/^Game_End:/", $line, $match)) {
      $dummy = "processStatsGameTypesOSPClanArena_EndGame";
      $this->processTeamScoreAndGameEnd($dummy);
      while (!feof($this->logFileHandle)) {
        $curPos = ftell($this->logFileHandle);
        $nextLine = fgets($this->logFileHandle, cBIG_STRING_LENGTH);
        $nextLine = rtrim($nextLine, "\r\n");
        $this->extractTimestamp($nextLine);
        if (preg_match("/^ShutdownGame:/", $nextLine, $m)) {
          $dummyShutdown = "ShutdownGame:";
          $this->processGameShutdown($dummyShutdown);
          return true;
        } elseif (preg_match("/^InitGame:/", $nextLine, $m)) {
          fseek($this->logFileHandle, $curPos);
          return true;
        }
      }
      return true;
    } elseif (preg_match("/^ShutdownGame:/", $line, $match)) {
      $startPos = ftell($this->logFileHandle);
      while (!feof($this->logFileHandle)) {
        $nextLine = fgets($this->logFileHandle, cBIG_STRING_LENGTH);
        $curPos = ftell($this->logFileHandle);
        $nextLine = rtrim($nextLine, "\r\n");
        $this->extractTimestamp($nextLine);
        if (preg_match("/^InitGame:/", $nextLine)) {
          if (
            preg_match("/Score_Time\\\EndOfMatch/", $nextLine) ||
            preg_match("/Score_Time\\\Round 1\\//", $nextLine) ||
            preg_match("/g_gametype\\\[^5]/", $nextLine)
          ) {
            $dummy = "processStatsGameTypesOSPClanArena_EndGame";
            $this->processLogLine($dummy);
            fseek($this->logFileHandle, $startPos);
            return false;
          } else {
            fseek($this->logFileHandle, $curPos);
            return true;
          }
        }
      }
      return true;
    }
    return false;
  }

  // Process threewave events (CTF or weapon_stats) for specific mods.
  function processThreewaveEvent(&$line)
  {
    // si son eventos de ctf o weapon_stats
    if (
      (stristr($this->translationData["mod"], "osp") ||
        stristr($this->translationData["gameversion"], "osp")) &&
      $this->translationData["gametype"] == "5"
    ) {
      if ($this->processTeamScoreAndGameEnd($line)) {
        return true;
      }
    }
    //change: less code and team control
    $events = [
      "Flag_Return",
      "Flag_Pickup",
      "Kill_Carrier",
      "Defend_Hurt_Carrier",
      "Hurt_Carrier_Defend",
      "Defend_Carrier",
      "Defend_Base",
      "Defend_Flag",
      "Flag_Assist_Frag",
      "Flag_Assist_Return",
      "Flag_Capture",
    ];
    foreach ($events as $event) {
      if (preg_match("/^{$event}: (\d+)/", $line, $match)) {
        if (isset($this->statsProcessor->players_team)) {
          $this->statsProcessor->updatePlayerEvent(
            $match[1],
            "CTF|{$event}",
            1,
            $this->playerInfo
          );
        } else {
          $this->statsProcessor->updatePlayerEvent(
            $this->playerInfo[$match[1]]["id"],
            "CTF|{$event}",
            1
          );
        }
        return true;
      }
    }
    //endchange
    if (preg_match("/^Weapon_Stats: (\d+) (.*)/", $line, $match)) {
      // accuracy
      $clientId = $match[1];
      $stats = $match[2];
      //change: acc control
      if (
        isset($this->statsProcessor->players_team) &&
        @$this->has_acc_stats[$clientId]
      ) {
        return true;
      }
      //endchange
      while (preg_match("/^(.+):(\d+):(\d+)(:\d+:\d+)* /U", $stats, $sMatch)) {
        $weaponName = $sMatch[1];
        $hitVal = "";
        if (isset($sMatch[4])) {
          $shots = $sMatch[2];
          $hits = $sMatch[3];
          $dummy = $sMatch[4];
        } else {
          if ($sMatch[2] > $sMatch[3]) {
            $shots = $sMatch[2];
            $hits = $sMatch[3];
          } else {
            $shots = $sMatch[3];
            $hits = $sMatch[2];
          }
        }
        $stats = substr($stats, strlen($sMatch[0]));
        if (!strcmp($weaponName, "Gauntlet") || !strcmp($weaponName, "G")) {
          $weaponName = "GAUNTLET";
        } elseif (
          !strcmp($weaponName, "MachineGun") ||
          !strcmp($weaponName, "Machinegun") ||
          !strcmp($weaponName, "MG")
        ) {
          $weaponName = "MACHINEGUN";
        } elseif (
          !strcmp($weaponName, "Shotgun") ||
          !strcmp($weaponName, "SG")
        ) {
          $weaponName = "SHOTGUN";
        } elseif (
          !strcmp($weaponName, "G.Launcher") ||
          !strcmp($weaponName, "GL")
        ) {
          $weaponName = "GRENADE";
        } elseif (
          !strcmp($weaponName, "R.Launcher") ||
          !strcmp($weaponName, "RL")
        ) {
          $weaponName = "ROCKET";
        } elseif (
          !strcmp($weaponName, "LightningGun") ||
          !strcmp($weaponName, "Lightning") ||
          !strcmp($weaponName, "LG")
        ) {
          $weaponName = "LIGHTNING";
        } elseif (
          !strcmp($weaponName, "Railgun") ||
          !strcmp($weaponName, "RG")
        ) {
          $weaponName = "RAILGUN";
        } elseif (
          !strcmp($weaponName, "Plasmagun") ||
          !strcmp($weaponName, "PG")
        ) {
          $weaponName = "PLASMA";
        }
        //change: acc grapple support
        elseif (!strcmp($weaponName, "Hook")) {
          $weaponName = "GRAPPLE";
        }
        //endchange
        else {
          $weaponName = preg_replace("/^MOD_/", "", $weaponName);
        }
        if ($shots > 0) {
          //change: team control
          if (isset($this->statsProcessor->players_team)) {
            $this->statsProcessor->updateAccuracyEvent(
              $clientId,
              $clientId,
              "accuracy|{$weaponName}_hits",
              $hits,
              $this->playerInfo
            );
            $this->statsProcessor->updateAccuracyEvent(
              $clientId,
              $clientId,
              "accuracy|{$weaponName}_shots",
              $shots,
              $this->playerInfo
            );
          } else {
            $this->statsProcessor->updateAccuracyEvent(
              $this->playerInfo[$clientId]["id"],
              $this->playerInfo[$clientId]["id"],
              "accuracy|{$weaponName}_hits",
              $hits
            );
            $this->statsProcessor->updateAccuracyEvent(
              $this->playerInfo[$clientId]["id"],
              $this->playerInfo[$clientId]["id"],
              "accuracy|{$weaponName}_shots",
              $shots
            );
          }
          //endchange
        }
      }
      while (
        preg_match("/^(.+):(\d+) /U", $stats, $sMatch) ||
        preg_match("/^(.+):(\d+)/", $stats, $sMatch)
      ) {
        $statName = $sMatch[1];
        $statVal = $sMatch[2];
        //change: team control
        if (
          (!strcmp($statName, "Given") || !strcmp($statName, "DG")) &&
          $statVal > 0
        ) {
          if (isset($this->statsProcessor->players_team)) {
            $this->statsProcessor->updatePlayerEvent(
              $clientId,
              "damage given",
              $statVal,
              $this->playerInfo
            );
          } else {
            $this->statsProcessor->updatePlayerEvent(
              $this->playerInfo[$clientId]["id"],
              "damage given",
              $statVal
            );
          }
        }
        if (
          (!strcmp($statName, "Recvd") || !strcmp($statName, "DR")) &&
          $statVal > 0
        ) {
          if (isset($this->statsProcessor->players_team)) {
            $this->statsProcessor->updatePlayerEvent(
              $clientId,
              "damage taken",
              $statVal,
              $this->playerInfo
            );
          } else {
            $this->statsProcessor->updatePlayerEvent(
              $this->playerInfo[$clientId]["id"],
              "damage taken",
              $statVal
            );
          }
        }
        if (
          (!strcmp($statName, "TeamDmg") || !strcmp($statName, "TD")) &&
          $statVal > 0
        ) {
          if (isset($this->statsProcessor->players_team)) {
            $this->statsProcessor->updatePlayerEvent(
              $clientId,
              "damage to team",
              $statVal,
              $this->playerInfo
            );
          } else {
            $this->statsProcessor->updatePlayerEvent(
              $this->playerInfo[$clientId]["id"],
              "damage to team",
              $statVal
            );
          }
        }
        //endchange
        $stats = substr($stats, strlen($sMatch[0]));
      }
      //change: acc control
      if (isset($this->statsProcessor->players_team)) {
        $this->has_acc_stats[$clientId] = true;
      }
      //endchange
      return true;
    }
    return false;
  }

  // Process freeze events.
  function processFreezeEvent(&$line)
  {
    if (preg_match("/^Round starts/", $line, $match)) {
      return true;
    } elseif (preg_match("/^Exit: Map voting complete/", $line, $match)) {
      debugPrint("3wave portal game, ignored\n");
      $this->statsProcessor->clearProcessorData();
      $this->gameInProgress = false;
      return true;
    } elseif (
      preg_match(
        "/^Client Connect Using IP Address: (\d+\.\d+\.\d+\.\d+)(:\d+)*(\s+\((.+)\))?/",
        $line,
        $match
      )
    ) {
      $this->miscStats["last_scanned_ip"] = $match[1];
      if (isset($match[4])) {
        $this->miscStats["last_scanned_guid"] = $match[4];
      }
      return true;
    } elseif (preg_match("/^ClientConnect: (\d+)/", $line, $match)) {
      $clientId = $match[1];
      if (isset($this->playerInfo[$clientId])) {
        unset($this->playerInfo[$clientId]);
      }
      if (isset($this->miscStats["last_scanned_ip"])) {
        $this->playerInfo[$clientId]["ip"] =
          $this->miscStats["last_scanned_ip"];
      }
      if (isset($this->miscStats["last_scanned_guid"])) {
        $this->playerInfo[$clientId]["guid"] =
          $this->miscStats["last_scanned_guid"];
      }
      return true;
    } elseif (preg_match("/^\^.Stats for (.*)/", $line, $match)) {
      $details = $match[1];
      $clientKey = $this->lookupPlayerByName(
        $this->convertColorCodes($details)
      );
      if (strlen($clientKey) < 1) {
        return true;
      }
      $this->miscStats["client_id_of_last_scanned_stats"] = $clientKey;
      return true;
    } elseif (
      isset($this->miscStats["client_id_of_last_scanned_stats"]) &&
      preg_match(
        "/^\^\d\[WP\](\w+)\s+\^\d\s+\d+\.\d+ \((\d+)\/(\d+)\)/",
        $line,
        $match
      )
    ) {
      $weapon = $match[1];
      $hits = $match[2];
      $shots = $match[3];
      if (!strcmp($weapon, "MG")) {
        $weapon = "MACHINEGUN";
      } elseif (!strcmp($weapon, "SG")) {
        $weapon = "SHOTGUN";
      } elseif (!strcmp($weapon, "GL")) {
        $weapon = "GRENADE";
      } elseif (!strcmp($weapon, "RL")) {
        $weapon = "ROCKET";
      } elseif (!strcmp($weapon, "LG")) {
        $weapon = "LIGHTNING";
      } elseif (!strcmp($weapon, "RG")) {
        $weapon = "RAILGUN";
      } elseif (!strcmp($weapon, "PG")) {
        $weapon = "PLASMA";
      } elseif (!strcmp($weapon, "NG")) {
        $weapon = "NAILGUN";
      }
      $clientKey = $this->miscStats["client_id_of_last_scanned_stats"];
      if ($shots > 0) {
        $this->statsProcessor->updateAccuracyEvent(
          $this->playerInfo[$clientKey]["id"],
          $this->playerInfo[$clientKey]["id"],
          "accuracy|{$weapon}_hits",
          $hits
        );
        $this->statsProcessor->updateAccuracyEvent(
          $this->playerInfo[$clientKey]["id"],
          $this->playerInfo[$clientKey]["id"],
          "accuracy|{$weapon}_shots",
          $shots
        );
      }
      return true;
    } elseif (
      isset($this->miscStats["client_id_of_last_scanned_stats"]) &&
      preg_match("/^\^\d(D[GT])\s+\^\d\s*(\d+)/", $line, $match)
    ) {
      $code = $match[1];
      $value = $match[2];
      $clientKey = $this->miscStats["client_id_of_last_scanned_stats"];
      if (!strcmp($code, "DG") && $value > 0) {
        $this->statsProcessor->updatePlayerEvent(
          $this->playerInfo[$clientKey]["id"],
          "damage given",
          $value
        );
      } elseif (!strcmp($code, "DT") && $value > 0) {
        $this->statsProcessor->updatePlayerEvent(
          $this->playerInfo[$clientKey]["id"],
          "damage taken",
          $value
        );
        unset($this->miscStats["client_id_of_last_scanned_stats"]);
      }
      return true;
    }
    return false;
  }

  // Process client details.
  function processClientDetails(&$line)
  {
    if (preg_match("/^ClientDetails: (.*)/", $line, $match)) {
      $details = $match[1];
      while (
        preg_match("/^(.+)\\\(.*)\\\/U", $details, $varMatch) ||
        preg_match("/^(.+)\\\(.*)/", $details, $varMatch)
      ) {
        $varName = $varMatch[1];
        $varValue = $varMatch[2];
        $details = substr($details, strlen($varName) + strlen($varValue) + 2);
        if (!strcmp($varName, "ip")) {
          $this->miscStats["last_scanned_ip"] = $varValue;
        } elseif (!strcmp($varName, "guid")) {
          $this->miscStats["last_scanned_guid"] = $varValue;
        }
      }
      return true;
    } elseif (preg_match("/^ClientConnect: (\d+)/", $line, $match)) {
      $clientId = $match[1];
      if (isset($this->playerInfo[$clientId])) {
        unset($this->playerInfo[$clientId]);
      }
      if (isset($this->miscStats["last_scanned_ip"])) {
        $this->playerInfo[$clientId]["ip"] =
          $this->miscStats["last_scanned_ip"];
      }
      if (isset($this->miscStats["last_scanned_guid"])) {
        $this->playerInfo[$clientId]["guid"] =
          $this->miscStats["last_scanned_guid"];
      }
      return true;
    }
    return false;
  }

  // Process client connect or chat (used to parse variables from connection info).
  function processClientConnectOrChat(&$line)
  {
    // si es conección de cliente o es chat del cliente (no sé qué relación pueden tener)
    if (preg_match("/^ClientConnect: (\d+) (.*)/", $line, $match)) {
      // conexión de cliente
      $clientId = $match[1]; // id
      $vars = $match[2]; // variables
      if (isset($this->playerInfo[$clientId])) {
        // si ya existe el cliente
        unset($this->playerInfo[$clientId]);
      }
      while (
        preg_match("/^\\\(.+)\\\(.*)\\\/U", $vars, $varMatch) ||
        preg_match("/^\\\(.+)\\\(.*)/", $vars, $varMatch)
      ) {
        // parsea las variables
        $varName = $varMatch[1];
        $varValue = $varMatch[2];
        $vars = substr($vars, strlen($varName) + strlen($varValue) + 2);
        if (!strcmp($varName, "ip")) {
          $this->playerInfo[$clientId]["ip"] = preg_replace(
            '/\:\d+$/',
            "",
            $varValue
          ); // ip
        } elseif (!strcmp($varName, "guid")) {
          $this->playerInfo[$clientId]["guid"] = $varValue; // guid
        } elseif (!strcmp($varName, "tld")) {
          $this->playerInfo[$clientId]["tld"] = $varValue; // tld
        } elseif (!strcmp($varName, "rtld")) {
          $this->playerInfo[$clientId]["rtld"] = $varValue; // utld
        }
      }
      return true;
    } elseif (preg_match("/^say: (.+): /U", $line, $match)) {
      // si es chat
      $namePart = $match[1];
      $chatMsg = substr($line, strlen($match[0]));
      $chatMsg = preg_replace("/^&.*\\.wav /i", "", $chatMsg);
      if (preg_match("/ (\d+)$/U", $chatMsg, $numMatch)) {
        $clientId = $numMatch[1];
        $chatMsg = substr($chatMsg, 0, -(strlen($numMatch[1]) + 1));
      } else {
        $clientId = $this->lookupPlayerByName(
          $this->convertColorCodes($namePart)
        );
      }
      if (strlen($clientId) > 0) {
        //change: special chars
        if ($this->config["xp_version"] <= 103) {
          // 1.03 special chars
          $chatMsg = preg_replace(
            "/\+([\x01-\x7F])#/e",
            "chr(ord('\\1') + 127)",
            $chatMsg
          );
        } else {
          // 1.04 special chars
          $chatMsg = preg_replace(
            "/#(#|[0-9a-f]{2})/ie",
            "'\\1' == '#' ? '#' : chr(hexdec('\\1'))",
            $chatMsg
          );
        }
        $chatMsg = strtr($chatMsg, $this->translationData["char_trans"]);
        //endchange
        $this->statsProcessor->updatePlayerQuote(
          $this->playerInfo[$clientId]["id"],
          $this->removeColorCodes($chatMsg)
        );
      }
      return true;
    }
    return false;
  }

  // Process RA3 events.
  function processRA3Event(&$line)
  {
    // si descongela (oO, no sabía esto)
    if (
      preg_match(
        "/^Kill: (\d+) (\d+) \d+: (.*) killed (.*) by MOD_UNKNOWN/",
        $line,
        $match
      ) &&
      //change: thaw only for freeze
      (strcmp($this->config["gametype"], "xp") != 0 ||
        $this->translationData["gametype"] == "8")
      //endchange
    ) {
      $attacker = $match[1];
      $victim = $match[2];
      if ($attacker > 128) {
        $attacker = $victim;
      }
      //change: team control
      if (
        isset($this->playerInfo[$attacker]["id"]) &&
        isset($this->playerInfo[$victim]["id"])
      ) {
        if (isset($this->statsProcessor->players_team)) {
          $this->statsProcessor->updatePlayerEvent(
            $attacker,
            "THAW",
            1,
            $this->playerInfo
          ); // lanza el evento THAW
        } else {
          $this->statsProcessor->updatePlayerEvent(
            $this->playerInfo[$attacker]["id"],
            "THAW",
            1
          ); // lanza el evento THAW
        }
      }
      //endchange
      return true;
    }
    return false;
  }

  // Process UT events (preliminary UT processing).
  function processUTEventPre(&$line)
  {
    if (preg_match("/^Warmup:/", $line, $match)) {
      return true;
    } elseif (preg_match("/^Item: \d+ ut_.*/", $line, $match)) {
      $line = preg_replace("/(Item: \d+ )ut_/", "\${1}", $line, 1);
      return false;
    } elseif (
      preg_match("/^Kill: \d+ \d+ \d+: .* killed .* by UT_MOD_/", $line, $match)
    ) {
      $line = preg_replace(
        "/(Kill: \d+ \d+ \d+: .* killed .* by )UT_MOD_/",
        "\${1}MOD_",
        $line,
        1
      );
      return false;
    } elseif (preg_match("/^ClientUserinfo: (\d+) (.*)/", $line, $match)) {
      $clientId = $match[1];
      $vars = $match[2];
      while (
        preg_match("/^\\\(.+)\\\(.*)\\\/U", $vars, $varMatch) ||
        preg_match("/^\\\(.+)\\\(.*)/", $vars, $varMatch)
      ) {
        $varName = $varMatch[1];
        $varValue = $varMatch[2];
        $vars = substr($vars, strlen($varName) + strlen($varValue) + 2);
        if (!strcmp($varName, "ip")) {
          $this->playerInfo[$clientId]["ip"] = $varValue;
        } elseif (!strcmp($varName, "cl_guid")) {
          $this->playerInfo[$clientId]["guid"] = $varValue;
        } elseif (!strcmp($varName, "model")) {
          if (
            !isset($this->playerInfo[$clientId]["icon"]) ||
            strcmp($this->playerInfo[$clientId]["icon"], $varValue)
          ) {
            $this->playerInfo[$clientId]["icon"] = $varValue;
          }
        }
      }
      return true;
    }
    return false;
  }

  // Process UT events.
  function processUTEvent(&$line)
  {
    if (preg_match("/^ClientUserinfoChanged: (\d+) (.*)/", $line, $match)) {
      $clientId = $match[1];
      $originalLine = $line;
      $savedPos = $this->currentFilePosition;
      $nextLine = fgets($this->logFileHandle, cBIG_STRING_LENGTH);
      $nextLine = rtrim($nextLine, "\r\n");
      $this->extractTimestamp($nextLine);
      if (
        preg_match(
          "/^ClientConnect: (\d+).*(\\((\d+\.\d+\.\d+\.\d+).*\\)$)/",
          $nextLine,
          $m
        )
      ) {
        if ($clientId == $m[1]) {
          $tempId = $m[1];
          if (isset($this->playerInfo[$tempId])) {
            unset($this->playerInfo[$tempId]);
          }
          if (isset($m[3])) {
            $this->playerInfo[$tempId]["ip"] = $m[3];
          }
          $this->processClientUserinfoChanged($originalLine);
          return true;
        } else {
          $this->currentFilePosition = $savedPos;
          fseek($this->logFileHandle, $savedPos);
          return false;
        }
      } else {
        $this->currentFilePosition = $savedPos;
        fseek($this->logFileHandle, $savedPos);
        return false;
      }
      return false;
    } elseif (
      preg_match(
        "/^ClientConnect: (\d+).*(\\((\d+\.\d+\.\d+\.\d+).*\\)$)/",
        $line,
        $match
      )
    ) {
      $clientId = $match[1];
      if (isset($this->playerInfo[$clientId])) {
        unset($this->playerInfo[$clientId]);
      }
      if (isset($match[3])) {
        $this->playerInfo[$clientId]["ip"] = $match[3];
      }
      return true;
    } elseif (
      preg_match("/^Kill: \d+ \d+ \d+ \d+: .* killed .* by \w+/", $line, $match)
    ) {
      $line = preg_replace("/^(Kill: \d+ \d+ \d+) \d+/", "\${1}", $line, 1);
      return false;
    } elseif (preg_match("/^say: (\d+) \d+: (.+):/U", $line, $match)) {
      $chatMsg = substr($line, strlen($match[0]));
      $clientId = $match[1];
      if (isset($this->playerInfo[$clientId]["id"])) {
        $this->statsProcessor->updatePlayerQuote(
          $this->playerInfo[$clientId]["id"],
          $this->removeColorCodes($chatMsg)
        );
      }
      return true;
    }
    return false;
  }

  // Dispatch game type–specific event processing.
  function dispatchGameTypeEvent(&$line)
  {
    // si son eventos especiales de cada mod
    if (
      !strcmp($this->config["gametype"], "osp") ||
      !strcmp($this->config["gametype"], "cpma")
    ) {
      if ($this->processThreewaveEvent($line)) {
        return true;
      } elseif ($this->processRA3Event($line)) {
        return true;
      }
    } elseif (!strcmp($this->config["gametype"], "threewave")) {
      if ($this->processThreewaveEvent($line)) {
        return true;
      } elseif ($this->processFreezeEvent($line)) {
        return true;
      } else {
        return false;
      }
    } elseif (!strcmp($this->config["gametype"], "battle")) {
      if ($this->processThreewaveEvent($line)) {
        return true;
      } elseif ($this->processClientDetails($line)) {
        return true;
      } else {
        return false;
      }
    } elseif (!strcmp($this->config["gametype"], "freeze")) {
      if ($this->processThreewaveEvent($line)) {
        return true;
      } elseif ($this->processRA3Event($line)) {
        return true;
      } else {
        return false;
      }
    } elseif (!strcmp($this->config["gametype"], "ut")) {
      return $this->processUTEventPre($line);
    } elseif (!strcmp($this->config["gametype"], "ra3")) {
      return $this->processUTEvent($line);
    } elseif (!strcmp($this->config["gametype"], "lrctf")) {
      return $this->processCTFAwards($line);
    } elseif (!strcmp($this->config["gametype"], "xp")) {
      // excessiveplus
      if ($this->processThreewaveEvent($line)) {
        // si son eventos de ctf o weapon_stats
        return true;
      } elseif ($this->processRA3Event($line)) {
        // si descongela (oO, no sabía esto)
        return true;
      } elseif ($this->processClientConnectOrChat($line)) {
        // si es conección de cliente o es chat del cliente (no sé qué relación pueden tener)
        return true;
      }
      //change: clientguid
      elseif ($this->isClientGuid($line)) {
        return true;
      }
      //endchange
      else {
        return false;
      }
    }
    return false;
  }

  // Change the client's GUID.
  function isClientGuid(&$line)
  {
    if (!preg_match("/^ClientGuid: (\d+) (.*)/", $line, $matches)) {
      return false;
    }
    $clientId = $matches[1];
    $guid = trim($matches[2]);
    if (isset($this->playerInfo[$clientId])) {
      // si ya existe el cliente
      $this->playerInfo[$clientId]["guid"] = $guid;
    }
    return true;
  }

  // Extract timestamp from the beginning of a line.
  function extractTimestamp(&$line)
  {
    // obtiene la fecha de la línea
    if (preg_match("/^\[(\d+[\\:\\.]\d+[\\:\\.]\d+)\] */", $line, $match)) {
      $this->rawTimestamp = $match[1];
      $line = substr($line, strlen($match[0]));
      return true;
    } elseif (preg_match("/^(\d+[\\:\\.]\d+[\\:\\.]\d+) */", $line, $match)) {
      $this->rawTimestamp = $match[1];
      $line = substr($line, strlen($match[0]));
      return true;
    } elseif (preg_match("/^ *(\d+[\\:\\.]\d+) */", $line, $match)) {
      $this->rawTimestamp = $match[1];
      $line = substr($line, strlen($match[0]));
      return true;
    }
    return false;
  }

  // Stub for unknown processing.
  function Fa8539cfc(&$line)
  {
    return false;
  }

  // Main log line processor – dispatches to various event handlers.
  function processLogLine(&$line)
  {
    // parsea la línea
    $this->extractTimestamp($line); // obtiene la fecha
    if ($this->processGameInit($line)) {
      // es inicio de juego
      echo sprintf(
        "(%05.2f%%) ",
        (100.0 * ftell($this->logFileHandle)) /
          $this->translationData["logfile_size"]
      ); // muestra porcentaje de archivo procesado
    } elseif ($this->gameInProgress) {
      // si hay juego en progreso
      //change: excessiveplus 1.03 fix
      /*if (!strcmp($this->config['gametype'], 'xp')) {
                    $patterns = array(
                        '/^Kill: (\d+) (\d+)/',
                        '/^Item: (\d+)/',
                        '/^say: ?:.* (\d+)\n/',
                        '/^ClientDisconnect: (\d+)/',
                        '/^Weapon_Stats: (\d+)/',
                        '/^Kick: (\d+)/'
                    );
                    foreach ($patterns as $pattern) {
                        if (preg_match($pattern, $line, $matches)) {
                            array_shift($matches);
                            foreach ($matches as $playerId) {
                                if (isset($this->playerInfo[$playerId]['id'])) {
                                    $id = $this->playerInfo[$playerId]['id'];
                                    if (!isset($this->statsProcessor->playerStats[$id])) {
                                        $dummy = "ClientBegin: ".$playerId;
                                        $this->processClientBegin($dummy);
                                    }
                                }
                            }
                            break;
                        }
                    }
                }*/
      //endchange
      if ($this->dispatchGameTypeEvent($line)) {
      } elseif ($this->processClientUserinfoChanged($line)) {
      } elseif ($this->processItemPickup($line)) {
      } elseif ($this->processKillEvent($line)) {
      } elseif ($this->processClientConnect($line)) {
      } elseif ($this->processClientDisconnect($line)) {
      } elseif ($this->processClientBegin($line)) {
      } elseif ($this->processClientChat($line)) {
      } elseif ($this->processGameShutdown($line)) {
      } elseif ($this->processPlayerScore($line)) {
      } elseif ($this->processTeamScoreLine($line)) {
      } elseif ($this->processServerTime($line)) {
      } elseif ($this->processWarmup($line)) {
      } elseif ($this->Fa8539cfc($line)) {
      } else {
      }
    } else {
    }
  }
}
?>
