<?php
/**
 * Класс для работы с API ВКонтакте, подробнее здесь: https://vk.com/dev/first_guide
 *
 */
class Vkapi {

	/**
	 * Вызов метода API ВКонтакте, список методов можно посмотреть здесь: https://vk.com/dev/methods
	 * Для идентификации в API используется параметр access_token зарегистрированного Standalone-приложения
	 * 
	 * @param unknown $name
	 * @param array $params
	 */
	public static function invoke ($name, array $params = array()) {
		$params['access_token'] = CSettingsManager::getSettingValue("access_token_vk");

		$content = file_get_contents('https://api.vk.com/method/'.$name.'?'.http_build_query($params));
		$result  = json_decode($content);

		return $result->response;
	}
	
}