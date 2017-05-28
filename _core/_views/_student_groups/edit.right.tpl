<p>
    <a href="?action=index"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="{$web_root}_modules/_students/index.php?action=index&filter=group_id:{$group->getId()}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/system-users.png"><br>
        Студенты
    </center></a>
</p>

{include file="_printGroupOnTemplate.tpl"}

<p>
    <a href="#studentsWithoutMarks" data-toggle="modal"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/system-file-manager.png"><br>
        Студенты без оценок
    </center></a>
</p>

<div id="studentsWithoutMarks" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Студенты без оценок</h3>
    </div>
    <div class="modal-body">

    </div>
</div>

<script>
    jQuery("#studentsWithoutMarks").on("show", function(){
        var place = jQuery(".modal-body", this);
        jQuery(place).html('<div style="text-align: center;"><img src="' + web_root + 'images/loader.gif"></div>');
    });
    jQuery("#studentsWithoutMarks").on("shown", function(){
        var place = jQuery(".modal-body", this);
        jQuery.ajax({
            url: "{$web_root}_modules/_student_groups/index.php?action=GetStudentsWithoutMarks&id={$group->getId()}",
            type: "GET",
            cache: false,
            context: this,
            success: function(data){
                jQuery(this).find(".modal-body").html(data);
            }
        });
    });
</script>

{CHtml::displayActionsMenu($_actions_menu)}