{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Экзаменационные вопросы</h2>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Направление подготовки</th>
            <th>Курс</th>
            <th>Учебный год</th>
            <th>Дисциплина</th>
            <th>Категория</th>
            <th>Вопрос</th>
        </tr>
        {foreach $questions->getItems() as $question}
        <tr>
            <td>{counter}</td>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вопрос по дисциплине {$question->discipline->getValue()}')) { location.href='?action=del&id={$question->id}'; }; return false;"></a></td>
            <td>{$question->speciality->getValue()}</td>
            <td>{$question->course}</td>
            <td>{$question->year->getValue()}</td>
            <td>{$question->discipline->getValue()}</td>
            <td>{$question->category->getValue()}</td>
            <td><a href="?action=edit&id={$question->getId()}">{$question->text|nl2br}</a></td>
        </tr>
        {/foreach}
    </table>
    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_examination/index.right.tpl"}
{/block}