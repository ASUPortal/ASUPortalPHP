<?php
class CorriculumsController extends AppController {

	var 	$name 			= 'Corriculums';
	// var		$scaffold;
	var		$paginate 		= array (
				'limit' => '15',
				'fields' => array(
					'(hours_auditory + hours_independent) as hours_total',
					'*',
					'Cycle.*',
					'Discipline.*',
					'Chair.*'
				),
				'recursive' => '2'
			);

	// РїСЂРѕСЃРјРѕС‚СЂ СѓС‡РµР±РЅРѕР№ РЅР°РіСЂСѓР·РєРё РЅР° РѕРїСЂРµРґРµР»РµРЅРЅСѓСЋ СЃРїРµС†РёР°Р»СЊРЅРѕСЃС‚СЊ
	function view($id, $form = null, $kadri_id = null, $year_id = null) {
		$this->set('specialites', $this->Corriculum->Specialite->find('all', array('conditions' => array('pub' => '1'))));
		
		/*
		$this->set('corriculums', $this->Corriculum->find('all', array('conditions' => array('specialite_id' => $id, 'form_id' => $form), 
			'fields' => array(
				'(hours_auditory + hours_independent) as hours_total',
				'*',
				'Cycle.*',
				'Discipline.*',
				'Chair.*'
			), 'recursive' => '2')));		
		*/
		
		$this->set('corriculums', $this->paginate('Corriculum', array('specialite_id' => $id, 'form_id' => $form, 'kadri_id' => $kadri_id, 'year_id' => $year_id)));
			
		$this->set('forms', $this->Corriculum->Form->find('all', array('conditions' => array('pub' => '1'))));
		$this->set('speciality', $id);
	}
	
	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р°
	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р° СЃРѕ СЃРїРёСЃРєРѕРј
	function admin_index() {
		// С‚СѓС‚ РЅР°РґРѕ РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІР° РґРѕСЃС‚СѓРїР° С‚Р°Рє-С‚Рѕ...

		$this->set('corriculums', $this->paginate('Corriculum'));
	}
	
	// Р°РґРјРёРЅРєР° - СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ 
	function admin_edit($id = null) {
		// РѕРїСЏС‚СЊ, РїСЂР°РІР°

		if (empty($this->data)) {
			// СЌС‚Рѕ РїСЂРѕСЃРјРѕС‚СЂ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ
			$this->Corriculum->id	= $id;
			$corriculum		= $this->Corriculum->read();	
			$this->data		= $corriculum;
			
			$prepods		= $this->Corriculum->query('select id, fio as title from kadri');
			$prepods		= $this->compact($prepods, 'kadri');
			$this->set('kadri', $prepods);
			
			$years			= $this->Corriculum->query('select id, name as title from time_intervals order by name desc');
			$years			= $this->compact($years, 'time_intervals');
			$this->set('years', $years);
			
			$forms			= $this->Corriculum->Form->findAll();
			$forms			= $this->compact($forms, 'Form');
			$this->set('forms', $forms);
			
			$cycles			= $this->Corriculum->Cycle->findAll();
			$cycles			= $this->compact($cycles, 'Cycle');
			$this->set('cycles', $cycles);
			
			$disciplines	= $this->Corriculum->Discipline->findAll();
			$disciplines	= $this->compact($disciplines, 'Discipline');
			$this->set('disciplines', $disciplines);
			
			$specialites	= $this->Corriculum->Specialite->findAll();
			$specialites	= $this->compact($specialites, 'Specialite');
			$this->set('specialites', $specialites);
			
			$chairs			= $this->Corriculum->Chair->findAll();
			$chairs			= $this->compact($chairs, 'Chair');
			$this->set('chairs', $chairs);
			
			$semesters		= $this->Corriculum->Semester->findAll();
			$semesters		= $this->compact($semesters, 'Semester');
			$this->set('semesters', $semesters);
		} else {
			// СЃРѕС…СЂР°РЅРµРЅРёРµ РґР°РЅРЅС‹С…
			$this->Corriculum->save($this->data);

			$this->redirect('/admin/corriculums/');
		}
	}
	
	// Р°РґРјРёРЅРєР° - СѓРґР°Р»РµРЅРёРµ
	function admin_del($id) {
		// РїСЂР°РІР°

		$this->Corriculum->id		= $id;
		$this->Corriculum->del();

		$this->redirect('/admin/corriculums/');
	}
	
	function compact($vars, $name) {
		$i	= 0;

		foreach ($vars as $var) {
			$out[$var[$name]['id']] = $var[$name]['title'];
			$i++;
		}

		return $out;
	}
}
?>