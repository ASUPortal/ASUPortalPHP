<div id="attachmentsSubform">
{if $form->grant->attachments->getCount() == 0}
    Вложений пока нет
{else}
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>Вложение</th>
            <th>Автор</th>
        </tr>
    {foreach $form->grant->attachments->getItems() as $attach}
        <tr>
            <td>
                {if $attach->attach_name == ""}
                    <a href="{$web_root}library/grants/{$attach->filename}">{$attach->filename}</a>
                {else}
                    <a href="{$web_root}library/grants/{$attach->filename}">{$attach->attach_name}</a>
                {/if}
            </td>
            <td>
                {if !is_null($attach->author)}
                    {$attach->author->getName()}
                {/if}
            </td>
        </tr>
    {/foreach}
    </table>
{/if}
</div>