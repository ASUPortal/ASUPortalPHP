<form action="sciences.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("id_kadri", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("id_year", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("id_year", $object, CTaxonomyManager::getYearsList())}
            {CHtml::error("id_year", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("id_vidov_rabot", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("id_vidov_rabot", $object, CIndPlanManager::getWorklistByCategory(3))}
            {CHtml::error("id_vidov_rabot", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("prim", $object)}
        <div class="controls">
            {CHtml::activeTextField("prim", $object)}
            {CHtml::error("prim", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("srok_vipolneniya", $object)}
        <div class="controls">
            {CHtml::activeDateField("srok_vipolneniya", $object)}
            {CHtml::error("srok_vipolneniya", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("kol_vo_plan", $object)}
        <div class="controls">
            {CHtml::activeTextField("kol_vo_plan", $object)}
            {CHtml::error("kol_vo_plan", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("vid_otch", $object)}
        <div class="controls">
            {CHtml::activeTextField("vid_otch", $object)}
            {CHtml::error("vid_otch", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("kol_vo", $object)}
        <div class="controls">
            {CHtml::activeTextField("kol_vo", $object)}
            {CHtml::error("kol_vo", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $object)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $object)}
            {CHtml::error("comment", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>