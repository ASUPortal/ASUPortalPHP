{NgHtml::activeTextBoxRow($plan, 'workplan', 'title')}

{NgHtml::activeSelectRow($plan, 'workplan', 'department_id', ["glossary"=>"departmentNames"])}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'approver_post')}

{NgHtml::activeTextRow($plan, 'workplan', 'approver_name')}

{NgHtml::activeSelectRow($plan, 'workplan', 'direction_id', ["glossary"=>"corriculum_speciality_directions"])}

{NgHtml::activeSelectRow($plan, 'workplan', 'profiles', ["glossary"=>"corriculum_speciality_directions", "multiple"=>true])}

{NgHtml::activeSelectRow($plan, 'workplan', 'qualification_id', ["glossary"=>"corriculum_skill"])}

{NgHtml::activeSelectRow($plan, 'workplan', 'education_form_id', ["glossary"=>"study_forms"])}

{NgHtml::activeTextRow($plan, 'workplan', 'year')}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'intended_for')}

{NgHtml::activeSelectRow($plan, 'workplan', 'author_id', ["glossary"=>"staff"])}

<h3>1. Цели и задачи освоения дисциплины</h3>

{NgHtml::activeTaggingRow($plan, 'workplan', 'goals', ["glossary"=>"workplan_goals", "multiple"=>true])}

{NgHtml::activeTaggingRow($plan, 'workplan', 'tasks', ["glossary"=>"workplan_tasks", "multiple"=>true])}

<h3>2. Место дисциплины в структуре ООП ВПО</h3>

{NgHtml::activeTextBoxRow($plan, 'workplan', 'position')}

{NgHtml::activeSelectRow($plan, 'workplan', 'disciplinesBefore', ["glossary"=>"subjects", "multiple"=>true])}

{NgHtml::activeSelectRow($plan, 'workplan', 'disciplinesAfter', ["glossary"=>"subjects", "multiple"=>true])}