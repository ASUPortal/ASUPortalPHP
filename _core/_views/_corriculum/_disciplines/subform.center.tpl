<ul class="nav nav-pills">
	<li>
	    <a href="index.php?action=view&id={$discipline->cycle->corriculum->getId()}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
	        Назад к учебному плану
	    </center></a>
	</li>
	<li>
	    <a href="cycles.php?action=edit&id={$discipline->cycle_id}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
	        Назад к циклу дисциплин
	    </center></a>
	</li>
	<li>
	    <a href="#" onclick="if (confirm('Действительно удалить дисциплину {$discipline->discipline->getValue()}?\n\rПеред удалением необходимо сначала удалить рабочие программы дисциплины')) 
	    { location.href='?action=del&id={$discipline->getId()}'; }; return false;">
	    <center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
	        Удалить дисциплину
	    </center></a>
	</li>
	<li>
	    <a href="#" onclick="if (confirm('Действительно удалить все рабочие программы?')) 
	    { location.href='?action=delWorkplans&id={$discipline->getId()}'; }; return false;">
	    <center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
	        Удалить все рабочие программы дисциплины
	    </center></a>
	</li> 
</ul>