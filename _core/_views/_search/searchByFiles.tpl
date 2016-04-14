{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Результат поиска по файлам</h2>

    {CHtml::helpForCurrentPage()}
    
    <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                	<th width="16">#</th>
                    <th width=50%>Результат поиска</th>
                    <th>Файл</th>
                    <th>Расположение</th>
                </tr>
            </thead>
            <tbody>
            {counter start=0 print=false}
            {foreach $result as $res}
                <tr>
                	<td>{counter}</td>
                	<td>{$res["hl"]}</td>
                	<td>
                		<a href="{$res['filepath']}">
	                        {$res['filename']}
	                    </a>
                		<img src="{CUtils::getFileMimeIcon($res['filename'])}">
                	</td>
                	<td>{$res["location"]}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}