{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Сведения о ППС</h2>
    {CHtml::helpForCurrentPage()}
    
    <script>
        jQuery(document).ready(function(){
			jQuery("#year_selector").change(function(){
	                window.location.href=web_root + "_modules/_study_loads/index.php?action=information&filter=year.id:" + jQuery(this).val();
	        });
        });
    </script>
    <table border="0" width="40%" class="tableBlank">
		<tr>
			<td>
			    <div class="form-horizontal">
			        <div class="control-group">
			            <label class="control-label" for="year.id">Учебный год</label>
			            <div class="controls">
			            	{CHtml::dropDownList("year.id", CTaxonomyManager::getYearsList(), $selectedYear, "year_selector", "span12")}
			            </div>
			        </div>
			    </div>
			</td>
		</tr>
	</table>
	
	{if $personsWithLoad->getCount() == 0}
		Нет объектов для отображения
	{else}
		<div>
			<div style="float:left;">
				<table class="table table-striped table-bordered table-hover table-condensed">
			        <thead>
				        <tr>
				            <th rowspan="2">По должностям</th>
				            <th colspan="5" style="text-align:center;">Ставки</th>
				        </tr>
					    <tr>
					        <th>чел</th>
					        <th>бюдж</th>
					        <th>ком</th>
					        <th>всего</th>
					        <th>по ШР</th>
					    </tr>
			        </thead>
			        <tbody>
				        {foreach $postsWithRates as $postWithRate}
				        	<tr>
					        	{foreach $postWithRate as $post}
					        		<td>{$post}</td>
					        	{/foreach}
				        	</tr>
				        {/foreach}
			        </tbody>
			    </table>
			</div>
			<div style="float:left;">&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<div style="float:left;">
				<table class="table table-striped table-bordered table-hover table-condensed">
			        <thead>
				        <tr>
				            <th>Сумма нагрузки</th>
				            <th>Ставки план</th>
				            <th>Ставки факт</th>
				            <th>Часы</th>
				        </tr>
			        </thead>
			        <tbody>
				        <tr>
				            <td>Общая сумма</td>
				            <td>{$rateSum}</td>
				            <td>{$rateSumFact}</td>
				            <td>{$sumTotal}</td>
				        </tr>
			        </tbody>
			    </table>
			</div>
		</div>
    {/if}
{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
