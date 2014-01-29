<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>Дата</th>
        <th>Исходная группа</th>
        <th>Новая группа</th>
        <th>Кто перенес</th>
    </tr>
    {foreach $student->groupChangeHistory->getItems() as $history}
        <tr>
            <td>{counter}</td>
            <td>{$history->date}</td>
            <td>
                {if !is_null($history->source)}
                    {$history->source->getName()}
                {/if}
            </td>
            <td>
                {if !is_null($history->target)}
                    {$history->target->getName()}
                {/if}
            </td>
            <td>
                {if !is_null($history->person)}
                    {$history->person->getName()}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>