{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальные планы преподавателей</h2>

    {CHtml::helpForCurrentPage()}

    {if $persons->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}
        <script>
            jQuery(document).ready(function(){
                jQuery("#selectAll").change(function(){
                    var items = jQuery("input[name='selectedDoc[]']")
                    for (var i = 0; i < items.length; i++) {
                        items[i].checked = this.checked;
                    }
                });
            });
        </script>

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th><input type="checkbox" id="selectAll"></th>
                <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
                <th>Год</th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $persons->getItems() as $person}
            <tr>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">{counter}</td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <input type="checkbox" value="{$person->getId()}" name="selectedDoc[]">
                </td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <a href="load.php?action=view&id={$person->getId()}">
                        {$person->fio}
                    </a>
                </td>
                {if $person->getIndPlansByYears()->getCount() == 0}
                    <td>Нет информации</td>
                {/if}
            </tr>
                {foreach $person->getIndPlansByYears()->getItems() as $year=>$load}
                    <tr>
                        <td>
                            {if !is_null(CTaxonomyManager::getYear($year))}
                                <a href="load.php?action=view&id={$person->getId()}&year={$year}">
                                    {CTaxonomyManager::getYear($year)->getValue()}
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/common.right.tpl"}
{/block}