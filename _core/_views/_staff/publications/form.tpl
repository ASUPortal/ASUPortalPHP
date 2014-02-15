<form action="publications.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("name", $object)}
        <div class="controls">
            {CHtml::activeTextBox("name", $object)}
            {CHtml::error("name", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("bibliografya", $object)}
        <div class="controls">
            {CHtml::activeTextBox("bibliografya", $object)}
            {CHtml::error("bibliografya", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("grif", $object)}
        <div class="controls">
            {CHtml::activeTextField("grif", $object)}
            {CHtml::error("grif", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("publisher", $object)}
        <div class="controls">
            {CHtml::activeTextBox("publisher", $object)}
            {CHtml::error("publisher", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("year", $object)}
        <div class="controls">
            {CHtml::activeTextField("year", $object)}
            {CHtml::error("year", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("page_range", $object)}
        <div class="controls">
            {CHtml::activeTextField("page_range", $object)}
            {CHtml::error("page_range", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("volume", $object)}
        <div class="controls">
            {CHtml::activeTextField("volume", $object)}
            {CHtml::error("volume", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("type_book", $object)}
        <div class="controls">
            {CHtml::activeLookup("type_book", $object, "izdan_type")}
            {CHtml::error("type_book", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("approve_date", $object)}
        <div class="controls">
            {CHtml::activeDateField("approve_date", $object)}
            {CHtml::error("approve_date", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("authors", $object)}
        <div class="controls">
            {CHtml::activeLookup("authors", $object, "staff", true)}
            {CHtml::error("authors", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("authors_all", $object)}
        <div class="controls">
            {CHtml::activeTextBox("authors_all", $object)}
            {CHtml::error("authors_all", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("copy", $object)}
        <div class="controls">
            {CHtml::activeUpload("copy", $object)}
            {CHtml::error("copy", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>