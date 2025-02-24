<?php

function secureString($value) {
    global $db;
    if (!$db) {
        return addslashes($value);
    }
    return $db->qstr($value);
}

// First statement in array MUST be a create database statement
//change: added if not exists
$sql_create = array(

  "CREATE DATABASE IF NOT EXISTS {$GLOBALS['cfg']['db']['dbname']} DEFAULT CHARSET=utf8"
//change: decimal skills
  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}playerprofile (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,playerName varchar(255) NOT NULL default ''
    ,countryCode char(2) CHARSET 'latin1' NOT NULL default ''
    ,skill float(12,4) default '0.0'
    ,kills INT DEFAULT 0
    ,deaths INT DEFAULT 0
    ,kill_streak INT DEFAULT 0
    ,death_streak INT DEFAULT 0
    ,games int(10) unsigned default '0'
    ,first_seen DATETIME NOT NULL default '1000-01-01 00:00:00'
    ,last_seen DATETIME NOT NULL default '1000-01-01 00:00:00'
    ,PRIMARY KEY  (playerID)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
//endchange
  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}playerdata (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,gameID bigint(20) unsigned NOT NULL default '0'
    ,dataName varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,dataNo INT unsigned NOT NULL default '0'
    ,dataValue varchar(255) NOT NULL default ''
    ,KEY (playerID,gameID)
    ,KEY (dataName,dataNo)
    ,PRIMARY KEY (playerID,gameID,dataName,dataNo)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,gameID bigint(20) unsigned NOT NULL default '0'
    ,round int(10) unsigned NOT NULL default '0'
    ,team varchar(30) CHARSET 'latin1' NOT NULL default ''
    ,role varchar(30) CHARSET 'latin1' NOT NULL default ''
    ,eventName varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventValue varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventCategory varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,KEY (playerID)
    ,KEY (gameID)
    ,KEY (eventName)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,gameID bigint(20) unsigned NOT NULL default '0'
    ,round int(10) unsigned NOT NULL default '0'
    ,team varchar(30) CHARSET 'latin1' NOT NULL default ''
    ,role varchar(30) CHARSET 'latin1' NOT NULL default ''
    ,eventName varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventValue varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventCategory varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,player2ID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,team2 varchar(30) CHARSET 'latin1' NOT NULL default ''
    ,role2 varchar(30) CHARSET 'latin1' NOT NULL default ''
    ,KEY (playerID)
    ,KEY (gameID)
    ,KEY (eventName)
    ,KEY (player2ID)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}gameprofile (
    gameID bigint(20) unsigned NOT NULL default '0'
    ,timeStart datetime NOT NULL default '1000-01-01 00:00:00'
    ,PRIMARY KEY (gameID)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}gamedata (
    gameID bigint(20) unsigned NOT NULL default '0'
    ,name varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,value varchar(255) default ''
    ,PRIMARY KEY (gameID,name)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}awards (
    awardID varchar(100) CHARSET 'latin1' NOT NULL
    ,name varchar(100) CHARSET 'latin1' NOT NULL
    ,category varchar(50) CHARSET 'latin1'
    ,image varchar (255) default ''
    ,playerID varchar(100) CHARSET 'latin1' default ''
    ,`sql` TEXT NOT NULL
    ,PRIMARY KEY (awardID)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}savestate (
    logfile varchar(250) NOT NULL
    ,value TEXT NOT NULL
    ,PRIMARY KEY (logfile)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
//change: ip2country table
  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}ip2country (
    ip_from int(10) unsigned NOT NULL
    ,ip_to int(10) unsigned NOT NULL
    ,country_code2 char(2) CHARSET 'latin1' NOT NULL
    ,country_name varchar(250) NOT NULL
    ,KEY (country_code2)
    ,PRIMARY KEY (ip_from, ip_to)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
//endchange
//change: total tables
  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,dataName varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,dataValue varchar(255) NOT NULL default ''
    ,dataCount INT unsigned NOT NULL default '0'
    ,PRIMARY KEY (playerID, dataName, dataValue)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,eventCategory varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventName varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventValue varchar(50) NOT NULL default ''
    ,PRIMARY KEY (playerID, eventCategory, eventName)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"

  ,"
  CREATE TABLE IF NOT EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d_total (
    playerID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,player2ID varchar(100) CHARSET 'latin1' NOT NULL default ''
    ,eventCategory varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventName varchar(50) CHARSET 'latin1' NOT NULL default ''
    ,eventValue varchar(50) NOT NULL default ''
    ,PRIMARY KEY (playerID, player2ID, eventCategory, eventName)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
//endchange
);

//change: added if exists
$sql_destroy = array(
  "DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}playerprofile"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}playerdata"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}gameprofile"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}gamedata"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}awards"
  //change: savestate table
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}savestate"
  //endchange
  //change: countries table
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}ip2country"
  //endchange
  //change: total tables
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total"
  ,"DROP TABLE IF EXISTS {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d_total"
  //endchange
);

?>
