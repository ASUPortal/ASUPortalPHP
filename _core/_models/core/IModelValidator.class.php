<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.12.13
 * Time: 16:50
 * To change this template use File | Settings | File Templates.
 */

interface IModelValidator{
    public function getError();
    public function onCreate(CModel $model);
    public function onRead(CModel $model);
    public function onUpdate(CModel $model);
    public function onDelete(CModel $model);
}