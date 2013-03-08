<table border="0" cellpadding="2" cellspacing="0" class="tableBlank">
    <tr>
        <td width="80%" valign="top">
            <select id="dialog_names" size="12" style="width: 100%;">
                {foreach $items as $item}
                    <option>{$item["name"]}</option>
                {/foreach}
            </select>
        </td>
        <td width="20%" valign="top">
            <div style="text-align: right; ">
                <p><span style="cursor: pointer; text-decoration: underline; color: #0000ff;" id="dialog_users">Пользователи</span></p>
                <p><span style="cursor: pointer; text-decoration: underline; color: #0000ff;" id="dialog_groups">Группы</span></p>
            </div>
        </td>
    </tr>
</table>

{foreach $items as $item}
    <input type="hidden" name="dialog[name][]" value="{$item["name"]}">
    <input type="hidden" name="dialog[id][]" value="{$item["id"]}">
    <input type="hidden" name="dialog[type][]" value="{$item["type"]}">
{/foreach}
<input type="hidden" id="dialog_field" value="{$fieldName}">