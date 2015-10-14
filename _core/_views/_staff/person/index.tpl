{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Сотрудники кафедры</h2>
	{CHtml::helpForCurrentPage()}
	<table border="0" width="100%" class="tableBlank">
		<tr>
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
      		<td valign="top">
			    <div class="form-horizontal">
			        <div class="control-group">
			            <label class="control-label" for="types.person_type_id">Тип участия на кафедре</label>
			            <div class="controls">
			            	{CHtml::dropDownList("types.person_type_id", $types, $selectedType, "type_selector", "span12")}
			            </div>
			        </div>
			    </div>
			</td>
		</tr>
	</table>

    {include file="_core.search.tpl"}

    <script>
        jQuery(document).ready(function(){
            jQuery("#selectAll").change(function(){
				var items = jQuery("input[name='selectedDoc[]']")
                for (var i = 0; i < items.length; i++) {
                    items[i].checked = this.checked;
                }
            });
            jQuery("#type_selector").change(function(){
                window.location.href=web_root + "_modules/_staff/index.php?filter=types.person_type_id:" + jQuery(this).val() + "&type=" + jQuery(this).val();
            });
			jQuery("#isAll").change(function(){
				window.location.href=web_root + "_modules/_staff/index.php?isAll=" + (jQuery(this).is(":checked") ? "1":"0");
			});
        });
	</script>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th width="100"><i class="icon-camera"></i></th>
            <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
            <th>{CHtml::tableOrder("types", $persons->getFirstItem())}</th>
            <th><input type="checkbox" id="selectAll"></th>
        </tr>
        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $persons->getItems() as $person}
            <tr>
                <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить сотрудника {$person->getName()}')) { location.href='?action=delete&id={$person->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td>
                    {CHtml::activeAttachPreview("photo", $person, 100)}
                </td>
                <td><a href="?action=edit&id={$person->getId()}">{$person->getName()}</a></td>
                <td>
                    {$needSeparation = false}
                    {foreach $person->getTypes()->getItems() as $type}
                        {if $needSeparation}
                            ,
                        {/if}
                        {$type->getValue()}
                        {$needSeparation = true}
                    {/foreach}
                </td>
                <td>
                    <input type="checkbox" value="{$person->getId()}" name="selectedDoc[]">
                </td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_staff/person/common.right.tpl"}
{/block}