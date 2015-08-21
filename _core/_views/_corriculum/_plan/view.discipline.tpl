{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$discipline->number} {$discipline->discipline->name}</h2>
    {CHtml::helpForCurrentPage()}

    <p><strong>Трудоемкость</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>Вид учебной нагрузки</th>
                <th>Нагрузка</th>
            </tr>
            {foreach $discipline->labors->getItems() as $labor}
            <tr>
                <td>{$labor->type->name}</td>
                <td>{$labor->value} {$labor->form->name}</td>
            </tr>
            {/foreach}
        </table>
    <p><strong>Форма итогового контроля</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>Форма итогового контроля</th>
                <th>Часов</th>
            </tr>
        {foreach $discipline->controls->getItems() as $control}
            <tr>
                <td>{$control->form->name}</td>
                <td>{$control->value}</td>
            </tr>
        {/foreach}
        </table>
    <p><strong>Распределение трудоемкости дисциплины по курсам и семестрам</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>Семестр</th>
                <th>Часов</th>
            </tr>
            {foreach $discipline->hours->getItems() as $hour}
                <tr>
                    <td>{$hour->period}</td>
                    <td>{$hour->value}</td>
                </tr>
            {/foreach}
        </table>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/viewDiscipline.right.tpl"}
{/block}