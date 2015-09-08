{extends file="_public.main.tpl"}

{block name="asu_center"}
    <h2>Новости</h2>
    {if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}

    {if ($news->getCount() == 0)}
        Нет новостей для отображения
    {else}
        <script>
            function news_show_full(news_id) {
                jQuery("#preview_" + news_id).hide();
                jQuery("#full_" + news_id).show();
            }
        </script>
		<script>
			jQuery(document).ready(function(){
				jQuery("a.image_clearboxy").colorbox({
					maxHeight: "100%",
					title: function(){
						var url = $(this).attr('href');
						return '<a href="' + url + '" target="_blank">Открыть в полном размере</a>';
					}
				});
			});
		</script>

        {CHtml::paginator($paginator, "?action=index")}
        <table class="table table-striped table-bordered table-hover table-condensed">
            {foreach $news->getItems() as $newsItem}
                <tr>
					<td width="120" valign="top">
						<a href="{$web_root}{$newsItem->getImagePath()}" target="_blank" class="image_clearboxy"><img src="{$web_root}_modules/_thumbnails/?src={$newsItem->getImagePath()}&q=100&w=135" align="middle"></a>
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

{block name="asu_right"}
    {if (CSession::isAuth())}
        {include file="_public.actions.tpl"}
    {/if}
{/block}