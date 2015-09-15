{if ($discipline->labors->getCount() > 0) and ($discipline->labors->getFirstItem()->section_id == 0)}
<b>Семестр не указан</b>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Вид нагрузки</th>
        <th>Величина</th>
    </tr>
    {foreach $discipline->labors->getItems() as $labor}
    <tr>
        <td>{counter}</td>
        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вид занятия {if !is_null($labor->type)}{$labor->type->getValue()}{/if}')) { location.href='labors.php?action=del&id={$labor->id}'; }; return false;"></a></td>
        <td>
            {if !is_null($labor->type)}
                <a href="labors.php?action=edit&id={$labor->getId()}&discipline_id={$labor->discipline_id}">{$labor->type->getValue()}</a>
            {/if}
        </td>
        <td>{$labor->value}</td>
    </tr>
    {/foreach}
</table>
{/if}

{foreach $discipline->sections->getItems() as $section}
    <a
        href="#"
        onclick="if (confirm('Вы действительно хотите удалить семестр?')) { location.href='disciplineSections.php?action=delete&id={$section->getId()}&discipline_id={CRequest::getInt("id")}'; }">
        <i class="icon-trash">&nbsp;</i>
    </a>
    <b>Семестр: {$section->title}</b>
    <a href="disciplineSections.php?action=edit&id={$section->getId()}">
        <i class="icon-pencil">&nbsp;</i>
    </a>
    <a href="labors.php?action=add&id={$section->getId()}&discipline_id={CRequest::getInt("id")}">
        <i class="icon-plus">&nbsp;</i>
    </a>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Вид нагрузки</th>
            <th>Величина</th>
        </tr>
        {foreach $section->labors->getItems() as $labor}
            <tr>
                <td>{counter}</td>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вид занятия {if !is_null($labor->type)}{$labor->type->getValue()}{/if}')) { location.href='labors.php?action=del&id={$labor->id}'; }; return false;"></a></td>
                <td>
                    {if !is_null($labor->type)}
                        <a href="labors.php?action=edit&id={$labor->getId()}&discipline_id={CRequest::getInt("id")}">{$labor->type->getValue()}</a>
                    {else}
                        {$labor->type_id}
                    {/if}
                </td>
                <td>{$labor->value}</td>
            </tr>
        {/foreach}
    </table>
{/foreach}