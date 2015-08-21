{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Мои билеты</h2>
{CHtml::helpForCurrentPage()}

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>Дисциплины</th>
        <th>Специальность</th>
        <th>Курс</th>
    </tr>
    {foreach $tickets->getItems() as $ticket}
        <tr>
            <td valign="top"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить набор билетов')) { location.href='?action=delete&id={$ticket->session_id}'; }; return false;"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="?action=view&id={$ticket->session_id}">{$ticket->getDisciplinesList()|implode}</a></td>
            <td valign="top">{$ticket->speciality->getValue()}</td>
            <td valign="top">{$ticket->course} ({$ticket->year->getValue()})</td>
        </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=my")}
{/block}

{block name="asu_right"}
{include file="_examination/my.right.tpl"}
{/block}