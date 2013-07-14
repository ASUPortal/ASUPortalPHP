{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование поля модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/field/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active" data-toggle="tab"><a href="#translations">Перевод</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="translations">
            {include file="_core/field/subform.translations.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_core/field/edit.right.tpl"}
{/block}