<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $filialGoing)}
    
    <div class="control-group">
        {CHtml::activeLabel("kadri_id", $filialGoing)}
        <div class="controls">
        	{CHtml::activeLookup("kadri_id", $filialGoing, "staff")}
            {CHtml::error("kadri_id", $filialGoing)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("filial_id", $filialGoing)}
        <div class="controls">
        	{CHtml::activeLookup("filial_id", $filialGoing, "filials")}
            {CHtml::error("filial_id", $filialGoing)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("day_cnt", $filialGoing)}
        <div class="controls">
	        {CHtml::activeTextField("day_cnt", $filialGoing)}
	        {CHtml::error("day_cnt", $filialGoing)}
    	</div>
    </div>
        
    <div class="control-group">
        {CHtml::activeLabel("hours_cnt", $filialGoing)}
        <div class="controls">
	        {CHtml::activeTextField("hours_cnt", $filialGoing)}
	        {CHtml::error("hours_cnt", $filialGoing)}
    	</div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("day_start", $filialGoing)}
        <div class="controls">
	        {CHtml::activeDateField("day_start", $filialGoing)}
	        {CHtml::error("day_start", $filialGoing)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("day_end", $filialGoing)}
        <div class="controls">
	        {CHtml::activeDateField("day_end", $filialGoing)}
	        {CHtml::error("day_end", $filialGoing)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("filial_act_id", $filialGoing)}
        <div class="controls">
        	{CHtml::activeLookup("filial_act_id", $filialGoing, "filial_actions")}
            {CHtml::error("filial_act_id", $filialGoing)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("transport_type_id", $filialGoing)}
        <div class="controls">
        	{CHtml::activeLookup("transport_type_id", $filialGoing, "transport")}
            {CHtml::error("transport_type_id", $filialGoing)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("comment", $filialGoing)}
        <div class="controls">
	        {CHtml::activeTextField("comment", $filialGoing)}
	        {CHtml::error("comment", $filialGoing)}
    	</div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>