{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование дисциплины</h2>
{CHtml::helpForCurrentPage()}

{include file="_corriculum/_disciplines/subform.center.tpl"}
<br>
{include file="_corriculum/_disciplines/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#labor" data-toggle="tab">Распределение нагрузки по видам занятий</a></li>
        <li><a href="#competentions" data-toggle="tab">Компетенции</a></li>
        <li><a href="#programs" data-toggle="tab">Рабочие программы</a></li>
        <li><a href="#books" data-toggle="tab">Учебники</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="labor">
            {CHtml::activeComponent("disciplineSections.php?discipline_id={$discipline->getId()}", $discipline)}
        </div>
        <div class="tab-pane" id="competentions">
			<ul class="nav nav-pills">
				<li>
				    <a href="#printDialog" data-toggle="modal">
				    	<center>
				        	<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
				        	Взаимосвязь дисциплины с другими дисциплинами
				    	</center>
				    </a>
				</li>
			</ul>
			<div id="printDialog" class="modal hide fade">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h3>Печать по шаблону</h3>
			    </div>
			    <div class="modal-body">
			        {CHtml::printOnTemplate("formset_corriculum_disciplines")}
			    </div>
			</div>
            {CHtml::activeComponent("competentions.php?discipline_id={$discipline->getId()}", $discipline)}
        </div>
        <div class="tab-pane" id="programs">
			<ul class="nav nav-pills">
				<li>
					<a href="workplans.php?action=add&id={$discipline->getId()}">
						<center>
					        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
					        Добавить рабочую программу
						</center>
					</a>
				</li>
			</ul>
            {include file="_corriculum/_disciplines/subform.programs.tpl"}
        </div>
        <div class="tab-pane" id="books">
			<ul class="nav nav-pills">
				<li>
				    <a href="disciplines.php?action=addFromUrl&discipline_id={CRequest::getInt("id")}">
				    	<center>
				        	<img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save.png"><br>
				        	Загрузить литературу из библиотеки
				    	</center>
				    </a>
				</li>
				<li>
				    <a href="{$link}{$disciplineTaxonomy->library_code}" target="_blank">
				    	<center>
				        	<img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
				        	Страница дисциплины на сайте библиотеки
				    	</center>
				    </a>
				</li>
				<li>
				    <a href="disciplines.php?action=addStatement&discipline_id={CRequest::getInt("id")}" target="_blank">
				    	<center>
				        	<img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
				        	Сформировать заявку на учебную литературу
				    	</center>
				    </a>
				</li>
			</ul>
            {CHtml::activeComponent("books.php?discipline_id={$discipline->getId()}", $discipline)}
        </div>
    </div>
{/block}

{block name="asu_right"}
	{include file="_corriculum/_disciplines/edit.right.tpl"}
{/block}