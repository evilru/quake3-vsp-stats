<?php 
  global $settings;
  
  //-[global settings]------------------------------

  // the default skin to use.
  $settings['display']['default_skin']='fest';

  // enable/disable skin selection by user. Set to 0 to disable (always uses default_skin), 1 to enable (user selected skin)
  $settings['display']['skin_selector']=1;
  
  // enable/disable colors in player names etc. Set to 0 to disable, 1 to enable
  $settings['display']['color_names']=1;

  // max number of characters to show in player names/guids etc. Set to 0 to disable
  $settings['display']['max_char_length']=32;

  // max number of characters to show in longer strings like Player Quotes/Chat etc. Set to 0 to disable
  $settings['display']['max_char_length_big']=100;

  // max number of characters to show in smaller strings like short map names etc
  $settings['display']['max_char_length_small']=10;
  
  // enable/disable display of server info on all pages. Set to 0 to disable, 1 to enable
  $settings['display']['server_info']=1;

  // enable/disable javascript tooltips (popups/bubbles) - much smoother and reliable tooltip but requires javascript enabled
  $settings['display']['javascript_tooltips']=1;

  //change: display/hide country flags
  $settings['display']['no_country_flags']=0;
  //endchange
  
  //------------------------------------------------
  
  //-[index.php settings]---------------------------

  // enable/disable Mini Award Stats list on main page. Set to 0 to disable, 1 to enable
  $settings['display']['mini_awardstats_list']=1;

  // enable/disable Mini Game Stats list on main page. Set to 0 to disable, 1 to enable
  $settings['display']['mini_gamestats_list']=0;
  
  // default sorting field for main listing
  $settings['display']['default_sort']='skill'; // [skill|kills|deaths|kill_streak|death_streak|efficiency|kd|kg|games]
  
  // enable/disable Random Quotes and Player Quotes. Set to 0 to disable or specify the number of quotes to display, -1 to enable only player quotes.
  $settings['display']['quotes']=4;
 
  // Player Listing conditions. You can use this to display only players that meet certain conditions.
  // Some common examples:-
  //   ="games>1";
  //   ="skill>1000";
  //   ="games>1 AND skill>1000";
  //   ="kills>0 OR deaths>0";
  $settings['display']['playerlist_conditions']="";
  
  //------------------------------------------------

  //-[playerstat.php settings]----------------------

  // adjust number of characters in guid display in playerstats
  $settings['display']['playerstats_guid_start']=0;
  $settings['display']['playerstats_guid_length']=32;

  $settings['display']['playerstats_max_awards_per_line']=5;
  
  //------------------------------------------------
  
  //-[gamestat.php settings]------------------------
  
  // enable/disable Game Stats. Set to 0 to disable, 1 to enable
  $settings['display']['gamestats']=1;
  
  // enable KillChart in Game Stats/Listing only if the number of players in the game is less than the one mentioned here 
  $settings['display']['gamestats_killchart']=27;

  // enable/disable KillChart stats for each weapon. Enabling this will result in a much larger html output (This feature doesn't seem to work in non standard browser like IE unless someone else writes in a workaround for it).
  $settings['display']['gamestats_killchart_per_weapon']=1;
  
  //------------------------------------------------

?>