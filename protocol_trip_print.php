<?php
include ('authorisation.php');
include ('master_page_short.php');

$trip_id=0;

if (isset($_GET['trip_id'])) {$trip_id=intval($_GET['trip_id']);}

if ($trip_id<=0) {echo '<div class=warning>не выбрана выписка.</div>';}
else {
    //$confirm_trip_id= //проверка существования протокола
    $num_trip=0;    //номер протокола
    $date_trip='';      //дата протокола
    $kadri_fio='';      //автор выступления
    $text_content='';   //слушали
    $opinion_text='';   //постановили
    $opinion_text_add='';   //постановили, дополнение
    //постановили
    $query_main='
    SELECT p.num,
       DATE_FORMAT(p.date_text,"%d.%m.%Y") date_text,
       k.fio_short,
       pd.text_content,
       pd.opinion_text as opinion_text_add,
       po.name as opinion_text 
    FROM    (   (   (   protocol_trips pt
                   INNER JOIN
                      protocol_details pd
                   ON (pt.section_id = pd.section_id))
               RIGHT OUTER JOIN
                  protocols p
               ON (p.id = pd.protocol_id) AND (pt.protocol_id = p.id))
           LEFT OUTER JOIN
              protocol_opinions po
           ON (po.id = pd.opinion_id))
       LEFT OUTER JOIN
          kadri k
       ON (k.id = pd.kadri_id)
    WHERE (pt.id = '.$trip_id.')';
    $res=mysql_query($query_main);
    $a=mysql_fetch_assoc($res);
    $num_trip=$a['num'];
    $date_trip=$a['date_text'];
    $kadri_fio=$a['fio_short'];
    $text_content=$a['text_content'];
    $opinion_text=$a['opinion_text'];
    $opinion_text_add=$a['opinion_text_add'];
    //echo $query_main;
    ?>
    <div align=center>
    ВЫПИСКА ИЗ ПРОТОКОЛА <br>
    профсоюзного собрания кафедры АСУ
    </div>
    <table width=100% border=0><tr>
        <td align=left>№ <?php echo $num_trip;?></td>
        <td align=right>от <?php echo $date_trip;?></td>
    </tr></table>
    <p>СЛУШАЛИ: <b><?php echo $kadri_fio; ?></b>: <?php echo $text_content; ?></p>
    
    <p>ПОСТАНОВИЛИ: <?php echo $opinion_text.$opinion_text_add; ?></p>
        <?php
$query='SELECT k.fio_short AS fio,
       d.name AS dolgnost,
       ptd.trip_count,
       DATE_FORMAT(ptd.date_start,"%d.%m.%Y") date_start,
       DATE_FORMAT(ptd.date_end,"%d.%m.%Y") date_end,
       ptd.trip_cost,
       ptd.dotation,
       th.name AS house_type
  FROM    (   (   kadri k
               RIGHT OUTER JOIN
                  protocol_trip_details ptd
               ON (k.id = ptd.kadri_id))
           LEFT OUTER JOIN
              trip_houses th
           ON (th.id = ptd.house_type))
       LEFT OUTER JOIN
          dolgnost d
       ON (d.id = k.dolgnost)
    where ptd.trip_id="'.$trip_id.'"';
    
    //echo $query;
    $res=mysql_query($query);
    $i=1;$sum=0;
    if (mysql_numrows($res)>0) {
    ?>
    <table width=100% border=1 cellpadding=2 cellspacing=0><tr class=title>
        <td>№ п\п</td>
        <td>Ф.И.О.</td>
        <td>должность</td>
        <td>кол-во путевок</td>
        <td>полная стои.путевки</td>
        <td>дотация</td>
        <td>дом</td>
    </tr>    
    <?php
        while ($a=mysql_fetch_assoc($res))
        {
            
            echo '<tr>
        <td>'.$i.'</td>
        <td>'.$a['fio'].'</td>
        <td>'.$a['dolgnost'].'</td>
        <td>'.$a['trip_count'].' '.$a['date_start'].'-'.$a['date_end'].'</td>
        <td>'.$a['trip_cost'].'</td>
        <td>'.$a['dotation'].'</td>
        <td>'.$a['house_type'].'</td>
            </tr>';
            $sum+=$a['trip_cost'];
            $i++;
        }
    ?>
    </table>
    <?php
        }
    else {echo '<div class=warning>записей по сотрудникам в выписке к протоколу не найдено</div>';}
        ?>
    
    <p>Итого:  <?php echo $sum;?></p>
    <table border=0 cellpadding=10>
        <tr><td width=400>Зав.каф. АСУ</td><td>Г.Г.Куликов</td></tr>
        <tr><td>Зам.зав.каф.</td><td>Р.Р.Еникеев</td></tr>
        <tr><td>Профорг кафедры</td><td>Е.Е.Попкова</td></tr>
    </table>
    
    <?php
    }
?>