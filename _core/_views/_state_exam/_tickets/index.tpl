{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Билеты к ГОС экзаменам</h2>

<table width="100%" cellpadding="0" cellspacing="0" border="1" id="dataTable">
    <thead>
    <tr>
        <th width="5">#</th>
        <th width="10%">Год</th>
        <th width="10%">Специальность</th>
        <th width="70%">Вопросы</th>
        <th width="10%">Протокол</th>
    </tr>
    </thead>
    <tbody>
        {foreach $tickets as $ticket}
        <tr>
            <td><a href="?action=view&id={$ticket->getId()}">{$ticket->getNumber()}</a></td>
            <td>{$ticket->getYear()->getValue()}</td>
            <td>{$ticket->getSpeciality()->getValue()}</td>
            <td>
                <ul>
                {foreach $ticket->getQuestions()->getItems() as $q}
                    <li>
                        ({$q->getDiscipline()->getValue()}) {$q->getText()}
                    </li>
                {/foreach}
                </ul>
            </td>
            <td>Протокол №{$ticket->getProtocol()->getNumber()} от {$ticket->getProtocol()->getDate()}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
{/block}

{block name="asu_right"}
{include file="_state_exam/_tickets/index.right.tpl"}
{/block}