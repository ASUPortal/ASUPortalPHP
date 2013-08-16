<?php
    /**
     * Проставляется во всех файлах, где есть нормальный вывод
     * end1 и end2. В большинстве этого нет, так что проверяем
     * отсутствие флага
     */
    if (!defined("CORRECT_FOOTER")) {
        echo $end1;
        echo $end2;
    }
?>

<div class="container-fluid asu_footer">
    <div class="row-fluid">
        <div style="text-align:right;color:#fff">
            <a href="<?php echo $files_path;?>p_portal_about.php" style="color:#fff" title="Подробнее...">(с)Портал АСУ</a>
            2006-<?php echo date("Y");?>,
            <a href="http://www.ugatu.ac.ru" title="Перейти на сайт УГАТУ" style="color:#fff" target="_blank">УГАТУ</a>
        </div>
    </div>
</div>

</body>
</html>