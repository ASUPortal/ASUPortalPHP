<p>
    <a href="index.php?action=inbox">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Вся сообщения
        </center></a>
</p>

<p>
    <a href="index.php?action=reply&id={$mail->getId()}">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/mail-reply-all.png"><br>
            Ответить на это сообщение
        </center></a>
</p>

<p>
    <a href="index.php?action=forward&id={$mail->getId()}">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/mail-forward.png"><br>
            Переслать это сообщение
        </center></a>
</p>