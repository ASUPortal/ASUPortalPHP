<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:54
 * To change this template use File | Settings | File Templates.
 */

class CGrantManager {
    private static $_cacheGrants = null;
    private static $_cacheAttachments = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheGrants() {
        if (is_null(self::$_cacheGrants)) {
            self::$_cacheGrants = new CArrayList();
        }
        return self::$_cacheGrants;
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheAttachments() {
        if (is_null(self::$_cacheAttachments)) {
            self::$_cacheAttachments = new CArrayList();
        }
        return self::$_cacheAttachments;
    }

    /**
     * @param $key
     * @return CGrant
     */
    public static function getGrant($key) {
        if (!self::getCacheGrants()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_GRANTS, $key);
            if (!is_null($ar)) {
                $grant = new CGrant($ar);
                self::getCacheGrants()->add($grant->getId(), $grant);
            }
        }
        return self::getCacheGrants()->getItem($key);
    }

    /**
     * @param $key
     * @return CGrantAttachment
     */
    public static function getAttachment($key) {
        if (!self::getCacheAttachments()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_GRANT_ATTACHMENTS, $key);
            if (!is_null($ar)) {
                $attach = new CGrantAttachment($ar);
                self::getCacheAttachments()->add($attach->getId(), $attach);
            }
        }
        return self::getCacheAttachments()->getItem($key);
    }

    /**
     * @param $key
     * @return CGrantEvent|null
     */
    public static function getEvent($key) {
        $event = null;
        $ar = CActiveRecordProvider::getById(TABLE_GRANT_EVENTS, $key);
        if (!is_null($ar)) {
            $event = new CGrantEvent($ar);
        }
        return $event;
    }

    /**
     * @param $key
     * @return CGrantOutgo|null
     */
    public static function getOutgo($key) {
        $outgo = null;
        $ar = CActiveRecordProvider::getById(TABLE_GRANT_OUTGOES, $key);
        if (!is_null($ar)) {
            $outgo = new CGrantOutgo($ar);
        }
        return $outgo;
    }
}