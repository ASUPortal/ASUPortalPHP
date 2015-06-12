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
                    <th>{CHtml::tableOrder("sectionIndex", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("name", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("lectures", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("controls", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить раздел дисциплины')) { location.href='workplancontentsections.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplancontentsections.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->sectionIndex}</td>
                    <td>{$object->name}</td>
                    <td>
                        {foreach $object->lectures->getItems() as $l}
                            <p>{$l->lecture_title}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->controls->getItems() as $l}
                            <p>{$l}</p>
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplancontentsections.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentSections/common.right.tpl"}
{/block}