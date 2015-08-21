<p>
    <a href="?action=index"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="{$web_root}_modules/_students/index.php?action=index&filter=group_id:{$group->getId()}"><center>
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

<p>
    <a href="#studentsWithoutMarks" data-toggle="modal"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/system-file-manager.png"><br>
        Студенты без оценок
    </center></a>
</p>

<div id="studentsWithoutMarks" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Студенты без оценок</h3>
    </div>
    <div class="modal-body">

    </div>
</div>

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
    jQuery("#studentsWithoutMarks").on("show", function(){
        var place = jQuery(".modal-body", this);
        jQuery(place).html('<div style="text-align: center;"><img src="' + web_root + 'images/loader.gif"></div>');
    });
    jQuery("#studentsWithoutMarks").on("shown", function(){
        var place = jQuery(".modal-body", this);
        jQuery.ajax({
            url: "{$web_root}_modules/_student_groups/index.php?action=GetStudentsWithoutMarks&id={$group->getId()}",
            type: "GET",
            cache: false,
            context: this,
            success: function(data){
                jQuery(this).find(".modal-body").html(data);
            }
        });
    });

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
            jQuery("#groupPrintDialog").modal("hide");
            window.location.href = data.url;
        });
    }
</script>
