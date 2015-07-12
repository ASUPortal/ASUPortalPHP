<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#tab-education-diploms">Дипломники текущего учебного года ({$lect->getGraduatesCurrentYear()->getCount()})</a></li>
    <li><a data-toggle="tab" href="#tab-education-diplomsOld">Дипломники предыдущих учебных лет ({$lect->getGraduatesOld()->getCount()})</a></li>
    <li><a data-toggle="tab" href="#tab-education-aspir">Подготовка аспирантов, текущие ({$lect->getAspirantsCurrent()->getCount()})</a></li>
	<li><a data-toggle="tab" href="#tab-education-aspirOld">Подготовка аспирантов, архив ({$lect->getAspirantsOld()->getCount()})</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-education-diploms">
        {include file="__public/_lecturers/subform.education.graduates.tpl"}
    </div>
    <div class="tab-pane" id="tab-education-diplomsOld">
        {include file="__public/_lecturers/subform.education.graduatesOld.tpl"}
    </div>
    <div class="tab-pane" id="tab-education-aspir">
        {include file="__public/_lecturers/subform.education.aspir.tpl"}
    </div>
    <div class="tab-pane" id="tab-education-aspirOld">
        {include file="__public/_lecturers/subform.education.aspirOld.tpl"}
    </div>
</div>