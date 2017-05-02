//-------------------------------------------------
// VPS 0.45 ExcessivePlus Community Edition 1.2
//-------------------------------------------------

//-------------------------------------------------
Contents:

- vsp core 0.45 - http://www.scivox.net/vsp/vsp-core.zip
- q3a +expansions media pack - http://www.scivox.net/vsp/vsp-media-q3a-default.zip
- q3a 3wave map image pack - http://www.scivox.net/vsp/vsp-media-q3a-default-mapset-threewave.zip
- q3a cpma map image pack - http://www.scivox.net/vsp/vsp-media-q3a-default-mapset-cpma.zip
- q3a ctfspak1 map image pack - http://www.scivox.net/vsp/vsp-media-q3a-default-mapset-ctfscp.zip
- q3a excessiveplus upgrade
- Lots of custom map screenshots

//-------------------------------------------------
Changes:

1.2
- Made 200 the default default value for xp_version parser option for xp.
- Removed dependence on short_open_tag php ini directive being set.
- Dropped support for non-q3a game logs.
- Added $cfg['display']['days_inactivity'] option to disable inactivity players from main rank.
- Added $cfg['display']['server_title'] option.
- Reimplemented $cfg['ip2country'] options. Now using http://ip-to-country.webhosting.info/ as the ipdb backend.
- Added pop_ip2country action to only populate/update the ip2country table.
- Added $cfg['games_limit'] option so the database doesn't grow indefinitely.
- Added prune_old_games action.
- Add $cfg['parser']['check_unique_gameID'] option.
- Small tweaks on skills file.
- Country flag to awardstats.

1.1.2
- Added option to disable country flags.

1.1.1
- Added parser option: xp_version. Must be an integer containing the excessiveplus log version to be parsed, i.e. 103, 104. Default value is 103.
- Fixed special characters for both 1.03 and 1.04.
- Removed writing of temporary log file for non-1.03 version (bug is fixed on 1.04beta4).

1.1
- Fixed some charset problems and set utf8 as the charset.
- Added first and last seen fields to player profile based on the start time of the games they played.
- Added country flags. Using http://software77.net/geo-ip/ as the ip to country table.
- Added ip2country table and fields configuration options so multiple vsp installations can share the same table and it also can be on another database.
- Added use_most_used_playerIP config option so if set to 1 the country displayed is from the most used ip, or the last ip if set to 0.
- Replaced baseq3 maps screenshots with new 1.04 screenshots.
- Added icons for thaws and shame hall rewards by dakini.
- Fixed possible negative skill problem.
- Optimized database inserts.
- Fixed posible database sql injection exploit.
- Added search by country field.

1.0.2
- Slightly changed formula for skill calculation.
- Tweaks on the skill file definition.

1.0.1
- Improved accuracy awards calculation.
- Fixed double guids for players.
- Corrected some locale-related bugs.
- Added skin by fest.

1.0
- ExcessivePlus 1.03 logs support. This requires a pre-parsing of the file, and the writting of a temporary file that be used for the parsing. This causes a perfomance hit of like 10 seconds for every 10 mbs of log file size, so it's not a big deal.
- Added player_exclude_list support. Personally, i prefer this method of excluding players from the stats instead of banning the players (player_ban_list). When a player is banned all the events in wich he takes part (kill, items, thaws, etc.) are ignored, but not accuracy stats, so the stats at the end turn innacurate. When using player_exclude_list, the process of parsing is independent of the player excluding, so you can change the list at any time without affecting the stats.
- Skills system changed completely. New skill system explained below.
- Increased perfomance of the awards generation subroutine by an exponential factor - a database created by a log of 20 mbs that lasted 1.5 minutes generating awards only, with some queries of 20 seconds duration that presented a potential problem when the database grow, now lasts only 1 second.
- Added gen_awards action that only generates the awards. Useful when updating the player_exclude_list or the awards definition.
- Added awards for thaws. Also added the "Hall of Shame" section of the awards, but the awards of that section ship disabled by default.
- Added new fields for several awards with too little information.
- Savestate system changed from file-driven to database driven. Now there is no need to have any writable file by the program, only read access is enough for the system to work properly. You don't have to mess anymore with the savestate files stored on the logdata folder. Only works with q3a gamecode.
- Added clear_savestate action that clears the savestate information for one or all of the files.
- Added skill events by game, so you can now track your skill variation from game to game. Also added max and min skill fields to the profile information.
- Added rank field to the profile information.
- Added all possible skill-related modifiers to the skill file definition.
- Fixed several related bugs about stats being parsed incorrectly when two or more players with same ids play on same match.
- Fixed bug that occassionaly caused accuracy stats to be parsed twice.
- Fixed bug that showed frags caused by suicide explosions to be parsed as thaws. Also added "Suicide Frag" to the weapons definition so the weapon won't be listed as UNKNOWN in the stats.
- Fixed bug that caused conflicts on the stats of the grapple.
- Fixed bug when empty awards were given to players with empty id.
- Fixed several bugs and added several improvements to the bismark theme.
- Added skin an theme modifications by WaspKiller so several links don't point to non-existent pages.
- Added all map image packs available for q3a, and also included tons of new map images - all i could find in my hard drive.
- Added several fixes and improvements posted on the forums (http://www.scivox.net/smf/index.php?topic=738, http://www.scivox.net/smf/index.php?topic=574, http://www.scivox.net/smf/index.php?topic=1206.0, http://www.scivox.net/smf/index.php?topic=995.0).

//-------------------------------------------------
Note:

- It's quite probably that support for other gamecodes or even mods inside q3a may be broken. That's because the interest was focused 100% on excessiveplus mod for quake3 and openarena, so testing with other games/mods was not done.

//-------------------------------------------------
Skill system:

The main idea was taking from here: http://www.scivox.net/smf/index.php?topic=574. However, i went further and applied the same concepts for events and team events. This was done in such a way that skill points are now "transferred" among players, so the final sum of all the players of the system remain the same at every time. The purpose if this change is that it can be suitable to keep the stats indefinitely without resetting them in some period basis, because the skill of each player is based on the quality of the opponents he plays against, not the quantity of frags he makes.

All the skills configuration are placed in the default-skills.php file. The first 2 parameters are the mean and variance for the logistical distribution used to compute the probability of winning. When a new player encountered, he is given the value of the mean, and his skill changes depending of the type of event he plays on.

- For frag events, the most basic case, first you compute the probability of the killer of fragging the victim with both players skills as parameters along with the variance, like this:
prob_winning = 1/(1+e^((looser_skill-winner_skill)/variance))
Then, the the killer gets his skill points increased in (1-prob_winning)*weapon_factor, and the victim skills decrease on the same ammount. Use of negative weapon factors are allowed, so if you decide that killing someone with the grapple (to say an example) is lame you can penalty the killer for making a frag. The good thing about the system is that if you kill someone with higher skill points than you, you'll get more skill points, but if you keep fragging noobs, your skill points will increase too little.

- For normal events (thaw, ctf events, suicide, teamkills, item pickup, ammo pickup, weapon pickup and a few more), a variant of the above calculation is used: you (the player to which the event is awarded) get your skill points increased (or decreased in case of negative values, i.e. for suicides of team kills) by the same (1-prob_winning)*event_factor, using an average of the skill of the hole enemy team in the probability calculation, and the same points are substracted (or added) to the enemy team, making individual calculation for probability of losing against you to calculate how much will actually be substracted (or added) to each player of the enemy team, so that the sum of the ammounts are the same that will be increased (or decreased) to your points. Also, if the enemy team has less players than the winner, the skill points you'll earn will be reduced by the factor players_of_loser_team/players_of_winner_team, so that the points are more carefully awarded on unbalanced teams. However, the opposite its not true: you won't earn more points if the enemy team has more players than your team.

- Team events are events that affects the hole team. They are only three: score (for round based and flag gametypes only, i.e. all team gametypes except team deathmatch), wins, and loses. They all are fired at the end of the match. The points are increased/decreased for every one of the team (even players that left earlier in the game or players that switched sides in the middle of the game) using the system explained in the previous section, and multiplied by the ammount of events (wins and loses will allways be one per game, but not for score). Also, team unbalance penalties/rewards are applied.

//-------------------------------------------------
Credits:

vsp 0.45: 
--------
             Lead Programming/Design : myrddin (myrddin8 <AT> gmail <DOT> com)
                         Programming : react
                             Hosting : gouki
                             Website : http://www.clanavl.com/vsp/
                                       http://www.clanavl.com
                                 Irc : #vsp on irc.enterthegame.com

xp 1.0:
------
		WaspBeast - http://forums.excessiveplus.net/profile.php?mode=viewprofile&u=13161
