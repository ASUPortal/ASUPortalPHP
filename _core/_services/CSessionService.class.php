<?php

/**
 * Сервис по работе с сессией
 *
 */
class CSessionService {
	/**
	 * Наличие прав у пользователя только на чтение и запись своих записей
	 * 
	 * @return boolean
	 */
    public static function hasRoleReadAndWriteOwnOnly() {
    	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
    	CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * Наличие прав у пользователя только на чтение и запись всех записей
     * 
     * @return boolean
     */
    public static function hasRoleReadAndWriteAll() {
    	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_ALL or
    	CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL) {
    		return true;
    	} else {
    		return false;
    	}
    }
}