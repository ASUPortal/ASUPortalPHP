{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Справочник ставок в часах по нагрузке</h2>

    {CHtml::helpForCurrentPage()}

    <script>
        jQuery(document).ready(function(){
            jQuery("#year_selector").change(function(){
                window.location.href=web_root + "_modules/_hrs_rate/index.php?filter=year.id:" + jQuery(this).val();
            });
			jQuery("#isAll").change(function(){
				window.location.href=web_root + "_modules/_hrs_rate/index.php?isAll=" + (jQuery(this).is(":checked") ? "1":"0");
			});
        });
    </script>
    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="year.id">Учебный год</label>
            <div class="controls">
            	{CHtml::dropDownList("year.id", CTaxonomyManager::getYearsList(), $selectedYear, "year_selector", "span12")}
            </div>
        </div>
    </div>
    <td valign="top">
		<div class="form-horizontal">
			<div class="control-group">
			<label class="control-label" for="isAll">Показать все</label>
				<div class="controls">
					{CHtml::checkBox("isAll", "1", $isAll, "isAll")}
				</div>
			</div>
		</div>
	</td>
    
    {if ($rates->getCount() == 0)}
        Нет объектов для отображения
    {else}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::activeViewGroupSelect("id", $rates->getFirstItem(), true)}</th>
                <th>{CHtml::tableOrder("dolgnost_id", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("rate", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("year_id", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("comment", $rates->getFirstItem())}</th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $rates->getItems() as $rate}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить ставку {$rate->rate}')) { location.href='?action=delete&id={$rate->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td>{CHtml::activeViewGroupSelect("id", $rate, false, true)}</td>
                    <td><a href="index.php?action=edit&id={$rate->getId()}">{CTaxonomyManager::getPostById($rate->dolgnost_id)->getValue()}</a></td>
                    <td>{$rate->rate}</td>
                    <td>
                    	{if !is_null(CTaxonomyManager::getYear($rate->year_id))}
                    		{CTaxonomyManager::getYear($rate->year_id)->getValue()}
                    	{else}
                    		не указан
                    	{/if}
                    </td>
                    <td>{$rate->comment}</td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_hrs_rate/index.right.tpl"}
{/block}