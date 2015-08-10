<p>
    <a href="index.php?action=list">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-desktop-wallpaper.png"><br>
            Управление
        </center></a>
</p>

<p>
    <a href="{$web_root}_modules/_settings/">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/categories/applications-accessories.png"><br>
            Личные настройки
        </center></a>
</p>
{foreach CSession::getCurrentUser()->getLevelsForCurrentTask() as $level}
	{if ($level == 4)}
		<p>
		    <a href="settings.php?action=index">
		        <center>
		            <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-desktop-wallpaper.png"><br>
		            Управление группами
		        </center></a>
		</p>
		
		<p>
		    <a href="#usersGroups" data-toggle="modal"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/categories/applications-accessories.png"><br>
		        Личные настройки групп
		    </center></a>
		</p>
		
		<div id="usersGroups" class="modal hide fade">
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h3>Выберите группу</h3>
		    </div>
			<div class="modal-body">
				
			</div>
		</div>
	{/if}
{/foreach}

<script>
    jQuery("#usersGroups").on("show", function(){
        var place = jQuery(".modal-body", this);
        jQuery(place).html('<div style="text-align: center;"><img src="' + web_root + 'images/loader.gif"></div>');
    });
    jQuery("#usersGroups").on("shown", function(){
        var place = jQuery(".modal-body", this);
        jQuery.ajax({
            url: "{$web_root}_modules/_settings/?action=usersGroups",
            type: "GET",
            cache: false,
            context: this,
            success: function(data){
                jQuery(this).find(".modal-body").html(data);
            }
        });
    });
</script>