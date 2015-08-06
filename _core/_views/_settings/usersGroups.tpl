{CHtml::helpForCurrentPage()}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>Название группы</th>
        <th>Добавить</th>
        <th>Изменить</th>
    </tr>
    {foreach $groups->getItems() as $group}
    <tr>
        <td>{counter}</td>
        <td>{$group->comment}</td>
		<td><a class="icon-plus" href="#" onclick="{ location.href='{$web_root}_modules/_settings/index.php?action=addUsersSettings&id={$group->getId()}'; };" title="добавить"></a></td>
		<td><a class="icon-edit" href="#" onclick="{ location.href='{$web_root}_modules/_settings/index.php?action=changeUsersSettings&id={$group->getId()}'; };" title="изменить"></a></td>
    </tr>
    {/foreach}
</table>


