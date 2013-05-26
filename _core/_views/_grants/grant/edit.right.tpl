<p>
    <a href="admin.php?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Все гранты
        </center></a>
</p>

<p>
    <a href="#" onclick="uploadFile(); return false;">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/devices/media-floppy.png"><br>
            Добавить файл
        </center></a>
</p>

<script>
    function uploadFile() {
        var dialog = jQuery("#uploadDialog");
        /**
         * Не загружаем диалог 20 раз
         */
        if (dialog.length > 0) {
            jQuery("#uploadDialog").dialog("open");
        } else {
            jQuery('<div id="uploadDialog">').dialog({
                modal: true,
                title: "Загрузка файлов",
                width: 390,
                height: 200,
                open: function(){
                    jQuery(this).load(web_root + "_modules/_grants/admin.php?action=getuploadform&id={$form->grant->getId()}", function(){
                        /**
                         * В этот момент форма подгрузилась,
                         * навешиваем на нее события
                         */
                        jQuery("#fileupload").ajaxForm({
                            url: web_root + "_modules/_grants/",
                            beforeSubmit: function(){
                                /**
                                 * Перед началом загрузки показываем прогрессор
                                 */
                                jQuery("#attachmentsSubform").html('<img src="' + web_root + 'images/loading.gif">');
                            },
                            success: function(){
                                /**
                                 * После окончания загрузки закрываем диалог
                                 */
                                jQuery("#uploadDialog").dialog("close");
                                /**
                                 * И обновляем сабформу с вложениями
                                 */
                                jQuery("#attachmentsSubform").load(web_root + "_modules/_grants/admin.php?action=getAttachmentsSubform&id={$form->grant->getId()}");
                            }
                        });
                    });
                }
            });
        }
        return false;
    }
</script>

<p>
    <a href="events.php?action=add&grant_id={$form->grant->getId()}">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/appointment-new.png"><br>
            Добавить мероприятие
        </center></a>
</p>

<p>
    <a href="periods.php?action=add&grant_id={$form->grant->getId()}">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-paste.png"><br>
            Добавить период
        </center></a>
</p>