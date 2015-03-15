{if ($discipline->plans->getCount() == 0)}
    Нет рабочих программ для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Дисциплина</th>
        </tr>
        {foreach $discipline->plans->getItems() as $plan}
            <tr>
                <td>{counter}</td>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить рабочую программу?')) { location.href='workplans.php?action=delete&id={$plan->getId()}'; }; return false;"></a></td>
                <td>
                    {if !is_null($plan->discipline)}
                        <a href="workplans.php?action=edit&id={$plan->getId()}">{$plan->discipline->getValue()}</a>
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
{/if}