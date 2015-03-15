<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 22:36
 *
 * @property int _created_by
 * @property int _version_of
 * @property string _created_at
 */
interface IJSONSerializable {
    public function toJsonObject();
    public function updateWithJsonString($jsonString);
}