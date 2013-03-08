<?php
Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
$fquantity=file("./voting/quantity.txt");
$fvote=file("./voting/vote.txt");
$im=imagecreate(480,170);
$white=imagecolorallocate($im,255,255,255);
$black=imagecolorallocate($im,0,0,0);
$p[1]=imagecolorallocate($im,0,0,255);
$p[2]=imagecolorallocate($im,255,0,0);
$p[3]=imagecolorallocate($im,0,255,0);
$p[4]=imagecolorallocate($im,255,128,0);
$p[5]=imagecolorallocate($im,255,0,128);
$p[6]=imagecolorallocate($im,128,128,255);
$p[7]=imagecolorallocate($im,0,255,255);
$p[8]=imagecolorallocate($im,255,255,0);
$fquantity[1]=trim(str_replace ("\r\n","",$fquantity[1]));
$all_v=0;
for ($numb=1; $numb<=$fquantity[1]; $numb++)
 {
   $fvote[$numb]=trim (str_replace ("\r\n","",$fvote[$numb]));
   $all_v=$all_v+$fvote[$numb];
 }
for ($numb=1; $numb<=$fquantity[1]; $numb++)
 {
  imagestring($im,4,3,(5+20*($numb-1)),(bcdiv($fvote[$numb], $all_v, 3)*100)."%",$black);
  ImageFilledRectangle($im,43,(3+20*($numb-1)),(43+(bcdiv($fvote[$numb], $all_v, 2)*400)),(20*$numb),$p[$numb]);
  imagestring($im,3,(43+(bcdiv($fvote[$numb], $all_v, 2)*400)+2),(5+20*($numb-1)),$fvote[$numb],$black);
 }
/*imagestring($im,4,3,5,"1.1%",$black);
imagestring($im,4,3,25,"100%",$black);
imagestring($im,4,3,45,"100%",$black);
imagestring($im,4,3,65,"100%",$black);
imagestring($im,4,3,85,"100%",$black);
imagestring($im,4,3,105,"100%",$black);
imagestring($im,4,3,125,"100%",$black);
imagestring($im,4,3,145,"100%",$black);
ImageFilledRectangle($im,43,3,443,20,$p[1]);
ImageFilledRectangle($im,43,23,443,40,$p[1]);
ImageFilledRectangle($im,43,43,443,60,$p[1]);
ImageFilledRectangle($im,43,63,443,80,$p[1]);
ImageFilledRectangle($im,43,83,443,100,$p[1]);
ImageFilledRectangle($im,43,103,443,120,$p[1]);
ImageFilledRectangle($im,43,123,443,140,$p[1]);
ImageFilledRectangle($im,43,143,443,160,$p[1]);*/
Header("Content-type: image/png");
ImagePng($im);
ImageDestroy($im);
?>
