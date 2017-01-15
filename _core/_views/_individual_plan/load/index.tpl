{extends file="_core.3col.tpl"}

{block name="localSearchContent"}
    <script>
        jQuery(document).ready(function(){
            jQuery("#year_selector").change(function(){
                window.location.href=web_root + "_modules/_individual_plan/load.php?filter=year.id:" + jQuery(this).val();
            });
        	jQuery("#selectAll").change(function(){
        		var items = jQuery("input[name='selectedDoc[]']")
                for (var i = 0; i < items.length; i++) {
                    items[i].checked = this.checked;
                }
            });
			jQuery("#isAll").change(function(){
				window.location.href=web_root + "_modules/_individual_plan/load.php?isAll=" + (jQuery(this).is(":checked") ? "1":"0");
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

    {if $persons->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th><input type="checkbox" id="selectAll" checked></th>
                <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
                <th>Год</th>
            </tr>
            {counter start=0 print=false}
            {foreach $persons->getItems() as $person}
            <tr>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">{counter}</td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <input type="checkbox" value="{$person->getId()}" name="selectedDoc[]" checked>
                </td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <a href="load.php?action=view&id={$person->getId()}">
                        {$person->fio}
                    </a>
                </td>
                {if $person->getIndPlansByYears()->getCount() == 0}
                    <td>Нет информации</td>
                {/if}
            </tr>
                {foreach $person->getIndPlansByYears()->getItems() as $year=>$load}
                    <tr>
                        <td>
                            {if !is_null(CTaxonomyManager::getYear($year))}
                                <a href="load.php?action=view&id={$person->getId()}&year={$year}">
                                    {CTaxonomyManager::getYear($year)->getValue()}
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
        </table>

    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/common.right.tpl"}
{/block}