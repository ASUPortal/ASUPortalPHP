<form action="controllers.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "generate")}

    <div class="control-group">
        {CHtml::activeLabel("controllerName", $controller)}
        <div class="controls">
            {CHtml::activeTextField("controllerName", $controller)}
            {CHtml::error("controllerName", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("controllerPath", $controller)}
        <div class="controls">
            {CHtml::activeTextField("controllerPath", $controller)}
            {CHtml::error("controllerPath", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("controllerFile", $controller)}
        <div class="controls">
            {CHtml::activeTextField("controllerFile", $controller)}
            {CHtml::error("controllerFile", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("pageTitle", $controller)}
        <div class="controls">
            {CHtml::activeTextField("pageTitle", $controller)}
            {CHtml::error("pageTitle", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("modelName", $controller)}
        <div class="controls">
            {CHtml::activeTextField("modelName", $controller)}
            {CHtml::error("modelName", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("modelTable", $controller)}
        <div class="controls">
            {CHtml::activeTextField("modelTable", $controller)}
            {CHtml::error("modelTable", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("modelGenerate", $controller)}
        <div class="controls">
            {CHtml::activeCheckbox("modelGenerate", $controller, 1)}
            {CHtml::error("modelGenerate", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("modelPath", $controller)}
        <div class="controls">
            {CHtml::activeTextField("modelPath", $controller)}
            {CHtml::error("modelPath", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("modelManager", $controller)}
        <div class="controls">
            {CHtml::activeTextField("modelManager", $controller)}
            {CHtml::error("modelManager", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("modelManagerGetter", $controller)}
        <div class="controls">
            {CHtml::activeTextField("modelManagerGetter", $controller)}
            {CHtml::error("modelManagerGetter", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("viewPath", $controller)}
        <div class="controls">
            {CHtml::activeTextField("viewPath", $controller)}
            {CHtml::error("viewPath", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("viewIndexTitle", $controller)}
        <div class="controls">
            {CHtml::activeTextField("viewIndexTitle", $controller)}
            {CHtml::error("viewIndexTitle", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("viewIndexNoObjects", $controller)}
        <div class="controls">
            {CHtml::activeTextField("viewIndexNoObjects", $controller)}
            {CHtml::error("viewIndexNoObjects", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("viewObjectSingleName", $controller)}
        <div class="controls">
            {CHtml::activeTextField("viewObjectSingleName", $controller)}
            {CHtml::error("viewObjectSingleName", $controller)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("viewObjectSingleNameRP", $controller)}
        <div class="controls">
            {CHtml::activeTextField("viewObjectSingleNameRP", $controller)}
            {CHtml::error("viewObjectSingleNameRP", $controller)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Генерировать", false)}
        </div>
    </div>
</form>