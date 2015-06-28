<?php

include 'sql_connect.php';

//открываем заставку, если требуется
$hide_logo=true;

// <abarmin date="04.05.2012">
// а ошибки кто-нибудь показывать пробовал?
if (!isset($_COOKIE['hide_logo'])) {
    $hide_logo = false;
} elseif ($_COOKIE['hide_logo']!=1) {
    $hide_logo=false;
}

// поставил заглушку ошибок
if (@$logo_enable && $logo_path!='' && !$hide_logo)
{
    setcookie('hide_logo',1);
    header('Location:'.$logo_path);
}
if ($site_blocked) {
    header('Location:under_construction.php'); }

// </abarmin>


$pg_title='Новости';

include 'header.php';

$showKadriImg_ifNullBiogImg=true;

if (!isset($_GET['wap'])) { if (!isset($_GET['id']))	{echo $head;} }
else { echo $head_wap;}

if(isset($_GET['id'])) {
    $query='select news.*,users.fio,users.id as user_id from news left join users on users.id=news.user_id_insert where news.id="'.$_GET['id'].'" ';
    $res00=mysql_query ($query);
    if(mysql_num_rows($res00)==1) {
        $g=mysql_fetch_array($res00);

        $e=$g['file'];
        $e=msg_replace($e);

        if (!isset($_GET['save']) && !isset($_GET['print'])) {
            echo "<div style='text-align:right;'>
		 	<a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=doc' title='Выгрузить'>Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
			<a class=text href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать'>Печать</a></div>";
        }

        if ($g['image']!="") {
            $notice_img_path='';$notice_img_path_small='';
            if ($g['news_type']=='notice'){$notice_img_path='lects/';		  }
            else {$notice_img_path='news/';}
            $notice_img_path_small='small/';
        } else {
            //echo '<img src="images/design/themes/'.$theme_folder.'/no_photo.gif" border="0" align="left" hspace="10" vspace="0">';
        }
        echo $e.'</div>';
        if (trim($g['file_attach'])!='') {
            echo '<br> <div class=success>Прикреплен файл: <a href="news/attachement/'.trim($g['file_attach']).'">

			  		<img src="images/design/attachment.gif" border=0><b>'.trim($g['file_attach']).'</b></a></div>';
        }
    } else {
        echo '<div style="text-align:center;" class=warning>Запись не найдена</div><p align=center><a href=#close onclick="javascript:window.close();">Закрыть</a></p>'; exit();
    }
} else {
    if(!isset($_GET['number'])) {
        $number=1; $start=0;
    } else {
        $number=$_GET['number'];$start=$number*5-5;
    }
    $res01=mysql_query ('select count(*) from news');
    $count=mysql_result($res01,0,0);
    $pages=$count/5;
    $pages_and=$pages;
    $pages_and=intval($pages_and);
    if ($pages_and==$pages) {
        $pages=$pages_and;
    } else {
        $pages=$pages_and+1;
    }
    if(($number>$pages) || ($number<1)) {
        $number=$pages;
        if ($number == 0) {
            $start = 0;
        } else {
            $start=$number*5-5;
        }
    }
    $finish=$start+5;
    if($finish>$count) {
        $length=$count-$start;
    } else {
        $length=5;
    }
    $query='select n.*,u.fio,u.id as user_id,k.photo as kadri_photo,b.image as user_photo
  	from news n
	left join users u on u.id=n.user_id_insert 
	left join kadri k on k.id=u.kadri_id
	left join biography b on b.user_id=u.id
	order by n.date_time desc 
	  limit '.$start.','.$length;

    $res02=mysql_query ($query) or die(mysql_error());
    echo '<div class="main">'.$pg_title;
    if (CSession::isAuth()){
        echo '<a href="'.WEB_ROOT.'_modules/_news/?action=add" class="text"> Добавить новость</a>';
    }
    echo '</div>';
    ?>
    <p style="text-align:center;">
        <?php
        if ($pages>1)
        {
            $q_str=reset_param_name($_SERVER["QUERY_STRING"],'number');
            if ($q_str!="") {$q_str=$q_str.'&';}
        } else {
            // <abarmin date="04.05.2012">
            $q_str = "";
            // </abarmin>
        }
        //$href="index.php?".$q_str."&number=";
        //printPGnums($pages,$number,$href);
        echo getPagenumList($pages,$number,3,'number',$q_str,'');
        ?>
    </p>
    <table style="border: solid 1px #dddddd" width="95%" cellspacing="0" cellpadding="0" align="center">
        <?php
        while($a=mysql_fetch_array($res02))
        {
            echo '<tr><td colspan=2 style="background:#eeeeee" height="10"></td></tr>';
            echo '<tr>';

            //вывод фото новости (кроме ?wap)

            if (!isset($_GET['wap'])) {

                echo '<td style="width: 130px;" align="center" valign="middle">';
                if ($a['image']!="") {
                    if ($a['news_type']=='notice') {
                    	?>
                    	<div id="modal<?php echo ($a['id'])?>" class="modal hide fade">
						  <div class="modal-header">
						    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    <h3 id="myModalLabel"><?php echo ($a['title'])?></h3>
						  </div>
						  <div class="modal-body">
						    <img src="images/news/<?php echo urlencode($a['image'])?>">
						  </div>
						</div>
                    	<p><a href="#modal<?php echo ($a['id'])?>" data-toggle="modal">
                        <?php 
                        echo '<img src="_modules/_thumbnails/?src=images/news/'.$a['image'].'" align="center" valign="middle"  border=0>';
                        ?>
                   		</a></p>
                        <?php
                    } else {
                        ?>
                    	<p><a href="#modal<?php echo ($a['id'])?>" data-toggle="modal">
                        <?php 
                        echo '<img src="_modules/_thumbnails/?src=images/news/'.$a['image'].'" align="center" valign="middle" alt="Фото новости" border=0>';
                        ?>
						</a></p>
                        <?php
                    }
                } else {
                    //сначала пытаемся получить фото из анкета, если не находим из биографии
                    if ($a['user_photo']!="") {   	
						$filename = 'images/lects/small/sm_'.$a['user_photo'].'';
						if (file_exists($filename)) {
							echo '<img src="images/lects/small/sm_'.$a['user_photo'].'" border="0" align="center" valign="middle" alt="Фото преподавателя из биографии" border=0>';
						} else {
							echo '<img src="_modules/_thumbnails/?src=images/lects/'.$a['user_photo'].'" border="0" align="center" valign="middle" alt="Фото преподавателя из биографии" border=0>';
						}
                    } else if ($showKadriImg_ifNullBiogImg && $a['kadri_photo']!="") {
                    	$filename = 'images/lects/small/sm_'.$a['kadri_photo'].'';
                    	if (file_exists($filename)) {
							echo '<img src="images/lects/small/sm_'.$a['kadri_photo'].'" border="0" align="center" valign="middle" alt="Фото преподавателя из анкеты" border=0>';
                    	} else {
							echo '<img src="_modules/_thumbnails/?src=images/lects/'.$a['kadri_photo'].'" border="0" align="center" valign="middle" alt="Фото преподавателя из анкеты" border=0>';
                    	}
                    } else {
                        echo '<img src="images/design/notice.gif" border="0" align="center" valign="middle" alt="Фото новости" border=0>';
                    }
                }

                echo '</td>';
            } else {
                echo '<td width=5></td>';
            }

            echo '<td valign="top">';
            $file_path='news/';
            if ($a['news_type']=='notice') {$file_path='notice/';}

            $b=$a['file'];$chars4read=200;

            if ($a['news_type']=='notice') {$a['title']=$a['title'].'<br><font color=#8D8D8D>
	 (автор: ';

                if ($hide_person_data_rule)
                    $a['title'].=$hide_person_data_text;
                else {
                    $a['title'].='<a href="_modules/_lecturers/index.php?action=view';
                    if (isset($_GET['wap']) ) { $a['title'].='wap&';}
                    $a['title'].='&id='.$a['user_id'].'" title="об авторе...">'.$a['fio'].'</a>' ;
                }
                $a['title'].=')</font>';
            }

            echo '<p class="title">'.$a['title'].'</p><p class="text">'.date("d.m.Y", strtotime($a["date_time"])).'</p>';
            if (trim($a['file_attach'])!='') {echo '<a href="news/attachement/'.trim($a['file_attach']).'"
	 	title="Скачать прикрепленный файл: '.trim($a['file_attach']).'"><img src="images/design/attachment.gif" border=0></a>';
                echo '<span class=success>';
                print_file_size('news/attachement/'.trim($a['file_attach']));
                echo '</span>';
            }

            echo'<div class="news" valign="middle">';
            //echo ' strlen='.strlen($b);
            $details='';
            if (strlen($b)>$chars4read)
            {
                $posCnt=strpos($b," ",$chars4read-20);
                if ($posCnt>0) $b=substr($b,0,$posCnt);
                $b=$b.' ...';
                $url='index.php?';
                if (isset($_GET['wap'])) {
                    $url.='wap&';
                }
                $details = '<div align="right"><a href="#news'.$a["id"].'" data-toggle="modal">подробнее...</a></div>';
                $details .= '
                <div id="news'.$a["id"].'" class="modal hide fade" url="'.WEB_ROOT.$url.'id='.$a["id"].'">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Х</button>
                        <h3 id="myModalLabel">'.$a["title"].'</h3>
                    </div>
                    <div class="modal-body">
						'.$a['file'];
				if (trim($a['file_attach'])!='') {
					$details .= '<br> <div class=success>Прикреплен файл: <a href="news/attachement/'.trim($a['file_attach']).'">

			  		<img src="images/design/attachment.gif" border=0><b>'.trim($a['file_attach']).'</b></a></div>';
				}
				$details .=  '
                    </div>
                </div>';
            }
            $b=msg_replace($b);

            echo $b.'</div>'.$details;

            echo '</td></tr>';
            //}
        }
        ?>
    </table>
    <p style="text-align:center;">
        <?php echo getPagenumList($pages,$number,3,'number','',''); ?>
    </p>
    <?php

    if (!isset($_GET['wap'])) {
        echo $end1;
        include "display_voting.php";
    }

    echo $end2;
    define("CORRECT_FOOTER", true);
    include 'footer.php';

}
