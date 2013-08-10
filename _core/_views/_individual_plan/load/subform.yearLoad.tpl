<div>
    <h3>План на {if !is_null($load->year)}{$load->year->getValue()}{/if} год по видам работ</h3>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#teaching{$load->year->getId()}" data-toggle="tab">Учебная</a></li>
        <li><a href="#orgload{$load->year->getId()}" data-toggle="tab">Учебно и орг.-методическая</a></li>
        <li><a href="#science{$load->year->getId()}" data-toggle="tab">Методическая</a></li>
        <li><a href="#education{$load->year->getId()}" data-toggle="tab">Воспитательная</a></li>
        <li><a href="#works{$load->year->getId()}" data-toggle="tab">Труды</a></li>
        <li><a href="#changes{$load->year->getId()}" data-toggle="tab">Изменения плана</a></li>
        <li><a href="#conclusion{$load->year->getId()}" data-toggle="tab">Заключение</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="teaching{$load->year->getId()}">

        </div>
        <div class="tab-pane" id="orgload{$load->year->getId()}">
            Учебно и орг. методическая
        </div>
        <div class="tab-pane" id="science{$load->year->getId()}">
            {include file="_individual_plan/load/subform.science.tpl"}
        </div>
        <div class="tab-pane" id="education{$load->year->getId()}">
            {include file="_individual_plan/load/subform.education.tpl"}
        </div>
        <div class="tab-pane" id="works{$load->year->getId()}">
            {include file="_individual_plan/load/subform.publication.tpl"}
        </div>
        <div class="tab-pane" id="changes{$load->year->getId()}">
            {include file="_individual_plan/load/subform.change.tpl"}
        </div>
        <div class="tab-pane" id="conclusion{$load->year->getId()}">
            {include file="_individual_plan/load/subform.conclusion.tpl"}
        </div>
    </div>
</div>