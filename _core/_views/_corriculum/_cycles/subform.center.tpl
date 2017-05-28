<ul class="nav nav-pills">
	<li>
		<a href="index.php?action=view&id={$cycle->corriculum_id}"><center>
		    <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
		    Назад к учебному плану
		</center></a>
	</li>
	<li>
	    <a href="?action=del&id={$cycle->getId()}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
	        Удалить цикл дисциплин
	    </center></a>
	</li>
	<li>
		<a href="disciplines.php?action=add&id={$cycle->getId()}"><center>
		    <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
		    Добавить дисциплину
		</center></a>
	</li>    
</ul>