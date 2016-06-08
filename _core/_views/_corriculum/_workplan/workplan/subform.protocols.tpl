<div class="control-group">
    {CHtml::activeLabel("protocolsDep", $plan)}
    <div class="controls">
        {CHtml::activeLookup("protocolsDep", $plan, "class.CSearchCatalogProtocolsDep", true, array())}
        {CHtml::error("protocolsDep", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("protocolsNMS", $plan)}
    <div class="controls">
        {CHtml::activeLookup("protocolsNMS", $plan, "class.CSearchCatalogProtocolsNMS", true, array())}
        {CHtml::error("protocolsNMS", $plan)}
    </div>
</div>