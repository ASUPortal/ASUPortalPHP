{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Файлы</h2>
{if (CSession::isAuth())}
    {CHtml::helpForCurrentPage()}
{/if}

{if ($files->getCount() == 0)}
	Нет учебных материалов.
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
				<th width="15px"></th>
				<th width="15px"></th>
				<th width="20px">#</th>
				<th>Тип</th>
				<th>Файл</th>
				<th>Примечание</th>
				<th>Скачано</th>
            </tr>
		{foreach $files->getItems() as $file}
            <tr>
                <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить файл {$file->browserFile}')) { location.href='?action=deleteFile&id_file={$file->getId()}&id={$file->nameFolder}&user_id={$file->user_id}'; }; return false;"></a></td>
		        <td><a class="icon-edit" href="#" onclick="{ location.href='?action=editFile&id={$file->nameFolder}&id_file={$file->getId()}&filter=author:{$file->user_id}'; };" title="изменить"></a></td>
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
                    {if ($file->getFileSize()) !=0 }
	                    <font size=-2 style="font-family:Arial;"> размер файла: <b>{$file->getFileSize()}</b> МБ</font>
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