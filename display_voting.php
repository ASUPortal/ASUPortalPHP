<?php
    if (CSettingsManager::getSettingValue("display_voting")) {
        //---------------------выводы доп.опросов-------------------
        include $files_path.$folder_poll.'booth.php';
        echo $tab_begin.'<tr><td colspan=3 align=center><div class=text style="text-align:right;">';
        if ($typepoll=='rnd_poll') {
            echo 'случайный опрос</div>';
            random_booth();}
        else {
            echo 'последний опрос</div>';
            newest_booth(); }
        echo '</td>'.$tab_end;
        //--------------------------------------------------------------

    }
?>
