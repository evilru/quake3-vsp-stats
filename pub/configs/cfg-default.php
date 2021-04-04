<?php
//==============================================================================
// Anything following double slashes // are comments.
// You can use // to disable certain settings. 
// Remove the // infront of a variable if you want to enable it.
//==============================================================================
global $cfg;

//================================================
// PHP configurations
    
    error_reporting(E_ALL ^ E_NOTICE ^ (defined('E_DEPRECATED') ? E_DEPRECATED : 0)); // For Debugging. Recommended when you set up vsp for the first time.
    //error_reporting(E_ALL ^ E_NOTICE ^ (defined('E_DEPRECATED') ? E_DEPRECATED : 0) ^ E_WARNING); // Recommended for regular use after ensuring proper run of vsp.
    //error_reporting(E_ALL ^ (defined('E_DEPRECATED') ? E_DEPRECATED : 0));                        // For Debugging. Enable this if you are having a hard time to get vsp going.

    ini_set("default_charset", "UTF-8");             // default charset
    setlocale(LC_NUMERIC, 'C');                      // numeric locale (dont set a locale that uses commas for separating float numbers)
    
//================================================
// Database settings
    
    //$cfg['db']['adodb_path']= "C:/php/lib/adodb/"; // Uncomment/Enable this only if you have and want to use your own adodb libraries.
                                                     // Must specify it as an absolute path! ie:- "../../adodb" etc. are *NOT ALLOWED*
    
    $cfg['db']['adodb_driver']= 'mysqli'; 
    
    $cfg['db']['table_prefix'] = getenv("TABLE_PREFIX");           // use only lower case to minimize windows/linux portability problems
    
    $cfg['db']['hostname'] = getenv("HOSTNAME");
    $cfg['db']['dbname']   = getenv("DBNAME");      // use only lower case to minimize windows/linux portability problems
    $cfg['db']['username'] = getenv("USERNAME");
    $cfg['db']['password'] = getenv("PASSWORD");

//================================================
// Ip2Country table

    $cfg['ip2country']['source'] = 'ip-to-country.csv';   // cvs source file from which the ip2country table gets populated,
                                                          // file will be searched in sql directory or as an absolute path
                                                          // when it's absent
    $cfg['ip2country']['countries_only'] = 1;             // if enabled(1) the ip2country table will only load the information
                                                          // of countries and nothing more
                                                          // enable when the country code information is already contained
                                                          // in the log file (as with xp log files) to optimize space and
                                                          // lookup speed
    $cfg['ip2country']['columns']['ip_from'] = 0;         // column that holds the ip from field (0-indexed)
    $cfg['ip2country']['columns']['ip_to'] = 1;           // column that holds the ip to field (0-indexed)
    $cfg['ip2country']['columns']['country_code2'] = 2;   // column that holds the country code (0-indexed)
    $cfg['ip2country']['columns']['country_name'] = 4;    // column that holds de country name (0-indexed)


//================================================
// Game settings 

    $cfg['game']['name']='q3a'; /*** make sure this is set correctly! Read below! ***/
                                                     /*----------------------------------------
                                                     In order to find out what values are
                                                     acceptable for this variable, look in the
                                                     /pub/games/ folder in vsp. Any sub 
                                                     directory in this folder is valid.
                                                     ex:- 
                                                          'cod'     for Call of Duty and all its expansions/mods
                                                          'hl'      for Half Life and all its mods
                                                          'moh'     for Medal of Honor and all its expansions/mods
                                                          'q3a'     for Quake 3 Arena and all its expansions/mods
                                                          'rtcw'    for Return to Castle Wolfenstein and all its mods
                                                          'sof2'    for Soldier of Fortune 2
                                                          'wet'     for Wolfenstein Enemy Territory and all its mods
                                                          'default' for games that are not listed in pub/games/
                                                         etc.
                                                     ----------------------------------------*/

// Themes *may* use the following variables to do special processing for a particular game,mod,type
    $cfg['game']['mod']='xp';  // not used currently
    $cfg['game']['type']='default'; // not used currently
// The theme author decides how to use these variables.
// Check the documentation for the theme that you are using

//================================================
// Remote downloading of logs

    $cfg['ftp']['logs_path']= "./ftplogs/";        // Files downloaded from ftp server will go into this directory on local server.
    $cfg['ftp']['username'] = "ftp_user";
    $cfg['ftp']['password'] = "ftp_pass";
    $cfg['ftp']['pasv'] = 0;                       // Enable(1)/Disable(0) Passive mode. Some FTP servers may require this to be ON
    $cfg['ftp']['overwrite'] = 0;                  // Enable(1)/Disable(0) overwriting of file(s). A value of 0 resumes the log.

//================================================
// Parser Options
    
    $cfg['parser']['use_original_playerID'] = 1;                   // Check http://www.clanavl.com/ipb/index.php?showtopic=32
    $cfg['parser']['use_most_used_playerName'] = 1;                // use the most used playerName (set to 1) OR newest playerName (set to 0) as primary playerName
    $cfg['parser']['use_most_used_playerIP'] = 1;                  // use the most used playerIP (set to 1) OR newest playerIP (set to 0) as primary playerIP
    $cfg['parser']['check_unique_gameID'] = 1;                     // check uniqueness of game start date
                                                                   // disable if log doesn't have server date information
    
    
    
    // You can track players by guid and ip if the game/mod supports it. 
    // Tracking by guid is always the best option if its available. If it doesn't work track by playername
    
    //----- ***USE ONLY ONE OF THESE AT A TIME*** -----
    
    //$cfg['parser']['trackID'] = 'playerName';                      // Default method for tracking, works with all games/mods. If unsure, use this.
    
    //$cfg['parser']['trackID'] = 'ip=/(\d+\\.\d+\\.\d+\\.\d+)/';  // Track by ip AAA.BBB.CCC.DDD (NOT RECOMMENDED - the full ip of the player will be viewable by anyone)
    //$cfg['parser']['trackID'] = 'ip=/(\d+\\.\d+\\.\d+\\.)/U';    // Track by ip AAA.BBB.CCC.*   (recommended tracking format for ips)
    //$cfg['parser']['trackID'] = 'ip=/(\d+\\.\d+\\.\d)/U';        // Track by ip AAA.BBB.C*.*
    //$cfg['parser']['trackID'] = 'ip=/(\d+\\.)/U';                // Track by ip AAA.*.*.*
    
    $cfg['parser']['trackID'] = 'playerName';                          // Recommended method of tracking, if available for that game/mod
    
    //----- ***USE ONLY ONE OF THESE AT A TIME*** -----

//================================================
// Other settings

    $cfg['awardset']='default';
    $cfg['skillset']='default';
    $cfg['roleset']='default';
    $cfg['iconset']='default';
    $cfg['mapset']='default';
    $cfg['weaponset']='default';

    $cfg['player_ban_list']='default';
    $cfg['player_exclude_list']='default';

    $cfg['games_limit'] = 1000;                 // limit of detailed game stats that will be stored
                                                // on the database

//================================================
// Display settings
    
    $cfg['display']['record_limit']=50;
    $cfg['display']['days_inactivity']=30;      // number of days after which players are excluded
                                                // for rank and awards (0 means never)

//================================================
// Server info

    $cfg['display']['server_title']='HERE GOES YOUR SERVER TITLE';

    $cfg['display']['server_image']="../../images/server.gif";
    
    $cfg['display']['server_info']=<<<END_OF_SERVER_INFO
    <table style="border-width: 10;">
    <TR>
      <TD style="border-width: 0; text-align: right">Server:</TD>
      <TD style="border-width: 0; text-align: left" ><font color="LimeGreen">Your Server Name and IP goes here</font></TD>
    </TR>
    <TR>
      <TD style="border-width: 0; text-align: right">Game:</TD>
      <TD style="border-width: 0; text-align: left" >Your Game and Mod type goes here</TD>
    </TR>
    <TR>
      <TD style="border-width: 0; text-align: right">Admins:</TD>
      <TD style="border-width: 0; text-align: left" ><b><font color="DarkGoldenrod">List your admin(s) here</font></b></TD>
    </TR>
    <TR>
      <TD style="border-width: 0; text-align: right">E-Mail/MSN:</TD>
      <TD style="border-width: 0; text-align: left" >List your E-Mail and/or IM account here</TD>
    </TR>
    <TR>
      <TD style="border-width: 0; text-align: right">Web Site:</TD>
      <TD style="border-width: 0; text-align: left" ><a href="http://My.web_site_goes_here.com" target="_blank">My web site name goes here</a></TD>
    </TR>
    <TR>
      <TD style="border-width: 0; text-align: right">Quote:</TD>
      <TD style="border-width: 0; text-align: left" ><b>My quote goes here</b></TD>
    </TR>
    </table>
END_OF_SERVER_INFO;

//================================================
// Data Filters
// format :- $cfg['data_filter']['events']['eventCategory'] = REGEXP

// $cfg['data_filter']['events']['eventCategory'] = "/^R/";     means exclude the events in the category eventCategory where eventName begins with R
// $cfg['data_filter']['events'][''] = "/.*/";                  means exclude all events in category eventCategory where eventName matches anything

  $cfg['data_filter']['events']['weapon']="/.*/"; // excluded all weapon events
  $cfg['data_filter']['events']['ammo']="/.*/"; // excluded all ammo events
  //$cfg['data_filter']['events']['items']="/.*/"; // uncomment this line to exclude all item pickup events (mainly health, armor and powerups)
  $cfg['data_filter']['events']['']="/^(team_CTF_blueflag|team_CTF_redflag|team_CTF_neutralflag)/"; // innacurate
  
  //$cfg['data_filter']['gamedata']['']="/^(sv_|g_|p_)/";
  $cfg['data_filter']['gamedata']['']="/.*/";

//================================================

//==============================================================================
?>
