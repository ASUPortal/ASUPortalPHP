{extends file="_core.3col.tpl"}

{block name="localSearchContent"}
    <script>
        jQuery(document).ready(function(){
            jQuery("#year_selector").change(function(){
                window.location.href=web_root + "_modules/_individual_plan/load.php?action=viewLoads&filter=year.id:" + jQuery(this).val();
            });
        	jQuery("#selectAll").change(function(){
        		var items = jQuery("input[name='selectedDoc[]']")
                for (var i = 0; i < items.length; i++) {
                    items[i].checked = this.checked;
                }
            });
			jQuery("#isAll").change(function(){
				window.location.href=web_root + "_modules/_individual_plan/load.php?action=viewLoads&isAll=" + (jQuery(this).is(":checked") ? "1":"0");
			});
        });
    </script>
    <td valign="top">
		<div class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="year.id">Учебный год</label>
				<div class="controls">
					{CHtml::dropDownList("year.id", $years, $selectedYear, "year_selector", "span12")}
				</div>
			</div>
		</div>
	</td>
	<td valign="top">
		<div class="form-horizontal">
			<div class="control-group">
			<label class="control-label" for="isAll">Показать всех</label>
				<div class="controls">
					{CHtml::checkBox("isAll", "1", $isAll, "isAll")}
				</div>
			</div>
		</div>
	</td>
{/block}

{block name="asu_center"}
    <h2>Индивидуальные планы преподавателей</h2>

    {CHtml::helpForCurrentPage()}
    {if (CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
		{include file="_core.searchLocal.tpl"}
    {/if}

    {if $loads->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                {if ($hasOwnAccessLevel)}
                    <th>&nbsp;</th>
                {/if}
                <th>#</th>
                <th><input type="checkbox" id="selectAll" checked></th>
                <th>{CHtml::tableOrder("person_id", $loads->getFirstItem())}</th>
                <th>Год</th>
            </tr>
            {counter start=0 print=false}
            {foreach $loads->getItems() as $load}
            <tr>
                {if ($hasOwnAccessLevel)}
                    <td>
	                    <span>
	                        <span title="Возможность редактирования" class="changeEditStatus" asu-id="{$load->getId()}" asu-action="updateEditStatus">
	                            {if ($load->_edit_restriction == 0)}&#10004;{else}&#10006;{/if}
	                        </span>
	                    </span>
                    </td>
                {/if}
                <td>{counter}</td>
                <td>
                    <input type="checkbox" value="{$load->getId()}" name="selectedDoc[]" checked>
                </td>
                <td>
                    <a href="load.php?action=view&id={$load->person->getId()}&year={$load->year->getId()}">
                        {$load->person->fio} ({$load->getType()})
                    </a>
                <td>{$load->year->getValue()}</td>
            </tr>
            {/foreach}
        </table>
    {/if}
    
<script>
    /**
     * Функция смены статуса
     *
     * @param value
     */
    function changeStatus(item) {
    	var container = item.target || item.srcElement;
        var id = jQuery(container).attr("asu-id");
        var action = jQuery(container).attr("asu-action");
        jQuery.ajax({
            url: web_root + "_modules/_individual_plan/load.php",
            beforeSend: function(){
                jQuery(container).html('<i class="icon-signal"></i>');
            },
            cache: false,
            context: item,
            data: {
                action: action,
                id: id
            },
            dataType: "json",
            method: "GET",
            success: function(data){
                jQuery(container).html(data.title);
            }
        });
    }
    jQuery(document).ready(function(){
        var classes = new Array(".changeEditStatus");
        /**
         * Обрабатываем смену статуса
         */
        classes.forEach(function(elem, i, arr) {
            jQuery(elem).on("click", function(item){
            	// изменяем статус
                changeStatus(item);
            });
        });
    });
</script>
<style>
    .changeEditStatus {
        cursor: pointer;
    }
    .changeEditStatus:hover {
        text-decoration: underline;
    }
</style>

{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/loads.right.tpl"}
{/block}