<?php
$files_path='../../';
include $files_path.'authorisation.php';

function tortf($str) {
  $s = '';
  for ($i = 0; $i < strlen($str); $i++)
    $s .= (ord($str[$i]) > 127) ? sprintf("\\'%02x",ord($str[$i])) : $str[$i];
  return $s;
}
	
$f_name_tml='kadri_print.rtf';
$f_name_out='rtf_kadri_form.rtf';



$item_id=0;
if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) {$item_id=intval($_GET['item_id']);}


if ($item_id<=0)	{die('не выбрана запись.');}
else //заполняем бланк данными, если режим печати
{
	$query='SELECT k.photo,
       k.fio,
       p.name AS pol_name,
       s.name AS step_name,
       z.name AS zv_name,
       fs.name AS fs_name,
       k.birth_place,
       k.date_rogd,
       k.nation,
       k.social,
       l.name AS lang_name,
       k.nagradi,
       k.add_home,
       (select count(*) from works w where w.kadri_id=k.id) as izdan_cnt,
       (select count(*) from works w
	  left join izdan i on w.izdan_id=i.id
	  where i.kadri_id=k.id and i.type_book=21) as invent_cnt
  FROM pol p
           RIGHT OUTER JOIN kadri k
              ON (p.id = k.pol)
          LEFT OUTER JOIN language l
             ON (l.id = k.language1) 
         LEFT OUTER JOIN zvanie z
            ON (z.id = k.zvanie)
        LEFT OUTER JOIN stepen s
           ON (s.id = k.stepen)
       LEFT OUTER JOIN family_status fs
          ON (fs.id = k.family_status)
 WHERE k.id ="'.$item_id.'" limit 0,1'; 
  $res=mysql_query($query);
 
  
  if (mysql_num_rows($res)<=0) {die('запрос не нашел ни одной записи.');}
  else
  {
    $res_edit=mysql_fetch_array($res);
 
	header("Content-type: application/msword");
	header("Content-Disposition: attachment; filename=$f_name_out");

	
	$doc = file_get_contents($f_name_tml);
	
	//убираем лишние проблемы в слове
	$res_edit['fio']=trim($res_edit['fio']);
	while (strpos($res_edit['fio'],'  ')!==false)
	    $res_edit['fio']=str_replace('  ',' ',$res_edit['fio']);
	
	//разбиваем ФИО на составные части
	$fio_arr=explode(' ', $res_edit['fio']);
	$fio_s=$fio_arr[0];
	$fio_n=$fio_arr[1];
	$fio_l=$fio_arr[2];
	
	$data = array('fio_s'=>$fio_s,
		      'fio_n'=>$fio_n,
		      'fio_l'=>$fio_l,
		      'k_sex'=>$res_edit['pol_name'],
		      'born_date'=>$res_edit['date_rogd'],             
		      'born_place'=>$res_edit['birth_place'],
		      'k_nation'=>$res_edit['nation'],
		      'k_social'=>$res_edit['social'],
		      'k_edu'=>'высшее',              
		      'k_lang'=>$res_edit['lang_name'],
		      'k_stepen'=>$res_edit['step_name'],
		      'k_zvanie'=>$res_edit['zv_name'],
		      'izdan_cnt'=>$res_edit['izdan_cnt'].' публикаций, '.$res_edit['invent_cnt'].' изобретений',
		      'awards'=>$res_edit['nagradi'],
		      'family_status'=>$res_edit['fs_name'],
		      'home_addr'=>$res_edit['add_home'],
		      'date_fill'=>date("« d » m.Y")
		      );
	
	$doc = preg_replace('#\\\\{(.*?)\\\\}#se','isset($data["$1"]) ? tortf($data["$1"]) : "$0"',$doc);
	echo $doc;
   }
}

/*
 
{k_photo} 	фотография
{fio_s}	фамилия
{fio_n}	имя
{fio_l}	отчество
{k_sex}	пол
{born_date}	дата рождения
{born_place}	место рождения
{k_nation}	национальность
{k_social}	соц.статус
{k_edu}		образование
{k_lang}	ин.язык
{k_stepen}	уч.степень
{k_zvanie}	уч.звание
{izdan_cnt}	число публикаций
{awards}	награды
{family_status}	сем.положение
{home_addr}	домашний адрес
{date_fill}	дата заполнения
{k_img}		фото_сотрудника
 
			       */

?>