<table border="1">
{foreach $report as $key=>$row}
    <tr>
        {foreach $row as $id=>$data}
            {if ($id == 0)}
                <td>
                    <p>{$key}</p>
                    <ul>
                        {if is_array($data)}
                            {foreach $data as $itemKey=>$item}
                                <li>{$itemKey} - {$item}</li>
                            {/foreach}
                        {/if}
                    </ul>
                </td>
            {else}
                {if is_array($data)}
                    <td>
                    {foreach $data as $itemKey=>$item}
                        <li>{$itemKey} - {$item}</li>
                    {/foreach}
                    </td>
                {else}
                    <td>{$data}</td>
                {/if}
            {/if}
        {/foreach}
    </tr>
{/foreach}
</table>