<?php

    // <abarmin date="15.10.2012">
    // система меню через модели
    require_once("core.php");

    function menuItemsAsListWithCount(array $data, $level) {
        echo '<ul class="level'.$level.'">';
        foreach ($data as $entry) {
            if ($entry->getChilds()->getCount() > 0) {
                echo '<li>';
                echo '<a href="'.htmlspecialchars($entry->getLink()).'"><strong>'.htmlspecialchars($entry->getName()).'</strong></a> ('.$entry->getChilds()->getCount().')';
                menuItemsAsListWithCount($entry->getChilds()->getItems(), ($level + 1));
                echo '</li>';
            } else {
                echo '<li>';
                if ($entry->getName() != "<hr />") {
                    echo '<a href="'.htmlspecialchars($entry->getLink()).'"><strong>'.htmlspecialchars($entry->getName()).'</strong></a>';
                } else {
                    echo '<hr />';
                }
                echo '</li>';
            }
        }
        echo '</ul>';
    }

    //не показывать в анкете
    if (isset($_SESSION['id']) && (strstr($_SERVER["SCRIPT_NAME"],"anketa")=='' || 1>0) ) {
        ?>
        <link href="<?php echo $web_root; ?>css/_core/adminmenu.css" rel="stylesheet" type="text/css">
        <div id="adminMenu">
            <?php menuItemsAsListWithCount(CMenuManager::getMenu("admin_menu")->getMenuPublishedItemsInHierarchy()->getItems(), 0); ?>
        </div>
        <?php

        $query="select count(*) as new_mails_cnt from mails where to_user_id='".$_SESSION['id']."' and mail_type='in' and read_status=0";
        $res=mysql_query($query);
        $a=mysql_fetch_array($res);
        if ($a['new_mails_cnt']>0) {
            echo '<span class=text> у Вас непрочитанных сообщений: <a href="mail.php" title="Ваши сообщения на портале"> <font style="font-weight:bold; font-size:12;">'.$a['new_mails_cnt'].'</font> &nbsp;
            <img src="'.$web_root.'images/design/themes/'.$theme_folder.'/new_mail.gif" height=20></a> ';
        }
    }
?>
<form name=menu_form_view> </form>