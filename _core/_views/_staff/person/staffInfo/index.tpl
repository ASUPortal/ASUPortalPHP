    {if ($form->person->infoPages->getCount() == 0)}
        Информации о сотруднике нет
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>Название страницы</th>
                <th>Сотрудник</th>
            </tr>
            {counter start=0 print=false}
            {foreach $form->person->infoPages->getItems() as $page}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить страницу {$page->title}')) { location.href='staffInfo.php?action=delete&id={$page->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="staffInfo.php?action=edit&id={$page->getId()}">{$page->title}</a></td>
                    <td>
                        {if !is_null($page->getAuthor())}
                            {$page->getAuthor()->getName()}
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    {/if}