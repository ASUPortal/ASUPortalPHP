{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/model/form.tpl"}

    <ul class="nav nav-tabs">
        <li><a href="#fields" class="active" data-toggle="tab">Поля модели</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="fields">
            {include file="_core/model/subform.fields.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_core/model/edit.right.tpl"}
{/block}