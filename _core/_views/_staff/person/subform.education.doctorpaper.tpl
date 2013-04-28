<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>Тема</th>
        <th>Номер спец-ти по ВАК</th>
        <th>Год защиты</th>
        <th>Доп. информация</th>
    </tr>
    {foreach $form->person->doctorpapers->getItems() as $paper}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить курсы?')) { location.href='papers.php?action=delete&id={$paper->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td><a href="papers.php?action=edit&id={$paper->getId()}">{counter}</a></td>
            <td>{$paper->tema}</td>
            <td>{$paper->spec_nom}</td>
            <td>{$paper->god_zach}</td>
            <td>{$paper->comment}</td>
        </tr>
    {/foreach}
</table>