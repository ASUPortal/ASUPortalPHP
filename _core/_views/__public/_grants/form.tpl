<form action="grants.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
    {CHtml::activeLabel("id", $object)}
    <div class="controls">
        {CHtml::activeTextField("id", $object)}
        {CHtml::error("id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("title", $object)}
    <div class="controls">
        {CHtml::activeTextField("title", $object)}
        {CHtml::error("title", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("number", $object)}
    <div class="controls">
        {CHtml::activeTextField("number", $object)}
        {CHtml::error("number", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $object)}
    <div class="controls">
        {CHtml::activeTextField("comment", $object)}
        {CHtml::error("comment", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("description", $object)}
    <div class="controls">
        {CHtml::activeTextField("description", $object)}
        {CHtml::error("description", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("author_id", $object)}
    <div class="controls">
        {CHtml::activeTextField("author_id", $object)}
        {CHtml::error("author_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("manager_id", $object)}
    <div class="controls">
        {CHtml::activeTextField("manager_id", $object)}
        {CHtml::error("manager_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_start", $object)}
    <div class="controls">
        {CHtml::activeTextField("date_start", $object)}
        {CHtml::error("date_start", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_end", $object)}
    <div class="controls">
        {CHtml::activeTextField("date_end", $object)}
        {CHtml::error("date_end", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("finances_planned", $object)}
    <div class="controls">
        {CHtml::activeTextField("finances_planned", $object)}
        {CHtml::error("finances_planned", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("finances_accepted", $object)}
    <div class="controls">
        {CHtml::activeTextField("finances_accepted", $object)}
        {CHtml::error("finances_accepted", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("finances_source", $object)}
    <div class="controls">
        {CHtml::activeTextField("finances_source", $object)}
        {CHtml::error("finances_source", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>