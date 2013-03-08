<?php
include "config.php";

$sql="SELECT fio FROM kadri WHERE id=".$_GET['id_kadri']."";
$mysql=mysql_query($sql);
$name=mysql_fetch_row($mysql);
$sql1="SELECT name FROM time_intervals WHERE id=".$_GET['id_year']."";
$mysql1=mysql_query($sql1);
$year=mysql_fetch_row($mysql1);
//--------------------------------------------1 Р Р°Р·РґРµР»---------------------------------------
//--------------------------------------------РџСЂРѕСЃРјРѕС‚СЂ---------------------------------------
//----------------------------------------Р’С‹РІРѕРґ РїР»Р°РЅРѕРІС‹С… Р·РЅР°С‡РµРЅРёР№----------------------------
if  (isset($_GET['id_razdel1']))
{
$sqlp1="SELECT * FROM plan WHERE id_semestr=1 and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$plan_zn1=mysql_query($sqlp1);
$resp1=mysql_fetch_array($plan_zn1);

$sqlp2="SELECT * FROM plan WHERE id_semestr=2 and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$plan_zn2=mysql_query($sqlp2);
$resp2=mysql_fetch_array($plan_zn2);
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
          $up="UPDATE `fact` SET `$n` = '$p' WHERE `id` = $id LIMIT 1;";
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
          $up="UPDATE `fact` SET `$n` = '$p' WHERE `id_month` = $i and `id_kadri` =".$_GET['id_kadri']." and `id_year` =".$_GET['id_year']." LIMIT 1;";
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
$sql="SELECT * FROM uch_org_rab WHERE id=".$_GET['id']."";
$uch_org_rab=mysql_query($sql);
$line=mysql_fetch_array($uch_org_rab);
 }

  if (isset($_GET['id_razdel2']))
   {

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
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO uch_org_rab (id_kadri,id_year,id_vidov_rabot, prim, srok_vipolneniya, kol_vo_plan, vid_otch, otmetka) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['delete_rab']."',
	   '".$_POST['pri']."','".$_POST['srok']."','".$_POST['plan']."','".$_POST['otch']."','".$_POST['danet']."')");
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
    $srok_vipolneniya=$_POST['srok'];
    $kol_vo_plan=$_POST['plan'];
    $vid_otch=$_POST['otch'];
    $otmetka=$_POST['danet'];

   if (!($_POST['delete_rab']!='' && $_POST['plan']!='' && $_POST['srok']!='' && $_POST['otch']!='' && $_POST['danet']!='' && $_POST['pri']!=''))
    {
    $empt=true;

    }
    else
    {
    $sqlup="UPDATE uch_org_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',id_vidov_rabot='".$rab."',prim='".$prim."',srok_vipolneniya='".$srok_vipolneniya."',kol_vo_plan='".$kol_vo_plan."',vid_otch='".$vid_otch."',otmetka='".$otmetka."' WHERE id=$i";
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
   	$sql1="SELECT * FROM uch_org_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
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
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO nauch_met_rab (id_kadri,id_year,id_vidov_rabot, prim, srok_vipolneniya, kol_vo_plan, vid_otch, kol_vo) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['delete_rab']."',
	   '".$_POST['pri']."','".$_POST['srok']."','".$_POST['timeplan']."','".$_POST['otch']."','".$_POST['plan']."')");
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
    $srok_vipolneniya=$_POST['srok'];
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
    	if(isset($_GET['delete3']))
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
//------------------------------------------Р—Р°РїСЂРѕСЃ РґР»СЏ РІС‹РІРѕРґР° СЃРїСЂР°РІРѕС‡РЅРёРєР° СЂР°Р±РѕС‚------------------------
 if (isset($_GET['update4']))
 {
$sql="SELECT * FROM uch_vosp_rab WHERE id=".$_GET['id']."";
$uch_vosp_rab=mysql_query($sql);
$line=mysql_fetch_array($uch_vosp_rab);
 }

  if (isset($_GET['id_razdel4']))
   {


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
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO uch_vosp_rab (id_kadri,id_year,id_vidov_rabot, prim, srok_vipolneniya, kol_vo_plan, gruppa, otmetka) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['delete_rab']."',
	   '".$_POST['prim']."','".$_POST['srok']."','".$_POST['plan']."','".$_POST['group']."','".$_POST['danet']."')");
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
    $srok_vipolneniya=$_POST['srok'];
    $kol_vo_plan=$_POST['plan'];
    $group=$_POST['group'];
    $otmetka=$_POST['danet'];

   if (!($_POST['delete_rab']!='' && $_POST['plan']!='' && $_POST['srok']!='' && $_POST['group']!='' && $_POST['danet']!='' && $_POST['prim']!=''))
    {
    $empt=true;

    }
    else
    {
    $sqlup="UPDATE uch_vosp_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',id_vidov_rabot='".$rab."',prim='".$prim."',srok_vipolneniya='".$srok_vipolneniya."',kol_vo_plan='".$kol_vo_plan."',gruppa='".$group."',otmetka='".$otmetka."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete4']))
    	{
    	$sqldel="DELETE FROM uch_vosp_rab WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}
 //============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
   	$sql1="SELECT * FROM uch_vosp_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
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

   	$empt_add=false;
   	if (isset($_POST['add5']))
    {

    if (!($_POST['rab_name']!=''&& $_POST['rab_vid']!='' && $_POST['kol']!=''))

    {

    $empt_add=true;

    }
    else
    {
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO perechen_nauch_rab (id_kadri,id_year,name, volume, type) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['rab_name']."',
	   '".$_POST['kol']."','".$_POST['rab_vid']."')");
       $result1=mysql_query($ins1);
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
    $sqlup="UPDATE perechen_nauch_rab SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',name='".$rab_name."',volume='".$kol."',type='".$rab_vid."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete5']))
    	{
    	$sqldel="DELETE FROM perechen_nauch_rab WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}

//============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
   $sql1="SELECT * FROM perechen_nauch_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and type='РџРµС‡Р°С‚РЅР°СЏ'";
   $sql2="SELECT * FROM perechen_nauch_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." and type='Р СѓРєРѕРїРёСЃРЅР°СЏ'";
   $perechen_nauch_rab_p=mysql_query($sql1);
   $perechen_nauch_rab_r=mysql_query($sql2);
   $check1=mysql_num_rows($perechen_nauch_rab_p);
   $check2=mysql_num_rows($perechen_nauch_rab_r);
   //------------------------------------------------------------------------------------------

 }
//----------------------------------------------6 Р Р°Р·РґРµР»---------------------------------------------
//------------------------------------------Р—Р°РїСЂРѕСЃ РґР»СЏ РІС‹РІРѕРґР° СЃРїСЂР°РІРѕС‡РЅРёРєР° СЂР°Р±РѕС‚------------------------
 if (isset($_GET['update6']))
 {
$sql="SELECT * FROM izmen WHERE id=".$_GET['id']."";
$izmen=mysql_query($sql);
$line=mysql_fetch_array($izmen);
 }

  if (isset($_GET['id_razdel6']))
   {


   	$empt_add=false;
   	if (isset($_POST['add6']))
    {
      if (!($_POST['razdel']!='' && $_POST['izmenenie']!='' && $_POST['zav']!='' && $_POST['prep']!='' && $_POST['danet']!=''))
    {
    $empt_add=true;

    }
    else
    {
     //Р’СЃС‚Р°РІР»СЏРµРј РЅРѕРІС‹Рµ РґР°РЅРЅС‹Рµ РІ Р±Р°Р·Сѓ
       $ins1=("INSERT INTO izmen (id_kadri,id_year, razdel, izmenenie, zav, prep, otmetka) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$_POST['razdel']."',
	   '".$_POST['izmenenie']."','".$_POST['zav']."','".$_POST['prep']."','".$_POST['danet']."')");
       $result1=mysql_query($ins1);
    }

    }

   //---------------------------------------РђРїРґРµР№С‚ РґР°РЅРЅС‹С…---------------------------------
 	$empt=false;
 	if (isset($_POST['update6']))
    {


    $i=$_POST['a'];

    $razdel=$_POST['razdel'];
    $izmenenie=$_POST['izmenenie'];
    $zav=$_POST['zav'];
    $prep=$_POST['prep'];
    $otmetka=$_POST['danet'];

    if (!($_POST['razdel']!='' && $_POST['izmenenie']!='' && $_POST['zav']!='' && $_POST['prep']!='' && $_POST['danet']!=''))
    {
    $empt=true;

    }
    else
    {
    $sqlup="UPDATE izmen SET id_kadri='".$_GET['id_kadri']."',id_year='".$_GET['id_year']."',razdel='".$razdel."',izmenenie='".$izmenenie."',zav='".$zav."',prep='".$prep."',otmetka='".$otmetka."' WHERE id=$i";
    mysql_query($sqlup) or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё".mysql_error());;
    }




    }



//-------------------------------------------------РЈРґР°Р»РµРЅРёРµ
    	if(isset($_GET['delete6']))
    	{
    	$sqldel="DELETE FROM izmen WHERE id=".$_GET['id']."";
    	$mysqldel=mysql_query($sqldel)or die("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ".mysql_error());

    	}

//============================================РџСЂРѕСЃРјРѕС‚СЂ=====================================
     $sql="SELECT * FROM izmen WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
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