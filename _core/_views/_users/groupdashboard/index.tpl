{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Элементы рабочего стола</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th width="16">{CHtml::tableOrder("icon", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("title", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить элемент рабочего стола')) { location.href='groupdashboard.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="groupdashboard.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>
                        {if ($object->icon == "")}
                            &nbsp;
                        {else}
                            <center><img src="{$web_root}images/{$icon_theme}/16x16/{$object->icon}"></center>
                        {/if}
                    </td>
                    <td><a href="?action=edit&id={$object->id}">{$object->title}</a></td>
                </tr>
                {foreach $object->children->getItems() as $child}
                    <tr>
                        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить элемент рабочего стола')) { location.href='groupdashboard.php?action=delete&id={$child->getId()}'; }; return false;"></a></td>
                        <td>{counter}</td>
                        <td><a href="groupdashboard.php?action=edit&id={$child->getId()}" class="icon-pencil"></a></td>
                        <td>&nbsp;</td>
                        <td><a href="?action=edit&id={$child->id}"> - {$child->title}</a></td>
                    </tr>
                {/foreach}
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "groupdashboard.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_users/groupdashboard/common.right.tpl"}
{/block}