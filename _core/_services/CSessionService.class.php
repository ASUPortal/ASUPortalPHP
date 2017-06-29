<?php

/**
 * Сервис по работе с сессией
 *
 */
class CSessionService {
	/**
	 * Проверка на наличие прав у пользователя
	 *
	 * @param array $roles
	 * @return boolean
	 */
	public static function hasAnyRole($roles = array()) {
		if (in_array(CSession::getCurrentUser()->getLevelForCurrentTask(), $roles)) {
			return true;
		} else {
			return false;
		}
	}
}