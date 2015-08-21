{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Вопрос по дисциплине {$question->getDiscipline()->getValue()}</h2>
    {CHtml::helpForCurrentPage()}

    <p>
        <strong>Специальность:</strong> {$question->getSpeciality()->getValue()}
    </p>

    <p>
        <strong>Вопрос:</strong> {$question->getText()}
    </p>
{/block}

{block name="asu_right"}
    {include file="_state_exam/_questions/view.right.tpl"}
{/block}