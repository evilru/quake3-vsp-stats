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

$categories_per_row=3;


setSkin();


setupVars();

//change: add player_exclude_list support
load_player_exclude_list();
//endchange

// if gameID is set but not a number, then get rid of it
if( isset( $_GET['gameID'] ) && !ctype_digit( $_GET['gameID'] ) ) {
	unset( $_GET['gameID'] );
}
$gameID=@$_GET['gameID'];

if ($gameID)
{
  
  getStats();
  getStats1D();
  getStatsGame();
  
  
  // To make it easy for making team classes for style sheets, assign a counter type index to each unique team and use that for naming the classes
  // I.E. instead of having teamRED teamBLUE classes in css. Designing a css for that would require knowledge of the name of the team
  // Instead use Team1 , Team2, Team3 etc
  $i=0;
  ksort($gteam);
  foreach($gteam as $teamID_key => $val) 
  {
    $i++;
    $gteam_index[$teamID_key]=$i;
  }
  //htmlprint_r($gteam_index);
  
  
  getPlayerNames();


}

include_once("../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg']['weaponset']}/{$GLOBALS['cfg']['weaponset']}-weapons.php");


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<TITLE>vsp (game stats)</TITLE>
<LINK REL=stylesheet HREF="<?php print $GLOBALS['stylesheet']; ?>" TYPE="text/css">
<script language="javascript" type="text/javascript" src="../../lib/sorttable/sorttable.js"></script>
<script language="JavaScript" type="text/javascript">

var oldKillChartTable="KillChartTable_v_weapon";
function displayKillChart(chart)
{
  // This feature doesn't seem to work in IE and i don't want to write a work around for a non-standard browser like IE. 
  // Use Firefox or Mozilla Suite :)
  var newKCT;
  var oldKCT;
  newKCT = document.getElementById(chart);
  oldKCT = document.getElementById(oldKillChartTable);
  oldKCT.setAttribute("style","display:none;");
  newKCT.setAttribute("style","display:table; border-width: 0");
  oldKillChartTable=chart;
  return;
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
function drawHeadBar()
{
  global $sort,$config;
  ?>
  <!-- navbar begin          ################################################-->
  <table CLASS="cellHeading" CELLSPACING="0" CELLPADDING="1" WIDTH="100%" style="border-width: 0;">
    <TR>
    <TD HEIGHT="25" CLASS="cellHeading" style="border-right-width: 0; text-align: left;" >
      <B>Game Stats</B>
    </TD>
    </TR>
  </table>
  <!-- navbar end            ################################################-->
  <?php
}
//*************************************************************************
function drawKillChart()
{
  global $gmatrix,$gmatrixtotal,$gteam,$gweapon,$gplayerName,$gteam_index;


  //htmlprint_r($gteam);
  
  if (count($GLOBALS['gplayerName'])>10)
    $max_char_length=$GLOBALS['settings']['display']['max_char_length_small'];
  else
    $max_char_length=$GLOBALS['settings']['display']['max_char_length'];
  
  $gteam1=$gteam;
  $gteam2=array_reverse($gteam, true);
  $no_of_teams= count($gteam);
  
  foreach($gteam1 as $team1 => $team1_val)
  {
    foreach($gteam2 as $team2 => $team2_val)
    {
      if ($no_of_teams > 1 && $team1==$team2)
        break;



      ?>
      <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">

      <tr>
      <td class="cellHeading" style="text-align: left; border-right: 0;">
      Kill Chart
      </td>
      
      <td class="cellHeading" style="border-left: 0;">
      <form style="margin: 0px" action="">
      <select class="cellSubHeading" style="text-align: left; width: 200px" name="KillChartSelect"  OnChange="displayKillChart(form.KillChartSelect.options[form.KillChartSelect.selectedIndex].value);">
      <option value="KillChartTable_v_weapon" selected="selected">
      All weapons
      </option>

      <?php
      //------------------------------------------------------
      if ($GLOBALS['settings']['display']['gamestats_killchart_per_weapon'])
      {
        foreach($gweapon as $weapon => $val)
        {
          print "<option value=\"KillChartTable$weapon\"";
          print ">".fstr($weapon)."</option>";
        }
      }
      //------------------------------------------------------
      ?>

      </select>
      </form>
      </td>
      
      </tr>
      </table>
      <?php



      
      $no_of_players_team2=count($gmatrix[$team2]);
      $cell_width=round(100/(2+$no_of_players_team2),2);

      //------------------------------------------------------
      if ($GLOBALS['settings']['display']['gamestats_killchart_per_weapon'])
      {
        foreach($gweapon as $weapon => $val)
        {

          ?>
          <table ID="<?php echo "KillChartTable".$weapon;?>" style="display: none; border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">

          <TR class="cellSubHeading">
          <td class="cellSubHeading" style="text-align: right" width="<?php print $cell_width;?>%">
          Players
          </td>
          <?php

          foreach($gmatrix[$team2] as $team2player => $team2player_val)
          {
            echo "<td colspan=2 class=\"Team".$gteam_index[$team2]."\" style=\"text-align: center \" width=\"".$cell_width. "%\">".processColors(html_encode($gplayerName[$team2player]),$GLOBALS['settings']['display']['color_names'],$max_char_length)."</td>";
          }
          echo "<td colspan=2 class=\"Team".$gteam_index[$team2]."\" style=\"text-align: center \" width=\"".$cell_width. "%\">Total</td>";
          echo "</tr>\n";

          $count=0;
          foreach($gmatrix[$team1] as $team1player => $team1player_val)
          {
            if ($count%2 == 0)
              $cell_class="cell1";
            else
              $cell_class="cell2";
            $count++;

            ?>
            <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
            <?php
            echo "<td class=\"Team".$gteam_index[$team1]."\"style=\"text-align: right \" >".processColors(html_encode($gplayerName[$team1player]),$GLOBALS['settings']['display']['color_names'],$max_char_length)."</td>";
            foreach($gmatrix[$team2] as $team2player => $team2player_val)
            {
              echo "<td style=\"text-align: right \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrix[$team1][$team1player][$team2player][$weapon]['kills'])."</td>";
              echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrix[$team2][$team2player][$team1player][$weapon]['kills'])."</td>";
            }

            echo "<td style=\"text-align: right \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrixtotal[$team1][$team1player]['_v_player'][$weapon]['kills'])."</td>";
            echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrixtotal[$team2]['_v_player'][$team1player][$weapon]['kills'])."</td>";
            echo "</tr>\n";
          }



          //*********Total Last Row Begin
          $count++;
          if ($count%2 == 0)
            $cell_class="cell2";
          else
            $cell_class="cell1";
          ?>
          <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
          <?php
          echo "<td class=\"Team".$gteam_index[$team1]."\"style=\"text-align: right \" >Total</td>";
          foreach($gmatrix[$team2] as $team2player => $team2player_val)
          {
            echo "<td style=\"text-align: right \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrixtotal[$team1]['_v_player'][$team2player][$weapon]['kills'])."</td>";
            echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrixtotal[$team2][$team2player]['_v_player'][$weapon]['kills'])."</td>";
          }

          echo "<td style=\"text-align: right \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrixtotal[$team1]['_v_player']['_v_player'][$weapon]['kills'])."</td>";
          echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\">".(0+@$gmatrixtotal[$team2]['_v_player']['_v_player'][$weapon]['kills'])."</td>";
          echo "</tr>\n";
          //*********Total Last Row End

          echo "</table>\n";

        }
      }      
      //------------------------------------------------------
      
      
      
      
      ?>
      <table ID="KillChartTable_v_weapon" style="display: inline-table; border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">

      <TR class="cellSubHeading">
      <td width="<?php print $cell_width;?>%">
      Players
      </td>
      <?php

      foreach($gmatrix[$team2] as $team2player => $team2player_val)
      {
          echo "<td colspan=2 class=\"Team".$gteam_index[$team2]."\" style=\"text-align: center \" width=\"".$cell_width. "%\">"."<A HREF=\"##\"" . processColors(html_encode($gplayerName[$team2player]),$GLOBALS['settings']['display']['color_names'],$max_char_length,1)."</A></td>";
      }
      echo "<td colspan=2 class=\"Team".$gteam_index[$team2]."\" style=\"text-align: center \" width=\"".$cell_width. "%\">Total</td>";
      echo "</tr>\n";

      $count=0;
      foreach($gmatrix[$team1] as $team1player => $team1player_val)
      {
        if ($count%2 == 0)
          $cell_class="cell1";
        else
          $cell_class="cell2";
        $count++;

        ?>
        <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
        <?php
        echo "<td class=\"Team".$gteam_index[$team1]."\">"."<A HREF=\"##\"".processColors(html_encode($gplayerName[$team1player]),$GLOBALS['settings']['display']['color_names'],$max_char_length,1)."</A></td>";
        foreach($gmatrix[$team2] as $team2player => $team2player_val)
        {
          echo "<td width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team1][$team1player][$team2player]['_v_weapon']['kills'])."</td>";
          echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team2][$team2player][$team1player]['_v_weapon']['kills'])."</td>";
        }

        echo "<td width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team1][$team1player]['_v_player']['_v_weapon']['kills'])."</td>";
        echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team2]['_v_player'][$team1player]['_v_weapon']['kills'])."</td>";
        echo "</tr>\n";
      }



      //*********Total Last Row Begin
      $count++;
      if ($count%2 == 0)
        $cell_class="cell2";
      else
        $cell_class="cell1";
      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
      <?php
      echo "<td class=\"Team".$gteam_index[$team1]."\">Total</td>";
      foreach($gmatrix[$team2] as $team2player => $team2player_val)
      {
          echo "<td width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team1]['_v_player'][$team2player]['_v_weapon']['kills'])."</td>";
          echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team2][$team2player]['_v_player']['_v_weapon']['kills'])."</td>";
      }



      echo "<td width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team1]['_v_player']['_v_player']['_v_weapon']['kills'])."</td>";
      echo "<td style=\"text-align:  left \" width=\"".round($cell_width/2,2)."%\" >".(0+@$gmatrixtotal[$team2]['_v_player']['_v_player']['_v_weapon']['kills'])."</td>";
      echo "</tr>\n";
      //*********Total Last Row End

      echo "</table>\n";

    
    }
  }


  
  //htmlprint_r($no_of_players);
  
  
}

//*************************************************************************
function getPlayerNames()
{
  global $db;
  global $gplayerName;
  foreach($gplayerName as $playerIDi => $playerName )
  {
      //change: get playername of game
      $qplayerIDi = $db->qstr($playerIDi);
      $sql = "select dataValue
            from {$GLOBALS['cfg']['db']['table_prefix']}playerdata
            where gameId = {$GLOBALS['gameID']}
            and playerId = {$qplayerIDi}
            and dataName = 'alias'
            order by dataNo desc";
      $rs=$db->SelectLimit($sql,1,0);
      if ($rs && !$rs->EOF) {
          $gplayerName[$playerIDi]=$rs->fields[0];
      } else if ($playerName === false) {
          //echo $playerIDi;
          $sql = "select playerName
                    from {$GLOBALS['cfg']['db']['table_prefix']}playerprofile
                    where playerID=$qplayerIDi
                 ";

          //echo $sql;
          $rs = $db->Execute($sql);
          $gplayerName[$playerIDi]=$rs->fields[0];
        }
        //endchange
  }

}
//*************************************************************************
function drawPlayerStats()
{
  global $gicon;
  global $categories_per_row;
  global $db;
  global $gmatrix1D,$gevent1D;
  global $gmatrix,$gmatrixtotal,$gteam,$gweapon,$gplayerName,$gteam_index;

  $no_of_teams= count($gteam);

  ?>

  <table WIDTH="100%" style="border-width: 0; padding: 0" CELLSPACING=0 CELLPADDING=0>
  <TR>
  <?php

  foreach($gteam as $teamID => $teamID_val)
  {
    
    ?>
    <TD WIDTH="<?php print round(100/($no_of_teams),2);?>%" style="vertical-align: top; padding: 5px 5px 5px 5px; border-width: 0px 0px 0px 0px; " CLASS="cellBG">
    <?php
    
    
    drawTeamOverall($teamID);
    ?><BR><BR><?php
    
    foreach($teamID_val as $playerID => $val)
    {



      //------------------------------------------------
      $player_data['role']="default";
      $player_data['icon']="default";
      $icon_count_max=0;

      if (isset($gicon[$teamID][$playerID]))
      {
        foreach ($gicon[$teamID][$playerID] as $rolei => $rolei_val)
        {
          foreach ($rolei_val as $iconi => $iconi_val)
          {
            if ($iconi_val>$icon_count_max)
            {
              $icon_count_max=$iconi_val;
              $player_data['icon']=$iconi;
              $player_data['role']=$rolei;

            }
          }
        }
      }

      if (strlen($player_data['role'])<1)
        $player_data['role']='default';
      if (strlen($player_data['icon'])<1)
        $player_data['icon']='default';
      //------------------------------------------------

      $icon_images[]="";
      unset($icon_images);
      $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/$teamID/{$player_data['role']}/".stripIllegalFilenameChars($player_data['icon'],"\\/").".gif";
      $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/$teamID/{$player_data['role']}/".stripIllegalFilenameChars($player_data['icon'],"\\/").".jpg";
      $icon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/iconsets/{$GLOBALS['cfg']['iconset']}/$teamID/{$player_data['role']}/".stripIllegalFilenameChars($player_data['icon'],"\\/").".png";
      
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
//change: added player_exclude_list support
      ?>
      <table style="border-width: 0; padding: 0px 0px 0px 0px" CELLSPACING=0 CELLPADDING=0 WIDTH="100%">
      <TR>
        <TD COLSPAN="<?php print $categories_per_row;?>" WIDTH="100%" CLASS="<?php print "team{$gteam_index[$teamID]}";?>" style="font-size: 180%; text-align: left">
        <img alt="" align="right" height="25" name="ICON-<?php print fstr($teamID.$playerID);?>" src="<?php  print $icon_image; ?>">
          <A NAME="stat-<?php print fstr($teamID.'-'.$playerID);?>" HREF="<?php print in_array($db->qstr($playerID), $GLOBALS['player_exclude_list']) ? "#" : "playerstat.php?playerID=".rawurlencode($playerID)."&amp;config=$GLOBALS[config]";?>">
          <?php print processColors(html_encode($gplayerName[$playerID]),$GLOBALS['settings']['display']['color_names'],$GLOBALS['settings']['display']['max_char_length']);?>
          </A>
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
          <?php drawStats1D($playerID,$teamID,$eventCategory);?>
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
        <TD WIDTH="<?php print 100/($categories_per_row)?>%" CLASS="cellSubHeading" style="vertical-align: top; text-align: left ;border-width: 0; padding: 0px 0px 0px 0px">
        <?php drawStats1D("","","");?>
        </TD>
        <?php

        if ($count_cat%$categories_per_row==0)
          echo "</TR>\n";

      }


      ?>
      <TR>
        <TD COLSPAN="<?php print $categories_per_row;?>" WIDTH="100%" CLASS="cellSubHeading" style="vertical-align: top; text-align: left; padding: 0; ">
        <?php drawWeaponStats($playerID,$teamID);?>
        </TD>
      </TR>

      </table>
      <BR>
      <?php

    }
    
    
    ?>
    </TD>
    <?php
    
  }



  ?>
  </TR>
  </table>
  <?php




} 

//*************************************************************************
function getStatsGame()
{
  global $db;
  global $ggame;
  $sql = "select name, value  
            from {$GLOBALS['cfg']['db']['table_prefix']}gamedata
            where gameID=$GLOBALS[gameID]
         ";

  //echo $sql;
  $rs = $db->Execute($sql);
  
  
  
  if (!$rs || $rs->EOF)
    return;

  $ggame=$rs->GetAssoc();
}
//*************************************************************************
function drawStatsGame()
{
  global $ggame;
  ?>
  <!-- game settings begin ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=0 WIDTH="100%" >
  <TR>
    <TD COLSPAN="3" WIDTH="100%"  CLASS="cellHeading" style="text-align: left">&nbsp</TD>
  </TR>

    <TR>
    <TD CLASS="cellSubHeading" style="padding: 1px 5px 1px 5px; text-align: right">Game Start Time</TD>
    <TD WIDTH="100%" CLASS="cell1" style="padding: 0px 5px 0px 5px; text-align: left"><?php  print $ggame['_v_time_start'];?></TD>

    <?php
      $map_images[]="";
      unset($map_images);
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/{$GLOBALS['cfg']['mapset']}/{$ggame['_v_map']}.gif";
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/{$GLOBALS['cfg']['mapset']}/{$ggame['_v_map']}.jpg";
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/{$GLOBALS['cfg']['mapset']}/{$ggame['_v_map']}.png";
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/{$GLOBALS['cfg']['mapset']}/default.gif";
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/{$GLOBALS['cfg']['mapset']}/default.jpg";
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/{$GLOBALS['cfg']['mapset']}/default.png";
      $map_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/mapsets/default/default.gif";
      $map_images[]="../../games/default/mapsets/default/default.gif";

      $no_of_map_images=count($map_images);
      for($i=0;$i<$no_of_map_images;$i++)
      {
        if (is_file($map_images[$i]))
        {
          $map_image=$map_images[$i];
          break;
        }
      }


    ?>
    <TD ROWSPAN="6" CLASS="cellSubHeading" style="text-align: left; padding: 0">
    <IMG alt="" name="MAP" src="<?php  print $map_image ?>">
    </TD>

    </TR>
    
    <TR>
    <TD CLASS="cellSubHeading" style="padding: 0px 5px 0px 5px; text-align: right">Map</TD>
    <TD WIDTH="100%" CLASS="cell2" style="padding: 0px 5px 0px 5px; text-align: left"><?php  print $ggame['_v_map'];?></TD>
    </TR>

    <TR>
    <TD CLASS="cellSubHeading" style="padding: 0px 5px 0px 5px; text-align: right">Game</TD>
    <TD WIDTH="100%" CLASS="cell1" style="padding: 0px 5px 0px 5px; text-align: left"><?php  print $ggame['_v_game'];?></TD>
    </TR>
    
    <TR>
    <TD CLASS="cellSubHeading" style="padding: 0px 5px 0px 5px; text-align: right">Mod</TD>
    <TD WIDTH="100%" CLASS="cell2" style="padding: 0px 5px 0px 5px; text-align: left"><?php  print $ggame['_v_mod'];?></TD>
    </TR>
    
    <TR>
    <TD CLASS="cellSubHeading" style="padding: 0px 5px 0px 5px; text-align: right">Game Type</TD>
    <TD WIDTH="100%" CLASS="cell1" style="padding: 0px 5px 0px 5px; text-align: left"><?php  print $ggame['_v_game_type'];?></TD>
    </TR>

    <TR>
    <TD CLASS="cellSubHeading" style="padding: 0px 5px 0px 5px; text-align: right">Settings</TD>

    <TD WIDTH="100%" CLASS="cell2" style="padding: 0px 5px 0px 5px; text-align: left">
    <select class="cellSubHeading" style="text-align: left; width: 400px" name="GameSettings">
    <?php
    foreach ($ggame as $var => $val)
    {
      if (!strstr("$var","_v_"))
        print "<option>$var: $val</option>";
    }
    ?>
    </select>
    </TD>

    </TR>


  </table>
  <!-- game settings end   ##################################################-->
  <?php


}
//*************************************************************************
function drawStats1D($playerID,$teamID,$eventCategory)
{
  
  global $db;
  global $gmatrix1D,$gevent1D;
  
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
  <TR>
    <?php
      if ($playerID!='_v_player' && $eventCategory=="team")// special case 
      {
        ?><TD COLSPAN="2" WIDTH="100%"  CLASS="cellSubHeading" style="text-align: left">&nbsp;</TD><?php
      }
      else
      {
        ?><TD COLSPAN="2" WIDTH="100%"  CLASS="cellSubHeading" style="text-align: left"><?php print fstr($eventCategory);?>&nbsp;</TD><?php
      }
    ?>
  </TR>
  <?php
  $count=0;
  if (isset($gmatrix1D[$teamID][$playerID][$eventCategory]))
  {
    //htmlprint_r($gmatrix1D[$teamID][$playerID]);
    foreach($gmatrix1D[$teamID][$playerID][$eventCategory] as $eventName => $eventValue)
    {
      
      
      
      if ($count%2 == 0)
        $cell_class="cell1";
      else
        $cell_class="cell2";
      $count++;

      //-------- special case
      if ($playerID=='_v_player' && $eventCategory=="team") // special case
      {
        $eventValue=$eventValue / (count($gmatrix1D[$teamID])-1);
      }
      else if ($eventCategory=="team") // special case
      {
        ?>
        <TR CLASS="<?php print "$cell_class"?>">
        <TD>&nbsp;</TD>
        <TD>&nbsp;</TD>
        </TR>
        <?php
        continue;
      }
      //-------- special case
      
      
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
function drawWeaponStats($playerID,$teamID)
{
  
  global $gmatrix,$gmatrixtotal,$gteam,$gweapon,$gplayerName,$gteam_index;
  
  ?>
  <!-- weaponstats table begin ##################################################-->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=1 WIDTH="100%">
    <TR CLASS="cellSubHeading">
    <TD style="text-align: left;">Weapon</TD>
    <TD WIDTH="10%">Kills</TD>
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
    <TD width="1%" style="text-align: center;">&nbsp;</TD>
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
  
      $kills=0+@$gmatrixtotal[$teamID][$playerID]['_v_player'][$weapon]['kills'];
      $deaths=0+@$gmatrixtotal[$teamID][$playerID]['_v_player'][$weapon]['deaths'];

      //----------
      // special case if playerid='_v_player'
      if ($playerID=='_v_player')
        $suicides=0+@$gmatrixtotal[$teamID][$playerID][$playerID][$weapon]['suicides'];
      else
        $suicides=0+@$gmatrix[$teamID][$playerID][$playerID][$weapon]['deaths'];
      //----------
      
      $hits=0+@$gmatrixtotal[$teamID][$playerID]['_v_player'][$weapon]['hits'];
      $shots=0+@$gmatrixtotal[$teamID][$playerID]['_v_player'][$weapon]['shots'];

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
        $weapon_images[]="../../games/{$GLOBALS['cfg']['game']['name']}/weaponsets/{$GLOBALS['cfg'][weaponset]}/{$GLOBALS['weaponset'][$weapon]['image']}";
      }
      else
      {
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
      <TD width="1%" style="text-align: center">
      <IMG alt="" src="<?php  print $weapon_image; ?>">
      </TD>
      </TR>
      <?php

  }
  

  $kills=0+@$gmatrixtotal[$teamID][$playerID]['_v_player']['_v_weapon']['kills'];
  $deaths=0+@$gmatrixtotal[$teamID][$playerID]['_v_player']['_v_weapon']['deaths'];
  
  //----------
  // special case if playerid='_v_player'
  if ($playerID=='_v_player')
    $suicides=0+@$gmatrixtotal[$teamID][$playerID][$playerID]['_v_weapon']['suicides'];
  else
    $suicides=0+@$gmatrixtotal[$teamID][$playerID][$playerID]['_v_weapon']['deaths'];
  //----------
  
  $hits=0+@$gmatrixtotal[$teamID][$playerID]['_v_player']['_v_weapon']['hits'];
  $shots=0+@$gmatrixtotal[$teamID][$playerID]['_v_player']['_v_weapon']['shots'];


  ?>


  <TR CLASS="Team<?php print "{$gteam_index[$teamID]}"?>">
  
  <TD style="text-align: left; border-right:0 ">Total</TD>
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
  <TD width="1%" style="text-align: center;">&nbsp;</TD>
  </TR>


  </table>
  <!-- weaponstats table end ##################################################-->
  <?php
  
  
  
}

//*************************************************************************
function getStats1D()
{
  global $db;
  global $gmatrix1D,$gplayerName,$gevent1D;
  global $gteam,$gicon;
  $sql = "select playerName,team, ED1D.playerID, eventCategory, eventName, eventValue,role
            from {$GLOBALS['cfg']['db']['table_prefix']}eventdata1d as ED1D,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile as PP
            where gameID=$GLOBALS[gameID]
                  AND ED1D.playerID=PP.playerID
         ";

  //echo $sql;
  $rs = $db->Execute($sql);
  
  if ($rs && !$rs->EOF)
  {
    
    do
    {
      //change: player name
      $gplayerName[$rs->fields[2]] = $rs->fields[0];
      //endchange
      $gteam[$rs->fields[1]][$rs->fields[2]]=1;
      
      if ($rs->fields[3]=='icon')
      {
        if (!isset($gicon[$rs->fields[1]][$rs->fields[2]][$rs->fields[6]][$rs->fields[4]]))
          $gicon[$rs->fields[1]][$rs->fields[2]][$rs->fields[6]][$rs->fields[4]]=0;
        $gicon[$rs->fields[1]][$rs->fields[2]][$rs->fields[6]][$rs->fields[4]]+=$rs->fields[5];
      }
      else
      {

        $gevent1D[$rs->fields[3]][$rs->fields[4]] = 1;

        if (!isset($gmatrix1D[$rs->fields[1]][$rs->fields[2]][$rs->fields[3]][$rs->fields[4]]))
          $gmatrix1D[$rs->fields[1]][$rs->fields[2]][$rs->fields[3]][$rs->fields[4]]=0;
        $gmatrix1D[$rs->fields[1]][$rs->fields[2]][$rs->fields[3]][$rs->fields[4]]+=$rs->fields[5];


        if (!isset($gmatrix1D[$rs->fields[1]]['_v_player'][$rs->fields[3]][$rs->fields[4]]))
          $gmatrix1D[$rs->fields[1]]['_v_player'][$rs->fields[3]][$rs->fields[4]]=0;
        $gmatrix1D[$rs->fields[1]]['_v_player'][$rs->fields[3]][$rs->fields[4]]+=$rs->fields[5];

      }

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
    foreach($gmatrix1D as $teamIDi=>$teamIDi_val)
      foreach($teamIDi_val as $playerIDi=>$playerIDi_val)
        foreach($playerIDi_val as $eventCategoryi=>$eventCategoryi_val)
          ksort($gmatrix1D[$teamIDi][$playerIDi][$eventCategoryi]);
  }
  //htmlprint_r($gevent1D);
  //htmlprint_r($gmatrix1D);
  //htmlprint_r($gicon);
}
//*************************************************************************
function getStats()
{
  global $db;
  global $gmatrix1D,$gevent1D;
  global $gmatrix,$gmatrixtotal,$gteam,$gweapon,$gplayerName;
  $sql = "select playerName,team, team2, ED2D.playerID, player2ID, eventName, eventValue, eventCategory,role,role2
            from {$GLOBALS['cfg']['db']['table_prefix']}eventdata2d as ED2D,{$GLOBALS['cfg']['db']['table_prefix']}playerprofile as PP
            where gameID=$GLOBALS[gameID]
                  AND ED2D.playerID=PP.playerID
         ";

  //echo $sql;
  $rs = $db->Execute($sql);
  
  if ($rs && !$rs->EOF)
  {
    
    do
    {
        //change: playername
      $gplayerName[$rs->fields[3]] = $rs->fields[0];

      // playerName of playerID2 might not get logged into $gplayerName, if thats the case just set it to null so that you can retrieve it from db later
      if (!isset($gplayerName[$rs->fields[4]]))
        $gplayerName[$rs->fields[4]] = false;
        //endchange

      $gteam[$rs->fields[1]][$rs->fields[3]]=1;

      
      
      if (($rs->fields[7] == 'kill') || ($rs->fields[7] == 'teamkill') || ($rs->fields[7] == 'suicide'))
      { 

        $gteam[$rs->fields[2]][$rs->fields[4]]=1;
        $gweapon[$rs->fields[5]]=1;


        //********** death matrix
        //000
        if (!isset($gmatrix[$rs->fields[2]][$rs->fields[4]][$rs->fields[3]][$rs->fields[5]]['deaths']))
          $gmatrix[$rs->fields[2]][$rs->fields[4]][$rs->fields[3]][$rs->fields[5]]['deaths']=0;
        $gmatrix[$rs->fields[2]][$rs->fields[4]][$rs->fields[3]][$rs->fields[5]]['deaths']+=$rs->fields[6];

        //001
        if (!isset($gmatrixtotal[$rs->fields[2]][$rs->fields[4]][$rs->fields[3]]['_v_weapon']['deaths']))
          $gmatrixtotal[$rs->fields[2]][$rs->fields[4]][$rs->fields[3]]['_v_weapon']['deaths']=0;
        $gmatrixtotal[$rs->fields[2]][$rs->fields[4]][$rs->fields[3]]['_v_weapon']['deaths']+=$rs->fields[6];

        //010
        if (!isset($gmatrixtotal[$rs->fields[2]][$rs->fields[4]]['_v_player'][$rs->fields[5]]['deaths']))
          $gmatrixtotal[$rs->fields[2]][$rs->fields[4]]['_v_player'][$rs->fields[5]]['deaths']=0;
        $gmatrixtotal[$rs->fields[2]][$rs->fields[4]]['_v_player'][$rs->fields[5]]['deaths']+=$rs->fields[6];

        //011
        if (!isset($gmatrixtotal[$rs->fields[2]][$rs->fields[4]]['_v_player']['_v_weapon']['deaths']))
          $gmatrixtotal[$rs->fields[2]][$rs->fields[4]]['_v_player']['_v_weapon']['deaths']=0;
        $gmatrixtotal[$rs->fields[2]][$rs->fields[4]]['_v_player']['_v_weapon']['deaths']+=$rs->fields[6];

        //100
        if (!isset($gmatrixtotal[$rs->fields[2]]['_v_player'][$rs->fields[4]][$rs->fields[5]]['deaths']))
          $gmatrixtotal[$rs->fields[2]]['_v_player'][$rs->fields[4]][$rs->fields[5]]['deaths']=0;
        $gmatrixtotal[$rs->fields[2]]['_v_player'][$rs->fields[4]][$rs->fields[5]]['deaths']+=$rs->fields[6];

        //101
        if (!isset($gmatrixtotal[$rs->fields[2]]['_v_player'][$rs->fields[4]]['_v_weapon']['deaths']))
          $gmatrixtotal[$rs->fields[2]]['_v_player'][$rs->fields[4]]['_v_weapon']['deaths']=0;
        $gmatrixtotal[$rs->fields[2]]['_v_player'][$rs->fields[4]]['_v_weapon']['deaths']+=$rs->fields[6];

        //110
        if (!isset($gmatrixtotal[$rs->fields[2]]['_v_player']['_v_player'][$rs->fields[5]]['deaths']))
          $gmatrixtotal[$rs->fields[2]]['_v_player']['_v_player'][$rs->fields[5]]['deaths']=0;
        $gmatrixtotal[$rs->fields[2]]['_v_player']['_v_player'][$rs->fields[5]]['deaths']+=$rs->fields[6];

        //111
        if (!isset($gmatrixtotal[$rs->fields[2]]['_v_player']['_v_player']['_v_weapon']['deaths']))
          $gmatrixtotal[$rs->fields[2]]['_v_player']['_v_player']['_v_weapon']['deaths']=0;
        $gmatrixtotal[$rs->fields[2]]['_v_player']['_v_player']['_v_weapon']['deaths']+=$rs->fields[6];
        //********** death matrix


        if ($rs->fields[7] == 'suicide')
        {
          // make a separate entry for total and weapon suicides by a team because otherwise it will use the
          // same position in the matrix as total deaths by a team
          
          if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['suicides']))
            $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['suicides']=0;
          $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['suicides']+=$rs->fields[6];

          if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$rs->fields[5]]['suicides']))
            $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$rs->fields[5]]['suicides']=0;
          $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$rs->fields[5]]['suicides']+=$rs->fields[6];


        }
        
        else if ($rs->fields[7] == 'teamkill')
        {
            

          $gevent1D['']['Team Kills'] = 1;
          if (!isset($gmatrix1D[$rs->fields[1]][$rs->fields[3]]['']['Team Kills']))
            $gmatrix1D[$rs->fields[1]][$rs->fields[3]]['']['Team Kills']=0;
          $gmatrix1D[$rs->fields[1]][$rs->fields[3]]['']['Team Kills']+=$rs->fields[6];


          $gevent1D['']['Team Deaths'] = 1;
          if (!isset($gmatrix1D[$rs->fields[2]][$rs->fields[4]]['']['Team Deaths']))
            $gmatrix1D[$rs->fields[2]][$rs->fields[4]]['']['Team Deaths']=0;
          $gmatrix1D[$rs->fields[2]][$rs->fields[4]]['']['Team Deaths']+=$rs->fields[6];


          if (!isset($gmatrix1D[$rs->fields[1]]['_v_player']['']['Team Kills']))
            $gmatrix1D[$rs->fields[1]]['_v_player']['']['Team Kills']=0;
          $gmatrix1D[$rs->fields[1]]['_v_player']['']['Team Kills']+=$rs->fields[6];

          if (!isset($gmatrix1D[$rs->fields[2]]['_v_player']['']['Team Deaths']))
            $gmatrix1D[$rs->fields[2]]['_v_player']['']['Team Deaths']=0;
          $gmatrix1D[$rs->fields[2]]['_v_player']['']['Team Deaths']+=$rs->fields[6];


        }
        else if ($rs->fields[7] == 'kill')
        {
          //valid kill

          //********** kill matrix
          //000
          if (!isset($gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$rs->fields[5]]['kills']))
            $gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$rs->fields[5]]['kills']=0;
          $gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$rs->fields[5]]['kills']+=$rs->fields[6];

          //001
          if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]]['_v_weapon']['kills']))
            $gmatrixtotal[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]]['_v_weapon']['kills']=0;
          $gmatrixtotal[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]]['_v_weapon']['kills']+=$rs->fields[6];

          //010
          if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$rs->fields[5]]['kills']))
            $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$rs->fields[5]]['kills']=0;
          $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$rs->fields[5]]['kills']+=$rs->fields[6];

          //011
          if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['kills']))
            $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['kills']=0;
          $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['kills']+=$rs->fields[6];

          //100
          if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player'][$rs->fields[4]][$rs->fields[5]]['kills']))
            $gmatrixtotal[$rs->fields[1]]['_v_player'][$rs->fields[4]][$rs->fields[5]]['kills']=0;
          $gmatrixtotal[$rs->fields[1]]['_v_player'][$rs->fields[4]][$rs->fields[5]]['kills']+=$rs->fields[6];

          //101
          if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player'][$rs->fields[4]]['_v_weapon']['kills']))
            $gmatrixtotal[$rs->fields[1]]['_v_player'][$rs->fields[4]]['_v_weapon']['kills']=0;
          $gmatrixtotal[$rs->fields[1]]['_v_player'][$rs->fields[4]]['_v_weapon']['kills']+=$rs->fields[6];

          //110
          if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$rs->fields[5]]['kills']))
            $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$rs->fields[5]]['kills']=0;
          $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$rs->fields[5]]['kills']+=$rs->fields[6];

          //111
          if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['kills']))
            $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['kills']=0;
          $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['kills']+=$rs->fields[6];
          //********** kill matrix

        }
        
      }
      else if ($rs->fields[7] == 'accuracy')
      {
        if (preg_match("/^(.*)_(.*)/", $rs->fields[5], $ma))
        {
          $weapon=$ma[1];
          $type=$ma[2];

          if (!isset($gweapon[$weapon])) 
           $gweapon[$weapon]=1;         
          
          if ($type=='hits')
          {
            if (!isset($gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$weapon]['hits']))
              $gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$weapon]['hits']=0;
            $gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$weapon]['hits']+=$rs->fields[6];

            if (!strcmp($rs->fields[3],$rs->fields[4]))// ie the same
            {
              if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$weapon]['hits']))
                $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$weapon]['hits']=0;
              $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$weapon]['hits']+=$rs->fields[6];


              if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['hits']))
                $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['hits']=0;
              $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['hits']+=$rs->fields[6];


              if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$weapon]['hits']))
                $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$weapon]['hits']=0;
              $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$weapon]['hits']+=$rs->fields[6];


              if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['hits']))
                $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['hits']=0;
              $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['hits']+=$rs->fields[6];



            }
          }
          else if ($type=='shots')
          {
            $GLOBALS['g_hitbox']['ALL']=0; // just setting this since it is the same var as is playerstat.php, this is used to indicate if an accuracy info was scanned or not.
            if (!isset($gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$weapon]['shots']))
              $gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$weapon]['shots']=0;
            $gmatrix[$rs->fields[1]][$rs->fields[3]][$rs->fields[4]][$weapon]['shots']+=$rs->fields[6];

            if (!strcmp($rs->fields[3],$rs->fields[4]))// ie the same
            {
              if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$weapon]['shots']))
                $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$weapon]['shots']=0;
              $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player'][$weapon]['shots']+=$rs->fields[6];


              if (!isset($gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['shots']))
                $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['shots']=0;
              $gmatrixtotal[$rs->fields[1]][$rs->fields[3]]['_v_player']['_v_weapon']['shots']+=$rs->fields[6];


              if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$weapon]['shots']))
                $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$weapon]['shots']=0;
              $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player'][$weapon]['shots']+=$rs->fields[6];


              if (!isset($gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['shots']))
                $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['shots']=0;
              $gmatrixtotal[$rs->fields[1]]['_v_player']['_v_player']['_v_weapon']['shots']+=$rs->fields[6];

            }
          }


        }
      
      
      
      }
      else
      {
        // PvP events. Just add as normal 1D event for now
        $gevent1D[$rs->fields[7]][$rs->fields[5]] = 1;

        if (!isset($gmatrix1D[$rs->fields[1]][$rs->fields[3]][$rs->fields[7]][$rs->fields[5]]))
          $gmatrix1D[$rs->fields[1]][$rs->fields[3]][$rs->fields[7]][$rs->fields[5]]=0;
        $gmatrix1D[$rs->fields[1]][$rs->fields[3]][$rs->fields[7]][$rs->fields[5]]+=$rs->fields[6];

        if (!isset($gmatrix1D[$rs->fields[1]]['_v_player'][$rs->fields[7]][$rs->fields[5]]))
          $gmatrix1D[$rs->fields[1]]['_v_player'][$rs->fields[7]][$rs->fields[5]]=0;
        $gmatrix1D[$rs->fields[1]]['_v_player'][$rs->fields[7]][$rs->fields[5]]+=$rs->fields[6];


      }





    }while($rs->MoveNext());
  }
  
  
 
  
  if ($gweapon)
  {
    ksort($gweapon);
  }
  
  //htmlprint_r($gmatrixtotal);
  

}
//*************************************************************************
function drawNavBar()
{
  global $config;
  ?>
  <!-- navbar begin          ################################################-->
  <table CLASS="cellHeading" CELLSPACING="0" CELLPADDING="1" WIDTH="100%" style="border-width: 0;">
    <TR>
    <TD CLASS="cellHeading" style="border-right-width: 0; text-align: left;" >
    <!-- search form begin     ################################################-->
    <form style="display:inline" method="post" action="<?php  print "$_SERVER[PHP_SELF]?config=$config"; ?>">
    <input CLASS="cellSubHeading" style="text-align: center; border-width: 1" type="Submit" name="goto_btn" value="&nbsp;goto #&nbsp;">
    <input CLASS="cellSubHeading" style="text-align: center; border-width: 1"   type="Text"   name="goto_txt" size="15">
    </form>
    <!-- search form end       ################################################-->
    </TD>


    <TD CLASS="cellHeading" style="border-left-width: 0; border-right-width: 0; text-align: left" width="100%">

      <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
        <TR>
        <TD CLASS="cellSubHeading" style="text-align: left">
        &nbsp;
        <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;sort=$sort&amp;start_from=0"; ?>">first</A> |
        <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;sort=$sort&amp;start_from={$GLOBALS['total_records']}"; ?>">last</A> |
        <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;sort=$sort&amp;start_from={$GLOBALS['prev']}"; ?>">prev</A> |
        <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;sort=$sort&amp;start_from={$GLOBALS['next']}"; ?>">next</A>
        &nbsp;
        </TD>

        <TD WIDTH="100%" style="border-width: 0; text-align: left">&nbsp;</TD>
        </TR>
      </table>
    </TD>

    

    </TR>
  </table>
  <!-- navbar end            ################################################-->
  <?php
}
//******************************************************************************

function drawTeamOverall($teamID)
{
  global $gmatrix,$gmatrixtotal,$gteam,$gweapon,$gplayerName,$gteam_index,$gevent1D;
  global $categories_per_row;
  
  // making a copy because foreach screws up if nested and using the same array
  $gteam_tmp=$gteam;


  ?>
  <!-- teamstat begin ############################################ -->
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
    <TR>
    <TD COLSPAN=5 style="font-size: 180%; text-align: center" WIDTH="100%" CLASS="<?php print "team{$gteam_index[$teamID]}";?>">
    Team <?php  print $teamID; ?>
    </TD>
    </TR>
  </table>
  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%" CLASS="sortable" id="TeamOverallStats<?php print "team{$gteam_index[$teamID]}";?>">

    <TR CLASS="cellSubHeading">
    <TD WIDTH="40%" style="text-align: left">Name</TD>
    <TD WIDTH="15%" sortdir="down">Kills</TD>
    <TD WIDTH="15%">Deaths</TD>
    <TD WIDTH="15%">Suicides</TD>   
    <TD WIDTH="15%">Eff %</TD>       

    </TR>

  <?php


  //htmlprint_r( $gteam);

  $no_of_rows=0;
  foreach($gteam_tmp as $team => $team_val)
  {
    $new_count = count($team_val);
    if ($new_count > $no_of_rows)
      $no_of_rows = $new_count;
  }


  $count = 0;
  
  //htmlprint_r($gteam_tmp);
  foreach($gteam_tmp[$teamID] as $playerID => $val)
  {
    if ($count%2 == 0)
      $cell_class="cell1";
    else
      $cell_class="cell2";
    $count++;


    $kills=0+@$gmatrixtotal[$teamID][$playerID]['_v_player']['_v_weapon']['kills'];
    $deaths=0+@$gmatrixtotal[$teamID][$playerID]['_v_player']['_v_weapon']['deaths'];
    $suicides=0+@$gmatrixtotal[$teamID][$playerID][$playerID]['_v_weapon']['deaths'];
//change: playername
    ?>

    <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >
    
    <TD style="text-align: left">
    <A HREF="#stat-<?php print fstr($teamID.'-'.$playerID);?>">
    <?php print processColors(html_encode($gplayerName[$playerID]),$GLOBALS['settings']['display']['color_names'],$GLOBALS['settings']['display']['max_char_length']);?>
    </A>
    </TD>
    <TD><?php print $kills;?></TD>
    <TD><?php print $deaths;?></TD>
    <TD><?php print $suicides;?></TD>
    <TD><?php printf("%02.2f",100*$kills/(0.00001+$kills+$deaths));?></TD>
    </TR>
    <?php




  }

  
  

  while($count<$no_of_rows)
  {
    $count++;
    if ($count%2 == 1)
      $cell_class="cell1";
    else
      $cell_class="cell2";

    ?>
    <TR sortbottom="1" CLASS="<?php print "$cell_class"?>">
    <TD>&nbsp;</TD>
    <TD>&nbsp;</TD>
    <TD>&nbsp;</TD>
    <TD>&nbsp;</TD>
    <TD>&nbsp;</TD>
    </TR>
    <?php
  }


  $kills=0+@$gmatrixtotal[$teamID]['_v_player']['_v_player']['_v_weapon']['kills'];
  $deaths=0+@$gmatrixtotal[$teamID]['_v_player']['_v_player']['_v_weapon']['deaths'];
  $suicides=0+@$gmatrixtotal[$teamID]['_v_player']['_v_player']['_v_weapon']['suicides'];


  ?>
  <TR sortbottom="1" CLASS="<?php print "team{$gteam_index[$teamID]}";?>">
    <TD style="text-align: left; "><?php print 'Total';?></TD>
    <TD><?php print $kills;?></TD>
    <TD><?php print $deaths;?></TD>
    <TD><?php print $suicides;?></TD>
    <TD><?php printf("%02.2f",100*$kills/(0.00001+$kills+$deaths));?></TD>
  
  </TR>


  </table>

  <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%" >




  <TR>
  <TD COLSPAN=1 style="border-width: 0; padding: 0; text-align: center" WIDTH="100%">



  <table style="border-width: 0; padding: 0px 0px 0px 0px" CELLSPACING=0 CELLPADDING=0 WIDTH="100%">
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
          <TD WIDTH="<?php print round(100/($categories_per_row),2)?>%" CLASS="cellBG" style="vertical-align: top; text-align: left ;border-width: 0; padding: 0px 0px 0px 0px">
          <?php drawStats1D('_v_player',$teamID,$eventCategory);?>
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
        <TD WIDTH="<?php print 100/($categories_per_row)?>%" CLASS="cellSubHeading" style="vertical-align: top; text-align: left ;border-width: 0; padding: 0px 0px 0px 0px">
        <?php drawStats1D("","","");?>
        </TD>
        <?php

        if ($count_cat%$categories_per_row==0)
          echo "</TR>\n";

      }


      ?>
      <TR>
        <TD COLSPAN="<?php print $categories_per_row;?>" WIDTH="100%" CLASS="cellSubHeading" style="vertical-align: top; text-align: left; padding: 0 ">
        <?php drawWeaponStats('_v_player',$teamID);?>
        </TD>
      </TR>
    </table>







  </TD>
  </TR>
















  </table>
  <!-- teamstat end          ################################################-->
  <?php

}
//*************************************************************************
function drawGamesList()
{
  global $db;
  global $start_from;
  global $config;

  $sql="select distinct gameID
          from {$GLOBALS['cfg']['db']['table_prefix']}gamedata
          ORDER BY gameID DESC
       ";
  //echo $sql;
  $rs=$db->SelectLimit($sql,$GLOBALS['cfg']['display']['record_limit'],$GLOBALS['start_from']);
  if (!$rs)
    return;

  $gameID_max = $rs->fields[0];
  $rs->MoveLast();
  $gameID_min = $rs->fields[0];

  
  
  $sql="select gameID,name,value
          from {$GLOBALS['cfg']['db']['table_prefix']}gamedata 
          where gameID >= $gameID_min
                AND gameID <= $gameID_max
                AND name LIKE '\\_v_%'
          ORDER BY gameID DESC,name ASC
       ";
  //echo $sql;
  $rs=$db->Execute($sql);
  
  //echo $_GET['start_from'];
  
  if ($rs && !$rs->EOF)
  {
    $i=0;
    $gameIDi=$rs->fields[0];
    do
    {
      $field_name[$i]=substr($rs->fields[1],3); //get rid of the _v_
      $i++;
    }while($rs->MoveNext() && $gameIDi==$rs->fields[0]);
    $field_name[$i]='Game';


    $no_of_field_names=count($field_name)

    ?>
    <!-- gamestats table begin ##################################################-->
    <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
      <TR>


        <TD COLSPAN="4" CLASS="cellHeading" style="text-align: left; border-right: 0">
        Game Listing
        </TD>

        <TD COLSPAN="<?php echo $no_of_field_names-3;?>" CLASS="cellHeading" style="text-align: left; border-left: 0">

          <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
            <TR>
               <TD WIDTH="100%" style="border-width: 0; text-align: right">&nbsp;
              </TD>

              <TD CLASS="cellSubHeading" style="text-align: right">
                &nbsp;
                <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from=0"; ?>">first</A>&nbsp;|&nbsp;
                <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from={$GLOBALS['total_records']}"; ?>">last</A>&nbsp;|&nbsp;
                <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from={$GLOBALS['prev']}"; ?>">prev</A>&nbsp;|&nbsp;
                <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from={$GLOBALS['next']}"; ?>">next</A>
                &nbsp;
              </TD>
            </TR>
          </table>
        </TD>

      </TR>

      

    <TR>
    <TD CLASS="cellSubHeading">#</TD>
    
    <?php
    for ($i=0;$i<$no_of_field_names;$i++)
    {
      ?>
      <TD CLASS="cellSubHeading" style="text-align: center"><?php print fstr($field_name[$i]);?></TD>
      <?php
    }


    ?>
    </TR>


    <?php
    $rs->Move(0);
    $count=0;
    do
    {
      $count++;
      if ($count%2 == 1)
        $cell_class="cell1";
      else
        $cell_class="cell2";

      $gameIDi=$rs->fields[0]
      
      ?>
      <TR CLASS="<?php print "$cell_class"?>" onMouseOver="this.className='rowHighlight';" onMouseOut="this.className='<?php print "$cell_class"?>';" >

      <TD>
      <?php print $count+$start_from;?>
      </TD>

      
      <?php
      do
      {
        ?>
        <TD style="text-align: center;">
        <?php
          print $rs->fields[2];
        ?>
        </TD>

        <?php
      }while($rs->MoveNext() && $gameIDi==$rs->fields[0]);
      ?>
      
      
      <TD style="text-align: center;">
      <A HREF="gamestat.php?gameID=<?php print $gameIDi."&amp;config=$GLOBALS[config]";?>">Details
      <?php
        //print $rs->fields[0]; 
      ?>
      </A>
      </TD>
      
      
      </TR>
      <?php

    }while (!$rs->EOF);

    ?>
    <TR>

      <TD colspan="11" CLASS="cellHeading" style="border-left-width: 0; border-right-width: 0; text-align: left" width="100%">

        <table style="border-width: 0" CELLSPACING=0 CELLPADDING=2 WIDTH="100%">
          <TR>

          <TD WIDTH="100%" style="border-width: 0; text-align: left">&nbsp;
            <!-- goto   form begin     ################################################-->
            <form style="display:inline" method="post" action="<?php  print "$_SERVER[PHP_SELF]?config=$config"; ?>">

            <input CLASS="cellSubHeading" style="text-align: center; border-width: 1" type="Submit" name="goto_btn" value="&nbsp;goto #&nbsp;">&nbsp;
            <input CLASS="cellSubHeading" style="text-align: center; border-width: 1"   type="Text"   name="goto_txt" size="10">
            </form>
            <!-- goto   form end       ################################################-->
          </TD>

          <TD CLASS="cellSubHeading" style="text-align: right">
          &nbsp;
          <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from=0"; ?>">first</A>&nbsp;|&nbsp;
          <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from={$GLOBALS['total_records']}"; ?>">last</A>&nbsp;|&nbsp;
          <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from={$GLOBALS['prev']}"; ?>">prev</A>&nbsp;|&nbsp;
          <A HREF="<?php  print "$_SERVER[PHP_SELF]?config=$config&amp;start_from={$GLOBALS['next']}"; ?>">next</A>
          &nbsp;
          </TD>

          </TR>
        </table>


      </TD>
    </TR>

    </table>
    <!-- gamestats table end ##################################################-->
    <?php
  }
}
//*************************************************************************
function setupVars()
{
  global $db,$start_from,$prev,$next,$total_records;

  $sql="select count(distinct gameID) 
          from {$GLOBALS['cfg']['db']['table_prefix']}gamedata
       ";
  $rs=$db->Execute($sql);
  $total_records=$rs->fields[0];

  

  //******************
  if (isset($_POST['goto_btn']) || isset($_POST['goto_txt'])) 
  {
    //echo $_POST['goto_txt'];
    $start_from=intval($_POST['goto_txt'])-1;
  }
  else if (isset($_GET['start_from']))
  {
    $start_from=$_GET['start_from'];
  }
  
  //********
  // limit checks
  if ($start_from>=$total_records)
  {
    $start_from=$total_records-$GLOBALS['cfg']['display']['record_limit'];
  }
  if (!isset($start_from) || $start_from<0) 
  {
    $start_from=0;
  }
  //********
  //******************
  
  
  //******************
  $next=$start_from+$GLOBALS['cfg']['display']['record_limit'];
  $prev=$start_from-$GLOBALS['cfg']['display']['record_limit'];

  //********
  // limit checks
  if ($prev<0)
    $prev=0;

  if ($next>$total_records)
    $next=$total_records;
  //********
  //******************

  if (!is_dir("../../games/{$GLOBALS['cfg']['game']['name']}"))
  {
    $GLOBALS['cfg']['game']['name']='default';
  }

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




<?php
if ($gameID)
{
?>

  <TR>
    <TD style="vertical-align: top; padding: 10px 10px 10px 10px; border-width: 0 0 0 0;" COLSPAN=2 CLASS="cellBG">
      <?php
      drawHeadBar();
      ?>
    </TD>
  </TR>

  <TR>
    <TD style="vertical-align: top; padding: 0px 10px 5px 10px; border-width: 0 0 0 0;" COLSPAN=2 CLASS="cellBG">
      <?php drawStatsGame();?>
    </TD>
  </TR>




  <TR>
    <TD style="vertical-align: top; padding: 10px 5px 5px 5px; border-width: 0 0 0 0;" COLSPAN=2 CLASS="cellBG">
      <?php drawPlayerStats();?>
    </TD>
  </TR>




  <?php
  if ($GLOBALS['settings']['display']['gamestats_killchart'] && count($GLOBALS['gplayerName']) < $GLOBALS['settings']['display']['gamestats_killchart'] )
  {
    ?>
    <TR>
      <TD style="vertical-align: top; padding: 0px 10px 20px 10px; border-width: 0 0 0 0;" COLSPAN=1 CLASS="cellBG">
        <?php drawKillChart();?>
      </TD>
    </TR>
    <?php
  }
  ?>

<?php
}
else
{
?>

  <TR>
    <TD style="vertical-align: top; padding: 10px 10px 10px 10px; border-width: 0 0 0 0;" COLSPAN=2 CLASS="cellBG">
      <?php
      drawHeadBar();
      ?>
    </TD>
  </TR>


  <TR>
    <TD style="vertical-align: top; padding: 0px 10px 10px 10px; border-width: 0 0 0 0;" COLSPAN=2 CLASS="cellBG">
      <?php drawGamesList();?>
    </TD>
  </TR>
<?php
}
?>

<TR>
  <TD COLSPAN=2 style="vertical-align: top; padding: 0 0 0 0; border-width: 0 0 0 0;" CLASS="cellBG">
    <?php
    drawCredits();
    ?>
  </TD>
</TR>
</table>
<!-- layout table end   ##################################################-->

<?php

  ob_end_flush(); // flush after compactHTML
  echo "<center>page loaded in ".timeElapsed($start_time)."s (".$pre_time."s)</center>";


?>


</BODY>
</HTML>



