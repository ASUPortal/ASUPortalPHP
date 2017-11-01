<ul class="nav nav-pills">
	<li>
	    <a href="resources.php?action=add&id={$form->person->getId()}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
	        Добавить ресурс
	    </center></a>
	</li>
</ul>

{if ($form->person->resources->getCount() == 0)}
    Информации о научных ресурсах нет
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th width="10"></th>
            <th width="10">#</th>
            <th>Ресурс</th>
        </tr>
        {counter start=0 print=false}
        {foreach $form->person->resources->getItems() as $resource}
            <tr>
                <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить сведения о ресурсе {$resource->resource->getValue()}?')) { location.href='resources.php?action=delete&id={$resource->id}'; }; return false;"></a></td>
                <td><a href="resources.php?action=edit&id={$resource->getId()}" title="Редактировать">{counter}</a></td>
                <td><a href="{$resource->resource->getAlias()}{$resource->author_id}" title="Посмотреть" target="_blank">{$resource->resource->getValue()}</a></td>
            </tr>
        {/foreach}
    </table>
{/if}