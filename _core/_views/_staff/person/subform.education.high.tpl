<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>ВУЗ</th>
        <th>Год окончания</th>
        <th>Специальность в дипломе</th>
        <th>Доп. информация</th>
    </tr>
    {foreach $form->person->diploms->getItems() as $diplom}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить диплом?')) { location.href='diploms.php?action=delete&id={$diplom->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td><a href="diploms.php?action=edit&id={$diplom->getId()}">{counter}</a></td>
            <td>{$diplom->zaved_name}</td>
            <td>{$diplom->god_okonch}</td>
            <td>{$diplom->spec_name}</td>
            <td>{$diplom->comment}</td>
        </tr>
    {/foreach}
</table>