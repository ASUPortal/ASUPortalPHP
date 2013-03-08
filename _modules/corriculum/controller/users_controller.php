<?php
class UsersController extends AppController {

	var 	$name = 'Users';
	// var		$scaffold;
	var		$paginate = array(
				'limit' => 15,
				'order' => 'user.id asc'
			);
	
	// Р»РѕРіРёРЅ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ
	function login() {
		if (!empty($this->data)) {
			$user	= $this->User->find('first', array('conditions' => array('login' => $this->data['User']['login'])));
			
			if ($user['User']['password'] == $this->data['User']['password']) {
				$this->redirect('/manage');

				//$this->session->write('User', $user);
			}
		}

		
	}
	
	// РѕС‚РѕР±СЂР°Р¶Р°РµРј СЃРїРёСЃРѕРє С‚РѕРіРѕ, С‡РµРіРѕ РѕРЅ РјРѕР¶РµС‚ СЂРµРґР°РєС‚РёСЂРѕРІР°С‚СЊ
	function manage() {
		// С‚СѓРїРѕ РѕРґРЅРѕ РѕС‚РѕР±СЂР°Р¶РµРЅРёРµ
		
	}
	
	// Р°РґРјРёРЅРєР° - РіР»Р°РІРЅР°СЏ СЃС‚СЂР°РЅРёС†Р° СЃРѕ СЃРїРёСЃРєРѕРј
	function admin_index() {
		// С‚СѓС‚ РЅР°РґРѕ РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІР° РґРѕСЃС‚СѓРїР° С‚Р°Рє-С‚Рѕ...

		$this->set('users', $this->paginate('User'));
	}
	
	// Р°РґРјРёРЅРєР° - СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ 
	function admin_edit($id = null) {
		// РѕРїСЏС‚СЊ, РїСЂР°РІР°

		if (empty($this->data)) {
			// СЌС‚Рѕ РїСЂРѕСЃРјРѕС‚СЂ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ
			$this->User->id	= $id;
			$user			= $this->User->read();		
			$this->data		= $user;
		} else {
			// СЃРѕС…СЂР°РЅРµРЅРёРµ РґР°РЅРЅС‹С…
			$this->User->save($this->data);

			$this->redirect('/admin/users/');
		}
	}
	
	// Р°РґРјРёРЅРєР° - РґРѕР±Р°РІР»РµРЅРёРµ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ
	function admin_add() {
		// С‚РѕР»СЊРєРѕ РѕС‚РѕР±СЂР°Р¶РµРЅРёРµ
		// РІСЃРµ РґРµР№СЃС‚РІРёСЏ РІС‹РїРѕР»РЅСЏРµС‚ РјРµС‚РѕРґ admin_edit
		
	}
	
	// Р°РґРјРёРЅРєР° - СѓРґР°Р»РµРЅРёРµ
	function admin_del($id) {
		// РїСЂР°РІР°

		$this->User->id		= $id;
		$this->User->del();

		$this->redirect('/admin/users/');
	}
}
?>