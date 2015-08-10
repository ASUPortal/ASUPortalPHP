{CHtml::displayActionsMenu($_actions_menu)}

{foreach CSession::getCurrentUser()->getLevelsForCurrentTask() as $level}
	{if ($level == 4)}
		<p>
		    <a href="#usersGroups" data-toggle="modal"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
		        Добавить элемент для группы
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
            url: "{$web_root}_modules/_dashboard/settings.php?action=usersGroups",
            type: "GET",
            cache: false,
            context: this,
            success: function(data){
                jQuery(this).find(".modal-body").html(data);
            }
        });
    });
</script>