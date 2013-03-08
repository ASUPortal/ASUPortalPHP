<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 22:25
 * To change this template use File | Settings | File Templates.
 */
class CNotification extends CActiveModel {
    /**
     * Добавление строчки в тело уведомления
     *
     * @param $url
     */
    public function appendLine($url) {
        $this->body .= "\n\n".$url;
    }
    /**
     * Отправить письмо указанному человеку
     *
     * @param CPerson $person
     */
    public function email(CPerson $person) {
        $mail = new PHPMailer();
        $mail->From = ADMIN_EMAIL;
        $mail->FromName = "Администрация портала АСУ УГАТУ";
        $mail->AddAddress($person->e_mail, $person->getName());
        $mail->Subject = $this->subject;
        $mail->Body = $this->body;
        $mail->Send();
    }
}
