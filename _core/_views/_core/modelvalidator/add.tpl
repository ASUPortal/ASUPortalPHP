{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление валидатора модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/modelvalidator/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/modelvalidator/add.right.tpl"}
{/block}