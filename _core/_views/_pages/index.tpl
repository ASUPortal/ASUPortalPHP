{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Страницы</h2>

    {CHtml::helpForCurrentPage()}

    {if ($pages->getCount() == 0)}
        У Вас еще нет страниц!
    {else}
        {include file="_core.searchLocal.tpl"}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::tableOrder("title", $pages->getFirstItem())}</th>
                <th>{CHtml::tableOrder("user_id_insert", $pages->getFirstItem())}</th>
                <th></th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $pages->getItems() as $page}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить страницу {$page->title}')) { location.href='?action=delete&id={$page->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="admin.php?action=edit&id={$page->getId()}">{$page->title}</a></td>
                    <td>
                        {if !is_null($page->getAuthor())}
                            {$page->getAuthor()->getName()}
                        {/if}
                    </td>
                    <td><a href="index.php?action=view&id={$page->getId()}" target="_blank">Просмотреть</a></td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_pages/index.right.tpl"}
{/block}