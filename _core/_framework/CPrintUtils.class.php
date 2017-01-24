<?php

/**
 * Класс утилит для печати по шаблону
 *
 * Class CPrintUtils
 */
class CPrintUtils {
	/**
	 * Конвертация изображения в 64-разрядный код
	 *
	 * @param $img
	 * @return string
	 */
	public static function getBase64encodedImage($img) {
		$imageSize = getimagesize($img);
		$imageData = base64_encode(file_get_contents($img));
		$imageEncoded = 'data:'.$imageSize["mime"].';base64,'.$imageData.' "'.$imageSize[3];
		return $imageEncoded;
	}
}