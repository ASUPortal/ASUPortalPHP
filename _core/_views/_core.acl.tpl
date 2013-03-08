<script>
    jQuery(document).ready(function(){
        jQuery("#access_control").accordion({
            collapsible: true,
            active: false
        });
        jQuery("#readers").namesSelector();
        jQuery("#authors").namesSelector();
    });
</script>
<div id="access_control">
    <h3>Контроль доступа</h3>
    <div>
    <p>
        {CHtml::activeLabel("readers", $table)}
        {CHtml::activeNamesSelect("readers", $table)}
        {CHtml::error("readers", $table)}
    </p>

    <p>
        {CHtml::activeLabel("authors", $table)}
        {CHtml::activeNamesSelect("authors", $table)}
        {CHtml::error("authors", $table)}
    </p>
    </div>
</div>