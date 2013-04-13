{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Просмотр сообщения</h2>

    <form>
        <p>
            <label>От кого:</label>
            {if !is_null($mail->getSender())}
                {$mail->getSender()->getName()}
            {/if}
        </p>

        <p>
            <label>Кому:</label>
            {if !is_null($mail->getRecipient())}
                {$mail->getRecipient()->getName()}
            {/if}
        </p>

        <p>
            <label>Тема:</label>
            {$mail->getTheme()}
        </p>

        <p>
            <label>Текст сообщения:</label>
            {$mail->getBody()}
        </p>
    </form>
{/block}

{block name="asu_right"}
    {include file="_messages/view.right.tpl"}
{/block}