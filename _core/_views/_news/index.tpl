{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <script>
        function news_show_full(news_id) {
            jQuery("#preview_" + news_id).hide();
            jQuery("#full_" + news_id).show();
        }
    </script>

    <h2>Мои новости</h2>

    {if ($news->getCount() == 0)}
        У Вас еще нет новостей
    {else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>Заголовок</th>
            <th>Текст новости</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $news->getItems() as $newsItem}
        <tr valign="top">
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить новость {$newsItem->title}')) { location.href='?action=delete&id={$newsItem->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$newsItem->getId()}">{$newsItem->title}</a></td>
            <td>{$newsItem->getBody()}</td>
        </tr>
        {/foreach}
    </table>
    {/if}

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_news/index.right.tpl"}
{/block}