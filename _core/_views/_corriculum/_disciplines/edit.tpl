{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование дисциплины</h2>
{include file="_corriculum/_disciplines/form.tpl"}

<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

    <div id="tabs">
        <ul style="height: 30px;">
            <li><a href="#labor">Распределение нагрузки по видам занятий</a></li>
        </ul>
        <div id="labor">
            {include file="_corriculum/_disciplines/subform.labor.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_corriculum/_disciplines/edit.right.tpl"}
{/block}