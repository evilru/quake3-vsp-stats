<?php
$start_time=gettimeofday();

require("./util.php");
require("./all.inc.php");
require(getConfig());
require("{$GLOBALS['cfg']['db']['adodb_path']}".'adodb.inc.php');

$db = &ADONewConnection("{$GLOBALS['cfg']['db']['adodb_driver']}");
if(!$db->Connect($GLOBALS['cfg']['db']['hostname'], $GLOBALS['cfg']['db']['username'], $GLOBALS['cfg']['db']['password'], $GLOBALS['cfg']['db']['dbname']))
{
  print "error: cannot establish database connection\n";
  exit();
}
//$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->SetFetchMode(ADODB_FETCH_NUM);

$playerID=$_GET['playerID'];

$qplayerID=$db->qstr($playerID); // playerID quoted for sql



setSkin();

setupVars();

//change: add player_exclude_list support
load_player_exclude_list();
if (in_array($qplayerID, $player_exclude_list)) {
    $playerID = md5(time()); // stupid bots with empty guid :-/
    $qplayerID=$db->qstr($playerID); // playerID quoted for sql
}
//endchange

getStats();
getStats1D();

readPlayerData();

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<TITLE>vsp (player stats)</TITLE>
<LINK REL=stylesheet HREF="<?php  print $GLOBALS['stylesheet']; ?>" TYPE="text/css">
<?php  if ($settings['display']['javascript_tooltips']) includeDOMTT();?>
<script language="javascript" type="text/javascript" src="../../lib/sorttable/sorttable.js"></script>
<script language="JavaScript" type="text/javascript">

function searchByGUID(search_txt)
{
  document.guidform.search_txt.value=''+search_txt;
  document.guidform.submit();
}
function searchByIP(search_txt)
{
  document.ipform.search_txt.value=''+search_txt;
  document.ipform.submit();
}
function searchByName(search_txt)
{
  document.nameform.search_txt.value=''+search_txt;
  document.nameform.submit();
}
</script>
</HEAD>

<?php
$pre_time=timeElapsed($start_time);
ob_start("compactHTML");
?>

<body>


<?php
//*************************************************************************
function setupVars()
{
  if (!is_dir("../../games/{$GLOBALS['cfg']['game']['name']}"))
  {
    $GLOBALS['cfg']['game']['name']='default';
  }
}
//*************************************************************************
function drawHeadBar()
{
  global $sort,$config;
  ?>
  <!-- navbar begin          ################################################-->
  <table CLASS="cellHeading" CELLSPACING="0" CELLPADDING="1" WIDTH="100%" style="border-width: 0;">
    <TR>
    <TD HEIGHT="25" CLASS="cellHeading" style="border-right-width: 0; text-align: left;" >
      <B>Player Profile</B>
    </TD>
    </TR>
  </table>
  <!-- navbar end            ################################################-->
  <?php
}
//*************************************************************************
function drawStats1D($eventCategory)
{
  
  global $db;
  global $gmatrix1D,$gevent1D,$geventName1D;
  
  $gevent1D_tmp = $gevent1D;
  
  $max_items=0;
  foreach($gevent1D_tmp as $eventCat => $val)
  {
    $max_new = count($val);
    if ($max_new>$max_items)
      $max_items = $max_new;
  }
  
  ?>
  <!-- events per category begin ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=0 WIDTH="100%" >
  <TR CLASS="cellSubHeading">
    <TD COLSPAN="2" WIDTH="100%" style="text-align: left"><?php print fstr($eventCategory);?>&nbsp;</TD>
  </TR>
  <?php
  $count=0;
  if (@$gmatrix1D[$eventCategory])
  {
    //htmlprint_r($gmatrix1D[$teamID][$playerID]);
    foreach($gmatrix1D[$eventCategory] as $eventName => $eventValue)
    {
      
      
      
      if ($count%2 == 0)
        $cell_class="cell1";
      else
        $cell_class="cell2";
      $count++;

      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
      <TD WIDTH="70%" style="text-align: left"><?php print fstr($eventName);?></TD>
      <TD WIDTH="30%"><?php print fstr($eventValue);?></TD>
      </TR>
      <?php


    }
  }
  
  for($i=$count;$i<$max_items;$i++)
  {
    if ($i%2 == 0)
      $cell_class="cell1";
    else
      $cell_class="cell2";

    ?>
    <TR CLASS="<?php print "$cell_class"?>">
    <TD WIDTH="70%">&nbsp;</TD>
    <TD WIDTH="30%">&nbsp;</TD>
    </TR>
    <?php
  }
  ?>
  </table>
  <!-- events per category end   ##################################################-->
  <?php
}
//*************************************************************************
function drawPlayerStats()
{
  $categories_per_row=3;
  global $db;
  global $gmatrix1D,$gevent1D;
  global $gmatrix,$gmatrixtotal,$gweapon;
  global $player_data;

  

  ?>

      <table style="border-width: 0; padding: 0px 0px 0px 0px" CELLSPACING=0 CELLPADDING=0 WIDTH="100%">
      <TR>
        <TD COLSPAN="<?php print $categories_per_row;?>" WIDTH="100%" CLASS="cellHeading" style="text-align: left">
          &nbsp;
        </TD>
      </TR>



      <?php
      $count_cat=0;
      
      if (isset($gevent1D))
      {
        
        foreach($gevent1D as $eventCategory => $val)
        {

          if ($count_cat%$categories_per_row==0)
            echo "\n<TR>";

          $count_cat++;
          ?>
          <TD WIDTH="<?php print round(100/($categories_per_row),2)?>%" CLASS="cellBG" style="vertical-align: top; text-align: left ;border-width: 0px 0px 0px 0px; padding: 0px 0px 0px 0px">
          <?php drawStats1D($eventCategory);?>
          </TD>
          <?php

          if ($count_cat%$categories_per_row==0)
            echo "</TR>\n";

        }
      }

      while ($count_cat%$categories_per_row!=0)
      {
        if ($count_cat%$categories_per_row==0)
          echo "\n<TR>";

        $count_cat++;

        ?>
        <TD WIDTH="<?php print 100/($categories_per_row)?>%" CLASS="cellSubHeading" style="vertical-align: top; text-align: left ;border-width: 0px 0px 0px 0px; padding: 0px 0px 0px 0px">
        <?php drawStats1D(false);?>
        </TD>
        <?php

        if ($count_cat%$categories_per_row==0)
          echo "</TR>\n";

      }


      ?>

      <TR>
        <TD COLSPAN="<?php print $categories_per_row;?>" WIDTH="100%" CLASS="cellSubHeading" style="vertical-align: top; text-align: left; padding: 0 ; border-width: 0">
        <?php drawWeaponStats();?>
        </TD>
      </TR>


      </table>
      <BR>

  <?php




} 

//*************************************************************************
function getStats1D()
{
  global $db;
  global $gmatrix1D,$gevent1D;
  //change: excludes skill category
  $sql = "select eventCategory, eventName, sum(eventValue)
            from {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total
            where playerID={$GLOBALS['qplayerID']}
                and eventCategory!='skill'
            group by playerID,eventCategory,eventName
         ";
  //endchange

  //echo $sql;
  $rs = $db->Execute($sql);
  
  if ($rs && !$rs->EOF)
  {
    
    do
    {
      $gevent1D[$rs->fields[0]][$rs->fields[1]] = 1;

      if (!isset($gmatrix1D[$rs->fields[0]][$rs->fields[1]]))
        $gmatrix1D[$rs->fields[0]][$rs->fields[1]]=0;
      $gmatrix1D[$rs->fields[0]][$rs->fields[1]]+=$rs->fields[2];

    }while($rs->MoveNext());
  }

  if ($gevent1D)
  {
    ksort($gevent1D);
    foreach ($gevent1D as $cat=>$event)
      ksort($gevent1D[$cat]);
  }
  if ($gmatrix1D)
  {
    foreach($gmatrix1D as $eventCategoryi=>$eventCategoryi_val)
      ksort($gmatrix1D[$eventCategoryi]);
  }

  //htmlprint_r($gmatrix1D);
}
//*************************************************************************
function getStats()
{

  global $db;
  global $gevent1D,$gmatrix1D;
  global $gmatrix,$gmatrixtotal,$gweapon,$gplayerName;
  $sql = "select eventCategory, 0, 0, ED2D.playerID, player2ID, eventName, eventValue, playerName,0,0,0
            from {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d_total as ED2D,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile as PP
            where ED2D.playerID={$GLOBALS['qplayerID']}
                  AND ED2D.playerID!=ED2D.player2ID
                  AND ED2D.player2ID=PP.playerID
         ";

  //echo($sql);
  $rs_all[] = $db->Execute($sql);
  
  $sql = "select eventCategory, 0, 0, ED2D.playerID, player2ID, eventName, eventValue, playerName,0,0,0
            from {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d_total as ED2D,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile as PP
            where ED2D.player2ID={$GLOBALS['qplayerID']}
                  AND ED2D.playerID=PP.playerID
         ";

  //echo($sql);
  $rs_all[] = $db->Execute($sql);

  foreach ($rs_all as $rs_count => $rs)
  {
    if ($rs && !$rs->EOF)
    {

      do
      {
        if ($rs->fields[0]=='kill' || $rs->fields[0]=='suicide' || $rs->fields[0]=='teamkill')
        { 
          $gweapon[$rs->fields[5]]=1;


          if ($rs_count==0)
            $gplayerName[$rs->fields[4]] = $rs->fields[7];
          else if ($rs_count==1)
            $gplayerName[$rs->fields[3]] = $rs->fields[7];



          //print(" t1->".$rs->fields[1]." p1->".$rs->fields[3]." t2->".$rs->fields[2]." p2>".$rs->fields[4]."<BR>");
          if (!strcmp($rs->fields[3],$GLOBALS['playerID']) && $rs->fields[0]=='kill')
          { //kill
            //echo "kill<BR>";
            //**********kill matrix





            if (!isset($gmatrixtotal[$rs->fields[4]]['_v_weapon']['kills']))
              $gmatrixtotal[$rs->fields[4]]['_v_weapon']['kills']=0;
            $gmatrixtotal[$rs->fields[4]]['_v_weapon']['kills']+=$rs->fields[6];

            if (!isset($gmatrixtotal['_v_player'][$rs->fields[5]]['kills']))
              $gmatrixtotal['_v_player'][$rs->fields[5]]['kills']=0;
            $gmatrixtotal['_v_player'][$rs->fields[5]]['kills']+=$rs->fields[6];

            if (!isset($gmatrixtotal['_v_player']['_v_weapon']['kills']))
              $gmatrixtotal['_v_player']['_v_weapon']['kills']=0;
            $gmatrixtotal['_v_player']['_v_weapon']['kills']+=$rs->fields[6];
            //**********kill matrix

          }

          if (!strcmp($rs->fields[3],$GLOBALS['playerID']) && $rs->fields[0]=='suicide')
          { //suicide
            //echo "suicide<BR>";





            if (!isset($gmatrixtotal['_v_player'][$rs->fields[5]]['suicides']))
              $gmatrixtotal['_v_player'][$rs->fields[5]]['suicides']=0;
            $gmatrixtotal['_v_player'][$rs->fields[5]]['suicides']+=$rs->fields[6];

            if (!isset($gmatrixtotal['_v_player']['_v_weapon']['suicides']))
              $gmatrixtotal['_v_player']['_v_weapon']['suicides']=0;
            $gmatrixtotal['_v_player']['_v_weapon']['suicides']+=$rs->fields[6];


            //********* death matrix
            if (!isset($gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']))
              $gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']=0;
            $gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']+=$rs->fields[6];


            if (!isset($gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']))
              $gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']=0;
            $gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']+=$rs->fields[6];

            if (!isset($gmatrixtotal['_v_player']['_v_weapon']['deaths']))
              $gmatrixtotal['_v_player']['_v_weapon']['deaths']=0;
            $gmatrixtotal['_v_player']['_v_weapon']['deaths']+=$rs->fields[6];
            //********* death matrix


          }

          if (!strcmp($rs->fields[3],$GLOBALS['playerID']) && $rs->fields[0]=='teamkill')
          { //team kill




            $gevent1D['']['Team Kills'] = 1;
            if (!isset($gmatrix1D['']['Team Kills']))
              $gmatrix1D['']['Team Kills']=0;
            $gmatrix1D['']['Team Kills']+=$rs->fields[6];
          }
          if (!strcmp($rs->fields[4],$GLOBALS['playerID']) && $rs->fields[0]=='teamkill')
          { // team death




            $gevent1D['']['Team Deaths'] = 1;
            if (!isset($gmatrix1D['']['Team Deaths']))
              $gmatrix1D['']['Team Deaths']=0;
            $gmatrix1D['']['Team Deaths']+=$rs->fields[6];


            //********* death matrix
            /* 
            // If this code is enabled, it messes with the easiest prey/worst enemy stuff.
            // ie team kills get shown in the easiest prey column
            if (!isset($gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']))
              $gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']=0;
            $gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']+=$rs->fields[6];
            */

            if (!isset($gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']))
              $gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']=0;
            $gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']+=$rs->fields[6];

            if (!isset($gmatrixtotal['_v_player']['_v_weapon']['deaths']))
              $gmatrixtotal['_v_player']['_v_weapon']['deaths']=0;
            $gmatrixtotal['_v_player']['_v_weapon']['deaths']+=$rs->fields[6];
            //********* death matrix


          }
          //change: added deaths by same player
          if (!strcmp($rs->fields[4],$GLOBALS['playerID']) && $rs->fields[0]=='kill')
          { //death
            //echo "death<BR>";





            //********* death matrix
            if (!isset($gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']))
              $gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']=0;
            $gmatrixtotal[$rs->fields[3]]['_v_weapon']['deaths']+=$rs->fields[6];


            if (!isset($gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']))
              $gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']=0;
            $gmatrixtotal['_v_player'][$rs->fields[5]]['deaths']+=$rs->fields[6];

            if (!isset($gmatrixtotal['_v_player']['_v_weapon']['deaths']))
              $gmatrixtotal['_v_player']['_v_weapon']['deaths']=0;
            $gmatrixtotal['_v_player']['_v_weapon']['deaths']+=$rs->fields[6];
            //********* death matrix

          }



        }
        else if ($rs->fields[0] == 'accuracy')
        {
          if (preg_match("/^(.*)_(.*)/", $rs->fields[5], $ma))
          {
            $weapon=$ma[1];
            $type=$ma[2];


            if (!isset($gweapon[$weapon])) 
             $gweapon[$weapon]=1;         


            if ($type=='hits')
            {
              if (!strcmp($rs->fields[3],$rs->fields[4]))// ie the same
              {
                if (!isset($GLOBALS['g_hitbox']['ALL']))
                  $GLOBALS['g_hitbox']['ALL']=0;
                $GLOBALS['g_hitbox']['ALL']+=$rs->fields[6];

                if (!isset($gmatrixtotal['_v_player'][$weapon]['hits']))
                  $gmatrixtotal['_v_player'][$weapon]['hits']=0;
                $gmatrixtotal['_v_player'][$weapon]['hits']+=$rs->fields[6];


                if (!isset($gmatrixtotal['_v_player']['_v_weapon']['hits']))
                  $gmatrixtotal['_v_player']['_v_weapon']['hits']=0;
                $gmatrixtotal['_v_player']['_v_weapon']['hits']+=$rs->fields[6];


              }
            }
            else if ($type=='shots')
            {
              if (!strcmp($rs->fields[3],$rs->fields[4]))// ie the same
              {
                if (!isset($gmatrixtotal['_v_player'][$weapon]['shots']))
                  $gmatrixtotal['_v_player'][$weapon]['shots']=0;
                $gmatrixtotal['_v_player'][$weapon]['shots']+=$rs->fields[6];


                if (!isset($gmatrixtotal['_v_player']['_v_weapon']['shots']))
                  $gmatrixtotal['_v_player']['_v_weapon']['shots']=0;
                $gmatrixtotal['_v_player']['_v_weapon']['shots']+=$rs->fields[6];

              }
            }
            else if (preg_match("/loc(.+)/",$type,$ma))
            {
              //echo "$type<BR>";
              if (!isset($GLOBALS['g_hitbox'][$ma[1]]))
                $GLOBALS['g_hitbox'][$ma[1]]=0;
              $GLOBALS['g_hitbox'][$ma[1]]+=$rs->fields[6];
            }
          }
        }
        else
        {
          // PvP events. Just add as normal 1D event for now
          $gevent1D[$rs->fields[0]][$rs->fields[5]] = 1;

          if (!isset($gmatrix1D[$rs->fields[0]][$rs->fields[5]]))
            $gmatrix1D[$rs->fields[0]][$rs->fields[5]]=0;
          $gmatrix1D[$rs->fields[0]][$rs->fields[5]]+=$rs->fields[6];
        }




      }while($rs->MoveNext());

    }
  }

  
  
  
  ksort($gweapon);
  
  
  //htmlprint_r($gmatrixtotal);
}
//*************************************************************************
function drawPreyOrEnemyList($what,$limit)
{
  global $db;
  global $gmatrixtotal;
  global $gplayerName;

  //$limit=100;
  if (!strcmp($what,"prey"))
  {
    $eff_sortdir="down";
    $data='kills';
    $table_heading = "Easiest Preys";
  }
  else if (!strcmp($what,"enemy"))
  {
    $eff_sortdir="up";
    $data='deaths';  
    $table_heading = "Worst Enemies";
  }
  else
    return;

  foreach($gmatrixtotal as $player_i=>$val_i)
    $player_list[$player_i]=@$gmatrixtotal[$player_i]['_v_weapon'][$data];

  unset($player_list['_v_player']);
  unset($player_list[$GLOBALS['playerID']]);

  arsort($player_list,SORT_NUMERIC);

  ?>
  <!-- prey/enemy  table begin ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
    <TD WIDTH="100%" COLSPAN="5" CLASS="cellHeading" style="text-align: center "><?php echo $table_heading?> (top <?php echo $limit?>)
    </TD>
    </TR>
  </table>
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%" class="sortable" id="<?php echo $what?>">
    <TR CLASS="cellSubHeading">
    <TD style="text-align: left">Player</TD>
    <TD>Kills</TD>
    <TD>Deaths</TD>
    <TD sortdir="<?php print $eff_sortdir;?>">Eff %</TD>
    </TR>
  <?php

  $count=0;
  foreach ($player_list as $playerID=>$val)
  {
      //change: add player_exclude_list support
      if (in_array($db->qstr($playerID), $GLOBALS['player_exclude_list'])) {
          continue;
      }
      //endchange
    $count++;
    if ($count%2 == 1)
      $cell_class="cell1";
    else
      $cell_class="cell2";

    $kills=0+@$gmatrixtotal[$playerID]['_v_weapon']['kills'];
    $deaths=0+@$gmatrixtotal[$playerID]['_v_weapon']['deaths'];

    ?>
    <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
    <TD style="text-align: left" >
    <A HREF="playerstat.php?playerID=<?php print rawurlencode($playerID)."&amp;config=$GLOBALS[config]";?>">
    <?php print processColors(html_encode($gplayerName[$playerID]),$GLOBALS['settings']['display']['color_names'],$GLOBALS['settings']['display']['max_char_length']);?>
    </A>
    </TD>
    <TD><?php print $kills?></TD>
    <TD><?php print $deaths;?></TD>
    <TD><?php printf("%02.2f",100*$kills/(0.00001+$kills+$deaths));?></TD>
    </TR>
    <?php
    if ($count==$limit)
      break;
  }

  ?>
  </table>
  <!-- prey/enemy  table end ##################################################-->
  <?php

  

}
//*************************************************************************
function drawPlayerAwards()
{
  global $db;

  $sql="select awardID,name,category,image,playerName,{$GLOBALS['cfg']['db']['table_prefix']}awards.playerID 
          from {$GLOBALS['cfg']['db']['table_prefix']}awards,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile
          where {$GLOBALS['cfg']['db']['table_prefix']}awards.playerID={$GLOBALS['qplayerID']}
                AND {$GLOBALS['cfg']['db']['table_prefix']}awards.playerID={$GLOBALS['cfg']['db']['table_prefix']}playerprofile.playerID
          order by category,name
       ";
  $rs=$db->Execute($sql);

  if ($rs && !$rs->EOF)
  {
    ?>
    <!-- allawards table begin ##################################################-->
    <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
      <TD COLSPAN=3 WIDTH="100%"  CLASS="cellHeading" style="text-align: left">Awards List</TD>
    </TR>
    <?php
    do
    {
      ?>
      <TR CLASS="cellSubHeading">
        <TD COLSPAN="3" WIDTH="100%" style="text-align: left"><?php print $rs->fields[2];?></TD>
      </TR>    
      <?php
      $count=0;
      do
      {
        $count++;
        if ($count%2 == 1)
          $cell_class="cell1";
        else
          $cell_class="cell2";
        $cat=$rs->fields[2];



        $award_images[]="";
        unset($award_images);
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[3]}".".gif";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[3]}".".jpg";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[3]}".".png";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/default.gif";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/default.jpg";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/default.png";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/default/default.gif";
        $award_images[] = "../../games/default/awardsets/default/default.gif";

        $no_of_award_images=count($award_images);
        for($i=0;$i<$no_of_award_images;$i++)
        {
          if (is_file($award_images[$i]))
          {
            $award_image=$award_images[$i];
            break;
          }
        }


        /*
        $award_image="../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[3]}";
        //echo $award_image;
        if (!is_file($award_image))
        {
          $award_image="../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/default.gif";
        }
        */
        ?>
        <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
          <TD style="text-align: left" width="100%">
            <A HREF="awardstat.php?awardID=<?php print rawurlencode($rs->fields[0])."&amp;config=$GLOBALS[config]";?>">
            <?php print processColors(html_encode($rs->fields[1]),$GLOBALS['settings']['display']['color_names'],0);?>
            </A>
          </TD>
          <TD style="text-align: center">
            <A HREF="awardstat.php?awardID=<?php print rawurlencode($rs->fields[0])."&amp;config=$GLOBALS[config]";?>">
            <IMG alt="" border=0 name="<?php print "AWARD_".$rs->fields[0];?>" src="<?php print $award_image;?>"></A>
          </TD>
          
        </TR>
        <?php

      }while($rs->MoveNext()  && strcmp($cat,$rs->fields[2])==0);
    
    }while(!$rs->EOF);
    ?>
    </table>
    <!-- allawards table end ##################################################-->
    <BR>
    <?php
  }
  
  
  
}
//*************************************************************************
function drawPlayerAwardsCompact()
{
  global $db;

  $sql="select name,image,playerName,category,{$GLOBALS['cfg']['db']['table_prefix']}awards.playerID,awardID 
          from {$GLOBALS['cfg']['db']['table_prefix']}awards,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile
          where {$GLOBALS['cfg']['db']['table_prefix']}awards.playerID={$GLOBALS['qplayerID']}
                AND {$GLOBALS['cfg']['db']['table_prefix']}awards.playerID={$GLOBALS['cfg']['db']['table_prefix']}playerprofile.playerID
          order by category,name
       ";
  $rs=$db->Execute($sql);
  
  if ($rs && !$rs->EOF)
  {
    ?>
    <!-- awards    table begin ##################################################-->
    <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
      <TD COLSPAN="2" WIDTH="100%"  CLASS="cellHeading" style="padding: 2; text-align: center">Awards List</TD>
    </TR>

    <?php
    do
    {
      ?>
      <TR>
        <TD COLSPAN="2" WIDTH="100%"  CLASS="cellSubHeading" style="text-align: center "><?php print $rs->fields[3];?></TD>
      </TR>    
      <?php
      $count=0;
      $count_per_cat=0;
      do
      {
        if ($count%2 == 0)
          $cell_class="cell1";
        else
          $cell_class="cell2";

        $cat=$rs->fields[3];

        $award_images[]="";
        unset($award_images);
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[1]}".".gif";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[1]}".".jpg";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[1]}".".png";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/default.gif";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/default/default.gif";
        $award_images[] = "../../games/default/awardsets/default/default.gif";
        
        $no_of_award_images=count($award_images);
        for($i=0;$i<$no_of_award_images;$i++)
        {
          if (is_file($award_images[$i]))
          {
            $award_image=$award_images[$i];
            break;
          }
        }

          
        if ($count_per_cat%$GLOBALS['settings']['display']['playerstats_max_awards_per_line']==0)
        {
          if ($count_per_cat>0)
          {
            ?>
            &nbsp;
            </TD>
          </TR>
          <?php
          }
          $count++;
          ?>
          <TR CLASS="<?php print "$cell_class"?>">
            <TD style="text-align: center;">
            <?php
        }
        ?>
        <A HREF="awardstat.php?awardID=<?php print rawurlencode($rs->fields[5])."&amp;config=$GLOBALS[config]";?>"><IMG align="middle" alt="" name="<?php print "AWARD_".$rs->fields[5];?>" BORDER=0 CLASS="tooltip" TITLE="<?php print $rs->fields[0];?>" SRC="<?php print $award_image;?>"></A>
        <?php

        $count_per_cat++;

      }while($rs->MoveNext() && strcmp($cat,$rs->fields[3])==0);

      if ($count_per_cat>0)
      {
        ?>
        &nbsp;
        </TD>
      </TR>
      <?php
      }

    }while(!$rs->EOF);
    
    ?>
    </table>
    <!-- awards    table end ##################################################-->
    <?php
  }
}
//*************************************************************************
function drawPlayerAwardsSimple()
{
  global $db;

  $sql="select name,image,playerName,category,{$GLOBALS['cfg']['db']['table_prefix']}awards.playerID,awardID 
          from {$GLOBALS['cfg']['db']['table_prefix']}awards,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile
          where {$GLOBALS['cfg']['db']['table_prefix']}awards.playerID={$GLOBALS['qplayerID']}
                AND {$GLOBALS['cfg']['db']['table_prefix']}awards.playerID={$GLOBALS['cfg']['db']['table_prefix']}playerprofile.playerID
          order by category,name
       ";
  $rs=$db->Execute($sql);
  
  if ($rs && !$rs->EOF)
  {
    ?>Awards: <?php
    do
    {

        $cat=$rs->fields[3];

        $award_images[]="";
        unset($award_images);
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[1]}".".gif";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[1]}".".jpg";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/{$rs->fields[1]}".".png";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/{$GLOBALS['cfg']['awardset']}/default.gif";
        $award_images[] = "../../games/{$GLOBALS['cfg']['game']['name']}/awardsets/default/default.gif";
        $award_images[] = "../../games/default/awardsets/default/default.gif";
        
        $no_of_award_images=count($award_images);
        for($i=0;$i<$no_of_award_images;$i++)
        {
          if (is_file($award_images[$i]))
          {
            $award_image=$award_images[$i];
            break;
          }
        }

        ?>
        <A HREF="awardstat.php?awardID=<?php print rawurlencode($rs->fields[5])."&amp;config=$GLOBALS[config]";?>"><IMG align="middle" alt="" name="<?php print "AWARD_".$rs->fields[5];?>" BORDER=0 TITLE="<?php print $rs->fields[0]." ($cat)";?>" SRC="<?php print $award_image;?>"></A>
        <?php
    
    }while($rs->MoveNext() && !$rs->EOF);
    
  }
}
//*************************************************************************
function drawPlayerGUIDs()
{
  ?>
  <!-- guid      table begin ##################################################-->
  <form name="guidform" action="index.php?config=<?php print $GLOBALS['config'];?>" method="post">
  <input type='hidden' name='search_by' value='guid'>
  <input type='hidden' name='search_txt' value=''>
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
  <TR>
    <TD COLSPAN=2 WIDTH="100%"  CLASS="cellHeading" style="text-align: center">GUIDs</TD>
  </TR>
  <?php

  global $db;
  
  $sql="SELECT dataValue,dataCount
          from {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total
          where playerID={$GLOBALS['qplayerID']}
                AND dataName='guid'
          group by dataValue
          order by dataValue ASC
       ";
  
  //echo $sql;
  //return;
  $rs = $db->Execute($sql);
  
  
  if ($rs && !$rs->EOF)
  {
    $count=0;
    do
    {
      $count++;
      if ($count%2 == 1)
        $cell_class="cell1";
      else
        $cell_class="cell2";

      if ((strlen($rs->fields[0])>20))
      {
        $guid_display=substr($rs->fields[0],$GLOBALS['settings']['display']['playerstats_guid_start'],$GLOBALS['settings']['display']['playerstats_guid_length']);
      }
      else
      {
        $guid_display=$rs->fields[0];
      }

      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
        <TD style="text-align: left"><A HREF="javascript:searchByGUID('<?php print $rs->fields[0];?>');" TITLE="<?php print $rs->fields[0];?>"><?php print $guid_display?></A></TD>
        <TD><?php print fstr($rs->fields[1]);?></TD>
      </TR>
      <?php
   
    }while($rs->MoveNext());
    
  }
  ?>
  </table>
  </form> 
  <!-- guid      table end ##################################################-->
  <?php

}
//*************************************************************************
function drawPlayerIPs()
{
  ?>
  <!-- ip        table begin ##################################################-->
  <form name="ipform" action="index.php?config=<?php print $GLOBALS['config'];?>" method="post">
  <input type='hidden' name='search_by' value='ip'>
  <input type='hidden' name='search_txt' value=''>
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
  <TR>
    <TD COLSPAN=2 WIDTH="100%"  CLASS="cellHeading" style="text-align: center">IPs</TD>
  </TR>
  
  <?php

  global $db;
  
  $sql="SELECT dataValue,dataCount
          from {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total
          where playerID={$GLOBALS['qplayerID']}
                AND dataName='ip'
          group by dataValue
          order by dataValue ASC
       ";
  
  //echo $sql;
  //return;
  $rs = $db->Execute($sql);
  
  
  if ($rs && !$rs->EOF)
  {
    $count=0;
    do
    {
      $count++;
      if ($count%2 == 1)
        $cell_class="cell1";
      else
        $cell_class="cell2";

      preg_match("/(\d+\\.\d+\\.)\d+\\.\d+/", $rs->fields[0], $ma);
      $ip=$ma[1].'?.?';
      //$ip=$rs->fields[0]
      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
        <TD style="text-align: left"> 
          <A href="javascript:searchByIP('<?php print $ma[1];?>');"><?php print $ip;?></A>
        </TD>
        <TD><?php print fstr($rs->fields[1]);?></TD>
      </TR>
      <?php
   
    }while($rs->MoveNext());
    
  }
  ?>
  
  </table>
  </form> 
  <!-- ip        table end ##################################################-->
  <?php

}
//*************************************************************************
function drawPlayerAliases()
{
  global $db;
  ?>
  <!-- alias     table begin ##################################################-->
  <form name="nameform" action="index.php?config=<?php print $GLOBALS['config'];?>" method="post">
  <input type='hidden' name='search_by' value='name'>
  <input type='hidden' name='search_txt' value=''>
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
  <TR>
    <TD COLSPAN=2 WIDTH="100%"  CLASS="cellHeading" style="text-align: center">Alias List</TD>
  </TR>
  <?php

  
  $sql="SELECT dataValue,dataCount
          from {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total
          where playerID={$GLOBALS['qplayerID']} 
                AND dataName='alias'
          group by dataValue
          order by dataCount DESC,dataValue ASC
       ";
  
  //echo $sql;
  //return;
  $rs = $db->Execute($sql);
  
  if ($rs && !$rs->EOF)
  {
    $count=0;
    do
    {
      $count++;
      if ($count%2 == 1)
        $cell_class="cell1";
      else
        $cell_class="cell2";

      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
        <TD style="text-align: left">
        <A href="javascript:searchByName('<?php print processColors(addslashes($rs->fields[0]),0,0);?>');"<?php print processColors(html_encode($rs->fields[0]),$GLOBALS['settings']['display']['color_names'],$GLOBALS['settings']['display']['max_char_length'],1);?></A></TD>
        <TD><?php print ($rs->fields[1]);?></TD>
      </TR>
      <?php
   
    }while($rs->MoveNext());
    
  }

  ?>
  </table>
  </form>
  <!-- alias     table end ##################################################-->
  <?php

}
//*************************************************************************
function drawPlayerData()
{
  global $player_data;
  global $player_data_0;
  
  $player_data_0['quote'] = $player_data['quote'];
  $player_data_0['playerName'] = $player_data['playerName'];
  $player_data_0['icon'] = $player_data['icon'];
  $player_data_0['role'] = $player_data['role'];
  $player_data_0['team'] = $player_data['team'];
  //change: player flag and country
  $player_data_0['country_code2'] = $player_data['country_code2'];
  $player_data_0['country_name'] = $player_data['country_name'];
  //endchange
  
  unset ($player_data['quote']);
  unset ($player_data['playerName']);
  unset ($player_data['icon']);
  unset ($player_data['role']);
  unset ($player_data['team']);
  //change: unsert player flag and country
  unset ($player_data['country_code2']);
  unset ($player_data['country_name']);
  //endchange
  
  
  if (is_array($player_data))
  {
    ?>
    <!-- playerdatatable begin ##################################################-->
    <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
      <TD COLSPAN="2" CLASS="cellHeading" style="padding: 2; text-align: center">General Stats</TD>
    </TR>

    <?php
      $role_images[]="";
      unset($role_images);

      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/{$GLOBALS['cfg']['roleset']}/$player_data_0[team]/$player_data_0[role].gif";
      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/{$GLOBALS['cfg']['roleset']}/$player_data_0[team]/$player_data_0[role].jpg";
      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/{$GLOBALS['cfg']['roleset']}/$player_data_0[team]/$player_data_0[role].png";
      
      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/{$GLOBALS['cfg']['roleset']}/default/default.gif";
      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/{$GLOBALS['cfg']['roleset']}/default/default.jpg";
      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/{$GLOBALS['cfg']['roleset']}/default/default.png";
      $role_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/rolesets/default/default/default.gif";
      $role_images[]="../../games/default/rolesets/default/default/default.gif";

      $no_of_role_images=count($role_images);
      for($i=0;$i<$no_of_role_images;$i++)
      {
        if (is_file($role_images[$i]))
        {
          $role_image=$role_images[$i];
          break;
        }
      }

    ?>
    <TR CLASS="cellSubHeading">
      <TD COLSPAN="2" style="text-align: center; padding: 0; ">
        <IMG alt="" name="ROLE" src="<?php  print $role_image; ?>">
      </TD>
    </TR>    

    
    <?php


    $count=0;
    $categoryi='';
    foreach($player_data as $data_var => $data_val)
    {
      if (preg_match("/(.*)\\|(.*)/", $data_var, $ma))
      {
        $categoryi_new=$ma[1];
        $data_var=$ma[2];
      }
      else
      {
        $categoryi_new='';
      }

      if (strcmp($categoryi_new,$categoryi))
      {
        ?>
        <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
          <TD colspan=2 CLASS="cellSubheading" style="text-align: left"><?php print fstr($categoryi_new);?></TD>
        </TR>
        <?php
        $categoryi=$categoryi_new;
      }
      
      $count++;
      if ($count%2 == 1)
        $cell_class="cell1";
      else
        $cell_class="cell2";

      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
        <TD style="text-align: left"><?php print fstr($data_var);?></TD>
        <TD><?php print $data_val;?></TD>
      </TR>
      <?php
    }
    ?>

    </table>
    <!-- playerdatatable end ##################################################-->
    <?php
  }


}
//*************************************************************************
function readPlayerData()
{
  global $db,$player_data;
  

  $sql = "SELECT dataName,dataValue
            FROM {$GLOBALS['cfg']['db']['table_prefix']}playerdata
            WHERE {$GLOBALS['cfg']['db']['table_prefix']}playerdata.playerID={$GLOBALS['qplayerID']}
                  AND gameID=0
                  AND dataNo=0
            ORDER BY dataName
         ";

  $rs=$db->Execute($sql);
  
  //if (!$rs || $rs->EOF)
  //  return;
  
  $player_data=$rs->GetAssoc();


  if (!isset($player_data['quote']))
    $player_data['quote']="";


//change: icon
  $player_data['team']="default";
  $player_data['role']="default";
  $player_data['icon']="default";
  
  $sql = "SELECT dataValue
            FROM {$GLOBALS['cfg']['db']['table_prefix']}playerdata_total
            WHERE playerID = {$GLOBALS['qplayerID']}
                AND dataName='icon'
            GROUP BY dataValue
            ORDER BY dataCount DESC
            LIMIT 1
        ";
  $rs = $db->Execute($sql);
  if ($rs->fields[0])
    $player_data['icon'] = $rs->fields[0];
//endchange


  //echo $player_data['team']." => ".$player_data['role']." => ".$player_data['icon'];
  
//change: add additional skills information and country
  $sql = "SELECT playerName,skill,kills,deaths,kill_streak,death_streak,games,max(eventValue),min(eventValue),countryCode,first_seen,last_seen
            FROM {$GLOBALS['cfg']['db']['table_prefix']}playerprofile left join {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d_total e
                on ({$GLOBALS['cfg']['db']['table_prefix']}playerprofile.playerID=e.playerID
                and eventCategory='skill' and eventName in ('min', 'max'))
            where {$GLOBALS['cfg']['db']['table_prefix']}playerprofile.playerID={$GLOBALS['qplayerID']}
            group by e.playerID
         ";
//endchange

  $rs=$db->Execute($sql);
  
  $player_data['playerName']=$rs->fields[0];

  //change: add country and flag
  $player_data['country_code2'] = $rs->fields[9];
  $player_data['country_name'] = 'Unknown Location';
  $qcode = $db->qstr($rs->fields[9]);
  $sql = "SELECT country_name
        FROM {$GLOBALS['cfg']['db']['table_prefix']}ip2country
        WHERE country_code2 = $qcode
        LIMIT 1
        ";
  $rs2 = $db->Execute($sql);
  if ($rs2->fields[0]) {
      $player_data['country_name'] = $rs2->fields[0];
  }
  //endchange
  
  //change: add current and historical rank
  if ( $GLOBALS['cfg']['display']['days_inactivity'] ) {
      // use timestamp from latest game
      $sql = "select max(timeStart) from {$GLOBALS['cfg']['db']['table_prefix']}gameprofile";
      $rs2 = $db->execute( $sql );
      if ( $rs2 && !$rs2->EOF ) {
          $last_update = "'{$rs2->fields[0]}'";
      } else {
          // use timestamp from latest update
          $sql = "select value from {$GLOBALS['cfg']['db']['table_prefix']}gamedata
            where name='last_update_time'";
          $rs2 = $db->execute( $sql );
          if ($rs2 && !$rs2->EOF) {
            $last_update = "'{$rs2->fields[0]}'";
          } else {
              // use current timestamp
            $last_update = 'CURRENT_TIMESTAMP';
          }
      }
      $sql = "SELECT DATEDIFF($last_update, '{$rs->fields[11]}')";
      $rs2 = $db->Execute( $sql );
      if ( $rs2->fields[0] > $GLOBALS['cfg']['display']['days_inactivity'] ) {
          $player_data['rank'] = '- IA -';
      } else {
          $sql = "SELECT COUNT(*) FROM {$GLOBALS['cfg']['db']['table_prefix']}playerprofile WHERE skill > {$rs->fields[1]}
                and DATEDIFF($last_update, last_seen) <= {$GLOBALS['cfg']['display']['days_inactivity']}
                ".($GLOBALS['excluded_players'] ? "and playerID not in {$GLOBALS['excluded_players']}" : "");
          if ($GLOBALS['settings']['display']['playerlist_conditions']) {
              $sql .= " AND ({$GLOBALS['settings']['display']['playerlist_conditions']})";
          }
          $rs2 = $db->Execute($sql);
          $player_data['rank'] = $rs2->fields[0] + 1;
      }
  }

  $sql = "SELECT COUNT(*) FROM {$GLOBALS['cfg']['db']['table_prefix']}playerprofile WHERE skill > {$rs->fields[1]}
        ".($GLOBALS['excluded_players'] ? "and playerID not in {$GLOBALS['excluded_players']}" : "");
  if ($GLOBALS['settings']['display']['playerlist_conditions']) {
      $sql .= " AND ({$GLOBALS['settings']['display']['playerlist_conditions']})";
  }
  $rs2 = $db->Execute($sql);
  $player_data[ $GLOBALS['cfg']['display']['days_inactivity'] ? 'historical_rank' : 'rank'] = $rs2->fields[0] + 1;
  //endchange

  //change: round skills
  $player_data['skill']=floor($rs->fields[1]);
  //endchange
  //change: add additional skills information
  if ($rs->fields[7]) {
    $player_data['max_skill']=floor($rs->fields[7]);
    $player_data['min_skill']=floor($rs->fields[8]);
  }
  //endchange
  $player_data['games']=$rs->fields[6];
  $player_data['kills']=$rs->fields[2];
  $player_data['deaths']=$rs->fields[3];
  $player_data['kill_streak']=$rs->fields[4];
  $player_data['death_streak']=$rs->fields[5];
  
  $player_data['kills:game']=round($player_data['kills']/$player_data['games'],2);
  $player_data['kills:death']=round($player_data['kills']/(1+$player_data['deaths']),2);

  //change: add first seen and last seen fields
  $player_data['first_seen'] = $rs->fields[10];
  $player_data['last_seen'] = $rs->fields[11];
  //endchange
}
//*************************************************************************
function drawProfile()
{
  global $db,$player_data,$player_data_0;

  ?>
  <!-- profile table start ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
    <TD COLSPAN="3" CLASS="cellHeading" style="text-align: right">
    &nbsp;
    </TD>
    </TR>
    <TR CLASS="cell1">
    <?php if (!$GLOBALS['settings']['display']['no_country_flags']) : ?>
    <TD style="text-align: center; vertical-align: top; ">
        <img alt="<?php echo $player_data_0['country_code2']; ?>" src="../../images/flags/<?php echo strtolower($player_data_0['country_code2']); ?>.gif" title="<?php echo html_encode($player_data_0['country_name']); ?>" />
    </TD>
    <?php endif; ?>
    <TD WIDTH="100%" style="vertical-align: top; text-align: left"<?php if ($GLOBALS['settings']['display']['no_country_flags']) echo 'COLSPAN="2"'; ?>>
      <font size="+1"><?php  print processColors(html_encode($player_data_0['playerName']),$GLOBALS['settings']['display']['color_names'],$GLOBALS['settings']['display']['max_char_length_big']) ?></font><BR><BR>
      <?php
      if ($GLOBALS['settings']['display']['quotes']!=0 && strlen($player_data_0['quote'])>0) 
      {
        print "&nbsp;&nbsp;&nbsp;- \" ".processColors(html_encode($player_data_0['quote']),$GLOBALS['settings']['display']['color_names'],$GLOBALS['settings']['display']['max_char_length_big'])." \"";
      }
      ?>
    </TD>
    
    <?php

    $icon_images[]="";
    unset($icon_images);
    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/$player_data_0[team]/$player_data_0[role]/".stripIllegalFilenameChars($player_data_0['icon'],"\\/").".gif";
    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/$player_data_0[team]/$player_data_0[role]/".stripIllegalFilenameChars($player_data_0['icon'],"\\/").".jpg";
    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/$player_data_0[team]/$player_data_0[role]/".stripIllegalFilenameChars($player_data_0['icon'],"\\/").".png";

    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/default/default/default.gif";
    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/default/default/default.jpg";    
    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/default/default/default.png";
    
    $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/default/default/default/default.gif";
    $icon_images[]="../../games/default/iconsets/default/default/default/default.gif";
    
    $no_of_icon_images=count($icon_images);
    for($i=0;$i<$no_of_icon_images;$i++)
    {
      if (is_file($icon_images[$i]))
      {
        $icon_image=$icon_images[$i];
        break;
      }
    }
    //echo $icon_image;
    ?>
    <TD style="text-align: center; vertical-align: bottom; ">
      <img alt="" name="ICON" src="<?php  print $icon_image; ?>">
    </TD>

    </TR>

  </table>
  <!-- profile table end ##################################################-->
  <?php
}
//*************************************************************************
function drawHitbox()
{
    //print_r($GLOBALS['g_hitbox']);
    ksort($GLOBALS['g_hitbox']);
    $hitbox_param="";
    foreach ($GLOBALS['g_hitbox'] as $loc => $val)
      $hitbox_param.="H[$loc]"."="."$val&amp;";
    //echo $hitbox_param;
    ?>
    
    <!-- hitbox    table begin ##################################################-->
    <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 width="100%">

      <TR>
        <TD COLSPAN="2" CLASS="cellHeading" style="text-align: center">Hitbox</TD>
      </TR>

      <?php
      if (extension_loaded('gd'))
      {
        ?>
        <TR CLASS="cellSubHeading">
          <TD style="text-align: center; padding: 0;"><IMG alt="" name="HITBOX" src="./hitbox.php?<?php echo $hitbox_param;?>"></TD>
        </TR>
        <?php
      }
      ?>
    </table>
    <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 width="100%" class="sortable" id="hitbox">  
    <TR CLASS="cellSubHeading">          
      <TD style="text-align: left; ">Location</TD>
      <TD style="text-align: right;" sortdir="down">Hits</TD>
      <TD style="text-align: right;" sortdir="none">%</TD>      
    </TR>

      <?php
      foreach ($GLOBALS['g_hitbox'] as $loc => $val)
      {
        if ($loc=='ALL')
          continue;
        $count++;
        if ($count%2 == 1)
          $cell_class="cell1";
        else
          $cell_class="cell2";
        ?>
        <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
          <TD style="text-align: left;"><?php echo ucfirst($loc);?></TD>
          <TD style="text-align: right;"><?php echo $val;?></TD>
          <TD style="text-align: right;"><?php printf("%02d",(($val/$GLOBALS['g_hitbox']['ALL'])*100));?></TD>
        </TR>
        <?php
      }
      ?>
    </table>
    <!-- hitbox    table end ##################################################-->
    <?php

}
//*************************************************************************
function drawGamesList()
{
  global $start_from;
  $start_from=0;
  global $db;

//change: game list
  $ggame = array();
  $sql = "SELECT p.gameID, g.value
    FROM {$GLOBALS['cfg']['db']['table_prefix']}gamedata g, {$GLOBALS['cfg']['db']['table_prefix']}playerdata p
    WHERE g.gameID = p.gameID
        AND p.playerID = {$GLOBALS['qplayerID']}
        AND g.name = '_v_time_start'
    GROUP BY p.gameID, g.value
    ORDER BY g.value DESC
    LIMIT {$GLOBALS['cfg']['display']['record_limit']}";
  $rs = $db->Execute($sql);
  while ($rs && !$rs->EOF) {
      $ggame[$rs->fields[0]] = $rs->fields[1];
      $rs->MoveNext();
  }
//endchange

  if (count($ggame)==0)
    return;

  ?>
  <!-- gamestats table begin ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
    <TD COLSPAN="2" CLASS="cellHeading" style="text-align: center; padding-left: 20; padding-right: 20">Games List (last <?php echo $GLOBALS['cfg']['display']['record_limit'];?>)</TD>
    </TR>



    <TR CLASS="cellSubHeading">
    <TD COLSPAN=2 style="text-align: center">Time, Map</TD>
    </TR>

  <?php
  
  $count=0;
  foreach($ggame as $gID => $time_start)
  {
    $sql="select value 
            from {$GLOBALS['cfg']['db']['table_prefix']}gamedata
            where gameID=$gID
                  AND name='_v_map'
            ORDER BY name DESC
         ";
    //echo $sql;

    $rs=$db->Execute($sql);
    $map=$rs->fields[0];
    
    $count++;
    if ($count%2 == 1)
      $cell_class="cell1";
    else
      $cell_class="cell2";

    ?>
    <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
    <TD style="border-right-width: 0; "><?php print $count;?></TD>
    <TD style="text-align: left; border-left-width: 0;">
    <A CLASS="tooltip" TITLE="<?php print "$time_start, $map"?>" HREF="gamestat.php?gameID=<?php print $gID."&amp;config=$GLOBALS[config]";?>">
    <?php
      print substr($time_start,5,5).", ".processColors(html_encode($map),0,$GLOBALS['settings']['display']['max_char_length_small']);
    ?>
    </A>
    </TD>
    </TR>
    <?php
    if ($count >= $GLOBALS['cfg']['display']['record_limit'])
      break;

  }

  ?>
  </table>
  <!-- gamestats table end ##################################################-->
  <?php


}
//*************************************************************************
function drawWeaponStats()
{
  include("../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$GLOBALS['cfg']['weaponset']}-weapons.php");
  global $gmatrix,$gmatrixtotal,$gweapon;
  global $db;
  
  ?>
  <!-- weaponstats table begin ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=1 WIDTH="100%" class="sortable" id="weaponstats">
    <TR CLASS="cellSubHeading">
    <TD style="text-align: left;">Weapon</TD>
    <TD WIDTH="10%" sortdir="down">Kills</TD>
    <TD WIDTH="10%">Deaths</TD>
    <TD WIDTH="10%">Suicides</TD>
    <TD WIDTH="10%">Eff %</TD>
    <?php
    if (isset($GLOBALS['g_hitbox']))
    {
      ?>
      <TD WIDTH="10%">Hits</TD>
      <TD WIDTH="10%">Shots</TD>
      <TD WIDTH="10%">Misses</TD>
      <TD WIDTH="10%">Acc %</TD>
      <?php
    }
    ?>
    <TD WIDTH="1%" style="text-align: center;" sortdir="none">&nbsp;</TD>
    </TR>
  <?php
  
  $count=0;
  //htmlprint_r($gmatrixtotal);
  foreach($gweapon as $weapon => $val)
  {
      $count++;
      if ($count%2 == 1)
        $cell_class="cell1";
      else
        $cell_class="cell2";
  
      $kills=0+@$gmatrixtotal['_v_player'][$weapon]['kills'];
      $deaths=0+@$gmatrixtotal['_v_player'][$weapon]['deaths'];
      $suicides=0+@$gmatrixtotal['_v_player'][$weapon]['suicides'];

      $hits=0+@$gmatrixtotal['_v_player'][$weapon]['hits'];
      $shots=0+@$gmatrixtotal['_v_player'][$weapon]['shots'];


      if (isset($GLOBALS['weaponset'][$weapon]['name']))
        $weapon_name=$GLOBALS['weaponset'][$weapon]['name'];
      else
        $weapon_name=fstr($weapon);

      ?>


      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
      
      <?php
      $weapon_images[]="";
      unset($weapon_images);
      if (isset($GLOBALS['weaponset'][$weapon]['image']))
      {
        $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$GLOBALS['weaponset'][$weapon]['image']}";
      }
      else
      {
        //echo $weapon;
        $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$weapon}.gif";
        $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$weapon}.jpg";
        $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$weapon}.png";
      }

      $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/default.gif";
      $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/default.jpg";
      $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/default.png";

      $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/default/default.gif";
      $weapon_images[]="../../games/default/weaponsets/default/default.gif";

      $no_of_weapon_images=count($weapon_images);
      for($i=0;$i<$no_of_weapon_images;$i++)
      {
        //echo $weapon_images[$i];
        if (is_file($weapon_images[$i]))
        {
          $weapon_image=$weapon_images[$i];
          break;
        }
      }
      //echo $weapon_image;
      ?>

      <TD style="text-align: left  "><?php print $weapon_name;?></TD>
      <TD><?php print $kills;?></TD>
      <TD><?php print $deaths;?></TD>
      <TD><?php print $suicides;?></TD>
      <TD><?php printf("%02.2f",100*$kills/(0.00001+$kills+$deaths));?></TD>
      <?php
      if (isset($GLOBALS['g_hitbox']))
      {
        ?>
        <TD><?php print $hits;?></TD>
        <TD><?php print $shots;?></TD>
        <TD><?php print $shots-$hits;?></TD>
        <TD><?php printf("%02.2f",100*$hits/(0.00001+$shots));?></TD>
        <?php
      }
      ?>
      <TD style="text-align: center"><IMG align="middle" alt="" src="<?php  print $weapon_image; ?>"></TD>
      </TR>
      <?php

  }
  

  $kills=0+@$gmatrixtotal['_v_player']['_v_weapon']['kills'];
  $deaths=0+@$gmatrixtotal['_v_player']['_v_weapon']['deaths'];
  $suicides=0+@$gmatrixtotal['_v_player']['_v_weapon']['suicides'];

  $hits=0+@$gmatrixtotal['_v_player']['_v_weapon']['hits'];
  $shots=0+@$gmatrixtotal['_v_player']['_v_weapon']['shots'];

  ?>



  <TR CLASS="cellSubHeading" sortbottom="1">
  <TD style="text-align: left;">Total</TD>
  <TD><?php print $kills;?></TD>
  <TD><?php print $deaths;?></TD>
  <TD><?php print $suicides;?></TD>
  <TD><?php printf("%02.2f",100*$kills/(0.00001+$kills+$deaths));?></TD>
  <?php
  if (isset($GLOBALS['g_hitbox']))
  {
    ?>
    <TD><?php print $hits;?></TD>
    <TD><?php print $shots;?></TD>
    <TD><?php print $shots-$hits;?></TD>
    <TD><?php printf("%02.2f",100*$hits/(0.00001+$shots));?></TD>
    <?php
  }
  ?>
  <TD style="text-align: center;">&nbsp;</TD>
  </TR>


  </table>
  <!-- weaponstats table end ##################################################-->
  <?php
  
  
  
}
//*************************************************************************


?>

<!-- layout table begin ##################################################-->
<table style="border-width: 2; border-spacing: 0; padding: 0 0 0 0; margin: 0 0 0 0;" CELLSPACING="0" CELLPADDING="0" WIDTH="100%">

<TR>
  <TD style="vertical-align: top; padding: 0;" COLSPAN=3 CLASS="cellBG">
    <?php  drawMainHeading(); ?>
  </TD>
</TR>

<TR>
  <TD COLSPAN=3 style="border-width: 0; padding: 0; ">
    <?php  drawMenu(); ?>
  </TD>
</TR>

<TR>
  <TD style="vertical-align: top; padding: 10px 10px 10px 10px; border-width: 0 0 0 0;" COLSPAN=3 CLASS="cellBG">
    <?php
    drawHeadBar();
    ?>
  </TD>
</TR>


<TR>
      <td CLASS="cellBG" style="vertical-align: top; padding: 0px 10px 10px 10px; border-width: 0 0 0 0;">
          <?php drawPlayerData();?><BR>
          <?php //drawPlayerAwardsCompact();?><!--<BR>-->
          <?php drawPlayerAliases();?><BR>
          <?php drawPlayerGUIDs();?><BR>
          <?php drawPlayerIPs();?><BR>
      </td>

      <td CLASS="cellBG" style="vertical-align: top; padding: 0px 0 10px 0; border-width: 0 0 0 0;" WIDTH="100%" >
        <?php
          //echo $GLOBALS['playerID'];
          drawProfile();?><BR><?php
          drawPlayerAwards();
          drawPlayerStats();    
          
        ?>
        
        <!--#######################-->
        <table style="padding: 0 0 0 0; border-width: 0 0 0 0;" CELLSPACING="0" CELLPADDING="0" WIDTH="100%">
        <tr>
          <td width="50%" style="vertical-align: top; border-width: 0 0 0 0; padding: 0 5px 0 0;">
            <?php
            drawPreyOrEnemyList("prey",25);
            ?>
          </td>
          <td width="50%" style="vertical-align: top; border-width: 0 0 0 0; padding: 0 0 0px 5px;">
            <?php
            drawPreyOrEnemyList("enemy",25);
            ?>
          </td>
        </tr>
        </table>
        <!--#######################-->
      </td>


      <td CLASS="cellBG" style="vertical-align: top; padding: 0px 10px 10px 10px; border-width: 0 0 0 0;">

      <?php
        if (isset($GLOBALS['g_hitbox']) && count($GLOBALS['g_hitbox'])>1)
        {
           drawHitbox();
           ?><BR><?php
        }
      ?>
        
      <?php
        if ($GLOBALS['settings']['display']['gamestats']) 
          drawGamesList();
      ?>
      
      </td>



</TR>

<TR>
  <TD COLSPAN=3 style="vertical-align: top; padding: 0 0 0 0; border-width: 0 0 0 0;" CLASS="cellBG">
    <?php
    drawCredits();
    ?>
  </TD>
</TR>

</table>
<!-- layout table end ##################################################-->
<?php

if ($settings['display']['javascript_tooltips'])
{
  ?>
  <script type="text/javascript">domTT_replaceTitles();</script>
  <?php
}
ob_end_flush(); // flush after compactHTML
echo "<center>page loaded in ".timeElapsed($start_time)."s (".$pre_time."s)</center>";
?>
</BODY>
</HTML>