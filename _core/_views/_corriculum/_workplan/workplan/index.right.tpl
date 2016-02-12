{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="workplans.php?action=addFromView" asu-action="flow">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить рабочую программу
        </center></a>
</p>

<p>
    <a href="workplans.php?action=corriculumToChange" asu-action="flow">
        <center>
        <div asu-type="flow-property" name="selected" value="selectedInView"></div>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-redo.png"><br>
            Сменить учебный план
        </center></a>
</p>

<p>
    <a href="workplans.php?action=corriculumToCopy" asu-action="flow">
        <center>
        <div asu-type="flow-property" name="selected" value="selectedInView"></div>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
            Копировать в другой учебный план
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
        <b>Печать для всех рабочих программ</b>

        {CHtml::printGroupOnTemplate("formset_workplans")}
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
         * Получаем список рабочих программ
         */
        var workplans = new Array();
        jQuery.each(jQuery("input[name='selectedDoc[]']:checked"), function(key, value){
        	workplans.push(jQuery(value).val());
        });
        /**
         * Адаптируем прогресс-бар
         */
        jQuery("#progressbar").attr("items", (workplans.length - 1));
        jQuery("#progressbar").css("width", "0%");
        /**
         * Для каждой рабочей программы генерим шаблон
         */
        var attachments = new Array();
        jQuery.each(workplans, function(key, value) {
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
                if (attachments.length == workplans.length) {
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