<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="10"></th>
        <th width="10">#</th>
        <th>Название</th>
        <th width="20%">Доп. информация</th>
        <th width="32"><i class="icon-camera"></i></th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->portfoliopapers->getItems() as $portfolio}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить портфолио?')) { location.href='papers.php?action=delete&id={$portfolio->getId()}'; }; return false;"></a></td>
            <td><a href="papers.php?action=edit&id={$portfolio->getId()}">{counter}</a></td>
            <td>{$portfolio->tema}</td>
            <td>{$portfolio->comment}</td>
            <td>{CHtml::activeAttachPreview("file_attach", $portfolio, true)}</td>
        </tr>
    {/foreach}
</table>