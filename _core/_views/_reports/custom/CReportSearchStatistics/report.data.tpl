<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Месяц</th>
        <th>Число обращений</th>
        <th>К поиску</th>
    </tr>
    {foreach $data as $row}
        <tr>
            <td>{$row["t_stamp"]}</td>
            <td>{$row["total"]}</td>
            <td>{$row["cnt"]}</td>
        </tr>
    {/foreach}
</table>