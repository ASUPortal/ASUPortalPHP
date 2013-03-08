{extends file="_core.3col.tpl"}

{block name="asu_center"}

<table cellpadding="0" cellspacing="0" border="0" class="tableBlank">
    {include file="_corriculum/_plan/subview.header.tpl"}
    <tr>
        <td valign="top" colspan="2">
            {include file="_corriculum/_plan/subview.center.tpl"}
        </td>
    </tr>
    <tr>
        <td valign="top" colspan="2">
            {include file="_corriculum/_plan/subview.practice.tpl"}
        </td>
    </tr>
</table>
{/block}

{block name="asu_right"}
{include file="_corriculum/_plan/view.right.tpl"}
{/block}