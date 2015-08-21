{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Протоколы ГОС экзаменов</h2>
    {CHtml::helpForCurrentPage()}

    <table border="1" cellpadding="0" cellspacing="0" id="dataTable">
        <thead>
        <tr>
            <th>Номер протокола</th>
            <th>Студент</th>
            <th>Специальность</th>
            <th>Оценка</th>
            <th>Дата подписания</th>
        </tr>
        </thead>
        {foreach $protocols as $protocol}
        <tr>
            <td><a href="?action=view&id={$protocol->getId()}">{$protocol->getNumber()}</a></td>
            <td>{$protocol->getStudent()->getName()}</td>
            <td>{$protocol->getSpeciality()->getValue()}</td>
            <td>{$protocol->getMark()->getValue()}</td>
            <td>{$protocol->getSignDate()}</td>
        </tr>
        {/foreach}
    </table>
{/block}

{block name="asu_right"}
    {include file="_state_exam/_protocols/index.right.tpl"}
{/block}