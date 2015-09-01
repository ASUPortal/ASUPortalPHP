<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#tab-news-subjects">Список пособий на портале ({$lect->getManuals()->getCount()})</a></li>
    <li><a data-toggle="tab" href="#tab-news-current">Объявления текущего учебного года ({$lect->getNewsCurrentYear()->getCount()})</a></li>
    <li><a data-toggle="tab" href="#tab-news-old">Объявления прошлых учебных лет ({$lect->getNewsOld()->getCount()})</a></li>
    <li><a data-toggle="tab" href="#tab-news-page">Cтраницы на портале ({$lect->getPages()->getCount()})</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab-news-subjects">
        {include file="__public/_lecturers/subform.news.subjects.tpl"}
    </div>
    <div class="tab-pane" id="tab-news-page">
        {include file="__public/_lecturers/subform.news.page.tpl"}
    </div>
    <div class="tab-pane" id="tab-news-current">
        {include file="__public/_lecturers/subform.news.current.tpl"}
    </div>
        <div class="tab-pane" id="tab-news-old">
        {include file="__public/_lecturers/subform.news.Old.tpl"}
    </div>
</div>