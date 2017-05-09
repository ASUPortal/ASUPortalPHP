<table border="0" width="30%" class="tableBlank">
    <tr>
        <td>
		    <a href="index.php?action=view&id={$cycle->corriculum_id}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
		        Назад к учебному плану
		    </center></a>
        </td>
        <td>
		    <a href="?action=del&id={$cycle->getId()}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
		        Удалить цикл дисциплин
		    </center></a>
        </td>
        <td>
		    <a href="disciplines.php?action=add&id={$cycle->getId()}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
		        Добавить дисциплину
		    </center></a>
        </td>
    </tr>
</table>