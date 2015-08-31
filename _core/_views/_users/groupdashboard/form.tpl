<form action="groupdashboard.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("user_id", $object)}
    {CHtml::activeHiddenField("group_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("title", $object)}
        <div class="controls">
            {CHtml::activeTextField("title", $object)}
            {CHtml::error("title", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("link", $object)}
        <div class="controls">
            {CHtml::activeTextField("link", $object)}
            {CHtml::error("link", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("icon", $object)}
        <div class="controls">
            <select name="CDashboardItem[icon]" class="span5">
                {foreach $icons->getItems() as $file}
                    <option value="{$file}" data-image="{$web_root}images/{$icon_theme}/16x16/{$file}"
                            {if $object->icon == $file}selected{/if}>
                        <img src="{$web_root}images/{$icon_theme}/16x16/{$file}"/>
                        {$file}
                    </option>
                {/foreach}
            </select>
            {CHtml::error("icon", $object)}
        </div>
    </div>

<div class="control-group">
    {CHtml::activeLabel("parent_id", $object)}
    <div class="controls">
        {CHtml::activeDropDownList("parent_id", $object, $parents->getItems())}
        {CHtml::error("parent_id", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>