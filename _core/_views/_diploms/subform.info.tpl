<ul>
	{if (!is_null($diplom->student))}
	<li><a href="{$web_root}_modules/_students/index.php?action=edit&id={$diplom->student_id}" target="_blank">Страница редактирования студента</a></li>
		{if (!is_null($diplom->student->group))}
			<li><a href="{$web_root}_modules/_student_groups/index.php?action=edit&id={$diplom->student->group->getId()}" target="_blank">Учебная группа студента</a></li>
			{if (!is_null($diplom->student->group->corriculum))}
				<li><a href="{$web_root}_modules/_corriculum/index.php?action=edit&id={$diplom->student->group->corriculum->getId()}" target="_blank">Учебный план</a></li>
			{else}
				<div class="alert">
					В группе студента не указан учебный план!
				</div>
			{/if}
		{else}
			<div class="alert">
				У студента не указана группа!
			</div>
		{/if}
	{/if}
</ul>