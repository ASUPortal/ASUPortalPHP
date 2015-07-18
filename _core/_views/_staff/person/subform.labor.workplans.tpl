<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Наименование программы</th>
        <th>Дисциплина</th>
        <th>Форма обучения</th>
    </tr>
    {foreach $form->person->workplans->getItems() as $workplan}
        <tr>
            <td>{counter}</td>
            <td><a href="{$web_root}_modules/_corriculum/workplans.php?action=edit&id={$workplan->getId()}" class="icon-pencil"></a></td>
            <td>{$workplan->title}</td>
            <td>{$workplan->discipline}</td>
            <td>{$workplan->education_form}</td>
        </tr>
    {/foreach}
</table>