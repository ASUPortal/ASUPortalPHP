<p>
    <a href="?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Все гранты
        </center></a>
</p>

<p>
    <a href="#uploadDialog" role="button" data-toggle="modal">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/devices/media-floppy.png"><br>
            Добавить файл
        </center></a>
</p>

<div id="uploadDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Загрузка файлов</h3>
    </div>
    <div class="modal-body" id="uploadDialogBody">
    <p>Подождите, идет загрузка</p>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery("#uploadDialog").on("show", function(){
            jQuery("#uploadDialogBody").load(web_root + "_modules/_grants/?action=getuploadform&id={$form->grant->getId()}", function(){
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
                            jQuery("#uploadDialog").modal("hide");
                            /**
                             * И обновляем сабформу с вложениями
                             */
                            jQuery("#attachmentsSubform").load(web_root + "_modules/_grants/?action=getAttachmentsSubform&id={$form->grant->getId()}");
                        }
                    });
                });
        });
    });
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
