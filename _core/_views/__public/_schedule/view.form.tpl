{if $schedules->getCount() == 0}
	Нет объектов для отображения
{else}
	<form action="{$link}" method="post" id="mainView">
    {if (!$invert)}
    	<table class="table table-bordered table-hover table-condensed" border=1 cellspacing=0>
	    	<tr>
		        <td style="background-color: #EFEFFF;" width=20>&nbsp;</td>
		        {for $num=1 to count($time)}
		        	<td style="background-color: #EFEFFF;" width=150>&nbsp;<b>{$num}</b>&nbsp;{$time[$num]}</td>
		        {/for}
	        </tr>
	    	{for $day=1 to 6}
		        <tr>
			        <td valign="top" style="background-color: #EFEFFF;" width=20><b>{$existDays[$day]}</b></td>
		        	{for $num=1 to count($time)}	
		        		{include file="__public/_schedule/view.subform.tpl"}
		        	{/for}
				</tr>
			{/for}
	    </table>
    {else}
	    <table class="table table-bordered table-hover table-condensed" border=1 cellspacing=0>
	    	<tr>
		        <td style="background-color: #EFEFFF;" width=100>&nbsp;</td>
		        {for $day=1 to 6}
		        	<td style="background-color: #EFEFFF;" width=150><b>{$existDays[$day]}</b></td>
		        {/for}
	        </tr>
	    	{for $num=1 to count($time)}
		        <tr>
			        <td style="background-color: #EFEFFF;" width=100>&nbsp;<b>{$num}</b>&nbsp;{$time[$num]}</td>
		        	{for $day=1 to 6}
		        		{include file="__public/_schedule/view.subform.tpl"}
		        	{/for}
				</tr>
			{/for}
	    </table>
    {/if}
    </form>
    
    <p><b>Примечание:</b><br>
	<p><font color="silver">Серым цветом</font> отмечена вторая половина лаб. работы.<br>
	При наведении на предмет, можно узнать его полное наименование.<br>
	Если ссылка предмета <a><b>подсвечена</b></a>, можно перейти к пособиям по предмету, кликнув по ссылке.
{/if}