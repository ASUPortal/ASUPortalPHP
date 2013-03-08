<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 22:19
 * To change this template use File | Settings | File Templates.
 */
class CNotificationTemplate extends CActiveModel{
    public function createNotification() {
        $notification = CFactory::createNotification();
        $notification->subject = $this->subject;
        $notification->body = $this->body;
        return $notification;
    }
}
