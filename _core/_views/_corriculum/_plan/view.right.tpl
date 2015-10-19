<p>
    <a href="?action=index"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="?action=edit&id={$corriculum->getId()}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/accessories-text-editor.png"><br>
        Редактировать
    </center></a>
</p>

<p>
    <a href="cycles.php?action=add&id={$corriculum->id}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
        Добавить цикл
    </center></a>
</p>

<p>
    <a href="?action=copy&id={$corriculum->id}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
        Копировать план
    </center></a>
</p>

<p>
    <a href="#printDialog" data-toggle="modal"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        Печать по шаблону
    </center></a>
</p>

<div id="printDialog" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <b>Печать для всех дисциплин</b>

        {CHtml::printGroupOnTemplate("formset_corriculum_disciplines")}
    </div>
</div>

<div id="groupPrintDialog"  class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <div class="progress progress-striped active">
            <div class="bar" id="progressbar" style="width: 0%;"></div>
        </div>
        <div id="statusbar">Подождите, идет формирование архива</div>
    </div>
</div>

<script>
    function printWithTemplate(manager, method, template_id) {
        /**
         * Закрываем диалог чтобы не мешался
         */
        jQuery("#printDialog").modal("hide");
        /**
         * Открываем свой диалог групповой печати
         */
        jQuery("#groupPrintDialog").modal("show");
        /**
         * Система будет синхронная - так немного проще
         * Да и мало кто это заметит
         *
         * Получаем список дисциплин в учебном плане
         */
        var disciplines = new Array();
        jQuery.ajax({
            async: false,
            url: "{$web_root}_modules/_corriculum/index.php",
            dataType: "json",
            data: {
                action: "JSONGetDisciplines",
                id: "{$discipline->getId()}"
            }
        }).done(function(data) {
            jQuery.each(data, function(key, value){
            	disciplines[disciplines.length] = key;
            });
        });
        /**
         * Адаптируем прогресс-бар
         */
        jQuery("#progressbar").attr("items", (disciplines.length - 1));
        jQuery("#progressbar").css("width", "0%");
        /**
         * Для каждой дисциплины генерим шаблон
         */
        var attachments = new Array();
        jQuery.each(disciplines, function(key, value) {
            jQuery.ajax({
                dataType: "json",
                url:"{$web_root}_modules/_print/",
                data: {
                    action: "print",
                    manager: manager,
                    method: method,
                    id: value,
                    template: template_id,
                    noredirect: "1"
                }
            }).done(function(data) {
                attachments[attachments.length] = data.filename;
                var width = (attachments.length) * 100 / jQuery("#progressbar").attr("items");
                jQuery("#progressbar").css("width", width + "%");
                /**
                 * Если все отработаны, то сгенерим
                 * архив и отдадим его пользователю
                 */
                if (attachments.length == disciplines.length) {
                    generateZip(attachments);
                }
            });
        });
    }
    function generateZip(attachments) {
        jQuery.ajax({
            type: "POST",
            url: "{$web_root}_modules/_zip/",
            data: {
                action: "archive",
                files: attachments,
                noredirect: "1"
            },
            dataType: "json"
        }).done(function(data){
            jQuery("#groupPrintDialog").modal("hide");
            window.location.href = data.url;
        });
    }
</script>