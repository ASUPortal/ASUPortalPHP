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
                </tr>
            </thead>
            <tbody>
            {counter start=0 print=false}
            {foreach $result as $res}
                <tr>
                	<td>{counter}</td>
                	<td>{$res["hl"]}</td>
                	<td>
                		<a href="{$web_root}{$res['filepath']}" ">
	                        {$res['filename']}
	                    </a>
	                    {if (CUtils::getFileSize("{$res["filepath"]}")) !=0 }
		                    <font size=-2 style="font-family:Arial;"> размер файла: <b>{CUtils::getFileSize("{$res["filepath"]}")}</b> МБ</font>
	                    {else}
	                    	<span><font size=-2 color="#FF0000" style="font-family:Arial;"> файл не найден.</font></span>
	                    {/if}
                		<img src="{CUtils::getFileMimeIcon($res['filename'])}">
                	</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}