<?php
/*************************************************
-----------------
AWARD DEFINITIONS
-----------------

$awardset['skill'] is everything related to the skill award you want to define
$awardset['accuracy'] is everything related to the accuracy award you want to define 
and so on... In the above example 'skill' and 'accuracy' are the "keys" for the award. 
Each award must have a unique key and there are a few special keys that usually begin 
with _v_ which cannot be user-defined.

Special keys:-
_v_weapons will be substituted  with every unique "kill" weapon in the database.



Each award should have a name,an image and at least one sql statement
or maybe more than one sql which should finally evaluate to the playerID 
for that award.
$awardset['skill']['name']
$awardset['skill']['image']
$awardset['skill']['sql'][0]    1st sql statement for skill award 
$awardset['skill']['sql'][1]    2nd sql statement for skill award 

$awardset['skill']['sql'][0] holds the first sql statement for that award
If you can get the playerID of the player with just this one sql statement,
then you are done. The result, which should be the playerID will be in
$awardset['skill']['sql'][0][0]

Sometimes you need to do multiple queries to get the playerID of the award.
Lets say you want to declare an award for the person with the most kills.
This might involve two queries on some databases
  1. You have to find out what the highest kills in the database is
  2. You have to get the playerID who has that amount of kills (obtained from step 1)
So the definition would look as follows,

  $awardset['kill']['sql'][0] = "select MAX(kills) from {$tp}playerprofile";
  $awardset['kill']['sql'][1] = "select playerID from {$tp}playerprofile where kills={\$awardset['kill']['sql'][0][0]}";

{$tp} is the table prefix and must be present before every table name

Notice how the result of the first statement is used to calculate the second statement
Also note the \ infront of the $ in the second sql statement.
You need that \ if you are using the result of a previous query in another sql statement
$awardset['kill']['sql'][0] is the first sql statement
$awardset['kill']['sql'][0][0] holds the first result of that sql statement

What is the first result of an sql statement? Is there a second result or third and so on?
Sometimes you need to get two or more data items from the database in one sql statement
ex:-
  $awardset['kd']['sql'][0] = "select MAX(kills),MAX(deaths) from {$tp}playerprofile";
Note the two data items MAX(kills) and MAX(deaths) in one sql statement.
In the above case,
$awardset['kd']['sql'][0][0] holds the result of MAX(kills)
$awardset['kd']['sql'][0][1] holds the result of MAX(deaths)
which can then be used to determine the playerID with the most kill/death ratio or something.

Also keep in mind, for each query, only the first row is retrieved from the database,
so use ORDER BY and things like that to make the result you want come on top as the first row

The first result in the last sql statement in every award should always result in the playerID
The second result in the last sql statement in every award should always result in the playerName
The last result in the last sql statement in every award should always result in the award value

You can also use $awardset to hold common data that can be used to calculate all the awards.
Just make sure you dont assign it a name, so it wont show up as an award.
For example $awardset['avg_games']['sql'][0] can hold the sql for average games played on the server.

You can use temp variables to make long variable names short
ex:-
  $avg_games="{\$awardset['avg_games']['sql'][0][0]}";

Note the \ infront of the $ again
So now if you want to use the average amount of games in several of your 
sql queries for awards, then you can just use $avg_games instead of using
the long {\$awardset['avg_games']['sql'][0][0]} like:-

  $awardset['kill']['sql'][0]   = "select MAX(kills/(games+$avg_games)) from {$tp}playerprofile".

Make sure to follow php syntax for everything which can be found at www.php.net

*************************************************/
global $awardset;

$awardset['avg_games']['sql'][0]    = "select AVG(games) 
                                       from {$tp}playerprofile
                                    ";

                         $avg_games = "({\$awardset['avg_games']['sql'][0][0]}-1)";
//------------------------------------------------

$awardset['kr']['name']                             = 'Best Killer';
$awardset['kr']['image']                            = 'player_killer';
$awardset['kr']['category']                         = 'Player';
      
$awardset['kr']['sql'][0]                           = "select playerID, playerName, kills/(games+$avg_games) from {$tp}playerprofile
                                                       where games>$avg_games 
                                                       ORDER BY kills/(games+$avg_games) DESC
                                                    ";
//------------------------------------------------
$awardset['ks']['name']                             = 'Highest Kill Streak';
$awardset['ks']['image']                            = 'player_killstreak';
$awardset['ks']['category']                         = 'Player';

$awardset['ks']['sql'][0]                           = "select playerID, playerName, kill_streak
                                                       from {$tp}playerprofile
                                                       ORDER BY kill_streak DESC
                                                    ";
//------------------------------------------------
$awardset['eff']['name']                            = 'Best Efficiency';
$awardset['eff']['image']                           = 'player_efficiency';
$awardset['eff']['category']                        = 'Player';

$awardset['eff']['sql'][0]                          = "select playerID, playerName, kills/(1+kills+deaths)
                                                       from {$tp}playerprofile
                                                       where games>$avg_games 
                                                       ORDER BY kills/(1+kills+deaths) DESC
                                                    ";
//------------------------------------------------
$awardset['killer__v_weapons']['name']                = 'Best Killer with _v_weapons';
$awardset['killer__v_weapons']['image']               = 'killer__v_weapons';
$awardset['killer__v_weapons']['category']            = 'Carnage';
 
$awardset['killer__v_weapons']['sql'][0]              = "select {$tp}playerprofile.playerID,{$tp}playerprofile.playerName,sum(eventValue) as Kills, {$tp}playerprofile.games as Games, round((sum(eventValue)/{$tp}playerprofile.games),2) as kill_ratio
                                                      from {$tp}eventdata2d,{$tp}playerprofile
                                                      where {$tp}playerprofile.games>$avg_games and
                                                            (eventName='_v_weapons') and
                                                            eventCategory='kill' and
                                                            {$tp}playerprofile.playerID={$tp}eventdata2d.playerID and
                                                            {$tp}eventdata2d.playerID!={$tp}eventdata2d.player2ID
                                                      group by {$tp}eventdata2d.playerID
                                                      ORDER by kill_ratio DESC
                                                   ";

?>