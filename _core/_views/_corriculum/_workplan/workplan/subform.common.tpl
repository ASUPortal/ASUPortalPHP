{NgHtml::activeTextBoxRow($plan, 'workplan', 'title')}

{NgHtml::activeSelectRow($plan, 'workplan', 'department_id', 'departmentNames')}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'approver_post')}

{NgHtml::activeTextRow($plan, 'workplan', 'approver_name')}

{NgHtml::activeSelectRow($plan, 'workplan', 'direction_id', 'corriculum_speciality_directions')}

{NgHtml::activeSelectRow($plan, 'workplan', 'profiles', 'corriculum_speciality_directions', true)}

{NgHtml::activeSelectRow($plan, 'workplan', 'qualification_id', 'corriculum_skill')}

{NgHtml::activeSelectRow($plan, 'workplan', 'education_form_id', 'study_forms')}

{NgHtml::activeTextRow($plan, 'workplan', 'year')}

{NgHtml::activeTaggingRow($plan, 'workplan', 'goals', 'workplan_goals', true)}

{NgHtml::activeTaggingRow($plan, 'workplan', 'tasks', 'workplan_tasks', true)}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'intended_for')}

{NgHtml::activeSelectRow($plan, 'workplan', 'author_id', 'staff')}