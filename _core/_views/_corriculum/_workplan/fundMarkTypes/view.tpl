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
                    <th>{CHtml::tableOrder("section_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("competentions", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("levels", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("controls", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                	<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить контролируемый раздел')) { location.href='workplanfundmarktypes.php?action=delete&id={$object->getId()}&plan_id={$object->plan_id}&view=1'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td>{$object->section->name}</td>
                    <td>
                        {foreach $object->competentions as $competention}
                            <p>{$competention}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->levels as $level}
                            <p>{$level}</p>
                        {/foreach}
                    </td>
                    <td>{", "|join:$object->controls->getItems()}</td>
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