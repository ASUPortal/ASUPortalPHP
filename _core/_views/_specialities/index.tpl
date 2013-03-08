{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Специальности</h2>

    {CHtml::helpForCurrentPage()}

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("name", $specialities->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $specialities->getItems() as $speciality}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить специальность {$speciality->fio}')) { location.href='?action=delete&id={$speciality->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$speciality->getId()}">{$speciality->name}</a></td>
            <td>{$speciality->comment}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_specialities/index.right.tpl"}
{/block}