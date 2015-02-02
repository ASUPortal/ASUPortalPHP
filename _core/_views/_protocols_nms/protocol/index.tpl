{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Протоколы НМС</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("date_text", $objects->getFirstItem(), true)}</th>
                    <th>{CHtml::tableOrder("num", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("program_content", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("comment", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить протокол НМС')) { location.href='index.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="index.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->date_text}</td>
                    <td>{$object->num}</td>
                    <td>{$object->program_content}</td>
                    <td>{$object->comment}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "index.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_protocols_nms/protocol/common.right.tpl"}
{/block}