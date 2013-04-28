<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>Степень</th>
        <th>Год присвоения</th>
        <th>Серия, номер свидетельства</th>
        <th>Доп. информация</th>
    </tr>
    {foreach $form->person->degrees->getItems() as $degree}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить звание?')) { location.href='degrees.php?action=delete&id={$degree->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td><a href="degrees.php?action=edit&id={$degree->getId()}">{counter}</a></td>
            <td>
                {if !is_null($degree->degree)}
                    {$degree->degree->getValue()}
                {/if}
            </td>
            <td>{$degree->year}</td>
            <td>номер {$degree->doc_num}, серия {$degree->doc_series}</td>
            <td>{$degree->comment}</td>
        </tr>
    {/foreach}
</table>