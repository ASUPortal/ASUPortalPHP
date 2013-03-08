<table width=99% cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" name=footer_site>
<tr align=right>
    <td colspan="3" class=round_table>
        <hr>
        <div class=TEXT style="text-align:right;">
        <a href="<?php echo $files_path;?>p_portal_about.php" title="подробнее...">(с)Портал АСУ</a>
        2006-<?php echo date("Y");?>, 
        <a href="http://www.ugatu.ac.ru" title="перейти на сайт УГАТУ" target="_blank">УГАТУ</a>
        </div>
    </td>
</tr>
</table>
<div class=text id=benchmark_info style="text-align:center;">
<?php
if (!isset($SavePrintMode) && isset($use_benchmark) && $use_benchmark==true) {
	$timer->stop();
	echo "Время генерации страницы: " .$timer->timeElapsed('Start', 'Stop') . " секунд\n";
	//$timer->display();
}
?>
</div>
    <?php CLog::dump(); ?>
</body>
</html>