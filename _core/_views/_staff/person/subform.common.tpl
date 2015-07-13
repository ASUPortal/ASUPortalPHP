<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#tab-common-common">Общие сведения</a></li>
    <li><a data-toggle="tab" href="#tab-common-contacts">Контактная информация</a></li>
    <li><a data-toggle="tab" href="#tab-common-children">Дети</a></li>
	<li><a data-toggle="tab" href="#tab-common-biography">Биография</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-common-common">
        {include file="_staff/person/subform.common.common.tpl"}
    </div>
    <div class="tab-pane" id="tab-common-contacts">
        {include file="_staff/person/subform.common.contacts.tpl"}
    </div>
    <div class="tab-pane" id="tab-common-children">
        {include file="_staff/person/subform.common.children.tpl"}
    </div>
        <div class="tab-pane" id="tab-common-biography">
        {include file="_staff/person/subform.common.biography.tpl"}
    </div>
</div>