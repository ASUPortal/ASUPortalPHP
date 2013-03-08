<?php
class TypesController extends AppController {

	var		$name = 'Types';
	//var		$scaffold;
	var		$paginate = array(
				'limit' => 15,
				'order' => 'type.id asc'
			);
			
	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р° СЃРѕ СЃРїРёСЃРєРѕРј
	function admin_index() {
		// С‚СѓС‚ РЅР°РґРѕ РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІР° РґРѕСЃС‚СѓРїР° С‚Р°Рє-С‚Рѕ...

		$this->set('types', $this->paginate('Type'));
	}
	
	// Р°РґРјРёРЅРєР° - СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ 
	function admin_edit($id = null) {
		// РѕРїСЏС‚СЊ, РїСЂР°РІР°

		if (empty($this->data)) {
			// СЌС‚Рѕ РїСЂРѕСЃРјРѕС‚СЂ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ
			$this->Type->id	= $id;
			$type		= $this->Type->read();		
			$this->data		= $type;
		} else {
			// СЃРѕС…СЂР°РЅРµРЅРёРµ РґР°РЅРЅС‹С…
			$this->Type->save($this->data);

			$this->redirect('/admin/types/');
		}
	}
	
	// Р°РґРјРёРЅРєР° - СѓРґР°Р»РµРЅРёРµ
	function admin_del($id) {
		// РїСЂР°РІР°

		$this->Type->id		= $id;
		$this->Type->del();

		$this->redirect('/admin/types/');
	}
}
?>