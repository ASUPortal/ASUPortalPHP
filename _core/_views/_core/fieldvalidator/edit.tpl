{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование валидатора</h2>

    {CHtml::helpForCurrentPage()}

    <p>Модель: {if !is_null($validator->field)}
            {if !is_null($validator->field->model)}
                {$validator->field->model->class_name}
            {/if}
        {/if}</p>
    <p>Поле: {if !is_null($validator->field)}
            {$validator->field->field_name}
        {/if}</p>

    {include file="_core/fieldvalidator/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/fieldvalidator/add.right.tpl"}
{/block}