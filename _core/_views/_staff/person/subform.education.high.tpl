<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="10"></th>
        <th width="10">#</th>
        <th width="30%">ВУЗ</th>
        <th>Год окончания</th>
        <th>Специальность в дипломе</th>
        <th width="20%">Доп. информация</th>
        <th width="32"><i class="icon-camera"></i></th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->diploms->getItems() as $diplom}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить диплом?')) { location.href='diploms.php?action=delete&id={$diplom->getId()}'; }; return false;"></a></td>
            <td><a href="diploms.php?action=edit&id={$diplom->getId()}">{counter}</a></td>
            <td>{$diplom->zaved_name}</td>
            <td>{$diplom->god_okonch}</td>
            <td>{$diplom->spec_name}</td>
            <td>{$diplom->comment}</td>
            <td>
                {CHtml::activeAttachPreview("file_attach", $diplom, true)}
            </td>
        </tr>
    {/foreach}
</table>