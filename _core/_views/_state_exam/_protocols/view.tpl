{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Протокол ГОС экзамена</h2>

    <p align="center">
        УФИМСКИЙ ГОСУДАРСТВЕННЫЙ АВИАЦИОННЫЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ
    </p>

    <p align="center">
        Председатель ГАК {$protocol->getChairman()->getName()} (______________)
    </p>

    <p align="center">
        <strong>ПРОТОКОЛ №{$protocol->getNumber()}</strong>
    </p>

    <p align="center">
        <strong>Заседания экзаменационной комиссии по приему государственного экзамена</strong><br>
        по направлению подготовки специалистов
    </p>

    <p align="center">
        {$protocol->getStudent()->getSpeciality()->getValue()}<br>
        <small>наименование направления (специальности)</small>
    </p>

    <p>
        Экзаменационная комиссия в составе:

        <ol>
            <li>{$protocol->getBoardMaster()->getName()} (председатель)</li>
            {foreach $protocol->getMembers()->getItems() as $member}
                <li>{$member->getName()}</li>
            {/foreach}
        </ol>
    </p>

    <p>
        рассмотрев и заслушав ответ студента {$protocol->getStudent()->getName()}
        факультета ИРТ по билету № {$protocol->getTicket()->getNumber()} и заданные вопросы
        {$protocol->getQuestions()|nl2br}
    </p>

    <p>
        признала ответ соответствующим оценке {$protocol->getMark()->getValue()}
    </p>

    <p align="right">
        {$protocol->getSignDate()}
    </p>

    <table width="100%" class="noStyle">
        <tr>
            <td width="33%"><strong>Председатель ЭК</strong></td>
            <td width="33%">{$protocol->getBoardMaster()->getName()}</td>
            <td width="33%">(______________________________________)</td>
        </tr>

        {$flag = true}
        {foreach $protocol->getMembers()->getItems() as $member}
            <tr>
                <td width="33%">{if $flag}Члены ЭК{/if}{$flag = false}</td>
                <td width="33%">{$member->getName()}</td>
                <td width="33%">(______________________________________)</td>
            </tr>
        {/foreach}
    </table>
{/block}

{block name="asu_right"}
{include file="_state_exam/_protocols/view.right.tpl"}
{/block}