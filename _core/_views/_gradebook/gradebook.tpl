{extends file="_core.3col.tpl"}

{block name="asu_center"}
<script>
    jQuery(document).ready(function(){
        jQuery(".stooltip").tooltip({
            delay: 0,
            showURL: false,
            track: true
        });
    });
</script>

<h2>Журнал успеваемости</h2>
{CHtml::helpForCurrentPage()}

    <p>По дисциплине: {if !is_null($gradebook->discipline)}{$gradebook->discipline->getValue()}{/if}</p>
    <p>За период: c {$gradebook->date_start} по {$gradebook->date_end}</p>
    <p>Преподаватель: {if !is_null($gradebook->person)}{$gradebook->person->getName()}{/if}</p>

    <p><strong>Легенда</strong></p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/apps/system-users.png"> - Присутствие</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/places/start-here.png"> - Отсутствие</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/actions/list-add.png"> - Зачет</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/status/dialog-error.png"> - Не зачет</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/emotes/face-smile.png"> - Выполнено</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/status/printer-error.png"> - Не выполнено</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/mimetypes/application-certificate.png"> - Защита</p>
    <p><img src="{$web_root}images/{$icon_theme}/16x16/actions/edit-paste.png"> - Отчет</p>

    <table cellpadding="2" cellspacing="0" border="1">
        {foreach $gradebook->toGradebookTable()->getItems() as $num=>$row}
        {if ($num == 0)}
            <tr>
                {$data = array()}
                {foreach $row->getItems() as $col}
                    {if array_key_exists($col, $data)}
                        {$data[$col] = $data[$col] + 1}
                    {else}
                        {$data[$col] = 0}
                    {/if}
                {/foreach}
                {foreach $data as $val=>$span}
                    {if $span == 0}
                        <td align="center">{$val}</td>
                    {else}
                        <td align="center" colspan="{($span + 1)}">{$val}</td>
                    {/if}
                {/foreach}
            </tr>
        {else}
            <tr>
                {foreach $row->getItems() as $key=>$col}
                <td>
                    {$date = explode("_", $key)}
                    {if array_key_exists(2, $date)}
                        {$date = date("d.m.Y", strtotime($date[2]))}
                    {else}
                        {$date = ""}
                    {/if}
                    {if is_array($col)}
                    	{foreach $col as $v}
							{if is_numeric($v)}
		                        {$v}
		                    {elseif strtolower($v) == "присутствие"}
		                        <center><img class="stooltip" title="{$date}, Присутствие" src="{$web_root}images/{$icon_theme}/16x16/apps/system-users.png"></center>
		                    {elseif strtolower($v) == "отсутствие"}
		                        <center><img class="stooltip" title="{$date}, Отсутствие" src="{$web_root}images/{$icon_theme}/16x16/places/start-here.png"></center>
		                    {elseif strtolower($v) == "зач"}
		                        <center><img class="stooltip" title="{$date}, Зачет" src="{$web_root}images/{$icon_theme}/16x16/actions/list-add.png"></center>
		                    {elseif strtolower($v) == "не зач."}
		                        <center><img class="stooltip" title="{$date}, Не зачет" src="{$web_root}images/{$icon_theme}/16x16/status/dialog-error.png"></center>
		                    {elseif strtolower($v) == "выполнено"}
		                        <center><img class="stooltip" title="{$date}, Выполнено" src="{$web_root}images/{$icon_theme}/16x16/emotes/face-smile.png"></center>
		                    {elseif strtolower($v) == "не выполнено"}
		                        <center><img class="stooltip" title="{$date}, Не выполнено" src="{$web_root}images/{$icon_theme}/16x16/status/printer-error.png"></center>
		                    {elseif strtolower($v) == "защита"}
		                        <center><img class="stooltip" title="{$date}, Защита" src="{$web_root}images/{$icon_theme}/16x16/mimetypes/application-certificate.png"></center>
		                    {elseif strtolower($v) == "защита работы"}
								<center><img class="stooltip" title="{$date}, Защита" src="{$web_root}images/{$icon_theme}/16x16/mimetypes/application-certificate.png"></center>
		                    {elseif strtolower($v) == "отчет"}
		                        <center><img class="stooltip" title="{$date}, Отчет" src="{$web_root}images/{$icon_theme}/16x16/actions/edit-paste.png"></center>
		                    {else}
		                        {$v}
		                    {/if}                    	
                    	{/foreach}
                    {else}
	                    {if is_numeric($col)}
	                        {$col}
	                    {elseif strtolower($col) == "присутствие"}
	                        <center><img class="stooltip" title="{$date}, Присутствие" src="{$web_root}images/{$icon_theme}/16x16/apps/system-users.png"></center>
	                    {elseif strtolower($col) == "отсутствие"}
	                        <center><img class="stooltip" title="{$date}, Отсутствие" src="{$web_root}images/{$icon_theme}/16x16/places/start-here.png"></center>
	                    {elseif strtolower($col) == "зач"}
	                        <center><img class="stooltip" title="{$date}, Зачет" src="{$web_root}images/{$icon_theme}/16x16/actions/list-add.png"></center>
	                    {elseif strtolower($col) == "не зач."}
	                        <center><img class="stooltip" title="{$date}, Не зачет" src="{$web_root}images/{$icon_theme}/16x16/status/dialog-error.png"></center>
	                    {elseif strtolower($col) == "выполнено"}
	                        <center><img class="stooltip" title="{$date}, Выполнено" src="{$web_root}images/{$icon_theme}/16x16/emotes/face-smile.png"></center>
	                    {elseif strtolower($col) == "не выполнено"}
	                        <center><img class="stooltip" title="{$date}, Не выполнено" src="{$web_root}images/{$icon_theme}/16x16/status/printer-error.png"></center>
	                    {elseif strtolower($col) == "защита"}
	                        <center><img class="stooltip" title="{$date}, Защита" src="{$web_root}images/{$icon_theme}/16x16/mimetypes/application-certificate.png"></center>
						{elseif strtolower($col) == "защита работы"}
							<center><img class="stooltip" title="{$date}, Защита" src="{$web_root}images/{$icon_theme}/16x16/mimetypes/application-certificate.png"></center>
	                    {elseif strtolower($col) == "отчет"}
	                        <center><img class="stooltip" title="{$date}, Отчет" src="{$web_root}images/{$icon_theme}/16x16/actions/edit-paste.png"></center>
	                    {else}
	                        {$col}
	                    {/if}
                    {/if}
                </td>
                {/foreach}
            </tr>
        {/if}
        {/foreach}
    </table>
{/block}

{block name="asu_right"}
{include file="_gradebook/gradebook.right.tpl"}
{/block}
