{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Билеты к ГОС экзаменам</h2>
    {CHtml::helpForCurrentPage()}

    <p align="center">
        ФГБОУ ВПО УФИМСКИЙ ГОСУДАРСТВЕННЫЙ АВИАЦИОННЫЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ
    </p>

    <p align="center">
        Кафедра АСУ
    </p>

    <p align="center">
        <strong>ЭКЗАМЕНАЦИОННЫЙ ВОПРОС №{$ticket->getNumber()}</strong>
    </p>

    <p align="center">
        По специальности {$ticket->getSpeciality()->getValue()}
    </p>

    <p>
        <ol>
            {foreach $ticket->getQuestions()->getItems() as $question}
            <li>{$question->getText()|nl2br}</li>
            {/foreach}
        </ol>
    </p>

    <p align="right">
        {$ticket->getSigner()->getPost()->getValue()} {$ticket->getSigner()->getName()}
    </p>

    <p align="right">
        Протокол №{$ticket->getProtocol()->getNumber()} от {$ticket->getProtocol()->getDate()}
    </p>
{/block}

{block name="asu_right"}
{include file="_state_exam/_tickets/view.right.tpl"}
{/block}