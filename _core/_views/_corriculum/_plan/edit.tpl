{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование учебного плана</h2>
	{CHtml::helpForCurrentPage()}
    {include file="_corriculum/_plan/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#practice" data-toggle="tab">Практика</a></li>
        <li><a href="#attestation" data-toggle="tab">Итоговая аттестация</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="practice">
            {include file="_corriculum/_plan/subform.practice.tpl"}
        </div>
        <div class="tab-pane" id="attestation">
            {include file="_corriculum/_plan/subform.attestation.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_corriculum/_plan/edit.right.tpl"}
{/block}