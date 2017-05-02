<?php 
Header( "Content-Type: image/jpeg");

/*
$bodyImage = imagecreatefromjpeg("body.jpg");
if (isset($_GET['H']['head']))
  $str=strval($_GET['H']['head']);


if (!isset($str))
  $str= $_SERVER['QUERY_STRING'];
imagestring($bodyImage, 2,  5,  5, $str, imagecolorallocate($bodyImage, 155, 155, 155));
imagejpeg($bodyImage, '', 100);
*/

$HB=$_GET['H'];


function getColorLevel(&$cImage,$level)
{
  $rgb = ImageColorAt($cImage, 140, 186-$level);
  $r = ($rgb >> 16) & 0xFF;
  $g = ($rgb >> 8) & 0xFF;
  $b = $rgb & 0xFF;
  $opacity=45;
  return imagecolorallocatealpha($cImage,$r,$g,$b, 100-$opacity);
}

// [left,right]->arm -> upper(shoulder,bicep)  lower(elbow,forearm)  hand(wrist,fingers)
// [left,right]->leg -> upper(thigh)           lower(knee,calves)    foot(ankle,toes)
// head(face,ears,eyes,etc)  neck
// torso->              chest(breast)          stomach(abdomen,waist)

$bodypartPoints['arm-right(upper)'][] = array(
  43,65
  ,43,40
  ,60,40
  ,60,65
);

$bodypartPoints['arm-left(upper)'][] = array(
   107,40
  ,107,65
  ,90,65
  ,90,40
);

$bodypartPoints['arm-right(lower)'][] = array(
  23,65
  ,23,45
  ,42,45
  ,42,65
);

$bodypartPoints['arm-left(lower)'][] = array(
   108,45
  ,108,65
  ,127,65
  ,127,45
);

$bodypartPoints['arm-right(hand)'][] = array(
   5,45
  ,5,65
  ,22,65
  ,22,45
);

$bodypartPoints['arm-left(hand)'][] = array(
   145,45
  ,145,65
  ,128,65
  ,128,45
);

$bodypartPoints['leg-right(upper)'][] = array(
  55,135
  ,65,85
  ,75,105
  ,70,140
);

$bodypartPoints['leg-left(upper)'][] = array(
  95,135
  ,85,85
  ,75,105
  ,80,140
);

$bodypartPoints['leg-right(lower)'][] = array(
  50,161
  ,55,135
  ,70,140
  ,60,166
);

$bodypartPoints['leg-left(lower)'][] = array(
  80,140
  ,90,166
  ,100,161
  ,95,135
);

$bodypartPoints['leg-right(foot)'][] = array(
   50,161
  ,60,166
  ,60,185
  ,40,185
);

$bodypartPoints['leg-left(foot)'][] = array(
   90,166
  ,100,161
  ,110,185
  ,90,185
);

$bodypartPoints['head'][] = array(
   60,39
  ,90,39
  ,90,12
  ,60,12

);

$bodypartPoints['neck'][] = array(
   60,38
  ,90,38
  ,90,45
  ,60,45

);

$bodypartPoints['chest'][] = array(
   59,46
  ,91,46
  ,87,66
  ,63,66

);

$bodypartPoints['stomach'][] = array(
   65,67
  ,85,67
  ,85,83
  ,75,103
  ,65,83

);

// put highest categories on top: ex:- define arm-left before arm-left(ul)

$bodyCategory['torso'][]='chest';
$bodyCategory['torso'][]='stomach';



$bodyCategory['arm-left'][]='arm-left(upper)';
$bodyCategory['arm-left'][]='arm-left(lower)';
$bodyCategory['arm-left'][]='arm-left(hand)';

$bodyCategory['arm-right'][]='arm-right(upper)';
$bodyCategory['arm-right'][]='arm-right(lower)';
$bodyCategory['arm-right'][]='arm-right(hand)';

$bodyCategory['arm-left(ul)'][]='arm-left(upper)';
$bodyCategory['arm-left(ul)'][]='arm-left(lower)';

$bodyCategory['arm-right(ul)'][]='arm-right(upper)';
$bodyCategory['arm-right(ul)'][]='arm-right(lower)';

$bodyCategory['arm-left(lh)'][]='arm-left(lower)';
$bodyCategory['arm-left(lh)'][]='arm-left(hand)';

$bodyCategory['arm-right(lh)'][]='arm-right(lower)';
$bodyCategory['arm-right(lh)'][]='arm-right(hand)';


$bodyCategory['leg-left'][]='leg-left(upper)';
$bodyCategory['leg-left'][]='leg-left(lower)';
$bodyCategory['leg-left'][]='leg-left(foot)';

$bodyCategory['leg-right'][]='leg-right(upper)';
$bodyCategory['leg-right'][]='leg-right(lower)';
$bodyCategory['leg-right'][]='leg-right(foot)';

$bodyCategory['leg-left(ul)'][]='leg-left(upper)';
$bodyCategory['leg-left(ul)'][]='leg-left(lower)';

$bodyCategory['leg-right(ul)'][]='leg-right(upper)';
$bodyCategory['leg-right(ul)'][]='leg-right(lower)';

$bodyCategory['leg-left(lf)'][]='leg-left(lower)';
$bodyCategory['leg-left(lf)'][]='leg-left(foot)';

$bodyCategory['leg-right(lf)'][]='leg-right(lower)';
$bodyCategory['leg-right(lf)'][]='leg-right(foot)';


foreach($bodyCategory as $main_cat => $main_cat_val)
{
  if (isset($HB[$main_cat]))
  {
    foreach ($main_cat_val as $part)
    {
      foreach ($bodypartPoints[$part] as $points)
      {
        $bodypartPoints[$main_cat][]=$points;
      }
      unset($bodypartPoints[$part]);
    }
    
  }
  
}


foreach ($bodypartPoints as $part=>$val)
{
  if (!isset($HB[$part]))
    $HB[$part]=0;
  
}


$bodyImage = imagecreatefromjpeg("images/body.jpg");
$bodybgImage = ImageCreateFromPNG('images/bodybg-duotone.png');

$bodybgW = ImageSX($bodybgImage);
$bodybgH = ImageSY($bodybgImage);

$hits_at_highest_loc=0;
foreach($HB as $loc => $val)
{
  if ($loc=='ALL')
    continue;
  if ($HB[$loc]>$hits_at_highest_loc)
    $hits_at_highest_loc=$HB[$loc];
}
$hits_at_highest_loc+=0.01;

$max_color_level=100;

foreach($HB as $loc => $val)
{
  if ($loc=='ALL' || !isset($bodypartPoints[$loc]))
    continue;

  foreach($bodypartPoints[$loc] as $points)
  {
    imagefilledpolygon($bodyImage, $points, count($points)/2, getColorLevel($bodybgImage,intval($HB[$loc]/$hits_at_highest_loc*$max_color_level)) );
  }
}

ImageCopy($bodyImage, $bodybgImage, 0, 0, 0, 0, $bodybgW, $bodybgH); 

if (isset($HB['head']) && isset($HB['ALL']) && $HB['ALL']>0)
{
  $color_text = imagecolorallocate($bodyImage, 155, 155, 155);
  $string="".intval($HB['head'])." (".(intval($HB['head']/$HB['ALL']*100))."%)";
  imagestring($bodyImage, 2,  90,  15, $string, $color_text);
}

imagejpeg($bodyImage, '', 100);

?>