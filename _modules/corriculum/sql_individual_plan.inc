<?php
include_once "config.php";

include_once $portal_path."authorisation.php";
include_once $portal_path."master_page_short.php";

$sql="SELECT fio FROM kadri WHERE id=".$_GET['id_kadri']."";
$mysql=mysql_query($sql);
$name=mysql_fetch_row($mysql);
$sql1="SELECT name FROM time_intervals WHERE id=".$_GET['id_year']."";
$mysql1=mysql_query($sql1);
$year=mysql_fetch_row($mysql1);
//--------------------------------------------1 Р Р°Р·РґРµР»---------------------------------------
//--------------------------------------------РџСЂРѕСЃРјРѕС‚СЂ---------------------------------------
//----------------------------------------Р’С‹РІРѕРґ РїР»Р°РЅРѕРІС‹С… Р·РЅР°С‡РµРЅРёР№----------------------------
if  (isset($_GET['id_razdel1'])) {
	$sql1	= "select 	sum(hours_lection) as lects, 
						sum(hours_practice) as practs,
						sum(hours_laboratory) as lab,
						sum(hours_consulting) as consult,
						sum(hours_cours_project) as kurs_proj,
						sum(hours_credits) as tests,
						sum(hours_exams) as exams, 
						sum(hours_work_pract) as work_pract,
						sum(hours_recence) as recenz,
						sum(hours_consulting_dipl) as consult_dipl,
						sum(hours_gek) as gek,
						sum(hours_duty) as duty,
						sum(hours_aspirants_manage) as aspir_manage,
						sum(hours_aspirants) as aspirants,
						sum(hours_rgr) as rgr from ang_corriculums where (part_id = 1 and kadri_id = ".$_GET['id_kadri']." and year_id = ".$_GET['id_year'].")";
	$res1	= mysql_query($sql1) or die (mysql_error());
	$plan_1	= mysql_fetch_row($res1);
	
	$sql2	= "select 	sum(hours_lection) as lects, 
						sum(hours_practice) as practs,
						sum(hours_laboratory) as lab,
						sum(hours_consulting) as consult,
						sum(hours_cours_project) as kurs_proj,
						sum(hours_credits) as tests,
						sum(hours_exams) as exams, 
						sum(hours_work_pract) as work_pract,
						sum(hours_recence) as recenz,
						sum(hours_consulting_dipl) as consult_dipl,
						sum(hours_gek) as gek,
						sum(hours_duty) as duty,
						sum(hours_aspirants_manage) as aspir_manage,
						sum(hours_aspirants) as aspirants,
						sum(hours_rgr) as rgr from ang_corriculums where (part_id = 2 and kadri_id = ".$_GET['id_kadri']." and year_id = ".$_GET['id_year'].")";
	$res2	= mysql_query($sql2) or die (mysql_error());
	$plan_2	= mysql_fetch_row($res2);	
	/*
$sqlp1="SELECT * FROM plan WHERE id_semestr=1 and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$plan_zn1=mysql_query($sqlp1);
$resp1=mysql_fetch_array($plan_zn1);

$sqlp2="SELECT * FROM plan WHERE id_semestr=2 and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$plan_zn2=mysql_query($sqlp2);
$resp2=mysql_fetch_array($plan_zn2);
	*/
//--------------------------------------------Р’С‹РІРѕРґ РЅР°Р·РІР°РЅРёР№ СЂР°Р±РѕС‚--------------------------------
$sql="SELECT * FROM spravochnik_uch_rab ORDER BY id";
$uch_rab=mysql_query($sql);
}
//=======================================Р’РІРѕРґ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ СѓРґР°Р»РµРЅРёРµ=================================
if (isset($_GET['id_razdel_red']))
{
if (isset($_POST['main']))
 {



  //---------------------------Р¤Р°РєС‚РёС‡РµСЃРєРёРµ Р·РЅР°С‡РµРЅРёСЏ---------------------
  $sql="SELECT * FROM fact WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
  $check=mysql_query($sql);

  $sql="SELECT * FROM spravochnik_uch_rab ORDER BY id";
  $mysql=mysql_query($sql);



  if (!mysql_num_rows($check))
  {
  //Insert
   $i=1;

  while ($i!==12)
  {
     $insert="INSERT INTO fact (id_month,id_kadri,id_year) VALUES ('$i','".$_GET['id_kadri']."','".$_GET['id_year']."')";
     $ins=mysql_query($insert);
     $id=mysql_insert_id();


     $mysql=mysql_query($sql);
    while ($name=mysql_fetch_row($mysql))
     {
        $n=$name[0];
        $p=$_POST['id'.$n.'m'.$i.''];
          $up="UPDATE `fact` SET `rab_".$n."` = '$p' WHERE `id` = $id LIMIT 1;";
          mysql_query($up);

     }
     ++$i;

   }

  }
  else
  {
  //UPDATE
  $i=1;

  while ($i!==12)
  {


     $mysql=mysql_query($sql);
    while ($name=mysql_fetch_row($mysql))
     {
        $n=$name[0];
        $p=$_POST['id'.$n.'m'.$i.''];
          $up="UPDATE `fact` SET `rab_".$n."` = '$p' WHERE `id_month` = $i and `id_kadri` =".$_GET['id_kadri']." and `id_year` =".$_GET['id_year']." LIMIT 1;";
          mysql_query($up);

     }
     ++$i;

   }

  }

 }
//----------------------------------------------Р’С‹РІРѕРґ РґР°РЅРЅС‹С…--------------------------------------
$sqlp1="SELECT * FROM plan WHERE id_semestr=1 and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
 $mysqlp1=mysql_query($sqlp1);
  $resp1=mysql_fetch_array($mysqlp1);

 $sqlp2="SELECT * FROM plan WHERE id_semestr=2 and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
 $mysqlp2=mysql_query($sqlp2);
  $resp2=mysql_fetch_array($mysqlp2);

 $sql="SELECT * FROM spravochnik_uch_rab ORDER BY id";
 $spravochnik_uch_rab_sel_fact=mysql_query($sql);
}

//---------------------------------------------2РѕР№ СЂР°Р·РґРµР»------------------------------------------
if (isset($_GET['add']))

{
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=2 ORDER BY id ";
$spr_vid=mysql_query($spravochnik);


}
//------------------------------------------Р—Р°РїСЂРѕСЃ РґР»СЏ РІС‹РІРѕРґР° СЃРїСЂР°РІРѕС‡РЅРёРєР° СЂР°Р±РѕС‚------------------------
 if (isset($_GET['update']))
 {
$sql="SELECT id,id_vidov_rabot,prim,srok_vipolneniya,kol_vo_plan,vid_otch,id_otmetka, (SELECT name FROM otmetka WHERE id=id_otmetka) AS otm FROM uch_org_rab WHERE id=".$_GET['id']."";
$uch_org_rab=mysql_query($sql);
$line=mysql_fetch_array($uch_org_rab);
 }

  if (isset($_GET['id_razdel2']))
   {
    $duplicat=false;
   	$empt_add=false;
   	if (isset($_POST['add']))
    {
    if (isset($_POST['delete_rab']))
    {
    	if (!($_POST['plan']!='' && $_POST['srok']!='' && $_POST['otch']!='' && $_POST['danet']!='' && $_POST['pri']!=''))
    {
    $empt_add=true;

    }
    else
    {
    $sql_org="SELECT * FROM uch_org_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and id_vidov_rabot=".$_POST['delete_rab']."";
    $org_rab=mysql_query($sql_org);
    if(mysql_num_rows($org_rab))
    {

     $duplicat=true;
    }


     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO uch_org_rab (id_kadri,id_year,id_vidov_rabot, prim, srok_vipolneniya, kol_vo_plan, vid_otch, id_otmetka) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['delete_rab']."',
	   '".$_POST['pri']."','".f_ri(DateTimeCustomConvert($_POST['srok'],'d','rus2mysql'))."','".$_POST['plan']."','".$_POST['otch']."','".$_POST['danet']."')");
       $result1=mysql_query($ins1);

    }
    }
    else $empt_add=true;
    }

   //---------------------------------------РђРїРґРµР№С‚ РґР°РЅРЅС‹С…---------------------------------

 	$empt=false;
 	if (isset($_POST['update']))
    {


    $i=$_POST['a'];

    $rab=$_POST['delete_rab'];
    $prim=$_POST['pri'];
    $srok_vipolneniya=f_ri(DateTimeCustomConvert($_POST['srok'],'d','rus2mysql'));
    $kol_vo_plan=$_POST['plan'];
    $vid_otch=$_POST['otch'];
    $otmetka=$_POST['danet'];

   if (!($_POST['delete_rab']!='' && $_POST['plan']!='' && $_POST['srok']!='' && $_POST['otch']!='' && $_POST['danet']!='' && $_POST['pri']!=''))
    {
    $empt=true;

    }
    else
    {

    $sqlup="UPDATE uch_org_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',id_vidov_rabot='".$rab."',prim='".$prim."',srok_vipolneniya='".$srok_vipolneniya."',kol_vo_plan='".$kol_vo_plan."',vid_otch='".$vid_otch."',id_otmetka='".$otmetka."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete']))
    	{
    	$sqldel="DELETE FROM uch_org_rab WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}
//============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
   	$sql1="SELECT id,id_vidov_rabot,prim,srok_vipolneniya,kol_vo_plan,vid_otch, (SELECT name FROM otmetka WHERE id=id_otmetka) AS otm FROM uch_org_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
    $uch_org_rab=mysql_query($sql1);
    $check=mysql_num_rows($uch_org_rab);
   //------------------------------------------------------------------------------------------

 }

//----------------------------------------3РёР№ СЂР°Р·РґРµР»----------------------------------------
 if (isset($_GET['add3']))

{
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=3 ORDER BY id ";
$spr_vid=mysql_query($spravochnik);


}
//------------------------------------------Р—Р°РїСЂРѕСЃ РґР»СЏ РІС‹РІРѕРґР° СЃРїСЂР°РІРѕС‡РЅРёРєР° СЂР°Р±РѕС‚------------------------
 if (isset($_GET['update3']))
 {
$sql="SELECT * FROM nauch_met_rab WHERE id=".$_GET['id']."";
$nauch_met_rab=mysql_query($sql);
$line=mysql_fetch_array($nauch_met_rab);
 }

  if (isset($_GET['id_razdel3']))
   {
    $duplicat=false;
   	$empt_add=false;
   	if (isset($_POST['add3']))
        {
     if (isset($_POST['delete_rab']))
           {
    	if (!($_POST['plan']!='' && $_POST['timeplan']!='' && $_POST['srok']!='' && $_POST['otch']!='' && $_POST['pri']!=''))

                  {

    $empt_add=true;

                 }
    else
                 {
    $sql_nauch="SELECT * FROM nauch_met_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and id_vidov_rabot=".$_POST['delete_rab']."";
    $nauch_rab=mysql_query($sql_nauch);
    if(mysql_num_rows($nauch_rab))
        {
        $duplicat=true;

        }
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO nauch_met_rab (id_kadri,id_year,id_vidov_rabot, prim, srok_vipolneniya, kol_vo_plan, vid_otch, kol_vo) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['delete_rab']."',
	   '".$_POST['pri']."','".f_ri(DateTimeCustomConvert($_POST['srok'],'d','rus2mysql'))."','".$_POST['timeplan']."','".$_POST['otch']."','".$_POST['plan']."')");
       $result1=mysql_query($ins1);
                 }
          }
        else $empt_add=true;
        }

   //---------------------------------------РђРїРґРµР№С‚ РґР°РЅРЅС‹С…---------------------------------

 	$empt=false;
 	if (isset($_POST['update3']))
    {


    $i=$_POST['a'];

    $rab=$_POST['delete_rab'];
    $prim=$_POST['pri'];
    $srok_vipolneniya=f_ri(DateTimeCustomConvert($_POST['srok'],'d','rus2mysql'));
    $kol_vo_plan=$_POST['plan'];
    $vid_otch=$_POST['otch'];
    $timeplan=$_POST['timeplan'];

   if (!($_POST['delete_rab']!='' && $_POST['plan']!='' && $_POST['srok']!='' && $_POST['otch']!='' && $_POST['timeplan']!='' && $_POST['pri']!=''))
    {
    $empt=true;

    }
    else
    {
    $sqlup="UPDATE nauch_met_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',id_vidov_rabot='".$rab."',prim='".$prim."',srok_vipolneniya='".$srok_vipolneniya."',kol_vo_plan='".$timeplan."',vid_otch='".$vid_otch."',kol_vo='".$kol_vo_plan."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete']))
    	{
    	$sqldel="DELETE FROM nauch_met_rab WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}
 //============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
   	$sql1="SELECT * FROM nauch_met_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
    $nauch_met_rab=mysql_query($sql1);
    $check=mysql_num_rows($nauch_met_rab);
   //------------------------------------------------------------------------------------------
 }


//------------------------------------------------4 Р Р°Р·РґРµР»-----------------------------------
if (isset($_GET['add4']))

{
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=4 ORDER BY id ";
$spr_vid=mysql_query($spravochnik);


}
//------------------------------------------Р—Р°РїСЂРѕСЃ РґР»Р° СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ------------------------
 if (isset($_GET['update4']))
 {
$sql="SELECT id,id_vidov_rabot,prim,srok_vipolneniya,kol_vo_plan,id_study_groups,id_otmetka, (SELECT name FROM otmetka WHERE id=id_otmetka) AS otm,(SELECT name FROM study_groups WHERE id=id_study_groups) AS st_group FROM uch_vosp_rab WHERE id=".$_GET['id']."";
$uch_vosp_rab=mysql_query($sql);
$line=mysql_fetch_array($uch_vosp_rab);
 }

  if (isset($_GET['id_razdel4']))
   {

    $duplicat=false;
   	$empt_add=false;
   	if (isset($_POST['add4']))
    {
    if (isset($_POST['delete_rab']))
     {
    	if (!($_POST['plan']!='' && $_POST['srok']!='' && $_POST['group']!='' && $_POST['danet']!='' && $_POST['prim']!=''))
    {
    $empt_add=true;

    }
    else
    {
    $sql_vosp="SELECT * FROM uch_vosp_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and id_vidov_rabot=".$_POST['delete_rab']."";
    $vosp_rab=mysql_query($sql_vosp);
    if(mysql_num_rows($vosp_rab))
        {
        $duplicat=true;

        }
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO uch_vosp_rab (id_kadri,id_year,id_vidov_rabot, prim, srok_vipolneniya, kol_vo_plan, id_study_groups, id_otmetka) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['delete_rab']."',
	   '".$_POST['prim']."','".f_ri(DateTimeCustomConvert($_POST['srok'],'d','rus2mysql'))."','".$_POST['plan']."','".$_POST['group']."','".$_POST['danet']."')");
       $result1=mysql_query($ins1);
    }
    }
    else $empt_add=true;
    }

   //---------------------------------------РђРїРґРµР№С‚ РґР°РЅРЅС‹С…---------------------------------
 	$empt=false;
 	if (isset($_POST['update4']))
    {


    $i=$_POST['a'];

    $rab=$_POST['delete_rab'];
    $prim=$_POST['prim'];
    $srok_vipolneniya=f_ri(DateTimeCustomConvert($_POST['srok'],'d','rus2mysql'));
    $kol_vo_plan=$_POST['plan'];
    $group=$_POST['group'];
    $otmetka=$_POST['danet'];

   if (!($_POST['delete_rab']!='' && $_POST['plan']!='' && $_POST['srok']!='' && $_POST['group']!='' && $_POST['danet']!='' && $_POST['prim']!=''))
    {
    $empt=true;

    }
    else
    {
    $sqlup="UPDATE uch_vosp_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',id_vidov_rabot='".$rab."',prim='".$prim."',srok_vipolneniya='".$srok_vipolneniya."',kol_vo_plan='".$kol_vo_plan."',id_study_groups='".$group."',id_otmetka='".$otmetka."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete']))
    	{
    	$sqldel="DELETE FROM uch_vosp_rab WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}
 //============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
   	$sql1="SELECT id,id_vidov_rabot,prim,srok_vipolneniya,kol_vo_plan, (SELECT name FROM otmetka WHERE id=id_otmetka) AS otm, (SELECT name FROM study_groups WHERE id=id_study_groups) AS st_group FROM uch_vosp_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
    $uch_vosp_rab=mysql_query($sql1);
    $check=mysql_num_rows($uch_vosp_rab);
   //------------------------------------------------------------------------------------------

 }

//------------------------------------------------5 Р Р°Р·РґРµР»-------------------------------------------
//------------------------------------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ ------------------------------------
 if (isset($_GET['update5']))
 {
$sql="SELECT * FROM perechen_nauch_rab WHERE id=".$_GET['id']."";
$perechen_nauch_rab=mysql_query($sql);
$line=mysql_fetch_array($perechen_nauch_rab);
 }
//------------------------------------------------Р”РѕР±Р°РІР»РµРЅРёРµ СЂР°Р±РѕС‚С‹------------------------------------
  if (isset($_GET['id_razdel5']))
   {
    $duplicat=false;
   	$empt_add=false;
   	if (isset($_POST['add5']))
    {

    if (!($_POST['rab_name']!=''&& $_POST['rab_vid']!='' && $_POST['kol']!=''))

    {

    $empt_add=true;

    }
    else
    {
    $sql_perechen="SELECT * FROM perechen_nauch_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and name='".$_POST['rab_name']."' and volume='".$_POST['kol']."'";
    $perechen_rab=mysql_query($sql_perechen);
    if(mysql_num_rows($perechen_rab))
        {
        $duplicat=true;

        }
        else
        {
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO perechen_nauch_rab (id_kadri,id_year,name, volume, id_type_nauch_rab) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['rab_name']."',
	   '".$_POST['kol']."','".$_POST['rab_vid']."')");
       $result1=mysql_query($ins1);
       }
    }


    }

   //---------------------------------------РђРїРґРµР№С‚ РґР°РЅРЅС‹С…---------------------------------

 	$empt=false;
 	if (isset($_POST['update5']))
    {


    $i=$_POST['a'];
    $rab_name=$_POST['rab_name'];
    $rab_vid=$_POST['rab_vid'];
    $kol=$_POST['kol'];


   if (!($_POST['rab_name']!='' && $_POST['rab_vid']!='' && $_POST['kol']!=''))
    {
    $empt=true;

    }
    else
    {

    $sqlup="UPDATE perechen_nauch_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',name='".$rab_name."',volume='".$kol."',id_type_nauch_rab='".$rab_vid."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());

    }
    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete']))
    	{
    	$sqldel="DELETE FROM perechen_nauch_rab WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}

//============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
   $sql1="SELECT * FROM perechen_nauch_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and id_type_nauch_rab='1'";
   $sql2="SELECT * FROM perechen_nauch_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and id_type_nauch_rab='2'";
   $perechen_nauch_rab_p=mysql_query($sql1);
   $perechen_nauch_rab_r=mysql_query($sql2);
   $check1=mysql_num_rows($perechen_nauch_rab_p);
   $check2=mysql_num_rows($perechen_nauch_rab_r);
   //------------------------------------------------------------------------------------------

 }
//----------------------------------------------6 Р Р°Р·РґРµР»---------------------------------------------
 if (isset($_GET['update6']))
 {
$sql="SELECT id,razdel,izmenenie,zav,prep,id_otmetka, (SELECT name FROM otmetka WHERE id=id_otmetka) AS otm FROM izmen WHERE id=".$_GET['id']."";
$izmen=mysql_query($sql);
$line=mysql_fetch_array($izmen);
 }

  if (isset($_GET['id_razdel6']))
   {

    $duplicat=false;
   	$empt_add=false;
   	if (isset($_POST['add6']))
    {
      if (!($_POST['razdel']!='' && $_POST['izmenenie']!='' && $_POST['zav']!='' && $_POST['prep']!='' && $_POST['danet']!=''))
    {
    $empt_add=true;

    }
    else
    {
    $sql_izmen="SELECT * FROM izmen WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and razdel='".$_POST['razdel']."' and izmenenie='".$_POST['izmenenie']."'";
    $izmen_rab=mysql_query($sql_izmen);
    if(mysql_num_rows($izmen_rab))
        {
        $duplicat=true;

        }
     else
     {
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO izmen (id_kadri,id_year, razdel, izmenenie, zav, prep, id_otmetka) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['razdel']."',
	   '".$_POST['izmenenie']."','".f_ri(DateTimeCustomConvert($_POST['zav'],'d','rus2mysql'))."','".f_ri(DateTimeCustomConvert($_POST['prep'],'d','rus2mysql'))."','".$_POST['danet']."')");
       $result1=mysql_query($ins1);
    }
    }

    }

   //---------------------------------------РђРїРґРµР№С‚ РґР°РЅРЅС‹С…---------------------------------
 	$empt=false;
 	if (isset($_POST['update6']))
    {


    $i=$_POST['a'];

    $razdel=$_POST['razdel'];
    $izmenenie=$_POST['izmenenie'];
    $zav=f_ri(DateTimeCustomConvert($_POST['zav'],'d','rus2mysql'));
    $prep=f_ri(DateTimeCustomConvert($_POST['prep'],'d','rus2mysql'));
    $otmetka=$_POST['danet'];

    if (!($_POST['razdel']!='' && $_POST['izmenenie']!='' && $_POST['zav']!='' && $_POST['prep']!='' && $_POST['danet']!=''))
    {
    $empt=true;

    }
    else
    {
    $sqlup="UPDATE izmen SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',razdel='".$razdel."',izmenenie='".$izmenenie."',zav='".$zav."',prep='".$prep."',id_otmetka='".$otmetka."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete']))
    	{
    	$sqldel="DELETE FROM izmen WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}

//============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
     $sql="SELECT id,razdel,izmenenie,zav,prep, (SELECT name FROM otmetka WHERE id=id_otmetka) AS otm FROM izmen WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
     $izmen=mysql_query($sql);
     $check=mysql_num_rows($izmen);
   //------------------------------------------------------------------------------------------


 }
//---------------------------------------7РёР№ СЂР°Р·РґРµР»--------------------------------

if (isset($_GET['izmen']))
{

$sql="SELECT * FROM zakl WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$mysql=mysql_query($sql);
$msg=mysql_fetch_row($mysql);

}

if (isset($_GET['id_razdel7']))
{
if (isset($_POST['msg']))
    {

       if ($_POST['id']==null)
       {
       $ins=("INSERT INTO zakl(id_kadri,id_year,msg) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['msg']."')");
       mysql_query($ins);
       }
       else
       {
        $upd="UPDATE zakl SET msg='".$_POST['msg']."' WHERE id='".$_POST['id']."'";
        mysql_query($upd);
       	}
	}

$sql="SELECT * FROM zakl WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$mysql=mysql_query($sql);
$msg=mysql_fetch_row($mysql);

}





?>