{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Учебные материалы</h2>
    {if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
{if ($files->getCount() == 0)}
	Нет учебных материалов.
{else}
	{if (!is_null($files->getFirstItem()->document))}
		{if !is_null($files->getFirstItem()->document->subject)}
    		<h4><a href="?action=index&filter=subject:{$files->getFirstItem()->document->subject->getId()}">{$files->getFirstItem()->document->subject->getValue()}</a></h4>
		{/if}
	{/if}
	{if $files->getFirstItem()->getAuthorName() !== ""}
    	<p><a href="?action=index&filter=author:{$files->getFirstItem()->getAuthorId()}">{$files->getFirstItem()->getAuthorName()}</a></p>
    {/if}
    <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
	            <th width="20px">#</th>
                <th>Тип</th>
                <th>Файл</th>
				<th>Примечание</th>
				<th>Скачано</th>
            </tr>
		{foreach $files->getItems() as $file}
            <tr>
				<td>{counter}</td>
                <td valign="top" width="32">
                    {if $file->getIconImagePath() !== ""}
                        <img src="{$file->getIconImagePath()}">
                    {/if}
                </td>
                <td>
                    <a href="{$file->getDownloadLink()}" title="Добавлено {$file->date_time|date_format:"d.m.Y H:i:s"}">
                        {$file->browserFile}
                    </a>
					{if (CUtils::getFileSize("library/{$file->nameFolder}/{$file->nameFile}")) !=0 }
	                    <font size=-2 style="font-family:Arial;"> размер файла: <b>{CUtils::getFileSize("library/{$file->nameFolder}/{$file->nameFile}")}</b> МБ</font>
                    {else}
                    	<span><font size=-2 color="#FF0000" style="font-family:Arial;"> файл не найден.</font></span>
                    {/if}
                </td>
				<td>
                    {if (trim($file->add_link)!='')}
						{$add_link_array = explode("\n", $file->add_link)}
		                {foreach $add_link_array as $link}
		                    {if (strstr($link,'http://') || strstr($link,'www.') || strstr($link,'ftp://'))}      	
		                        <a href="{$link}" title="перейти по ссылке" target="_blank">{$link}</a><br>
		                    {else} 
		                    	{$link}
		                    {/if}
		                {/foreach}
		            {/if}
                </td>
				<td>{$file->entry}&nbsp;раз(а)</td>
            </tr>
        {/foreach}
    </table>
{/if}
{/block}

{block name="asu_right"}
	{include file="_library/public/public.view.right.tpl"}
{/block}