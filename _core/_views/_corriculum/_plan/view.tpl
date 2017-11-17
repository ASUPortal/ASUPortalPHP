{extends file="_core.3col.tpl"}

{block name="asu_center"}
{CHtml::helpForCurrentPage()}

{include file="_corriculum/_plan/view.center.tpl"}

<table class="table table-striped table-bordered table-hover table-condensed">
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
    <tr>
        <td valign="top" colspan="2">
            {include file="_corriculum/_plan/subview.attestation.tpl"}
        </td>
    </tr>
</table>
{/block}

{block name="asu_right"}
    {CHtml::displayActionsMenu($_actions_menu)}
{/block}