<?php
class CyclesController extends AppController {

	var 	$name = 'Cycles';
	//var		$scaffold;

	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р° СЃРѕ СЃРїРёСЃРєРѕРј
	function admin_index() {
		// С‚СѓС‚ РЅР°РґРѕ РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІР° РґРѕСЃС‚СѓРїР° С‚Р°Рє-С‚Рѕ...


		$this->set ('cycles', $this->Cycle->find('all'));
	}

	// Р°РґРјРёРЅРєР° - СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ 
	function admin_edit($id = null) {
		// РѕРїСЏС‚СЊ, РїСЂР°РІР°

		if (empty($this->data)) {
			// СЌС‚Рѕ РїСЂРѕСЃРјРѕС‚СЂ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ
			$this->Chair->id	= $id;
			$cycle			= $this->Cycle->read();

			$this->data		= $cycle;
		} else {
			// СЃРѕС…СЂР°РЅРµРЅРёРµ РґР°РЅРЅС‹С…
			$this->Cycle->save($this->data);

			$this->redirect('/admin/cycles/');
		}
	}

	// Р°РґРјРёРЅРєР° - СѓРґР°Р»РµРЅРёРµ
	function admin_del($id) {
		// РїСЂР°РІР°

		$this->Cycle->id		= $id;
		$this->Cycle->del();

		$this->redirect('/admin/cycles/');
	}
}
?>