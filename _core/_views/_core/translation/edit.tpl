{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование перевода</h2>

    {CHtml::helpForCurrentPage()}

    <p>Модель: {if !is_null($translation->field)}
            {if !is_null($translation->field->model)}
                {$translation->field->model->class_name}
            {/if}
        {/if}</p>
    <p>Поле: {if !is_null($translation->field)}
            {$translation->field->field_name}
        {/if}</p>

    {include file="_core/translation/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/translation/add.right.tpl"}
{/block}