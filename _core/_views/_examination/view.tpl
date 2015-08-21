{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Экзаменационные билеты</h2>
    {CHtml::helpForCurrentPage()}

<table>
    {foreach $tickets->getItems() as $ticket}
    <tr>
        <td valign="top">
            <ol>
            {foreach $ticket->questions->getItems() as $q}
                <li>{$q->text}</li>
            {/foreach}
            </ol>
        </td>
    </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=view&id={CRequest::getInt("id")}")}
{/block}

{block name="asu_right"}
{include file="_examination/view.right.tpl"}
{/block}