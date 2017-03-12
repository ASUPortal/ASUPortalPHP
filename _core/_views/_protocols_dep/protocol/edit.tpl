{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование протокола кафедры</h2>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общие сведения</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_protocols_dep/protocol/form.tpl"}
            
            <h4>Пункты повестки</h4>
            {CHtml::activeComponent("point.php?protocol_id={$protocol->getId()}", $protocol)}
            
            <h4>Посещаемость</h4>
            {CHtml::activeComponent("visit.php?protocol_id={$protocol->getId()}", $protocol)}
        </div>
    </div>
{/block}

{block name="asu_right"}
	{include file="_protocols_dep/protocol/common.right.tpl"}
{/block}