{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление поля модели</h2>

    {CHtml::helpForCurrentPage()}

    <p>Модель: {if !is_null($field->model)}{$field->model->title}{/if}</p>

    {include file="_core/field/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/field/add.right.tpl"}
{/block}