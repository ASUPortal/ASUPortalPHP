<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveItem")}
    {CHtml::activeHiddenField("menu_id", $item)}
    {CHtml::activeHiddenField("id", $item)}

    <p>
        {CHtml::activeLabel("title", $item)}
        {CHtml::activeTextField("title", $item)}
        {CHtml::error("title", $item)}
    </p>

    <p>
        {CHtml::activeLabel("anchor", $item)}
        {CHtml::activeTextField("anchor", $item)}
        {CHtml::error("anchor", $item)}
    </p>

    <p>
        {CHtml::activeLabel("parent_id", $item)}
        {if !is_null($menu)}
            {CHtml::activeDropDownList("parent_id", $item, $menu->getMenuItemsList())}
        {else}
            {CHtml::activeDropDownList("parent_id", $item, array())}
        {/if}
        {CHtml::error("parent_id", $item)}
    </p>

    <p>
        {CHtml::activeLabel("order", $item)}
        {CHtml::activeTextField("order", $item)}
        {CHtml::error("order", $item)}
    </p>

    <p>
        {CHtml::activeLabel("published", $item)}
        {CHtml::activeCheckBox("published", $item)}
        {CHtml::error("published", $item)}
    </p>


    {if !is_null($item->id) & ($item->id !== "")}
    <p>
        {CHtml::activeLabel("roles", $item)}
        <div style="margin-left: 200px;">
            {CHtml::activeCheckBoxGroup("roles", $item, CStaffManager::getAllUserRolesList())}
        </div>
        {CHtml::error("roles", $item)}
    </p>
    {/if}

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>