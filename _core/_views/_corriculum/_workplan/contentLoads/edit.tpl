{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование нагрузки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentLoads/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#themes" data-toggle="tab">Темы</a></li>
        <li><a href="#technologies" data-toggle="tab">Образовательные технологии</a></li>
        <li><a href="#selfEducation" data-toggle="tab">Самостоятельное изучение</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="themes">
            {include file="_corriculum/_workplan/contentLoads/subform.themes.tpl"}
        </div>
        <div class="tab-pane" id="technologies">
            {include file="_corriculum/_workplan/contentLoads/subform.technologies.tpl"}
        </div>
        <div class="tab-pane" id="selfEducation">
            {include file="_corriculum/_workplan/contentLoads/subform.selfeducation.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}