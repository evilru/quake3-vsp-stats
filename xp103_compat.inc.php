<?php
/**
 * Contains the functions needed to repair an excessiveplus 1.03 log file
 *
 * @author wasp*beast <rabusmar@hotmail.com>
 * @version 1.0
 */

/**
 * Repairs the excessiveplus log file
 *
 * Creates a temporary file that will be removed on shutdown. That file can
 * be parsed through vps without problems, as it was with excessiveplus
 * mod version 1.02b
 *
 * @param string $logfile Path of the log file
 * @return string The name of the temporary repaired log file
 */
function repair_xp_logfile($logfile) {
    $handler = @fopen($logfile, 'rb');
    if (!$handler) {
        die("Cannot open log file\n");
    }
    $tmp_name = tempnam(sys_get_temp_dir(), "xp_log_");
    $new_handler = @fopen($tmp_name, "wb");
    if (!$new_handler) {
        @fclose($handler);
        echo "Cannot write to temporary folder\n";
        _flush();
        return false;
    }
    echo "Writing temp log file {$tmp_name}...";
    _flush();
    $i = 0;
    while (!feof($handler) && ($line = fgets($handler)) !== false) {
        fwrite($new_handler, $line);
        if (_is_game_start($line)) {
            fwrite($new_handler, _get_repaired_game($handler));
        }
    }
    @fclose($handler);
    @fclose($new_handler);
    echo "done\n";
    //register_shutdown_function('_remove_xp_tmp_logfile', $tmp_name, true);
    return $tmp_name;
}

/**
 * Function registered on shutdown to remove the temporary repaired log file
 *
 * @param string $tmp_name Name of the temporary repaired log file
 * @param bool $alert If must echo to stdout
 * @access private
 */
function remove_xp_tmp_logfile($tmp_name) {
    @unlink($tmp_name);
    echo "Removed temp log file {$tmp_name}\n";
}

/**
 * Repairs one game at a time and returns it as a string
 *
 * @param resource $handler Log file handler
 * @return string The repaired game string
 * @access private
 */
function _get_repaired_game(&$handler) {
    $gamelines = array();
    $index = 0;
    $players = array();
    while (!feof($handler) && ($line = fgets($handler)) !== false) {
        $gamelines[$index++] = $line;
        if (_is_game_end($line)) {
            break;
        }
        $info = _get_line_info($line);
        if (!$info) {
            continue;
        }
        switch ($info['type']) {
        case 'connect':
            $players[$info['id']] = false;
            //echo "connect {$info['id']}\n";
            break;
        case 'change':
            $id = $info['id'];
            if (array_key_exists($id, $players) && !$players[$id]) {
                $players[$id] = array('time' => $info['time'], 'offset' => $index);
                //echo "change $id {$players[$id]['time']} {$players[$id]['offset']}\n";
            }
            break;
        case 'begin':
            $id = $info['id'];
            if (array_key_exists($id, $players)) {
                //echo "begin $id\n";
                unset($players[$id]);
            }
            break;
        case 'use':
            foreach ($info['players'] as $id) {
                if (array_key_exists($id, $players) && is_array($players[$id])) {
                    //echo "use $id\n";
                    _insert_client_begin_line($gamelines, $players, $id);
                    $index++;
                }
            }
            break;
        }
    }
    return implode('', $gamelines);
}

/**
 * Inserts the ClientBegin line missing for the player
 *
 * @param array $gamelines Lines of the current game
 * @param array $players Pending players of the current game
 * @param int $id Client id
 * @accesss private
 */
function _insert_client_begin_line(&$gamelines, &$players, $id) {
    $offset = $players[$id]['offset'];
    $line = $players[$id]['time']." ClientBegin: ".$id."\n";
    array_splice($gamelines, $offset, 0, $line);
    unset($players[$id]);
    foreach ($players as $key => $value) {
        if ($value['offset'] > $offset) {
            $players[$key]['offset']++;
        }
    }
}

/**
 * Gets the information of one line
 *
 * @param string $line The game line to be processed
 * @return array Line information as an associative array
 * @access private
 */
function _get_line_info($line) {
    $info = false;
    if (preg_match('/^[\s\d\:\.]*ClientConnect: (\d+)/', $line, $matches)) {
        $info = array('type' => 'connect', 'id' => $matches[1]);
    } else if (preg_match('/^([\s\d\:\.]*)ClientUserinfoChanged: (\d+)/', $line, $matches)) {
        $info = array('type' => 'change', 'time' => $matches[1], 'id' => $matches[2]);
    } else if (preg_match('/^[\s\d\:\.]*ClientBegin: (\d+)/', $line, $matches)) {
        $info = array('type' => 'begin', 'id' => $matches[1]);
    } else {
        $patterns = array(
            '/^[\s\d\:\.]*Kill: (\d+) (\d+)/',
            '/^[\s\d\:\.]*Item: (\d+)/',
            '/^[\s\d\:\.]*say(team)?:.* (\d+)\n/',
            '/^[\s\d\:\.]*ClientDisconnect: (\d+)/',
            '/^[\s\d\:\.]*Weapon_Stats: (\d+)/',
            '/^[\s\d\:\.]*Kick: (\d+)/'
        );
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                array_shift($matches);
                $info = array('type' => 'use', 'players' => $matches);
                break;
            }
        }
    }
    return $info;
}

/**
 * Returns true if the line marks the ending of the game
 *
 * @param string $line The game line
 * @return bool True if the line marks the ending of the game
 * @access private
 */
function _is_game_end($line) {
    return preg_match('/^[\s\d\:\.]*ShutdownGame/', $line);
}

/**
 * Returns true if the line marks the beggining of a new game
 *
 * @param string $line The game line
 * @return bool True if the line marks the beggining of a new game
 * @access private
 */
function _is_game_start($line) {
    return preg_match('/^[\s\d\:\.]*InitGame/', $line);
}

function _flush() { while (ob_get_level() > 0) ob_end_flush(); flush(); }

if (!function_exists('sys_get_temp_dir')) {
  /**
   * Replacement for sys_get_temp_dir for PHP <= 5.2.1
   */
  function sys_get_temp_dir() {
    if (!empty($_ENV['TMP'])) { return realpath($_ENV['TMP']); }
    if (!empty($_ENV['TMPDIR'])) { return realpath( $_ENV['TMPDIR']); }
    if (!empty($_ENV['TEMP'])) { return realpath( $_ENV['TEMP']); }
    $tempfile=tempnam(uniqid(rand(),TRUE),'');
    if (file_exists($tempfile)) {
    unlink($tempfile);
    return realpath(dirname($tempfile));
    }
  }
}
?>
