{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование модуля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentModules/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#sections" data-toggle="tab">Разделы модуля</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="sections">
            {include file="_corriculum/_workplan/contentModules/subform.sections.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentModules/common.right.tpl"}
{/block}