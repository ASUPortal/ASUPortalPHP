<form action="index.php" method="post" class="form-horizontal" enctype="multipart/form-data">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-news">Учебные пособия и объявления</a></li>
        <li><a data-toggle="tab" href="#tab-questions">Вопросы и ответы</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-news">
            {include file="__public/_lecturers/subform.news.tpl"}
        </div>
        <div class="tab-pane" id="tab-questions">
            {include file="__public/_lecturers/subform.questions.tpl"}
        </div>
    </div>
</form>
