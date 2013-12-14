{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/model/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#fields" data-toggle="tab">Поля модели</a></li>
        <li><a href="#tasks" data-toggle="tab">Задачи модели</a></li>
        <li><a href="#validators" data-toggle="tab">Валидаторы модели</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="fields">
            {include file="_core/model/subform.fields.tpl"}
        </div>
        <div class="tab-pane" id="tasks">
            {include file="_core/model/subform.tasks.tpl"}
        </div>
        <div class="tab-pane" id="validators">
            {include file="_core/model/subform.validators.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_core/model/edit.right.tpl"}
{/block}