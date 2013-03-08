{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование цикла</h2>
{include file="_corriculum/_cycles/form.tpl"}

<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<h2>Дисциплины</h2>
    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#labor">Распределение нагрузки</a></li>
        </ul>
        <div id="labor">
            {include file="_corriculum/_cycles/subform.labors.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_corriculum/_cycles/edit.right.tpl"}
{/block}