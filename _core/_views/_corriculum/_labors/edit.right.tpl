<p>
	{if is_null($labor->section)}
	<a href="disciplines.php?action=edit&id={$labor->discipline_id}"><center>
	{else}
    <a href="disciplines.php?action=edit&id={$labor->section->discipline_id}"><center>
    {/if}
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="#" onclick="if (confirm('Действительно удалить нагрузку?')) 
	{ location.href='?action=del&id={$labor->getId()}'; }; return false;">
    <center>
        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
        Удалить
    </center></a>
</p>