<div id="listUnrealizedCompetentionsCorriculum" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список нереализованных компетенций учебного плана</h3>
	</div>
	<div class="modal-body">
		{foreach $unrealizedCompetentionsCorriculum as $competention}
        	<li>{$competention}</li>
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<div id="listUnrealizedCompetentionsWorkPlans" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список нереализованных компетенций рабочих программ</h3>
	</div>
	<div class="modal-body">
		{foreach $unrealizedCompetentionsWorkPlans as $planId=>$competentions}
        	{if (is_string($competentions))}
        		<b><a href="../../_modules/_corriculum/workplans.php?action=edit&id={$planId}" target="_blank">{$competentions}</a></b><br><br>
        	{/if}
        	{if (is_array($competentions))}
        		{foreach $competentions as $competention}
        			<li>{$competention}</li>
        		{/foreach}
        		<br>
        	{/if}
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<div id="listDisciplinesWithOutCompetentionsCorriculum" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список дисциплин без компетенций УП</h3>
	</div>
	<div class="modal-body">
		{foreach $disciplinesWithOutCompetentionsCorriculum as $disciplines}
        	{if (is_string($disciplines))}
        		<b>{$disciplines}</b><br><br>
        	{/if}
        	{if (is_array($disciplines))}
        		{foreach $disciplines as $discipline}
        			<li>{$discipline}</li>
        		{/foreach}
        		<br>
        	{/if}
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<div id="listDisciplinesWithOutCompetentionsWorkPlans" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список дисциплин без компетенций РП</h3>
	</div>
	<div class="modal-body">
		{foreach $disciplinesWithOutCompetentionsWorkPlans as $discipline}
        	<li>{$discipline}</li>
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<div id="listCorriculumCompetentionsWithoutQES" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список компетенций без ЗУН УП</h3>
	</div>
	<div class="modal-body">
		{foreach $corriculumCompetentionsWithoutQES as $competentions}
        	{if (is_string($competentions))}
        		<b>{$competentions}</b><br><br>
        	{/if}
        	{if (is_array($competentions))}
        		{foreach $competentions as $competention}
        			<li>{$competention}</li>
        		{/foreach}
        		<br>
        	{/if}
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<div id="listWorkPlansCompetentionsWithoutQES" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список компетенций без ЗУН РП УП</h3>
	</div>
	<div class="modal-body">
		{foreach $workPlansCompetentionsWithoutQES as $planId=>$competentions}
        	{if (is_string($competentions))}
        		<b><a href="../../_modules/_corriculum/workplans.php?action=edit&id={$planId}" target="_blank">{$competentions}</a></b><br><br>
        	{/if}
        	{if (is_array($competentions))}
        		{foreach $competentions as $competention}
        			<li>{$competention}</li>
        		{/foreach}
        		<br>
        	{/if}
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<div id="listWorkPlansWithErrorLoadTerms" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Список рабочих программ с ошибками семестров в нагрузке</h3>
	</div>
	<div class="modal-body">
		{foreach $workPlansWithErrorLoadTerms as $planId=>$planName}
        	<li><a href="../../_modules/_corriculum/workplans.php?action=edit&id={$planId}" target="_blank">{$planName}</a></li>
        {/foreach}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
	</div>
</div>

<ul class="nav nav-pills nav-justified">
	<li>
        <a href="index.php?action=index"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
	        Назад
	        <br>&nbsp;
	        <br>&nbsp;
	    </center></a>
	</li>
	<li>
        <a href="index.php?action=edit&id={$corriculum->getId()}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/apps/accessories-text-editor.png"><br>
	        Редактировать<br>основную информацию<br>об учебном плане
	    </center></a>
	</li>
	<li>
        <a href="cycles.php?action=add&id={$corriculum->id}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
	        Добавить цикл
	        <br>&nbsp;
	        <br>&nbsp;
	    </center></a>
	</li>
	<li>
        <a href="index.php?action=copy&id={$corriculum->id}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
	        Копировать текущий<br>учебный план
	        <br>&nbsp;
	    </center></a>
	</li>
	<li>
        <a href="workplans.php?action=index&corriculumId={$corriculum->id}"><center>
	        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-select-all.png"><br>
	        Список рабочих программ<br>с авторами, статусами<br>и комментариями
	    </center></a>
	</li>
	<li>
        {include file="_corriculum/_plan/_printGroupOnTemplateCorriculumDisciplines.tpl"}
	</li>
	<li>
        {include file="_corriculum/_plan/_printGroupOnTemplateWorkplans.tpl"}
	</li>
	<li>
        <a href="{$web_root}_modules/_print/?action=ShowForms&template={$template}" asu-action="flow">
    		{if !is_null($formset)}
    			{$var = $formset->computeTemplateVariables()}
        		{foreach $var as $key=>$value}
        			<div asu-type="flow-property" name="{$key}" value="{$value}"></div>
        		{/foreach}
        	{/if}
	        <center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
		        Форма 1<br>Форма 2<br>&nbsp;
		    </center>
	    </a>
	</li>
</ul>