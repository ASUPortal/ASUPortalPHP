<p>
    <a href="?action=index"><center>
        <img src="{$web_root}images/tango/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="{$web_root}_modules/_students/?action=index&filter=group:{$group->getId()}"><center>
        <img src="{$web_root}images/tango/32x32/apps/system-users.png"><br>
        Студенты
    </center></a>
</p>

<p>
    <a href="#" onclick="jQuery('#printDialog').dialog();"><center>
        <img src="{$web_root}images/tango/32x32/devices/printer.png"><br>
        Печать для всех студентов
    </center></a>
</p>

<div id="printDialog" title="Печать по шаблону" style="display: none;">
    {CHtml::printGroupOnTemplate("formset_students")}
</div>

<div id="groupPrintDialog" title="Печать по шаблону" style="display: none;">
    <div id="progressbar"></div>
    <div id="statusbar">Подождите, идет формирование архива</div>
</div>

<script>
    function printWithTemplate(manager, method, template_id) {
        /**
         * Закрываем диалог чтобы не мешался
         */
        jQuery("#printDialog").dialog("close");
        /**
         * Открываем свой диалог групповой печати
         */
        jQuery("#groupPrintDialog").dialog();
        /**
         * Ставим прогресс-бар
         */
        jQuery("#progressbar").progressbar();
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
        jQuery("#progressbar").progressbar({
            max: (students.length - 1)
        });
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
                jQuery("#progressbar").progressbar({
                    value: attachments.length
                });
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