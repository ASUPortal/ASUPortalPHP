<p>
    <a href="index.php?action=view&id={$practice->corriculum_id}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="#" onclick="if (confirm('Действительно удалить практику?')) 
	{ location.href='?action=del&id={$practice->getId()}'; }; return false;">
    <center>
        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
        Удалить
    </center></a>
</p>