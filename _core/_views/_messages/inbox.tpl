{extends file="_core.3col.tpl"}

{block name="asu_center"}

    <h2>Мои входящие сообщения</h2>

    {CHtml::helpForCurrentPage()}
    <form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    	<table class="table table-striped table-bordered table-hover table-condensed">
    		<tr>
    			<th>Поиск по:</th>
    			<th>Теме сообщения</th>
    			<th>Тексту сообщения</th>
    			<th>От кого</th>
    		</tr>
    		<tr>
    			<td></td>
    			<td>
    				<input type="checkbox" name="mailTitle" value="1">
    			</td>
    			<td>
    				<input type="checkbox" name="mailText" value="1">
    			</td>
    			<td>
    				<input type="checkbox" name="mailFio" value="1">
    			</td>
    		</tr>
    	</table>
    	{CHtml::textField("search", "")}
    	{CHtml::submit("Найти", false)}
    </form>

    {*include file="_core.searchLocal.tpl"*}
    {include file="_messages/subform.subscription.tpl"}

        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-inbox" data-toggle="tab">Входящие</a></li>
            <li><a href="?action=outbox">Исходящие</a></li>
            <li><a href="#tab-new" data-toggle="tab">Написать сообщение</a></li>
        </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-inbox">
            {if $messages->getCount() == 0}
                Вам никто ничего еще не написал.
            {else}
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>{CHtml::tableOrder("mail_title", $messages->getFirstItem())}</th>
                        <th>{CHtml::tableOrder("from_user_id", $messages->getFirstItem())}</th>
                        <th>{CHtml::tableOrder("date_send", $messages->getFirstItem())}</th>
                        <th>&nbsp;</th>
                    </tr>
                    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
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
                                {if !is_null($mail->getSender())}
                                    {$mail->getSender()->getName()}
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
                {CHtml::paginator($paginator, "?action=inbox")}
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
