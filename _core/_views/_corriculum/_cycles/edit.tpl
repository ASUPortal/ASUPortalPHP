{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование цикла</h2>
{include file="_corriculum/_cycles/form.tpl"}

<h2>Дисциплины</h2>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#labor">Распределение нагрузки</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="labor">
            {include file="_corriculum/_cycles/subform.labors.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_corriculum/_cycles/edit.right.tpl"}
{/block}