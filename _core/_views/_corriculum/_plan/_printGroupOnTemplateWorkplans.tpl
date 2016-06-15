<a href="#printDialogWorkplans" data-toggle="modal">
    <center>
        <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        Печать по шаблону рабочих программ
    </center>
</a>

<div id="printDialogWorkplans" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <b>Массовая печать</b>

        {CHtml::printGroupOnTemplate($templateWorkplans, $selectedDoc, $url, $actionGetWorkplans, $id)}
    </div>
</div>

<div id="groupPrintDialogWorkplans" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <div class="progress progress-striped active">
            <div class="bar" id="progressbarWorkplans" style="width: 0%;"></div>
        </div>
        <div id="statusbarWorkplans">Подождите, идет формирование архива</div>
    </div>
</div>

<script>
function printWithTemplateWorkplans(manager, method, template_id, selectedDoc, url, action, id) {
	/**
	 * Закрываем диалог чтобы не мешался
	 */
	jQuery("#printDialogWorkplans").modal("hide");
	/**
	 * Открываем свой диалог групповой печати
	*/
	jQuery("#groupPrintDialogWorkplans").modal("show");
	/**
	 * Получаем список значений
	*/
	var items = new Array();

	if (selectedDoc) {
		jQuery.each(jQuery("input[name='selectedDoc[]']:checked"), function(key, value){
			items.push(jQuery(value).val());
		});
	} else {
		jQuery.ajax({
			async: false,
			url: url,
			dataType: "json",
			data: {
				action: action,
				id: id
			}
		}).done(function(data) {
			jQuery.each(data, function(key, value){
				items[items.length] = key;
			});
		});
	}
	/**
	 * Адаптируем прогресс-бар
	*/
	jQuery("#progressbarWorkplans").attr("items", (items.length - 1));
	jQuery("#progressbarWorkplans").css("width", "0%");
	/**
	 * Для каждого значения генерим шаблон
	*/
	var attachments = new Array();
	jQuery.each(items, function(key, value) {
		jQuery.ajax({
			dataType: "json",
			url: web_root + "_modules/_print/",
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
			var width = (attachments.length) * 100 / jQuery("#progressbarWorkplans").attr("items");
			jQuery("#progressbarWorkplans").css("width", width + "%");
			/**
			 * Если все отработаны, то сгенерим
			 * архив и отдадим его пользователю
			*/
			if (attachments.length == items.length) {
				generateZipWorkplans(attachments);
			}
		});
	});
}
function generateZipWorkplans(attachments) {
	jQuery.ajax({
		type: "POST",
		url: web_root + "_modules/_zip/",
		data: {
			action: "archive",
			files: attachments,
			noredirect: "1"
		},
		dataType: "json"
	}).done(function(data){
		jQuery("#groupPrintDialogWorkplans").modal("hide");
		window.location.href = data.url;
	});
}
</script>        