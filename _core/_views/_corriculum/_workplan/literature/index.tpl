{extends file="_core.component.tpl"}
{block name="asu_center"}

{if (isset($corriculumDisciplineId))}
<br>
    <a href="disciplines.php?action=addStatement&discipline_id={$corriculumDisciplineId}" target="_blank">Сформировать заявку на учебную литературу</a>
<br><br>
{/if}

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("book_id", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить литературу')) { location.href='workplanliterature.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplanliterature.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->book->book_name}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplanliterature.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/literature/common.right.tpl"}
{/block}