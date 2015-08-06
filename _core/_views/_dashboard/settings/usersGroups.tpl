{CHtml::helpForCurrentPage()}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>Название группы</th>
        <th>Добавить элемент</th>
    </tr>
    {foreach $groups->getItems() as $group}
    <tr>
        <td>{counter}</td>
        <td>{$group->comment}</td>
		<td><a class="icon-plus" href="#" onclick="{ location.href='?action=addItemsForGroups&id={$group->getId()}'; };" title="добавить"></a></td>
    </tr>
    {/foreach}
</table>


