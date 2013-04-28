<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>Название курсов</th>
        <th>Место проведения</th>
        <th>Время проведения</th>
        <th>Документ по завершении</th>
        <th>Доп. информация</th>
    </tr>
    {foreach $form->person->cources->getItems() as $course}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить курсы?')) { location.href='courses.php?action=delete&id={$course->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td><a href="courses.php?action=edit&id={$course->getId()}">{counter}</a></td>
            <td>{$course->name}</td>
            <td>{$course->place}</td>
            <td>{$course->getPeriod()}</td>
            <td>{$course->document}</td>
            <td>{$course->comment}</td>
        </tr>
    {/foreach}
</table>