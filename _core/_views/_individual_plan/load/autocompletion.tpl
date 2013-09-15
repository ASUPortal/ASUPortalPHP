<script>
    jQuery(document).ready(function(){
        jQuery("input[name=totalSelector]").on("change", function(){
            jQuery.each(jQuery("#typesTable tbody input[type=checkbox]"), function(key, val){
                jQuery(val).attr("checked", jQuery("input[name=totalSelector]").is(":checked"));
            });
        });
    });
</script>

<table class="table table-striped table-bordered table-hover table-condensed" id="typesTable">
    <tr>
        <th><input type="checkbox" name="totalSelector"></th>
        <th>Наименование</th>
        <th>План</th>
        <th>Выполнено</th>
        <th>Категория</th>
    </tr>
    <tbody>
    {foreach $data as $row}
        <tr>
            <td><input type="checkbox" name="selected[]" value="{$row["id"]}"></td>
            <td>{$row["title"]}</td>
            <td>{$row["planned"]}</td>
            <td>{if ($row["isExecuted"])}Выполнено{/if}</td>
            <td>{$row["category"]}</td>
        </tr>
    {/foreach}
    </tbody>
</table>