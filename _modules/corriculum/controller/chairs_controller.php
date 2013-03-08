<?php
class ChairsController extends AppController {

	var		$name = 'Chairs';
	// var		$scaffold;

	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р° СЃРѕ СЃРїРёСЃРєРѕРј
	function admin_index() {
		// С‚СѓС‚ РЅР°РґРѕ РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІР° РґРѕСЃС‚СѓРїР° С‚Р°Рє-С‚Рѕ...


		$this->set ('chairs', $this->Chair->find('all'));
	}

	// Р°РґРјРёРЅРєР° - СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ 
	function admin_edit($id = null) {
		// РѕРїСЏС‚СЊ, РїСЂР°РІР°

		if (empty($this->data)) {
			// СЌС‚Рѕ РїСЂРѕСЃРјРѕС‚СЂ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ
			$this->Chair->id	= $id;
			$chair			= $this->Chair->read();

			$this->data		= $chair;
		} else {
			// СЃРѕС…СЂР°РЅРµРЅРёРµ РґР°РЅРЅС‹С…
			$this->Chair->save($this->data);

			$this->redirect('/admin/chairs/');
		}
	}

	// Р°РґРјРёРЅРєР° - СѓРґР°Р»РµРЅРёРµ
	function admin_del($id) {
		// РїСЂР°РІР°

		$this->Chair->id		= $id;
		$this->Chair->del();

		$this->redirect('/admin/chairs/');
	}
}
?>