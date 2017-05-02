<?php
//*************************************************************************
function fixPath($path)
{
  return rtrim($path,"\\/").'/';
}
//*************************************************************************
function implode_r($glue, $array, $array_name = NULL)
{
  // ulderico at maber dot com dot br from php.net
  while(list($key,$value) = @each($array))
  if(is_array($value))
    $return[] = implode_r($glue, $value, (string) $key);
  else if($array_name != NULL)
   $return[] = $array_name."[".(string) $key."]=".$value;
  else
   $return[] = $key."=".$value;
  return(implode($glue, $return));
}
//*************************************************************************
function htmlprint_r($var) 
{
  echo "<PRE>";
  print_r($var);
  echo "</PRE>";
}
//*************************************************************************
function timeElapsed(&$start_time) 
{
  $end_time=gettimeofday();
  $total_time = (float)($end_time['sec'] - $start_time['sec']) + ((float)($end_time['usec'] - $start_time['usec'])/1000000);
  return $total_time;
}
//*************************************************************************
function div($a,$b)
{
 return ($a-($a % $b))/$b;
}
//*************************************************************************
function fstr($str)
{
  //format string
  //echo $str;
  return html_encode(ucfirst(strtolower(str_replace("_", " ",$str))));
}
//*************************************************************************
function standard_deviation($std)  
{ 
  $total; 
  while(list($key,$val) = each($std)) 
  { 
    $total += $val; 
  } 
  reset($std); 
  $mean = $total/count($std); 

  while(list($key,$val) = each($std)) 
  {  
    $sum += pow(($val-$mean),2); 
  }  
  $var = sqrt($sum/(count($std)-1)); 
  return $var;  
}
//*************************************************************************
function stripIllegalFilenameChars()
{ //arguments - $str,$exclude_chars
  $num_args = func_num_args();
  
  $str=func_get_arg(0);
  if ($num_args>1)
    $exclude_chars=func_get_arg(1);
  else
    $exclude_chars='';

  $str_length=strlen($str);
  
  for ($i=0;$i<$str_length;$i++)
  {
    if (strstr($exclude_chars,$str[$i]))
      continue;
    
    $ascii=ord($str[$i]);
    if  ( $str[$i]!='.' && 
          ( $ascii<48 || ($ascii>57 && $ascii<65) || ($ascii>90 && $ascii<97) || $ascii>122 )
        )
    {
      $str[$i]='_';
    }
  }
  return $str;
}
//*************************************************************************
function processColors($str,$enable_colors,$max_length)
{
  $num_args = func_num_args();

  if ($num_args==4)
    $html_title=func_get_arg(3); // if this is set, then a TITLE is added in the TAG for everything that gets chopped off
  else
    $html_title=0;

  $clean_str=preg_replace ("/`#[\da-fA-F]{6}/", "", $str);
  //echo strlen($clean_str);
  if (strlen($clean_str)<1 || !preg_match('/[\x21-\x7E\w]/',$clean_str)) // force a printable character in the name
  {
    $str.="&nbsp;";
    $clean_str.="&nbsp;";
  }

  $str_length=strlen($str);
  if ($max_length<1)
    $max_length=$str_length;

  if (!$enable_colors)
  {
    if (strlen($clean_str)>$max_length)
    {
      $processed_str=substr($clean_str,0,$max_length)."...";
      if ($html_title)
        $processed_str=" TITLE=\"$clean_str\">".$processed_str;
      return $processed_str;
    }
    else
    {
      $processed_str=$clean_str;
      if ($html_title)
        $processed_str=">".$processed_str;
      return $processed_str;
    }
  }


  $clean_str_char_count=0;
  $processed_str='';
  $font_tag=0;
  $fonted_str='';
  $i=0;

  if ($str_length<1)
  {
    $processed_str="?";
    if ($html_title)
      $processed_str=">".$processed_str;
    return $processed_str;
  }
  for ($i=0;$i< $str_length-1;$i++)
  {
    if ($clean_str_char_count >= $max_length)
    {
      if ($font_tag)
        $processed_str .= "$fonted_str</font>";
      $processed_str.="...";

      if ($html_title)
        $processed_str=" TITLE=\"$clean_str\">".$processed_str;
      return $processed_str;
    }
    if ($str[$i]=="`" && $str[$i+1]=="#")
    {
      if (preg_match("/(^[\da-fA-F]{6})/",substr($str, $i+2,6),$ma))
      {
        if ($font_tag)
        {
          $processed_str .= "$fonted_str</font>";
          $fonted_str='';
          $font_tag=0;
        }

        $processed_str .= "<font color=\"#$ma[1]\">";
        $font_tag=1;
        $i += 7;
        continue;
      }
    }
    if ($font_tag)
    {
      $fonted_str.=$str[$i];
    }
    else
    {
      $processed_str .=$str[$i];
    }
    //echo $str[$i]."=".$clean_str_char_count."|";
    
    //-----------------
    // count html entities as only 1
    if ($str[$i]=='&')
    {
      //echo substr($str,$i+1);
      //echo strpos(substr($str,$i),";");
      $clean_str_char_count-=strpos(substr($str,$i),";");
    }
    //-----------------
    
    $clean_str_char_count++;
  }
  $clean_str_char_count++;
  //echo $clean_str_char_count;
  
  if ($font_tag)
    $processed_str .= "$fonted_str";

  if ($i<$str_length)
    $processed_str .= $str[$i];

  if ($font_tag)
    $processed_str .= "</font>";

  if ($html_title)
    $processed_str=">".$processed_str;
  return $processed_str;
}
//*************************************************************************
function compactHTML($buffer) 
{
  
  $search = array ("/\s*[\r\n]+\s*/" // Strip out newlines
                   ,"/\s{2,}/"       // replace 2+ spaces with 1
                   ,"/<!--.*-->/U"   // remove html comments
                   ,"/<table/i"      // do a return on every table so its not one huge line which may cause problems with some browsers/viewers?
                  ); 
  $replace = array (""
                    ," "
                    ,""
                    ,"\r\n<table"
                   );
  $compacted=preg_replace ($search, $replace, $buffer);

  return ( preg_replace ("/>[ \t]+</", "><", $compacted) );
}
//*************************************************************************
//change: add player_exclude_list support
function load_player_exclude_list() {
    global $db;
    include_once(fixpath(cROOT_PATH)."include/playerExcludeList-{$GLOBALS['cfg']['player_exclude_list']}.inc.php");
    foreach ($GLOBALS['player_exclude_list'] as $key => $value) {
        $GLOBALS['player_exclude_list'][$key]=$db->qstr($value);
    }
    $GLOBALS['excluded_players'] = count($GLOBALS['player_exclude_list']) ?
        "(".implode(",", $GLOBALS['player_exclude_list']).")" : false;
}
//endchange
//************************************************************************
function html_encode($var) {
	$return = htmlentities($var, ENT_QUOTES, 'UTF-8');
    if (!$return) {
        $return = htmlentities($var, ENT_QUOTES);
    }
    return $return;
}
?>