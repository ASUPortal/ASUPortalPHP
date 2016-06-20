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
                    <th>{CHtml::tableOrder("term.name", $objects->getFirstItem(), true)}</th>
                    <th>{CHtml::tableOrder("type_task", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("procedure_eval", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("criteria_eval", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить владение компетенции')) { location.href='workplancompetentionexperiences.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplancompetentionexperiences.php?action=edit&id={$object->getId()}">{$object->experience}</a></td>
                    <td>{$object->type_task}</td>
                    <td>{$object->procedure_eval}</td>
                    <td>{$object->criteria_eval}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplancompetentionexperiences.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentionExperiences/common.right.tpl"}
{/block}