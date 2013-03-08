<?php
mysql_query("SET NAMES utf8;");

set_time_limit(60);

//------------------- удаление старых изображений---------------

  function delTemporaryFiles ($directory)
  {
  $dir = opendir ($directory);
  while (( $file = readdir ($dir)))
  {
    if( is_file ($directory."/".$file))
    {
      $acc_time = fileatime ($directory."/".$file);
      $time =  time();
      if (($time - $acc_time) > 10)
      {
          unlink ($directory."/".$file);

      }
    }
    else if ( is_dir ($directory."/".$file) && ($file != ".") && ($file != ".."))
    {
      delTemporaryFiles ($directory."/".$file);
    }
  }
  closedir ($dir);
  }
 delTemporaryFiles ("images/pChart/");

 $image=time();

 // Standard inclusions
 include("pChart/pData.class");
 include("pChart/pChart.class");


   $cnt=0;
 if(($_POST['year'])!=0)
   {
   foreach($_POST['checkbox_arr'] AS $i)
   	{
   	$sql=mysql_query("SELECT * FROM  summa_ballov WHERE id_kadri=".$i." AND id_year=".$_POST['year']."");
    $cnt=$cnt+mysql_num_rows($sql);
    }
   }

  reset($_POST['checkbox_arr']);
 $count_images=ceil((count($_POST['checkbox_arr'])+$cnt)/20);//кол-во картинок

  for($z=1;$z<=$count_images;$z++)
{   $c=0;$y=1;
   $DataSet = new pData;
   while($y<=20)//for($y=1;$y<=20;$y++)
   {
   	$i=each($_POST['checkbox_arr']);
   	if ($i)
   		{
   $sql="SELECT time_intervals.name AS year, kadri.fio AS name,summa_ballov.*  FROM summa_ballov INNER JOIN time_intervals ON summa_ballov.id_year=time_intervals.id INNER JOIN kadri ON summa_ballov.id_kadri=kadri.id WHERE id_kadri=".$i['value']." AND id_year=".$_GET['id_year']."";
   $mysql=mysql_query($sql);
   $graph=mysql_fetch_array($mysql);

   			$DataSet->AddPoint($graph['zvanie'],"Serie1");
   			$DataSet->AddPoint($graph['dolzhnost'],"Serie2");
   			$DataSet->AddPoint($graph['nauch_met_uch_rab'],"Serie3");
   			$DataSet->AddPoint($graph['vichet'],"Serie4");
            $DataSet->AddPoint(str_replace(' ',chr(10),$graph['name']),"Serie5");
            if(($_POST['year'])!=0)
           {
           $query=mysql_query("SELECT  kadri.fio AS name,summa_ballov.* ,time_intervals.name AS year FROM summa_ballov INNER JOIN time_intervals ON summa_ballov.id_year=time_intervals.id INNER JOIN kadri ON summa_ballov.id_kadri=kadri.id WHERE id_kadri=".$i['value']." AND id_year=".$_POST['year']."");
           $graph1=mysql_fetch_array($query);
           if($graph1)
           		{
           $DataSet->AddPoint($graph1['zvanie'],"Serie1");
   			$DataSet->AddPoint($graph1['dolzhnost'],"Serie2");
   			$DataSet->AddPoint($graph1['nauch_met_uch_rab'],"Serie3");
   			$DataSet->AddPoint($graph1['vichet'],"Serie4");
   			$DataSet->AddPoint(str_replace(' ',chr(10),$graph1['name'].' '.$graph1['year']),"Serie5");
                $c++;$y++;
            	}
           }
    $c++;$y++;
       }
       else { $y=22; break;}
   }
   $length=1100;
   $graph_font=7;
   if ($c<=10) {$graph_font=8; $length=700;}
   if ($c>10 and $c<=15) {$graph_font=7; $length=900;}

 // Dataset definition

  $DataSet->AddAllSeries();
  $DataSet->RemoveSerie("Serie5");

  $DataSet->SetAbsciseLabelSerie("Serie5");
  $mysql=mysql_query("SELECT * FROM pChart");
  $legend=mysql_fetch_array($mysql);
  $DataSet->SetSerieName($legend['zvanie'],"Serie1");
  $DataSet->SetSerieName($legend['dolzhnost'],"Serie2");
  $DataSet->SetSerieName($legend['rabota'],"Serie3");
  $DataSet->SetSerieName($legend['vichet'],"Serie4");

  $Test = new pChart($length+60,280);
  // Initialise the graph
  $Test->setFontProperties("Fonts/tahoma.ttf",$graph_font);
  $Test->setGraphArea(30,30,$length-20,200); //$Test->setGraphArea(85,30,650,200);
  $Test->drawFilledRoundedRectangle(0,0,$length+53,273,5,240,240,240);// заливка
  $Test->drawRoundedRectangle(0,0,$length+55,275,5,230,230,230);//рамка вокруг
  $Test->drawGraphArea(255,255,255,TRUE);

  if(($_POST['graph_type'])==17)
  {


 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);

  }
  else
  {

 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,150,150,150,TRUE,0,2,TRUE);

  }
  $Test->drawGrid(4,TRUE,230,230,230,50);

  // Draw the 0 line
 $Test->setFontProperties("Fonts/tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

  if(($_POST['graph_type'])==20)
  {

    // Example20 : A stacked bar graph

     // Draw the bar graph
 $Test->drawStackedBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),100);

 // Finish the graph



  }

  if(($_POST['graph_type'])==12)
  {

    //Example12 : A true bar graph


 // Draw the bar graph
 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,80);




  }
  if(($_POST['graph_type'])==17)
  {

    // Example17 :


 // Draw the line graph
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

  }
  // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",7);
 $Test->drawLegend($length-18,20,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);

 $Test->drawTitle(60,22,$graph['year'],50,50,50,585);
 $Test->Render("images/pChart/image_".$image."_".$z.".png");
}


?>