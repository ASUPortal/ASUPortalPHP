{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>#viewIndexTitle#</h2>

    {if ($objects->getCount() == 0)}
        #viewIndexNoObjects#
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    #viewTableHeadFields#
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить #viewObjectSingleName#')) { location.href='#controllerFile#?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="#controllerFile#?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    #viewTableBodyFields#
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "#controllerFile#?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="#viewPath#/index.right.tpl"}
{/block}