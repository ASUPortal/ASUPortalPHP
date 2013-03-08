<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 22:19
 * To change this template use File | Settings | File Templates.
 */
class CNotificationManager {
    private static $_cacheTemplates = null;
    /**
     * @static
     * @return CArrayList
     */
    private static function getCacheTemplates() {
        if (is_null(self::$_cacheTemplates)) {
            self::$_cacheTemplates = new CArrayList();
        }
        return self::$_cacheTemplates;
    }
    /**
     * @static
     * @param $key
     * @return CNotificationTemplate
     */
    public static function getTemplate($key) {
        if (!self::getCacheTemplates()->hasElement($key)) {
            if (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_NOTIFICATION_TEMPLATES, $key);
                if (!is_numeric($item)) {
                    $template = new CNotificationTemplate($item);
                    self::getCacheTemplates()->add($template->getId(), $template);
                    self::getCacheTemplates()->add($template->alias, $template);
                }
            } else {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_NOTIFICATION_TEMPLATES, "alias='".$key."'")->getItems() as $item) {
                    $template = new CNotificationTemplate($item);
                    self::getCacheTemplates()->add($template->getId(), $template);
                    self::getCacheTemplates()->add($template->alias, $template);
                }
            }
        }
        return self::getCacheTemplates()->getItem($key);
    }
}
