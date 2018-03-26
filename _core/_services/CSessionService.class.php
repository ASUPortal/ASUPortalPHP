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
		if (!is_null(CSession::getCurrentUser())) {
			if (in_array(CSession::getCurrentUser()->getLevelForCurrentTask(), $roles)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Проверка на наличие группы у пользователя
	 * 
	 * @param array $groups
	 * @return boolean
	 */
	public static function hasAnyUserGroup($groups = array()) {
		if (!is_null(CSession::getCurrentUser())) {
			$userGroups = array();
			foreach (CSession::getCurrentUser()->getGroups()->getItems() as $group) {
				$userGroups[$group->getId()] = $group->getName();
			}
			foreach ($groups as $group) {
				if (in_array($group, $userGroups)) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
		
	}
}