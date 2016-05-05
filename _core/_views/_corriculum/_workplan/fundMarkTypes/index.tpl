{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("competentions", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("levels", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("controls", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить контролируемый раздел')) { location.href='workplanfundmarktypes.php?action=delete&id={$object->getId()}&plan_id={$object->plan_id}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplanfundmarktypes.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>
                        {foreach $object->competentions as $competention}
                            <p>{$competention}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->competentions as $competention}
                            {foreach CWorkPlanManager::getWorkplanCompetentionFormed(CWorkPlanManager::getWorkplan($object->plan_id), $competention) as $items}
                            	<p>{CTaxonomyManager::getTerm($items->level_id)}</p>
                            {/foreach}
                        {/foreach}
                    </td>
                    <td>{", "|join:CBaseManager::getWorkPlanContentSection($object->section_id)->controls->getItems()}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplanfundmarktypes.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/fundMarkTypes/common.right.tpl"}
{/block}