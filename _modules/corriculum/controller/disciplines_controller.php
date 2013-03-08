<?php
class DisciplinesController extends AppController {

	var		$name = 'Disciplines';
	//var		$scaffold;
	var		$paginate = array(
				'limit' => 15,
				'order' => 'discipline.id asc'
			);

	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р° СЃРѕ СЃРїРёСЃРєРѕРј
	function admin_index() {
		// С‚СѓС‚ РЅР°РґРѕ РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІР° РґРѕСЃС‚СѓРїР° С‚Р°Рє-С‚Рѕ...

		$this->set('disciplines', $this->paginate('Discipline'));
	}

	// Р°РґРјРёРЅРєР° - СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ 
	function admin_edit($id = null) {
		// РѕРїСЏС‚СЊ, РїСЂР°РІР°

		if (empty($this->data)) {
			// СЌС‚Рѕ РїСЂРѕСЃРјРѕС‚СЂ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ
			$this->Discipline->id	= $id;
			$discipline		= $this->Discipline->read();

			$types			= $this->Discipline->Type->find('all', array('fields' => array('id', 'title')));
			$types			= $this->compact($types);
			$this->set('types', $types);		

			$this->data		= $discipline;
		} else {
			// СЃРѕС…СЂР°РЅРµРЅРёРµ РґР°РЅРЅС‹С…
			$this->Discipline->save($this->data);

			$this->redirect('/admin/disciplines/');
		}
	}

	// Р°РґРјРёРЅРєР° - СѓРґР°Р»РµРЅРёРµ
	function admin_del($id) {
		// РїСЂР°РІР°

		$this->Discipline->id		= $id;
		$this->Discipline->del();

		$this->redirect('/admin/disciplines/');
	}

	function compact($vars) {
		$i	= 0;

		foreach ($vars as $var) {
			$out[$var['Type']['id']] = $var['Type']['title'];
			$i++;
		}

		return $out;
	}
}
?>