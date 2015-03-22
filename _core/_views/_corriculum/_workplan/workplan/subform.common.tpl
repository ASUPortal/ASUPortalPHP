{NgHtml::activeTextBoxRow($plan, 'workplan', 'title')}

{NgHtml::activeSelectRow($plan, 'workplan', 'department_id', 'departmentNames')}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'approver_post')}

{NgHtml::activeTextRow($plan, 'workplan', 'approver_name')}

{NgHtml::activeSelectRow($plan, 'workplan', 'direction_id', 'corriculum_speciality_directions')}

{NgHtml::activeSelectRow($plan, 'workplan', 'profiles', 'corriculum_speciality_directions', true)}

{NgHtml::activeSelectRow($plan, 'workplan', 'qualification_id', 'corriculum_skill')}

{NgHtml::activeSelectRow($plan, 'workplan', 'education_form_id', 'study_forms')}

{NgHtml::activeTextRow($plan, 'workplan', 'year')}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'intended_for')}

{NgHtml::activeSelectRow($plan, 'workplan', 'author_id', 'staff')}

<h3>1. Цели и задачи освоения дисциплины</h3>

{NgHtml::activeTaggingRow($plan, 'workplan', 'goals', 'workplan_goals', true)}

{NgHtml::activeTaggingRow($plan, 'workplan', 'tasks', 'workplan_tasks', true)}

<h3>2. Место дисциплины в структуре ООП ВПО</h3>

{NgHtml::activeTextBoxRow($plan, 'workplan', 'position')}

{NgHtml::activeSelectRow($plan, 'workplan', 'disciplinesBefore', 'subjects', true)}

{NgHtml::activeSelectRow($plan, 'workplan', 'disciplinesAfter', 'subjects', true)}