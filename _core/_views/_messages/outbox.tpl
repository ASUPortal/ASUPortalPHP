{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Мои исходящие сообщения</h2>

    {include file="_messages/subform.subscription.tpl"}


    <ul class="nav nav-tabs">
        <li><a href="?action=inbox">Входящие</a></li>
        <li class="active"><a href="#tab-outbox" data-toggle="tab">Исходящие</a></li>
        <li><a href="#tab-new" data-toggle="tab">Написать сообщение</a></li>
    </ul>
    <div class="tab-content" id="tabs">
        <div class="tab-pane active" id="tab-outbox">
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
        <div class="tab-pane" id="tab-new">
            {include file="_messages/subform.new.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_messages/inbox.right.tpl"}
{/block}