{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Учебная нагрузка</h2>
    {CHtml::helpForCurrentPage()}
    
    <script>
        jQuery(document).ready(function(){
			{if $isBudget and $isContract}
	            jQuery("#year_selector").change(function(){
	                window.location.href=web_root + "_modules/_study_loads/index.php?filter=year.id:" + jQuery(this).val();
	            });
				jQuery("#isBudget").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isBudget=" + (jQuery(this).is(":checked") ? "1":"-1") + "&filter=year.id:{$selectedYear}";
				});
				jQuery("#isContract").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isContract=" + (jQuery(this).is(":checked") ? "1":"-1") + "&filter=year.id:{$selectedYear}";
				});
	        {/if}
			{if !$isBudget and !$isContract}
	            jQuery("#year_selector").change(function(){
	                window.location.href=web_root + "_modules/_study_loads/index.php?isBudget=-1&isContract=-1&filter=year.id:" + jQuery(this).val();
	            });
	            jQuery("#isBudget").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isBudget=" + (jQuery(this).is(":checked") ? "1":"-1") + "&isContract=-1&filter=year.id:{$selectedYear}";
				});
				jQuery("#isContract").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isContract=" + (jQuery(this).is(":checked") ? "1":"-1") + "&isBudget=-1&filter=year.id:{$selectedYear}";
				});
	        {/if}
			{if !$isBudget and $isContract}
	            jQuery("#year_selector").change(function(){
	                window.location.href=web_root + "_modules/_study_loads/index.php?isBudget=-1&filter=year.id:" + jQuery(this).val();
	            });
	            jQuery("#isBudget").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isBudget=" + (jQuery(this).is(":checked") ? "1":"-1") + "&filter=year.id:{$selectedYear}";
				});
				jQuery("#isContract").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isContract=" + (jQuery(this).is(":checked") ? "1":"-1") + "&isBudget=-1&filter=year.id:{$selectedYear}";
				});
	        {/if}
			{if $isBudget and !$isContract}
	            jQuery("#year_selector").change(function(){
	                window.location.href=web_root + "_modules/_study_loads/index.php?isContract=-1&filter=year.id:" + jQuery(this).val();
	            });
	            jQuery("#isBudget").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isBudget=" + (jQuery(this).is(":checked") ? "1":"-1") + "&isContract=-1&filter=year.id:{$selectedYear}";
				});
				jQuery("#isContract").change(function(){
					window.location.href=web_root + "_modules/_study_loads/index.php?isContract=" + (jQuery(this).is(":checked") ? "1":"-1") + "&filter=year.id:{$selectedYear}";
				});
	        {/if}
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
    <table border="0" width="100%" class="tableBlank">
		<tr>
			<td valign="top" width="10%">
				<div class="form-horizontal">
					<div class="control-group">
					<label class="control-label" for="isBudget">Бюджет</label>
						<div class="controls">
							{CHtml::checkBox("isBudget", "1", $isBudget, "isBudget")}
						</div>
					</div>
				</div>
			</td>
		    <td valign="top">
				<div class="form-horizontal">
					<div class="control-group">
					<label class="control-label" for="isContract">Коммерция</label>
						<div class="controls">
							{CHtml::checkBox("isContract", "1", $isContract, "isContract")}
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
	
	{if $personsWithoutLoad->getCount() == 0 and count($personsWithLoad) == 0}
		Нет объектов для отображения
	{else}
		{if count($personsWithLoad) != 0}
			<div class="alert alert-info">Свод нагрузки за {CTaxonomyManager::getYear($selectedYear)->getValue()} год. <b>Итого: {number_format($sumTotal,1,',','')}</b></div>
			{$persons = $personsWithLoad}
			{include file="_study_loads/subform.personsWithLoad.tpl"}
		{/if}
		{if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
	         {if $personsWithoutLoad->getCount() != 0}
				<div class="alert alert-info">Сотрудники ППС, у которых нет нагрузки в выбранном учебном году</div>
				{$persons = $personsWithoutLoad}
				{include file="_study_loads/subform.personsWithoutLoad.tpl"}
			{/if}
	    {/if}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
