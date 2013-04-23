<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.04.13
 * Time: 12:20
 * To change this template use File | Settings | File Templates.
 */

class CSubscription extends CActiveModel{
    protected $_table = TABLE_SUBSCRIPTIONS;
    public $user_id;
    public $type_id = 1;
}