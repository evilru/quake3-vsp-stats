<?php
global $skillset;

//---------------------------------------------
// Parameters of the logistical distribution used to compute win probability
$skillset['defaults']['value'] = 1600.0; // mean of the distribution
$skillset['defaults']['variance'] = 400.0; // variance

// a fraction is gained by the killer and lost by the victim
//$skillset['fraction']['value'] = 1/1000.0; // unused

//--------------------------------
// Weapon factor for kills
$skillset['weapon_factor']['GAUNTLET']=6.0;//1.5;
$skillset['weapon_factor']['MACHINEGUN']=4.0;//1.0;
$skillset['weapon_factor']['SHOTGUN']=5.0;//1.0;
$skillset['weapon_factor']['GRENADE']=4.0;//1.5;
$skillset['weapon_factor']['ROCKET']=5.0;//1.25;
$skillset['weapon_factor']['LIGHTNING']=4.0;//1.25;
$skillset['weapon_factor']['PLASMA']=5.0;//1.25;
$skillset['weapon_factor']['RAILGUN']=5.0;//1.5;
$skillset['weapon_factor']['BFG']=4.0;//1.5;
$skillset['weapon_factor']['GRAPPLE']=0.0;
$skillset['weapon_factor']['TELEFRAG']=0.0;
$skillset['weapon_factor']['UNKNOWN']=0.0; // suicide frag

//-----------------------------------------
// Team events - affects a whole team
$skillset['event']['team|score']=0.0; // only for ctf or round based team gametype (i.e. all team gametypes except tdm): team score at the end of the game
$skillset['event']['team|loss']=0.0; // team losses game
$skillset['event']['team|wins']=0.0; // team wins game

//----------------------------------
// CTF
$skillset['event']['CTF|Flag_Return']=2.0;//1.0;
$skillset['event']['CTF|Kill_Carrier']=4.0;//2.0;
$skillset['event']['CTF|Defend_Base']=2.0;//1.0;
$skillset['event']['CTF|Defend_Flag']=2.0;//1.0;
$skillset['event']['CTF|Flag_Assist_Frag']=2.0;//1.0;
$skillset['event']['CTF|Flag_Assist_Return']=2.0;//1.0;
$skillset['event']['CTF|Flag_Pickup']=2.0;//1.0;
$skillset['event']['CTF|Defend_Hurt_Carrier']=4.0;//2.0;
$skillset['event']['CTF|Hurt_Carrier_Defend']=4.0;//2.0;
$skillset['event']['CTF|Defend_Carrier']=4.0;//2.0;
$skillset['event']['CTF|Flag_Capture']=20.0;//10.0;

//---------------------------------------
// Freeze tag
$skillset['event']['THAW']=4.0; // freeze tag

//---------------------------------------
// Suicide
$skillset['event']['suicide|FALLING']=0.0;
$skillset['event']['suicide|TRIGGER_HURT']=0.0;
$skillset['event']['suicide|CAMPER']=0.0;
$skillset['event']['suicide|SUICIDE']=0.0;
$skillset['event']['suicide|LAVA']=0.0;
$skillset['event']['suicide|CRUSH']=0.0;
$skillset['event']['suicide|SLIME']=0.0; // ??
$skillset['event']['suicide|WATER']=0.0;
$skillset['event']['suicide|GAUNTLET']=0.0;
$skillset['event']['suicide|MACHINEGUN']=0.0;
$skillset['event']['suicide|SHOTGUN']=0.0;
$skillset['event']['suicide|GRENADE']=0.0;
$skillset['event']['suicide|ROCKET']=0.0;
$skillset['event']['suicide|LIGHTNING']=0.0;
$skillset['event']['suicide|PLASMA']=0.0;
$skillset['event']['suicide|RAILGUN']=0.0;
$skillset['event']['suicide|BFG']=0.0;
$skillset['event']['suicide|GRAPPLE']=0.0;
$skillset['event']['suicide|TELEFRAG']=0.0;

// ---------------------------------------
// Team kills
$skillset['event']['teamkill|GAUNTLET']=0.0;
$skillset['event']['teamkill|MACHINEGUN']=0.0;
$skillset['event']['teamkill|SHOTGUN']=0.0;
$skillset['event']['teamkill|GRENADE']=0.0;
$skillset['event']['teamkill|ROCKET']=0.0;
$skillset['event']['teamkill|LIGHTNING']=0.0;
$skillset['event']['teamkill|PLASMA']=0.0;
$skillset['event']['teamkill|RAILGUN']=0.0;
$skillset['event']['teamkill|BFG']=0.0;
$skillset['event']['teamkill|GRAPPLE']=0.0;
$skillset['event']['teamkill|TELEFRAG']=0.0;
$skillset['event']['teamkill|UNKNOWN']=0.0; // suicide team kill

//----------------------------------------
// Item pickup
$skillset['event']['item|armor_body']=0.0;
$skillset['event']['item|armor_shard']=0.0;
$skillset['event']['item|armor_combat']=0.0;
$skillset['event']['item|armor_jacket']=0.0;
$skillset['event']['item|health_mega']=0.0;
$skillset['event']['item|health_large']=0.0;
$skillset['event']['item|health']=0.0;
$skillset['event']['item|health_small']=0.0;
$skillset['event']['item|quad']=0.0;
$skillset['event']['item|invis']=0.0;
$skillset['event']['item|regen']=0.0;
$skillset['event']['item|enviro']=0.0;
$skillset['event']['item|haste']=0.0;
$skillset['event']['item|flight']=0.0;
$skillset['event']['item|team_CTF_redflag']=0.0; // disabled on default cfg
$skillset['event']['item|team_CTF_blueflag']=0.0; // disabled on default cfg
$skillset['event']['item|team_CTF_neutralflag']=0.0; // should be disabled like the previous 2

//-----------------------------------------
// Weapon pickups - disabled on default cfg
$skillset['event']['weapon|gauntlet']=0.0;
$skillset['event']['weapon|machinegun']=0.0;
$skillset['event']['weapon|shotgun']=0.0;
$skillset['event']['weapon|grenadelauncher']=0.0;
$skillset['event']['weapon|rocketlauncher']=0.0;
$skillset['event']['weapon|lightning']=0.0;
$skillset['event']['weapon|railgun']=0.0;
$skillset['event']['weapon|plasmagun']=0.0;
$skillset['event']['weapon|bfg']=0.0;

//-----------------------------------------
// Ammo pickups - disabled on default cfg
$skillset['event']['ammo|bullets']=0.0;
$skillset['event']['ammo|shells']=0.0;
$skillset['event']['ammo|grenades']=0.0;
$skillset['event']['ammo|rockets']=0.0;
$skillset['event']['ammo|lightning']=0.0;
$skillset['event']['ammo|slugs']=0.0;
$skillset['event']['ammo|cells']=0.0;
$skillset['event']['ammo|bfg']=0.0;

//-----------------------------------------
// Accuracy - launched at the end of the game or when the client disconnects, innacurate
$skillset['event']['accuracy|GAUNTLET_shots']=0.0;
$skillset['event']['accuracy|GAUNTLET_hits']=0.0;
$skillset['event']['accuracy|MACHINEGUN_shots']=0.0;
$skillset['event']['accuracy|MACHINEGUN_hits']=0.0;
$skillset['event']['accuracy|SHOTGUN_shots']=0.0;
$skillset['event']['accuracy|SHOTGUN_hits']=0.0;
$skillset['event']['accuracy|GRENADE_shots']=0.0;
$skillset['event']['accuracy|GRENADE_hits']=0.0;
$skillset['event']['accuracy|ROCKET_shots']=0.0;
$skillset['event']['accuracy|ROCKET_hits']=0.0;
$skillset['event']['accuracy|LIGHTNING_shots']=0.0;
$skillset['event']['accuracy|LIGHTNING_hits']=0.0;
$skillset['event']['accuracy|PLASMA_shots']=0.0;
$skillset['event']['accuracy|PLASMA_hits']=0.0;
$skillset['event']['accuracy|RAILGUN_shots']=0.0;
$skillset['event']['accuracy|RAILGUN_hits']=0.0;
$skillset['event']['accuracy|BFG_shots']=0.0;
$skillset['event']['accuracy|BFG_hits']=0.0;
$skillset['event']['accuracy|GRAPPLE_shots']=0.0;
$skillset['event']['accuracy|GRAPPLE_hits']=0.0;

//-----------------------------------------
// Miscelaneous
$skillset['event']['first victim']=0.0;
$skillset['event']['first killer']=0.0;
$skillset['event']['damage given']=0.0; // innacurate
$skillset['event']['damage taken']=0.0; // innacurate
$skillset['event']['damage to team']=0.0; // inaccurate
$skillset['event']['holdable_medkit']=0.0;
$skillset['event']['holdable_teleporter']=0.0;
$skillset['event']['score']=0.0; // individual score at the end of the game, inaccurate
?>