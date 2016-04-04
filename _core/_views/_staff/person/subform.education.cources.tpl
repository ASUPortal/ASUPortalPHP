<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>Название курсов</th>
        <th>Место проведения</th>
        <th>Время проведения</th>
        <th>Документ по завершении</th>
        <th>Доп. информация</th>
        <th width="32"><i class="icon-camera"></i></th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->cources->getItems() as $course}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить курсы?')) { location.href='courses.php?action=delete&id={$course->getId()}'; }; return false;"></a></td>
            <td><a href="courses.php?action=edit&id={$course->getId()}">{counter}</a></td>
            <td>{$course->name}</td>
            <td>{$course->place}</td>
            <td>{$course->getPeriod()}</td>
            <td>{$course->document}</td>
            <td>{$course->comment}</td>
            <td>{CHtml::activeAttachPreview("file_attach", $course, true)}</td>
        </tr>
    {/foreach}
</table>