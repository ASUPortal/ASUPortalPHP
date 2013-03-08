<?php
class Corriculum extends AppModel {

	var 	$name		= "Corriculum";

	var		$belongsTo	= array (
				'Cycle',
				'Discipline',
				'Chair',
				'Semester',
				'Specialite',
				'Form'
			);
}
?>