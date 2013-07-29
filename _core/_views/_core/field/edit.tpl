{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование поля модели</h2>

    {CHtml::helpForCurrentPage()}

    <p>Модель: {if !is_null($field->model)}{$field->model->title}{/if}</p>

    {include file="_core/field/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#translations" data-toggle="tab">Перевод</a></li>
        <li><a href="#validators" data-toggle="tab">Валидация</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="translations">
            {include file="_core/field/subform.translations.tpl"}
        </div>
        <div class="tab-pane" id="validators">
            {include file="_core/field/subform.validators.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_core/field/edit.right.tpl"}
{/block}