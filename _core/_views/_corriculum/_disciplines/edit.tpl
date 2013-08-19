{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование дисциплины</h2>
{include file="_corriculum/_disciplines/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#labor" data-toggle="tab">Распределение нагрузки по видам занятий</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="labor">
            {include file="_corriculum/_disciplines/subform.labor.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_corriculum/_disciplines/edit.right.tpl"}
{/block}