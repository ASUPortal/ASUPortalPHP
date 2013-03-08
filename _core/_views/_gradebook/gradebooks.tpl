{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Мои журналы</h2>

    <table border="1" cellpadding="2" cellspacing="0">
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
            <td><a href="#" onclick="if (confirm('Действительно удалить журнал?')) { location.href='?action=deleteGradebook&id={$gradebook->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
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