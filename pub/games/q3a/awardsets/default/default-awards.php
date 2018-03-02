<?php
//change: add player_exclude_list support and some additional fields for some queries
//change: add some awards
global $awardset, $player_exclude_list, $db;

$auxwhere = "";
if ( count( $player_exclude_list ) ) {
    $auxwhere .= " and {$tp}playerprofile.playerID not in (".implode( ", ", $player_exclude_list ).")";
}
if ( $last_update ) {
    $auxwhere .= " and DATEDIFF($last_update, {$tp}playerprofile.last_seen) <= {$GLOBALS['cfg']['display']['days_inactivity']}";
}

$awardset['avg_games']['sql'][0]    = "select AVG(games) / 2
                                       from {$tp}playerprofile
                                       where 1 $auxwhere
                                    ";

                         $avg_games="({\$awardset['avg_games']['sql'][0][0]}-1)";
//------------------------------------------------ added
$awardset['TAG_Thaw']['name']                      = 'Best Thawer';
$awardset['TAG_Thaw']['image']                     = 'TAG_thaw';
$awardset['TAG_Thaw']['category']                  = 'Freeze Tag';
$awardset['TAG_Thaw']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as thaws,
                                                            {$tp}playerprofile.games as games,
                                                            round(sum(eventValue)/games,2) as ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where eventName='THAW'
                                                             and games>$avg_games
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere

                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY ratio DESC
                                                    ";

//------------------------------------------------
$awardset['CTF_Assist']['name']                      = 'Best Supporter';
$awardset['CTF_Assist']['image']                     = 'CTF_Supporter';
$awardset['CTF_Assist']['category']                  = 'CTF';
$awardset['CTF_Assist']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Supports, 
                                                            {$tp}playerprofile.games as Games, 
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where (eventName='Defend_Carrier' OR eventName='Defend_Hurt_Carrier' OR eventName='Flag_Assist_Return' OR eventName='Flag_Assist_Frag')
                                                             and eventCategory='CTF'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere

                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";

//------------------------------------------------
$awardset['CTF_Retriever']['name']                      = 'Best Flag Retriever';
$awardset['CTF_Retriever']['image']                     = 'CTF_Retriever';
$awardset['CTF_Retriever']['category']                  = 'CTF';
$awardset['CTF_Retriever']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Retrieves,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where (eventName='Kill_Carrier' OR eventName='Flag_Return')
                                                             and eventCategory='CTF'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";


//------------------------------------------------
$awardset['CTF_Defend']['name']                      = 'Best Defender';
$awardset['CTF_Defend']['image']                     = 'CTF_Defend';
$awardset['CTF_Defend']['category']                  = 'CTF';
$awardset['CTF_Defend']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Defends,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where (eventName='Defend_Base' OR eventName='Defend_Flag')
                                                             and eventCategory='CTF'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";


//------------------------------------------------
$awardset['CTF_Flag_Capture']['name']                      = 'Best Flag Capper';
$awardset['CTF_Flag_Capture']['image']                     = 'CTF_Flag_Capture';
$awardset['CTF_Flag_Capture']['category']                  = 'CTF';
$awardset['CTF_Flag_Capture']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Captures,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where eventName='Flag_Capture' 
                                                             and eventCategory='CTF'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";

//------------------------------------------------
$awardset['item_quad']['name']                      = 'Quad Whore';
$awardset['item_quad']['image']                     = 'item_quad';
$awardset['item_quad']['category']                  = 'Item';
$awardset['item_quad']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Pickups,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where eventName='quad' 
                                                             and eventCategory='item'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";
//------------------------------------------------
$awardset['item_regen']['name']                      = 'Regen Romper';
$awardset['item_regen']['image']                     = 'item_regen';
$awardset['item_regen']['category']                  = 'Item';
$awardset['item_regen']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Pickups,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where eventName='regen' 
                                                             and eventCategory='item'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";
//------------------------------------------------
$awardset['item_haste']['name']                      = 'Haste Hog';
$awardset['item_haste']['image']                     = 'item_haste';
$awardset['item_haste']['category']                  = 'Item';
$awardset['item_haste']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Pickups,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where eventName='haste' 
                                                             and eventCategory='item'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";
//------------------------------------------------
$awardset['item_mega']['name']                      = 'Mega Slut';
$awardset['item_mega']['image']                     = 'item_mega';
$awardset['item_mega']['category']                  = 'Item';
$awardset['item_mega']['sql'][0]                    = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Pickups,
                                                            {$tp}playerprofile.games as Games,
                                                            round(sum(eventValue)/games,2) as Ratio
                                                       from {$tp}playerprofile,{$tp}eventdata1d_total
                                                       where eventName='health_mega' 
                                                             and eventCategory='item'
                                                             and games>$avg_games 
                                                             and {$tp}playerprofile.playerID={$tp}eventdata1d_total.playerID
                                                             $auxwhere
                                                             
                                                       group by {$tp}eventdata1d_total.playerID
                                                       ORDER BY Ratio DESC
                                                    ";
//------------------------------------------------

$awardset['kr']['name']                             = 'Best Killer';
$awardset['kr']['image']                            = 'player_killer';
$awardset['kr']['category']                         = 'Player';
      
$awardset['kr']['sql'][0]                           = "select playerID, countryCode, playerName, kills, games, round(kills/games,2) as Ratio
                                                       from {$tp}playerprofile
                                                       where games>$avg_games
                                                             $auxwhere
                                                       ORDER BY Ratio DESC
                                                    ";
//------------------------------------------------
$awardset['ks']['name']                             = 'Highest Kill Streak';
$awardset['ks']['image']                            = 'player_killstreak';
$awardset['ks']['category']                         = 'Player';

$awardset['ks']['sql'][0]                           = "select playerID, countryCode, playerName, kill_streak
                                                       from {$tp}playerprofile
                                                       where 1 $auxwhere
                                                       ORDER BY kill_streak DESC
                                                    ";
//------------------------------------------------
$awardset['eff']['name']                            = 'Best Efficiency';
$awardset['eff']['image']                           = 'player_efficiency';
$awardset['eff']['category']                        = 'Player';

$awardset['eff']['sql'][0]                          = "select playerID, countryCode, playerName, kills, deaths, kills/(1+kills+deaths) as Efficiency
                                                       from {$tp}playerprofile
                                                       where games>$avg_games
                                                             $auxwhere
                                                       ORDER BY Efficiency DESC
                                                    ";
//------------------------------------------------
$awardset['killer__v_weapons']['name']                = 'Best Killer with _v_weapons';
$awardset['killer__v_weapons']['image']               = 'killer__v_weapons';
$awardset['killer__v_weapons']['category']            = 'Carnage';
 
$awardset['killer__v_weapons']['sql'][0]              = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Kills,
                                                            {$tp}playerprofile.games as Games,
                                                            round((sum(eventValue)/{$tp}playerprofile.games),2) as kill_ratio
                                                      from {$tp}eventdata2d_total,{$tp}playerprofile
                                                      where {$tp}playerprofile.games>$avg_games and
                                                            (eventName='_v_weapons') and
                                                            eventCategory='kill' and
                                                            {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID
                                                            $auxwhere
                                                      group by {$tp}eventdata2d_total.playerID
                                                      ORDER by kill_ratio DESC
                                                   ";

/*
//------------------------------------------------
// Hall of Shame
//
$awardset['shame_suicide']['name']                = 'Suicidal Moron';
$awardset['shame_suicide']['image']               = 'shame_suicide';
$awardset['shame_suicide']['category']            = 'Shame Hall';
$awardset['shame_suicide']['sql'][0]              = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as Suicides,
                                                            {$tp}playerprofile.games as Games,
                                                            round((sum(eventValue)/{$tp}playerprofile.games),2) as Ratio
                                                      from {$tp}eventdata2d_total,{$tp}playerprofile
                                                      where {$tp}playerprofile.games>$avg_games and
                                                            eventCategory='suicide' and
                                                            {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID
                                                            $auxwhere
                                                      group by {$tp}eventdata2d_total.playerID
                                                      ORDER BY Ratio DESC
                                                    ";

//------------------------------------------------ added
$awardset['shame_camper']['name']                = 'Cheap Ass Camper';
$awardset['shame_camper']['image']               = 'shame_camper';
$awardset['shame_camper']['category']            = 'Shame Hall';
$awardset['shame_camper']['sql'][0]              = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as `Camp Protection Suicides`,
                                                            {$tp}playerprofile.games as Games,
                                                            round((sum(eventValue)/{$tp}playerprofile.games),2) as Ratio
                                                      from {$tp}eventdata2d_total,{$tp}playerprofile
                                                      where {$tp}playerprofile.games>$avg_games and
                                                            eventName='CAMPER' and
                                                            eventCategory='suicide' and
                                                            {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID
                                                            $auxwhere
                                                      group by {$tp}eventdata2d_total.playerID
                                                      ORDER BY Ratio DESC
                                                    ";

//------------------------------------------------ added
$awardset['shame_teamkill']['name']                = 'Team Shooting Traitor';
$awardset['shame_teamkill']['image']               = 'shame_teamkill';
$awardset['shame_teamkill']['category']            = 'Shame Hall';
$awardset['shame_teamkill']['sql'][0]              = "select {$tp}playerprofile.playerID,
                                                            {$tp}playerprofile.countryCode,
                                                            {$tp}playerprofile.playerName,
                                                            sum(eventValue) as `Team Kills`,
                                                            {$tp}playerprofile.games as Games,
                                                            round((sum(eventValue)/{$tp}playerprofile.games),2) as Ratio
                                                      from {$tp}eventdata2d_total,{$tp}playerprofile
                                                      where {$tp}playerprofile.games>$avg_games and
                                                            eventCategory='teamkill' and
                                                            {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID
                                                            $auxwhere
                                                      group by {$tp}eventdata2d_total.playerID
                                                      ORDER BY Ratio DESC
                                                    ";

//------------------------------------------------
$awardset['shame_death_strike']['name']            = 'Most Deaths In A Row';
$awardset['shame_death_strike']['image']           = 'shame_death_strike';
$awardset['shame_death_strike']['category']        = 'Shame Hall';

$awardset['shame_death_strike']['sql'][0]          = "select playerID, countryCode, playerName, death_streak
                                                       from {$tp}playerprofile
                                                       where 1 $auxwhere
                                                       ORDER BY death_streak DESC
                                                    ";*/




//------------------------------------------------------------------
// Accuracy awards using compiled tables to make it fast - optimized process
//
$allweaps_id = md5(time()); // haha, i must be a little paranoic xD

$awardset['accuracy_set_table']['sql'][0]    = "drop table {$tp}awardsweaponshots";

$awardset['accuracy_set_table']['sql'][1]    = "CREATE TABLE {$tp}awardsweaponshots (
                                                playerID varchar(100) NOT NULL default ''
                                                ,weaponID varchar(100) NOT NULL default ''
                                                ,shots int(10) unsigned default '0'
                                                ,PRIMARY KEY  (playerID,weaponID)
                                              ) ENGINE=MyISAM
                                              ";

$awardset['accuracy_set_table']['sql'][2]    = "insert into {$tp}awardsweaponshots
                                              select {$tp}playerprofile.playerID,
                                                    '{$allweaps_id}' as weaponID,
                                                    sum(eventValue) as shots
                                              from {$tp}eventdata2d_total,{$tp}playerprofile
                                              where {$tp}playerprofile.games>$avg_games and
                                                    eventName LIKE '%_shots' and
                                                    eventCategory='accuracy' and
                                                    {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID
                                                    $auxwhere
                                              group by {$tp}eventdata2d_total.playerID
                                              ";

$awardset['accuracy__v_weapons_populate_table']['sql'][0]  = "insert into {$tp}awardsweaponshots
                                                              select {$tp}playerprofile.playerID,
                                                                    '_v_weapons' as weaponID,
                                                                    sum(eventValue) as shots
                                                              from {$tp}eventdata2d_total,{$tp}playerprofile
                                                              where {$tp}playerprofile.games>$avg_games and
                                                                    eventName='_v_weapons_shots' and
                                                                    eventCategory='accuracy' and
                                                                    {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID
                                                                    $auxwhere
                                                              group by {$tp}eventdata2d_total.playerID
                                                              ";


$awardset['avg_shots__v_weapons']['sql'][0]    = "select avg(shots/games+1) / 2
                                               from {$tp}awardsweaponshots
                                               where weaponID='_v_weapons'
                                               group by weaponID
                                    ";


$awardset['accuracy__v_weapons']['name']                 = 'Best Accuracy with _v_weapons';
$awardset['accuracy__v_weapons']['image']                = 'accuracy__v_weapons';
$awardset['accuracy__v_weapons']['category']             = 'Accuracy';
$awardset['accuracy__v_weapons']['sql'][0]               = "select {$tp}playerprofile.playerID,
                                                                {$tp}playerprofile.countryCode,
                                                                {$tp}playerprofile.playerName,
                                                                {$tp}playerprofile.games as games,
                                                                sum(eventValue) as hits,
                                                                shots,
                                                                round(sum(eventValue)/shots*100,2) as accuracy
                                                          from {$tp}eventdata2d_total,{$tp}playerprofile,{$tp}awardsweaponshots
                                                          where weaponID='_v_weapons' and
                                                                eventName='_v_weapons_hits' and
                                                                eventCategory='accuracy' and
                                                                {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID and
                                                                {$tp}playerprofile.playerID={$tp}awardsweaponshots.playerID
                                                          group by {$tp}eventdata2d_total.playerID
                                                          having (shots/games+1) > ({\$awardset['avg_shots__v_weapons']['sql'][0][0]}-1)
                                                          ORDER by accuracy DESC
                                                          ";




$awardset['accuracyALLWEAPONS']['name']                 = 'Best Overall Accuracy';
$awardset['accuracyALLWEAPONS']['image']                = 'accuracy_ALLWEAPONS';
$awardset['accuracyALLWEAPONS']['category']             = 'Accuracy';
$awardset['accuracyALLWEAPONS']['sql'][0]               = "select {$tp}playerprofile.playerID,
                                                                {$tp}playerprofile.countryCode,
                                                                {$tp}playerprofile.playerName,
                                                                {$tp}playerprofile.games as games,
                                                                sum(eventValue) as hits,
                                                                shots,
                                                                round(sum(eventValue)/shots*100,2) as accuracy
                                                          from {$tp}eventdata2d_total,{$tp}playerprofile,{$tp}awardsweaponshots
                                                          where weaponID='{$allweaps_id}' and
                                                                eventName like '%_hits' and
                                                                eventCategory='accuracy' and
                                                                {$tp}playerprofile.playerID={$tp}eventdata2d_total.playerID and
                                                                {$tp}playerprofile.playerID={$tp}awardsweaponshots.playerID
                                                          group by {$tp}eventdata2d_total.playerID
                                                          ORDER by accuracy DESC
                                                           ";



?>