{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <script>
        jQuery(document).ready(function(){
            jQuery("#tabs").tabs();
        });
    </script>

    <h2>Мои исходящие сообщения</h2>

    {include file="_messages/subform.subscription.tpl"}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-inbox" onclick="location.href='?action=inbox#tab-inbox'">Входящие</a></li>
            <li><a href="#tab-outbox">Исходящие</a></li>
            <li><a href="#tab-new">Написать сообщение</a></li>
        </ul>
        <div id="tab-outbox">
            {if $messages->getCount() == 0}
                Вам еще никому не писали!
            {else}
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>{CHtml::tableOrder("mail_title", $messages->getFirstItem())}</th>
                        <th>{CHtml::tableOrder("to_user_id", $messages->getFirstItem())}</th>
                        <th>{CHtml::tableOrder("date_send", $messages->getFirstItem())}</th>
                        <th>&nbsp;</th>
                    </tr>
                    {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
                    {foreach $messages->getItems() as $mail}
                        <tr>
                            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить сообщение {$mail->getTheme()}')) { location.href='?action=delete&id={$mail->getId()}'; }; return false;"></a></td>
                            <td>{counter}</td>
                            <td>
                                <a href="?action=view&id={$mail->getId()}">
                                    {if ($mail->isRead())}
                                        {$mail->getTheme()}
                                    {else}
                                        <b>{$mail->getTheme()}</b>
                                    {/if}
                                </a>
                            </td>
                            <td>
                                {if !is_null($mail->getRecipient())}
                                    {$mail->getRecipient()->getName()}
                                {/if}
                            </td>
                            <td>{$mail->getSendDate()}</td>
                            <td>
                                {if $mail->file_name !== ""}
                                    <a href="{$web_root}f_mails/{$mail->file_name}">
                                        <img src="{$web_root}images/tango/16x16/devices/media-floppy.png">
                                    </a>
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </table>
                {CHtml::paginator($paginator, "?action=outbox")}
            {/if}
        </div>
        <div id="tab-inbox">
            <img src="{$web_root}images/loading.gif">
        </div>
        <div id="tab-new">
            {include file="_messages/subform.new.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_messages/inbox.right.tpl"}
{/block}