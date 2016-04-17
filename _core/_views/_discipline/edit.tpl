{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование дисциплины</h2>
	{CHtml::helpForCurrentPage()}
	<a href="{$link}{$discipline->library_code}" target="_blank">Страница дисциплины в библиотеке</a><br><br>
	{include file="_discipline/form.tpl"}
	
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#books">Учебники</a></li>
	</ul>
	<div class="tab-content">
		<div id="books" class="tab-pane active">
			{include file="_discipline/subform.books.tpl"}
		</div>
	</div>
{/block}

{block name="asu_right"}
    {include file="_discipline/edit.right.tpl"}
{/block}