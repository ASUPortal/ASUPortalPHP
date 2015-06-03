{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Биография</h2>

    {CHtml::helpForCurrentPage()}

    {if ($biographys->getCount() == 0)}
        У Вас ещё не заполнена биография!
        <div><input name="" onclick="location.href='?action=add'" type="button" class="btn" value="Добавить"></div>
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
	            <th width="10"></th>
	            <th width="10"></th>
	            <th width="100"><i class="icon-camera"></i></th>
	            <th>Биография</th>
        	</tr>
            {foreach $biographys->getItems() as $biography}
                {if !is_null($biography->getUser())}
					<b>{$biography->getUser()->getName()}</b><br><br>
				{/if}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить биографию {$biography->getUser()->fio}')) { location.href='?action=delete&id={$biography->id}'; }; return false;"></a></td>
                    <td><a href="index.php?action=edit&id={$biography->getId()}" class="icon-pencil" title="правка"></a></td>
					<td>{CHtml::activeAttachPreview("image", $biography, 100)}</td>
					<td>{$biography->main_text}</td>
                </tr>
            {/foreach}
        </table>
    {/if}
{/block}

{block name="asu_right"}
	{include file="_biography/index.right.tpl"}
{/block}

