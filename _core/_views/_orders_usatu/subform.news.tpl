{if ($order->news->getCount() == 0)}
    По данному приказу не были добавлены новости
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>Заголовок</th>
            <th>Текст сообщения</th>
        </tr>
        {foreach $order->news->getItems() as $news}
            <tr><td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить новость {$news->title}')) { location.href='?action=deleteNewsItem&id={$news->id}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="{$web_root}_modules/_news/?action=edit&id={$news->getId()}">{$news->title}</a></td>
                <td>{$news->file}</td>
            </tr>
        {/foreach}
    </table>
{/if}