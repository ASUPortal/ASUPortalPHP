{extends file="_core.3col.tpl"}

{block name="asu_center"}
<script>
    jQuery(document).ready(function(){
        jQuery("#members").namesSelector();
    });
</script>

<h2>Редактирование состава группы {$group->name}</h2>

    <form action="groups.php" method="post">
        {CHtml::hiddenField("action", "saveMembers")}
        {CHtml::activeHiddenField("id", $group)}

        <p>
            {CHtml::activeLabel("members", $group)}
            {CHtml::activeNamesSelect("members", $group)}
            {CHtml::error("members", $group)}
        </p>

        <p>
            {CHtml::submit("Сохранить")}
        </p>
    </form>
{/block}

{block name="asu_right"}
{include file="_acl_manager/groups/manageMembers.right.tpl"}
{/block}