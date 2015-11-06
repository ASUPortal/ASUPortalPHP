{extends file="_core.component.tpl"}

{block name="asu_center"}
<form action="workplancontentloads.php" method="post">
    <table class="table">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("load_type_id", $loads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("term_id", $loads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("value", $loads->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $loads->getItems() as $object}
            {if isset($editSectionLoad) && $editSectionLoad->getId() == $object->getId()}
                {include file="_corriculum/_workplan/contentLoads/form.tpl"}
            {else}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузка')) { location.href='workplancontentloads.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplancontentloads.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->loadType}</td>
                    <td>{$object->term->corriculum_discipline_section->title}</td>
                    <td>{$object->value}</td>
                </tr>
            {/if}
        {/foreach}
        {if isset($editSectionLoad) && $editSectionLoad->getId() == null}
            {include file="_corriculum/_workplan/contentLoads/form.tpl"}
        {elseif !isset($editSectionLoad)}
            <tr>
                <td colspan="6">
                    <a href="workplancontentloads.php?action=add&id={$section->getId()}" class="btn btn-small btn-success">Добавить нагрузку</a>
                </td>
            </tr>
        {/if}
        </tbody>
    </table>
</form>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}