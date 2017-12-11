<div class="control-group">
    {CHtml::activeLabel("title_id", $object)}
    <div class="controls">
        {CHtml::activeDropDownList("title_id", $object, $object->load->person->getPublicationsList(), "", "", $object->restrictionAttribute())}
        {CHtml::error("title_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("paper_pages", $object)}
    <div class="controls">
        {CHtml::activeTextField("paper_pages", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("paper_pages", $object)}
    </div>
</div>