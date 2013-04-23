{extends file="_public.main.tpl"}

{block name="asu_center"}
    <h2>Учебные материалы</h2>

    <table class="tableBlank">
        {foreach $files->getItems() as $file}
            <tr>
                <td valign="top" width="32">
                    {if $file->getIconImagePath() !== ""}
                        <img src="{$file->getIconImagePath()}">
                    {/if}
                </td>
                <td>
                    <a href="{$file->getDownloadLink()}">
                        {$file->browserFile}
                    </a>
                </td>
            </tr>
        {/foreach}
    </table>
{/block}

{block name="asu_right"}
    {if (CSession::isAuth())}
        {include file="_public.actions.tpl"}
    {/if}
{/block}