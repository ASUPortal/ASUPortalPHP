<?php

class Vkapi {

	public static function invoke ($name, array $params = array()) {
		$params['access_token'] = CSettingsManager::getSettingValue("access_token_vk");

		$content = file_get_contents('https://api.vk.com/method/'.$name.'?'.http_build_query($params));
		$result  = json_decode($content);

		return $result->response;
	}
	
}