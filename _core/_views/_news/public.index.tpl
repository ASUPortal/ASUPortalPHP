{extends file="_public.main.tpl"}

{block name="asu_center"}
    <h2>Новости</h2>

    {if ($news->getCount() == 0)}
        Нет новостей для отображения
    {else}
        <script>
            function news_show_full(news_id) {
                jQuery("#preview_" + news_id).hide();
                jQuery("#full_" + news_id).show();
            }
        </script>

        {CHtml::paginator($paginator, "?action=index")}
        <table border="1" cellpadding="5" cellspacing="0" width="100" class="tableBlank">
            {foreach $news->getItems() as $newsItem}
                <tr>
                    <td width="130" valign="top">
                        <img src="{$newsItem->getImagePath()}" hspace="5" vspace="5">
                    </td>
                    <td valign="top" style="padding-left: 5px; padding-right: 5px; ">
                        <p><b>{$newsItem->title}</b> - {$newsItem->getPublicationDate()}</p>
                        {if ($newsItem->getAuthorName() !== "")}
                            {if ($newsItem->getAuthorLink() !== "")}
                                <p><a href="{$newsItem->getAuthorLink()}">{$newsItem->getAuthorName()}</a></p>
                            {else}
                                <p>{$newsItem->getAuthorName()}</p>
                            {/if}
                        {/if}
                        <p>
                            {$newsItem->getBody()}
                        </p>
                    </td>
                    <td valign="top" width="50">
                        {if ($newsItem->getAttachLink() !== "")}
                            <p>
                                <center>
                                <a href="{$newsItem->getAttachLink()}">
                                    <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save.png" border="0"><br />
                                    Скачать
                                </a>
                                </center>
                            </p>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{if (CSession::isAuth())}
    {block name="asu_right"}
        {include file="_news/public.index.right.tpl"}
    {/block}
{/if}