<form action="attestations.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("corriculum_id", $object)}

    <div class="control-group">
        {CHtml::activeLabel("type_id", $object)}
        <div class="controls">
        {CHtml::activeDropDownList("type_id", $object, CTaxonomyManager::getTaxonomy("attestation_types")->getTermsList())}
        {CHtml::error("type_id", $object)}
        </div> 
    </div>

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $object)}
        <div class="controls">
        {CHtml::activeLookup("discipline_id", $object, "subjects")}
        {CHtml::error("discipline_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("alias", $object)}
        <div class="controls">
        {CHtml::activeTextField("alias", $object)}
        {CHtml::error("alias", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("length", $object)}
        <div class="controls">
        {CHtml::activeTextField("length", $object)}
        {CHtml::error("length", $object)}
        </div>
    </div>
        
    <div class="control-group">
        {CHtml::activeLabel("length_credits", $object)}
        <div class="controls">
        {CHtml::activeTextField("length_credits", $object)}
        {CHtml::error("length_credits", $object)}
        </div>
    </div>        
        
    <div class="control-group">
        {CHtml::activeLabel("length_hours", $object)}
        <div class="controls">
        {CHtml::activeTextField("length_hours", $object)}
        {CHtml::error("length_hours", $object)}
        </div>
    </div>        

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>