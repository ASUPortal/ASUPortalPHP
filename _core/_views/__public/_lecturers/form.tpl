<form action="index.php" method="post" class="form-horizontal" enctype="multipart/form-data">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-news">Учебные пособия и объявления</a></li>
        <li><a data-toggle="tab" href="#tab-education">Дипломники и аспиранты</a></li>
        <li><a data-toggle="tab" href="#tab-questions">Вопросы и ответы</a></li>
        <li><a data-toggle="tab" href="#tab-groups">Кураторство учебных групп</a></li>
        <li><a data-toggle="tab" href="#tab-info">Информация о сотруднике</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-news">
            {include file="__public/_lecturers/subform.news.tpl"}
        </div>
        <div class="tab-pane" id="tab-education">
            {include file="__public/_lecturers/subform.education.tpl"}
        </div>
        <div class="tab-pane" id="tab-questions">
            {include file="__public/_lecturers/subform.questions.tpl"}
        </div>
        <div class="tab-pane" id="tab-groups">
            {include file="__public/_lecturers/subform.groups.tpl"}
        </div>
        <div class="tab-pane" id="tab-info">
            {include file="__public/_lecturers/subform.info.tpl"}
        </div>
    </div>
</form>
