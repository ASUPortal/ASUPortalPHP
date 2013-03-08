{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование учебного плана</h2>
    <script>
        jQuery(document).ready(function(){
            jQuery("#tabs").tabs();
        });
    </script>

    {include file="_corriculum/_plan/form.tpl"}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#practice">Практика и итоговая аттестация</a></li>
        </ul>
        <div id="practice">
            {include file="_corriculum/_plan/subform.practice.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_corriculum/_plan/edit.right.tpl"}
{/block}