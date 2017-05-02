<?php
//------------------------------------------------------------------------------
// Pretty simple formula for now, which is basically:-
//
// $victim_based_skill_no =  $victimSkill * $skillset_fraction ;
//
// $KillerSkill+=$weaponFactor * $victim_based_skill_no;
// $VictimSkill-=$victim_based_skill_no;
// 
// In the event of a suicide or a teamkill, the appropriate penalty based on the
// above formula is applied
//------------------------------------------------------------------------------

global $skillset;

// a fraction is gained by the killer and lost by the victim
$skillset['fraction']['value'] = 1/1000.0;

// weapon modifier
$skillset['weapon_factor']['WEAPON_1_ID']=1.5;
$skillset['weapon_factor']['WEAPON_2_ID']=1.0;

// event skill is just added to the player's current skill
$skillset['event']['event_1']=1.0;
$skillset['event']['event_2']=2.0;
$skillset['event']['eventCategory_1|event_1']=10.0;
$skillset['event']['eventCategory_1|event_2']=5.0;
$skillset['event']['eventCategory_2|event_1']=1.5;
$skillset['event']['eventCategory_2|event_2']=1.0;

?>


