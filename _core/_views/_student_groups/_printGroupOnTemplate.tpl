<p>
    <a href="#printDialogWithGroup" data-toggle="modal">
    	<center>
        	<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        	Печать с добавлением группы
    	</center></a>
</p>

<div id="printDialogWithGroup" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <b>Массовая печать</b>

        {CHtml::printGroupOnTemplate($templateWithGroup, $selectedDoc, $url, $actionWithGroup, $id)}
    </div>
</div>

<div id="groupPrintDialogWithGroup"  class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <div class="progress progress-striped active">
            <div class="bar" id="progressbarWithGroup" style="width: 0%;"></div>
        </div>
        <div id="statusbarWithGroup">Подождите, идет формирование архива</div>
    </div>
</div>

<script>
function printWithTemplateStaff(manager, method, template_id, selectedDoc, url, action, id) {
	/**
	 * Закрываем диалог чтобы не мешался
	 */
	jQuery("#printDialogWithGroup").modal("hide");
	/**
	 * Открываем свой диалог групповой печати
	*/
	jQuery("#groupPrintDialogWithGroup").modal("show");
	/**
	 * Получаем список значений
	*/
	if (id == "selectedInView") {
		var values = new Array();
		jQuery.each(jQuery("input[name='selectedDoc[]']:checked"), function(key, value){
			values.push(jQuery(value).val());
		});
		var id = values.join(':');
	}
		
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
	jQuery("#progressbarWithGroup").attr("items", (items.length - 1));
	jQuery("#progressbarWithGroup").css("width", "0%");
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
			var width = (attachments.length) * 100 / jQuery("#progressbarWithGroup").attr("items");
			jQuery("#progressbarWithGroup").css("width", width + "%");
			/**
			 * Если все отработаны, то сгенерим
			 * архив и отдадим его пользователю
			*/
			if (attachments.length == items.length) {
				generateZipGroup(attachments);
			}
		});
	});
}
function generateZipGroup(attachments) {
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
		jQuery("#groupPrintDialogWithGroup").modal("hide");
		window.location.href = data.url;
	});
}
</script>        