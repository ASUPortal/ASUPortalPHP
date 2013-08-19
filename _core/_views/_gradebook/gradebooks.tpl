{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Мои журналы</h2>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>&nbsp;</th>
            <th>#</th>
            <th>Период</th>
            <th>Дисциплина</th>
            <th>Преподаватель</th>
            <th>Группа</th>
        </tr>
        {foreach $gradebooks->getItems() as $gradebook}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить журнал?')) { location.href='?action=deleteGradebook&id={$gradebook->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td><a href="index.php?action=viewGradebook&id={$gradebook->getId()}">С {$gradebook->date_start} по {$gradebook->date_end}</a></td>
            <td>
                {if !is_null($gradebook->discipline)}
                    {$gradebook->discipline->getValue()}
                {/if}
            </td>
            <td>
                {if !is_null($gradebook->person)}
                    {$gradebook->person->getName()}
                {/if}
            </td>
            <td>
                {if !is_null($gradebook->group)}
                    {$gradebook->group->getName()}
                {/if}
            </td>
        </tr>
        {/foreach}
    </table>
    {CHtml::paginator($paginator, "?action=myGradebooks")}
{/block}

{block name="asu_right"}
{include file="_gradebook/gradebooks.right.tpl"}
{/block}