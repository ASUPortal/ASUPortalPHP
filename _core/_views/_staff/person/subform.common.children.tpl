<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>Пол</th>
        <th>Дата рождения</th>
        <th>Полных лет</th>
    </tr>
    {foreach $form->person->children->getItems() as $child}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить ребенка?')) { location.href='children.php?action=delete&id={$child->getId()}'; }; return false;"></a></td>
            <td><a href="children.php?action=edit&id={$child->getId()}">{counter}</a></td>
            <td>
                {if !is_null($child->gender)}
                    {$child->gender->getValue()}
                {/if}
            </td>
            <td>{$child->getBirthDate()}</td>
            <td>{$child->getAge()}</td>
        </tr>
    {/foreach}
</table>