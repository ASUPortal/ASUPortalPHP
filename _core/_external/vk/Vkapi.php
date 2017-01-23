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
		$params["access_token"] = CSettingsManager::getSettingValue("access_token_vk");
		
		// подключаем библиотеку curl с указанием proxy
		$proxy = CSettingsManager::getSettingValue("proxy_address");
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_PROXY, $proxy);
		
		curl_setopt($curl, CURLOPT_URL, 'https://api.vk.com/method/'.$name.'?'.http_build_query($params));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		// FALSE для остановки cURL от проверки сертификата узла сети (необходимо для запросов к адресам с протоколом https://)
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$results = curl_exec($curl);
		return $results;
	}
	
}