{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Вопросы к ГОС экзаменам</h2>
    {CHtml::helpForCurrentPage()}

        <table width="100%" cellpadding="0" cellspacing="0" border="1" id="dataTable">
            <thead>
                <tr>
                    <th width="5"></th>
                    <th width="20%">Дисциплина</th>
                    <th width="70%">Вопрос</th>
                    <th width="10%">Специальность</th>
                </tr>
            </thead>
            <tbody>
            {foreach $questions as $q}
            <tr>
                <td valign="top"><a href="?action=view&id={$q->getId()}">{$q->getId()}</a></td>
                <td valign="top">{$q->getDiscipline()->getValue()}</td>
                <td valign="top">{$q->getText()|nl2br}</td>
                <td valign="top">{$q->getSpeciality()->getValue()}</td>
            </tr>
            {/foreach}
            </tbody>
        </table>
{/block}

{block name="asu_right"}
    {include file="_state_exam/_questions/index.right.tpl"}
{/block}