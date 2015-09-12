{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование цели рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/goal/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tasks" data-toggle="tab">Задачи</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="active tab-pane" id="tasks">
            {include file="_corriculum/_workplan/goal/subform.tasks.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/goal/common.right.tpl"}
{/block}