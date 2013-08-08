{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальный план</h2>

    {CHtml::helpForCurrentPage()}

    <table border="0" width="100%" class="tableBlank">
        <tr>
            <td valign="top">
                <form id="filters">
                </form>
            </td>
        </tr>
    </table>

    {if $person->getIndPlansByYears()->getCount() == 0}
        Нет информации для отображения
    {else}
        {foreach $person->getIndPlansByYears()->getItems() as $load}
            {include file="_individual_plan/load/subform.yearLoad.tpl"}
        {/foreach}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/view.right.tpl"}
{/block}