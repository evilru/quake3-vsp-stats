<?php
  /*
   * Prefered method of excluding players instead of banning
   */
  global $player_exclude_list;

  // Specify the ID of the player - The ID depends on what you are tracking by
  // Specify color coded player names if tracking by playerName
  // Specify GUIDs if tracking by guid
  // Specify IPs   if tracking by ip

  $player_exclude_list=array(
      //'' // uncomment the line to exclude all the bots when tracking by guid in xp
  );

?>