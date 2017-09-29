<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="10"></th>
        <th width="10">#</th>
        <th width="30%">Тема</th>
        <th>Номер спец-ти по ВАК</th>
        <th>Год защиты</th>
        <th width="20%">Доп. информация</th>
        <th width="32"><i class="icon-camera"></i></th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->phdpapers->getItems() as $paper}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить курсы?')) { location.href='papers.php?action=delete&id={$paper->getId()}'; }; return false;"></a></td>
            <td><a href="papers.php?action=edit&id={$paper->getId()}">{counter}</a></td>
            <td>{$paper->tema}</td>
            <td>{$paper->spec_nom}</td>
            <td>{$paper->god_zach}</td>
            <td>{$paper->comment}</td>
            <td>{CHtml::activeAttachPreview("file_attach", $paper, true)}</td>
        </tr>
    {/foreach}
</table>