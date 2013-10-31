<p>
    <a href="?action=index"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="{$web_root}_modules/_students/?action=index&filter=group:{$group->getId()}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/system-users.png"><br>
        Студенты
    </center></a>
</p>

<p>
    <a href="#printDialog" data-toggle="modal"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        Печать по шаблону
    </center></a>
</p>

{if (false)}
<p>
    <a href="#" onclick="showStudentsWithoutMarks(); return false;"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/apps/system-file-manager.png"><br>
            Студенты без оценок
        </center></a>
</p>
{/if}

<div id="printDialog" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <b>Печать для всех студентов</b>

        {CHtml::printGroupOnTemplate("formset_students")}
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
    function showStudentsWithoutMarks(){
        dojo.require("dijit.Dialog");
        var dialog = new dijit.Dialog({
            title: "Студенты без оценок по дисциплинам учебного плана",
            href: "{$web_root}_modules/_student_groups/?action=GetStudentsWithoutMarks&id={$group->getId()}"
        });
        dialog.show();
        return false;
    };
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
         * Получаем список студентов в группе
         */
        var students = new Array();
        jQuery.ajax({
            async: false,
            url: "{$web_root}_modules/_student_groups/index.php",
            dataType: "json",
            data: {
                action: "JSONGetStudents",
                id: "{$group->getId()}"
            }
        }).done(function(data) {
            jQuery.each(data, function(key, value){
                students[students.length] = key;
            });
        });
        /**
         * Адаптируем прогресс-бар
         */
        jQuery("#progressbar").attr("items", (students.length - 1));
        jQuery("#progressbar").css("width", "0%");
        /**
         * Для каждого студента генерим вкладыш
         */
        var attachments = new Array();
        jQuery.each(students, function(key, value) {
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
                 * Если все вкладыши отработаны, то сгенерим
                 * архив и отдадим его пользователю
                 */
                if (attachments.length == students.length) {
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
            window.location.href = data.url;
        });
    }
</script>