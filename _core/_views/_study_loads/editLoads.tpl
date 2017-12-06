{extends file="_core.3col.tpl"}

{block name="asu_center"}

<style>
	.vert-text {
		-webkit-transform: rotate(180deg); 
		-moz-transform: rotate(270deg);
		-ms-transform: rotate(270deg);
		-o-transform: rotate(180deg);
		
		-webkit-writing-mode: vertical-rl;
		max-width: 30px;
	}
</style>

{function name=clearNullValues level=0}
  {if (floatval(str_replace(',','.',$number)) != 0 or $number != 0)}
     {$number}
  {else}
     &nbsp;
  {/if}
{/function}

{if (is_null($lecturer))}
	{CHtml::helpForCurrentPage()}
	{include file="_study_loads/subform.showLoads.tpl"}
{/if}

{if (!is_null($lecturer))}
<h2>План годовой нагрузки {$lecturer->getName()}</h2>
<h3>{$position}</h3>
    {CHtml::helpForCurrentPage()}
	
	{include file="_study_loads/subform.showLoads.tpl"}

	{if $loadsFall->getCount() == 0 and $loadsSpring->getCount() == 0}
		Нет объектов для отображения
	{else}
		<div class="alert alert-info"><b>Всего за год: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes),1,',','') level=0}
						( с учётом филиалов: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerWithFilials($lecturer, $year, $loadTypes),1,',','') level=0})</b></div>
		{if $loadsFall->getCount() != 0}
			<div class="alert alert-info" style="text-align:center;">Осенний семестр</div>
			
			{$studyLoads = $loadsFall}
			{$loadsId = "loadsFall"}
			{$part = CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::FALL)}
			
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		{if $loadsSpring->getCount() != 0}
			<div class="alert alert-info" style="text-align:center;">Весенний семестр</div>
			
			{$studyLoads = $loadsSpring}
			{$loadsId = "loadsSpring"}
			{$part = CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING)}
			
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		<div class="alert alert-info"><b>Всего за год: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes),1,',','') level=0}
						( с учётом филиалов: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerWithFilials($lecturer, $year, $loadTypes),1,',','') level=0})</b></div>
    	
		<div><p><b>Примечание:</b><br>
			сверка с расписанием производится только по сумме Лекции+ЛабРаботы+Практики, не включаются Курс.Проекты и пр.
		</div>
    {/if}
{else}
    <div class="alert">Не выбран преподаватель!</div>
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
            url: web_root + "_modules/_study_loads/index.php",
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
	{include file="_study_loads/common.right.tpl"}
{/block}