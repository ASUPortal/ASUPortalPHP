{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Сотрудники кафедры</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core.search.tpl"}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th><i class="icon-camera"></i></th>
            <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
            <th>{CHtml::tableOrder("types", $persons->getFirstItem())}</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $persons->getItems() as $person}
            <tr>
                <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить сотрудника {$person->getName()}')) { location.href='?action=delete&id={$person->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td>
                    {if $person->isPhotoExists()}
                        <img src="{$web_root}_modules/_thumbnails/?src={$web_root}images/lects/{$person->photo}&w=100" />
                    {/if}
                </td>
                <td><a href="?action=edit&id={$person->getId()}">{$person->getName()}</a></td>
                <td>
                    {$needSeparation = false}
                    {foreach $person->getTypes()->getItems() as $type}
                        {if $needSeparation}
                            ,
                        {/if}
                        {$type->getValue()}
                        {$needSeparation = true}
                    {/foreach}
                </td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_staff/person/index.right.tpl"}
{/block}